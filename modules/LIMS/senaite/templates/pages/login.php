<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Senaite LIMS - Login</title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-bg">

  <div class="container h-100">
    <div class="d-flex h-100 justify-content-center align-items-center">
      <div class="card w-50">
        <img class="card-img-top img-logo" src="assets/img/logo.png" alt="Card image cap">
        <div class="card-body">
        <p class="display-4 login-title text-center"> Senaite LIMS </p>
        <hr />
        <form method="POST" act="">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" aria-describedby="usernameHelp" placeholder="Enter username" />
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
          </div>
          <button type="submit" name="submit" class="btn btn-primary btn-orange">Login</button>
          <hr />
          <?php if (isset($errors)) { ?> 
            <div class="alert alert-danger form-group">
              <ul>
                <?php foreach($errors as $error) { ?>
                  <li> <?php echo $error; ?></li>
                <?php } ?>
              </ul>
            </div>
          <?php } ?>
            
        </form>
        </div>
      </div>
    </div>
  </div>



  <script
    src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>
    <script
    src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
    <script src="assets/js/bootstrap.min.js"></script>
  </body>
</html>