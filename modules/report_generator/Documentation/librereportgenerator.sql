SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `librereportgenerator`
--

-- --------------------------------------------------------

--
-- Dumping data for table `draggable_components`
--

INSERT INTO `draggable_components` (`id`, `option_id`, `is_default`, `user`, `title`, `order`, `active`, `note`, `toggle_sort`, `toggle_display`, `created_at`, `updated_at`) VALUES
(1, 'pfullname', 1, 'default', 'Patient Fullname', 5, 1, 'patient_data:fname:mname:lname', 0, 0, NULL, NULL),
(2, 'plfname', 1, 'default', 'Patient Last Firstname', 10, 1, 'patient_data:lname:fname', 0, 0, NULL, NULL),
(3, 'pflname', 1, 'default', 'Patient First Lastname', 15, 1, 'patient_data:fname:lname', 0, 0, NULL, NULL),
(4, 'preversedname', 1, 'default', 'Patient Full reversed name', 20, 1, 'patient_data:lname:mname:fname', 0, 0, NULL, NULL),
(5, 'pfmname', 1, 'default', 'Patient First Middlename', 25, 1, 'patient_data:fname:mname', 0, 0, NULL, NULL),
(6, 'pid', 1, 'default', 'Patient Id', 30, 1, 'patient_data:id', 0, 0, NULL, NULL),
(7, 'pstreet', 1, 'default', 'Patient Street', 35, 1, 'patient_data:street', 0, 0, NULL, NULL),
(8, 'pcity', 1, 'default', 'Patient First Lastname', 40, 1, 'patient_data:city', 0, 0, NULL, NULL),
(9, 'pstate', 1, 'default', 'Patient Full reversed name', 45, 1, 'patient_data:lname:mname:fname', 0, 0, NULL, NULL),
(10, 'pstreetcity', 1, 'default', 'Patient Street City', 50, 1, 'patient_data:street:city', 0, 0, NULL, NULL),
(11, 'pcitystate', 1, 'default', 'Patient City State', 55, 1, 'patient_data:city:state', 0, 0, NULL, NULL),
(12, 'pstreetcitystate', 1, 'default', 'Patient Street City State', 60, 1, 'patient_data:street:city:state', 0, 0, NULL, NULL),
(13, 'ppcode', 1, 'default', 'Patient Postal Code', 65, 1, 'patient_data:postal_code', 0, 0, NULL, NULL),
(14, 'poccupation', 1, 'default', 'Patient Occupation', 70, 1, 'patient_data:occupation', 0, 0, NULL, NULL),
(15, 'pemail', 1, 'default', 'Patient Email', 75, 1, 'patient_data:email', 0, 0, NULL, NULL),
(16, 'pphone', 1, 'default', 'Patient Phone Contact', 80, 1, 'patient_data:phone_contact', 0, 0, NULL, NULL),
(17, 'planguage', 1, 'default', 'Patient Language', 85, 1, 'patient_data:language', 0, 0, NULL, NULL),
(18, 'pregdate', 1, 'default', 'Patient Register date', 90, 1, 'patient_data:regdate', 0, 0, NULL, NULL),
(19, 'psex', 1, 'default', 'Patient Sex', 95, 1, 'patient_data:sex', 0, 0, NULL, NULL),
(20, 'pemailphone', 1, 'default', 'Patient Email Phone', 100, 1, 'patient_data:email:phone_contact', 0, 0, NULL, NULL),
(21, 'pidfullname', 1, 'default', 'Patient PID Fullname', 105, 1, 'patient_data:id:fname:mname:lname', 0, 0, NULL, NULL),
(22, 'pdob', 1, 'default', 'Patient Date of birth', 110, 1, 'patient_data:DOB', 0, 0, NULL, NULL),
(23, 'pss', 1, 'default', 'Patient SS', 115, 1, 'patient_data:ss', 0, 0, NULL, NULL),
(24, 'pstatus', 1, 'default', 'Patient Status', 120, 1, 'patient_data:status', 0, 0, NULL, NULL),
(25, 'preferrer', 1, 'default', 'Patient Referrer', 125, 1, 'patient_data:referrer', 0, 0, NULL, NULL),
(26, 'pstatussex', 1, 'default', 'Patient Status Sex', 130, 1, 'patient_data:status:sex', 0, 0, NULL, NULL),
(27, 'pidnamecontact', 1, 'default', 'Patient ID Name Contact', 135, 1, 'patient_data:id:fname:mname:phone_contact:email', 0, 0, NULL, NULL),
(28, 'pidnameAddress', 1, 'default', 'Patient ID Name Address', 140, 1, 'patient_data:id:fname:mname:street:city:state:postal_code', 0, 0, NULL, NULL),
(29, 'poccupationmonthlyincome', 1, 'default', 'Patient Occupation Monthly Income', 145, 1, 'patient_data:occupation:monthly_income', 0, 0, NULL, NULL),
(30, 'psexstatusfamilysize', 1, 'default', 'Patient Sex Status Family Size', 150, 1, 'patient_data:sex:status:family_size', 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Dumping data for table `draggable_component_report_format`
--

INSERT INTO `draggable_component_report_format` (`id`, `draggable_component_id`, `report_format_id`, `created_at`, `updated_at`) VALUES
(1, 21, 1, '2018-07-05 09:16:37', '2018-07-05 09:16:37'),
(2, 12, 1, '2018-07-05 09:16:37', '2018-07-05 09:16:37'),
(3, 19, 1, '2018-07-05 09:16:37', '2018-07-05 09:16:37'),
(4, 5, 2, '2018-07-05 09:21:59', '2018-07-05 09:21:59'),
(5, 20, 2, '2018-07-05 09:21:59', '2018-07-05 09:21:59'),
(6, 12, 2, '2018-07-05 09:21:59', '2018-07-05 09:21:59'),
(7, 21, 3, '2018-07-05 09:36:19', '2018-07-05 09:36:19'),
(8, 30, 3, '2018-07-05 09:36:19', '2018-07-05 09:36:19'),
(9, 21, 4, '2018-07-05 09:37:39', '2018-07-05 09:37:39'),
(10, 25, 4, '2018-07-05 09:37:39', '2018-07-05 09:37:39'),
(11, 18, 4, '2018-07-05 09:37:39', '2018-07-05 09:37:39'),
(12, 21, 5, '2018-07-05 09:40:59', '2018-07-05 09:40:59'),
(13, 23, 5, '2018-07-05 09:40:59', '2018-07-05 09:40:59'),
(14, 13, 5, '2018-07-05 09:40:59', '2018-07-05 09:40:59'),
(15, 15, 5, '2018-07-05 09:40:59', '2018-07-05 09:40:59');

-- --------------------------------------------------------

--
-- Dumping data for table `report_formats`
--

INSERT INTO `report_formats` (`id`, `user`, `title`, `description`, `system_feature_id`, `created_at`, `updated_at`) VALUES
(1, 'default', 'Patient List', 'List of all patients with basic demographics.', 1, '2018-07-05 09:16:37', '2018-07-05 09:16:37'),
(2, 'default', 'Patients Contacts and Address', 'List of all patient\'s email, phone, street, city and state.', 1, '2018-07-05 09:21:59', '2018-07-05 09:21:59'),
(3, 'default', 'Patient Family Size', 'Information on patient\'s family size', 1, '2018-07-05 09:36:19', '2018-07-05 09:36:19'),
(4, 'default', 'Patient Registration', 'Information on patient\'s registrars, and date.', 1, '2018-07-05 09:37:39', '2018-07-05 09:37:39'),
(5, 'default', 'Patient SS', 'Patients\' SS and postal codes', 1, '2018-07-05 09:40:59', '2018-07-05 09:40:59');

-- --------------------------------------------------------

--
-- Dumping data for table `system_features`
--

INSERT INTO `system_features` (`id`, `name`, `description`, `user`, `created_at`, `updated_at`) VALUES
(1, 'Clients', 'Reports related to clients', 'default', '2018-07-02 04:43:04', '2018-07-02 04:43:04'),
(2, 'Financial', 'Reports related to finance and payments.', 'default', '2018-07-02 08:20:40', '2018-07-02 08:20:40'),
(3, 'Visits', 'Reports concerning patients and other visits.', 'default', '2018-07-04 23:00:00', '2018-07-04 23:00:00'),
(4, 'Procedures', 'Procedure statistics, and other pending orders', 'default', '2018-07-04 23:00:00', '2018-07-04 23:00:00'),
(5, 'Insurance', 'Reports related to patients\' insurance, and other related issues.', 'default', '2018-07-04 23:00:00', '2018-07-04 23:00:00'),
(6, 'Inventory', 'Reports related to inventory at various level in the system.', 'default', '2018-07-04 23:00:00', '2018-07-04 23:00:00');
