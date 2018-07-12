<?php

if (isset($_GET['sact']) && ($_GET['sact'] != null )) {
  $sub = $_GET['sact'];
} else {
  $sub = 'clients';
}


switch($sub) {
  case 'createclient':
    $errors = [];

    if (isset($_POST['submit'])) {
      $required = [ 'name', 'clientid' ];

      // general required field check
      foreach ($required as $requiredField) {
        if (!isset($_POST[$requiredField]) || strlen($_POST[$requiredField]) === 0) {
          $errors[] = 'Please fill in the required fields';
          break;
        }
      }

      if (!is_numeric($_POST['clientid'])) {
        $errors[] = 'The client ID has to be numeric'; 
      }
      if (count($errors) === 0) {       
        /*
          To-do:
         * Physical, Postal and Billing addresses need to be consolidated into Schemas specific to the LIMS
         */

        try {
          $client->post('client', [
            'form_params' => [
              'Name' => $_POST['name'],
              'title' => $_POST['name'],
              'ClientID' => (int)$_POST['clientid'],
              'TaxNumber' => $_POST['vat'],
              'Phone' => $_POST['phone'],
              'Fax' => $_POST['fax'],
              'EmailAddress' => $_POST['email'],
              'AccountType' => $_POST['acctype'],
              'AccountName' => $_POST['accname'],
              'AccountNumber' => $_POST['accnumber'],
              'BankName' => $_POST['bankname'],
              'PhysicalAddress' => json_encode([
                'city' => $_POST['phycity'],
                'district' => $_POST['phydistrict'],
                'state' => $_POST['phystate'],
                'address' => $_POST['phyaddress'],
                'zip' => $_POST['phyzip'],
                'country' => $_POST['phycountry'],
              ]),
              'PostalAddress' => json_encode([
                'city' => $_POST['postcity'],
                'district' => $_POST['postdistrict'],
                'state' => $_POST['poststate'],
                'address' => $_POST['postaddress'],
                'zip' => $_POST['postzip'],
                'country' => $_POST['postcountry'],
              ]),
              'BillingAddress' => json_encode([
                'city' => $_POST['billcity'],
                'district' => $_POST['billdistrict'],
                'state' => $_POST['billstate'],
                'address' => $_POST['billaddress'],
                'zip' => $_POST['billzip'],
                'country' => $_POST['billcountry'],
              ]),
 
            ]
      
          ]);

        } catch(Exception $e) {
          die($e->getMessage());
        }
      }
    }
  break;

  case 'clients':
  default:
  
    $limsClients = json_decode($client->get('client')->getBody()->getContents())->items;


}




require_once('./templates/pages/site/clients/'.$sub.'.php');