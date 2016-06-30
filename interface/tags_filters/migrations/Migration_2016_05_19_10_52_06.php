<?php

use \Framework\Plugin\Migration\Migration;

class Migration_2016_05_19_10_52_06 extends Migration
{
    public function up()
    {
$directive = <<<MIGRATION
--
--  Comment Meta Language Constructs:
--
--  #IfNotTable
--    argument: table_name
--    behavior: if the table_name does not exist,  the block will be executed

--  #IfTable
--    argument: table_name
--    behavior: if the table_name does exist, the block will be executed

--  #IfMissingColumn
--    arguments: table_name colname
--    behavior:  if the colname in the table_name table does not exist,  the block will be executed

--  #IfNotColumnType
--    arguments: table_name colname value
--    behavior:  If the table table_name does not have a column colname with a data type equal to value, then the block will be executed

--  #IfNotRow
--    arguments: table_name colname value
--    behavior:  If the table table_name does not have a row where colname = value, the block will be executed.

--  #IfNotRow2D
--    arguments: table_name colname value colname2 value2
--    behavior:  If the table table_name does not have a row where colname = value AND colname2 = value2, the block will be executed.

--  #IfNotRow3D
--    arguments: table_name colname value colname2 value2 colname3 value3
--    behavior:  If the table table_name does not have a row where colname = value AND colname2 = value2 AND colname3 = value3, the block will be executed.

--  #IfNotRow4D
--    arguments: table_name colname value colname2 value2 colname3 value3 colname4 value4
--    behavior:  If the table table_name does not have a row where colname = value AND colname2 = value2 AND colname3 = value3 AND colname4 = value4, the block will be executed.

--  #IfNotRow2Dx2
--    desc:      This is a very specialized function to allow adding items to the list_options table to avoid both redundant option_id and title in each element.
--    arguments: table_name colname value colname2 value2 colname3 value3
--    behavior:  The block will be executed if both statements below are true:
--               1) The table table_name does not have a row where colname = value AND colname2 = value2.
--               2) The table table_name does not have a row where colname = value AND colname3 = value3.

--  #IfNotIndex
--    desc:      This function will allow adding of indexes/keys.
--    arguments: table_name colname
--    behavior:  If the index does not exist, it will be created

--  #EndIf
--    all blocks are terminated with and #EndIf statement.

#IfNotTable tf_filters

