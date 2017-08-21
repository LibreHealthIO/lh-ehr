-- MySQL dump 10.13  Distrib 5.7.19, for Linux (x86_64)
--
-- Host: localhost    Database: LibreLaravel
-- ------------------------------------------------------
-- Server version	5.7.19-0ubuntu0.16.04.1

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
-- Table structure for table `audit_details`
--

DROP TABLE IF EXISTS `audit_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_details`
--

LOCK TABLES `audit_details` WRITE;
/*!40000 ALTER TABLE `audit_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `audit_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_masters`
--

DROP TABLE IF EXISTS `audit_masters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_masters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_masters`
--

LOCK TABLES `audit_masters` WRITE;
/*!40000 ALTER TABLE `audit_masters` DISABLE KEYS */;
/*!40000 ALTER TABLE `audit_masters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `automatic_notifications`
--

DROP TABLE IF EXISTS `automatic_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `automatic_notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Autoincrement. Primary Key',
  `sms_gateway_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of sms gateway.',
  `next_app_schedule` datetime NOT NULL COMMENT 'When next notification is scheduled?',
  `provider_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci COMMENT 'Message to be sent.',
  `email_sender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Who sent email? It can be any user or any clinic.',
  `email_subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Subject of notification',
  `type` enum('SMS','Email') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SMS' COMMENT 'Type of notification.',
  `notification_sent_date` datetime NOT NULL COMMENT 'When notification was sent.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `automatic_notifications`
--

LOCK TABLES `automatic_notifications` WRITE;
/*!40000 ALTER TABLE `automatic_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `automatic_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `background_services`
--

DROP TABLE IF EXISTS `background_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `background_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Service Name',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Name for reports',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is service active or deleted.  0 -> False | 1 -> True',
  `running` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is service running or stopped. 0 -> Stopped(False) | 1 -> Running(True)',
  `next_run` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'When next run is scheduled?',
  `execute_interval` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Minimum number of minutes between function calls, 0 = Manual Mode',
  `function` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Name of background service function',
  `sort_order` int(10) unsigned NOT NULL COMMENT 'If there are multiple services, then lower number will run first.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `background_services_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `background_services`
--

LOCK TABLES `background_services` WRITE;
/*!40000 ALTER TABLE `background_services` DISABLE KEYS */;
/*!40000 ALTER TABLE `background_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `batchcoms`
--

DROP TABLE IF EXISTS `batchcoms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `batchcoms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign key to patient_datas table.',
  `sent_by` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `msg_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Message Type',
  `msg_subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Subject',
  `msg_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Message to be sent.',
  `msg_date_sent` datetime NOT NULL COMMENT 'Timestamp when message was sent.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `batchcoms_pid_foreign` (`pid`),
  KEY `batchcoms_sent_by_foreign` (`sent_by`),
  CONSTRAINT `batchcoms_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `batchcoms_sent_by_foreign` FOREIGN KEY (`sent_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batchcoms`
--

LOCK TABLES `batchcoms` WRITE;
/*!40000 ALTER TABLE `batchcoms` DISABLE KEYS */;
/*!40000 ALTER TABLE `batchcoms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Name of category.',
  `parent` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Parent of sub directory. Category directory is root and hence it will have parent as 0.',
  `lft` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Left Subtree.',
  `rght` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Right Subtree',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories_to_documents`
--

DROP TABLE IF EXISTS `categories_to_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories_to_documents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key. Autoincrement.',
  `category_id` int(10) unsigned NOT NULL COMMENT 'Foreign Key to categories table.',
  `document_id` int(10) unsigned NOT NULL COMMENT 'Foreign key to Documents table.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_to_documents_category_id_foreign` (`category_id`),
  KEY `categories_to_documents_document_id_foreign` (`document_id`),
  CONSTRAINT `categories_to_documents_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `categories_to_documents_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories_to_documents`
--

LOCK TABLES `categories_to_documents` WRITE;
/*!40000 ALTER TABLE `categories_to_documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories_to_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chart_trackers`
--

DROP TABLE IF EXISTS `chart_trackers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chart_trackers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key. Autoincrement.',
  `user_id` int(10) unsigned NOT NULL COMMENT 'Foreign Key to users table.',
  `when` datetime NOT NULL COMMENT 'Timestamp when this created.',
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Location',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chart_trackers_user_id_foreign` (`user_id`),
  CONSTRAINT `chart_trackers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chart_trackers`
--

LOCK TABLES `chart_trackers` WRITE;
/*!40000 ALTER TABLE `chart_trackers` DISABLE KEYS */;
/*!40000 ALTER TABLE `chart_trackers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dated_reminder_links`
--

DROP TABLE IF EXISTS `dated_reminder_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dated_reminder_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Autoincrement. Primary Key.',
  `dr_id` int(10) unsigned NOT NULL COMMENT 'Link to dated_reminders table.',
  `to_id` int(10) unsigned NOT NULL COMMENT 'Link to users table',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dated_reminder_links_dr_id_foreign` (`dr_id`),
  KEY `dated_reminder_links_to_id_foreign` (`to_id`),
  CONSTRAINT `dated_reminder_links_dr_id_foreign` FOREIGN KEY (`dr_id`) REFERENCES `dated_reminders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dated_reminder_links_to_id_foreign` FOREIGN KEY (`to_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dated_reminder_links`
--

LOCK TABLES `dated_reminder_links` WRITE;
/*!40000 ALTER TABLE `dated_reminder_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `dated_reminder_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dated_reminders`
--

DROP TABLE IF EXISTS `dated_reminders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dated_reminders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `dr_from_ID` int(10) unsigned NOT NULL COMMENT 'Who created dated reminder? Refers to users table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign key to patient_datas table.',
  `dr_message_text` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Message.',
  `dr_message_sent_date` datetime NOT NULL COMMENT 'When message is sent.',
  `dr_message_due_date` date NOT NULL COMMENT 'Due Date',
  `message_priority` int(10) unsigned NOT NULL COMMENT 'Priority of Message. 1 -> High | 2 -> Medium | 3 -> Low',
  `message_processed` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is message processed? 0 -> No | 1 -> Yes',
  `processed_date` datetime NOT NULL COMMENT 'When message is processed by respective user? Not keeping it null, becuase it can be system generated datetime, whenever message is processed.',
  `dr_processed_by` int(10) unsigned DEFAULT NULL COMMENT 'User who processed the message. It can be multiple users or a single user. References users table.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dated_reminders_pid_foreign` (`pid`),
  KEY `dated_reminders_dr_from_id_foreign` (`dr_from_ID`),
  KEY `dated_reminders_dr_processed_by_foreign` (`dr_processed_by`),
  CONSTRAINT `dated_reminders_dr_from_id_foreign` FOREIGN KEY (`dr_from_ID`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dated_reminders_dr_processed_by_foreign` FOREIGN KEY (`dr_processed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dated_reminders_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dated_reminders`
--

LOCK TABLES `dated_reminders` WRITE;
/*!40000 ALTER TABLE `dated_reminders` DISABLE KEYS */;
/*!40000 ALTER TABLE `dated_reminders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Auto increment. Primary Key.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign key to patient_datas table. Earlier foreign_id.',
  `owner` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `list_id` int(10) unsigned DEFAULT NULL COMMENT 'Foreign key to lists table.',
  `encounter_id` int(10) unsigned DEFAULT NULL COMMENT 'Foreign key to form_encounters table.',
  `audit_master_id` int(10) unsigned DEFAULT NULL COMMENT 'Foreign key to audit_masters table.',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Path of uploaded file.',
  `mimetype` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of uploaded file. Image or text.',
  `revision` datetime NOT NULL COMMENT 'Timestamp when document was revised.',
  `docdate` date NOT NULL COMMENT 'When document was uploaded.',
  `hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '40 character SHA-1 hash of document.',
  `imported` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Parsing status for CCR/CCD/CCDA importing. 0 -> False | 1 -> True',
  `encounter_check` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'If encounter is created while tagging. 0 -> No | 1 -> Yes',
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Notes related to docuements.',
  `audit_master_approval_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'approval_status from audit_master table.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documents_pid_foreign` (`pid`),
  KEY `documents_owner_foreign` (`owner`),
  KEY `documents_list_id_foreign` (`list_id`),
  KEY `documents_encounter_id_foreign` (`encounter_id`),
  KEY `documents_audit_master_id_foreign` (`audit_master_id`),
  CONSTRAINT `documents_audit_master_id_foreign` FOREIGN KEY (`audit_master_id`) REFERENCES `audit_masters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documents_encounter_id_foreign` FOREIGN KEY (`encounter_id`) REFERENCES `form_encounters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documents_list_id_foreign` FOREIGN KEY (`list_id`) REFERENCES `lists` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documents_owner_foreign` FOREIGN KEY (`owner`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documents_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drug_inventories`
--

DROP TABLE IF EXISTS `drug_inventories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drug_inventories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement.',
  `drug_id` int(10) unsigned NOT NULL COMMENT 'Foreign key to drugs table.',
  `warehouse_id` int(10) unsigned NOT NULL COMMENT 'Foreign key to product_warehouses table.',
  `lot_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'LOT Number. Unique Number for drugs.',
  `expiration` date DEFAULT NULL COMMENT 'Expiry Date of Drug.',
  `manufacturer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Manufacturer of Drug.',
  `on_hand` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Count of drug already in inventory.',
  `vendor_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Vendor.',
  `destroy_date` date DEFAULT NULL COMMENT 'Date when drug is destroyed.',
  `destroy_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Method used to destroy drug.',
  `destroy_witness` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Witness at the time of destruction.',
  `destroy_notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Extra information.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `drug_inventories_lot_number_unique` (`lot_number`),
  KEY `drug_inventories_drug_id_foreign` (`drug_id`),
  KEY `drug_inventories_warehouse_id_foreign` (`warehouse_id`),
  KEY `drug_inventories_vendor_id_index` (`vendor_id`),
  CONSTRAINT `drug_inventories_drug_id_foreign` FOREIGN KEY (`drug_id`) REFERENCES `drugs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `drug_inventories_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `product_warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drug_inventories`
--

LOCK TABLES `drug_inventories` WRITE;
/*!40000 ALTER TABLE `drug_inventories` DISABLE KEYS */;
/*!40000 ALTER TABLE `drug_inventories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drug_sales`
--

DROP TABLE IF EXISTS `drug_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drug_sales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `drug_id` int(10) unsigned NOT NULL COMMENT 'Foreign key to drugs table.',
  `inventory_id` int(10) unsigned NOT NULL COMMENT 'Foreign key to drug_inventories table.',
  `prescription_id` int(10) unsigned DEFAULT NULL COMMENT 'Foreign key to prescriptions table.',
  `pid` int(10) unsigned DEFAULT NULL COMMENT 'Foreign key to patient_datas table.',
  `encounter` int(10) unsigned DEFAULT NULL COMMENT 'Foreign key to form_encounters table.',
  `user` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `distributor_id` int(10) unsigned DEFAULT NULL COMMENT 'Distributor of drug. Foreign key to users table.',
  `sale_date` date NOT NULL COMMENT 'Date when drug is sold.',
  `quantity` int(10) unsigned NOT NULL,
  `fee` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Fees of Drugs.',
  `billed` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'If the sale is posted to accounting? 0 -> No | 1 -> Yes',
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Notes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `drug_sales_drug_id_foreign` (`drug_id`),
  KEY `drug_sales_inventory_id_foreign` (`inventory_id`),
  KEY `drug_sales_prescription_id_foreign` (`prescription_id`),
  KEY `drug_sales_pid_foreign` (`pid`),
  KEY `drug_sales_encounter_foreign` (`encounter`),
  KEY `drug_sales_user_foreign` (`user`),
  KEY `drug_sales_distributor_id_foreign` (`distributor_id`),
  CONSTRAINT `drug_sales_distributor_id_foreign` FOREIGN KEY (`distributor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `drug_sales_drug_id_foreign` FOREIGN KEY (`drug_id`) REFERENCES `drugs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `drug_sales_encounter_foreign` FOREIGN KEY (`encounter`) REFERENCES `form_encounters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `drug_sales_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `drug_inventories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `drug_sales_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `drug_sales_prescription_id_foreign` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `drug_sales_user_foreign` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drug_sales`
--

LOCK TABLES `drug_sales` WRITE;
/*!40000 ALTER TABLE `drug_sales` DISABLE KEYS */;
/*!40000 ALTER TABLE `drug_sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drug_templates`
--

DROP TABLE IF EXISTS `drug_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drug_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `drug_id` int(10) unsigned NOT NULL COMMENT 'Foreign Key to drugs table.',
  `selector` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Selector. Name of Template.',
  `dosage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Schedule.',
  `period` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Interval',
  `quantity` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Quantity',
  `refills` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Refills.',
  `taxrates` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tax Rate.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `drug_templates_selector_unique` (`selector`),
  KEY `drug_templates_drug_id_foreign` (`drug_id`),
  CONSTRAINT `drug_templates_drug_id_foreign` FOREIGN KEY (`drug_id`) REFERENCES `drugs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drug_templates`
--

LOCK TABLES `drug_templates` WRITE;
/*!40000 ALTER TABLE `drug_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `drug_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drugs`
--

DROP TABLE IF EXISTS `drugs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drugs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `related_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Codes',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Name of Drug.',
  `ndc_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'NDC Number',
  `on_order` int(10) unsigned NOT NULL COMMENT 'On Order.',
  `reorder_point` double(8,2) NOT NULL DEFAULT '0.00' COMMENT 'Min Global (In Form)',
  `max_level` double(8,2) NOT NULL DEFAULT '0.00' COMMENT 'Max Global (In Form)',
  `reactions` text COLLATE utf8mb4_unicode_ci COMMENT 'Reaction of drug.',
  `cyp_factor` double(8,2) NOT NULL COMMENT 'Quantity representing a years supply',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is Drug active? 0 -> No | 1 -> Yes',
  `allow_combining` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Allow filling an order from multiple lots? 0 -> No | 1 -> Yes',
  `allow_multiple` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Allow multiple lots at one warehouse? 0 -> No | 1 -> Yes',
  `drug_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Drug Code',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `drugs_drug_code_index` (`drug_code`),
  KEY `drugs_related_code_index` (`related_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drugs`
--

LOCK TABLES `drugs` WRITE;
/*!40000 ALTER TABLE `drugs` DISABLE KEYS */;
/*!40000 ALTER TABLE `drugs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `extended_logs`
--

DROP TABLE IF EXISTS `extended_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `extended_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign Key to patient_datas table.',
  `user` int(10) unsigned NOT NULL COMMENT 'Foreign Key to users table.',
  `date` datetime NOT NULL COMMENT 'Date when disclosure recorded.',
  `event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of Disclosure',
  `recipient` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Recipient of disclosure.',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Description of Disclosure.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `extended_logs_pid_foreign` (`pid`),
  KEY `extended_logs_user_foreign` (`user`),
  CONSTRAINT `extended_logs_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `extended_logs_user_foreign` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `extended_logs`
--

LOCK TABLES `extended_logs` WRITE;
/*!40000 ALTER TABLE `extended_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `extended_logs` ENABLE KEYS */;
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
  `primary_business_entity` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Identifies if this facility is a listing for the actual business running everything. 0 -> False | 1 -> True',
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
-- Table structure for table `globals`
--

