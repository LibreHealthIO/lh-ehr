<?php
/*
 * Copyright Medical Information Integration,LLC info@mi-squared.com
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * Rewrite and modifications by sjpadgett@gmail.com Padgetts Consulting 2016.
 *
 *
 * @file FormAnnotate.php
 *
 * @brief This file ontains the FormAnnotate class, used to model the data contents of a clickmap based form.
 */
/* include the class we are extending. */
require_once ($GLOBALS['fileroot'] . "/interface/forms/annotate_diagram/mapdiagram/AbstractAnnotateModel.php");

/**
 * @class FormAnnotate
 *
 * @brief This class extends the AbstractAnnotateModel class, to create a class for modelling the data in a pain form.
 */
class FormAnnotate extends AbstractAnnotateModel {

    /**
     * The database table to place form data in/read form data from.
     *
     * @var TABLE_NAME
     */
    static $TABLE_NAME = "form_annotate_diagram";

    /* Initializer. just alles parent's initializer. */
    function FormAnnotate($id="") {
    	parent::AbstractAnnotateModel(FormAnnotate::$TABLE_NAME, $id);
    }

    /**
     * @brief Return the Title of the form, Useful when calling addform().
     */
    public function getTitle() {
        return C_FormAnnotate::$FORM_TITLE;
    }

    /**
     * @brief Return the 'Code' of the form. Again, used when calling addform().
     */
    public function getCode() {
        return C_FormAnnotate::$FORM_CODE;
    }
}
?>