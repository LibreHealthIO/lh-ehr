DROP TABLE IF EXISTS aaa_billing;
CREATE TABLE aaa_billing 
SELECT * from billing 
WHERE pid IN (SELECT pid FROM `report_itemized` WHERE `report_id` =12 and `itemized_test_id` =8 and `numerator_label` LIKE 'Numerator') 
AND YEAR(billing.date) ='2018'
GROUP BY pid;

UPDATE aaa_billing set code = 'G9341', code_type = 'HCPCS';


INSERT INTO `billing`( `date`, `code_type`, `code`, `pid`, `provider_id`, `user`, `groupname`,
 `authorized`, `encounter`, `code_text`, `billed`, `activity`, `payer_id`, `bill_process`, `bill_date`,
  `process_date`, `process_file`, `modifier`, `units`, `fee`, `justify`, `target`, `x12_partner_id`, `ndc_info`,
   `notecodes`, `exclude_from_insurance_billing`, `external_id`) SELECT  `date`, `code_type`, `code`, `pid`, `provider_id`, `user`, `groupname`,
 `authorized`, `encounter`, `code_text`, `billed`, `activity`, `payer_id`, `bill_process`, `bill_date`,
  `process_date`, `process_file`, `modifier`, `units`, `fee`, `justify`, `target`, `x12_partner_id`, `ndc_info`,
   `notecodes`, `exclude_from_insurance_billing`, `external_id` FROM aaa_billing;
   
DROP TABLE aaa_billing;

/* Use to set Fail scores to "reported"*/
UPDATE `report_itemized` SET `pass`=9 WHERE `report_id` =13  AND `itemized_test_id` =2 AND `numerator_label` = 'Numerator' AND `pass` = 0;