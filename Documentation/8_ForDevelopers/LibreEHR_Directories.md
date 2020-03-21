
***This document covered under MPL License.***

LibreEHR Directory structure


1. **CCR**


2. **CONTRIB**


3. **CONTROLLERS**


4. **CUSTOM**


5. **DOCUMENTATION**


6. **GACL**


7. **IMAGES**


8. **INTERFACE**


9. **LIBRARY**


10. **MODULES**


11. **PATIENTS**


12. **SITES**


13. **SQL**


14. **TEMPLATES**


15. **TESTS**


**CCR**

This Directory contains the programs used by the onsite portal to create the CCR and CCD documents for Meaningful Use Stage 1. This is currently obsolete.

**CONTRIB**

This directory contains the programs that are contributed to codebase but not used in the actual software. It also contains the loads for ICD10, SNOWMED and RXNORM. There is a UTIL directory that contains some useful tools. I have used dupcheck check for duplicate patients. The merging of  patients is now an menu item under Administration->Other.

**CONTROLLERS**

This directory contains some of the various controllers. I do not believe that all of the controllers are stored here.

**CUSTOM**

This directory contains programs that don't have a good place to reside in the code base. It can also contain custom vendor specific code. I think the original idea was this directory would not be updated and thus maintaining the vendors custom code.

**DOCUMENTATION**

This directory is currently being purged by LibreEHR 95% of this is out of date and not pertinent to the latest versions. The file structures and and tables have changed greatly from what is in this directory.

**GACL**

This directory contains the add on for program Access Control. This section is not changes or altered by developers, it's really not understood by most.

**IMAGES**

This is where images are stored that are used in the core software and do not vary by site in a multi-site environment.

**INTERFACE**

This directory contains the majority of the code. The directories contained in the interface directory are as follows:

**batchcom**
Code in this directory is for running batch communication. I do not know if it is usable or not.

**billing**
This is where the billing programs reside. The claims, electronic remittance and posting programs etc.

**clickmap**
This directory houses programs used for the original graphical pain map program.

**code_systems**
This directory contains programs for displaying instruction when using the external data loads.

**drugs**
This directory contains programs related to drugs.

**esign**
This directory is for the ESIGN programs.

**fax**
This directory contains programs for the hylafax fax server.

**forms**
This Directory has the forms used in the system.

**forms_admin**
This directory has the programs to administer the forms directory.

**Includes**
This directory should be removed in future versions.

**language**
This directory contains programs for maintaining the various languages.

**Login**
This contains programs for generating the login screen.

**Logview**
Contains programs for viewing the software log files.

**Main**
This directory contains directories for the calendar , patient finder, authorizations, office notes, Dated reminders and messages. It also contains the menu programs ,backup programs , and the about page.

**Modules**
This directory is used for the adding of custom and ZEND modules.

**New**
This directory contains the New Patient create screen.

**Orders**
This is for lab orders

**patient_file**
This directory contains programs programs used in the Demographics and Patient Encounter.

**patient_tracker**
This contains the Patient Flow Board programs.

**Pic**
This is an image directory (that needs to be migrated to one of the other ones)

**practice**
This contains 2 programs for editing the insurance information in demographics and the new patient screens

**reports**
This directory contains the reports used in the software.

**soap_functions**
This contains programs used by NewCrop.

**super**
This directory contains the programs for globals, lists and layout maintenance. It also contains a directory for the rules engine.

**tags_filters**
Directory contains files for the tabs programs. This allows the including of modules at points in the software with out changes to the core code.

**Themes**
This contains the themes for the system.

**Usergroup**
This directory contains the programs for facility, address book, user info and user group programs.


Files in the root of the interface directory:

This file is not used
errorWin.php  

These eRx files are used by newcrop e-prescribing,
eRx.php            
eRxGlobals.php
eRxPage.php
eRxSOAP.php
eRxStore.php
eRxXMLBuilder.php


globals.php is a configuration file for the software.


index.php

login_screen.php

logout.php


**LIBRARY**

This contains the includes and classes used in interface and elsewhere in the code.

**adodb**
Contains programs for ADOdb utilities

**ajax**
Contains various ajax programs

**authentication**
Contains programs used in pass phrase authentication.

**classes**
Contains various classes

**css**
CSS files for the system. Should be migrated to the public/assets directory

**custom_template**
Uses ckeditor to create custom templates.

**edihistory**
Currently outdated, being moved to /modules or external plugin:  Edi History  module used for claim and payment display and manipliation

**Esign**
Classes and programs used for the Esign function

**fonts**
Contains fonts used in the code.

**fpdf**
Used in the creation of print to pdf

**html2pdf**
moved to /modules Used in the creation of print to pdf

**js**
Being done away with contents being migrated to /assets in future versions

**openflashchart**
 Moved to /modules Contains programs used for graphing items with in the code.

**Phpseclib**
Contains programs used in the creation of SSL certificates

**pluginsystem**
Contains programs for support of the api plugins

**Smarty**
Smarty templates used in calendar and practice utilities

**Vendor**
Directory contains files related to vendor needs.


Files contained in the root directory of Library are listed here

acl.inc
acl_upgrade_fx.php
amc.php
api.inc
appointments.inc.php
auth.inc
billing.inc
billrep.inc
calendar.inc
calendar.js
calendar_events.inc.php
Claim.class.php
clinical_rules.php
coding.inc.php
create_ssl_certificate.php
csv_like_join.php
dated_reminder_functions.php
date_functions.php
daysheet.inc.php
dialog.js
direct_message_check.inc
documents.php
dynarch_calendar.css
dynarch_calendar.js
dynarch_calendar_en.inc.php,
dynarch_calendar_en.js
dynarch_calendar_setup.js
edi.inc
edi_276.inc.php
encounter.inc
encounter_events.inc.php
erx_javascript.inc.php
formatting.inc.php
formdata.inc.php
forms.inc
gen_hcfa_1500.inc.php
gen_hcfa_1500_02_12.inc.php
gen_x12_837.inc.php
globals.inc.php
gprelations.inc.php
htmlspecialchars.inc.php
immunization_helper.php
invoice_summary.inc.php
ippf_issues.inc.php
lab_exchange_api.php
lists.inc
log.inc
maviq_phone_api.php
menuarrow.gif
openssl.cnf
options.inc.php
options.js.php
options_listadd.inc
overlib_mini.js
parse_era.inc.php
parse_patient_xml.php
patient.inc
patient_tracker.inc.php
payment.inc.php
payment_jav.inc.php
phpmailer.lang-en.php
pid.inc
pnotes.inc
registry.inc
reminders.php
report.inc
report_database.inc
restoreSession.php
sanitize.inc.php
sl_eob.inc.php
sql.inc
sql-ccr.inc
sqlconf.php
sql_upgrade_fx.php
standard_tables_capture.inc
textformat.js
tooltip.js
topdialog.js
transactions.inc
translation.inc.php
user.inc
xmltoarray_parser_htmlfix.php



**MODULES**

This directory is designed to be used for the adding of modules to the software, this is still in the development stages.

**PATIENTS**

This is the directory for the onsite portal.

**SITES**

This is the directory where multi-site information is stored. Items that are only for the individual sites like the main statement print program, the scanned documents , Letter templates and 837/835 information for examples

**SQL**

This is the directory that stores SQL filed for table creation. This currently contains the main sql file , database.sql , for the creation of a new database and the upgrade scripts from one version to the next.

**TEMPLATES**

This is the directory that stores the Smarty templates used in the software. Used for displaying of the practice information, insurance information X12 information ETC found in the administration section of the Left Navigation Panel

**TESTS**

The start of the code to do automated testing
