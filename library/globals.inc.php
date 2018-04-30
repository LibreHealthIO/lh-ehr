<?php
// Copyright (C) 2016-2017 Tony McCormick <tony@mi-squared.com>
// Copyright (C) 2010-2015 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
// Modified by dozens of contributors

/* @package LibreHealth EHR
 * @author Rod Roark <rod@sunsetsystems.com>
 * @author Tony McCormick <tony@mi-squared.com>
 * @author Terry Hill <teryhill@librehealth.io>
 *
 * @link http://librehealth.io
 */

// REQUIRED FOR TRANSLATION ENGINE.  DO NOT REMOVE
//  Current supported languages:    // Allow capture of term for translation:
//   Albanian                       // xl('Albanian')
//   Amharic                        // xl('Amharic')
//   Arabic                         // xl('Arabic')
//   Armenian                       // xl('Armenian')
//   Bahasa Indonesia               // xl('Bahasa Indonesia')
//   Bengali                        // xl('Bengali')
//   Bosnian                        // xl('Bosnian')
//   Chinese (Simplified)           // xl('Chinese (Simplified)')
//   Chinese (Traditional)          // xl('Chinese (Traditional)')
//   Croatian                       // xl('Croatian')
//   Czech                          // xl('Czech')
//   Danish                         // xl('Danish')
//   Dutch                          // xl('Dutch')
//   English (Indian)               // xl('English (Indian)')
//   English (Standard)             // xl('English (Standard)')
//   Estonian                       // xl('Estonian')
//   Finnish                        // xl('Finnish')
//   French                         // xl('French (Standard)')
//   French                         // xl('French (Canadian)')
//   Georgian                       // xl('Georgian')
//   German                         // xl('German')
//   Greek                          // xl('Greek')
//   Hebrew                         // xl('Hebrew')
//   Hindi                          // xl('Hindi')
//   Hungarian                      // xl('Hungarian')
//   Italian                        // xl('Italian')
//   Japanese                       // xl('Japanese')
//   Korean                         // xl('Korean')
//   Lithuanian                     // xl('Lithuanian')
//   Marathi                        // xl('Marathi')
//   Mongolian                      // xl('Mongolian')
//   Norwegian                      // xl('Norwegian')
//   Persian                        // xl('Persian')
//   Polish                         // xl('Polish')
//   Portuguese (Brazilian)         // xl('Portuguese (Brazilian)')
//   Portuguese (European)          // xl('Portuguese (European)')
//   Romanian                       // xl('Romanian')
//   Russian                        // xl('Russian')
//   Serbian                        // xl('Serbian')
//   Sinhala                        // xl('Sinhala')
//   Slovak                         // xl('Slovak')
//   Somali                         // xl('Somali')
//   Spanish (Latin American)       // xl('Spanish (Latin American)')
//   Spanish (Spain)                // xl('Spanish (Spain)')
//   Swedish                        // xl('Swedish')
//   Turkish                        // xl('Turkish')
//   Ukrainian                      // xl('Ukrainian')
//   Vietnamese                     // xl('Vietnamese')

// OS-dependent stuff.
if (stristr(PHP_OS, 'WIN')) {
  // MS Windows
  $mysql_bin_dir       = 'C:/xampp/mysql/bin';
  $perl_bin_dir        = 'C:/xampp/perl/bin';
  $temporary_files_dir = 'C:/windows/temp';
  $backup_log_dir      = 'C:/windows/temp';
} else {
  // Everything else
  $mysql_bin_dir       = '/usr/bin';
  $perl_bin_dir        = '/usr/bin';
  $sql_admin_tool_url  = 'http://localhost/phpmyadmin';
  $temporary_files_dir = '/tmp';
  $backup_log_dir      = '/tmp';
}

// REQUIRED FOR TRANSLATION ENGINE.  DO NOT REMOVE
// Language constant declarations:
// xl('Appearance')
// xl('Locale')
// xl('Features')
// xl('Report')
// xl('Billing')
// xl('Claim')
// xl('Esign')
// xl('Documents')
// xl('Calendar')
// xl('Security')
// xl('Notifications')
// xl('CDR')
// xl('Logging')
// xl('Miscellaneous')
// xl('Portal')
// xl('Connectors')
// xl('RX')
// xl('PDF')
// List of user specific tabs and globals
$USER_SPECIFIC_TABS = array('Appearance',
                            'Locale',
                            'Report',
                            'Encounter',
                            'Claim',
                            'Demographic',
                            'Calendar',
                            'Connectors');
$USER_SPECIFIC_GLOBALS = array('default_tab_1',
                               'default_tab_2',
                               'css_header',
                               'primary_color',
                               'primary_font_color',
                               'secondary_color',
                               'secondary_font_color',
                               'menu_styling_tabs',
                               'gbl_pt_list_page_size',
                               'gbl_pt_list_new_window',
                               'default_encounter_view',
                               'units_of_measurement',
                               'us_weight_format',
                               'date_display_format',
                               'time_display_format',
                               'ledger_begin_date',
                               'print_next_appointment_on_ledger',
                               'calendar_view_type',
                               'calendar_refresh_freq',
                               'check_appt_time',
                               'event_color',
                               'pat_trkr_timer',
                               'ptkr_visit_reason',
                               'ptkr_show_pid',
                               'ptkr_show_room',
                               'ptkr_date_range',
                               'ptkr_end_date',
                               'ptkr_show_visit_type',
                               'ptkr_show_encounter',
                               'ptkr_flag_dblbook',
                               'status_default',
                               'checkout_roll_off',
                               'ptkr_pt_list_new_window',
                               'erx_import_status_message',
                               'floating_message_alerts',
                               'floating_message_alerts_timer',
                               'floating_message_alerts_allergies',
                               'ubtop_margin_default',
                               'ubleft_margin_default',
                               'cms_top_margin_default',
                               'cms_left_margin_default');

