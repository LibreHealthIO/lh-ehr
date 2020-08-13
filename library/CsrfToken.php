<?php

class CsrfToken {

    // Generate csrf token for authentication
    function generateCsrfToken()
    {
        if (!extension_loaded('openssl')) {
        error_log("ERROR: openssl extension not enabled, needed for proper functioning of LibreHealth");
            die("Error: Systems needs openssl.");
        }

        $csrfToken = bin2hex(openssl_random_pseudo_bytes(32));

        if (empty($csrfToken)) {
            error_log("ERROR : Token generation failed");
            die("Error : Unable to correctly generate token");
        }

        return $csrfToken;
    }

    // Function to verify a csrf token
    function verifyCsrfToken($token)
    {
        if (hash_equals($_SESSION['token'], $token)) {
            return true;
        } else {
            error_log("WARNING : Malicious attempt encountered");
            return false;
        }
    }

    //log error and kill the page
    function noTokenFoundError() {
        error_log('WARNING: A POST request detected with no csrf token found');
        header('HTTP/1.1 401 Unauthorized');
        die('Authentication failed.');
    }
    function incorrectToken() {
        header('HTTP/1.1 401 Unauthorized');
        die('Authentication failed.');
    }
    // Function to verify a csrf token using with second token
    function verifyCsrfTokenAndCompareHash($token, $secondToken)
    {
        if (hash_equals(hash_hmac('sha256', (string) $secondToken, (string) $_SESSION['token']), $token)) {
            return true;
        } else {
            error_log("WARNING : Malicious attempt encountered");
            return false;
        }
    }
}

?>