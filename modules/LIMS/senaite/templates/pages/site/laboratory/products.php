
  <div class="col-12">
    <div class="card">
      <div class="card-header"> Lab Departments </div>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>Volume</th>
              <th>Unit</th>
              <th>Price</th>
              <th>VAT amount</th>
              <th>Total Price</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($products as $product) {
              $productsDetails = json_decode($client->get($product->api_url)->getBody()->getContents())->items[0];
              $productsDetails->VATAmount = (($productsDetails->VAT/100) * $productsDetails->Price);
            ?> 
            <tr>
              <td>#</td>
              <td><?php echo $product->title; ?></td>
              <td><?php echo $productsDetails->Volume; ?></td>
              <td><?php echo $productsDetails->Unit; ?></td>
              <td><?php echo $productsDetails->Price; ?></td>
              <td><?php echo $productsDetails->VATAmount; ?></td>
              <td><?php echo ($productsDetails->Price + $productsDetails->VATAmount); ?></td>
              <td><?php if ($product->review_state === 'active') echo 'Active'; else echo 'Dormant'; ?></td>
            </tr>
          <?php } ?>
          
          </tbody>
        </table>
        <a href="index.php?action=laboratory&sact=createproducts" class="btn btn-outline-success">Create new</a>

      </div>
    </div>
  
  </div>