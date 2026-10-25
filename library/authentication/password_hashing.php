<?php
/**
 * Hashing and salt generation algorithms for use in password management.
 * 
 * <pre>
 * These are the algorithms used for password hashing, including generation of salt and selection
 * of a hashing algorithm.  crypt() with blowfish will be used on systems that support it.
 * If blowfish is not available, then SHA1 with a prepended salt will be used.
 * 
 * When a system without blowfish (e.g. PHP 5.2) is upgraded to one with support (e.g. php 5.3),
 * the passwords will continue to be stored and useable in SHA1 format until a user either changes
 * his own password, or an administrator issues a new password.
 * </pre>
 * Copyright (C) 2013 Kevin Yeh <kevin.y@integralemr.com> and OEMR <www.oemr.org>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package LibreEHR
 * @author  Kevin Yeh <kevin.y@integralemr.com>
 * @link    http://librehealth.io
 */

define("SALT_PREFIX_SHA1",'$SHA1$');

/**
 * 
 * Generate a salt to be used with the password_hash() function.
 * 
 * <pre>
 * This function checks for the availability of the preferred hashing algorithm (BLOWFISH)
 * on the system.  If it is available the salt returned is prefixed to indicate it is for BLOWFISH.
 * If it is not available, then SHA1 will be used instead.
 * 
 * See php documentation on crypt() for more details.
 * </pre>
 * 
 * 
 * @return type     The algorithm prefix + random data for salt.
 */
function oemr_password_salt()
{
    $Allowed_Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./';
    $Chars_Len = 63;
    $Salt_Length = 22;
    $salt = "";

    // Use cryptographically secure pseudo-random bytes
    if (function_exists('random_bytes')) {
        $bytes = random_bytes($Salt_Length);
        for ($i = 0; $i < $Salt_Length; $i++) {
            $salt .= $Allowed_Chars[ord($bytes[$i]) % 64];
        }
    } elseif (function_exists('openssl_random_pseudo_bytes')) {
        $bytes = openssl_random_pseudo_bytes($Salt_Length);
        for ($i = 0; $i < $Salt_Length; $i++) {
            $salt .= $Allowed_Chars[ord($bytes[$i]) % 64];
        }
    } else {
        for ($i = 0; $i < $Salt_Length; $i++) {
            $salt .= $Allowed_Chars[mt_rand(0, $Chars_Len)];
        }
    }

    // Preferred hashing mechanism: Blowfish with 10 rounds (HIPAA 45 CFR 164.312(d))
    $rounds = '10';
    $prefix = (defined('CRYPT_BLOWFISH') && CRYPT_BLOWFISH === 1) ? '$2y$' : '$2a$';
    return $prefix . $rounds . '$' . $salt . '$';
}

/**
 * Hash a plaintext password for comparison or initial storage.
 * 
 * <pre>
 * This function uses PHP crypt() with Blowfish ($2y$ or $2a$).
 * Legacy SHA-1 salts are maintained solely for migration verification.
 * </pre>
 * 
 * @param string $plaintext
 * @param string $salt
 * @return string
 */
function oemr_password_hash($plaintext,$salt)
{
    // If this is a legacy SHA1 salt, calculate for backward-compatibility verification
    if(strpos($salt,SALT_PREFIX_SHA1)===0)
    {
        error_log("SECURITY WARNING: Verifying password with legacy SHA-1 salt. Password should be updated to Blowfish/bcrypt.");
        return SALT_PREFIX_SHA1 . hash('sha1', $salt . $plaintext);
    }
    else { // Otherwise use PHP crypt() with Blowfish
        $crypt_return = crypt($plaintext,$salt);
        if ( ($crypt_return == '*0') || ($crypt_return == '*1') || (strlen($crypt_return) < 6) ) {
            // Error code returned by crypt or not hash, so die
            error_log("FATAL ERROR: crypt() function is not working correctly in LibreEHR");
            die("FATAL ERROR: crypt() function is not working correctly in LibreEHR");
        }
        else {
            // Hash confirmed, so return the hash.
            return $crypt_return;
        }
    }
}
?>
