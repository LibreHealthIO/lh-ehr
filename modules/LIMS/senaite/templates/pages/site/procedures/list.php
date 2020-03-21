<div class="col-12">


  <div class="card">
    <div class="card-header">Procedure Request List</div>
    <div class="card-body">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Procedure Name</th>
            <th>Procedure Priority</th>
            <th>Diagnosis Codes</th>
            <th>Order Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($procedures as $procedure) {
            $procedureCodeDetails = sqlStatement("SELECT * FROM procedure_order_code WHERE procedure_order_id = ?", [ $procedure['procedure_order_id'] ]);
            $procedureCodeDetails = sqlFetchArray($procedureCodeDetails);
            ?>
          <tr>
            <td><?php echo $procedure['procedure_order_id']; ?></td>
            <td><a href="index.php?action=procedure&sact=view&id=<?php echo $procedure['procedure_order_id']; ?>"><?php echo $procedureCodeDetails['procedure_name']; ?> </a></td>
            <td><?php echo $procedure['order_priority']; ?></td>
            <td><?php echo $procedureCodeDetails['diagnoses']; ?></td>
            <td><?php echo $procedure['order_status']; ?></td>
            <?php if ($procedure['order_status'] === 'pending') {
              ?> <td>
              <form method="POST" action="">
                <input type="hidden" name="procedure_id" value="<?php echo $procedure['procedure_order_id']; ?>" />
                <button type="submit" name="submit" class="btn btn-success"> Accept Procedure Request </button>
              </form>
              </td>
            <?php } ?>
          </tr>
          <?php } ?>
        </tbody>
      
      
      </table>
    </div>
  </div>



</div>