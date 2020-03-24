<?php 
	include('config/init.php'); 
	loggedInRedirect();
	
	if (empty($_POST) === false) {
		
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		if (empty($username) === true || empty($password) === true) {
			
			$errors[] = 'You need to enter a username and password';
			
		}else if (memberExists($username) === false){
			
			$errors[] = 'We can\'t find that username. Have you registered?';
			
		}else if (memberActive($username) === false){
			
			$errors[] = 'You need to activate your account through your email account!';
			
		}else{
			
			if(strlen($password) > 32){
				$errors[] = 'Password is too long';
			}
			
			$login = login($username, $password);
			if ($login === false) {
				
				$errors[] = 'That username/password combination is incorrect';
			} else{
				
				$_SESSION['memberID'] = $login;
				$data = loginRole($_SESSION['memberID']);
				if ($data['type'] == 0) {
					header('Location: patient.php');
				} else if ($data['type'] == 1) {
					header('Location: doctor.php');
				}
				exit();	
			}
		}
	}
?>


<!DOCTYPE HTML>
<html>
	<head>
		<title>Login</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">	
			<?php include('config/css.php'); ?>
			<?php include('config/js.php'); ?>
	</head>
	<body>
		<div id="wrap">
			<?php include('template/planNav.php') ?>;
			
			<?php if(empty($errors) === false)
			echo outputErrors($errors); ?>
			<div class="container">
				<div class="row"> 
					<div class="col-md-4 col-md-offset-4"> 
						<div class="panel panel-success">
							<div class="panel-heading">
								<strong>Login</strong>
							</div><!--- End panel heading -->
							<div class="panel-body">	
								
								<form action="" method="post">
									<div class="form-group">
										<input type="text" class="form-control" name="username" autocomplete="off" placeholder="Enter Your Username">
									</div>
									<div class="form-group">
										<input type="password" class="form-control" name="password" placeholder="Enter Your Password">
									</div>
									
									<div class="checkbox">
										<label for="remember">
										<input type="checkbox" name="remember" id="remember"> Remember Me
										</label>
									</div>
									<div class="form-group">
										<input type="submit" class="btn btn-success" name="login" value="Login">
									</div>
									<div class"form-group">
										<label for="forgotten">
											Forgotten your <a href="recovery.php?mode=username">Username</a> or <a href="recovery.php?mode=password">Password</a>?
										</label>
									</div>
								</form>
							</div><!--- End panel body -->	
						</div>	<!--- End panel-->
						<a href="register.php">
							<button type="button" class="btn btn-success btn-block">Register</button>
						</a>
					</div><!--- End Col-->
				</div><!--- End Row -->
			</div><!--- End container -->
		</div><!--- End wrap -->
	</body>
	<footer>
		<?php include('template/footer.php')?>
	</footer>
</html>