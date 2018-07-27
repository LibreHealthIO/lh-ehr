<?php
/**
 * Loader to use templates inside the updater
 *
 *
 * Copyright (C) 2018 Naveen Muthusamy <kmnaveen101@gmail.com>
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Naveen Muthusamy <kmnaveen101@gmail.com>
 * @link    http://librehealth.io
 */
class loader {
	private $template_directory = "templates/";
	private $extension = ".html";
	private $template_file_directory;
	private $data;
	private $output;

	public function set_template_file($template_file) 
	{
        $this->template_file_directory = $this->template_directory.$template_file.$this->extension;	  
    }

    public function assign($key, $value) {
       $this->data[$key] = $value;
    }

    public function output() {
    	$template_file_data = file_get_contents($this->template_file_directory);
    	$this->output = $template_file_data;
        if (count($this->data) != 0) 
        {
    	    foreach ($this->data as $key => $value) 
    	    {
                $this->output = str_replace("{".$key."}", "$value", $this->output);	
    	    }
            echo $this->output;
        }
    }
}

$loader = new loader;
?>
