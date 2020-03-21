
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
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($manufacturerDetails as $manufacturer) { ?>
              <tr>
                <td> # </td>
                <td> <?php echo $manufacturer->title; ?></td>
                <td> <?php echo $manufacturer->description; ?></td>
                <td> <?php if ($manufacturer->review_state == "active") echo "Active"; else echo "Dormant";  ?></td>              
              </tr>
           <?php } ?>
            </tbody>
          
          
          </table>
        
        
        <a href="index.php?action=resource&sact=createmanufacturer" class="btn btn-outline-success">Create new</a>
        </form>
      
      
      </div>
      
    </div>
  
  </div>