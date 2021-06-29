<?php 
	ob_start();
	session_start();
    require_once '..\config.php'; 
	
	// Register user
	if(!empty($_POST)){
		try {
			$user_obj = new User();
			$data = $user_obj->register( $_POST );
			if($data){
                $message = REGISTER_OK;
            }
		} catch (Exception $e) {
			$error = $e->getMessage();
		}
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Test</title>
		<link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>


        <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
        <meta name="theme-color" content="#7952b3">

		<!-- Custom styles for this template -->
		<link href="../css/signin.css" rel="stylesheet">
		
	</head>
	<body class="text-center">
		<div class="container">

			<!-- Div for messages -->
			<?php 
				if( isset($_SESSION['message']) && $_SESSION['message']){ 
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

			<div class="row justify-content-md-center">
				<div class="col col-md-auto">
					<div class="card mb-1 rounded-3 shadow-sm"> 
						<main class="form-signin">
							<div class="card-header py-3">
								<img class="mb-4" src="../img/logo.png" alt="" width="72" height="72">
								<h1 class="h6 mb-3 fw-normal"><?php echo REGISTER_INFO;?></h1>
							</div>
							<div class="card-body">
								<form id="login-form" method="post" class="form-signin" role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div class="form-floating">
										<input id="email" name="email" type="email" class="form-control" placeholder="name@example.com">
										<label for="floatingInput">Email address</label>
									</div>
                                    <div class="form-floating">
										<input id="username" name="username" type="text" class="form-control"  placeholder="username">
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
									<div class="checkbox mb-3">
										<label>
											<a href="../index.php"> Sign-In </a>
										</label>
									</div>
									<button class="w-100 btn btn-lg btn-primary" type="submit">Register</button>
								</form>
							</div>
						</main>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

<?php
    ob_end_flush();
?>