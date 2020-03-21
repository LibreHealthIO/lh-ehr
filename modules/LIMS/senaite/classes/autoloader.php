<?php

class Autoloader {


        /**
         * LoadClasses
         * Checks if a class with name $className exists in the class directory
         * If it does, include it, otherwise throw an exception
         *
         * @param string $className
         * @return void
         */
        public static function loadClasses($class) {
            // Retrieve the structure from the class name provided
            $structure = explode('\\', $class);
            $lastIndex = count($structure);

            // The class to include is at the last index of the structure
            $className = $structure[$lastIndex-1];
            $classDirectory = 'classes/';

            // Loop through the levels of the structure and add the 
            // directory names into the directory tree variable
            for($i = 2; $i < $lastIndex-1; $i++) {
                $classDirectory .= $structure[$i]."/";
            }
            
            // Check if file exists
            // if it does, include it, otherwise throw an exception
            if(file_exists($classDirectory . $className . '.php')) {
                require_once($classDirectory . $className . '.php');
            } else {
                throw new \Exception('Could not find class '. $class);
            }
        }
    }