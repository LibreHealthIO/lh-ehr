<?php
/**
 * Copyright Medical Information Integration,LLC info@mi-squared.com
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * Rewrite and modifications by sjpadgett@gmail.com Padgetts Consulting 2016.
 *
 * @file C_FormAnnotate.class.php
 *
 * @brief This file contains the C_FormAnnotate class, used to control a clickmap bassed form.
 */

/* Include the class we're extending. */
require_once ($GLOBALS['fileroot'] . "/interface/forms/annotate_diagram/mapdiagram/C_AbstractAnnotate.php");

/* included so that we can instantiate FormAnnotate in createModel, to model the data contained in this form. */
require_once ("FormAnnotate.php");


/**
 * @class C_FormAnnotate
 *
 * @brief This class extends the C_AbstractAnnotate class, to create a form useful for modelling patient feet complaints.
 */
class C_FormAnnotate extends C_AbstractAnnotate {
    /**
     * The title of the form, used when calling addform().
     *
     * @var FORM_TITLE
     */
    static $FORM_TITLE = "Generic Graphic Diagrams";
    /**
     * The 'code' of the form, also used when calling addform().
     *
     * @var FORM_CODE
     */
    static $FORM_CODE = "annotate_diagram";

    /* initializer, just calls parent's initializer. */
    public function C_FormAnnotate() {
        parent::C_AbstractAnnotate();
    }

    /**
     * @brief Called by C_AbstractAnnotate's members to instantiate a Model object on demand.
     *
     * @param form_id
     *  optional id of a form in the EMR, to populate data from.
     */
    public function createModel($form_id = "") {
        if ( $form_id != "" ) {
            return new FormAnnotate($form_id);
        } else {
            return new FormAnnotate();
        }
    }

    /**
     * @brief return the path to the backing image relative to the webroot.
     */

    function getImage() {
        //really now just a default image
        return ($GLOBALS['webroot'] . "/interface/forms/" . C_FormAnnotate::$FORM_CODE ."/diagram/default.png");
    }

    /**
     * @brief return a n array containing the options for the dropdown box.
     */
    function getOptionList() {
        return array(  "0" => '',
                       "1" => xl("None"),
                       "2" => xl("Severity 1"),
                       "3" => xl("Severity 2"),
                       "4" => xl("Severity 3"),
                       "5" => xl("Severity 4"),
                       "6" => xl("Moderate"),
                       "7" => xl("Severity 6"),
                       "8" => xl("Severity 7"),
                       "9" => xl("Severity 8"),
                       "10" => xl("Severity 9"),
                       "11" => xl("Worst Possible"),
                       "apq2" => xl("Sharp"),
                       "apq3" => xl("Dull"),
                       "apq4" => xl("Stabbing"),
                       "apq5" => xl("Burning"),
                       "apq6" => xl("Constant"),
                       "apq7" => xl("Intermettent"),
                       "bpf2" => xl("Laceration"),
                       "bpf3" => xl("Hemotoma"),
                       "bpf4" => xl("Tenderness"),
                       "bpf5" => xl("Ecchymosis"),
                       "bpf6" => xl("Deformity"),
                       "bpf7" => xl("Swelling"),
                       "bpf8" => xl("Contusion"),
                       "bpf9" => xl("Abrasion"),
                       "bpf10" => xl("Muscle spasm"),
                       "e1" => xl("Corneal Abrasion"),
                       "e2" => xl("Corneal Ulceration"),
                       "e3" => xl("Foreign Body"),
                       "e4" => xl("Punctate Lesions"),
                       "e5" => xl("Fluorescein Uptate"),
                       "e6" => xl("Subconjuntival Hemporrhage"),
                       "f1" => xl("Redness"),
                       "f2" => xl("Normal Overall"),
                       "f3" => xl("Callous"),
                       "f4" => xl("Pre Ulcer"),
                       "f5" => xl("Ulcer"),
                       "f6" => xl("Maceration"),
                       "f7" => xl("Dryness"),
                       "f8" => xl("Tinea"),
                       "f9" => xl("Can feel the 5.07 filament"),
                       "f10" => xl("Can't feel the 5.07 filament"),
                       "f11" => xl("Odor"));
    }

    /**
     * @brief return a label for the dropdown boxes on the form, as a string.
     */
    function getOptionsLabel() {
        return xl("Observations List");
    }
}
?>