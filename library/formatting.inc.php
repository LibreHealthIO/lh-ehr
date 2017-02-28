<?php
/*
 *
 * Copyright (C) 2016-2017 Terry Hill <teryhill@librehealth.io>
 * Copyright (C) 2010-2014 Rod Roark <rod@sunsetsystems.com>
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
 * along with this program. If not, see http://opensource.org/licenses/gpl-license.php.
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Rod Roark <rod@sunsetsystems.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 *
 */

function oeFormatMoney($amount, $symbol=false) {
  $s = number_format($amount,
    $GLOBALS['currency_decimals'],
    $GLOBALS['currency_dec_point'],
    $GLOBALS['currency_thousands_sep']);
  // If the currency symbol exists and is requested, prepend it.
  if ($symbol && !empty($GLOBALS['gbl_currency_symbol']))
    $s = $GLOBALS['gbl_currency_symbol'] . " $s";
  return $s;
}
function oeFormatDateForPrintReport($date)
{
    if ($date) {
        $dmy = preg_split("'[/.-]'", $date);
        if ($GLOBALS['date_display_format'] == 2) {
            $date = sprintf("%02u-%02u-%04u", $dmy[0], $dmy[1], $dmy[2]);
        }
        return $date;
    }
}
function prepareDateBeforeSave($date, $withTime = false)
{
    if (!$withTime) {
        $dmy = preg_split("'[/.-]'", $date);
        if ($GLOBALS['date_display_format'] == 1) {
            return sprintf("%04u-%02u-%02u", $dmy[2], $dmy[0], $dmy[1]);
        } elseif ($GLOBALS['date_display_format'] == 2) {
            return sprintf("%04u-%02u-%02u", $dmy[2], $dmy[1], $dmy[0]);
        } else {
            return $date;
        }
    } else {
        $dmy = preg_split("'[/.: -]'", $date);
        if ($GLOBALS['date_display_format'] == 1) {
            return sprintf("%04u-%02u-%02u %02u:%02u:%02u", $dmy[2], $dmy[0], $dmy[1], $dmy[3], $dmy[4], $dmy[5]);
        } elseif ($GLOBALS['date_display_format'] == 2) {
            return sprintf("%04u-%02u-%02u %02u:%02u:%02u", $dmy[2], $dmy[1], $dmy[0], $dmy[3], $dmy[4], $dmy[5]);
        } else {
            return $date;
        }
    }
}

function oeFormatShortDate($date='today') {
  if ($date === 'today') $date = date('Y-m-d');
  if (strlen($date) == 10) {
    // assume input is yyyy-mm-dd
    if ($GLOBALS['date_display_format'] == 1)      // mm/dd/yyyy
      $date = substr($date, 5, 2) . '/' . substr($date, 8, 2) . '/' . substr($date, 0, 4);
    else if ($GLOBALS['date_display_format'] == 2) // dd/mm/yyyy
      $date = substr($date, 8, 2) . '/' . substr($date, 5, 2) . '/' . substr($date, 0, 4);
  }
  return $date;
}
function oeFormatDateTime($date='today') {
  if ($date === 'today') $date = date('Y-m-d H:i:s');
  if (strlen($date) == 19) {
    // assume input is yyyy-mm-dd hh:mm:ss
    if ($GLOBALS['date_display_format'] == 1)      // mm/dd/yyyy hh:mm:ss
      $date = substr($date, 5, 2) . '/' . substr($date, 8, 2) . '/' . substr($date, 0, 4) . substr($date, 10, 9);
    else if ($GLOBALS['date_display_format'] == 2) // dd/mm/yyyy hh:mm:ss
      $date = substr($date, 8, 2) . '/' . substr($date, 5, 2) . '/' . substr($date, 0, 4) . substr($date, 10, 9);
  }
  return $date;
}

