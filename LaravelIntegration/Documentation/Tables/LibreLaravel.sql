-- MySQL dump 10.13  Distrib 5.7.18, for Linux (x86_64)
--
-- Host: localhost    Database: LibreLaravel
-- ------------------------------------------------------
-- Server version	5.7.18-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Autoincrement Primary Key.',
  `line1` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Line 1 of address.',
  `line2` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Line 2 of address. Optional',
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'city',
  `state` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'state',
  `zip` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'zip code',
  `plus_four` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'plus four code. US specific thing',
  `country` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Country',
  `country_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Country Code',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `amendment_histories`
--

DROP TABLE IF EXISTS `amendment_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `amendment_histories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `amendmentID` int(10) unsigned NOT NULL COMMENT 'Foreign key to amendments table.',
  `created_by` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `amendment_status` int(10) unsigned NOT NULL COMMENT 'Amendment Status',
  `amendment_note` text COLLATE utf8mb4_unicode_ci COMMENT 'Amendment Note',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `amendment_histories_amendmentid_foreign` (`amendmentID`),
  KEY `amendment_histories_created_by_foreign` (`created_by`),
  CONSTRAINT `amendment_histories_amendmentid_foreign` FOREIGN KEY (`amendmentID`) REFERENCES `amendments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `amendment_histories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `amendment_histories`
--

LOCK TABLES `amendment_histories` WRITE;
/*!40000 ALTER TABLE `amendment_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `amendment_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `amendments`
--

DROP TABLE IF EXISTS `amendments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `amendments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign key to patient_datas table',
  `created_by` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table. This is the user who created amendment',
  `modified_by` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table. This is the user who modified amendment',
  `amendment_date` date NOT NULL COMMENT 'Amendment Date',
  `amendment_status` int(10) unsigned NOT NULL COMMENT 'Ammendment Status. 0->rejected, 1->accepted, 2->null',
  `amendment_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Amendment requested from',
  `amendment_desc` text COLLATE utf8mb4_unicode_ci COMMENT 'Amendment Description',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `amendments_pid_foreign` (`pid`),
  KEY `amendments_created_by_foreign` (`created_by`),
  KEY `amendments_modified_by_foreign` (`modified_by`),
  CONSTRAINT `amendments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `amendments_modified_by_foreign` FOREIGN KEY (`modified_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `amendments_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `amendments`
--

LOCK TABLES `amendments` WRITE;
/*!40000 ALTER TABLE `amendments` DISABLE KEYS */;
/*!40000 ALTER TABLE `amendments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facilities`
--

DROP TABLE IF EXISTS `facilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facilities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `addressID` int(10) unsigned NOT NULL COMMENT 'Foreign key to addresses table.',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Facility Name',
  `federal_ein` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tax Identifier for the business.',
  `phone` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Phone Number',
  `fax` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Fax Number',
  `website` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Web Site',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Email Id',
  `service_location` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Type of facility that shows up in the encounter form as a Service Location.',
  `billing_location` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Shows up in the form_encounter in the dropdown for Billing Facility.',
  `accept_assignment` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Flag to control payments processing.',
  `pos_code` int(10) unsigned NOT NULL COMMENT 'Vital code that is pulled from the Service Facility in an encounter that indicates what type of place of service it is, such as Office, Inpatient Hospital, Home, Mental Health Facility, etc.',
  `attn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Field of value like Claims Department, or John the Billing Desk Guy',
  `domain_identifier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `facility_npi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Defines Group National Provider Identifier, or a kind of UUID.',
  `tax_id_type` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Indicates that if it is a Employer ID Number or Personal Tax Number.',
  `color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'To mark the physical location of a appointment is so the user can visually sort them.',
  `primary_business_entity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identifies if this facility is a listing for the actual business running everything.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `facilities_addressid_foreign` (`addressID`),
  CONSTRAINT `facilities_addressid_foreign` FOREIGN KEY (`addressID`) REFERENCES `addresses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facilities`
--

LOCK TABLES `facilities` WRITE;
/*!40000 ALTER TABLE `facilities` DISABLE KEYS */;
/*!40000 ALTER TABLE `facilities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_aftercare_plans`
--

DROP TABLE IF EXISTS `form_aftercare_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_aftercare_plans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement.',
  `encounter` int(10) unsigned NOT NULL COMMENT 'Foreign key to form_encounters table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign Key to patient_datas table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `provider` int(10) unsigned NOT NULL COMMENT 'Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet',
  `date` datetime NOT NULL COMMENT 'Date when this form filled.',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Means a clinician (physician, etc...) has verified this form as part of the client record',
  `activity` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'A delete flag. 0 -> Yes | 1 -> No',
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Name of Patient.',
  `admit_date` date NOT NULL COMMENT 'Date when patient is admitted',
  `discharged` date DEFAULT NULL COMMENT 'Date when patient is dischanrged.',
  `goal_a_acute_intoxication` text COLLATE utf8mb4_unicode_ci COMMENT 'Look at form UI.',
  `goal_a_acute_intoxication_I` text COLLATE utf8mb4_unicode_ci COMMENT 'Look at form UI.',
  `goal_a_acute_intoxication_II` text COLLATE utf8mb4_unicode_ci COMMENT 'Look at form UI.',
  `goal_b_emotional_behavioal_conditions` text COLLATE utf8mb4_unicode_ci COMMENT 'Look at form UI.',
  `goal_b_emotional_behavioal_conditions_I` text COLLATE utf8mb4_unicode_ci COMMENT 'Look at form UI.',
  `goal_c_relapse_potential` text COLLATE utf8mb4_unicode_ci COMMENT 'Look at form UI.',
  `goal_c_relapse_potential_I` text COLLATE utf8mb4_unicode_ci COMMENT 'Look at form UI.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_aftercare_plans_encounter_foreign` (`encounter`),
  KEY `form_aftercare_plans_pid_foreign` (`pid`),
  KEY `form_aftercare_plans_userid_foreign` (`userID`),
  KEY `form_aftercare_plans_provider_index` (`provider`),
  CONSTRAINT `form_aftercare_plans_encounter_foreign` FOREIGN KEY (`encounter`) REFERENCES `form_encounters` (`encounter`) ON DELETE CASCADE,
  CONSTRAINT `form_aftercare_plans_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `form_aftercare_plans_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_aftercare_plans`
--

LOCK TABLES `form_aftercare_plans` WRITE;
/*!40000 ALTER TABLE `form_aftercare_plans` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_aftercare_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_ankleinjuries`
--

DROP TABLE IF EXISTS `form_ankleinjuries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_ankleinjuries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `encounter` int(10) unsigned NOT NULL COMMENT 'Foreign key to form_encounters table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign Key to patient_datas table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `provider` int(10) unsigned NOT NULL COMMENT 'Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Means a clinician (physician, etc...) has verified this form as part of the client record',
  `activity` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'A delete flag. 0 -> Yes | 1 -> No',
  `date` datetime NOT NULL COMMENT 'Date when this form filled.',
  `ankle_date_of_injury` datetime NOT NULL COMMENT 'Date of Injury.',
  `ankle_work_related` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'O -> Off, 1 -> On',
  `ankle_foot` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Which foot is? Left or right?',
  `ankle_severity_of_pain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Pain Severity',
  `ankle_significant_swelling` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> no significant swelling | 1 -> There is significant swelling.',
  `ankle_onset_of_swelling` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'When swelling happend? Like within minutes or hours',
  `ankle_how_did_injury_occur` text COLLATE utf8mb4_unicode_ci COMMENT 'Reason for injury.',
  `ankle_ottawa_bone_tenderness` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Bone Tenderness',
  `ankle_able_to_bear_weight_steps` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether able to bear weight? 0 -> No | 1 -> Yes',
  `ankle_x_ray_interpretation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Interpretation of X-Ray',
  `ankle_additional_x_ray_notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Additional X-Ray Notes.',
  `ankle_diagnosis_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Further Diagnosis.',
  `ankle_diagnosis_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Further Diagnosis.',
  `ankle_diagnosis_3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Further Diagnosis.',
  `ankle_diagnosis_4` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Further Diagnosis.',
  `ankle_plan` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Prescription by doctor',
  `ankle_additional_diagnisis` text COLLATE utf8mb4_unicode_ci COMMENT 'Additional Diagnosis.',
  `cpt_codes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'CPT Code',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_ankleinjuries_encounter_foreign` (`encounter`),
  KEY `form_ankleinjuries_pid_foreign` (`pid`),
  KEY `form_ankleinjuries_userid_foreign` (`userID`),
  KEY `form_ankleinjuries_provider_index` (`provider`),
  CONSTRAINT `form_ankleinjuries_encounter_foreign` FOREIGN KEY (`encounter`) REFERENCES `form_encounters` (`encounter`) ON DELETE CASCADE,
  CONSTRAINT `form_ankleinjuries_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `form_ankleinjuries_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_ankleinjuries`
--