CREATE TABLE `tf_filters` (
  `id` bigint(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` varchar(255) NOT NULL,
  `requesting_action` varchar(255) NOT NULL COMMENT 'allow or deny',
  `requesting_type` varchar(255) NOT NULL COMMENT 'user or group',
  `requesting_entity` varchar(255) NOT NULL COMMENT 'the group name or username of the source',
  `object_type` varchar(255) NOT NULL COMMENT 'patient or tag',
  `object_entity` bigint(20) NOT NULL COMMENT 'tag id of tag',
  `note` text NOT NULL,
  `gacl_aro` int(11) NOT NULL COMMENT 'placeholder',
  `gacl_acl` int(11) NOT NULL COMMENT 'placeholder',
  `effective_datetime` datetime NOT NULL,
  `expiration_datetime` datetime NOT NULL,
  `priority` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
);

ALTER TABLE `tf_filters`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tf_filters`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

#EndIf

#IfNotTable tf_patients_tags

CREATE TABLE `tf_patients_tags` (
  `id` bigint(20) NOT NULL,
  `tag_id` bigint(20) NOT NULL,
  `pid` bigint(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` datetime NOT NULL,
  `status` varchar(255) NOT NULL
);

ALTER TABLE `tf_patients_tags`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tf_patients_tags`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

#EndIf

#IfNotTable tf_tags

CREATE TABLE `tf_tags` (
  `id` bigint(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` varchar(255) NOT NULL,
  `tag_name` varchar(255) NOT NULL,
  `tag_color` varchar(255) NOT NULL
);

ALTER TABLE `tf_tags`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tf_tags`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;


#EndIf

#IfNotRow list_options option_id ACLT_Tag_Colors
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`, `activity`) VALUES
('ACLT_Tag_Colors', 'blue', 'Blue', 0, 1, 0, '', '#90caf9', '', 0, 0, 0),
('ACLT_Tag_Colors', 'green', 'Green', 0, 0, 0, '', '#a5d6a7', '', 0, 0, 0),
('ACLT_Tag_Colors', 'orange', 'Orange', 0, 0, 0, '', '#ffb74d', '', 0, 0, 0),
('ACLT_Tag_Colors', 'purple', 'Purple', 0, 0, 0, '', '#b39ddb', '', 0, 0, 0),
('ACLT_Tag_Colors', 'red', 'Red', 0, 0, 0, '', '#e57373', '', 0, 0, 0),
('ACLT_Tag_Colors', 'yellow', 'Yellow', 0, 0, 0, '', '#fff59d', '', 0, 0, 0),
('ACLT_Tag_Status', 'active', 'Active', 0, 1, 0, '', '', '', 0, 0, 0),
('ACLT_Tag_Status', 'deleted', 'Deleted', 0, 0, 0, '', '', '', 0, 0, 0),
('ACLT_Tag_Status', 'suspended', 'Suspended', 0, 0, 0, '', '', '', 0, 0, 0),
('lists', 'ACLT_Tag_Colors', 'ACLT Tag Colors', 299, 1, 0, '', '', '', 0, 0, 1),
('lists', 'ACLT_Tag_Status', 'ACLT Tag Status', 298, 1, 0, '', '', '', 0, 0, 1);
#EndIf

MIGRATION;

        return $directive;
    }
    
    public function down()
    {
$directive = <<<MIGRATION
--
--  Comment Meta Language Constructs:
--
--  #IfNotTable
--    argument: table_name
--    behavior: if the table_name does not exist,  the block will be executed

--  #IfTable
--    argument: table_name
--    behavior: if the table_name does exist, the block will be executed

--  #IfMissingColumn
--    arguments: table_name colname
--    behavior:  if the colname in the table_name table does not exist,  the block will be executed

--  #IfNotColumnType
--    arguments: table_name colname value
--    behavior:  If the table table_name does not have a column colname with a data type equal to value, then the block will be executed

--  #IfNotRow
--    arguments: table_name colname value
--    behavior:  If the table table_name does not have a row where colname = value, the block will be executed.

--  #IfNotRow2D
--    arguments: table_name colname value colname2 value2
--    behavior:  If the table table_name does not have a row where colname = value AND colname2 = value2, the block will be executed.

--  #IfNotRow3D
--    arguments: table_name colname value colname2 value2 colname3 value3
--    behavior:  If the table table_name does not have a row where colname = value AND colname2 = value2 AND colname3 = value3, the block will be executed.

--  #IfNotRow4D
--    arguments: table_name colname value colname2 value2 colname3 value3 colname4 value4
--    behavior:  If the table table_name does not have a row where colname = value AND colname2 = value2 AND colname3 = value3 AND colname4 = value4, the block will be executed.

--  #IfNotRow2Dx2
--    desc:      This is a very specialized function to allow adding items to the list_options table to avoid both redundant option_id and title in each element.
--    arguments: table_name colname value colname2 value2 colname3 value3
--    behavior:  The block will be executed if both statements below are true:
--               1) The table table_name does not have a row where colname = value AND colname2 = value2.
--               2) The table table_name does not have a row where colname = value AND colname3 = value3.

--  #IfNotIndex
--    desc:      This function will allow adding of indexes/keys.
--    arguments: table_name colname
--    behavior:  If the index does not exist, it will be created

--  #EndIf
--    all blocks are terminated with and #EndIf statement.


#IfTable tf_filters

DROP TABLE `tf_filters`;

#EndIf

#IfTable tf_patients_tags

DROP TABLE `tf_patients_tags`;

#EndIf

#IfTable tf_tags

DROP TABLE `tf_tags`;

#EndIf
MIGRATION;

        return $directive;
    }
}



