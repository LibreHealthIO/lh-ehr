<?php
  require_once("../globals.php");
  require_once("$srcdir/acl.inc");
  require_once("$srcdir/formdata.inc.php");
  require_once("$srcdir/options.inc.php");
  require_once("$srcdir/htmlspecialchars.inc.php");
  require_once("$srcdir/headers.inc.php");

  $query = "SELECT id, size, docdate, storagemethod, url FROM documents";
  $query .= " LIMIT 500";
  $res = sqlStatement($query);
?>

<html>
<head>
<?php
  call_required_libraries(array("jquery-min-3-1-1","bootstrap","fancybox"));
  resolveFancyboxCompatibility();
?>

<title><?php echo xlt('Documents Validation'); ?></title>

<!-- style tag moved into proper CSS file -->

</head>

<body class="body_top">
  <center>
    <h2><?php echo xlt('Documents Validation'); ?></h2>
  </center>
  <hr>

  <!-- using addressbook_list styles-->
  <div id="addressbook_list">
  <table class="table">
    <tr class='head'>
      <td><?php echo xlt('Location'); ?></td>
      <td><?php echo xlt('Size'); ?></td>
      <td><?php echo xlt('Date added'); ?></td>
      <td><?php echo xlt('Storage'); ?></td>
    </tr>

  <?php
    $listEmpty = true;
    while ($row = sqlFetchArray($res)) {
      $listEmpty = false;
      $fileURL = $row['url'];
      $location = substr($fileURL, strpos($fileURL, "LibreEHR"));
      $size = ((float)$row['size'])*(0.000001);
      if ($size < 1) {
        //if size < 1 MB
        $size = ($size*1000) . " KB";
      } else {
        $size = $size . " MB";
      }
      $dateAdded = $row['docdate'];
      $storage = null;
      if ($row['storagemethod'] == 0) {
        $check = file_exists($fileURL);
        $storage = $check ? "On Hard disk" : "Doesn't exist";
      } else {
        //$row['storagemethod'] == 1
        $storage = "On CouchDB";
      }

      echo "<tr>\n";
      echo "  <td>" . text($location) . "</td>\n";
      echo "  <td>" . text($size) . "</td>\n";
      echo "  <td>" . text($dateAdded) . "</td>\n";
      echo "  <td>" . text($storage) . "</td>\n";
      echo "</tr>\n";
    }
  ?>
  </table>

  <center>
    <h3>
      <?php
        if ($listEmpty) {
          echo "No documents found in the database";
        }
      ?>
    </h3>
  </center>
  <hr>
  </div>

  <!--Adding Jquery Plugins-->

  <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/common.js"></script>
  <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-ui.js"></script>
</body>
</html>
