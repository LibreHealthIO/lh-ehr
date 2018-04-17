<?php
/**
 *  Role class
 *
 * This class manages all the operations that are performed on the
 * roles.json file which contains the data of all the roles
 * 
 * Copyright (C) 2018 Anirudh Singh
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0 and the following
 * Healthcare Disclaimer
 *
 * In the United States, or any other jurisdictions where they may apply, the following additional disclaimer of
 * warranty and limitation of liability are hereby incorporated into the terms and conditions of MPL 2.0:
 *
 * No warranties of any kind whatsoever are made as to the results that You will obtain from relying upon the covered code
 *(or any information or content obtained by way of the covered code), including but not limited to compliance with privacy
 * laws or regulations or clinical care industry standards and protocols. Use of the covered code is not a substitute for a
 * health care provider’s standard practice or professional judgment. Any decision with regard to the appropriateness of treatment,
 * or the validity or reliability of information or content made available by the covered code, is the sole responsibility
 * of the health care provider. Consequently, it is incumbent upon each health care provider to verify all medical history
 * and treatment plans with each patient.
 *
 * Under no circumstances and under no legal theory, whether tort (including negligence), contract, or otherwise,
 * shall any Contributor, or anyone who distributes Covered Software as permitted by the license,
 * be liable to You for any indirect, special, incidental, consequential damages of any character including,
 * without limitation, damages for loss of goodwill, work stoppage, computer failure or malfunction,
 * or any and all other damages or losses, of any nature whatsoever (direct or otherwise)
 * on account of or associated with the use or inability to use the covered content (including, without limitation,
 * the use of information or content made available by the covered code, all documentation associated therewith,
 * and the failure of the covered code to comply with privacy laws and regulations or clinical care industry
 * standards and protocols), even if such party shall have been informed of the possibility of such damages.
 *
 * See the Mozilla Public License for more details.
 *
 * @package Librehealth EHR 
 * @author Anirudh (anirudh.s.c.96@hotmail.com)
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 *
 *
 */

class Role {


    public $file;


    /**
     * constructor
     * initializes the file handler for the roles json file
     * 
     * @param string $location
     */
    public function __construct($location) {
        $this->file = file_get_contents($location);
    }

    /**
     * createNewRole
     * 
     * Adds a new role entry in the JSON file with the given data
     * 
     * @param array $role
     * @return bool
     */
    public function createNewRole($role) {

        //var_dump($role);

    }

    /**
     * editRole
     *
     * Changes the data in the specified role to the given data
     * @param string $role
     * @param array $data
     * @return void
     */
    public function editRole($role, $data) {

        return true;
    }

    /**
     * getRole
     * 
     * Retrieves the data related to the given role
     * 
     * @param string $role
     * @return array
     */
    public function getRole($role) {


        return $role;
    }





    
}