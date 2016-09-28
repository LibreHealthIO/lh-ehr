# This is only need on existing OpenEMR sites
RENAME TABLE `openemr_module_vars` TO `libreehr_module_vars`;
RENAME TABLE `openemr_modules` TO `libreehr_modules`;
RENAME TABLE `openemr_postcalendar_categories` TO `libreehr_postcalendar_categories`;
RENAME TABLE `openemr_postcalendar_events` TO `libreehr_postcalendar_events`;
RENAME TABLE `openemr_postcalendar_limits` TO `libreehr_postcalendar_limits`;
RENAME TABLE `openemr_postcalendar_topics` TO `libreehr_postcalendar_topics`;
RENAME TABLE `openemr_session_info` TO `libreehr_session_info`;
RENAME TABLE `openemr_module_vars` TO `libreehr_module_vars`;
UPDATE `forms` SET `form_name` = 'Patient Encounter' WHERE `form_name` = 'New Patient Encounter';
UPDATE `registry` SET `name` = 'Patient Encounter', `directory` = 'patient_encounter' WHERE `directory` = 'newpatient';
ALTER TABLE facility ADD COLUMN alias VARCHAR(60) default NULL;