DROP TABLE IF EXISTS `globals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `globals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `settings` json NOT NULL COMMENT 'Store global settings in json format.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `globals`
--

LOCK TABLES `globals` WRITE;
/*!40000 ALTER TABLE `globals` DISABLE KEYS */;
/*!40000 ALTER TABLE `globals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `history_datas`
--

DROP TABLE IF EXISTS `history_datas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `history_datas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign key to patient_datas table.',
  `risk_factors` json DEFAULT NULL COMMENT 'Stores risk factors data in json format. The field name is key and boolean is value.',
  `exams` json DEFAULT NULL COMMENT 'Stores exams/test data in json format.',
  `history_mother` json DEFAULT NULL COMMENT 'Stores history of mother with diagnosis code in json format.',
  `history_father` json DEFAULT NULL COMMENT 'Stores history of father with diagnosis code in json format.',
  `history_siblings` json DEFAULT NULL COMMENT 'Stores history of siblings with diagnosis code in json format.',
  `history_offspring` json DEFAULT NULL COMMENT 'Stores history of childeren with diagnosis code in json format.',
  `history_spouse` json DEFAULT NULL COMMENT 'Stores history of wife with diagnosis code in json format.',
  `relatives_cancer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Cancer information of relatives.',
  `relatives_diabetes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Diabetes information of relatives',
  `relatives_tuberculosis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tuberculosis information of relatives',
  `relatives_high_blood_pressure` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Blood Pressure information of relatives',
  `relatives_heart_problems` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Heart Related information of relatives',
  `relatives_stroke` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Stroke information of relatives.',
  `relatives_epilepsy` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Epilepsy information of relatives.',
  `relatives_mental_illness` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mental illeness information of relatives.',
  `coffee` json DEFAULT NULL COMMENT 'Coffe data in json format.',
  `tobacco` json DEFAULT NULL COMMENT 'Tobacco data.',
  `alcohol` json NOT NULL COMMENT 'Alcohol data.',
  `sleep_patterns` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Sleep Pattern.',
  `exercise_patterns` json DEFAULT NULL COMMENT 'Exercise Pattern.',
  `seatbelt_use` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Seatbelt Use.',
  `counseling` json DEFAULT NULL COMMENT 'Counselling',
  `hazardous_activities` json DEFAULT NULL COMMENT 'Hazardous Activities',
  `recreational_drugs` json DEFAULT NULL COMMENT 'Recreational Drugs.',
  `name_value` json DEFAULT NULL COMMENT 'Name-Value Pair',
  `additional_history` text COLLATE utf8mb4_unicode_ci COMMENT 'Additional History.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `history_datas_pid_foreign` (`pid`),
  CONSTRAINT `history_datas_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `history_datas`
--

LOCK TABLES `history_datas` WRITE;
/*!40000 ALTER TABLE `history_datas` DISABLE KEYS */;
/*!40000 ALTER TABLE `history_datas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `icd10_dx_order_codes`
--

DROP TABLE IF EXISTS `icd10_dx_order_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icd10_dx_order_codes` (
  `dx_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `dx_code` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ICD10 Dx code',
  `formatted_dx_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Formatted DX Code',
  `valid_for_coding` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Is it valid for coding?',
  `short_desc` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Short description of that code.',
  `long_desc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Long description of that code.',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is code Active? 0 -> No | 1 -> Yes',
  `revision` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Revision of code.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`dx_id`),
  KEY `icd10_dx_order_codes_formatted_dx_code_index` (`formatted_dx_code`),
  KEY `icd10_dx_order_codes_active_index` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `icd10_dx_order_codes`
--

LOCK TABLES `icd10_dx_order_codes` WRITE;
/*!40000 ALTER TABLE `icd10_dx_order_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `icd10_dx_order_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `icd10_gem_dx_10_9s`
--

DROP TABLE IF EXISTS `icd10_gem_dx_10_9s`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icd10_gem_dx_10_9s` (
  `map_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `dx_icd10_source` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ICD 10 Source.',
  `dx_icd9_target` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ICD 9 target.',
  `flags` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Flags',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is code active? 0 -> No  | 1 -> Yes',
  `revision` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Revision of code.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`map_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `icd10_gem_dx_10_9s`
--

LOCK TABLES `icd10_gem_dx_10_9s` WRITE;
/*!40000 ALTER TABLE `icd10_gem_dx_10_9s` DISABLE KEYS */;
/*!40000 ALTER TABLE `icd10_gem_dx_10_9s` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `icd10_gem_dx_9_10s`
--

DROP TABLE IF EXISTS `icd10_gem_dx_9_10s`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icd10_gem_dx_9_10s` (
  `map_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `dx_icd9_source` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ICD 9 Source.',
  `dx_icd10_target` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ICD 10 target.',
  `flags` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Flags',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is code active? 0 -> No  | 1 -> Yes',
  `revision` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Revision of code.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`map_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `icd10_gem_dx_9_10s`
--

LOCK TABLES `icd10_gem_dx_9_10s` WRITE;
/*!40000 ALTER TABLE `icd10_gem_dx_9_10s` DISABLE KEYS */;
/*!40000 ALTER TABLE `icd10_gem_dx_9_10s` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `icd10_gem_pcs_10_9s`
--

DROP TABLE IF EXISTS `icd10_gem_pcs_10_9s`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icd10_gem_pcs_10_9s` (
  `map_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `pcs_icd10_source` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ICD 10 Source.',
  `pcs_icd9_target` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ICD 9 target.',
  `flags` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Flags',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is code active? 0 -> No  | 1 -> Yes',
  `revision` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Revision of code.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`map_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `icd10_gem_pcs_10_9s`
--

LOCK TABLES `icd10_gem_pcs_10_9s` WRITE;
/*!40000 ALTER TABLE `icd10_gem_pcs_10_9s` DISABLE KEYS */;
/*!40000 ALTER TABLE `icd10_gem_pcs_10_9s` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `icd10_gem_pcs_9_10s`
--

DROP TABLE IF EXISTS `icd10_gem_pcs_9_10s`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icd10_gem_pcs_9_10s` (
  `map_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `pcs_icd9_source` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ICD 9 Source.',
  `pcs_icd10_target` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ICD 10 target.',
  `flags` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Flags',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is code active? 0 -> No  | 1 -> Yes',
  `revision` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Revision of code.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`map_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `icd10_gem_pcs_9_10s`
--

LOCK TABLES `icd10_gem_pcs_9_10s` WRITE;
/*!40000 ALTER TABLE `icd10_gem_pcs_9_10s` DISABLE KEYS */;
/*!40000 ALTER TABLE `icd10_gem_pcs_9_10s` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `icd10_pcs_order_codes`
--

DROP TABLE IF EXISTS `icd10_pcs_order_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icd10_pcs_order_codes` (
  `pcs_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `pcs_code` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ICD10 PCS code',
  `valid_for_coding` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Is it valid for coding?',
  `short_desc` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Short description of that code.',
  `long_desc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Long description of that code.',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is code Active? 0 -> No | 1 -> Yes',
  `revision` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Revision of code.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pcs_id`),
  KEY `icd10_pcs_order_codes_pcs_code_index` (`pcs_code`),
  KEY `icd10_pcs_order_codes_active_index` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `icd10_pcs_order_codes`
--

LOCK TABLES `icd10_pcs_order_codes` WRITE;
/*!40000 ALTER TABLE `icd10_pcs_order_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `icd10_pcs_order_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `icd10_reimbr_dx_9_10s`
--

DROP TABLE IF EXISTS `icd10_reimbr_dx_9_10s`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icd10_reimbr_dx_9_10s` (
  `map_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `code` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Code.',
  `code_cnt` tinyint(4) DEFAULT NULL,
  `ICD9_01` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ICD9_02` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ICD9_03` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ICD9_04` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ICD9_05` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ICD9_06` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is code active? 0 -> No | 1 -> Yes',
  `revision` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Code Revision',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`map_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `icd10_reimbr_dx_9_10s`
--

LOCK TABLES `icd10_reimbr_dx_9_10s` WRITE;
/*!40000 ALTER TABLE `icd10_reimbr_dx_9_10s` DISABLE KEYS */;
/*!40000 ALTER TABLE `icd10_reimbr_dx_9_10s` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `icd10_reimbr_pcs_9_10s`
--

DROP TABLE IF EXISTS `icd10_reimbr_pcs_9_10s`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icd10_reimbr_pcs_9_10s` (
  `map_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `code` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Code.',
  `code_cnt` tinyint(4) DEFAULT NULL,
  `ICD9_01` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ICD9_02` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ICD9_03` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ICD9_04` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ICD9_05` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ICD9_06` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is code active? 0 -> No | 1 -> Yes',
  `revision` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Code Revision',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`map_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `icd10_reimbr_pcs_9_10s`
--

LOCK TABLES `icd10_reimbr_pcs_9_10s` WRITE;
/*!40000 ALTER TABLE `icd10_reimbr_pcs_9_10s` DISABLE KEYS */;
/*!40000 ALTER TABLE `icd10_reimbr_pcs_9_10s` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `immunizations`
--

DROP TABLE IF EXISTS `immunizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `immunizations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign key to patient_datas table.',
  `administered_by` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `created_by` int(10) unsigned NOT NULL COMMENT 'User who created record. Foreign key to users table.',
  `updated_by` int(10) unsigned NOT NULL COMMENT 'User who updated record. Foreign key to users table.',
  `administered_date` datetime NOT NULL COMMENT 'Date when administered.',
  `cvx_code` int(10) unsigned NOT NULL COMMENT 'CVX Code Number',
  `manufacturer` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Manufacturer',
  `lot_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'LOT Number of Vaccine.',
  `education_date` date DEFAULT NULL COMMENT 'Date when Immunization Information Statements Given.',
  `vis_date` date DEFAULT NULL COMMENT 'Date of VIS statement.',
  `note` text COLLATE utf8mb4_unicode_ci COMMENT 'Note about immunization.',
  `amount_administered` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Amount of vaccine administered.',
  `amount_administered_unit` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mg' COMMENT 'Unit in which vaccine administered.',
  `expiration_date` date DEFAULT NULL COMMENT 'Date when vaccine expires.',
  `route` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Route for administration.',
  `administration_site` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Adminstration Site',
  `added_erroneously` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> False | 1 -> True',
  `external_id` int(10) unsigned DEFAULT NULL COMMENT 'To hold an ID number from some other system, such as another EHR, an assigned ID that exists on a proprietary medical device or the like.',
  `status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0 -> None | 1 -> Completed | 2 -> Refused | 3 -> Not Administered | 4 -> Partially Administered',
  `information_source` varchar(31) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Information Source',
  `refusal_reason` varchar(31) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Refusal Reason',
  `ordering_provider` int(10) unsigned DEFAULT NULL COMMENT 'Ordering Provider',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `immunizations_lot_number_unique` (`lot_number`),
  KEY `immunizations_pid_foreign` (`pid`),
  KEY `immunizations_administered_by_foreign` (`administered_by`),
  KEY `immunizations_created_by_foreign` (`created_by`),
  KEY `immunizations_updated_by_foreign` (`updated_by`),
  CONSTRAINT `immunizations_administered_by_foreign` FOREIGN KEY (`administered_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `immunizations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `immunizations_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `immunizations_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `immunizations`
--

LOCK TABLES `immunizations` WRITE;
/*!40000 ALTER TABLE `immunizations` DISABLE KEYS */;
/*!40000 ALTER TABLE `immunizations` ENABLE KEYS */;
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
  `x12_default_partner_id` int(10) unsigned NOT NULL COMMENT 'Foreign key to x12_partners. The real clearinghouse partner key, and is related to cms_id',
  `attn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Attn. Eg : Billing Department',
  `cms_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Insurance company identifier supplied by x12_default_partner published list.  NOT a UUID like an NPI or tax number.  Refer to Clearinghouse Payer List for value.',
  `ins_type_code` int(10) unsigned DEFAULT NULL COMMENT 'Payer Type ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `insurance_companies_addressid_foreign` (`addressID`),
  KEY `insurance_companies_x12_default_partner_id_foreign` (`x12_default_partner_id`),
  CONSTRAINT `insurance_companies_addressid_foreign` FOREIGN KEY (`addressID`) REFERENCES `addresses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `insurance_companies_x12_default_partner_id_foreign` FOREIGN KEY (`x12_default_partner_id`) REFERENCES `x12_partners` (`id`) ON DELETE CASCADE
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
-- Table structure for table `insurance_datas`
--

DROP TABLE IF EXISTS `insurance_datas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `insurance_datas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement.',
  `type` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Type of insurance data. 0 -> Primary | 1 -> Secomdary | 2 -> Tertiary',
  `provider` int(10) unsigned NOT NULL COMMENT 'Foreign key to insurance_companies table.',
  `subscriber_addr_id` int(10) unsigned DEFAULT NULL COMMENT 'Address of Subscriber. Foreign key to addresses table.',
  `employer_addr_id` int(10) unsigned DEFAULT NULL COMMENT 'Address of employer. Foreign key to addresses table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign Key to patient_datas table.',
  `plan_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Insurance Plan.',
  `policy_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Policy Number.',
  `group_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Group Number',
  `subscriber_lname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Last name of subscriber.',
  `subscriber_mname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Middle name of subscriber.',
  `subscriber_fname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'First Name of Subscriber.',
  `subscriber_relationship` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Relationship of subscriber with patient.',
  `subscriber_DOB` date NOT NULL COMMENT 'Date of birth of subscriber.',
  `subscriber_sex` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sex of subscriber.',
  `subscriber_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Phone number of subscriber.',
  `subscriber_employer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Employer of subscriber.',
  `copay` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Co Pay',
  `sDate` date NOT NULL COMMENT 'Start date of insurance.',
  `eDate` date NOT NULL COMMENT 'End date of insurance',
  `accept_assignment` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> False | 1 -> True',
  `policy_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Policy Type',
  `inactive` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is insurance active? 0 -> False | 1 -> True',
  `inactive_time` datetime NOT NULL COMMENT 'Time since inactive.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `insurance_datas_provider_foreign` (`provider`),
  KEY `insurance_datas_subscriber_addr_id_foreign` (`subscriber_addr_id`),
  KEY `insurance_datas_employer_addr_id_foreign` (`employer_addr_id`),
  KEY `insurance_datas_pid_foreign` (`pid`),
  KEY `insurance_datas_policy_number_index` (`policy_number`),
  KEY `insurance_datas_sdate_index` (`sDate`),
  CONSTRAINT `insurance_datas_employer_addr_id_foreign` FOREIGN KEY (`employer_addr_id`) REFERENCES `addresses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `insurance_datas_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `insurance_datas_provider_foreign` FOREIGN KEY (`provider`) REFERENCES `insurance_companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `insurance_datas_subscriber_addr_id_foreign` FOREIGN KEY (`subscriber_addr_id`) REFERENCES `addresses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `insurance_datas`
