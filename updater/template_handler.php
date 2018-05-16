<?php
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
    	$this->output = $this->header_data.$template_file_data.$this->footer_data;
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
