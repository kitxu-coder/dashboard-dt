<?php
		ob_start();
		session_start();
		require_once '..\config.php'; 

		//Check registered user
		$user_obj = new User();
		$result = $user_obj->isRegisteredUser();
		if(!$result){
			header('Location: ../index.php?error=1');
		}else{
      $email = $_SESSION['user_data']['email'];
    }

    // Display active tabs correctly 
    if(!empty($_GET['action'])){
        switch($_GET['action']){
          case 'parameters': { $tabParameters = 'active'; $tabAccount = ''; break;}
          case 'account': { $tabAccount = ''; $tabAccount = 'active'; break;}
          default: {$tabParameters = 'active'; $tabAccount = ''; break;}
        }
    }else{
      //Default
      $tabParameters = 'active';
      $tabAccount = '';
    }

    //Create Parameters object for read/update
    if( (!empty( $_POST ))&& (!empty($_GET['action'])) && ($_GET['action'] == 'parameters')){
      try {
        $parameter_obj = new Parameters();
        $result = $parameter_obj->setParameters( $_POST );
        if($result){
          $_SESSION['message'] = SUCCESS_UPDATED_PARAMETERS;
          $_SESSION['error_message'] = false;
        }
      } catch (Exception $e) {
        $error = $e->getMessage();
      }
    }

    //Check parameters of simulation
    $parameter_obj = new Parameters();
    list($frequency, $beltSpeed, $containerCapacity, $containerSpeed) = $parameter_obj->getParameters();

    //Update Account
    if( (!empty( $_POST ))&& (!empty($_GET['action'])) && ($_GET['action'] == 'account')){
      try {
        $account_obj = new User();
        $result = $account_obj->update($email, $_POST );
        // refresh data session email and password
        $_SESSION['user_data']['name'] = $_POST['username'];
        $_SESSION['user_data']['password'] =  $_POST['password'];
      } catch (Exception $e) {
        $error = $e->getMessage();
      }
    }

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TSE Prueba Técnica - Dashboard</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/dashboard/">

    <!-- Bootstrap core CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
	
	<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
	<meta name="theme-color" content="#7952b3">


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <!-- Custom styles for this template -->
    <link href="../css/dashboard.css" rel="stylesheet">
  </head>
  <body>
    
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Dashboard</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
 
  <ul class="navbar-nav px-3">
    <li class="nav-item text-nowrap">
      <a class="nav-link" href="../modules/logout.php">Sign out</a>
    </li>
  </ul>
</header>

<div class="container-fluid">
  <div class="row">
  <!-- Sidebar Menu -->
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link <?php echo $tabParameters; ?>" aria-current="page" href="#" id="simulation-tab" data-bs-toggle="tab" data-bs-target="#simulation-content">
              <span data-feather="home"></span>
              Simulation
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $tabAccount; ?>" href="#" id="account-tab" data-bs-toggle="tab" data-bs-target="#account-content">
              <span data-feather="file"></span>
              Account
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Digital Twin</h1>

          <!-- Div for messages -->
          <?php if( isset($_SESSION['message']) && $_SESSION['message']){ 
          if(isset($_SESSION['error_message'])){
              switch ($_SESSION['error_message']){
                  case true: $colordiv = 'danger';  break;
                  case false: $colordiv = 'success'; break;
              }
          } else {
              $colordiv = 'danger';
          }
          ?>
          <div id="message_div" class="alert alert-<?php echo $colordiv; ?> alert-dismissible fade show" role="alert">
              <?php echo $_SESSION['message']; ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          <script>
              $(document).ready(function(){
                  $('#message_div').delay(5000).slideUp();
              });
          </script>
        <?php $_SESSION['message'] = "";} ?>
        <!-- End Div for messages -->

	  </div>

	  <div class="tab-content">
      <!-- Parameters Panel  -->
			<div class="tab-pane <?php echo $tabParameters; ?>" id="simulation-content" role="tabpanel" aria-labelledby="simulation-tab">
          <form id="login-form" method="post" class="form-signin" role="form" action="<?php echo $_SERVER['PHP_SELF'] . '?action=parameters'; ?>">
              
            <div class="col-8">
              <label for="customRangeFrequency" class="form-label">Frequency new packages (packages per minute): </label> <output id="frequencyNow"><?php echo $frequency; ?></output>
              <input type="range" class="form-range" min="1" max="60" value="<?php echo $frequency; ?>" oninput="frequencyNow.value = this.value" id="customRangeFrequency" name="customRangeFrequency">

              <label for="customRangeBeltSpeed" class="form-label">Belt speed (m/s): </label> <output id="beltSpeedNow"><?php echo $beltSpeed; ?></output>
              <input type="range" class="form-range" min="1" max="8" value="<?php echo $beltSpeed; ?>" oninput="beltSpeedNow.value = this.value" id="customRangeBeltSpeed" name="customRangeBeltSpeed"> 

              <label for="customRangeContainerCapacity" class="form-label">Container capacity (in packages): </label> <output id="containerCapacityNow"><?php echo $containerCapacity; ?></output>
              <input type="range" class="form-range" min="5" max="40" value="<?php echo $containerCapacity; ?>" oninput="containerCapacityNow.value = this.value" id="customRangeContainerCapacity" name="customRangeContainerCapacity"> 

              <label for="customRangeContainerSpeed" class="form-label">Container Speed (m/s): </label> <output id="containerSpeedNow"><?php echo $containerSpeed; ?></output>
              <input type="range" class="form-range" min="1" max="6" value="<?php echo $containerSpeed; ?>" oninput="containerSpeedNow.value = this.value" id="customRangeContainerSpeed" name="customRangeContainerSpeed">
            </div>
            <div class="col-4">
              <button class="w-100 btn btn-lg btn-primary" type="submit">Update Parameters</button>
            </div>
          </form> 
			</div>
      <!-- Account Panel  -->
			<div class="tab-pane <?php echo $tabAccount; ?>" id="account-content" role="tabpanel" aria-labelledby="account-tab">
        
				<div class ="col-6">
					<form id="login-form" method="post" class="form-signin" role="form" action="<?php echo $_SERVER['PHP_SELF']. '?action=account'; ?>">
            <input class="form-control" type="text" placeholder="<?php echo $email; ?>" aria-label="readonly input example" readonly>
						<div class="form-floating">
							<input id="username" name="username" type="text" class="form-control" value="<?php echo $_SESSION['user_data']['name'] ?>"  placeholder="Username">
							<label for="floatingInput">Username</label>
						</div>
						<div class="form-floating">
							<input id="password" name="password" type="password" class="form-control"  placeholder="Password">
							<label for="floatingPassword">Password</label>
						</div>
						<div class="form-floating">
							<input id="passwordRepeat" name="passwordRepeat" type="password" class="form-control"  placeholder="Repeat Password">
							<label for="floatingPassword">Repeat Password</label>
						</div>
						<button class="w-100 btn btn-lg btn-primary" type="submit">Update account</button>
					</form>
				</div>
			</div>
	  	</div>
    </main>
  </div>
</div>

      <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="dashboard.js"></script>
  </body>
</html>


<?php
	ob_end_flush();
?>