// 0 - Time format 24 hr
// 1 - Time format 12 hr
function oeFormatTime( $time, $format = "" ) 
{
    $formatted = $time;
    if ( $format == "" ) {
        $format = $GLOBALS['time_display_format'];
    }
    
    if ( $format == 0 ) {
        $formatted = date( "H:i", strtotime( $time ) ); 
    } else if ( $format == 1 ) {
        $formatted = date( "g:i a", strtotime( $time ) );       
    }
    
    return $formatted;
}

// Format short date from time.
function oeFormatSDFT($time) {
  return oeFormatShortDate(date('Y-m-d', $time));
}

// Format the body of a patient note.
function oeFormatPatientNote($note) {
  $i = 0;
  while ($i !== false) {
    if (preg_match('/^\d\d\d\d-\d\d-\d\d/', substr($note, $i))) {
      $note = substr($note, 0, $i) . oeFormatShortDate(substr($note, $i, 10)) . substr($note, $i + 10);
    }
    $i = strpos("\n", $note, $i);
    if ($i !== false) ++$i;
  }
  return $note;
}

function oeFormatClientID($id) {

  // TBD

  return $id;
}
//----------------------------------------------------
function DateFormatRead($withTime = false)
 {
     // For the 3 supported date format,the javascript code also should be twicked to display the date as per it.
     // Output of this function is given to 'ifFormat' parameter of the 'Calendar.setup'.
     // This will show the date as per the global settings.
     if ($GLOBALS['date_display_format'] == 0) {
         return ($withTime) ? "Y-m-d H:i" : "Y-m-d";
     } else if ($GLOBALS['date_display_format'] == 1) {
         return ($withTime) ? "m/d/Y H:i" : "m/d/Y";
     } else if ($GLOBALS['date_display_format'] == 2) {
         return ($withTime) ? "d/m/Y H:i" : "d/m/Y";
     }
 }
 
function DateFormatReadold()
 {//For the 3 supported date format,the javascript code also should be twicked to display the date as per it.
  //Output of this function is given to 'ifFormat' parameter of the 'Calendar.setup'.
  //This will show the date as per the global settings.
    if($GLOBALS['date_display_format']==0)
     {
      return "%Y-%m-%d";
     }
    else if($GLOBALS['date_display_format']==1)
     {
      return "%m/%d/%Y";
     }
    else if($GLOBALS['date_display_format']==2)
     {
      return "%d/%m/%Y";
     }
 }