--

LOCK TABLES `insurance_datas` WRITE;
/*!40000 ALTER TABLE `insurance_datas` DISABLE KEYS */;
/*!40000 ALTER TABLE `insurance_datas` ENABLE KEYS */;
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
  CONSTRAINT `insurance_numbers_insurance_company_id_foreign` FOREIGN KEY (`insurance_company_id`) REFERENCES `insurance_companies` (`id`) ON DELETE CASCADE,
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
-- Table structure for table `lang_constants`
--

DROP TABLE IF EXISTS `lang_constants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lang_constants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Auto increment. Primary Key.',
  `constant_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Name of Constant',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lang_constants`
--

LOCK TABLES `lang_constants` WRITE;
/*!40000 ALTER TABLE `lang_constants` DISABLE KEYS */;
/*!40000 ALTER TABLE `lang_constants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lang_customs`
--

DROP TABLE IF EXISTS `lang_customs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lang_customs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key. Autoincrement.',
  `lang_code` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '2 Character Language Code.',
  `lang_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Description of langauge.',
  `cons_id` int(10) unsigned DEFAULT NULL COMMENT 'Foreign key to lang_constants table.',
  `definition` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Definition of language.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lang_customs_cons_id_foreign` (`cons_id`),
  CONSTRAINT `lang_customs_cons_id_foreign` FOREIGN KEY (`cons_id`) REFERENCES `lang_constants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lang_customs`
--

LOCK TABLES `lang_customs` WRITE;
/*!40000 ALTER TABLE `lang_customs` DISABLE KEYS */;
/*!40000 ALTER TABLE `lang_customs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lang_definitions`
--

DROP TABLE IF EXISTS `lang_definitions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lang_definitions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `lang_id` int(10) unsigned NOT NULL COMMENT 'Foreign key to lang_languages table.',
  `cons_id` int(10) unsigned NOT NULL COMMENT 'Foreign key to lang_constants table.',
  `definition` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Definition of language.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lang_definitions_lang_id_foreign` (`lang_id`),
  KEY `lang_definitions_cons_id_foreign` (`cons_id`),
  CONSTRAINT `lang_definitions_cons_id_foreign` FOREIGN KEY (`cons_id`) REFERENCES `lang_constants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lang_definitions_lang_id_foreign` FOREIGN KEY (`lang_id`) REFERENCES `lang_languages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lang_definitions`
--

LOCK TABLES `lang_definitions` WRITE;
/*!40000 ALTER TABLE `lang_definitions` DISABLE KEYS */;
/*!40000 ALTER TABLE `lang_definitions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lang_languages`
--

DROP TABLE IF EXISTS `lang_languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lang_languages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `lang_code` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Language code. 2 character code.',
  `lang_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Language Description.',
  `lang_is_rtl` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is language Right to left formatted.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lang_languages`
--

LOCK TABLES `lang_languages` WRITE;
/*!40000 ALTER TABLE `lang_languages` DISABLE KEYS */;
/*!40000 ALTER TABLE `lang_languages` ENABLE KEYS */;
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
-- Table structure for table `list_diagnoses`
--

