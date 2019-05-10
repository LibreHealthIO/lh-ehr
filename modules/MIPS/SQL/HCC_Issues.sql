DELETE from lists where type = 'HCC';


INSERT INTO lists  (`pid`, `title`) SELECT DISTINCT pid,numerator_label FROM 
report_itemized WHERE numerator_label LIKE 'HCC%';

UPDATE lists  SET `type` ='HCC' , `activity` ='1', `user`='1' WHERE title LIKE 'HCC%';

UPDATE lists SET `subtype` = `title`;
UPDATE lists SET `subtype` = REPLACE (`subtype`,'HCC_000','');
UPDATE lists SET `subtype` = REPLACE (`subtype`,'HCC_00','');
UPDATE lists SET `subtype` = REPLACE (`subtype`,'HCC_0','');

UPDATE lists SET `diagnosis` = (SELECT billing.code from billing 
INNER JOIN mips_hcc ON (billing.code = mips_hcc.code) 
WHERE billing.pid = lists.pid 
AND (billing.code = mips_hcc.code AND mips_hcc.type = lists.title)
GROUP BY billing.code LIMIT 1);

UPDATE lists SET `begdate` = (SELECT billing.date from billing 
INNER JOIN mips_hcc ON (billing.code = mips_hcc.code) 
WHERE billing.pid = lists.pid 
AND (billing.code = mips_hcc.code AND mips_hcc.type = lists.title)
GROUP BY billing.code LIMIT 1);

UPDATE billing SET billing.HCC = (SELECT mips_hcc.type FROM mips_hcc
WHERE billing.code !='' 
AND billing.code = mips_hcc.code LIMIT 1);

TRUNCATE issue_encounter;
INSERT INTO issue_encounter (encounter,pid,list_id) 
SELECT DISTINCT billing.encounter,lists.pid, lists.id  from billing
JOIN lists ON (billing.HCC = lists.title AND billing.pid = lists.pid)
WHERE billing.HCC !='';
  
UPDATE form_encounter SET facility = (Select facility.name from facility
                                      WHERE facility.id = form_encounter.facility_id);

UPDATE lists SET `comments` = (SELECT mips_hcc_rates.code from mips_hcc_rates  
WHERE mips_hcc_rates.type = lists.title 
GROUP BY mips_hcc_rates.type LIMIT 1);

UPDATE lists SET `extrainfo` = (SELECT mips_hcc_rates.rate from mips_hcc_rates  
WHERE mips_hcc_rates.type = lists.title 
GROUP BY mips_hcc_rates.type LIMIT 1);



CREATE TABLE IF NOT EXISTS aaa_lists SELECT * FROM lists;
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '12' AND `pid`  IN(SELECT pid from aaa_lists where `subtype` IN('8','9','10','11'));
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '11' AND `pid`  IN(SELECT pid from aaa_lists where `subtype` IN('8','9','10'));
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '10' AND `pid`  IN(SELECT pid from aaa_lists where `subtype` IN('8','9'));
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '9' AND `pid`   IN(SELECT pid from aaa_lists where `subtype`= '8');                                                                                     
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '19' AND `pid`  IN(SELECT pid from aaa_lists where `subtype` IN('17','18'));
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '18' AND `pid`  IN(SELECT pid from aaa_lists where `subtype`= '17');                                                                                  
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '80' AND `pid`  IN(SELECT pid from aaa_lists where `subtype` IN('27','166'));
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '29' AND `pid`  IN(SELECT pid from aaa_lists where `subtype` IN('27','28'));
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '28' AND `pid`  IN(SELECT pid from aaa_lists where `subtype`= '27');                                                                                    
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '48' AND `pid`  IN(SELECT pid from aaa_lists where `subtype`= '46');                                                                                  
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '55' AND `pid`  IN(SELECT pid from aaa_lists where `subtype`= '54');                                                                                    
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '58' AND `pid`  IN(SELECT pid from aaa_lists where `subtype`= '57');                                                                                    
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '169' AND `pid` IN(SELECT pid from aaa_lists where `subtype` IN('70','71','72'));
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '104' AND `pid` IN(SELECT pid from aaa_lists where `subtype` IN('70','71','103'));
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '103' AND `pid` IN(SELECT pid from aaa_lists where `subtype`= '70');
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '72' AND `pid`  IN(SELECT pid from aaa_lists where `subtype` IN('70','71'));
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '71' AND `pid`  IN(SELECT pid from aaa_lists where `subtype`= '70');                                                                                   
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '84' AND `pid`  IN(SELECT pid from aaa_lists where `subtype` IN('82','83'));
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '83' AND `pid`  IN(SELECT pid from aaa_lists where `subtype`= '82');                                                                                    
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '88' AND `pid`  IN(SELECT pid from aaa_lists where `subtype` IN('86','87'));
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '87' AND `pid`  IN(SELECT pid from aaa_lists where `subtype`= '86');                                                                                  
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '100' AND `pid` IN(SELECT pid from aaa_lists where `subtype`= '99');                                                                                   
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '87' AND `pid`  IN(SELECT pid from aaa_lists where `subtype`= '86');                                                                                  
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '189' AND `pid` IN(SELECT pid from aaa_lists where `subtype`= '106');                                                                                    
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '161' AND `pid` IN(SELECT pid from aaa_lists where `subtype` IN('106','157','158'));
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '158' AND `pid` IN(SELECT pid from aaa_lists where `subtype` IN('106','157'));                                                                                     
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '108' AND `pid` IN(SELECT pid from aaa_lists where `subtype` IN('106','107'));
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '107' AND `pid` IN(SELECT pid from aaa_lists where `subtype`= '106');                                                                                   
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '112' AND `pid` IN(SELECT pid from aaa_lists where `subtype` IN('110','111'));
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '111' AND `pid` IN(SELECT pid from aaa_lists where `subtype`= '110');                                                                                    
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '115' AND `pid` IN(SELECT pid from aaa_lists where `subtype`= '114');                                                                                    
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '137' AND `pid` IN(SELECT pid from aaa_lists where `subtype` IN('134','135','136'));
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '136' AND `pid` IN(SELECT pid from aaa_lists where `subtype` IN('134','135'));
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '135' AND `pid` IN(SELECT pid from aaa_lists where `subtype`= '134');                                                                                      
UPDATE lists SET `extrainfo` ='' WHERE `subtype` = '167' AND `pid` IN(SELECT pid from aaa_lists where `subtype`= '166');
DROP TABLE `aaa_lists`
   
UPDATE  patient_data p
        INNER JOIN
        (
            SELECT  pid, SUM(extrainfo) totalraf
            FROM    lists         
            GROUP   BY pid
        ) l ON p.pid = l.pid
SET     p.billing_note = l.totalraf; 


UPDATE patient_data SET billing_note=SUBSTRING(billing_note, 1, 5);