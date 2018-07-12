<!DOCTYPE html>

<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Senaite LIMS - <?php echo strtoupper($action); ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css"
     integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <a href="index.php" class="navbar-brand display-4">Senaite LIMS</a>
      <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarContent">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <div class="dropdown dropleft">
              <a href="#" class="nav-link dropdown-toggle" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['lims_user']; ?></a>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a href="index.php" class="dropdown-item"><i class="fas fa-tachometer-alt fa-sm"></i> Dashboard</a>
                <a href="index.php?action=account" class="dropdown-item"><i class="fas fa-cogs fa-sm"></i> Manage Account</a>
                <a href="index.php?action=site&sact=setup" class="dropdown-item"><i class="fas fa-sitemap fa-sm"></i> Site Setup</a>
                <a href="index.php?action=logout" class="dropdown-item"><i class="fas fa-user-times fa-sm"></i> Logout</a>
                
              </div>
            </div>
          </li>

        </ul>
      </div>
    </nav>


    <div class="container">

    


    
    






   

