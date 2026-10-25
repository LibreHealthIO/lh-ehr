<?php
// Copyright (C) 2005 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This class exports billing information to an external billing
// system.  In this case we are writing a custom CSV format, but
// it would be easy and more generally useful to write X12 (837p)
// format and then have some separate utilities for converting to
// HCFA 1500, UB-92, etc.
//
// To implement this feature, rename this file to BillingExport.php.

require_once (dirname(__FILE__) . "/../library/sql.inc");

class BillingExport {

  // You should customize these paths.  They must share the same
  // physical disk partition so that the final rename will be an
  // atomic operation.
  var $TMP_DIR    = "/home/billing/tmp";
  var $TARGET_DIR = "/home/billing/ftp";

  var $tmpname; // output filename including path
  var $tmpfh;   // output file handle

  function fixString($string) {
    return addslashes(trim($string));
  }

  function fixMI($string) {
    return addslashes(substr(trim($string), 0, 1));
  }

  function fixSex($sex) {
    $sex = substr(strtoupper(trim($sex)), 0, 1);
    if ($sex == 'M') return 'Male';
    if ($sex == 'F') return 'Female';
    return '';
  }

  function fixPhone($phone) {
    $tmparr = array();
    if (preg_match("/(\d\d\d)\D*(\d\d\d)\D*(\d\d\d\d)/", $phone, $tmparr))
      return $tmparr[1] . '-' . $tmparr[2] . '-' . $tmparr[3];
    return '';
  }

  function fixSSN($ssn) {
    $tmparr = array();
    if (preg_match("/(\d\d\d)\D*(\d\d)\D*(\d\d\d\d)/", $ssn, $tmparr))
      return $tmparr[1] . '-' . $tmparr[2] . '-' . $tmparr[3];
    return '';
  }

  function fixMStatus($status) {
    return ucfirst(trim($status));
  }

  function fixEStatus($employer) {
    $status = strtoupper(trim($employer));
    if (! $status) return '';
    if ($status == 'STUDENT') return 'Student';
    if ($status == 'RETIRED') return 'Retired';
    return 'Full-time';
  }

  function fixRelation($rel) {
    return ucfirst(trim($rel));
  }

  function fixCPT($code, $mod) {
    $code = trim($code);
    $mod = trim($mod);
    if ($mod) $code .= '-' . $mod;
    return addslashes($code);
  }

  var $encryption_key = null; // Optional custom encryption key

  function fixJust($str) {
    return addslashes(trim(str_replace(':', ' ', $str)));
  }

  function fixDate($date) {
    return substr($date, 0, 10);
  }

  // Get or derive the encryption key for HIPAA-compliant PHI export at rest (45 CFR 164.312(a)(2)(iv))
  private function getEncryptionKey() {
    if (!empty($this->encryption_key)) {
      return hash('sha256', $this->encryption_key, true);
    }
    if (!empty($GLOBALS['oer_config']['billing']['export_encryption_key'])) {
      return hash('sha256', $GLOBALS['oer_config']['billing']['export_encryption_key'], true);
    }
    // Fallback to site-specific key derived from database credentials/site config
    $seed = (isset($GLOBALS['OE_SITE_DIR']) ? $GLOBALS['OE_SITE_DIR'] : '') . (isset($GLOBALS['dbase']) ? $GLOBALS['dbase'] : 'lh_billing');
    return hash('sha256', $seed, true);
  }

