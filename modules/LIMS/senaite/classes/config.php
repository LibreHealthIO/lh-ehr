<?php

// Config class, deals with intial setups including setting of authentication headers for
// API requests and dealing with other application-wide options
class Config {



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