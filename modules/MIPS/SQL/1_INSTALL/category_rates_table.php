<?php
/*
 * Copyright (C) 2018 Suncoast Connection
 *
 * @package MIPS_Gateway
 * @linkhttp://suncoastconnection.com
 * @author Suncoast Connection
 * @author Art Eaton
*/



$query =
"DROP TABLE IF EXISTS mips_hcc_rates;";
sqlStatementNoLog($query);

$query =
"CREATE TABLE IF NOT EXISTS `mips_hcc_rates` (
id int NOT NULL auto_increment,
type varchar(15),
code varchar(150),
rate varchar(15),
PRIMARY KEY(`id`)
);";
sqlStatementNoLog($query);

$query =
"INSERT INTO `mips_hcc_rates` (`type`, `code`,`rate`) VALUES 
('HCC_0001','HIV/AIDS','0.47'),
('HCC_0002','Septicemia, Sepsis, Systemic Inflammatory Response Syndrome/Shock','0.535'),
('HCC_0006','Opportunistic Infections','0.44'),
('HCC_0008','Metastatic Cancer and Acute Leukemia','2.484'),
('HCC_0009','Lung and Other Severe Cancers','0.973'),
('HCC_0010','Lymphoma and Other Cancers','0.672'),
('HCC_0011','Colorectal, Bladder, and Other Cancers','0.317'),
('HCC_0012','Breast, Prostate, and Other Cancers and Tumors','0.154'),
('HCC_0017','Diabetes with Acute Complications','0.368'),
('HCC_0018','Diabetes with Chronic Complications','0.368'),
('HCC_0019','Diabetes without Complication','0.118'),
('HCC_0021','Protein-Calorie Malnutrition','0.713'),
('HCC_0022','Morbid Obesity','0.365'),
('HCC_0023','Other Significant Endocrine and Metabolic Disorders','0.245'),
('HCC_0027','End-Stage Liver Disease','0.923'),
('HCC_0028','Cirrhosis of Liver','0.399'),
('HCC_0029','Chronic Hepatitis','0.251'),
('HCC_0033','Intestinal Obstruction/Perforation','0.31'),
('HCC_0034','Chronic Pancreatitis','0.286'),
('HCC_0035','Inflammatory Bowel Disease','0.302'),
('HCC_0039','Bone/Join/Muscle Infections/Necrosis','0.498'),
('HCC_0040','Rheumatoid Arthritis and Inflammatory Connective Tissue Disease','0.374'),
('HCC_0046','Severe Hematological Disorders','1.136'),
('HCC_0047','Disorders of Immunity','0.521'),
('HCC_0048','Coagulation Defects and Other Specified','0.252'),
('HCC_0054','Drug/Alcohol Psychosis','0.42'),
('HCC_0055','Drug/Alcohol Dependence','0.42'),
('HCC_0057','Schizophrenia','0.49'),
('HCC_0058','Major Depressive, Bipolar, and Paranoid Disorders','0.33'),
('HCC_0070','Quadriplegia ','1.234'),
('HCC_0071','Paraplegia','1.052'),
('HCC_0072','Spinal cord Disorders/ Injuries','0.509'),
('HCC_0073','Amyotrophic Lateral Sclerosis and Other Motor Neuron Disease','0.958'),
('HCC_0074','Cerebral Palsy','0.045'),
('HCC_0075','Myasthenia Gravis/Myoneural Disorders, Inflammatory','0.408'),
('HCC_0076','Muscular Dystrophy','0.565'),
('HCC_0077','Multiple Sclerosis ','0.556'),
('HCC_0078','Parkinsonâ€™s and Huntingtonâ€™s Diseases ','0.691'),
('HCC_0079','Seizure Disorders and Convulsions','0.284'),
('HCC_0080','Coma, Brain Compression/Anoxic','0.57'),
('HCC_0082','Respirator Dependence/Tracheostomy Status','1.52'),
('HCC_0083','Respiratory Arrest','0.802'),
('HCC_0084','Cardio-Respiratory Failure and Shock','0.329'),
('HCC_0085','Congestive Heart Failure','0.368'),
('HCC_0086','Acute Myocardial Infarction','0.275'),
('HCC_0087','Unstable Angina and Other Acute Ischemic Heart Disease ','0.258'),
('HCC_0088','Angina Pectoris','0.141'),
('HCC_0096','Specified Heart Arrhythmias','0.295'),
('HCC_0099','Cerebral Hemorrhage','0.339'),
('HCC_0100','Ischemic or Unspecified Stroke','0.317'),
('HCC_0103','Hemiplegia/Hemiparesis','0.581'),
('HCC_0104','Monoplegia, Other Paralytic Syndromes','0.396'),
('HCC_0106','Atherosclerosis of the Extremities with Ulceration or Gangrene','1.413'),
('HCC_0107','Vascular Disease with Complications','0.41'),
('HCC_0108','Vascular Disease','0.299'),
('HCC_0110','Cystic Fibrosis','0.417'),
('HCC_0111','Chronic Obstructive Pulmonary Disease','0.346'),
('HCC_0112','Fibrosis of Lung and Other Chronic Lung Disorders','0.274'),
('HCC_0114','Aspiration and Specified Bacterial Pneumonias','0.672'),
('HCC_0115','Pneumococcal Pneumonia, Empyema, Lung Abscess','0.2'),
('HCC_0122','Proliferative Diabetic Retinopathy and Vitreous Hemorrhage','0.203'),
('HCC_0124','Exudative Macular Degeneration','0.335'),
('HCC_0134','Dialysis Status','0.476'),
('HCC_0135','Acute Renal Failure Hematological Disorders','0.476'),
('HCC_0136','Chronic Kidney Disease, Stage 5','0.224'),
('HCC_0137','Chronic Kidney Disease, Severe (Stage 4)','0.224'),
('HCC_0157','Pressure Ulcer of Skin with Necrosis Through to Muscle, Tendon, or Bone','2.488'),
('HCC_0158','Pressure Ulcer of Skin with Full Thickness Skin Loss','1.338'),
('HCC_0161','Chronic Ulcer of Skin, Except Pressure','0.536'),
('HCC_0162','Severe Skin Burn or Condition','0.411'),
('HCC_0166','Severe Head Injury','0.57'),
('HCC_0167','Major Head Injury','0.163'),
('HCC_0169','Vertebral Fractures without Spinal Cord Injury','0.497'),
('HCC_0170','Hip Fracture/Dislocation and Toxic Neuropathy','0.446'),
('HCC_0173','Traumatic Amputations and Complications','0.265'),
('HCC_0176','Complications of Specified Implanted Device or Graft','0.566'),
('HCC_0186','Major Organ Transplant or Replacement Status','0.891'),
('HCC_0188','Artificial Openings for Feeding or Elimination','0.651'),
('HCC_0189','Amputation Status, Lower Limb/Amputation Complications','0.779');";
sqlStatementNoLog($query);
?>
