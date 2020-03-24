<?php

	include 'config/init.php';
	loggedInRedirect();
	
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>Recovery Page</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">	
			<?php include('config/css.php'); ?>
			<?php include('config/js.php'); ?>
	</head>
	<body>
		<div id="wrap">
			<?php include('template/planNav.php') ?>;
			
				<?php 
					if(isset($_GET['success']) === true && empty($_GET['success']) === true){
						?>
						<p>Please check your email in order to recover your loging details.</p>
				<?php
					}else{
						$mode_allowed = array('username', 'password');
						if(isset($_GET['mode']) ===true && in_array($_GET['mode'], $mode_allowed) === true){
							if(isset($_POST['email']) === true && empty($_POST['email']) === false){
								
								if(emailExists($_POST['email']) ===true){
									
									recover($_GET['mode'], $_POST['email']);
									header('Location: recovery.php?success');
									exit();
								}else{
								
									echo '<p>Sorry, we could not find that email address! </p>';
								}
							}
						
					?>
				<div class="container">
					<div class="row"> 
					<div class="col-md-5 col-md-offset-3"> 
						<div class="panel panel-success">
							<div class="panel-heading">
								<strong>Recovery</strong>
							</div><!--- End panel heading -->
							<div class="panel-body">	
					
							<form action="" method="post">
								<div class="form-group">
									Please enter your email address: <br />
									<input type="text" class="form-control" name="email" placeholder="Please Enter Your Email"/>
								</div>
								<div class="form-group">
									<input type ="submit" class="btn btn-success" value="Recover">
								</div>
							</form>
						
					<?php
					
							}else{
								header('Location: index.php');
								exit();
							}
						}
					?>
					
							</div><!--- End panel body -->	
						</div>	<!--- End panel-->
					</div><!--- End Col-->
				</div><!--- End Row -->
			</div>
		</div><!--- End wrap -->
	</body>
	<footer>
		<?php include('template/footer.php')?>
	</footer>
</html>