  // Encrypt plaintext data using AES-256-CBC
  private function encryptData($plaintext) {
    if (function_exists('openssl_encrypt')) {
      $key = $this->getEncryptionKey();
      $iv_len = openssl_cipher_iv_length('aes-256-cbc');
      $iv = openssl_random_pseudo_bytes($iv_len);
      $encrypted = openssl_encrypt($plaintext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
      return "ENC::AES256::" . base64_encode($iv . $encrypted);
    }
    // If OpenSSL is somehow unavailable, return false
    return false;
  }

  // Creating a BillingExport object opens the output file with restricted permissions.
  // Filename format is "transYYYYMMDDHHMMSS.txt".
  //
  function BillingExport($key = null) {
    if ($key !== null) {
      $this->encryption_key = $key;
    }
    if (!is_dir($this->TMP_DIR)) {
      @mkdir($this->TMP_DIR, 0700, true);
    }
    $this->tmpname = $this->TMP_DIR . '/trans' . date("YmdHis") . '.txt';
    $this->tmpfh = fopen($this->tmpname, 'w');
    if ($this->tmpfh) {
      chmod($this->tmpname, 0600); // HIPAA: Restrict access to owner only
    }
  }

  // Call this once for each claim to be processed.
  //
  function addClaim($patient_id, $encounter) {
    $patient_id = (int)$patient_id;
    $encounter  = (int)$encounter;

    // Patient information (parameterized to prevent SQL injection):

    $query = "SELECT p.pid, p.ss, p.lname, p.fname, p.mname, p.DOB, " .
      "p.street, p.city, p.state, p.postal_code, p.phone_home, p.phone_biz, " .
      "p.status, p.sex, e.name " .
      "FROM patient_data AS p " .
      "LEFT OUTER JOIN employer_data AS e ON e.pid = ? " .
      "WHERE p.pid = ? " .
      "LIMIT 1";
    $prow = sqlQuery($query, array($patient_id, $patient_id));

    // Patient line.
    fwrite($this->tmpfh, 'PT' .
      ',"' . $this->fixString($prow['pid'])         . '"' .
      ',"' . $this->fixString($prow['lname'])       . '"' .
      ',"' . $this->fixString($prow['fname'])       . '"' .
      ',"' . $this->fixMI($prow['mname'])           . '"' .
      ',"' . $this->fixString($prow['street'])      . '"' .
      ',""'                                  .
      ',"' . $this->fixString($prow['city'])        . '"' .
      ',"' . $this->fixString($prow['state'])       . '"' .
      ',"' . $this->fixString($prow['postal_code']) . '"' .
      ',"' . $this->fixPhone($prow['phone_home'])   . '"' .
      ',"' . $this->fixPhone($prow['phone_biz'])    . '"' .
      ',"' . $this->fixSex($prow['sex'])            . '"' .
      ',"' . $prow['DOB']                           . '"' .
      ',"' . $this->fixSSN($prow['ss'])             . '"' .
      ',"' . $this->fixEStatus($prow['name'])       . '"' .
      ',"' . $this->fixString($prow['name'])        . '"' .
    "\n");

    // Encounter information (parameterized):

    $query = "SELECT e.date, e.facility, " .
      "u.id, u.lname, u.fname, u.mname, u.upin, " .
      "f.street, f.city, f.state, f.postal_code, f.pos_code, " .
      "f.domain_identifier AS clia_code " .
      "FROM form_encounter AS e " .
      "LEFT OUTER JOIN forms ON forms.formdir = 'patient_encounter' AND " .
      "forms.form_id = e.id AND forms.pid = ? " .
      "LEFT OUTER JOIN users AS u ON u.username = forms.user " .
      "LEFT OUTER JOIN facility AS f ON f.name = e.facility " .
      "WHERE e.pid = ? AND e.encounter = ? " .
      "LIMIT 1";
    $erow = sqlQuery($query, array($patient_id, $patient_id, $encounter));

    // Performing Provider line.
    fwrite($this->tmpfh, 'PP' .
      ',"' . $this->fixString($erow['lname'])       . '"' .
      ',"' . $this->fixString($erow['fname'])       . '"' .
      ',"' . $this->fixMI($erow['mname'])           . '"' .
      ',"' . $this->fixString($erow['upin'])        . '"' .
    "\n");

    // TBD: Referring Provider line when we have such a thing.

    // Insurance information, up to 3 lines (parameterized):

    $query = "SELECT " .
      "d.type, d.policy_number, d.group_number, " .
      "d.subscriber_lname, d.subscriber_fname, d.subscriber_mname, " .
      "d.subscriber_street, d.subscriber_city, d.subscriber_state, " .
      "d.subscriber_postal_code, d.subscriber_DOB, d.subscriber_sex, " .
      "d.subscriber_relationship, " .
      "c.name, " .
      "a.line1, a.line2, a.city, a.state, a.zip, " .
      "p.area_code, p.prefix, p.number, " .
      "n.provider_number " .
      "FROM insurance_data AS d " .
      "LEFT OUTER JOIN insurance_companies AS c ON c.id = d.provider " .
      "LEFT OUTER JOIN addresses AS a ON a.foreign_id = c.id " .
      "LEFT OUTER JOIN phone_numbers AS p ON p.foreign_id = c.id AND p.type = 2 " .
      "LEFT OUTER JOIN insurance_numbers AS n ON n.provider_id = ? " .
      " AND n.insurance_company_id = c.id " .
      "WHERE d.pid = ? AND d.provider != '' " .
      "ORDER BY d.type ASC, d.date DESC";
    $ires = sqlStatement($query, array($erow['id'], $patient_id));

    $prev_type = '?';
    while ($irow = sqlFetchArray($ires)) {
      if (strcmp($irow['type'], $prev_type) == 0) continue;
      $prev_type = $irow['type'];

      fwrite($this->tmpfh, 'IN' .
        ',"' . $this->fixString($irow['subscriber_lname'])          . '"' .
        ',"' . $this->fixString($irow['subscriber_fname'])          . '"' .
        ',"' . $this->fixMI($irow['subscriber_mname'])              . '"' .
        ',"' . $this->fixString($irow['subscriber_street'])         . '"' .
        ',"' . $this->fixString($irow['subscriber_city'])           . '"' .
        ',"' . $this->fixString($irow['subscriber_state'])          . '"' .
        ',"' . $this->fixString($irow['subscriber_postal_code'])    . '"' .
        ',"' . $irow['subscriber_DOB']                              . '"' .
        ',"' . $this->fixRelation($irow['subscriber_relationship']) . '"' .
        ',"' . $this->fixString($irow['policy_number'])             . '"' .
        ',"' . $this->fixString($irow['group_number'])              . '"' .
        ',"' . $this->fixString($irow['name'])                      . '"' .
        ',"' . $this->fixString($irow['line1'])                     . '"' .
        ',"' . $this->fixString($irow['line2'])                     . '"' .
        ',"' . $this->fixString($irow['city'])                      . '"' .
        ',"' . $this->fixString($irow['state'])                     . '"' .
        ',"' . $this->fixString($irow['zip'])                       . '"' .
        ',"' . $this->fixPhone($irow['area_code'] . $irow['prefix'] . $irow['number']) . '"' .
        ',"' . $this->fixString($irow['provider_number'])           . '"' .
        ',"' . $this->fixString($irow['provider_number'])           . '"' . // TBD: referring provider
      "\n");
    }

    // Procedure information (parameterized):

    $query = "SELECT id, code, modifier, justify " .
      "FROM billing " .
      "WHERE pid = ? AND encounter = ? " .
      "AND activity = 1 AND code_type = 'CPT4' " .
      "ORDER BY id";
    $bres = sqlStatement($query, array($patient_id, $encounter));

    while ($brow = sqlFetchArray($bres)) {
      fwrite($this->tmpfh, 'PR' .
        ',"' . $this->fixCPT($brow['code'], $brow['modifier']) . '"' .
        ',"' . $this->fixJust($brow['justify'])                . '"' .
        ',"' . $this->fixDate($erow['date'])                   . '"' .
        ',"' . $this->fixString($erow['pos_code'])             . '"' .
        ',"' . $this->fixString($erow['clia_code'])            . '"' .
        ',"' . $this->fixString($erow['facility'])             . '"' .
        ',"' . $this->fixString($erow['street'])               . '"' .
        ',""'                                           .
        ',"' . $this->fixString($erow['city'])                 . '"' .
        ',"' . $this->fixString($erow['state'])                . '"' .
        ',"' . $this->fixString($erow['postal_code'])          . '"' .
      "\n");
    }
  }

  // Close the output file, encrypt PHI content for HIPAA compliance, and move to ftp area.
  //
  function close() {
    fclose($this->tmpfh);

    // Read the unencrypted plaintext content and encrypt it
    $plaintext = file_get_contents($this->tmpname);
    $encrypted = $this->encryptData($plaintext);

    if (!is_dir($this->TARGET_DIR)) {
      @mkdir($this->TARGET_DIR, 0700, true);
    }

    if ($encrypted !== false) {
      // Overwrite temporary file with encrypted ciphertext
      file_put_contents($this->tmpname, $encrypted);
      $target_file = $this->TARGET_DIR . '/' . basename($this->tmpname) . '.enc';
    } else {
      $target_file = $this->TARGET_DIR . '/' . basename($this->tmpname);
    }

    chmod($this->tmpname, 0600); // Restrict to owner only (45 CFR 164.312(a)(2)(iv))
    rename($this->tmpname, $target_file);
    chmod($target_file, 0600);
  }
}

?>
