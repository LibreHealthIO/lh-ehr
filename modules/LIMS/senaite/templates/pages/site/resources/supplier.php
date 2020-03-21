
  <div class="col-12">

<div class="card">
  <div class="card-header">
    Manufacturers
  </div>
  <div class="card-body">

    <form action="">
      <table class="table">
        <thead>
          <tr>
            <th> # </th>
            <th>Name</th>
            <th>eMail</th>
            <th>Phone</th>
            <th>Fax</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($supplierDetails as $supplier) {
          $supplierData = json_decode($client->get($supplier->api_url)->getBody()->getContents())->items[0];
          ?>
          <tr>
            <td> # </td>
            <td> <?php echo $supplier->title; ?></td>
            <td> <?php echo $supplierData->EmailAddress; ?></td>
            <td> <?php echo $supplierData->Phone; ?> </td>
            <td> <?php echo $supplierData->Fax; ?> </td>
            <td> <?php if ($supplier->review_state == "active") echo "Active"; else echo "Dormant";  ?></td>              
          </tr>
       <?php } ?>
        </tbody>
      
      
      </table>
    
    
    <a href="index.php?action=resource&sact=createsupplier" class="btn btn-outline-success">Create new</a>
    </form>
  
  
  </div>
  
</div>

</div>