DROP TABLE IF EXISTS `list_diagnoses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `list_diagnoses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key. Autoincrement.',
  `list_id` int(10) unsigned NOT NULL COMMENT 'Foreign key to lists table.',
  `diagnosis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Diagnosis.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `list_diagnoses_list_id_foreign` (`list_id`),
  CONSTRAINT `list_diagnoses_list_id_foreign` FOREIGN KEY (`list_id`) REFERENCES `lists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `list_diagnoses`
--

LOCK TABLES `list_diagnoses` WRITE;
/*!40000 ALTER TABLE `list_diagnoses` DISABLE KEYS */;
/*!40000 ALTER TABLE `list_diagnoses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lists`
--

DROP TABLE IF EXISTS `lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign Key to patient_datas table.',
  `user` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `date` datetime NOT NULL COMMENT 'Timestamp when list created.',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of list. Medications, Allergies, Surgery, Medical Problems, etc.',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Title of that particular type.',
  `begdate` date NOT NULL COMMENT 'Date of beginning of issue.',
  `enddate` date DEFAULT NULL COMMENT 'Date of end of this issue. Null if still active.',
  `occurrence` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Occurence of this issue. Recurrence, First, Early Recurrence, Late Recurrence, and Acute on Chronic.',
  `referredby` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Who referred this issue.',
  `activity` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Still Active. 0 -> No | 1 -> Yes',
  `comments` text COLLATE utf8mb4_unicode_ci COMMENT 'Comment about that issue.',
  `outcome` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Outcome of issue.',
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'destination.',
  `reaction` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Reaction of that issue.',
  `external_allergyid` int(10) unsigned DEFAULT NULL COMMENT 'External ERX Id.',
  `erx_source` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> LibreEHR | 1 -> External',
  `erx_uploaded` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> Pending to NewCrop upload | 1 -> Uploaded to NewCrop',
  `modifydate` datetime NOT NULL COMMENT 'Timestamp when issue modified.',
  `severity_al` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Severity Level.',
  `external_id` int(10) unsigned DEFAULT NULL COMMENT 'To hold an ID number from some other system, such as another EHR, an assigned ID that exists on a proprietary medical device or the like.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lists_pid_foreign` (`pid`),
  KEY `lists_user_foreign` (`user`),
  CONSTRAINT `lists_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `lists_user_foreign` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lists`
--

LOCK TABLES `lists` WRITE;
/*!40000 ALTER TABLE `lists` DISABLE KEYS */;
/*!40000 ALTER TABLE `lists` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=1377 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1283,'2014_10_12_000000_create_users_table',1),(1284,'2014_10_12_100000_create_password_resets_table',1),(1285,'2017_06_17_083322_create_addresses_table',1),(1286,'2017_06_17_084322_create_user_secures_table',1),(1287,'2017_06_17_094408_create_user_settings_table',1),(1288,'2017_06_17_101539_create_user_addr_books_table',1),(1289,'2017_06_17_103044_create_user_password_histories_table',1),(1290,'2017_06_17_103947_create_user_communications_table',1),(1291,'2017_06_17_111354_create_patient_datas_table',1),(1292,'2017_06_17_111916_create_patient_face_sheets_table',1),(1293,'2017_06_17_134316_create_patient_social_statistics_table',1),(1294,'2017_06_17_134845_create_patient_employers_table',1),(1295,'2017_06_17_135505_create_patient_contacts_table',1),(1296,'2017_06_17_141531_create_patient_contact_links_table',1),(1297,'2017_06_17_142000_create_patient_privacy_contacts_table',1),(1298,'2017_06_17_142423_create_patient_contact_communications_table',1),(1299,'2017_06_17_192428_create_amendments_table',1),(1300,'2017_06_17_195953_create_amendment_histories_table',1),(1301,'2017_06_19_193813_create_facilities_table',1),(1302,'2017_06_19_202456_create_user_facility_links_table',1),(1303,'2017_06_20_162245_create_libreehr_calendar_categories_table',1),(1304,'2017_06_20_162307_create_libreehr_calendar_events_table',1),(1305,'2017_06_20_172045_create_form_encounters_table',1),(1306,'2017_06_20_172800_create_forms_table',1),(1307,'2017_06_20_195925_create_form_aftercare_plans_table',1),(1308,'2017_06_21_144239_create_form_ankleinjuries_table',1),(1309,'2017_06_21_173702_create_form_annotate_diagrams_table',1),(1310,'2017_06_22_011754_create_form_clinical_instructions_table',1),(1311,'2017_06_22_012807_create_form_dictations_table',1),(1312,'2017_06_22_014513_create_form_notes_table',1),(1313,'2017_06_22_020654_create_form_painmaps_table',1),(1314,'2017_06_22_172607_create_form_physical_exams_table',1),(1315,'2017_06_22_181230_create_form_physical_exam_diagnoses_table',1),(1316,'2017_06_22_184239_create_form_prior_auths_table',1),(1317,'2017_06_22_190845_create_form_soaps_table',1),(1318,'2017_06_23_162354_create_form_track_anything_types_table',1),(1319,'2017_06_23_173954_create_form_track_anythings_table',1),(1320,'2017_06_23_183725_create_form_track_anything_results_table',1),(1321,'2017_06_23_194614_create_form_transfer_summaries_table',1),(1322,'2017_06_24_032958_create_form_treatment_plans_table',1),(1323,'2017_06_24_043121_create_form_vitals_table',1),(1324,'2017_06_24_054151_create_form_misc_billing_options_table',1),(1325,'2017_06_24_123245_create_form_bronchitis_table',1),(1326,'2017_06_24_193504_create_form_reviewofs_table',1),(1327,'2017_06_25_065204_create_form_ros_table',1),(1328,'2017_06_27_170625_create_x12_partners_table',1),(1329,'2017_06_28_162853_create_insurance_companies_table',1),(1330,'2017_06_28_165512_create_insurance_numbers_table',1),(1331,'2017_06_28_181202_create_pnotes_table',1),(1332,'2017_06_29_201827_create_dated_reminders_table',1),(1333,'2017_06_29_210431_create_dated_reminder_links_table',1),(1334,'2017_07_01_102005_create_user_residential_links_table',1),(1335,'2017_07_04_195129_create_background_services_table',1),(1336,'2017_07_05_161121_create_batchcoms_table',1),(1337,'2017_07_05_170249_create_automatic_notifications_table',1),(1338,'2017_07_07_165115_create_categories_table',1),(1339,'2017_07_09_143758_create_tf_tags_table',1),(1340,'2017_07_09_161214_create_tf_filters_table',1),(1341,'2017_07_09_171607_create_tf_patient_tags_table',1),(1342,'2017_07_09_191453_create_icd10_dx_order_codes_table',1),(1343,'2017_07_09_194848_create_icd10_gem_dx_10_9s_table',1),(1344,'2017_07_09_200852_create_icd10_gem_dx_9_10s_table',1),(1345,'2017_07_09_201254_create_icd10_gem_pcs_10_9s_table',1),(1346,'2017_07_09_201827_create_icd10_gem_pcs_9_10s_table',1),(1347,'2017_07_09_202332_create_icd10_pcs_order_codes_table',1),(1348,'2017_07_09_203018_create_icd10_reimbr_dx_9_10s_table',1),(1349,'2017_07_09_204819_create_icd10_reimbr_pcs_9_10s_table',1),(1350,'2017_07_15_143915_create_lists_table',1),(1351,'2017_07_15_180535_create_audit_masters_table',1),(1352,'2017_07_15_180547_create_audit_details_table',1),(1353,'2017_07_15_180557_create_documents_table',1),(1354,'2017_07_15_181604_create_categories_to_documents_table',1),(1355,'2017_07_15_183711_create_list_diagnoses_table',1),(1356,'2017_07_15_190446_create_chart_trackers_table',1),(1357,'2017_07_15_191158_create_office_notes_table',1),(1358,'2017_07_15_195729_create_extended_logs_table',1),(1359,'2017_07_16_052607_create_drugs_table',1),(1360,'2017_07_16_062312_create_drug_templates_table',1),(1361,'2017_07_16_070701_create_prices_table',1),(1362,'2017_07_17_150343_create_product_warehouses_table',1),(1363,'2017_07_17_164619_create_drug_inventories_table',1),(1364,'2017_07_18_144245_create_prescriptions_table',1),(1365,'2017_07_18_144254_create_drug_sales_table',1),(1366,'2017_07_20_193311_create_insurance_datas_table',1),(1367,'2017_07_20_214001_create_lang_languages_table',1),(1368,'2017_07_20_214027_create_lang_constants_table',1),(1369,'2017_07_20_214046_create_lang_definitions_table',1),(1370,'2017_07_20_214055_create_lang_customs_table',1),(1371,'2017_07_22_084303_create_history_datas_table',1),(1372,'2017_07_24_100105_create_globals_table',1),(1373,'2017_07_26_143101_create_immunizations_table',1),(1374,'2017_07_27_142520_create_referral_transactions_table',1),(1375,'2017_07_27_154256_create_transactions_table',1),(1376,'2017_07_31_154159_create_pharmacies_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `office_notes`
--

DROP TABLE IF EXISTS `office_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `office_notes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `user` int(10) unsigned NOT NULL COMMENT 'Foreign Key to users table.',
  `date` datetime NOT NULL COMMENT 'Timestamp when note is created.',
  `body` text COLLATE utf8mb4_unicode_ci COMMENT 'Note content',
  `activity` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is note active? 0 -> No | 1 -> Yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `office_notes_user_foreign` (`user`),
  CONSTRAINT `office_notes_user_foreign` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `office_notes`
--

LOCK TABLES `office_notes` WRITE;
/*!40000 ALTER TABLE `office_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `office_notes` ENABLE KEYS */;
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
  `home_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Contact home phone',
  `work_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Contact business phone',
  `contact_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Personal contact phone number.',
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
-- Table structure for table `pharmacies`
--

DROP TABLE IF EXISTS `pharmacies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pharmacies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `address_id` int(10) unsigned DEFAULT NULL COMMENT 'Foreign key to addresses table.',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Name of pharmacy.',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email ID of pharmacy.',
  `phone_number` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Phone Number of pharmacy.',
  `fax_number` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Fax number of pharmacy.',
  `transit_method` int(11) NOT NULL DEFAULT '1' COMMENT 'Method of Transit. 1 -> Print | 2 -> Email | 3 -> Fax',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pharmacies_email_unique` (`email`),
  KEY `pharmacies_address_id_foreign` (`address_id`),
  CONSTRAINT `pharmacies_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pharmacies`
--

LOCK TABLES `pharmacies` WRITE;
/*!40000 ALTER TABLE `pharmacies` DISABLE KEYS */;
/*!40000 ALTER TABLE `pharmacies` ENABLE KEYS */;
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
-- Table structure for table `prescriptions`
--

DROP TABLE IF EXISTS `prescriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prescriptions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prescriptions`
--

LOCK TABLES `prescriptions` WRITE;
/*!40000 ALTER TABLE `prescriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `prescriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prices`
--

