<?php

if (isset($_GET['sact']) && ($_GET['sact'] != null )) {
  $sub = $_GET['sact'];
} else {
  $sub = 'clients';
}


switch($sub) {

  case 'createcontacts':
    $errors = [];
    $labClients = getDataFromUrl($client, 'client');
    if (isset($_POST['submit'])) {
      $required = ['contactFirstName', 'contactSurname'];

      if (!(checkRequiredFields($required, $_POST))) {
        $errors[] = 'Please fill in all the required fields';
      }

      if (count($errors) === 0) {
        try {
          $client->post('contact', [
            'json' => [
              'Salutation' => valueOrNull($_POST['contactTitle']),
              'Firstname' => $_POST['contactFirstName'],
              'Middleinitial' => valueOrNull($_POST['contactMiddleInitial']),
              'parent_uid' => $_POST['client'],
              'Middlename' => valueOrNull($_POST['contactMiddleName']),
              'Surname' => $_POST['contactSurname'], 
              'JobTitle' => valueOrNull($_POST['contactJobTitle']),
              'Department' => valueOrNull($_POST['contactDepartment']),
              'EmailAddress' => valueOrNull($_POST['contactEmailAddress']),
              'BusinessPhone' => valueOrNull($_POST['contactPhoneBusiness']),     
            ]
          ]);

          header('location: index.php?action=site&sact=setup');
        } catch (Exception $e) {
          die($e->getMessage());
        }
      }

    }

  break;
  case 'createclient':
    $errors = [];

    if (isset($_POST['submit'])) {
      $required = [ 'name', 'clientid'];

      // general required field check
      if (!(checkRequiredFields($required, $_POST))) {
        $errors[] = 'Please fill in all the required fields';
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
          $createdClient = $client->post('client', [
            'json' => [
              'Name' => $_POST['name'],
              'title' => $_POST['name'],
              'ClientID' => (int)$_POST['clientid'],
              'TaxNumber' => valueOrNull($_POST['vat']),
              'Phone' =>  valueOrNull($_POST['phone']),
              'Fax' =>  valueOrNull($_POST['fax']),
              'EmailAddress' =>  valueOrNull($_POST['email']),
              'AccountType' =>  valueOrNull($_POST['acctype']),
              'AccountName' =>  valueOrNull($_POST['accname']),
              'AccountNumber' =>  valueOrNull($_POST['accnum']),
              'BankName' =>  valueOrNull($_POST['bankname']),
              'PhysicalAddress' => json_encode([
                'city' =>  valueOrNull($_POST['phycity']),
                'district' =>  valueOrNull($_POST['phydistrict']),
                'state' =>  valueOrNull($_POST['phystate']),
                'address' =>  valueOrNull($_POST['phyaddress']),
                'zip' =>  valueOrNull($_POST['phypostal']),
                'country' =>  valueOrNull($_POST['phycountry']),
              ]),
              'PostalAddress' => json_encode([
                'city' =>  valueOrNull($_POST['postcity']),
                'district' =>  valueOrNull($_POST['postdistrict']),
                'state' =>  valueOrNull($_POST['poststate']),
                'address' =>  valueOrNull($_POST['postaddress']),
                'zip' =>  valueOrNull($_POST['postpostal']),
                'country' =>  valueOrNull($_POST['postcountry']),
              ]),
              'BillingAddress' => json_encode([
                'city' =>  valueOrNull($_POST['billcity']),
                'district' =>  valueOrNull($_POST['billdistrict']),
                'state' =>  valueOrNull($_POST['billstate']),
                'address' =>  valueOrNull($_POST['billaddress']),
                'zip' =>  valueOrNull($_POST['billpostal']),
                'country' =>  valueOrNull($_POST['billcountry']),
              ]),
 
              ],
            'form_params' => [
                'title' => $_POST['name']
              ]
            
          ]);
          header('location: index.php?action=client');
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