function DateToYYYYMMDD($DateValue)
 {//With the help of function DateFormatRead() now the user can enter date is any of the 3 formats depending upon the global setting.
 //But in database the date can be stored only in the yyyy-mm-dd format.
 //This function accepts a date in any of the 3 formats, and as per the global setting, converts it to the yyyy-mm-dd format.
    if(trim($DateValue)=='')
     {
      return '';
     }
     
    if($GLOBALS['date_display_format']==0)
     {
      return $DateValue;
     }
    else if($GLOBALS['date_display_format']==1 || $GLOBALS['date_display_format']==2)
     {
      $DateValueArray=explode('/',$DateValue);
      if($GLOBALS['date_display_format']==1)
       {
          return $DateValueArray[2].'-'.$DateValueArray[0].'-'.$DateValueArray[1];
       }
      if($GLOBALS['date_display_format']==2)
       {
          return $DateValueArray[2].'-'.$DateValueArray[1].'-'.$DateValueArray[0];
       }
     }
 }

 function getLocaleCodeForDisplayLanguage($name){
     # need to come up with a better way of doing this TLH
    if(preg_match('/\s/', $name)){
        $name = substr($name, 0, strrpos($name, ' '));
    }
    $languageCodes = array(
        "aa" => "Afar",
        "ab" => "Abkhazian",
        "ae" => "Avestan",
        "af" => "Afrikaans",
        "ak" => "Akan",
        "am" => "Amharic",
        "an" => "Aragonese",
        "ar" => "Arabic",
        "as" => "Assamese",
        "av" => "Avaric",
        "ay" => "Aymara",
        "az" => "Azerbaijani",
        "ba" => "Bashkir",
        "be" => "Belarusian",
        "bg" => "Bulgarian",
        "bh" => "Bihari",
        "bi" => "Bislama",
        "bm" => "Bambara",
        "bn" => "Bengali",
        "bo" => "Tibetan",
        "br" => "Breton",
        "bs" => "Bosnian",
        "ca" => "Catalan",
        "ce" => "Chechen",
        "ch" => "Chamorro",
        "co" => "Corsican",
        "cr" => "Cree",
        "cs" => "Czech",
        "cu" => "Church Slavic",
        "cv" => "Chuvash",
        "cy" => "Welsh",
        "da" => "Danish",
        "de" => "German",
        "dv" => "Divehi",
        "dz" => "Dzongkha",
        "ee" => "Ewe",
        "el" => "Greek",
        "en" => "English",
        "eo" => "Esperanto",
        "es" => "Spanish",
        "et" => "Estonian",
        "eu" => "Basque",
        "fa" => "Persian",
        "ff" => "Fulah",
        "fi" => "Finnish",
        "fj" => "Fijian",
        "fo" => "Faroese",
        "fr" => "French",
        "fy" => "Western Frisian",
        "ga" => "Irish",
        "gd" => "Scottish Gaelic",
        "gl" => "Galician",
        "gn" => "Guarani",
        "gu" => "Gujarati",
        "gv" => "Manx",
        "ha" => "Hausa",
        "he" => "Hebrew",
        "hi" => "Hindi",
        "ho" => "Hiri Motu",
        "hr" => "Croatian",
        "ht" => "Haitian",
        "hu" => "Hungarian",
        "hy" => "Armenian",
        "hz" => "Herero",
        "ia" => "Interlingua (International Auxiliary Language Association)",
        "id" => "Indonesian",
        "ie" => "Interlingue",
        "ig" => "Igbo",
        "ii" => "Sichuan Yi",
        "ik" => "Inupiaq",
        "io" => "Ido",
        "is" => "Icelandic",
        "it" => "Italian",
        "iu" => "Inuktitut",
        "ja" => "Japanese",
        "jv" => "Javanese",
        "ka" => "Georgian",
        "kg" => "Kongo",
        "ki" => "Kikuyu",
        "kj" => "Kwanyama",
        "kk" => "Kazakh",
        "kl" => "Kalaallisut",
        "km" => "Khmer",
        "kn" => "Kannada",
        "ko" => "Korean",
        "kr" => "Kanuri",
        "ks" => "Kashmiri",
        "ku" => "Kurdish",
        "kv" => "Komi",
        "kw" => "Cornish",
        "ky" => "Kirghiz",
        "la" => "Latin",
        "lb" => "Luxembourgish",
        "lg" => "Ganda",
        "li" => "Limburgish",
        "ln" => "Lingala",
        "lo" => "Lao",
        "lt" => "Lithuanian",
        "lu" => "Luba-Katanga",
        "lv" => "Latvian",
        "mg" => "Malagasy",
        "mh" => "Marshallese",
        "mi" => "Maori",
        "mk" => "Macedonian",
        "ml" => "Malayalam",
        "mn" => "Mongolian",
        "mr" => "Marathi",
        "ms" => "Malay",
        "mt" => "Maltese",
        "my" => "Burmese",
        "na" => "Nauru",
        "nb" => "Norwegian Bokmal",
        "nd" => "North Ndebele",
        "ne" => "Nepali",
        "ng" => "Ndonga",
        "nl" => "Dutch",
        "nn" => "Norwegian Nynorsk",
        "no" => "Norwegian",
        "nr" => "South Ndebele",
        "nv" => "Navajo",
        "ny" => "Chichewa",
        "oc" => "Occitan",
        "oj" => "Ojibwa",
        "om" => "Oromo",
        "or" => "Oriya",
        "os" => "Ossetian",
        "pa" => "Panjabi",
        "pi" => "Pali",
        "pl" => "Polish",
        "ps" => "Pashto",
        "pt" => "Portuguese",
        "qu" => "Quechua",
        "rm" => "Raeto-Romance",
        "rn" => "Kirundi",
        "ro" => "Romanian",
        "ru" => "Russian",
        "rw" => "Kinyarwanda",
        "sa" => "Sanskrit",
        "sc" => "Sardinian",
        "sd" => "Sindhi",
        "se" => "Northern Sami",
        "sg" => "Sango",
        "si" => "Sinhala",
        "sk" => "Slovak",
        "sl" => "Slovenian",
        "sm" => "Samoan",
        "sn" => "Shona",
        "so" => "Somali",
        "sq" => "Albanian",
        "sr" => "Serbian",
        "ss" => "Swati",
        "st" => "Southern Sotho",
        "su" => "Sundanese",
        "sv" => "Swedish",
        "sw" => "Swahili",
        "ta" => "Tamil",
        "te" => "Telugu",
        "tg" => "Tajik",
        "th" => "Thai",
        "ti" => "Tigrinya",
        "tk" => "Turkmen",
        "tl" => "Tagalog",
        "tn" => "Tswana",
        "to" => "Tonga",
        "tr" => "Turkish",
        "ts" => "Tsonga",
        "tt" => "Tatar",
        "tw" => "Twi",
        "ty" => "Tahitian",
        "ug" => "Uighur",
        "uk" => "Ukrainian",
        "ur" => "Urdu",
        "uz" => "Uzbek",
        "ve" => "Venda",
        "vi" => "Vietnamese",
        "vo" => "Volapuk",
        "wa" => "Walloon",
        "wo" => "Wolof",
        "xh" => "Xhosa",
        "yi" => "Yiddish",
        "yo" => "Yoruba",
        "za" => "Zhuang",
        "zh" => "Chinese",
        "zu" => "Zulu"
    );
    return array_search($name, $languageCodes);
}