DROP TABLE IF EXISTS `prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `pr_id` int(10) unsigned NOT NULL COMMENT 'Foreign key to drugs table.',
  `pr_selector` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Template selector for drugs, empty for codes',
  `pr_level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Price Level',
  `pr_price` decimal(10,2) NOT NULL COMMENT 'Price of that drug in local currency',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prices_pr_id_foreign` (`pr_id`),
  KEY `prices_pr_selector_foreign` (`pr_selector`),
  CONSTRAINT `prices_pr_id_foreign` FOREIGN KEY (`pr_id`) REFERENCES `drugs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `prices_pr_selector_foreign` FOREIGN KEY (`pr_selector`) REFERENCES `drug_templates` (`selector`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prices`
--

LOCK TABLES `prices` WRITE;
/*!40000 ALTER TABLE `prices` DISABLE KEYS */;
/*!40000 ALTER TABLE `prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_warehouses`
--

DROP TABLE IF EXISTS `product_warehouses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_warehouses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `drug_id` int(10) unsigned NOT NULL COMMENT 'Foreign key to Drugs table.',
  `pw_warehouse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Warehouse',
  `pw_min_level` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Min Level',
  `pw_max_level` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Max Level',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_warehouses_drug_id_foreign` (`drug_id`),
  CONSTRAINT `product_warehouses_drug_id_foreign` FOREIGN KEY (`drug_id`) REFERENCES `drugs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_warehouses`
--

LOCK TABLES `product_warehouses` WRITE;
/*!40000 ALTER TABLE `product_warehouses` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_warehouses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `referral_transactions`
--

DROP TABLE IF EXISTS `referral_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `referral_transactions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign key to patient_datas table',
  `user` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> False | 1 -> True',
  `refer_by` int(10) unsigned NOT NULL COMMENT 'User who referred this issue. Foreign key to users table.',
  `refer_to` int(10) unsigned NOT NULL COMMENT 'User to whom it is referred. Foreign key to users table.',
  `referral_date` date NOT NULL COMMENT 'Date when referred',
  `external_referral` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is this referral external? 0 -> No | 1 -> Yes',
  `reason` text COLLATE utf8mb4_unicode_ci COMMENT 'Reason for this referral.',
  `risk_level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0 -> Low | 1 -> Medium | 2 -> High',
  `requested_service` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Diagnosis Codes',
  `referrer_diagnosis` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Referrar Diagnosis',
  `include_vitals` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> False | 1 -> True',
  `misc` json DEFAULT NULL COMMENT 'Store User created field in key -> value pair form.',
  `reply_date` date DEFAULT NULL COMMENT 'Reply Date.',
  `reply_from` int(10) unsigned DEFAULT NULL COMMENT 'User who replied? Foreign key to users table.',
  `prescription_id` int(10) unsigned DEFAULT NULL COMMENT 'Prescriptions. Foreign key to prescriptions table.',
  `document_id` int(10) unsigned DEFAULT NULL COMMENT 'Documents related ',
  `presumed_diagnosis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Presumed Diagnosis',
  `final_diagnosis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Final Diagnosis.',
  `findings` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Findings.',
  `services_provided` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Dignosis codes provided.',
  `recommendation` text COLLATE utf8mb4_unicode_ci COMMENT 'Recommendations',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `referral_transactions_pid_foreign` (`pid`),
  KEY `referral_transactions_user_foreign` (`user`),
  KEY `referral_transactions_refer_by_foreign` (`refer_by`),
  KEY `referral_transactions_refer_to_foreign` (`refer_to`),
  KEY `referral_transactions_reply_from_foreign` (`reply_from`),
  KEY `referral_transactions_document_id_foreign` (`document_id`),
  KEY `referral_transactions_prescription_id_foreign` (`prescription_id`),
  CONSTRAINT `referral_transactions_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `referral_transactions_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `referral_transactions_prescription_id_foreign` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `referral_transactions_refer_by_foreign` FOREIGN KEY (`refer_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `referral_transactions_refer_to_foreign` FOREIGN KEY (`refer_to`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `referral_transactions_reply_from_foreign` FOREIGN KEY (`reply_from`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `referral_transactions_user_foreign` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `referral_transactions`
--

LOCK TABLES `referral_transactions` WRITE;
/*!40000 ALTER TABLE `referral_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `referral_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tf_filters`
--

DROP TABLE IF EXISTS `tf_filters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tf_filters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Auto-increment',
  `created_by` int(10) unsigned NOT NULL COMMENT 'User who created this filter. Foreign key to users table.',
  `updated_by` int(10) unsigned NOT NULL COMMENT 'User who updated this filter. Foreign key to users table.',
  `requesting_action` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 -> Allow | 0 -> Deny',
  `requesting_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 -> Group | 0 -> User',
  `requesting_entity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Group name or username of the source',
  `object_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> Tag | 1 -> Patient. Filter object type',
  `note` text COLLATE utf8mb4_unicode_ci COMMENT 'Note about filter.',
  `gacl_aro` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Gacl Field',
  `gacl_acl` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Gacl Field',
  `effective_datetime` datetime NOT NULL COMMENT 'Timestamp when filter is active.',
  `expiration_datetime` datetime NOT NULL COMMENT 'Timestamp till filter is active.',
  `priority` int(10) unsigned NOT NULL COMMENT 'Priority of filter.',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is filter deleted. 0 -> No  | 1 -> Yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tf_filters_created_by_foreign` (`created_by`),
  KEY `tf_filters_updated_by_foreign` (`updated_by`),
  CONSTRAINT `tf_filters_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tf_filters_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tf_filters`
--

LOCK TABLES `tf_filters` WRITE;
/*!40000 ALTER TABLE `tf_filters` DISABLE KEYS */;
/*!40000 ALTER TABLE `tf_filters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tf_patient_tags`
--

DROP TABLE IF EXISTS `tf_patient_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tf_patient_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `created_by` int(10) unsigned NOT NULL COMMENT 'User who created this tag. Foreign key to users table.',
  `updated_by` int(10) unsigned NOT NULL COMMENT 'User who updated this tag. Foreign key to users table.',
  `tag_id` int(10) unsigned NOT NULL COMMENT 'Foreign key to tf_tags table.',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign key to patient_datas table.',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> In-active | 1 -> active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tf_patient_tags_created_by_foreign` (`created_by`),
  KEY `tf_patient_tags_updated_by_foreign` (`updated_by`),
  KEY `tf_patient_tags_tag_id_foreign` (`tag_id`),
  KEY `tf_patient_tags_pid_foreign` (`pid`),
  CONSTRAINT `tf_patient_tags_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tf_patient_tags_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `tf_patient_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tf_tags` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tf_patient_tags_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tf_patient_tags`
--

LOCK TABLES `tf_patient_tags` WRITE;
/*!40000 ALTER TABLE `tf_patient_tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tf_patient_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tf_tags`
--

DROP TABLE IF EXISTS `tf_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tf_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincremnet',
  `created_by` int(10) unsigned NOT NULL COMMENT 'User who created this tag. Foreign key to users table.',
  `updated_by` int(10) unsigned NOT NULL COMMENT 'User who updated this tag. Foreign key to users table.',
  `tag_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Name of tag.',
  `tag_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Color used to denote this tag on layout.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tf_tags_created_by_foreign` (`created_by`),
  KEY `tf_tags_updated_by_foreign` (`updated_by`),
  KEY `tf_tags_tag_name_index` (`tag_name`),
  CONSTRAINT `tf_tags_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tf_tags_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tf_tags`
--

LOCK TABLES `tf_tags` WRITE;
/*!40000 ALTER TABLE `tf_tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tf_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement',
  `pid` int(10) unsigned NOT NULL COMMENT 'Foreign key to patient_datas table',
  `user` int(10) unsigned NOT NULL COMMENT 'Foreign key to users table.',
  `authorized` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 -> False | 1 -> True',
  `data` json NOT NULL COMMENT 'Data of other transactions.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_pid_foreign` (`pid`),
  KEY `transactions_user_foreign` (`user`),
  CONSTRAINT `transactions_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `patient_datas` (`pid`) ON DELETE CASCADE,
  CONSTRAINT `transactions_user_foreign` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
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
-- Table structure for table `user_residential_links`
--

DROP TABLE IF EXISTS `user_residential_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_residential_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key. Autoincrement.',
  `addressID` int(10) unsigned NOT NULL COMMENT 'To create link with addresses table.',
  `userID` int(10) unsigned NOT NULL COMMENT 'To create link with user_infos table.',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT 'What type of address is? 0 -> primary address | 1 -> alternate address.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_residential_links_addressid_foreign` (`addressID`),
  KEY `user_residential_links_userid_foreign` (`userID`),
  CONSTRAINT `user_residential_links_addressid_foreign` FOREIGN KEY (`addressID`) REFERENCES `addresses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_residential_links_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_residential_links`
--

LOCK TABLES `user_residential_links` WRITE;
/*!40000 ALTER TABLE `user_residential_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_residential_links` ENABLE KEYS */;
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

-- Dump completed on 2017-08-21 10:48:31