LOCK TABLES `form_ankleinjuries` WRITE;
/*!40000 ALTER TABLE `form_ankleinjuries` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_ankleinjuries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_annotate_diagrams`
--

DROP TABLE IF EXISTS `form_annotate_diagrams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_annotate_diagrams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Aotoincrement. Primary Key',
  `encounter` int(10) unsigned NOT NULL COMMENT 'Foreign key to form_encounters table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign Key to patient_datas table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `provider` int(10) unsigned NOT NULL COMMENT 'Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet',
  `date` datetime NOT NULL COMMENT 'Date when this form filled.',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Means a clinician (physician, etc...) has verified this form as part of the client record',
  `activity` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'A delete flag. 0 -> Yes | 1 -> No',
  `data` text COLLATE utf8mb4_unicode_ci COMMENT 'Annotated Data.',
  `imagedata` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Image Data Dorectoey',
  `dyntitle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Digram name',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_annotate_diagrams_encounter_foreign` (`encounter`),
  KEY `form_annotate_diagrams_pid_foreign` (`pid`),
  KEY `form_annotate_diagrams_userid_foreign` (`userID`),
  KEY `form_annotate_diagrams_provider_index` (`provider`),
  CONSTRAINT `form_annotate_diagrams_encounter_foreign` FOREIGN KEY (`encounter`) REFERENCES `form_encounters` (`encounter`) ON DELETE CASCADE,
  CONSTRAINT `form_annotate_diagrams_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `form_annotate_diagrams_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_annotate_diagrams`
--

LOCK TABLES `form_annotate_diagrams` WRITE;
/*!40000 ALTER TABLE `form_annotate_diagrams` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_annotate_diagrams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_bronchitis`
--

DROP TABLE IF EXISTS `form_bronchitis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_bronchitis` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Autoincrement. Primary Key.',
  `encounter` int(10) unsigned NOT NULL COMMENT 'Foreign key to form_encounters table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign Key to patient_datas table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `provider` int(10) unsigned NOT NULL COMMENT 'Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet',
  `date` datetime NOT NULL COMMENT 'Date when this form filled.',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Means a clinician (physician, etc...) has verified this form as part of the client record',
  `activity` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'A delete flag. 0 -> Yes | 1 -> No',
  `bronchitis_date_of_illness` date NOT NULL COMMENT 'Bronchitis date of illness.',
  `bronchitis_hpi` text COLLATE utf8mb4_unicode_ci COMMENT 'HPI',
  `bronchitis_ops_fever` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_ops_cough` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_ops_dizziness` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_ops_chest_pain` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_ops_dyspnea` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_ops_sweating` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_ops_wheezing` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_ops_malaise` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_ops_sputum` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_ops_appearance` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Appearance',
  `bronchitis_ops_all_reviewed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_review_of_pmh` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_review_of_allergies` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_review_of_sh` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes | Social History',
  `bronchitis_review_of_fh` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes | Family History',
  `bronchitis_tms_normal_right` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_tms_normal_left` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_nares_normal_right` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_nares_normal_left` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_tms_thickened_right` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_tms_thickened_left` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_tms_af_level_right` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_tms_af_level_left` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_nares_swelling_right` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_nares_swelling_left` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_nares_discharge_right` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_nares_discharge_left` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_tms_bulging_right` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_tms_bulging_left` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_tms_perforated_right` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_tms_perforated_left` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_tms_nares_not_examined` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_no_sinus_tenderness` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_oropharynx_normal` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_sinus_tenderness_frontal_right` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_sinus_tenderness_frontal_left` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_oropharynx_erythema` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_oropharynx_exudate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_oropharynx_abcess` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_oropharynx_ulcers` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_sinus_tenderness_maxillary_right` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_sinus_tenderness_maxillary_left` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_oropharynx_appearance` text COLLATE utf8mb4_unicode_ci COMMENT 'Oropharnyx Appearance',
  `bronchitis_sinus_tenderness_not_examined` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_oropharynx_not_examined` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_heart_pmi` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_heart_s3` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_heart_s4` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_heart_click` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_heart_rub` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_heart_murmur` text COLLATE utf8mb4_unicode_ci COMMENT 'Murmer',
  `bronchitis_heart_grade` text COLLATE utf8mb4_unicode_ci COMMENT 'Grade',
  `bronchitis_heart_location` text COLLATE utf8mb4_unicode_ci COMMENT 'Location',
  `bronchitis_heart_normal` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes | Normal Cardiac Exam',
  `bronchitis_heart_not_examined` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_lungs_bs_normal` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_lungs_bs_reduced` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_lungs_bs_increased` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_lungs_crackles_LLL` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_lungs_crackles_RLL` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_lungs_crackles_BLL` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes | Bilateral',
  `bronchitis_lungs_rubs_LLL` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_lungs_rubs_RLL` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_lungs_rubs_BLL` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_lungs_wheezes_LLL` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_lungs_wheezes_RLL` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_lungs_wheezes_BLL` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_lungs_wheezes_DLL` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `bronchitis_diagnostic_tests` text COLLATE utf8mb4_unicode_ci COMMENT '0 -> No | 1 -> Yes',
  `diagnosis1_bronchitis_form` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Diagnosis_1',
  `diagnosis2_bronchitis_form` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Diagnosis_2',
  `diagnosis3_bronchitis_form` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Diagnosis_3',
  `diagnosis4_bronchitis_form` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Diagnosis_4',
  `bronchitis_additional_diagnosis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Additional Diagnosis',
  `bronchitis_treatment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Treatment',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_bronchitis_encounter_foreign` (`encounter`),
  KEY `form_bronchitis_pid_foreign` (`pid`),
  KEY `form_bronchitis_userid_foreign` (`userID`),
  KEY `form_bronchitis_provider_index` (`provider`),
  CONSTRAINT `form_bronchitis_encounter_foreign` FOREIGN KEY (`encounter`) REFERENCES `form_encounters` (`encounter`) ON DELETE CASCADE,
  CONSTRAINT `form_bronchitis_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `form_bronchitis_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_bronchitis`
--

LOCK TABLES `form_bronchitis` WRITE;
/*!40000 ALTER TABLE `form_bronchitis` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_bronchitis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_clinical_instructions`
--

DROP TABLE IF EXISTS `form_clinical_instructions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_clinical_instructions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `encounter` int(10) unsigned NOT NULL COMMENT 'Foreign key to form_encounters table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign Key to patient_datas table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `provider` int(10) unsigned NOT NULL COMMENT 'Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet',
  `date` datetime NOT NULL COMMENT 'Date when this form filled.',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Means a clinician (physician, etc...) has verified this form as part of the client record',
  `activity` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'A delete flag. 0 -> Yes | 1 -> No',
  `instruction` text COLLATE utf8mb4_unicode_ci COMMENT 'Instruction Provided.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_clinical_instructions_encounter_foreign` (`encounter`),
  KEY `form_clinical_instructions_pid_foreign` (`pid`),
  KEY `form_clinical_instructions_userid_foreign` (`userID`),
  KEY `form_clinical_instructions_provider_index` (`provider`),
  CONSTRAINT `form_clinical_instructions_encounter_foreign` FOREIGN KEY (`encounter`) REFERENCES `form_encounters` (`encounter`) ON DELETE CASCADE,
  CONSTRAINT `form_clinical_instructions_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `form_clinical_instructions_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_clinical_instructions`
--

LOCK TABLES `form_clinical_instructions` WRITE;
/*!40000 ALTER TABLE `form_clinical_instructions` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_clinical_instructions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_dictations`
--

DROP TABLE IF EXISTS `form_dictations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_dictations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Autoincrement Primary key.',
  `encounter` int(10) unsigned NOT NULL COMMENT 'Foreign key to form_encounters table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign Key to patient_datas table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `provider` int(10) unsigned NOT NULL COMMENT 'Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet',
  `date` datetime NOT NULL COMMENT 'Date when this form filled.',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Means a clinician (physician, etc...) has verified this form as part of the client record',
  `activity` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'A delete flag. 0 -> Yes | 1 -> No',
  `dictation` text COLLATE utf8mb4_unicode_ci COMMENT 'Dictation',
  `additional_notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Additional Notes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_dictations_encounter_foreign` (`encounter`),
  KEY `form_dictations_pid_foreign` (`pid`),
  KEY `form_dictations_userid_foreign` (`userID`),
  KEY `form_dictations_provider_index` (`provider`),
  CONSTRAINT `form_dictations_encounter_foreign` FOREIGN KEY (`encounter`) REFERENCES `form_encounters` (`encounter`) ON DELETE CASCADE,
  CONSTRAINT `form_dictations_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `form_dictations_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_dictations`
--

LOCK TABLES `form_dictations` WRITE;
/*!40000 ALTER TABLE `form_dictations` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_dictations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_encounters`
--

DROP TABLE IF EXISTS `form_encounters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_encounters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement.',
  `facilityID` int(10) unsigned NOT NULL COMMENT 'Foreign key to facilities table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign key to Patients table.',
  `provider_id` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `date` datetime NOT NULL COMMENT 'Date of service.',
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Description of this encounter.',
  `facility` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Facility Name',
  `encounter` int(10) unsigned NOT NULL COMMENT 'Encounter id. More relevent to reference.',
  `onset_date` datetime NOT NULL COMMENT 'The date that the patient reports current issue is linked to.',
  `sensitivity` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'A flag that restrict access for VIPs or anyone who requests it.',
  `billing_note` text COLLATE utf8mb4_unicode_ci COMMENT 'An accounting note that applies to just this encounter (but not the patient level, or the line-item level.)',
  `pc_catid` int(10) unsigned NOT NULL COMMENT 'The encounter category which is actually from the calendar categories for appointment type.',
  `last_level_billed` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Flag that tells you if billed to Primary, Secondary, Tertiary Insurance etc. This should be an incremental flag as payments get processed.',
  `last_level_closed` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Refer as above.',
  `last_stmt_date` datetime DEFAULT NULL COMMENT 'Refer as above.',
  `stmt_count` int(10) unsigned NOT NULL COMMENT 'How many statements have been sent out?',
  `supervisor_id` int(10) unsigned NOT NULL COMMENT 'Supervising provider. If any for this visit.',
  `ordering_physician` int(10) unsigned NOT NULL COMMENT 'Ordering provider. If any for this visit.',
  `referring_physician` int(10) unsigned NOT NULL COMMENT 'Referring provider, if any, for this visit.',
  `contract_physician` int(10) unsigned NOT NULL COMMENT 'Contract provider, if any, for this visit.',
  `invoice_refno` varchar(31) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Billing stuff.',
  `referal_source` int(10) unsigned NOT NULL COMMENT 'Should be an ID from the users table. Can be from an address book entry.',
  `billing_facility` int(10) unsigned NOT NULL COMMENT 'Facilities table billing entity.',
  `external_id` int(10) unsigned DEFAULT NULL COMMENT 'External ID',
  `eft_number` int(10) unsigned DEFAULT NULL COMMENT 'eft number.',
  `claim_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Claim Number. Related to insurance.',
  `document_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Document of patient.',
  `seq_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Sequence Number.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `form_encounters_encounter_unique` (`encounter`),
  KEY `form_encounters_facilityid_foreign` (`facilityID`),
  KEY `form_encounters_pid_foreign` (`pid`),
  KEY `form_encounters_provider_id_foreign` (`provider_id`),
  KEY `form_encounters_pc_catid_index` (`pc_catid`),
  KEY `form_encounters_supervisor_id_index` (`supervisor_id`),
  KEY `form_encounters_ordering_physician_index` (`ordering_physician`),
  KEY `form_encounters_referring_physician_index` (`referring_physician`),
  KEY `form_encounters_contract_physician_index` (`contract_physician`),
  CONSTRAINT `form_encounters_facilityid_foreign` FOREIGN KEY (`facilityID`) REFERENCES `facilities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `form_encounters_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `form_encounters_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_encounters`
--

LOCK TABLES `form_encounters` WRITE;
/*!40000 ALTER TABLE `form_encounters` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_encounters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_misc_billing_options`
--

DROP TABLE IF EXISTS `form_misc_billing_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_misc_billing_options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `encounter` int(10) unsigned NOT NULL COMMENT 'Foreign key to form_encounters table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign Key to patient_datas table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `provider` int(10) unsigned NOT NULL COMMENT 'Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet',
  `date` datetime NOT NULL COMMENT 'Date when this form filled.',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Means a clinician (physician, etc...) has verified this form as part of the client record',
  `activity` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'A delete flag. 0 -> Yes | 1 -> No',
  `employment_related` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `auto_accident` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `accident_state` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Accident State Code',
  `other_accident` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `medicaid_referral_code` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Medical Referral Code. In UI it is EPSDT Referral Code.',
  `epsdt_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'EPSDT Flag',
  `provider_qualifier_code` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Provider Qualifier Code',
  `is_outside_lab` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Outside Lab Used? 0 -> No | 1 -> Yes',
  `lab_amount` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Lab Cost',
  `is_unable_to_work` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Unable to work. 0- > No | 1 -> Yes',
  `off_work_from` date DEFAULT NULL COMMENT 'Date when not able to work.',
  `off_work_to` date DEFAULT NULL COMMENT 'Date till not able to work. It is to be noticed that if this field( and off_work_from) is filled then only is_unable_to_work is true else not.',
  `is_hospitalised` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is patient hospitalised? 0 -> No | 1 -> Yes',
  `hospitalization_date_from` date DEFAULT NULL COMMENT 'Date when hospitalised.',
  `hospitalization_date_to` date DEFAULT NULL COMMENT 'Date till hospitalised. If this and hospitalization_date_from is filled then only is_hospitalised is true.',
  `medicaid_resubmission_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ICD9 Code',
  `medicaid_original_reference` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Reference Number',
  `prior_auth_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Authorization Number.',
  `comments` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Comment',
  `replacement_claim` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'X12 Replacement Claim',
  `icn_resubmission_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'X12 ICN Resubmission Number',
  `box_14_date_equal` date NOT NULL COMMENT 'onset_date from form_encounter.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_misc_billing_options_encounter_foreign` (`encounter`),
  KEY `form_misc_billing_options_pid_foreign` (`pid`),
  KEY `form_misc_billing_options_userid_foreign` (`userID`),
  KEY `form_misc_billing_options_provider_index` (`provider`),
  CONSTRAINT `form_misc_billing_options_encounter_foreign` FOREIGN KEY (`encounter`) REFERENCES `form_encounters` (`encounter`) ON DELETE CASCADE,
  CONSTRAINT `form_misc_billing_options_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `form_misc_billing_options_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_misc_billing_options`
--

LOCK TABLES `form_misc_billing_options` WRITE;
/*!40000 ALTER TABLE `form_misc_billing_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_misc_billing_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_notes`
--

DROP TABLE IF EXISTS `form_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_notes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `encounter` int(10) unsigned NOT NULL COMMENT 'Foreign key to form_encounters table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign Key to patient_datas table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `provider` int(10) unsigned NOT NULL COMMENT 'Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet',
  `date` datetime NOT NULL COMMENT 'Date when this form filled.',
  `note_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of Note. Viz Work Note or School Note.',
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Message in Note.',
  `doctor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Doctor giving this note.',
  `date_of_signature` date NOT NULL COMMENT 'When note created by doctor.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_notes_encounter_foreign` (`encounter`),
  KEY `form_notes_pid_foreign` (`pid`),
  KEY `form_notes_userid_foreign` (`userID`),
  KEY `form_notes_provider_index` (`provider`),
  CONSTRAINT `form_notes_encounter_foreign` FOREIGN KEY (`encounter`) REFERENCES `form_encounters` (`encounter`) ON DELETE CASCADE,
  CONSTRAINT `form_notes_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `form_notes_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_notes`
--

LOCK TABLES `form_notes` WRITE;
/*!40000 ALTER TABLE `form_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_painmaps`
--

DROP TABLE IF EXISTS `form_painmaps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_painmaps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key Autoincrement.',
  `encounter` int(10) unsigned NOT NULL COMMENT 'Foreign key to form_encounters table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign Key to patient_datas table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `provider` int(10) unsigned NOT NULL COMMENT 'Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet',
  `date` datetime NOT NULL COMMENT 'Date when this form filled.',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Means a clinician (physician, etc...) has verified this form as part of the client record',
  `activity` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'A delete flag. 0 -> Yes | 1 -> No',
  `data` text COLLATE utf8mb4_unicode_ci COMMENT 'Data of annotated form.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_painmaps_encounter_foreign` (`encounter`),
  KEY `form_painmaps_pid_foreign` (`pid`),
  KEY `form_painmaps_userid_foreign` (`userID`),
  KEY `form_painmaps_provider_index` (`provider`),
  CONSTRAINT `form_painmaps_encounter_foreign` FOREIGN KEY (`encounter`) REFERENCES `form_encounters` (`encounter`) ON DELETE CASCADE,
  CONSTRAINT `form_painmaps_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `form_painmaps_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_painmaps`
--

LOCK TABLES `form_painmaps` WRITE;
/*!40000 ALTER TABLE `form_painmaps` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_painmaps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_physical_exam_diagnoses`
--

DROP TABLE IF EXISTS `form_physical_exam_diagnoses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_physical_exam_diagnoses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `line_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Foreign Key. References form_physical_exams table',
  `ordering` int(10) unsigned NOT NULL COMMENT 'Diagnosis Order',
  `diagnosis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Diagnosis information',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_physical_exam_diagnoses_line_id_foreign` (`line_id`),
  CONSTRAINT `form_physical_exam_diagnoses_line_id_foreign` FOREIGN KEY (`line_id`) REFERENCES `form_physical_exams` (`line_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_physical_exam_diagnoses`
--

LOCK TABLES `form_physical_exam_diagnoses` WRITE;
/*!40000 ALTER TABLE `form_physical_exam_diagnoses` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_physical_exam_diagnoses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_physical_exams`
--

DROP TABLE IF EXISTS `form_physical_exams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_physical_exams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Autoincrement. Primary Key',
  `encounter` int(10) unsigned NOT NULL COMMENT 'Foreign key to form_encounters table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign Key to patient_datas table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `provider` int(10) unsigned NOT NULL COMMENT 'Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet',
  `date` datetime NOT NULL COMMENT 'Date when this form filled.',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Means a clinician (physician, etc...) has verified this form as part of the client record',
  `line_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'A kind of key to be used to refer this form.',
  `wnl` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `abn` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> No | 1 -> Yes',
  `diagnosis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Diagnosis related to this line',
  `comments` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Comment',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `form_physical_exams_line_id_unique` (`line_id`),
  KEY `form_physical_exams_encounter_foreign` (`encounter`),
  KEY `form_physical_exams_pid_foreign` (`pid`),
  KEY `form_physical_exams_userid_foreign` (`userID`),
  KEY `form_physical_exams_provider_index` (`provider`),
  CONSTRAINT `form_physical_exams_encounter_foreign` FOREIGN KEY (`encounter`) REFERENCES `form_encounters` (`encounter`) ON DELETE CASCADE,
  CONSTRAINT `form_physical_exams_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `form_physical_exams_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_physical_exams`
--

LOCK TABLES `form_physical_exams` WRITE;
/*!40000 ALTER TABLE `form_physical_exams` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_physical_exams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_prior_auths`
--

DROP TABLE IF EXISTS `form_prior_auths`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_prior_auths` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Autoincrement Primary Key.',
  `encounter` int(10) unsigned NOT NULL COMMENT 'Foreign key to form_encounters table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign Key to patient_datas table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `provider` int(10) unsigned NOT NULL COMMENT 'Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet',
  `date` datetime NOT NULL COMMENT 'Date when this form filled.',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Means a clinician (physician, etc...) has verified this form as part of the client record',
  `activity` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'A delete flag. 0 -> Yes | 1 -> No',
  `prior_auth_number` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Auth Number',
  `comments` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Comment.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_prior_auths_encounter_foreign` (`encounter`),
  KEY `form_prior_auths_pid_foreign` (`pid`),
  KEY `form_prior_auths_userid_foreign` (`userID`),
  KEY `form_prior_auths_provider_index` (`provider`),
  CONSTRAINT `form_prior_auths_encounter_foreign` FOREIGN KEY (`encounter`) REFERENCES `form_encounters` (`encounter`) ON DELETE CASCADE,
  CONSTRAINT `form_prior_auths_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `form_prior_auths_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_prior_auths`
--

LOCK TABLES `form_prior_auths` WRITE;
/*!40000 ALTER TABLE `form_prior_auths` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_prior_auths` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_reviewofs`
--

DROP TABLE IF EXISTS `form_reviewofs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_reviewofs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Autoincrement. Primary Key',
  `encounter` int(10) unsigned NOT NULL COMMENT 'Foreign key to form_encounters table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign Key to patient_datas table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `provider` int(10) unsigned NOT NULL COMMENT 'Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet',
  `date` datetime NOT NULL COMMENT 'Date when this form filled.',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Means a clinician (physician, etc...) has verified this form as part of the client record',
  `activity` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'A delete flag. 0 -> Yes | 1 -> No',
  `fever` tinyint(1) NOT NULL DEFAULT '0',
  `chills` tinyint(1) NOT NULL DEFAULT '0',
  `night_sweats` tinyint(1) NOT NULL DEFAULT '0',
  `weight_loss` tinyint(1) NOT NULL DEFAULT '0',
  `poor_appetite` tinyint(1) NOT NULL DEFAULT '0',
  `insomnia` tinyint(1) NOT NULL DEFAULT '0',
  `fatigued` tinyint(1) NOT NULL DEFAULT '0',
  `depressed` tinyint(1) NOT NULL DEFAULT '0',
  `hyperactive` tinyint(1) NOT NULL DEFAULT '0',
  `exposure_to_foreign_countries` tinyint(1) NOT NULL DEFAULT '0',
  `cataracts` tinyint(1) NOT NULL DEFAULT '0',
  `cataract_surgery` tinyint(1) NOT NULL,
  `glaucoma` tinyint(1) NOT NULL DEFAULT '0',
  `double_vision` tinyint(1) NOT NULL DEFAULT '0',
  `blurred_vision` tinyint(1) NOT NULL DEFAULT '0',
  `poor_hearing` tinyint(1) NOT NULL DEFAULT '0',
  `headaches` tinyint(1) NOT NULL DEFAULT '0',
  `ringing_in_ears` tinyint(1) NOT NULL DEFAULT '0',
  `bloody_nose` tinyint(1) NOT NULL DEFAULT '0',
  `sinusitis` tinyint(1) NOT NULL DEFAULT '0',
  `sinus_surgery` tinyint(1) NOT NULL DEFAULT '0',
  `dry_mouth` tinyint(1) NOT NULL DEFAULT '0',
  `strep_throat` tinyint(1) NOT NULL DEFAULT '0',
  `tonsillectomy` tinyint(1) NOT NULL DEFAULT '0',
  `swollen_lymph_nodes` tinyint(1) NOT NULL DEFAULT '0',
  `throat_cancer` tinyint(1) NOT NULL DEFAULT '0',
  `throat_cancer_surgery` tinyint(1) NOT NULL DEFAULT '0',
  `heart_attack` tinyint(1) NOT NULL DEFAULT '0',
  `irregular_heart_beat` tinyint(1) NOT NULL DEFAULT '0',
  `chest_pains` tinyint(1) NOT NULL DEFAULT '0',
  `shortness_of_breath` tinyint(1) NOT NULL DEFAULT '0',
  `high_blood_pressure` tinyint(1) NOT NULL DEFAULT '0',
  `heart_failure` tinyint(1) NOT NULL DEFAULT '0',
  `poor_circulation` tinyint(1) NOT NULL DEFAULT '0',
  `vascular_surgery` tinyint(1) NOT NULL DEFAULT '0',
  `cardiac_catheterization` tinyint(1) NOT NULL DEFAULT '0',
  `coronary_artery_bypass` tinyint(1) NOT NULL DEFAULT '0',
  `heart_transplant` tinyint(1) NOT NULL DEFAULT '0',
  `stress_test` tinyint(1) NOT NULL DEFAULT '0',
  `emphysema` tinyint(1) NOT NULL DEFAULT '0',
  `chronic_bronchitis` tinyint(1) NOT NULL DEFAULT '0',
  `interstitial_lung_disease` tinyint(1) NOT NULL DEFAULT '0',
  `shortness_of_breath_2` tinyint(1) NOT NULL DEFAULT '0',
  `lung_cancer` tinyint(1) NOT NULL DEFAULT '0',
  `lung_cancer_surgery` tinyint(1) NOT NULL DEFAULT '0',
  `pheumothorax` tinyint(1) NOT NULL DEFAULT '0',
  `stomach_pains` tinyint(1) NOT NULL DEFAULT '0',
  `peptic_ulcer_disease` tinyint(1) NOT NULL DEFAULT '0',
  `gastritis` tinyint(1) NOT NULL DEFAULT '0',
  `endoscopy` tinyint(1) NOT NULL DEFAULT '0',
  `polyps` tinyint(1) NOT NULL DEFAULT '0',
  `colonoscopy` tinyint(1) NOT NULL DEFAULT '0',
  `colon_cancer` tinyint(1) NOT NULL DEFAULT '0',
  `colon_cancer_surgery` tinyint(1) NOT NULL DEFAULT '0',
  `ulcerative_colitis` tinyint(1) NOT NULL DEFAULT '0',
  `crohns_disease` tinyint(1) NOT NULL DEFAULT '0',
  `appendectomy` tinyint(1) NOT NULL DEFAULT '0',
  `divirticulitis` tinyint(1) NOT NULL DEFAULT '0',
  `divirticulitis_surgery` tinyint(1) NOT NULL DEFAULT '0',
  `gall_stones` tinyint(1) NOT NULL DEFAULT '0',
  `cholecystectomy` tinyint(1) NOT NULL DEFAULT '0',
  `hepatitis` tinyint(1) NOT NULL DEFAULT '0',
  `cirrhosis_of_the_liver` tinyint(1) NOT NULL DEFAULT '0',
  `splenectomy` tinyint(1) NOT NULL DEFAULT '0',
  `kidney_failure` tinyint(1) NOT NULL DEFAULT '0',
  `kidney_stones` tinyint(1) NOT NULL DEFAULT '0',
  `kidney_cancer` tinyint(1) NOT NULL DEFAULT '0',
  `kidney_infections` tinyint(1) NOT NULL DEFAULT '0',
  `bladder_infections` tinyint(1) NOT NULL DEFAULT '0',
  `bladder_cancer` tinyint(1) NOT NULL DEFAULT '0',
  `prostate_problems` tinyint(1) NOT NULL DEFAULT '0',
  `prostate_cancer` tinyint(1) NOT NULL DEFAULT '0',
  `kidney_transplant` tinyint(1) NOT NULL DEFAULT '0',
  `sexually_transmitted_disease` tinyint(1) NOT NULL DEFAULT '0',
  `burning_with_urination` tinyint(1) NOT NULL DEFAULT '0',
  `discharge_from_urethra` tinyint(1) NOT NULL DEFAULT '0',
  `rashes` tinyint(1) NOT NULL DEFAULT '0',
  `infections` tinyint(1) NOT NULL DEFAULT '0',
  `ulcerations` tinyint(1) NOT NULL DEFAULT '0',
  `pemphigus` tinyint(1) NOT NULL DEFAULT '0',
  `herpes` tinyint(1) NOT NULL DEFAULT '0',
  `osetoarthritis` tinyint(1) NOT NULL DEFAULT '0',
  `rheumotoid_arthritis` tinyint(1) NOT NULL DEFAULT '0',
  `lupus` tinyint(1) NOT NULL DEFAULT '0',
  `ankylosing_sondlilitis` tinyint(1) NOT NULL DEFAULT '0',
  `swollen_joints` tinyint(1) NOT NULL DEFAULT '0',
  `stiff_joints` tinyint(1) NOT NULL DEFAULT '0',
  `broken_bones` tinyint(1) NOT NULL DEFAULT '0',
  `neck_problems` tinyint(1) NOT NULL DEFAULT '0',
  `back_problems` tinyint(1) NOT NULL DEFAULT '0',
  `back_surgery` tinyint(1) NOT NULL DEFAULT '0',
  `scoliosis` tinyint(1) NOT NULL DEFAULT '0',
  `herniated_disc` tinyint(1) NOT NULL DEFAULT '0',
  `shoulder_problems` tinyint(1) NOT NULL DEFAULT '0',
  `elbow_problems` tinyint(1) NOT NULL DEFAULT '0',
  `wrist_problems` tinyint(1) NOT NULL DEFAULT '0',
  `hand_problems` tinyint(1) NOT NULL DEFAULT '0',
  `hip_problems` tinyint(1) NOT NULL DEFAULT '0',
  `knee_problems` tinyint(1) NOT NULL DEFAULT '0',
  `ankle_problems` tinyint(1) NOT NULL DEFAULT '0',
  `foot_problems` tinyint(1) NOT NULL DEFAULT '0',
  `insulin_dependent_diabetes` tinyint(1) NOT NULL DEFAULT '0',
  `noninsulin_dependent_diabetes` tinyint(1) NOT NULL DEFAULT '0',
  `hypothyroidism` tinyint(1) NOT NULL DEFAULT '0',
  `hyperthyroidism` tinyint(1) NOT NULL DEFAULT '0',
  `cushing_syndrom` tinyint(1) NOT NULL DEFAULT '0',
  `addison_syndrom` tinyint(1) NOT NULL DEFAULT '0',
  `additional_notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Notes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_reviewofs_encounter_foreign` (`encounter`),
  KEY `form_reviewofs_pid_foreign` (`pid`),
  KEY `form_reviewofs_userid_foreign` (`userID`),
  KEY `form_reviewofs_provider_index` (`provider`),
  CONSTRAINT `form_reviewofs_encounter_foreign` FOREIGN KEY (`encounter`) REFERENCES `form_encounters` (`encounter`) ON DELETE CASCADE,
  CONSTRAINT `form_reviewofs_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `form_reviewofs_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_reviewofs`
--

LOCK TABLES `form_reviewofs` WRITE;
/*!40000 ALTER TABLE `form_reviewofs` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_reviewofs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_ros`
--

DROP TABLE IF EXISTS `form_ros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_ros` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Autoincrement Primary Key',
  `encounter` int(10) unsigned NOT NULL COMMENT 'Foreign key to form_encounters table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign Key to patient_datas table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `provider` int(10) unsigned NOT NULL COMMENT 'Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet',
  `date` datetime NOT NULL COMMENT 'Date when this form filled.',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Means a clinician (physician, etc...) has verified this form as part of the client record',
  `activity` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'A delete flag. 0 -> Yes | 1 -> No',
  `weight_change` int(10) unsigned NOT NULL DEFAULT '0',
  `weakness` int(10) unsigned NOT NULL DEFAULT '0',
  `fatigue` int(10) unsigned NOT NULL DEFAULT '0',
  `anorexia` int(10) unsigned NOT NULL DEFAULT '0',
  `fever` int(10) unsigned NOT NULL DEFAULT '0',
  `chills` int(10) unsigned NOT NULL DEFAULT '0',
  `night_sweats` int(10) unsigned NOT NULL DEFAULT '0',
  `insomnia` int(10) unsigned NOT NULL DEFAULT '0',
  `irritability` int(10) unsigned NOT NULL DEFAULT '0',
  `heat_or_cold` int(10) unsigned NOT NULL DEFAULT '0',
  `intolerance` int(10) unsigned NOT NULL DEFAULT '0',
  `change_in_vision` int(10) unsigned NOT NULL DEFAULT '0',
  `glaucoma_history` int(10) unsigned NOT NULL DEFAULT '0',
  `eye_pain` int(10) unsigned NOT NULL DEFAULT '0',
  `irritation` int(10) unsigned NOT NULL DEFAULT '0',
  `redness` int(10) unsigned NOT NULL DEFAULT '0',
  `excessive_tearing` int(10) unsigned NOT NULL DEFAULT '0',
  `double_vision` int(10) unsigned NOT NULL DEFAULT '0',
  `blind_spots` int(10) unsigned NOT NULL DEFAULT '0',
  `photophobia` int(10) unsigned NOT NULL DEFAULT '0',
  `hearing_loss` int(10) unsigned NOT NULL DEFAULT '0',
  `discharge` int(10) unsigned NOT NULL DEFAULT '0',
  `pain` int(10) unsigned NOT NULL DEFAULT '0',
  `vertigo` int(10) unsigned NOT NULL DEFAULT '0',
  `tinnitus` int(10) unsigned NOT NULL DEFAULT '0',
  `frequent_colds` int(10) unsigned NOT NULL DEFAULT '0',
  `sore_throat` int(10) unsigned NOT NULL DEFAULT '0',
  `sinus_problems` int(10) unsigned NOT NULL DEFAULT '0',
  `post_nasal_drip` int(10) unsigned NOT NULL DEFAULT '0',
  `nosebleed` int(10) unsigned NOT NULL DEFAULT '0',
  `snoring` int(10) unsigned NOT NULL DEFAULT '0',
  `apnea` int(10) unsigned NOT NULL DEFAULT '0',
  `breast_mass` int(10) unsigned NOT NULL DEFAULT '0',
  `breast_discharge` int(10) unsigned NOT NULL DEFAULT '0',
  `biopsy` int(10) unsigned NOT NULL DEFAULT '0',
  `abnormal_mammogram` int(10) unsigned NOT NULL DEFAULT '0',
  `cough` int(10) unsigned NOT NULL DEFAULT '0',
  `sputum` int(10) unsigned NOT NULL DEFAULT '0',
  `shortness_of_breath` int(10) unsigned NOT NULL DEFAULT '0',
  `wheezing` int(10) unsigned NOT NULL DEFAULT '0',
  `hemoptsyis` int(10) unsigned NOT NULL DEFAULT '0',
  `asthma` int(10) unsigned NOT NULL DEFAULT '0',
  `copd` int(10) unsigned NOT NULL DEFAULT '0',
  `chest_pain` int(10) unsigned NOT NULL DEFAULT '0',
  `palpitation` int(10) unsigned NOT NULL DEFAULT '0',
  `syncope` int(10) unsigned NOT NULL DEFAULT '0',
  `pnd` int(10) unsigned NOT NULL DEFAULT '0',
  `doe` int(10) unsigned NOT NULL DEFAULT '0',
  `orthopnea` int(10) unsigned NOT NULL DEFAULT '0',
  `peripheal` int(10) unsigned NOT NULL DEFAULT '0',
  `edema` int(10) unsigned NOT NULL DEFAULT '0',
  `legpain_cramping` int(10) unsigned NOT NULL DEFAULT '0',
  `history_murmur` int(10) unsigned NOT NULL DEFAULT '0',
  `arrythmia` int(10) unsigned NOT NULL DEFAULT '0',
  `heart_problem` int(10) unsigned NOT NULL DEFAULT '0',
  `dysphagia` int(10) unsigned NOT NULL DEFAULT '0',
  `heartburn` int(10) unsigned NOT NULL DEFAULT '0',
  `bloating` int(10) unsigned NOT NULL DEFAULT '0',
  `belching` int(10) unsigned NOT NULL DEFAULT '0',
  `flatulence` int(10) unsigned NOT NULL DEFAULT '0',
  `nausea` int(10) unsigned NOT NULL DEFAULT '0',
  `vomiting` int(10) unsigned NOT NULL DEFAULT '0',
  `hematemesis` int(10) unsigned NOT NULL DEFAULT '0',
  `gastro_pain` int(10) unsigned NOT NULL DEFAULT '0',
  `food_intolerance` int(10) unsigned NOT NULL DEFAULT '0',
  `hepatitis` int(10) unsigned NOT NULL DEFAULT '0',
  `jaundice` int(10) unsigned NOT NULL DEFAULT '0',
  `hematochezia` int(10) unsigned NOT NULL DEFAULT '0',
  `changed_bowel` int(10) unsigned NOT NULL DEFAULT '0',
  `diarrhea` int(10) unsigned NOT NULL DEFAULT '0',
  `constipation` int(10) unsigned NOT NULL DEFAULT '0',
  `polyuria` int(10) unsigned NOT NULL DEFAULT '0',
  `polydypsia` int(10) unsigned NOT NULL DEFAULT '0',
  `dysuria` int(10) unsigned NOT NULL DEFAULT '0',
  `hematuria` int(10) unsigned NOT NULL DEFAULT '0',
  `frequency` int(10) unsigned NOT NULL DEFAULT '0',
  `urgency` int(10) unsigned NOT NULL DEFAULT '0',
  `incontinence` int(10) unsigned NOT NULL DEFAULT '0',
  `renal_stones` int(10) unsigned NOT NULL DEFAULT '0',
  `utis` int(10) unsigned NOT NULL DEFAULT '0',
  `hesitancy` int(10) unsigned NOT NULL DEFAULT '0',
  `dribbling` int(10) unsigned NOT NULL DEFAULT '0',
  `stream` int(10) unsigned NOT NULL DEFAULT '0',
  `nocturia` int(10) unsigned NOT NULL DEFAULT '0',
  `erections` int(10) unsigned NOT NULL DEFAULT '0',
  `ejaculations` int(10) unsigned NOT NULL DEFAULT '0',
  `g` int(10) unsigned NOT NULL DEFAULT '0',
  `p` int(10) unsigned NOT NULL DEFAULT '0',
  `ap` int(10) unsigned NOT NULL DEFAULT '0',
  `lc` int(10) unsigned NOT NULL DEFAULT '0',
  `mearche` int(10) unsigned NOT NULL DEFAULT '0',
  `menopause` int(10) unsigned NOT NULL DEFAULT '0',
  `lmp` int(10) unsigned NOT NULL DEFAULT '0',
  `f_frequency` int(10) unsigned NOT NULL DEFAULT '0',
  `f_flow` int(10) unsigned NOT NULL DEFAULT '0',
  `f_symptoms` int(10) unsigned NOT NULL DEFAULT '0',
  `abnormal_hair_growth` int(10) unsigned NOT NULL DEFAULT '0',
  `f_hirsutism` int(10) unsigned NOT NULL DEFAULT '0',
  `joint_pain` int(10) unsigned NOT NULL DEFAULT '0',
  `swelling` int(10) unsigned NOT NULL DEFAULT '0',
  `m_redness` int(10) unsigned NOT NULL DEFAULT '0',
  `m_warm` int(10) unsigned NOT NULL DEFAULT '0',
  `m_stiffness` int(10) unsigned NOT NULL DEFAULT '0',
  `muscle` int(10) unsigned NOT NULL DEFAULT '0',
  `m_aches` int(10) unsigned NOT NULL DEFAULT '0',
  `fms` int(10) unsigned NOT NULL DEFAULT '0',
  `arthritis` int(10) unsigned NOT NULL DEFAULT '0',
  `loc` int(10) unsigned NOT NULL DEFAULT '0',
  `seizures` int(10) unsigned NOT NULL DEFAULT '0',
  `stroke` int(10) unsigned NOT NULL DEFAULT '0',
  `tia` int(10) unsigned NOT NULL DEFAULT '0',
  `n_numbness` int(10) unsigned NOT NULL DEFAULT '0',
  `n_weakness` int(10) unsigned NOT NULL DEFAULT '0',
  `paralysis` int(10) unsigned NOT NULL DEFAULT '0',
  `intellectual_decline` int(10) unsigned NOT NULL DEFAULT '0',
  `memory_problems` int(10) unsigned NOT NULL DEFAULT '0',
  `dementia` int(10) unsigned NOT NULL DEFAULT '0',
  `n_headache` int(10) unsigned NOT NULL DEFAULT '0',
  `s_cancer` int(10) unsigned NOT NULL DEFAULT '0',
  `psoriasis` int(10) unsigned NOT NULL DEFAULT '0',
  `s_acne` int(10) unsigned NOT NULL DEFAULT '0',
  `s_other` int(10) unsigned NOT NULL DEFAULT '0',
  `s_disease` int(10) unsigned NOT NULL DEFAULT '0',
  `p_diagnosis` int(10) unsigned NOT NULL DEFAULT '0',
  `p_medication` int(10) unsigned NOT NULL DEFAULT '0',
  `depression` int(10) unsigned NOT NULL DEFAULT '0',
  `anxiety` int(10) unsigned NOT NULL DEFAULT '0',
  `social_difficulties` int(10) unsigned NOT NULL DEFAULT '0',
  `thyroid_problems` int(10) unsigned NOT NULL DEFAULT '0',
  `diabetes` int(10) unsigned NOT NULL DEFAULT '0',
  `abnormal_blood` int(10) unsigned NOT NULL DEFAULT '0',
  `anemia` int(10) unsigned NOT NULL DEFAULT '0',
  `fh_blood_problems` int(10) unsigned NOT NULL DEFAULT '0',
  `bleeding_problems` int(10) unsigned NOT NULL DEFAULT '0',
  `allergies` int(10) unsigned NOT NULL DEFAULT '0',
  `frequent_illness` int(10) unsigned NOT NULL DEFAULT '0',
  `hiv` int(10) unsigned NOT NULL DEFAULT '0',
  `hai_status` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_ros_encounter_foreign` (`encounter`),
  KEY `form_ros_pid_foreign` (`pid`),
  KEY `form_ros_userid_foreign` (`userID`),
  KEY `form_ros_provider_index` (`provider`),
  CONSTRAINT `form_ros_encounter_foreign` FOREIGN KEY (`encounter`) REFERENCES `form_encounters` (`encounter`) ON DELETE CASCADE,
  CONSTRAINT `form_ros_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `form_ros_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_ros`
--

LOCK TABLES `form_ros` WRITE;
/*!40000 ALTER TABLE `form_ros` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_ros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_soaps`
--

DROP TABLE IF EXISTS `form_soaps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_soaps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `encounter` int(10) unsigned NOT NULL COMMENT 'Foreign key to form_encounters table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign Key to patient_datas table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `provider` int(10) unsigned NOT NULL COMMENT 'Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet',
  `date` datetime NOT NULL COMMENT 'Date when this form filled.',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Means a clinician (physician, etc...) has verified this form as part of the client record',
  `activity` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'A delete flag. 0 -> Yes | 1 -> No',
  `subjective` text COLLATE utf8mb4_unicode_ci COMMENT 'Subjective Part',
  `objective` text COLLATE utf8mb4_unicode_ci COMMENT 'Objective Part',
  `assessment` text COLLATE utf8mb4_unicode_ci COMMENT 'Assessment Part',
  `plan` text COLLATE utf8mb4_unicode_ci COMMENT 'Nullable Part',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_soaps_encounter_foreign` (`encounter`),
  KEY `form_soaps_pid_foreign` (`pid`),
  KEY `form_soaps_userid_foreign` (`userID`),
  KEY `form_soaps_provider_index` (`provider`),
  CONSTRAINT `form_soaps_encounter_foreign` FOREIGN KEY (`encounter`) REFERENCES `form_encounters` (`encounter`) ON DELETE CASCADE,
  CONSTRAINT `form_soaps_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `form_soaps_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_soaps`
--

LOCK TABLES `form_soaps` WRITE;
/*!40000 ALTER TABLE `form_soaps` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_soaps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_track_anything_results`
--

DROP TABLE IF EXISTS `form_track_anything_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_track_anything_results` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `track_anything_id` int(10) unsigned NOT NULL COMMENT 'Foreign key to form_track_anythings table.',
  `itemid` int(10) unsigned NOT NULL COMMENT 'Foreign key to form_track_anything_types table',
  `track_timestamp` datetime NOT NULL COMMENT 'Timestamp of track created.',
  `result` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Result',
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Comment on result.',
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Notes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_track_anything_results_track_anything_id_foreign` (`track_anything_id`),
  KEY `form_track_anything_results_itemid_foreign` (`itemid`),
  CONSTRAINT `form_track_anything_results_itemid_foreign` FOREIGN KEY (`itemid`) REFERENCES `form_track_anything_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `form_track_anything_results_track_anything_id_foreign` FOREIGN KEY (`track_anything_id`) REFERENCES `form_track_anythings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_track_anything_results`
--

LOCK TABLES `form_track_anything_results` WRITE;
/*!40000 ALTER TABLE `form_track_anything_results` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_track_anything_results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_track_anything_types`
--

DROP TABLE IF EXISTS `form_track_anything_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_track_anything_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Name of Type.',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Description of Type.',
  `parent` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Parent of any type. This happens in the case when you edit the type and enters a position of another type. Initially it will contain 0 as default value, indicating no parent.',
  `position` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'At which position it should appear in the list.',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether this type is enabled or not. Disable from Configure Track. 0 -> False | 1 -> True.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_track_anything_types`
--

LOCK TABLES `form_track_anything_types` WRITE;
/*!40000 ALTER TABLE `form_track_anything_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_track_anything_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_track_anythings`
--

DROP TABLE IF EXISTS `form_track_anythings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_track_anythings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement.',
  `encounter` int(10) unsigned NOT NULL COMMENT 'Foreign key to form_encounters table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign Key to patient_datas table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `provider` int(10) unsigned NOT NULL COMMENT 'Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet',
  `procedure_type_id` int(10) unsigned NOT NULL COMMENT 'Foreign key to form_track_anything_types.',
  `date` datetime NOT NULL COMMENT 'Date when this form filled.',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Means a clinician (physician, etc...) has verified this form as part of the client record',
  `activity` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'A delete flag. 0 -> Yes | 1 -> No',
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Comment',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_track_anythings_encounter_foreign` (`encounter`),
  KEY `form_track_anythings_pid_foreign` (`pid`),
  KEY `form_track_anythings_userid_foreign` (`userID`),
  KEY `form_track_anythings_procedure_type_id_foreign` (`procedure_type_id`),
  KEY `form_track_anythings_provider_index` (`provider`),
  CONSTRAINT `form_track_anythings_encounter_foreign` FOREIGN KEY (`encounter`) REFERENCES `form_encounters` (`encounter`) ON DELETE CASCADE,
  CONSTRAINT `form_track_anythings_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `form_track_anythings_procedure_type_id_foreign` FOREIGN KEY (`procedure_type_id`) REFERENCES `form_track_anything_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `form_track_anythings_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_track_anythings`
--

LOCK TABLES `form_track_anythings` WRITE;
/*!40000 ALTER TABLE `form_track_anythings` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_track_anythings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_transfer_summaries`
--

DROP TABLE IF EXISTS `form_transfer_summaries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_transfer_summaries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Auto Increment. Primary Key',
  `encounter` int(10) unsigned NOT NULL COMMENT 'Foreign key to form_encounters table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign Key to patient_datas table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `provider` int(10) unsigned NOT NULL COMMENT 'Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet',
  `date` datetime NOT NULL COMMENT 'Date when this form filled.',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Means a clinician (physician, etc...) has verified this form as part of the client record',
  `activity` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'A delete flag. 0 -> Yes | 1 -> No',
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'This is basically patient name.',
  `transfer_to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'To whom it is transferred?',
  `transfer_date` date NOT NULL COMMENT 'When transfer is done?',
  `status_of_admission` text COLLATE utf8mb4_unicode_ci COMMENT 'State of Admission',
  `diagnosis` text COLLATE utf8mb4_unicode_ci COMMENT 'Diagnosis',
  `intervention_provided` text COLLATE utf8mb4_unicode_ci COMMENT 'Intervention',
  `overall_status_of_discharge` text COLLATE utf8mb4_unicode_ci COMMENT 'Staus of Discharge',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_transfer_summaries_encounter_foreign` (`encounter`),
  KEY `form_transfer_summaries_pid_foreign` (`pid`),
  KEY `form_transfer_summaries_userid_foreign` (`userID`),
  KEY `form_transfer_summaries_provider_index` (`provider`),
  CONSTRAINT `form_transfer_summaries_encounter_foreign` FOREIGN KEY (`encounter`) REFERENCES `form_encounters` (`encounter`) ON DELETE CASCADE,
  CONSTRAINT `form_transfer_summaries_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `form_transfer_summaries_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_transfer_summaries`
--

LOCK TABLES `form_transfer_summaries` WRITE;
/*!40000 ALTER TABLE `form_transfer_summaries` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_transfer_summaries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_treatment_plans`
--

DROP TABLE IF EXISTS `form_treatment_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_treatment_plans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement.',
  `encounter` int(10) unsigned NOT NULL COMMENT 'Foreign key to form_encounters table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign Key to patient_datas table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `provider` int(10) unsigned NOT NULL COMMENT 'Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet',
  `date` datetime NOT NULL COMMENT 'Date when this form filled.',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Means a clinician (physician, etc...) has verified this form as part of the client record',
  `activity` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'A delete flag. 0 -> Yes | 1 -> No',
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Patient Name',
  `admit_date` date NOT NULL COMMENT 'When Patient was Admitted.',
  `presenting_issues` text COLLATE utf8mb4_unicode_ci COMMENT 'Issues',
  `patient_history` text COLLATE utf8mb4_unicode_ci COMMENT 'Patient History',
  `medications` text COLLATE utf8mb4_unicode_ci COMMENT 'Medication',
  `anyother_relevent_information` text COLLATE utf8mb4_unicode_ci COMMENT 'Extra Information',
  `diagnosis` text COLLATE utf8mb4_unicode_ci COMMENT 'Diagnosis Report.',
  `treatment_received` text COLLATE utf8mb4_unicode_ci COMMENT 'Treatment given to Patient.',
  `recomendation_for_follow_up` text COLLATE utf8mb4_unicode_ci COMMENT 'Follow up recomendations.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_treatment_plans_encounter_foreign` (`encounter`),
  KEY `form_treatment_plans_pid_foreign` (`pid`),
  KEY `form_treatment_plans_userid_foreign` (`userID`),
  KEY `form_treatment_plans_provider_index` (`provider`),
  CONSTRAINT `form_treatment_plans_encounter_foreign` FOREIGN KEY (`encounter`) REFERENCES `form_encounters` (`encounter`) ON DELETE CASCADE,
  CONSTRAINT `form_treatment_plans_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `form_treatment_plans_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_treatment_plans`
--

LOCK TABLES `form_treatment_plans` WRITE;
/*!40000 ALTER TABLE `form_treatment_plans` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_treatment_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_vitals`
--

DROP TABLE IF EXISTS `form_vitals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_vitals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Autoincrement. Primary Key',
  `encounter` int(10) unsigned NOT NULL COMMENT 'Foreign key to form_encounters table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign Key to patient_datas table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `provider` int(10) unsigned NOT NULL COMMENT 'Initially provider is set to be user, but when an encounter has a fee sheet filled out (billing table items are associated with that encounter number) then the feesheet sets the Provider fields to equal the Rendering Provider choice in the fee sheet',
  `date` datetime NOT NULL COMMENT 'Date when this form filled.',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Means a clinician (physician, etc...) has verified this form as part of the client record',
  `activity` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'A delete flag. 0 -> Yes | 1 -> No',
  `bps` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'BP Systolic',
  `bpd` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'BP Diastolic',
  `weight` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Weight of Patient.',
  `height` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Height of Person.',
  `temperature` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Temperature',
  `temp_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Temp Location',
  `pulse` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Pulse Rate',
  `respiration` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Respiration',
  `BMI` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Body Mass Index',
  `waist_circ` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Waist Circumference',
  `head_circ` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Head Circumference',
  `oxygen_saturation` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT 'Percentage Oxygen Saturation',
  `external_id` int(10) unsigned DEFAULT NULL COMMENT 'To hold an ID number from some other system, such as another EHR, an assigned ID that exists on a proprietary medical device or the like.',
  `note` text COLLATE utf8mb4_unicode_ci COMMENT 'Note',
  `BMI_status` text COLLATE utf8mb4_unicode_ci COMMENT 'BMI status.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_vitals_encounter_foreign` (`encounter`),
  KEY `form_vitals_pid_foreign` (`pid`),
  KEY `form_vitals_userid_foreign` (`userID`),
  KEY `form_vitals_provider_index` (`provider`),
  CONSTRAINT `form_vitals_encounter_foreign` FOREIGN KEY (`encounter`) REFERENCES `form_encounters` (`encounter`) ON DELETE CASCADE,
  CONSTRAINT `form_vitals_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `form_vitals_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_vitals`
--

LOCK TABLES `form_vitals` WRITE;
/*!40000 ALTER TABLE `form_vitals` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_vitals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forms`
--

DROP TABLE IF EXISTS `forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `date` datetime NOT NULL COMMENT 'Date when a new form is registered with that particular user encounter.',
  `encounter` int(10) unsigned NOT NULL COMMENT 'Foreign key to encounter table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign key to patients table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table. User who is registering form.',
  `form_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Name of form, like bronchitis, ankel injury, etc.',
  `form_id` int(10) unsigned NOT NULL COMMENT 'Id of form which is related to that encounter. Basically this is like, an encounter for a particular patient can have many forms and a single form can be related to other patient as well.',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is form authorized by physician or doctor? 0 -> No, 1 -> yes',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is form deleted from patient encounter? 0 -> No, 1 -> yes',
  `formdir` text COLLATE utf8mb4_unicode_ci COMMENT 'Directory of form.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `forms_encounter_foreign` (`encounter`),
  KEY `forms_pid_foreign` (`pid`),
  KEY `forms_userid_foreign` (`userID`),
  KEY `forms_form_id_index` (`form_id`),
  CONSTRAINT `forms_encounter_foreign` FOREIGN KEY (`encounter`) REFERENCES `form_encounters` (`encounter`) ON DELETE CASCADE,
  CONSTRAINT `forms_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `forms_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forms`
--

LOCK TABLES `forms` WRITE;
/*!40000 ALTER TABLE `forms` DISABLE KEYS */;
/*!40000 ALTER TABLE `forms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `insurance_companies`
--

DROP TABLE IF EXISTS `insurance_companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `insurance_companies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `addressID` int(10) unsigned NOT NULL COMMENT 'Foreign Key to addresses table.',
  `attn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Attn',
  `cms_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'CMS Id',
  `alt_cms_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Alternate CMS Id',
  `ins_type_code` int(10) unsigned DEFAULT NULL COMMENT 'Payer Type ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `insurance_companies_addressid_foreign` (`addressID`),
  CONSTRAINT `insurance_companies_addressid_foreign` FOREIGN KEY (`addressID`) REFERENCES `addresses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `insurance_companies`
--

LOCK TABLES `insurance_companies` WRITE;
/*!40000 ALTER TABLE `insurance_companies` DISABLE KEYS */;
/*!40000 ALTER TABLE `insurance_companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `insurance_numbers`
--

DROP TABLE IF EXISTS `insurance_numbers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `insurance_numbers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `provider_id` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `insurance_company_id` int(10) unsigned NOT NULL COMMENT 'Foreign key to insurance_companies table.',
  `provider_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NA' COMMENT 'Provider Number',
  `rendering_provider_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NA' COMMENT 'Rendering Provider Number',
  `provider_number_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NA' COMMENT 'Provider Number Type',
  `rendering_provider_number_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NA' COMMENT 'Rendering Provider Number Type',
  `group_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NA' COMMENT 'Group Number',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `insurance_numbers_provider_id_foreign` (`provider_id`),
  KEY `insurance_numbers_insurance_company_id_foreign` (`insurance_company_id`),
  CONSTRAINT `insurance_numbers_insurance_company_id_foreign` FOREIGN KEY (`insurance_company_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `insurance_numbers_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `insurance_numbers`
--

LOCK TABLES `insurance_numbers` WRITE;
/*!40000 ALTER TABLE `insurance_numbers` DISABLE KEYS */;
/*!40000 ALTER TABLE `insurance_numbers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `libreehr_calendar_categories`
--

DROP TABLE IF EXISTS `libreehr_calendar_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `libreehr_calendar_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `libreehr_calendar_categories`
--

LOCK TABLES `libreehr_calendar_categories` WRITE;
/*!40000 ALTER TABLE `libreehr_calendar_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `libreehr_calendar_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `libreehr_calendar_events`
--

DROP TABLE IF EXISTS `libreehr_calendar_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `libreehr_calendar_events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `libreehr_calendar_events`
--

LOCK TABLES `libreehr_calendar_events` WRITE;
/*!40000 ALTER TABLE `libreehr_calendar_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `libreehr_calendar_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=205 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (155,'2014_10_12_000000_create_users_table',1),(156,'2014_10_12_100000_create_password_resets_table',1),(157,'2017_06_17_083322_create_addresses_table',1),(158,'2017_06_17_084322_create_user_secures_table',1),(159,'2017_06_17_085507_create_user_resedential_links_table',1),(160,'2017_06_17_094408_create_user_settings_table',1),(161,'2017_06_17_101539_create_user_addr_books_table',1),(162,'2017_06_17_103044_create_user_password_histories_table',1),(163,'2017_06_17_103947_create_user_communications_table',1),(164,'2017_06_17_111354_create_patient_datas_table',1),(165,'2017_06_17_111916_create_patient_face_sheets_table',1),(166,'2017_06_17_134316_create_patient_social_statistics_table',1),(167,'2017_06_17_134845_create_patient_employers_table',1),(168,'2017_06_17_135505_create_patient_contacts_table',1),(169,'2017_06_17_141531_create_patient_contact_links_table',1),(170,'2017_06_17_142000_create_patient_privacy_contacts_table',1),(171,'2017_06_17_142423_create_patient_contact_communications_table',1),(172,'2017_06_17_192428_create_amendments_table',1),(173,'2017_06_17_195953_create_amendment_histories_table',1),(174,'2017_06_19_193813_create_facilities_table',1),(175,'2017_06_19_202456_create_user_facility_links_table',1),(176,'2017_06_20_162245_create_libreehr_calendar_categories_table',1),(177,'2017_06_20_162307_create_libreehr_calendar_events_table',1),(178,'2017_06_20_172045_create_form_encounters_table',1),(179,'2017_06_20_172800_create_forms_table',1),(180,'2017_06_20_195925_create_form_aftercare_plans_table',1),(181,'2017_06_21_144239_create_form_ankleinjuries_table',1),(182,'2017_06_21_173702_create_form_annotate_diagrams_table',1),(183,'2017_06_22_011754_create_form_clinical_instructions_table',1),(184,'2017_06_22_012807_create_form_dictations_table',1),(185,'2017_06_22_014513_create_form_notes_table',1),(186,'2017_06_22_020654_create_form_painmaps_table',1),(187,'2017_06_22_172607_create_form_physical_exams_table',1),(188,'2017_06_22_181230_create_form_physical_exam_diagnoses_table',1),(189,'2017_06_22_184239_create_form_prior_auths_table',1),(190,'2017_06_22_190845_create_form_soaps_table',1),(191,'2017_06_23_162354_create_form_track_anything_types_table',1),(192,'2017_06_23_173954_create_form_track_anythings_table',1),(193,'2017_06_23_183725_create_form_track_anything_results_table',1),(194,'2017_06_23_194614_create_form_transfer_summaries_table',1),(195,'2017_06_24_032958_create_form_treatment_plans_table',1),(196,'2017_06_24_043121_create_form_vitals_table',1),(197,'2017_06_24_054151_create_form_misc_billing_options_table',1),(198,'2017_06_24_123245_create_form_bronchitis_table',1),(199,'2017_06_24_193504_create_form_reviewofs_table',1),(200,'2017_06_25_065204_create_form_ros_table',2),(201,'2017_06_27_170625_create_x12_partners_table',3),(202,'2017_06_28_162853_create_insurance_companies_table',4),(203,'2017_06_28_165512_create_insurance_numbers_table',5),(204,'2017_06_28_181202_create_pnotes_table',6);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_contact_communications`
--

DROP TABLE IF EXISTS `patient_contact_communications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_contact_communications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key Auto Increment',
  `pid` int(10) unsigned NOT NULL COMMENT 'creates foreign key to link patient_datas table.',
  `contactId` int(10) unsigned NOT NULL COMMENT 'creates foreign key to link patient_contacts table.',
  `mailMode` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Allow through email. Initially set to be 0. Previously hippa_mail.',
  `voiceMode` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Allow voice message. Initially set to be 0. Previously hippa_voice.',
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'What message to be sent. Previously hippa_message.',
  `messageMode` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Allow text sms. Initially set to be 0. Previously hippa_allowsms.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_contact_communications_pid_foreign` (`pid`),
  KEY `patient_contact_communications_contactid_foreign` (`contactId`),
  CONSTRAINT `patient_contact_communications_contactid_foreign` FOREIGN KEY (`contactId`) REFERENCES `patient_contacts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patient_contact_communications_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_contact_communications`
--

LOCK TABLES `patient_contact_communications` WRITE;
/*!40000 ALTER TABLE `patient_contact_communications` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_contact_communications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_contact_links`
--

DROP TABLE IF EXISTS `patient_contact_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_contact_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement.',
  `contactsId` int(10) unsigned NOT NULL COMMENT 'This will be foreign key to link patient_contacts table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign key to patient_datas table.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_contact_links_contactsid_foreign` (`contactsId`),
  KEY `patient_contact_links_pid_foreign` (`pid`),
  CONSTRAINT `patient_contact_links_contactsid_foreign` FOREIGN KEY (`contactsId`) REFERENCES `patient_contacts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patient_contact_links_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_contact_links`
--

LOCK TABLES `patient_contact_links` WRITE;
/*!40000 ALTER TABLE `patient_contact_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_contact_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_contacts`
--

DROP TABLE IF EXISTS `patient_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key, Autoincrement',
  `providerId` int(11) NOT NULL COMMENT 'Provider Id',
  `refProviderId` int(11) NOT NULL COMMENT 'Reference Provider Id',
  `home_phone` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Contact home phone',
  `work_phone` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Contact business phone',
  `contact_phone` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Personal contact phone number.',
  `contact_relationship` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'what relationship does contact have with patient.',
  `patient_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Additional field added. Contact email id.',
  `county` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'county',
  `country_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'country code of patient.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patient_contacts_patient_email_unique` (`patient_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_contacts`
--

LOCK TABLES `patient_contacts` WRITE;
/*!40000 ALTER TABLE `patient_contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_datas`
--

DROP TABLE IF EXISTS `patient_datas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_datas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key Autoincrement.',
  `pid` int(10) unsigned NOT NULL COMMENT 'pid is patient id. More reasonable in case of medical information.',
  `title` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'title viz Mr., Ms., Mrs., etc.',
  `occupation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'occupation of patient.',
  `industry` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'industry in which patient work.',
  `addressId` int(10) unsigned NOT NULL COMMENT 'This will be used as foreign key for address linking to patient.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patient_datas_pid_unique` (`pid`),
  KEY `patient_datas_addressid_foreign` (`addressId`),
  CONSTRAINT `patient_datas_addressid_foreign` FOREIGN KEY (`addressId`) REFERENCES `addresses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_datas`
--

LOCK TABLES `patient_datas` WRITE;
/*!40000 ALTER TABLE `patient_datas` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_datas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_employers`
--

DROP TABLE IF EXISTS `patient_employers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_employers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign key to patient_datas table.',
  `addressId` int(10) unsigned NOT NULL COMMENT 'To create link with addresses table.',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Employers Name',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_employers_addressid_foreign` (`addressId`),
  KEY `patient_employers_pid_foreign` (`pid`),
  CONSTRAINT `patient_employers_addressid_foreign` FOREIGN KEY (`addressId`) REFERENCES `addresses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patient_employers_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_employers`
--

LOCK TABLES `patient_employers` WRITE;
/*!40000 ALTER TABLE `patient_employers` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_employers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_face_sheets`
--

DROP TABLE IF EXISTS `patient_face_sheets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_face_sheets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Autoincrement Primary Key',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign key to patient_datas table.',
  `f_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'First Name of patient.',
  `m_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Middle Name of Patient. Optional',
  `l_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Last Name of Patient',
  `DOB` date NOT NULL COMMENT 'Date of Birth of Patient.',
  `marietal_status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Marietal Status of Patient.',
  `license_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'License Id of patient.',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Email Id of Patient',
  `sex` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sex of Patient',
  `billing_note` text COLLATE utf8mb4_unicode_ci COMMENT 'Billing Note',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patient_face_sheets_license_id_unique` (`license_id`),
  UNIQUE KEY `patient_face_sheets_email_unique` (`email`),
  KEY `patient_face_sheets_pid_foreign` (`pid`),
  CONSTRAINT `patient_face_sheets_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_face_sheets`
--

LOCK TABLES `patient_face_sheets` WRITE;
/*!40000 ALTER TABLE `patient_face_sheets` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_face_sheets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_privacy_contacts`
--

DROP TABLE IF EXISTS `patient_privacy_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_privacy_contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign key to patient_datas table.',
  `contactId` int(10) unsigned NOT NULL COMMENT 'Foreign key to patient_contacts table.',
  `allow_patient_portal` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Allow patient portal. Initially allowed to be false.',
  `allow_health_info_ex` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Allow health information exchange. Initially allowed to be false.',
  `allow_imm_info_share` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Allow immunization information share. Initially allowed to be false.',
  `allow_imm_reg_use` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Allow immunization registration info. Initially allowed to be false.',
  `vfc` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Allow vfc. Initially allowed to be false.',
  `secure_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Previously email_direct. Secure email of patient.',
  `deceased_reason` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Reason for deaceasing.',
  `deceased_date` date DEFAULT NULL COMMENT 'Deceased date',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_privacy_contacts_pid_foreign` (`pid`),
  KEY `patient_privacy_contacts_contactid_foreign` (`contactId`),
  CONSTRAINT `patient_privacy_contacts_contactid_foreign` FOREIGN KEY (`contactId`) REFERENCES `patient_contacts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patient_privacy_contacts_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_privacy_contacts`
--

LOCK TABLES `patient_privacy_contacts` WRITE;
/*!40000 ALTER TABLE `patient_privacy_contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_privacy_contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_social_statistics`
--

DROP TABLE IF EXISTS `patient_social_statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_social_statistics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key Autoincrement.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign key to patient_datas table.',
  `ethnicity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ethnicity.',
  `religion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Religion',
  `interpreter` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Interpreter',
  `migrant_seasonal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Whetehr Migrant or Seasonal?',
  `family_size` int(11) NOT NULL COMMENT 'Family Size of Patient',
  `monthly_income` decimal(5,2) NOT NULL COMMENT 'Mothly income of patient.',
  `homeless` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is homeless or not? 0 -> No | 1 -> Yes',
  `financial_review` datetime NOT NULL COMMENT 'Financial Review Date',
  `language` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Language.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_social_statistics_pid_foreign` (`pid`),
  CONSTRAINT `patient_social_statistics_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_social_statistics`
--

LOCK TABLES `patient_social_statistics` WRITE;
/*!40000 ALTER TABLE `patient_social_statistics` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_social_statistics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pnotes`
--

DROP TABLE IF EXISTS `pnotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pnotes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign key to patient_datas table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table',
  `assigned_to` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table. It is basically that user whome that note is assigned.',
  `date` datetime NOT NULL COMMENT 'Timestamp at which date is created.',
  `body` text COLLATE utf8mb4_unicode_ci COMMENT 'Message sent by user.',
  `activity` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is note active? 0 -> No | 1 -> Yes',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is note authorized? 0 -> No | 1 -> Yes',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Indicates note is deleted. 0 -> No | 1 -> Yes',
  `message_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'New' COMMENT 'Status of message',
  `portal_relation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Patient Portal Relation',
  `is_msg_encrypted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'WHether message is encrypted? 0 -> No | 1 -> Yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pnotes_pid_foreign` (`pid`),
  KEY `pnotes_userid_foreign` (`userID`),
  KEY `pnotes_assigned_to_foreign` (`assigned_to`),
  CONSTRAINT `pnotes_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pnotes_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pnotes_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pnotes`
--

LOCK TABLES `pnotes` WRITE;
/*!40000 ALTER TABLE `pnotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `pnotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_addr_books`
--

DROP TABLE IF EXISTS `user_addr_books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_addr_books` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Auto Increment.',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to linke users table.',
  `title` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Title',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Email Id of user. Earlier we had two fields email and email_direct. Taking one email id.',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'User website url.',
  `assistant` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Assistant',
  `organization` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'User Organization',
  `valedictory` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Field that should be converted to something to store credentials, like M.D., so that you do not get user and provider last names like Gupta M.D.',
  `speciality` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'User Speciality like physician, etc.',
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'To store user notes.',
  `abook_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Address Book Type',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_addr_books_email_unique` (`email`),
  KEY `user_addr_books_userid_foreign` (`userID`),
  CONSTRAINT `user_addr_books_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_addr_books`
--

LOCK TABLES `user_addr_books` WRITE;
/*!40000 ALTER TABLE `user_addr_books` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_addr_books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_communications`
--

DROP TABLE IF EXISTS `user_communications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_communications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key. Autoincrement',
  `userID` int(10) unsigned NOT NULL COMMENT 'Used to create link with users table.',
  `contact_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Contact number.',
  `type` int(11) NOT NULL COMMENT 'Contact Number Type. 0 -> home_phone | 1 -> work_phone | 2 -> mobile | 3 -> fax | 4 -> alternate_work_phone',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_communications_userid_foreign` (`userID`),
  CONSTRAINT `user_communications_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_communications`
--

LOCK TABLES `user_communications` WRITE;
/*!40000 ALTER TABLE `user_communications` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_communications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_facility_links`
--

DROP TABLE IF EXISTS `user_facility_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_facility_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `userID` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `facilityId` int(10) unsigned NOT NULL COMMENT 'Foreign key to facilities table.',
  `isDefault` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is the current facilty default? Note that it must be updated if any edit in UI.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_facility_links_userid_foreign` (`userID`),
  KEY `user_facility_links_facilityid_foreign` (`facilityId`),
  CONSTRAINT `user_facility_links_facilityid_foreign` FOREIGN KEY (`facilityId`) REFERENCES `facilities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_facility_links_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_facility_links`
--

LOCK TABLES `user_facility_links` WRITE;
/*!40000 ALTER TABLE `user_facility_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_facility_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_password_histories`
--

DROP TABLE IF EXISTS `user_password_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_password_histories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `userID` int(10) unsigned NOT NULL COMMENT 'To create link with users table.',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Store password.',
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Store timestamp of last updated password.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_password_histories_userid_foreign` (`userID`),
  CONSTRAINT `user_password_histories_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_password_histories`
--

LOCK TABLES `user_password_histories` WRITE;
/*!40000 ALTER TABLE `user_password_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_password_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_resedential_links`
--

DROP TABLE IF EXISTS `user_resedential_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_resedential_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement.',
  `addressID` int(10) unsigned NOT NULL COMMENT 'To create link with addresses table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'To create link with user_infos table.',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT 'What type of address is? 0 -> primary address | 1 -> alternate address.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_resedential_links_addressid_foreign` (`addressID`),
  KEY `user_resedential_links_userid_foreign` (`userID`),
  CONSTRAINT `user_resedential_links_addressid_foreign` FOREIGN KEY (`addressID`) REFERENCES `addresses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_resedential_links_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_resedential_links`
--

LOCK TABLES `user_resedential_links` WRITE;
/*!40000 ALTER TABLE `user_resedential_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_resedential_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_secures`
--

DROP TABLE IF EXISTS `user_secures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_secures` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Auto increment primary key of table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'To be used as foreign key to users.',
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'username of user.',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Password of user.',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is user active? 0 -> No, 1 -> Yes.',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is user authorised? 0 -> No, 1 -> Yes.',
  `pwd_expiration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Set password expiration date. Initially it is set from globals.',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Remember me token. Can be dropped if not used.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_secures_username_unique` (`username`),
  KEY `user_secures_userid_foreign` (`userID`),
  CONSTRAINT `user_secures_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_secures`
--

LOCK TABLES `user_secures` WRITE;
/*!40000 ALTER TABLE `user_secures` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_secures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_settings`
--

DROP TABLE IF EXISTS `user_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key. Autoincremement.',
  `userID` int(10) unsigned NOT NULL COMMENT 'To create foreign key to users table',
  `setting_label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Setting Label',
  `setting_value` int(11) NOT NULL COMMENT 'Setting Value',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_settings_userid_foreign` (`userID`),
  KEY `user_settings_setting_label_index` (`setting_label`),
  CONSTRAINT `user_settings_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_settings`
--

LOCK TABLES `user_settings` WRITE;
/*!40000 ALTER TABLE `user_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement.',
  `fname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'User First Name. Required.',
  `mname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'User Middle Name. Null allowed.',
  `lname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'User Last Name. Required.',
  `federalTaxId` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Federal Tax ID.',
  `federalDrugId` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Federal Drug ID. DEA Number',
  `see_auth` int(11) NOT NULL COMMENT 'See Authorization. 0 -> None | 1 -> All | 2 -> Only Mine',
  `npi` int(11) NOT NULL COMMENT 'National Provider Identifier.',
  `suffix` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'User Suffix',
  `taxonomy` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Taxonomy',
  `calendar_ui` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Calendar Preference. viz Outlook, Original and Fancy',
  `info` text COLLATE utf8mb4_unicode_ci COMMENT 'Information About User.',
  `newcrop_user_role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Role of created user. Like admin, nurse, doctor, etc.',
  `access_control` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Acccess Control of user. Viz Accounting, Administrators, Clinicians, etc.',
  `inCalendar` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'If user wants calendar or not. 0 -> No | 1 -> Yes ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_federaltaxid_unique` (`federalTaxId`),
  UNIQUE KEY `users_federaldrugid_unique` (`federalDrugId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `x12_partners`
--

DROP TABLE IF EXISTS `x12_partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `x12_partners` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'x12 Partner Name',
  `id_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID Number (ETIN)',
  `x12_sender_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'X12 Sender ID. ISA 06',
  `x12_receiver_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'X12 Receiver ID. ISA 08',
  `x12_version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Version',
  `processing_format` enum('standard','medi-cal','cms','proxymed') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Processing Format',
  `x12_isa_01` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '00' COMMENT 'User Logon Required Indicator',
  `x12_isa_02` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'User Logon. If 03 in x12_isa_01.',
  `x12_isa_03` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '00' COMMENT 'User Password required indicator.',
  `x12_isa_04` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'User Password. If 01 in x12_isa_03.',
  `x12_isa_05` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ZZ' COMMENT 'Sender Id Qualifier.',
  `x12_isa_07` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ZZ' COMMENT 'Receiver Id Qualifier',
  `x12_isa_14` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Acknowledgement Requested. 0 -> No | 1 -> Yes',
  `x12_isa_15` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'P' COMMENT 'Usage Indicator. viz Production(P) and Testing(T)',
  `x12_gs_02` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Application Sender Code',
  `x12_per_06` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Submitted EDI Access No.',
  `x12_gs_03` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Application Receiver Code',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `x12_partners_x12_sender_id_index` (`x12_sender_id`),
  KEY `x12_partners_x12_receiver_id_index` (`x12_receiver_id`),
  KEY `x12_partners_id_number_index` (`id_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `x12_partners`
--

LOCK TABLES `x12_partners` WRITE;
/*!40000 ALTER TABLE `x12_partners` DISABLE KEYS */;
/*!40000 ALTER TABLE `x12_partners` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-06-29  8:38:42