$GLOBALS_METADATA = array(

  // Appearance Tab
  //
  'Appearance' => array(

    'default_tab_1' => array(
      xl('Default First Tab'),       // descriptive name
      array(
        '/interface/main/main_info.php' => xl('Calendar Screen'),
        '/interface/main/finder/dynamic_finder.php' => xl('Dynamic Finder'),
        '/interface/new/new.php' => xl('Patient Add/Search'),
        '/interface/patient_tracker/patient_tracker.php?skip_timeout_reset=1' => xl('Patient Flow Board'),
        '/interface/main/messages/messages.php?form_active=1' => xl("Messages"),
      ),
       '/interface/main/main_info.php',                 // default = calendar
      xl('First TAB on the left')
    ),


    'default_tab_2' => array(
      xl('Default Second Tab'),       // descriptive name
      array(
        '/interface/main/messages/messages.php?form_active=1' => xl("Messages"),
        '/interface/main/finder/dynamic_finder.php' => xl('Dynamic Finder'),
        '/interface/new/new.php' => xl('Patient Add/Search'),
        '/interface/patient_tracker/patient_tracker.php?skip_timeout_reset=1' => xl('Patient Flow Board'),
        '/interface/main/main_info.php' => xl('Calendar Screen'),
      ),
      '/interface/main/messages/messages.php?form_active=1',    // default = Messages
      xl('Second TAB on the left')
  ),

    'menu_styling_tabs' => array(
      xl('Role-based Menu'),
      array(
        'AnsServ' =>xl('Answering Service'),
        'Front Office' =>xl('Front Office'),
        'Clinical Staff'  =>xl('Clinical Staff'),
        /**
          * Menu additions :
          * Add line below here and populate menu_trees with its menu
          * 'Back Office' =>xl('Back Office')
          * Priviedges to access a menu are handled later
          */
        'Administrators' =>xl('Administrator'),
        'Default' =>xl('Default'),
      ),
      'Administrator',
      xl('Choose Application Menu: Role-based by work flows')
    ),

    'css_header' => array(
      xl('Theme'),
       'css',
       'style_light.css',
      xl('Pick a CSS theme.')
    ),
    'primary_color'=>array(xl('Primary color'),  'color', '#ffffff'),
    'primary_font_color'=>array(xl('Primary font color'),  'color', '#000000'),
    'secondary_color'=>array(xl('Secondary color'),  'color', '#ffffff'),
    'secondary_font_color'=>array(xl('Secondary font color'),  'color', '#000000'),

    'libreehr_name' => array(
      xl('Application Title'),
       'text',
       'LibreHealth EHR',
      xl('Application name for login page and main window title.')
    ),

    'full_new_patient_form' => array(
      xl('New Patient Form'),
      array(
        '1' => xl('All demographics fields, with search and duplication check'),
        '2' => xl('Mandatory or specified fields only, search and dup check'),
        '3' => xl('Mandatory or specified fields only, dup check, no search'),
      ),
       '1',                              // default
      xl('Style of form used for adding new patients')
    ),

    'patient_search_results_style' => array(
      xl('Patient Search Results Style'),
      array(
        '0' => xl('Encounter statistics'),
        '1' => xl('Mandatory and specified fields'),
      ),
       '0',                              // default
      xl('Type of columns displayed for patient search results')
    ),


    'simplified_demographics' => array(
      xl('Simplified Demographics'),
       'bool',                          // data type
       '0',                             // default = false
      xl('Omit insurance and some other things from the demographics form')
    ),

    'simplified_prescriptions' => array(
      xl('Simplified Prescriptions'),
       'bool',                          // data type
       '0',                             // default = false
      xl('Omit form, route and interval which then become part of dosage')
    ),

    'simplified_copay' => array(
      xl('Simplified Co-Pay'),
       'bool',                          // data type
       '0',                             // default = false
      xl('Omit method of payment from the co-pay panel')
    ),


    // TajEmo Work BY CB 2012/06/21 10:42:31 AM added option to Hide Fees
    'enable_fees_in_menu' => array(
      xl('Enable Fees In Menu'),
       'bool',                          // data type
       '1',                             // default = true
      xl('Enable Fees In Menu')
    ),
    // EDI history  2012-09-13

    'enable_edihistory_in_menu' => array(
      xl('Enable EDI History In Fees Menu'),
       'bool',                          // data type
       '1',                             // default = true
      xl('EDI History (under Fees) for storing and interpreting EDI claim response files')
    ),

    'online_support_link' => array(
      xl('Online Forum Support Link'),
       'text',                          // data type
       'https://forums.librehealth.io/c/7-support',
      xl('URL for LibreHealth EHR Website.')
    ),

    'support_phone_number' => array(
      xl('Support Phone Number'),
       'text',
       '',
      xl('Phone Number for Vendor Support that Appears on the About Page.')
    ),



    'gbl_pt_list_page_size' => array(
      xl('Patient List Page Size'),
      array(
        '10'  =>  '10',
        '25'  =>  '25',
        '50'  =>  '50',
        '100' => '100',
      ),
       '10',
      xl('Number of patients to display per page in the patient list.')
    ),

    'gbl_pt_list_new_window' => array(
      xl('Patient List New Window'),
      'bool',                           // data type
      '0',                              // default = false
      xl('Default state of New Window checkbox in the patient list.')
    ),

    'gbl_vitals_options' => array(
      xl('Vitals Form Options'),
      array(
        '0' => xl('Standard'),
        '1' => xl('Omit circumferences'),
      ),
       '0',                             // default
      xl('Special treatment for the Vitals form')
    ),

  ),

  // Locale Tab
  //
  'Locale' => array(

    'language_default' => array(
      xl('Default Language'),
       'lang',                          // data type
       'English (Standard)',            // default = english
      xl('Default language if no other is allowed or chosen.')
    ),

    'language_menu_showall' => array(
      xl('All Languages Allowed'),
       'bool',                          // data type
       '1',                             // default = true
      xl('Allow all available languages as choices on menu at login.')
    ),

    'language_menu_other' => array(
      xl('Allowed Languages'),
       'm_lang',                        // data type
       '',                              // default = none
      xl('Select which languages, if any, may be chosen at login. (only pertinent if above All Languages Allowed is turned off)')
    ),

    'allow_debug_language' => array(
      xl('Allow Debugging Language'),
       'bool',                          // data type
       '1',                             // default = true during development and false for production releases
      xl('This will allow selection of the debugging (\'dummy\') language.')
    ),

    'translate_layout' => array(
      xl('Translate Layouts'),
       'bool',                          // data type
       '1',                             // default = true
      xl('Is text from form layouts to be translated?')
    ),

    'translate_lists' => array(
      xl('Translate Lists'),
       'bool',                          // data type
       '1',                             // default = true
      xl('Is text from lists to be translated?')
    ),

    'translate_gacl_groups' => array(
      xl('Translate Access Control Groups'),
       'bool',                          // data type
       '1',                             // default = true
      xl('Are access control group names to be translated?')
    ),

    'translate_form_titles' => array(
      xl('Translate Patient Note Titles'),
       'bool',                          // data type
       '1',                             // default = true
      xl('Are patient note titles to be translated?')
    ),

    'translate_document_categories' => array(
      xl('Translate Document Categories'),
       'bool',                          // data type
       '1',                             // default = true
      xl('Are document category names to be translated?')
    ),

    'translate_appt_categories' => array(
      xl('Translate Appointment Categories'),
       'bool',                          // data type
       '1',                             // default = true
      xl('Are appointment category names to be translated?')
    ),

    'units_of_measurement' => array(
      xl('Units for Visit Forms'),
      array(
        '1' => xl('Show both US and metric (main unit is US)'),
        '2' => xl('Show both US and metric (main unit is metric)'),
        '3' => xl('Show US only'),
        '4' => xl('Show metric only'),
      ),
       '1',                             // default = Both/US
      xl('Applies to the Vitals form and Growth Chart')
    ),

    'us_weight_format' => array(
      xl('Display Format for US Weights'),
      array(
        '1'=>xl('Show pounds as decimal value'),
        '2'=>xl('Show pounds and ounces')
      ),
       '1',
      xl('Applies to Vitals form')
    )
      ,
    'disable_deprecated_metrics_form' => array(
      xl('Disable Old Metric Vitals Form'),
       'bool',                          // data type
       '1',                             // default = true
      xl('This was the older metric-only Vitals form, now deprecated.')
    ),

    'phone_country_code' => array(
      xl('Telephone Country Code'),
       'num',
       '1',                             // default = North America
      xl('1 = North America. See http://www.wtng.info/ for a list of other country codes.')
    ),

    'date_display_format' => array(
      xl('Date Display Format'),
      array(
        '0' => xl('YYYY-MM-DD'),
        '1' => xl('MM/DD/YYYY'),
        '2' => xl('DD/MM/YYYY'),
      ),
       '0',
      xl('Format used to display most dates.')
    ),

    'time_display_format' => array(
      xl('Time Display Format'),
      array(
        '0' => xl('24 hr'),
        '1' => xl('12 hr'),
      ),
       '0',
      xl('Format used to display most times.')
    ),

    'currency_decimals' => array(
      xl('Currency Decimal Places'),
      array(
        '0' => xl('0'),
        '1' => xl('1'),
        '2' => xl('2'),
      ),
       '2',
      xl('Number of digits after decimal point for currency, usually 0 or 2.')
    ),

    'currency_dec_point' => array(
      xl('Currency Decimal Point Symbol'),
      array(
        '.' => xl('Period'),
        ',' => xl('Comma'),
      ),
       '.',
      xl('Symbol used as the decimal point for currency. Not used if Decimal Places is 0.')
    ),

    'currency_thousands_sep' => array(
      xl('Currency Thousands Separator'),
      array(
        ',' => xl('Comma'),
        '.' => xl('Period'),
        ' ' => xl('Space'),
        ''  => xl('None'),
      ),
       ',',
      xl('Symbol used to separate thousands for currency.')
    ),

    'gbl_currency_symbol' => array(
      xl('Currency Designator'),
       'text',                          // data type
       '$',                             // default
      xl('Code or symbol to indicate currency')
    ),
    'age_display_format'=>array(xl('Age Display Format'),
      array(
        '0'=>xl('Years or months'),
        '1'=>xl('Years, months and days')
      ),
       '0',
      xl('Format for age display')
    ),

    'age_display_limit' => array(
      xl('Age in Years for Display Format Change'),
       'num',
       '3',
      xl('If YMD is selected for age display, switch to just Years when patients older than this value in years')
    ),
  ),

  // Features Tab
  //
  'Features' => array(

    'specific_application' => array(
      xl('Specific Application'),
      array(
        '0' => xl('None'),
        '2' => xl('IPPF'),
        '3' => xl('Weight loss clinic'),
      ),
       '0',                             // default
      xl('Indicator for specialized usage')
    ),

    'inhouse_pharmacy' => array(
      xl('Drugs and Products'),
      array(
        '0' => xl('Do not inventory and sell any products'),
        '1' => xl('Inventory and sell drugs only'),
        '2' => xl('Inventory and sell both drugs and non-drug products'),
        '3' => xl('Products but no prescription drugs and no templates'),
      ),
       '0',                             // default
      xl('Option to support inventory and sales of products')
    ),

    'disable_chart_tracker' => array(
      xl('Disable Chart Tracker'),
       'bool',                          // data type
       '0',                             // default = false
      xl('Removes the Chart Tracker feature')
    ),

    'disable_sql_admin_link' => array(
     xl('Disable SQL Admin'),
      'bool',                           // data type
      '1',                              // default = true
     xl('Removes menu selection for configured SQL Admin Tool')
    ),

    'disable_immunizations' => array(
      xl('Disable Immunizations'),
       'bool',                          // data type
       '0',                             // default = false
      xl('Removes support for immunizations')
    ),

    'disable_prescriptions' => array(
      xl('Disable Prescriptions'),
       'bool',                          // data type
       '0',                             // default = false
      xl('Removes support for prescriptions')
    ),


    'select_multi_providers' => array(
      xl('Support Multi-Provider Events'),
       'bool',                          // data type
       '0',                             // default = false
      xl('Support calendar events that apply to multiple providers')
    ),

    'disable_non_default_groups' => array(
      xl('Disable User Groups'),
       'bool',                          // data type
       '1',                             // default = true
      xl('Normally this should be checked. Not related to access control.')
    ),

    'ignore_pnotes_authorization' => array(
      xl('Skip Authorization of Patient Notes'),
       'bool',                          // data type
       '1',                             // default = true
      xl('Do not require patient notes to be authorized')
    ),


    'configuration_import_export' => array(
      xl('Configuration Export/Import'),
       'bool',                          // data type
       '0',                             // default = false
      xl('Support export/import of configuration data via the Backup page.')
    ),

    'restrict_user_facility' => array(
      xl('Restrict Users to Facilities'),
       'bool',                          // data type
       '0',                             // default
      xl('Restrict non-authorized users to the Schedule Facilities set in User admin.')
    ),

    'set_facility_cookie' => array(
      xl('Remember Selected Facility'),
       'bool',                          // data type
       '0',                             // default
      xl('Set a facility cookie to remember the selected facility between logins.')
    ),


    'discount_by_money' => array(
      xl('Discounts as Monetary Amounts'),
       'bool',                          // data type
       '1',                             // default = true
      xl('Discounts at checkout time are entered as money amounts, as opposed to percentage.')
    ),


    'gbl_mask_patient_id' => array(
      xl('Mask for Patient IDs'),
       'text',                          // data type
       '',                              // default
      xl('Specifies formatting for the external patient ID.  # = digit, @ = alpha, * = any character.  Empty if not used.')
    ),

    'gbl_mask_invoice_number' => array(
      xl('Mask for Invoice Numbers'),
       'text',                          // data type
       '',                              // default
      xl('Specifies formatting for invoice reference numbers.  # = digit, @ = alpha, * = any character.  Empty if not used.')
    ),

    'gbl_mask_product_id' => array(
      xl('Mask for Product IDs'),
       'text',                          // data type
       '',                              // default
      xl('Specifies formatting for product NDC fields.  # = digit, @ = alpha, * = any character.  Empty if not used.')
    ),


    'use_custom_immun_list' => array(
      xl('Use Custom Immunization List'),
       'bool',                          // data type
       '0',                             // default = true
      xl('This will use the custom immunizations list rather than the standard CVX immunization list.')
    ),

    'amendments' => array (
        xl('Amendments'),
         'bool',                        // data type
         '1',                           // default = true
        xl('Enable amendments feature')
    ),

  ),

  // Report Tab
  //
  'Report' => array(

    'use_custom_daysheet' => array(
      xl('Use Custom End of Day Report'),
      array(
        '0' => xl('None'),
        '1' => xl('Print End of Day Report 1'),
        '2' => xl('Print End of Day Report 2'),
        '3' => xl('Print End of Day Report 3'),
      ),                                // data type
       '1',                             // default = Print End of Day Report 1
      xl('This will allow the use of the custom End of Day report and indicate which report to use.')
    ),

    'daysheet_provider_totals' => array(
      xl('End of Day by Provider or allow Totals Only'),
      array(
        '0' => xl('Provider'),
        '1' => xl('Totals Only'),
      ),
       '1',                             // default
      xl('This specifies the Printing of the Custom End of Day Report grouped Provider or allow the Printing of Totals Only')
    ),

    'ledger_begin_date' => array(
      xl('Beginning Date for Ledger Report'),
      array(
        'Y1' => xl('One Year Ago'),
        'Y2' => xl('Two Years Ago'),
        'M6' => xl('Six Months Ago'),
        'M3' => xl('Three Months Ago'),
        'M1' => xl('One Month Ago'),
        'D1' => xl('One Day Ago'),
      ),
       'Y1',                            // default = One Year
      xl('This is the Beginning date for the Ledger Report.')
    ),

    'print_next_appointment_on_ledger' => array(
       xl('Print the Next Appointment on the Bottom of the Ledger'),
        'bool',                         // data type
        '1',                            // default = true
       xl('This Will Print the Next Appointment on the Bottom of the Patient Ledger')
    ),

    'sales_report_invoice' => array(
      xl('Display Invoice Number or Patient Name or Both in the Sales Report'),
      array(
        '0' => xl('Invoice Number'),
        '1' => xl('Patient Name and ID'),
        '2' => xl('Patient Name and Invoice'),
      ),
       '2',                             // default = 2
      xl('This will Display the Invoice Number in the Sales Report or the Patient Name and ID or Patient Name and Invoice Number.')
    ),

    'cash_receipts_report_invoice' => array(
      xl('Display Invoice Number or Patient Name in the Cash Receipt Report'),
      array(
        '0' => xl('Invoice Number'),
        '1' => xl('Patient Name'),
      ),
       '0',                             // default = 0
      xl('This will Display the Invoice Number or the Patient Name in the Cash Receipts Report.')
    ),

    'receipts_by_provider' => array(
      xl('Print Receipts by Provider'),
      'bool',
      '0',                              // default
      xl('Causes Receipts to Print Encounter/Primary Provider Info')
  ),

    'activate_ccr_ccd_report' => array(
      xl('Activate CCR/CCD Reporting'),
      'bool',                           // data type
      '1',                              // default = true
      xl('This will activate the CCR(Continuity of Care Record) and CCD(Continuity of Care Document) reporting.')
    ),

  ),

    // Demographics Tab
  //
    'Demographic' => array(

    'insurance_information' => array(
      xl('Show Additional Insurance Information'),               // descriptive name
      array(
        '0' => xl('None'),
        '1' => xl('Address Only'),
        '2' => xl('Address and Postal Code'),
        '3' => xl('Address and State'),
        '4' => xl('Address, State and Postal Code'),
        '5' => xl('Postal Code and Box Number'),
    ),
      '4',                              // default
      xl('Show Insurance Address Information in the Insurance Panel of Demographics.')

  ),

    'insurance_address_demographics_report' => array(
      xl('Show Insurance Address on Demographics Report'),
       'bool',                          // data type
       '0',                             // default = false
      xl('This will Show the Insurance Address on the Demographics Report')
    ),


    'hide_billing_widget' => array(
      xl('Hide Billing Widget'),
       'bool',                          // data type
       '0',                             // default = false
      xl('This will hide the Billing Widget in the Patient Summary screen')
    ),

    'force_billing_widget_open' => array(
      xl('Force Billing Widget Open'),
      'bool',                           // data type
      '0',                              // default = false
      xl('This will force the Billing Widget in the Patient Summary screen to always be open.')
    ),

    'omit_employers' => array(
      xl('Omit Employers'),
      'bool',                           // data type
      '0',                              // default = false
      xl('Omit employer information in patient demographics')
    ),

    'advance_directives_warning' => array(
      xl('Advance Directives Warning'),
      'bool',                           // data type
      '0',                              // default = false
      xl('Display advance directives in the demographics page.')
    ),

    'allow_pat_delete' => array(
       xl('Allow Administrators to Delete Patients'),
       'bool',                           // data type
       '1',                              // default = true
       xl('Allow Administrators to Delete Patients')

    ),

    'floating_message_alerts' => array(
      xl('Show Floating Alerts for User Messages'),
       'bool',                          // data type
       '0',                             // default = false
      xl('Show Timed Floating Message Notices for any Unread Messages Addressed to the User When in the Demographics Summary.')
    ),

    'floating_message_alerts_allergies' => array(
      xl('Show Floating Alerts for Patient Allergies'),
       'bool',                          // data type
       '0',                             // default = false
      xl('Show Timed Floating Message Notices for Patient Allergies to the User When in the Demographics Summary.')
    ),

    'floating_message_alerts_timer' => array(
      xl('Re-Display Floating Alerts Timer'),
      array(
       '0:20' => '20',
       '0:30' => '30',
       '0:40' => '40',
       '0:50' => '50',
      ),
       '0:20',                          // default
      xl('The Re-Display Time in Seconds for the Floating Alerts.')
    ),

    'phone_number_format' => array(
      xl('Phone Number Format'),
      array(
        '($1) $2-$3' => '(XXX) XXX-XXXX',   // $1: 3 digits, $2: 3 digits, $3: 4 digits
        '($1) $2 $3' => '(XXX) XXX XXXX',
        '$1-$2-$3' => 'XXX-XXX-XXXX',
        '$1 $2 $3' => 'XXX XXX XXXX',
        '$1$2$3' => 'XXXXXXXXXX'
      ),
      '($1) $2-$3',                    //default
      xl('The format to display phone numbers in.')
    )

  ),

    // Encounters Tab
    //
    'Encounter' => array(

    'esign_all' => array(
      xl('Allows E-Sign on the entire encounter'),
      'bool',                           // data type
      '0',                              // default = false
      xl('This will enable signing an entire encounter, rather than individual forms')
    ),

    'lock_esign_all' => array(
      xl('Lock e-signed encounters and their forms'),
      'bool',                           // data type
      '0',                              // default = false
      xl('This will disable the Edit button on all forms whose parent encounter is e-signed')
    ),

    'esign_individual' => array(
      xl('Allows E-Signing Individual Forms'),
      'bool',                           // data type
      '1',                              // default = false
      xl('This will enable signing individual forms separately')
    ),

    'lock_esign_individual' => array(
      xl('Lock an e-signed form individually'),
      'bool',                           // data type
      '1',                              // default = false
      xl('This will disable the Edit button on any form that is e-signed')
    ),

    'esign_lock_toggle' => array(
      xl('Enable lock toggle'),
      'bool',                           // data type
      '0',                              // default = false
      xl('This will give the user the option to lock (separate locking and signing)')
    ),

    'esign_report_hide_empty_sig' => array(
      xl('Hide Empty E-Sign Logs On Report'),
      'bool',                           // data type
      '1',                              // default = false
      xl('This will hide empty e-sign logs on the patient report')
    ),

    'support_encounter_claims' => array(
      xl('Allow Encounter Claims'),
      'bool',                           // data type
      '0',                              // default = false
      xl('Allow creation of claims containing diagnoses but not procedures or charges. Most clinics do not want this.')
    ),

    'gbl_visit_referral_source' => array(
      xl('Referral Source for Encounters'),
      'bool',                           // data type
      '0',                              // default = false
      xl('A referral source may be specified for each visit.')
    ),

    'encounter_page_size' => array(
      xl('Encounter Page Size'),
      array(
        '0' => xl('Show All'),
        '5' => '5',
        '10' => '10',
        '15' => '15',
        '20' => '20',
        '25' => '25',
        '50' => '50',
      ),
      '20',
      xl('Number of encounters to display per page.')
    ),

    'default_encounter_view' => array(
      xl('Default Encounter View'),               // descriptive name
      array(
        '0' => xl('Clinical View'),
        '1' => xl('Billing View'),
      ),
      '0',                              // default = tree menu
      xl('Choose your default encounter view')
    ),

    'default_search_code_type' => array(
      xl('Default Search Code Type'),
      'all_code_types',                           // data type
      'ICD10',                 // default
      xl('The default code type to search for in the Fee Sheet.')
    ),

    'support_fee_sheet_line_item_provider' => array(
       xl('Support provider in line item in fee sheet'),
        'bool',                         // data type
        '0',                            // default = false
       xl('This Enables provider in line item in the fee sheet')
    ),

    'default_fee_sheet_line_item_provider' => array(
       xl('Default to a provider for line item in the fee sheet'),
        'bool',                         // data type
        '0',                            // default = false
       xl('Default to a provider for line item in the fee sheet.(only applicable if Support line item billing in option above)')
    ),

    'replicate_justification' => array(
      xl('Automatically replicate justification codes in Fee Sheet'),
       'bool',                          // data type
       '0',                             // default = false
      xl('Automatically replicate justification codes in Fee Sheet (basically fills in the blanks with the justification code above it).')
    ),
    'bill_to_patient' => array(
       xl('Allows Fee Sheet Items to be excluded from Insurance Billing'),
        'bool',                         // data type
        '0',                            // default = false
       xl('Allows Fee Sheet Items to be excluded from Insurance Billing')
    ),

    'supervising_physician_in_feesheet' => array(
      xl('Show Supervising Physician on Fee Sheet'),
      'bool',                           // data type
      '0',                              // default = false
      xl('Show Supervising Physician on Fee Sheet.')
    ),

    'ordering_physician_in_feesheet' => array(
      xl('Show Ordering Physician on Fee Sheet'),
      'bool',                           // data type
      '0',                              // default = false
      xl('Show Ordering Physician on Fee Sheet.')
    ),

    'referring_physician_in_feesheet' => array(
      xl('Show Referring Physician on Fee Sheet'),
      'bool',                           // data type
      '0',                              // default = false
      xl('Show Reffering Physician on Fee Sheet.')
    ),

    'contract_physician_in_feesheet' => array(
      xl('Show Contract Physician on Fee Sheet'),
      'bool',                           // data type
      '0',                              // default = false contract_physician_in_feesheet_name
      xl('Show Contract Physician on Fee Sheet.')
    ),

    'contract_physician_in_feesheet_name' => array(
      xl('Label Title for Contract Physician on Fee Sheet'),
      'text',                           // data type
      'Contractor',
      xl('Label Title for Contract Physician on Fee Sheet')
    ),

    'allow_appointments_in_feesheet' => array(
      xl('Allow Scheduling of Appointments from Fee Sheet'),
      'bool',                           // data type
      '0',                              // default = false
      xl('Allow Scheduling of Appointments from the Fee Sheet.')
    ),

    'default_chief_complaint' => array(
      xl('Default Reason for Visit'),
      'text',                           // data type
      '',
      xl('You may put text here as the default complaint in the New Patient Encounter form.')
    ),

    'default_new_encounter_form' => array(
      xl('Default Encounter Form ID'),
      'text',                           // data type
      '',
      xl('To automatically open the specified form. Some sports teams use football_injury_audit here.')
    ),

  ),

  // Billing Tab

  'Billing' => array(


    'display_units_in_billing' => array(
      xl('Display the Units Column on the Billing Screen'),
        'bool',                         // data type
        '0',                            // default = false
      xl('Display the Units Column on the Billing Screen')
    ),

    'notes_to_display_in_Billing' => array(
      xl('Which notes are to be displayed in the Billing Screen'),
      array(
        '0' => xl('None'),
        '1' => xl('Encounter Billing Note'),
        '2' => xl('Patient Billing Note'),
        '3' => xl('All'),
      ),
       '3',
     xl('Display the Encounter Billing Note or Patient Billing Note or Both in the Billing Screen.')
    ),

    'payment_delete_begin_date' => array(
      xl('Do Not Delete Payment older than'),
      array(
        'N0' => xl('Allow All Deletes'),
        'Y1' => xl('One Year'),
        'M6' => xl('Six Months'),
        'M5' => xl('Five Months'),
        'M4' => xl('Four Months'),
        'M3' => xl('Three Months'),
        'M2' => xl('Two Months'),
        'M1' => xl('One Month'),
        'D1' => xl('One Day'),
      ),
       'N0',                            // default = Allow All
      xl('Do not Allow Deletion of Payments older that this selection.')
    ),

   'inactivate_insurance_companies' => array(
      xl('Allow Insurance Companies to be Inactivated'),
      'bool',
      '0',                              // default
      xl('This Will Allow Individual Insurance Companies to be Inactivated')

  ),

   'auto_writeoff_y_n' => array(
      xl('Allow Automatic Caculation of Write Offs in Posting'),
      'bool',
      '1',                              // default
      xl('Allow Automatic Caculation of Write Offs in Posting')

  ),

  ),

  // Statement Tab
  //
  'Statement' => array(
    'use_custom_statement' => array(
      xl('Use Custom Statement'),
       'bool',                          // data type
       '0',                             // default = false
      xl('This will use the custom Statement showing the description instead of the codes.')
    ),

    'statement_appearance' => array(
       xl('Statement Appearance'),
       array(
            '0' => xl('Plain Text'),
            '1' => xl('Modern/images')
             ),                          // data type
       '1',                              // default = true
       xl('Patient statements can be generated as plain text or with a modern graphical appearance.')
     ),

    'billing_phone_number' => array(
       xl('Custom Billing Phone Number'),
       'text',                           // data type
       '',
       xl('Phone number for billing inquiries')
     ),

    'show_aging_on_custom_statement' => array(
      xl('Show Aging on Custom Statement'),
       'bool',                          // data type
       '0',                             // default = false
      xl('This will Show Aging on the custom Statement.')
    ),

    'show_insurance_name_on_custom_statement' => array(
      xl('Show Insurance Company Name on Custom Statement'),
       'bool',                          // data type
       '0',                             // default = false
      xl('This will Show Insurance Company Name on the custom Statement Instead of Insurance information on file.')
    ),

    'use_statement_print_exclusion' => array(
      xl('Allow Statement to be Excluded from Printing'),
       'bool',                          // data type
       '0',                             // default = false
      xl('This will enable the Ability to Exclude Certain Patient Statements from Printing.')
    ),

    'minimum_amount_to_print' => array(
      xl('Total Minimum Amount of Statement to Allow Printing'),
       'num',                           // data type
       '1.00',
      xl('Total Minimum Dollar Amount of Statement to Allow Printing.(only applicable if Allow Statement to be Excluded from Printing is enabled)')
    ),

    'insurance_statement_exclude' => array(
       xl('Do Not Print Statements For Insurance Companies'),
       array(
            '0' => xl('Primary'),
            '1' => xl('Secondary'),
            '2' => xl('Tertiary'),
            '3' => xl('All'),
            '4' => xl('None')
             ),                          // data type
       '1',                              // default = true
       xl('Do Not Print Statements for Insurance Companies Statement to Allow Printing.(only applicable if Allow Statement to be Excluded from Printing is enabled).')
     ),

    'disallow_print_deceased' => array(
      xl('Disallow Printing for Deceased Patients'),
       'bool',                         // data type
       '0',                            // default = false
      xl('This will disallow printing of statements for deceased patients')
    ),

      'statement_bill_note_print' => array(
      xl('Print Patient Billing Note'),
       'bool',                          // data type
       '0',                             // default = false
      xl('This will allow printing of the Patient Billing Note on the statements.')
    ),

    'number_appointments_on_statement' => array(
      xl('Number of Appointments on Statement'),
       'num',                           // data type
       '2',                             // default = 2
      xl('The Number of Future Appointments to Display on the Statement.')
    ),

      'statement_message_to_patient' => array(
      xl('Print Custom Message'),
       'bool',                          // data type
       '0',                             // default = false
      xl('This will allow printing of a custom Message on the statements.')
    ),

    'statement_msg_text' => array(
      xl('Custom Statement message'),
       'text',                          // data type
       '',
      xl('Text for Custom statement message.')
    ),

      'use_dunning_message' => array(
      xl('Use Custom Dunning Messages'),
       'bool',                          // data type
       '0',                             // default = false
      xl('This will allow use of the custom Dunning Messages on the statements.')
    ),

    'first_dun_msg_set' => array(
      xl('Number of days before showing first account message'),
       'num',                           // data type
       '30',
      xl('Number of days before showing first account message.')
    ),

    'first_dun_msg_text' => array(
      xl('First account message'),
       'text',                          // data type
       '',
      xl('Text for first account message.')
    ),

    'second_dun_msg_set' => array(
      xl('Number of days before showing second account message'),
       'num',                           // data type
       '60',
      xl('Number of days before showing second account message')
    ),

    'second_dun_msg_text' => array(
      xl('Second account message'),
       'text',                          // data type
       '',
      xl('Text for second account message.')
    ),

    'third_dun_msg_set' => array(
      xl('Number of days before showing third account message'),
       'num',                           // data type
       '90',
      xl('Number of days before showing third account message')
    ),

    'third_dun_msg_text' => array(
      xl('Third account message'),
       'text',                          // data type
       '',
      xl('Text for third account message.')
    ),

    'fourth_dun_msg_set' => array(
      xl('Number of days before showing fourth account message'),
       'num',                           // data type
       '120',
      xl('Number of days before showing fourth account message')
    ),

    'fourth_dun_msg_text' => array(
      xl('Fourth account message'),
       'text',                          // data type
       '',
      xl('Text for fourth account message.')
    ),

    'fifth_dun_msg_set' => array(
      xl('Number of days before showing fifth account message'),
       'num',                           // data type
       '150',
      xl('Number of days before showing fifth account message')
    ),

    'fifth_dun_msg_text' => array(
      xl('Fifth account message'),
       'text',                          // data type
       '',
      xl('Text for fifth account message.')
    ),
  ),

  // Claim Tab
  //
  'Claims' => array(

    'claim_type' => array(
     xl('Insurance Claim Type'),
        array(
            '0' => xl('CMS 1500'),
            '1' => xl('UB-04'),
            '2' => xl('Both')
        ),
        '0',                              // default = CMS 1500
        xl('Insurance Claim Type CMS 1500 , UB-04 or Both Displayed in the Billing Screen'),
    ),

    'preprinted_cms_1500' => array(
      xl('Prints the CMS 1500 on the Preprinted form.'),
       'bool',                          // data type
       '0',                             // default = false
      xl('Prints the CMS 1500 on the Preprinted form.')
    ),

    'cms_top_margin_default' => array(
      xl('Default top print margin for CMS 1500'),
       'num',                           // data type
       '24',                            // default
      xl('This is the default top print margin for CMS 1500. It will adjust the final printed output up or down.')
    ),

    'cms_left_margin_default' => array(
      xl('Default left print margin for CMS 1500'),
       'num',                           // data type
       '20',                            // default
      xl('This is the default left print margin for CMS 1500. It will adjust the final printed output left or right.')
    ),

    'cms_1500' => array(
      xl('CMS 1500 Paper Form Format'),
      array(
        '0' => xl('08/05{{CMS 1500 format date revision setting in globals}}'),
        '1' => xl('02/12{{CMS 1500 format date revision setting in globals}}'),
      ),
       '1',                             // default
      xl('This specifies which revision of the form the billing module should generate')
    ),

    'cms_1500_box_31_format' => array(
      xl('CMS 1500: Box 31 Format'),
      array(
        '0' => xl('Signature on File'),
        '1' => xl('Firstname Lastname'),
        '2' => xl('None'),
      ),
       '0',                             // default
      xl('This specifies whether to include date in Box 31.')
    ),
     'cms_1500_box_31_date' => array(
      xl('CMS 1500: Date in Box 31 (Signature)'),
      array(
        '0' => xl('None'),
        '1' => xl('Date of Service'),
        '2' => xl('Today'),
      ),
       '0',                             // default
      xl('This specifies whether to include date in Box 31.')
    ),

    'ubtop_margin_default' => array(
      xl('Default top print margin for UB-04'),
      'num', // data type
      '07', // default
      xl('This is the default top print margin for UB-04. It will adjust the final printed output up or down.')
    ),

    'ubleft_margin_default' => array(
      xl('Default left print margin for UB-04'),
      'num', // data type
      '14', // default
      xl('his is the default left print margin for UB-04. It will adjust the final printed output left or right.')
    ),

    'default_bill_type' => array(
      xl('Default Bill Type Box 4'),
      'text', // data type
      '0111', // default
      xl('This Default entry must start with a zero followed by three numbers. It will be used in Box 4 of the UB04')
    ),

     'admit_default_type' => array(
      xl('Admission Type Box 14'),
      'list', // data type
      '',     // default
      xl('This entry is for the Admission Type it needs to be a single digit. It will be used in Box 14 of the UB04'),
      'ub_admit_type'
    ),

     'admit_default_source' => array(
      xl('Admission Source Box 15'),
      'list', // data type
      '',     // default
      xl('This entry is for the Admission Source it needs to be 2 digits (example 01, 12 etc). It will be used in Box 15 of the UB04'),
      'ub_admit_source'
    ),

     'discharge_status_default' => array(
      xl('Discharge Status Box 17'),
      'text', // data type
      '', // default
      xl('This entry is for the Discharge Status it needs to be 2 digits (example 02, 11 etc). It will be used in Box 17 of the UB04')
    ),

     'attending_id' => array(
      xl('Attending Physician Box 76'),
      'provider', // data type
      '',     // default
      xl('Attending Physician Box 76 of the UB04')
    ),

     'operating_id' => array(
      xl('Operating Physician Box 77'),
      'provider', // data type
      '',     // default
      xl('Operating Physician Box 77 of the UB04')
    ),

    'other1_id' => array(
      xl('Other Physician #1 Box 78'),
      'provider', // data type
      '',     // default
      xl('Other Physician #1 Box 78 of the UB04')
    ),

    'other2_id' => array(
      xl('Other Physician #2 Box 79'),
      'provider', // data type
      '',     // default
      xl('Other Physician #2 Box 79 of the UB04')
    ),

  ),


  //Documents Tab
  //
  'Documents' => array(

    'document_storage_method' => array(
      xl('Document Storage Method'),
      array(
       '0' => xl('Hard Disk'),
       '1' => xl('CouchDB')
      ),
       '0',                             // default
      xl('Option to save method of document storage.')
    ),

   'couchdb_host' => array(
      xl('CouchDB HostName'),
       'text',
       'localhost',
      xl('CouchDB host'),
    ),

    'couchdb_user' => array(
      xl('CouchDB UserName'),
       'text',
       '',
      xl('Username to connect to CouchDB'),
    ),

    'couchdb_pass' => array(
      xl('CouchDB Password'),
       'text',
       '',
      xl('Password to connect to CouchDB'),
    ),

    'couchdb_port' => array(
      xl('CouchDB Port'),
       'text',
       '5984',
      xl('CouchDB port'),
    ),

    'couchdb_dbase' => array(
      xl('CouchDB Database'),
       'text',
       '',
      xl('CouchDB database name'),
    ),

    'couchdb_log' => array(
      xl('CouchDB Log Enable'),
       'bool',
       '0',
      xl('Enable log for document uploads/downloads to CouchDB'),
    ),

    'expand_document_tree' => array(
      xl('Expand All Document Categories'),
       'bool',                          // data type
       '0',                             // default = false
      xl('Expand All Document Categories by Default')
    ),

    'patient_id_category_name' => array(
      xl('Patient ID Category Name'),
       'text',                          // data type
       'Patient ID card',               // default
      xl('Optional category name for an ID Card image that can be viewed from the patient summary page.')
    ),

    'patient_photo_category_name' => array(
      xl('Patient Photo Category Name'),
       'text',                          // data type
       'Patient Photograph',            // default
      xl('Optional category name for photo images that can be viewed from the patient summary page.')
    ),

    'hide_document_encryption' => array(
      xl('Hide Encryption/Decryption Options In Document Management'),
      'bool',                           // data type
      '1',                              // default = true
      xl('This will deactivate document the encryption and decryption features, and hide them in the UI.')
    ),

  ),

  // Calendar Tab
  //
  'Calendar' => array(

    'disable_calendar' => array(
      xl('Disable Calendar'),
       'bool',                          // data type
       '0',                             // default
      xl('Do not display the calendar.')
    ),

    'schedule_start' => array(
      xl('Calendar Starting Hour'),
       'hour',
       '8',                             // default
      xl('Beginning hour of day for calendar events.')
    ),

    'schedule_end' => array(
      xl('Calendar Ending Hour'),
       'hour',
       '17',                            // default
      xl('Ending hour of day for calendar events.')
    ),

    'check_appt_time' => array(
      xl('Check Selected Appointment Time'),
       'bool',                          // data type
       '1',                             // default
      xl('Do not register appointments with time outside clinic hours.')
    ),

    'calendar_refresh_freq' => array(
      xl('Calendar Refresh Frequency'),
      array(
        'none' => xl('No Refresh'),
        '60000' => xl('60 seconds'),
        '360000' => xl('5 minutes'),
        '720000' => xl('10 minutes'),
      ),
       '360000',                     // default
      xl('How often the calendar automatically refetches events.')
    ),

    'calendar_provider_view_type' => array(
      xl('Resource Title'),
      array(
        'full' => xl('Provider Full Name'),
        'last' => xl('Provider Last Name'),
        'resource' => xl('Resource Title'),
      ),
       'full',                     // default
      xl('Name Choice for Resource in Calendar.')
    ),

    'calendar_interval' => array(
      xl('Calendar Interval'),
      array(
        '5' => '5',
       '10' => '10',
       '15' => '15',
       '20' => '20',
       '30' => '30',
       '60' => '60',
      ),
       '15',                            // default
      xl('The time granularity of the calendar and the smallest interval in minutes for an appointment slot.')
    ),

    'calendar_view_type' => array(
      xl('Default Calendar View'),
      array(
       'providerAgenda' => xl('1 Day'),
       'providerAgenda2Day' => xl('2 Day'),
       'timelineWeek' => xl('Week'),
       'timelineMonth' => xl('Month'),
      ),
       'providerAgenda',                           // default
      xl('This sets the Default Calendar View, Default is 1 Day.')
    ),
     'first_day_week' => array(
       xl('First day in the week') ,
       array(
         '1' => xl('Monday'),
         '0' => xl('Sunday'),
         '6' => xl('Saturday')
       ),
       '1',
       xl('Your first day in the week.')
     ),

         // Reference - https://en.wikipedia.org/wiki/Workweek_and_weekend#Around_the_world
    'weekend_days' => array(
      xl('Your weekend days'),
      array(
         '6,0' => xl('Saturday') . ' - ' . xl('Sunday'),
          '0' => xl('Sunday'),
          '5' => xl('Friday'),
          '6' => xl('Saturday'),
          '5,6' => xl('Friday') .' - ' . xl('Saturday'),
      ),
      '6,0'
      ,xl('which days are your weekend days?')
    ),

    'calendar_appt_style' => array(
      xl('Appointment Display Style'),
      array(
                '1' => xl('Last name'),
                '2' => xl('Last name, first name'),
                '3' => xl('Last name, first name (title)'),
                '4' => xl('Last name, first name (title: description)'),
      ),
       '2',                             // default
      xl('This determines how appointments display on the calendar.')
    ),


    'number_of_appts_to_show' => array(
      xl('Appointments - Patient Summary - Number to Display'),
       'num',
       '10',
      xl('Number of Appointments to display in the Patient Summary')
    ),

    'patient_portal_appt_display_num' => array(
      xl('Appointments - Onsite Patient Portal - Number to Display'),
       'num',
       '20',
      xl('Number of Appointments to display in the Onsite Patient Portal')
    ),

    'appt_overbook_statuses' => array(
        xl('Appointment Overbook Statuses'),
        'text',                           // data type
        'x, ?, %, ^, =, &',                              // default
        xl('Override the statuses that would make an event appear available using find-available')
    ),

    'appt_display_sets_option' => array(
      xl('Appointment Display Sets - Ignore Display Limit (Last Set)'),
       'bool',                           // data type
       '1',                              // default
      xl('Override (if necessary) the appointment display limit to allow all appointments to be displayed for the last set')
    ),

    'appt_display_sets_color_1' => array(
      xl('Appointment Display Sets - Color 1'),
       'color_code',
       '#FFFFFF',
      xl('Color for odd sets (except when last set is odd and all member appointments are displayed and at least one subsequent scheduled appointment exists (not displayed) or not all member appointments are displayed).')
    ),

    'appt_display_sets_color_2' => array(
      xl('Appointment Display Sets - Color 2'),
       'color_code',
       '#E6E6FF',
      xl('Color for even sets (except when last set is even and all member appointments are displayed and at least one subsequent scheduled appointment exists (not displayed) or not all member appointments are displayed).')
    ),

    'appt_display_sets_color_3' => array(
      xl('Appointment Display Sets - Color 3'),
       'color_code',
       '#E6FFE6',
      xl('Color for the last set when all member appointments are displayed and at least one subsequent scheduled appointment exists (not displayed).')
    ),

    'appt_display_sets_color_4' => array(
      xl('Appointment Display Sets - Color 4'),
       'color_code',
       '#FFE6FF',
      xl('Color for the last set when not all member appointments are displayed.')
    ),

    'num_past_appointments_to_show' => array(
      xl('Past Appointment Display Widget'),
       'num',                           // data type
       '0',                             // default = false
      xl('A positive number will show that many past appointments on a Widget in the Patient Summary screen (a negative number will show the past appointments in descending order)')
    ),

    'event_color' => array(
      xl('Appointment/Event Color'),
      array(
        '1' => 'Category Color Schema',
        '2' => 'Facility Color Schema',
      ),                           // data type
      '1',                              // default
      xl('This determines which color schema used for appointment')
    ),

    'docs_see_entire_calendar' => array(
      xl('Providers See Entire Calendar'),
       'bool',                          // data type
       '0',                             // default
      xl('Check this if you want providers to see all appointments by default and not just their own.')
    ),

    'display_canceled_appointments' => array(
      xl('Display Canceled Appointments in Calendar'),
       'bool',                          // data type
       '1',                             // default
      xl('Display Canceled Appointments in Calendar.')
    ),

    'auto_create_new_encounters' => array(
      xl('Auto-Create New Encounters'),
       'bool',                          // data type
       '1',                             // default
      xl('Automatically create a new encounter when an appointment check in status is selected.')
    ),

    'disable_pat_trkr' => array(
      xl('Patient Flow Board: Disable'),
       'bool',                          // data type
       '0',                             // default
      xl('Do not display the patient flow board.')
    ),

    'ptkr_pt_list_new_window' => array(
      xl('Patient Flow Board: Open Demographics in New Window'),
       'bool',                          // data type
       '0',                             // default = false
      xl('When Checked, Demographics Will Open in New Window from Patient Flow Board.')
    ),

    'ptkr_visit_reason' => array(
      xl('Patient Flow Board: Show Visit Reason'),
       'bool',                          // data type
       '0',                             // default = false
      xl('When Checked, Visit Reason Will Show in Patient Flow Board.')
    ),

    'ptkr_show_pid' => array(
      xl('Patient Flow Board: Show Patient ID'),
       'bool',                          // data type
       '1',                             // default = true
      xl('When Checked, Patient ID Will Show in Patient Flow Board.')
    ),
    'ptkr_show_room' => array(
      xl('Patient Flow Board: Show Exam Room'),
      'bool',                          // data type
      '1',                             // default = true
      xl('When Checked, Exam Room Will Show in Patient Flow Board.')
    ),
    'ptkr_show_visit_type' => array(
      xl('Patient Flow Board: Show Visit Type'),
      'bool',                          // data type
      '1',                             // default = true
      xl('When Checked, Visit Type Will Show in Patient Flow Board.')
    ),
    'ptkr_show_encounter' => array(
      xl('Patient Flow Board: Show Patient Encounter Number'),
       'bool',                          // data type
       '1',                             // default = true
      xl('When Checked, Patient Encounter Number Will Show in Patient Flow Board.')
    ),
      'ptkr_flag_dblbook' => array(
          xl('Patient Flow Board: Flag Double Booked Appt'),
          'bool',                          // data type
          '1',                             // default = true
          xl('When Checked, double booked appointments will be flagged in orange in Patient Flow Board.')
    ),
    'ptkr_date_range' => array(
      xl('Patient Flow Board: Allow Date Range'),
       'bool',                          // data type
       '0',                             // default = false
      xl('This Allows a Date Range to be Selected in Patient Flow Board.')
    ),
    'ptkr_end_date' => array(
      xl('Patient Flow Board: Ending Date'),
      array(
        'Y1' => xl('One Year Ahead'),
        'Y2' => xl('Two Years Ahead'),
        'M6' => xl('Six Months Ahead'),
        'M3' => xl('Three Months Ahead'),
        'M1' => xl('One Month Ahead'),
        'W3' => xl('Three Weeks Ahead'),
        'W2' => xl('Two Weeks Ahead'),
        'W1' => xl('One Week Ahead'),
        'D1' => xl('One Day Ahead'),
      ),
      'Y1',                     // default = One Year
      xl('This is the Ending date for the Patient Flow Board Date Range. (only applicable if Allow Date Range in option above is Enabled)')
    ),
    'pat_trkr_timer' => array(
      xl('Patient Flow Board: Timer Interval'),
      array(
       '0' => xl('No automatic refresh'),
       '0:10' => '10',
       '0:20' => '20',
       '0:30' => '30',
       '0:40' => '40',
       '0:50' => '50',
       '0:59' => '60',
      ),
       '0:20',                          // default
      xl('The screen refresh time in Seconds for the Patient Flow Board Screen.')
    ),

    'status_default' => array(
      xl('Patient Flow Board: Default Status'),
      'status',                           // data type
      '',                                 // default = none
      xl('Default Status for the Patient Flow Board Screen.')
    ),

    'checkout_roll_off' => array(
      xl('Patient Flow Board: Number of Minutes to display completed checkouts'),
       'num',
       '0',                             // default
      xl('Number of Minutes to display completed checkouts. Zero is continuous display')
    ),

    'drug_screen' => array(
      xl('Patient Flow Board: Enable Random Drug Testing'),
      'bool',                           // data type
      '0',                              // default
      xl('Allow Patient Flow Board to Select Patients for Drug Testing.')
    ),

    'drug_testing_percentage' => array(
      xl('Percentage of Patients to Drug Test'),
       'num',
       '33',                            // default
      xl('Percentage of Patients to select for Random Drug Testing.')
    ),

    'maximum_drug_test_yearly' => array(
      xl('Maximum number of times a Patient can be tested in a year'),
       'num',
       '0',                             // default
      xl('Maximum number of times a Patient can be tested in a year. Zero is no limit.')
    ),
  ),


  // CDR (Clinical Decision Rules)
  //
  'CDR' => array(

    'enable_cdr' => array(
      xl('Enable Clinical Decisions Rules (CDR)'),
       'bool',                          // data type
       '1',                             // default
      xl('Enable Clinical Decisions Rules (CDR)')
    ),

    'enable_allergy_check' => array(
      xl('Enable Allergy Check'),
       'bool',                          // data type
       '1',                             // default
      xl('Enable Allergy Check Against Medications and Prescriptions')
    ),

    'enable_alert_log' => array(
      xl('Enable Alert Log'),
       'bool',                          // data type
       '1',                             // default
      xl('Enable Alert Logging')
    ),

    'enable_cdr_new_crp' => array(
      xl('Enable Clinical Passive New Reminder(s) Popup'),
       'bool',                          // data type
       '1',                             // default
      xl('Enable Clinical Passive New Reminder(s) Popup')
    ),

    'enable_cdr_crw' => array(
      xl('Enable Clinical Passive Reminder Widget'),
       'bool',                          // data type
       '1',                             // default
      xl('Enable Clinical Passive Reminder Widget')
    ),

    'enable_cdr_crp' => array(
      xl('Enable Clinical Active Reminder Popup'),
       'bool',                          // data type
       '1',                             // default
      xl('Enable Clinical Active Reminder Popup')
    ),

    'enable_cdr_prw' => array(
      xl('Enable Patient Reminder Widget'),
       'bool',                          // data type
       '1',                             // default
      xl('Enable Patient Reminder Widget')
    ),

    'pat_rem_clin_nice' => array(
      xl('Patient Reminder Creation Processing Priority'),
      array(
        '' => xl('Default Priority'),
        '5' => xl('Moderate Priority'),
        '10' => xl('Moderate/Low Priority'),
        '15' => xl('Low Priority'),
        '20' => xl('Lowest Priority')
      ),
       '',                              // default
      xl('Set processing priority for creation of Patient Reminders (in full clinic mode).')
    ),

  ),

  // Logging
  //
  'Logging' => array(

    'enable_auditlog' => array(
      xl('Enable Audit Logging'),
       'bool',                          // data type
       '1',                             // default
      xl('Enable Audit Logging')
    ),

    'audit_events_patient-record' => array(
      xl('Audit Logging Patient Record'),
       'bool',                          // data type
       '1',                             // default
      xl('Enable logging of patient record modifications.').' ('.xl('Note that Audit Logging needs to be enabled above').')'
    ),

    'audit_events_scheduling' => array(
      xl('Audit Logging Scheduling'),
       'bool',                          // data type
       '1',                             // default
      xl('Enable logging of scheduling activities.').' ('.xl('Note that Audit Logging needs to be enabled above').')'
    ),

    'audit_events_order' => array(
      xl('Audit Logging Order'),
       'bool',                          // data type
       '1',                             // default
      xl('Enable logging of ordering activities.').' ('.xl('Note that Audit Logging needs to be enabled above').')'
    ),

    'audit_events_security-administration' => array(
      xl('Audit Logging Security Administration'),
       'bool',                          // data type
       '1',                             // default
      xl('Enable logging of security and administration activities.').' ('.xl('Note that Audit Logging needs to be enabled above').')'
    ),

    'audit_events_backup' => array(
      xl('Audit Logging Backups'),
       'bool',                          // data type
       '1',                             // default
      xl('Enable logging of backup related activities.').' ('.xl('Note that Audit Logging needs to be enabled above').')'
    ),

    'audit_events_other' => array(
      xl('Audit Logging Miscellaneous'),
       'bool',                          // data type
       '1',                             // default
      xl('Enable logging of miscellaneous activities.').' ('.xl('Note that Audit Logging needs to be enabled above').')'
    ),

    'audit_events_query' => array(
      xl('Audit Logging SELECT Query'),
       'bool',                          // data type
       '0',                             // default
      xl('Enable logging of all SQL SELECT queries.').' ('.xl('Note that Audit Logging needs to be enabled above').')'
    ),

    'audit_events_cdr' => array(
      xl('Audit CDR Engine Queries'),
       'bool',                          // data type
       '0',                             // default
      xl('Enable logging of CDR Engine Queries.').' ('.xl('Note that Audit Logging needs to be enabled above').')'
    ),

    'enable_atna_audit' => array(
      xl('Enable ATNA Auditing'),
       'bool',                          // data type
       '0',                             // default
      xl('Enable Audit Trail and Node Authentication (ATNA).')
    ),

    'atna_audit_host' => array(
      xl('ATNA audit host'),
       'text',                          // data type
       '',                              // default
      xl('The hostname of the ATNA audit repository machine.')
    ),

    'atna_audit_port' => array(
      xl('ATNA audit port'),
       'text',                          // data type
       '6514',                          // default
      xl('Listening port of the RFC 5425 TLS syslog server.')
    ),

    'atna_audit_localcert' => array(
      xl('ATNA audit local certificate'),
       'text',                          // data type
       '',                              // default
      xl('Certificate to send to RFC 5425 TLS syslog server.')
    ),

    'atna_audit_cacert' => array(
      xl('ATNA audit CA certificate'),
       'text',                          // data type
       '',                              // default
      xl('CA Certificate for verifying the RFC 5425 TLS syslog server.')
    ),

    //July 1, 2014: Ensoftek: Flag to enable/disable audit log encryption
    'enable_auditlog_encryption' => array(
      xl('Enable Audit Log Encryption'),
       'bool',                          // data type
       '0',                             // default
      xl('Enable Audit Log Encryption')
    ),

    'billing_log_option' => array(
      xl('Billing Log Option'),
      array(
        '1' => xl('Billing Log Append'),
        '2' => xl('Billing Log Overwrite')
      ),
       '1',                             // default
      xl('Billing log setting to append or overwrite the log file.')
    ),

    'gbl_print_log_option' => array(
      xl('Printing Log Option'),
      array(
        '0' => xl('No logging'),
        '1' => xl('Hide print feature'),
        '2' => xl('Log entire document'),
      ),
       '0',                             // default
      xl('Individual pages can override 2nd and 3rd options by implementing a log message.')
    ),
  ),

  // Miscellaneous Tab
  //
  'Miscellaneous' => array(

    'state_data_type' => array(
      xl('State Data Type'),
      array(
        '2' => xl('Text field'),
        '1' => xl('Single-selection list'),
       '26' => xl('Single-selection list with ability to add to the list'),
      ),
       '26',                            // default
      xl('Field type to use for employer or subscriber state in demographics.')
    ),

    'state_list' => array(
      xl('State list'),
       'text',                          // data type
       'state',                         // default
      xl('List used by above State Data Type option.')
    ),

    'state_custom_addlist_widget' => array(
      xl('State List Widget Custom Fields'),
       'bool',                          // data type
       '1',                             // default
      xl('Show the custom state form for the add list widget (will ask for title and abbreviation).')
    ),

    'country_data_type' => array(
      xl('Country Data Type'),
      array(
        '2' => xl('Text field'),
        '1' => xl('Single-selection list'),
       '26' => xl('Single-selection list with ability to add to the list'),
      ),
       '26',                            // default
      xl('Field type to use for employer or subscriber country in demographics.')
    ),

    'country_list' => array(
      xl('Country list'),
       'text',                          // data type
       'country',                       // default
      xl('List used by above Country Data Type option.')
    ),


    'MedicareReferrerIsRenderer' => array(
      xl('Medicare Referrer Is Renderer'),
       'bool',                          // data type
       '0',                             // default = true
      xl('For Medicare only, forces the referring provider to be the same as the rendering provider.')
    ),

    'post_to_date_benchmark' => array(
      xl('Financial Close Date (yyyy-mm-dd)'),
       'text',                          // data type
       date('Y-m-d',time() - (10 * 24 * 60 * 60)),
      xl('The payments posted cannot go below this date.This ensures that after taking the final report nobody post for previous dates.')
    ),

    'chart_label_type' => array(
        xl('Patient Label Type'),
        array(
            '0' => xl('None'),
            '1' => '5160',
            '2' => '5161',
            '3' => '5162'
    ),

        '1', // default
        xl('Avery Label type for printing patient labels from popups in left nav screen'),
    ),

    'barcode_label_type' => array(
        xl('Barcode Label Type'),
       array(
            '0'  => xl('None'),
            '1'  => 'std25',
            '2'  => 'int25',
            '3'  => 'ean8',
            '4'  => 'ean13',
            '5'  => 'upc',
            '6'  => 'code11',
            '7'  => 'code39',
            '8'  => 'code93',
            '9'  => 'code128',
            '10' => 'codabar',
            '11' => 'msi',
            '12' => 'datamatrix'
    ),

        '9',                              // default = None
        xl('Barcode type for printing barcode labels from popups in left nav screen.')
    ),

    'addr_label_type' => array(
        xl('Print Patient Address Label'),
       'bool',                          // data type
        '1',                              // default = false
        xl('Select to print patient address labels from popups in left nav screen.')
    ),

      'env_x_width' => array(
          xl('Envelope Width in mm'),
          'num',                           // data type
          '104.775',                              // default = false
          xl('In Portrait mode, determines the width of the envelope along the x-axis in mm')
      ),

      'env_y_height' => array(
          xl('Envelope Height in mm'),
          'num',                           // data type
          '241.3',                              // default = false
          xl('In Portrait mode, determines the height of the envelope along the y-axis in mm')
      ),

      'env_font_size' => array(
          xl('Font Size in Pt'),
          'num',                           // data type
          '14',                              // default = false
          xl('Sets the font of the address text on the envelope in mm')
      ),

      'env_x_dist' => array(
          xl('Envelope x-axis starting pt'),
          'num',                           // data type
          '65',                              // default = false
          xl('Distance from the \'top\' of the envelope in mm')
      ),

      'env_y_dist' => array(
          xl('Envelope y-axis starting pt'),
          'num',                           // data type
          '220',                              // default = false
          xl(' Distance from the right most edge of the envelope in portrait position in mm')
      ),
  ),

  // Portal Tab
  //
  'Portal' => array(

    'portal_onsite_enable' => array(
      xl('Enable Onsite Patient Portal'),
       'bool',                          // data type
       '0',
      xl('Enable Onsite Patient Portal.')
    ),

    'portal_onsite_address' => array(
      xl('Onsite Patient Portal Site Address'),
       'text',                          // data type
       'https://your_web_site.com/libreehr/patient_portal',
      xl('Website link for the Onsite Patient Portal.')
    ),

    'portal_onsite_two_basepath' => array(
      xl('Portal Uses Server Base Path (internal)'),
      'bool',
      '0',
      xl('Use servers protocol and host in urls (portal internal only).')
    ),

    'portal_onsite_two_register' => array(
      xl('Allow Onsite New Patient Registration Widget'),
        'bool',                           // data type
        '1',
      xl('Enable Onsite Patient Portal new patient to self register.')
    ),

    'portal_two_payments' => array(
      xl('Allow Onsite Online Payments'),
        'bool',                           // data type
        '0',
      xl('Allow Onsite Patient to make payments online.')
    ),

    'portal_two_pass_reset' => array(
      xl('Allow Patients to Reset Credentials'),
        'bool',                           // data type
        '0',
      xl('Patient may change their logon from portal login dialog.')
    ),

    //Terry Fix this in the 3rd release of portal
    'ccda_alt_service_enable' => array(
      xl('Enable C-CDA Alternate Service'),
      array(
          0 => xl('Off'),
          1 => xl('Care Coordination Only'),
          2 => xl('Portal Only'),
          3 => xl('Both'),
      ),
      '0',
      xl('Enable C-CDA Alternate Service')
    ),

    'portal_onsite_document_download' => array(
      xl('Enable Onsite Patient Portal Document Download'),
       'bool',                          // data type
       '1',
      xl('Enables the ability to download documents in the Onsite Patient Portal by the user.')
    ),

    'portal_onsite_appt_modify' => array(
      xl('Allow Users to Schedule Appointments in the Patient Portal'),
      'bool',                           // data type
      '0',                              // default = false
      xl('Allow Users to Schedule Appointments in the Patient Portal.')
    ),

    'portal_onsite_appt_modify' => array(
      xl('Allow Patient Modification of Appointments'),
      'bool',                           // data type
      '0',
      xl('Allow Patient Modification of Appointments in Onsite Patient Portal.')
    ),

    'portal_start_days' => array(
      xl('Number of Days from today to start Patients choice of Appointments'),
      'num',                           // data type
      '14',                            // Default
      xl('Number of Days from today to start Patients choice of Appointments in Onsite Patient Portal.')
    ),

    'portal_search_days' => array(
      xl('Number of Days for Patient choice of Appointments'),
      'num',                           // data type
      '7',                             // Default
      xl('Number of Days for Patient choice of Appointments in Onsite Patient Portal.')
    ),

    'portal_default_status' => array(
      xl('Default Status for Appointment Creation in the Patient Portal'),
      'status',                           // data type
      '- None',                                 // default = none
      xl('Default Status for Appointment Creation in the Patient Portal.')
    ),

  ),

  // Lab Tab
  //
  'Lab' => array(

    'lab_results_category_name' => array(
      xl('Lab Results Category Name'),
      'text',                           // data type
      'Lab Report',                     // default
      xl('Document category name for storage of electronically received lab results.')
    ),

    'gbl_mdm_category_name' => array(
      xl('MDM Document Category Name'),
       'text',                          // data type
       'Lab Report',                    // default
      xl('Document category name for storage of electronically received MDM documents.')
    ),

    ),

    // Mail Tab
  //
  'Mail' => array(

    'phimail_enable' => array(
      xl('Enable phiMail Direct Messaging Service'),
       'bool',                          // data type
       '0',
      xl('Enable phiMail Direct Messaging Service')
    ),

    'phimail_server_address' => array(
      xl('phiMail Server Address'),
       'text',                          // data type
       'https://phimail.example.com:32541',
      xl('Contact EMR Direct to subscribe to the phiMail Direct messaging service')
    ),

    'phimail_username' => array(
      xl('phiMail Username'),
       'text',                          // data type
       '',
      xl('Contact EMR Direct to subscribe to the phiMail Direct messaging service')
    ),

    'phimail_password' => array(
      xl('phiMail Password'),
       'pass',                          // data type
       '',
      xl('Contact EMR Direct to subscribe to the phiMail Direct messaging service')
    ),

    'phimail_notify' => array(
      xl('phiMail notification user'),
       'text',                          // data type
       'admin',
      xl('This user will receive notification of new incoming Direct messages')
    ),

    'phimail_interval' => array(
      xl('phiMail Message Check Interval (minutes)'),
       'num',                           // data type
       '5',
      xl('Interval between message checks (set to zero for manual checks only)')
    ),

    'phimail_ccd_enable' => array(
      xl('phiMail Allow CCD Send'),
       'bool',                          // data type
       '0',
      xl('phiMail Allow CCD Send')
    ),

    'phimail_ccr_enable' => array(
      xl('phiMail Allow CCR Send'),
       'bool',                          // data type
       '0',
      xl('phiMail Allow CCR Send')
    ),

    'patient_reminder_sender_name' => array(
      xl('Patient Reminder Sender Name'),
      'text',                           // data type
      '',                               // default
      xl('Name of the sender for patient reminders.')
    ),

    'patient_reminder_sender_email' => array(
      xl('Patient Reminder Sender Email'),
      'text',                           // data type
      '',                               // default
      xl('Email address of the sender for patient reminders. Replies to patient reminders will be directed to this address. It is important to use an address from your clinic\'s domain to avoid help prevent patient reminders from going to junk mail folders.')
    ),

    'practice_return_email_path' => array(
      xl('Notification Email Address'),
      'text',                           // data type
      '',                               // default
      xl('Email address, if any, to receive administrative notifications.')
    ),

    'EMAIL_METHOD' => array(
      xl('Email Transport Method'),
      array(
        'PHPMAIL'  => 'PHPMAIL',
        'SENDMAIL' => 'SENDMAIL',
        'SMTP'     => 'SMTP',
      ),
      'SMTP',                             // default
      xl('Method for sending outgoing email.')
  ),

    'SMTP_HOST' => array(
      xl('SMTP Server Hostname'),
      'text',                           // data type
      'localhost',                      // default
      xl('If SMTP is used, the server`s hostname or IP address.')
    ),

    'SMTP_PORT' => array(
      xl('SMTP Server Port Number'),
      'num',                            // data type
      '25',                             // default
      xl('If SMTP is used, the server`s TCP port number (usually 25).')
    ),

    'SMTP_USER' => array(
      xl('SMTP User for Authentication'),
      'text',                           // data type
      '',                               // default
      xl('Must be empty if SMTP authentication is not used.')
    ),

    'SMTP_PASS' => array(
      xl('SMTP Password for Authentication'),
      'pass',                           // data type
      '',                               // default
      xl('Must be empty if SMTP authentication is not used.')
    ),

    'SMTP_SECURE' => array(
      xl('SMTP Security Protocol'),
      array(
        '' => xl('None'),
        'ssl'  => 'SSL',
        'tls'  => 'TLS'
      ),
      '',
      xl('SMTP security protocol to connect with. Required by some servers such as gmail.')
    ),

    'EMAIL_NOTIFICATION_HOUR' => array(
      xl('Email Notification Hours'),
      'num',                            // data type
      '50',                             // default
      xl('Number of hours in advance to send email notifications.')
    ),

  ),

  // RX
  //
  'Rx' => array(

    'rx_enable_DEA' => array(
      xl('Rx Enable DEA #'),
       'bool',                          // data type
       '1',
      xl('Rx Enable DEA #')
    ),

    'rx_show_DEA' => array(
      xl('Rx Show DEA #'),
       'bool',                          // data type
       '0',
      xl('Rx Show DEA #')
    ),

    'rx_enable_NPI' => array(
      xl('Rx Enable NPI'),
       'bool',                          // data type
       '0',
      xl('Rx Enable NPI')
    ),

    'rx_show_NPI' => array(
      xl('Rx Show NPI'),
       'bool',                          // data type
       '0',
      xl('Rx Show NPI')
    ),

    'rx_enable_SLN' => array(
      xl('Rx Enable State Lic. #'),
       'bool',                          // data type
       '0',
      xl('Rx Enable State Lic. #')
    ),

    'rx_show_SLN' => array(
      xl('Rx Show State Lic. #'),
       'bool',                          // data type
       '0',
      xl('Rx Show State Lic. #')
    ),

    'rx_paper_size' => array(
      xl('Rx Paper Size'),              // descriptive name
      array(
        'LETTER' => xl('Letter Paper Size'),
        'LEGAL' => xl('Legal Paper Size'),
        'FOLIO' => xl('Folio Paper Size'),
        'EXECUTIVE' => xl('Executive Paper Size'),
        '4A0' => ('4A0' . " " . xl('Paper Size')),
        '2A0' => ('2A0' . " " . xl('Paper Size')),
        'A0' => ('A0' . " " . xl('Paper Size')),
        'A1' => ('A1' . " " . xl('Paper Size')),
        'A2' => ('A2' . " " . xl('Paper Size')),
        'A3' => ('A3' . " " . xl('Paper Size')),
        'A4' => ('A4' . " " . xl('Paper Size')),
        'A5' => ('A5' . " " . xl('Paper Size')),
        'A6' => ('A6' . " " . xl('Paper Size')),
        'A7' => ('A7' . " " . xl('Paper Size')),
        'A8' => ('A8' . " " . xl('Paper Size')),
        'A9' => ('A9' . " " . xl('Paper Size')),
        'A10' => ('A10' . " " . xl('Paper Size')),
        'B0' => ('B0' . " " . xl('Paper Size')),
        'B1' => ('B1' . " " . xl('Paper Size')),
        'B2' => ('B2' . " " . xl('Paper Size')),
        'B3' => ('B3' . " " . xl('Paper Size')),
        'B4' => ('B4' . " " . xl('Paper Size')),
        'B5' => ('B5' . " " . xl('Paper Size')),
        'B6' => ('B6' . " " . xl('Paper Size')),
        'B7' => ('B7' . " " . xl('Paper Size')),
        'B8' => ('B8' . " " . xl('Paper Size')),
        'B9' => ('B9' . " " . xl('Paper Size')),
        'B10' => ('B10' . " " . xl('Paper Size')),
        'C0' => ('C0' . " " . xl('Paper Size')),
        'C1' => ('C1' . " " . xl('Paper Size')),
        'C2' => ('C2' . " " . xl('Paper Size')),
        'C3' => ('C3' . " " . xl('Paper Size')),
        'C4' => ('C4' . " " . xl('Paper Size')),
        'C5' => ('C5' . " " . xl('Paper Size')),
        'C6' => ('C6' . " " . xl('Paper Size')),
        'C7' => ('C7' . " " . xl('Paper Size')),
        'C8' => ('C8' . " " . xl('Paper Size')),
        'C9' => ('C9' . " " . xl('Paper Size')),
        'C10' => ('C10' . " " . xl('Paper Size')),
        'RA0' => ('RA0' . " " . xl('Paper Size')),
        'RA1' => ('RA1' . " " . xl('Paper Size')),
        'RA2' => ('RA2' . " " . xl('Paper Size')),
        'RA3' => ('RA3' . " " . xl('Paper Size')),
        'RA4' => ('RA4' . " " . xl('Paper Size')),
        'SRA0' => ('SRA0' . " " . xl('Paper Size')),
        'SRA1' => ('SRA1' . " " . xl('Paper Size')),
        'SRA2' => ('SRA2' . " " . xl('Paper Size')),
        'SRA3' => ('SRA3' . " " . xl('Paper Size')),
        'SRA4' => ('SRA4' . " " . xl('Paper Size')),
      ),
       'LETTER',                        // default = tree menu
      xl('Rx Paper Size')
    ),

    'rx_left_margin' => array(
      xl('Rx Left Margin (px)'),
       'num',
       '30',
      xl('Rx Left Margin (px)')
    ),

    'rx_right_margin' => array(
      xl('Rx Right Margin (px)'),
       'num',
       '30',
      xl('Rx Right Margin (px)')
    ),

    'rx_top_margin' => array(
      xl('Rx Top Margin (px)'),
       'num',
       '72',
      xl('Rx Top Margin (px)')
    ),

    'rx_bottom_margin' => array(
      xl('Rx Bottom Margin (px)'),
       'num',
       '30',
      xl('Rx Bottom Margin (px)')
    ),

    'erx_enable' => array(
      xl('Enable NewCrop eRx Service'),
      'bool',
      '0',
      xl('Enable NewCrop eRx Service.') + ' ' +
      xl('Contact the community for information on subscribing to the NewCrop eRx service.')
  ),

    'erx_newcrop_path' => array(
      xl('NewCrop eRx Site Address'),
      'text',
      'https://secure.newcropaccounts.com/InterfaceV7/RxEntry.aspx',
      xl('URL for NewCrop eRx Site Address.')
    ),

    'erx_newcrop_path_soap' => array(
      xl('NewCrop eRx Web Service Address'),
      'text',
      'https://secure.newcropaccounts.com/v7/WebServices/Update1.asmx?WSDL;https://secure.newcropaccounts.com/v7/WebServices/Patient.asmx?WSDL',
      xl('URLs for NewCrop eRx Service Address, separated by a semi-colon.')
    ),

    'erx_soap_ttl_allergies' => array(
      xl('NewCrop eRx SOAP Request Time-To-Live for Allergies'),
      'num',
      '21600',
      xl('Time-To-Live for NewCrop eRx Allergies SOAP Request in seconds.')
    ),

    'erx_soap_ttl_medications' => array(
      xl('NewCrop eRx SOAP Request Time-To-Live for Medications'),
      'num',
      '21600',
      xl('Time-To-Live for NewCrop eRx Medications SOAP Request in seconds.')
    ),

    'erx_account_partner_name' => array(
      xl('NewCrop eRx Partner Name'),
      'text',
      '',
      xl('Partner Name issued for NewCrop eRx service.')
    ),

    'erx_account_name' => array(
      xl('NewCrop eRx Name'),
      'text',
      '',
      xl('Account Name issued for NewCrop eRx service.')
    ),

    'erx_account_password' => array(
      xl('NewCrop eRx Password'),
      'pass',
      '',
      xl('Account Password issued for NewCrop eRx service.')
    ),

    'erx_account_id' => array(
      xl('NewCrop eRx Account Id'),
      'text',
      '1',
      xl('Account Id issued for NewCrop eRx service, used to separate multi-facility accounts.')
    ),

    'erx_upload_active' => array(
      xl('Only upload active prescriptions'),
      'bool',
      '0',
      xl('Only upload active prescriptions to NewCrop eRx.')
    ),

    'erx_import_status_message' => array(
      xl('Enable NewCrop eRx import status message'),
      'bool',
      '0',
      xl('Enable import status message after visiting NewCrop eRx.')
    ),

    'erx_medication_display' => array(
      xl('Do not display NewCrop eRx Medications uploaded'),
      'bool',
      '0',
      xl('Do not display Medications uploaded after visiting NewCrop eRx.')
    ),

    'erx_allergy_display' => array(
      xl('Do not display NewCrop eRx Allergy uploaded'),
      'bool',
      '0',
      xl('Do not display Allergies uploaded after visiting NewCrop eRx.')
    ),

    'erx_default_patient_country' => array(
        xl('NewCrop eRx Default Patient Country'),
        array(
            '' => '',
            'US' => xl('USA'),
            'CA' => xl('Canada'),
            'MX' => xl('Mexico'),
        ),
        '',
        xl('Default Patient Country sent to NewCrop eRx, only if patient country is not set.'),
    ),

    'erx_debug_setting' => array(
        xl('NewCrop eRx Debug Setting'),
        array(
            0 => xl('None'),
            1 => xl('Request Only'),
            2 => xl('Response Only'),
            3 => xl('Request & Response'),
        ),
        '0',
        xl('Log all NewCrop eRx Requests and / or Responses.'),
    ),

  ),

  'PDF' => array (

   'pdf_layout' => array (
      xl('Layout'),
      array(
        'P' => xl('Portrait'),
        'L' => xl('Landscape')
      ),
       'P',                             // defaut
      xl("Choose Layout Direction"),
    ),

    'pdf_language' => array (
      xl('PDF Language'),
      array(
        'aa' => xl('Afar'),
        'af' => xl('Afrikaans'),
        'ak' => xl('Akan'),
        'sq' => xl('Albanian'),
        'am' => xl('Amharic'),
        'ar' => xl('Arabic'),
        'an' => xl('Aragonese'),
        'hy' => xl('Armenian'),
        'as' => xl('Assamese'),
        'av' => xl('Avaric'),
        'ae' => xl('Avestan'),
        'ay' => xl('Aymara'),
        'az' => xl('Azerbaijani'),
        'bm' => xl('Bambara'),
        'ba' => xl('Bashkir'),
        'eu' => xl('Basque'),
        'be' => xl('Belarusian'),
        'bn' => xl('Bengali- Bangla'),
        'bh' => xl('Bihari'),
        'bi' => xl('Bislama'),
        'bs' => xl('Bosnian'),
        'br' => xl('Breton'),
        'bg' => xl('Bulgarian'),
        'my' => xl('Burmese'),
        'ca' => xl('Catalan- Valencian'),
        'ch' => xl('Chamorro'),
        'ce' => xl('Chechen'),
        'ny' => xl('Chichewa- Chewa- Nyanja'),
        'zh' => xl('Chinese'),
        'cv' => xl('Chuvash'),
        'kw' => xl('Cornish'),
        'co' => xl('Corsican'),
        'cr' => xl('Cree'),
        'hr' => xl('Croatian'),
        'cs' => xl('Czech'),
        'da' => xl('Danish'),
        'dv' => xl('Divehi- Dhivehi- Maldivian-'),
        'nl' => xl('Dutch'),
        'dz' => xl('Dzongkha'),
        'en' => xl('English'),
        'eo' => xl('Esperanto'),
        'et' => xl('Estonian'),
        'ee' => xl('Ewe'),
        'fo' => xl('Faroese'),
        'fj' => xl('Fijian'),
        'fi' => xl('Finnish'),
        'fr' => xl('French'),
        'ff' => xl('Fula- Fulah- Pulaar- Pular'),
        'gl' => xl('Galician'),
        'ka' => xl('Georgian'),
        'de' => xl('German'),
        'el' => xl('Greek, Modern'),
        'gn' => xl('Guaraní'),
        'gu' => xl('Gujarati'),
        'ht' => xl('Haitian- Haitian Creole'),
        'ha' => xl('Hausa'),
        'he' => xl('Hebrew (modern)'),
        'hz' => xl('Herero'),
        'hi' => xl('Hindi'),
        'ho' => xl('Hiri Motu'),
        'hu' => xl('Hungarian'),
        'ia' => xl('Interlingua'),
        'id' => xl('Indonesian'),
        'ie' => xl('Interlingue'),
        'ga' => xl('Irish'),
        'ig' => xl('Igbo'),
        'ik' => xl('Inupiaq'),
        'io' => xl('Ido'),
        'is' => xl('Icelandic'),
        'it' => xl('Italian'),
        'iu' => xl('Inuktitut'),
        'ja' => xl('Japanese'),
        'jv' => xl('Javanese'),
        'kl' => xl('Kalaallisut, Greenlandic'),
        'kn' => xl('Kannada'),
        'kr' => xl('Kanuri'),
        'ks' => xl('Kashmiri'),
        'kk' => xl('Kazakh'),
        'km' => xl('Khmer'),
        'ki' => xl('Kikuyu, Gikuyu'),
        'rw' => xl('Kinyarwanda'),
        'ky' => xl('Kyrgyz'),
        'kv' => xl('Komi'),
        'kg' => xl('Kongo'),
        'ko' => xl('Korean'),
        'ku' => xl('Kurdish'),
        'kj' => xl('Kwanyama, Kuanyama'),
        'la' => xl('Latin'),
        'lb' => xl('Luxembourgish, Letzeburgesch'),
        'lg' => xl('Ganda'),
        'li' => xl('Limburgish, Limburgan, Limburger'),
        'ln' => xl('Lingala'),
        'lo' => xl('Lao'),
        'lt' => xl('Lithuanian'),
        'lu' => xl('Luba-Katanga'),
        'lv' => xl('Latvian'),
        'gv' => xl('Manx'),
        'mk' => xl('Macedonian'),
        'mg' => xl('Malagasy'),
        'ms' => xl('Malay'),
        'ml' => xl('Malayalam'),
        'mt' => xl('Maltese'),
        'mi' => xl('Māori'),
        'mr' => xl('Marathi (Marāṭhī)'),
        'mh' => xl('Marshallese'),
        'mn' => xl('Mongolian'),
        'na' => xl('Nauru'),
        'nv' => xl('Navajo, Navaho'),
        'nb' => xl('Norwegian Bokmål'),
        'nd' => xl('North Ndebele'),
        'ne' => xl('Nepali'),
        'ng' => xl('Ndonga'),
        'nn' => xl('Norwegian Nynorsk'),
        'no' => xl('Norwegian'),
        'ii' => xl('Nuosu'),
        'nr' => xl('South Ndebele'),
        'oc' => xl('Occitan'),
        'oj' => xl('Ojibwe, Ojibwa'),
        'cu' => xl('Old Church Slavonic, Church Slavonic, Old Bulgarian'),
        'om' => xl('Oromo'),
        'or' => xl('Oriya'),
        'os' => xl('Ossetian, Ossetic'),
        'pa' => xl('Panjabi, Punjabi'),
        'pi' => xl('Pāli'),
        'fa' => xl('Persian (Farsi)'),
        'pl' => xl('Polish'),
        'ps' => xl('Pashto, Pushto'),
        'pt' => xl('Portuguese'),
        'qu' => xl('Quechua'),
        'rm' => xl('Romansh'),
        'rn' => xl('Kirundi'),
        'ro' => xl('Romanian'),
        'ru' => xl('Russian'),
        'sa' => xl('Sanskrit (Saṁskṛta)'),
        'sc' => xl('Sardinian'),
        'sd' => xl('Sindhi'),
        'se' => xl('Northern Sami'),
        'sm' => xl('Samoan'),
        'sg' => xl('Sango'),
        'sr' => xl('Serbian'),
        'gd' => xl('Scottish Gaelic- Gaelic'),
        'sn' => xl('Shona'),
        'si' => xl('Sinhala, Sinhalese'),
        'sk' => xl('Slovak'),
        'sl' => xl('Slovene'),
        'so' => xl('Somali'),
        'st' => xl('Southern Sotho'),
        'es' => xl('Spanish- Castilian'),
        'su' => xl('Sundanese'),
        'sw' => xl('Swahili'),
        'ss' => xl('Swati'),
        'sv' => xl('Swedish'),
        'ta' => xl('Tamil'),
        'te' => xl('Telugu'),
        'tg' => xl('Tajik'),
        'th' => xl('Thai'),
        'ti' => xl('Tigrinya'),
        'bo' => xl('Tibetan Standard, Tibetan, Central'),
        'tk' => xl('Turkmen'),
        'tl' => xl('Tagalog'),
        'tn' => xl('Tswana'),
        'to' => xl('Tonga (Tonga Islands)'),
        'tr' => xl('Turkish'),
        'ts' => xl('Tsonga'),
        'tt' => xl('Tatar'),
        'tw' => xl('Twi'),
        'ty' => xl('Tahitian'),
        'ug' => xl('Uyghur, Uighur'),
        'uk' => xl('Ukrainian'),
        'ur' => xl('Urdu'),
        'uz' => xl('Uzbek'),
        've' => xl('Venda'),
        'vi' => xl('Vietnamese'),
        'vo' => xl('Volapük'),
        'wa' => xl('Walloon'),
        'cy' => xl('Welsh'),
        'wo' => xl('Wolof'),
        'fy' => xl('Western Frisian'),
        'xh' => xl('Xhosa'),
        'yi' => xl('Yiddish'),
        'yo' => xl('Yoruba'),
        'za' => xl('Zhuang, Chuang'),
        'zu' => xl('Zulu'),
       ),
       'en',                            // default English
      xl('Choose PDF languange Preference'),
    ),

    'pdf_size' => array(
      xl('Paper Size'),                // Descriptive Name
      array(
        'LETTER' => xl('Letter Paper Size'),
        'LEGAL' => xl('Legal Paper Size'),
        'FOLIO' => xl('Folio Paper Size'),
        'EXECUTIVE' => xl('Executive Paper Size'),
        '4A0' => ('4A0' . " " . xl('Paper Size')),
        '2A0' => ('2A0' . " " . xl('Paper Size')),
        'A0' => ('A0' . " " . xl('Paper Size')),
        'A1' => ('A1' . " " . xl('Paper Size')),
        'A2' => ('A2' . " " . xl('Paper Size')),
        'A3' => ('A3' . " " . xl('Paper Size')),
        'A4' => ('A4' . " " . xl('Paper Size')),
        'A5' => ('A5' . " " . xl('Paper Size')),
        'A6' => ('A6' . " " . xl('Paper Size')),
        'A7' => ('A7' . " " . xl('Paper Size')),
        'A8' => ('A8' . " " . xl('Paper Size')),
        'A9' => ('A9' . " " . xl('Paper Size')),
        'A10' => ('A10' . " " . xl('Paper Size')),
        'B0' => ('B0' . " " . xl('Paper Size')),
        'B1' => ('B1' . " " . xl('Paper Size')),
        'B2' => ('B2' . " " . xl('Paper Size')),
        'B3' => ('B3' . " " . xl('Paper Size')),
        'B4' => ('B4' . " " . xl('Paper Size')),
        'B5' => ('B5' . " " . xl('Paper Size')),
        'B6' => ('B6' . " " . xl('Paper Size')),
        'B7' => ('B7' . " " . xl('Paper Size')),
        'B8' => ('B8' . " " . xl('Paper Size')),
        'B9' => ('B9' . " " . xl('Paper Size')),
        'B10' => ('B10' . " " . xl('Paper Size')),
        'C0' => ('C0' . " " . xl('Paper Size')),
        'C1' => ('C1' . " " . xl('Paper Size')),
        'C2' => ('C2' . " " . xl('Paper Size')),
        'C3' => ('C3' . " " . xl('Paper Size')),
        'C4' => ('C4' . " " . xl('Paper Size')),
        'C5' => ('C5' . " " . xl('Paper Size')),
        'C6' => ('C6' . " " . xl('Paper Size')),
        'C7' => ('C7' . " " . xl('Paper Size')),
        'C8' => ('C8' . " " . xl('Paper Size')),
        'C9' => ('C9' . " " . xl('Paper Size')),
        'C10' => ('C10' . " " . xl('Paper Size')),
        'RA0' => ('RA0' . " " . xl('Paper Size')),
        'RA1' => ('RA1' . " " . xl('Paper Size')),
        'RA2' => ('RA2' . " " . xl('Paper Size')),
        'RA3' => ('RA3' . " " . xl('Paper Size')),
        'RA4' => ('RA4' . " " . xl('Paper Size')),
        'SRA0' => ('SRA0' . " " . xl('Paper Size')),
        'SRA1' => ('SRA1' . " " . xl('Paper Size')),
        'SRA2' => ('SRA2' . " " . xl('Paper Size')),
        'SRA3' => ('SRA3' . " " . xl('Paper Size')),
        'SRA4' => ('SRA4' . " " . xl('Paper Size')),
      ),
       'LETTER',
      xl('Choose Paper Size')
    ),

    'pdf_left_margin' => array(
      xl('Left Margin (mm)'),
       'num',
       '5',
      xl('Left Margin (mm)')
    ),

    'pdf_right_margin' => array(
      xl('Right Margin (mm)'),
       'num',
       '5',
      xl('Right Margin (mm)')
    ),

    'pdf_top_margin' => array(
      xl('Top Margin (mm)'),
       'num',
       '5',
      xl('Top Margin (mm)')
    ),

    'pdf_bottom_margin' => array(
      xl('Bottom Margin (px)'),
       'num',
       '8',
      xl('Bottom Margin (px)')
    ),

    'pdf_output' => array (
      xl('Output Type'),
      array(
        'D' => xl('Download'),
        'I' => xl('Inline')
      ),
       'D',                             // default
      xl("Choose Download or Display Inline"),
    ),

   ),

  // Security Tab
  //
  'Security' => array(

    'timeout' => array(
      xl('Idle Session Timeout Seconds'),
      'num',                            // data type
      '7200',                           // default
      xl('Maximum idle time in seconds before logout. Default is 7200 (2 hours).')
    ),

    'secure_password' => array(
      xl('Require Strong Passwords'),
      'bool',                           // data type
      '0',                              // default
      xl('Strong password means at least 8 characters, and at least three of: a number, a lowercase letter, an uppercase letter, a special character.')
      ),

    'password_history' => array(
      xl('Require Unique Passwords'),
      'bool',                           // data type
      '0',                              // default
      xl('Means none of last three passwords are allowed when changing a password.')
    ),
    'password_compatibility' => array(
      xl('Permit unsalted passwords'),
      'bool',                           // data type
       '1',                             // default
      xl('After migration from the old password mechanisms where passwords are stored in the users table without salt is complete, this flag should be set to false so that only authentication by the new method is possible')
    ),

    'password_expiration_days' => array(
      xl('Default Password Expiration Days'),
      'num',                            // data type
      '0',                              // default
      xl('Default password expiration period in days. 0 means this feature is disabled.')
      ),

    'password_grace_time' => array(
      xl('Password Expiration Grace Period'),
      'num',                            // data type
      '0',                              // default
      xl('Period in days where a user may login with an expired password.')
    ),

    'is_client_ssl_enabled' => array(
      xl('Enable Client SSL'),
       'bool',                          // data type
      '0',                              // default
      xl('Enable client SSL certificate authentication.')
    ),

    'certificate_authority_crt' => array(
      xl('Path to CA Certificate File'),
      'text',                           // data type
      '',                               // default
      xl('Set this to the full absolute path. For creating client SSL certificates for HTTPS.')
    ),

    'certificate_authority_key' => array(
      xl('Path to CA Key File'),
      'text',                           // data type
      '',                               // default
      xl('Set this to the full absolute path. For creating client SSL certificates for HTTPS.')
    ),

    'client_certificate_valid_in_days' => array(
      xl('Client Certificate Expiration Days'),
      'num',                            // data type
      '365',                            // default
      xl('Number of days that the client certificate is valid.')
    ),

    'Emergency_Login_email_id' => array(
      xl('Emergency Login Email Address'),
      'text',                           // data type
      '',                               // default
      xl('Email address, if any, to receive emergency login user activation messages.')
    ),

  ),

    // System Tab
    //
    'System' => array(

    'mysql_bin_dir' => array(
      xl('Path to MySQL Binaries'),
      'text',                           // data type
      $mysql_bin_dir,                   // default
      xl('Full path to directory containing MySQL executables.')
    ),

    'perl_bin_dir' => array(
      xl('Path to Perl Binaries'),
      'text',                           // data type
      $perl_bin_dir,                    // default
      xl('Full path to directory containing Perl executables.')
  ),

    'temporary_files_dir' => array(
      xl('Path to Temporary Files'),
      'text',                           // data type
      $temporary_files_dir,             // default
      xl('Full path to directory used for temporary files.')
    ),

    'backup_log_dir' => array(
      xl('Path for Event Log Backup'),
      'text',                           // data type
      $backup_log_dir,                  // default
      xl('Full path to directory for event log backup.')
    ),

    'print_command' => array(
      xl('Print Command'),
      'text',                           // data type
      'lpr -P HPLaserjet6P -o cpi=10 -o lpi=6 -o page-left=72 -o page-top=72',
      xl('Shell command for printing from the server.')
    ),

    'gb_how_sort_list' => array(
      xl('How to sort the lists and categories'),
      array(
        '0' => 'Sort by seq',
        '1' => 'Sort alphabetically'
      ),
      '0',
      xl('What kind of sorting will be used for the lists and categories.')
    ),

    'gb_how_sort_categories' => array(
      xl('How to sort the categories'),
      array(
        '0' => 'Sort by seq',
        '1' => 'Sort alphabetically'
      ),
      '1',
      xl('What kind of sorting will be used for the categories.')
    ),

  ),

    // Fax Tab
    //
    'Fax' => array(

    'enable_hylafax' => array(
      xl('Enable Hylafax Support'),
      'bool',                           // data type
      '0',                              // default
      xl('Enable Hylafax Support')
    ),

    'hylafax_server' => array(
      xl('Hylafax Server'),
      'text',                           // data type
      'localhost',                      // default
      xl('Hylafax server hostname.')
    ),

    'hylafax_basedir' => array(
      xl('Hylafax Directory'),
      'text',                           // data type
      '/var/spool/fax',                 // default
      xl('Location where Hylafax stores faxes.')
    ),

    'hylafax_enscript' => array(
      xl('Hylafax Enscript Command'),
      'text',                           // data type
      'enscript -M Letter -B -e^ --margins=36:36:36:36', // default
      xl('Enscript command used by Hylafax.')
    ),

  ),

    // Scanner Tab
    //
    'Scanner' => array(

    'enable_scanner' => array(
      xl('Enable Scanner Support'),
      'bool',                           // data type
      '0',                              // default
      xl('Enable Scanner Support')
    ),

    'scanner_output_directory' => array(
      xl('Scanner Directory'),
      'text',                           // data type
      '/mnt/scan_docs',                 // default
      xl('Location where scans are stored.')
    ),

  ),
    'MIPS' => array(

    'enable_pqrs' => array(
      xl('Enable Physician Quality Reporting System (MIPS)'),
      'bool',                           // data type
      '1',                               // default
      xl('Enable Physician Quality Reporting System (MIPS)')
    ),

    'pqrs_demosystem' => array(
      xl('This is a MIPS demo system'),
      'bool',                           // data type
      '0',                               // default
      xl('Show demo system "Save/Load database presets" menu')
    ),

    'report_itemizing_pqrs' => array(
      xl('Enable MIPS report itemization'),     // for itemizing reports
      'bool',                           // data type
      '1',                     // default
      xl('Creates patient lists from reports')
    ),


    'pqrs_creator' => array(
      xl('MIPS Report Creator Name'),       // for XML generation
      'text',                           // data type
      'FIXME creator FIXME!!!',                     // default
      xl('MIPS Report Creator Name')
    ),

    'pqrs_registry_name' => array(
      xl('MIPS Registry Name'),     // for XML generation
      'text',                           // data type
      'FIXME registry name FIXME!!!',               // default
      xl('MIPS Registry Name')
    ),

    'pqrs_registry_id' => array(
      xl('MIPS Registry ID'),       // for XML generation
      'text',                           // data type
      'FIXME registry id FIXME!!!',                 // default
      xl('MIPS Registry ID')
    ),

    'pqrs_vendor_unique_id' => array(
      xl('MIPS VENDOR UNIQUE ID'),  // for XML generation
      'text',                           // data type
      'FIXME vendor unique id FIXME!!!',            // default
      xl('MIPS Registry Name')
    ),
     'pqrs_attestation_date' => array(
      xl('Default Direct Entry Date'),
      'text',                           // data type
      '2017-06-06',            // default
      xl('Default date that direct entry encounters will be created on.')
    ),
  ),

);

if ( function_exists( 'do_action' ) ) {
    do_action( 'globals_init', $args = [
        'global_metadata' => $GLOBALS_METADATA,
        'user_specific_globals' => $USER_SPECIFIC_GLOBALS,
        'user_specific_tabs' => $USER_SPECIFIC_TABS ] );
}

?>
