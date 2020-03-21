<?php

if (isset($_GET['sact']) && ($_GET['sact'] != null )) {
  $sub = $_GET['sact'];
} else {
  $sub = 'list';
}

switch ($sub) {

  case 'view':
    if (isset($_GET['id']) && ($_GET['id'] != null )) {
      $id = $_GET['id'];
    } else {
      header('location: index.php?action=procedure');
    }

    $procedure = sqlFetchArray(sqlStatement("SELECT * FROM procedure_order WHERE procedure_order_id = ?", [ $id ]));
    $procedureInformation = sqlFetchArray(sqlStatement("SELECT * FROM procedure_order WHERE procedure_order_id = ?", [ $id ]));
    if (!$procedure) {
      die('No procedure with the given ID was found');
    } else {

      $analysisRequests = getDataFromUrl($client, 'analysisrequest', true);
      
      if (isset($_POST['assignAR'])) {
        $arToAssign = $_POST['analysisrequest'];
        $status = 'processing';
        
        $assignAR = sqlStatement("INSERT INTO lims_analysisrequests(`procedure_order_id`, `analysisrequest_id`, `status`) VALUES(?, ?, ?)", [$id, $arToAssign, $status]);
        $updateProcedureOrder = sqlStatement("UPDATE procedure_order SET order_status = ? WHERE procedure_order_id = ?", [ 'assigned', $id]);
        if ($assignAR && $updateProcedureOrder) {
          header('location: index.php?action=procedure');
        }
      }

      if (isset($_POST['updateAR'])) {
        $arToAssign = $_POST['analysisrequest'];
        $updateAR = sqlStatement("UPDATE lims_analysisrequests SET analysisrequest_id = ? WHERE procedure_order_id = ?", [$arToAssign, $id]);

        if ($updateAR) {
          header('location: index.php?action=procedure');
        }
      }
      



    }

  break;

  default:
  case 'list':
  $sub = 'list';
  
  if (isset($_POST['submit'])) {
    $procedureId = $_POST['procedure_id'];
    $updateOrderStatus = sqlStatement("UPDATE procedure_order SET order_status='routed' WHERE procedure_order_id=?", [ $procedureId ]);
    if ($updateOrderStatus) {
      echo "<div class='alert alert-success'> Order accepted! </div>";
    }
  }
  $procedureQuery = sqlStatement("SELECT * FROM procedure_order");
  $procedures = [];
  while ($procedure = sqlFetchArray($procedureQuery)) {
    $procedures[] = $procedure;
  }


  break;
}



require_once('./templates/pages/site/procedures/'.$sub.'.php');
?>




