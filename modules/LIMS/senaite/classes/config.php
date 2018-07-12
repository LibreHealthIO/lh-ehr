<?php

// Config class, deals with intial setups including setting of authentication headers for
// API requests and dealing with other application-wide options
class Config {




  public function __construct() {
    
  }

  /**
   * getAuthenticationHeader
   * Provides LIMS authentication details from the database
   * Will change this down the line, authentication details shouldn't be kept in cleartext in the database
   * @return array
   */
  public function getAuthenticationHeaders() {
    $username = sqlFetchArray(sqlStatement("SELECT * FROM globals WHERE gl_name = 'lims_username'"))['gl_value'];
    $password = sqlFetchArray(sqlStatement("SELECT * from globals WHERE gl_name = 'lims_password'"))['gl_value'];
    return [ $username, $password ];
  }

  /**
   * getLimsURI
   * Retrieves the URL at which the LIMS resides
   *
   * @return void
   */
  public function getLimsURL() {
    $URI = sqlFetchArray(sqlStatement("SELECT * FROM globals WHERE gl_name = 'lims_url'"))['gl_value'];
    $URI .= '/senaite/@@API/senaite/v1/';
    return $URI;
  }






}