// Returns age in a desired format:
//   0 = "xx month(s)" if < 2 years, else years
//   1 = Years      : just a number
//   2 = Months     : just a number
//   3 = Gestational: "xx week(s) y day(s)"
// $dobYMD is YYYYMMDD or YYYY-MM-DD
// $nowYMD is same format but optional
//
function oeFormatAge($dobYMD, $nowYMD='', $format=0) {
  // Strip any dashes from the dates.
  $dobYMD = preg_replace('/-/', '', $dobYMD);
  $nowYMD = preg_replace('/-/', '', $nowYMD);
  $dobDay   = substr($dobYMD,6,2);
  $dobMonth = substr($dobYMD,4,2);
  $dobYear  = substr($dobYMD,0,4);

  if ($nowYMD) {
    $nowDay   = substr($nowYMD,6,2);
    $nowMonth = substr($nowYMD,4,2);
    $nowYear  = substr($nowYMD,0,4);
  }
  else {
    $nowDay   = date("d");
    $nowMonth = date("m");
    $nowYear  = date("Y");
  }

  if ($format == 3) {
    // Gestational age as weeks and days.
    $secs = mktime(0, 0, 0, $nowMonth, $nowDay, $nowYear) -
            mktime(0, 0, 0, $dobMonth, $dobDay, $dobYear);
    $days  = intval($secs / (24 * 60 * 60));
    $weeks = intval($days / 7);
    $days  = $days % 7;
    $age   = "$weeks " . ($weeks == 1 ? xl('week') : xl('weeks')) .
             " $days " . ($days  == 1 ? xl('day' ) : xl('days' ));
  }
  else {
    // Years or months.
    $dayDiff   = $nowDay   - $dobDay;
    $monthDiff = $nowMonth - $dobMonth;
    $yearDiff  = $nowYear  - $dobYear;
    $ageInMonths = $yearDiff * 12 + $monthDiff;
    if ($dayDiff < 0) --$ageInMonths;
    if ($format == 1 || ($format == 0 && $ageInMonths >= 24)) {
      $age = $yearDiff;
      if ($monthDiff < 0 || ($monthDiff == 0 && $dayDiff < 0)) --$age;
    }
    else {
      $age = $ageInMonths;
      if ($format == 0) {
        $age .= ' ' . $ageInMonths == 1 ? xl('month') : xl('months'); 
      }
    }
  }

  return $age;
}
?>
