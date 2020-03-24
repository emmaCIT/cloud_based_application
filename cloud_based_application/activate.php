<?php
	include('config/init.php'); 
	loggedInRedirect();
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>Activate Page</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">	
			<?php include('config/css.php'); ?>
			<?php include('config/js.php'); ?>
	</head>
	<body>
		<div id="wrap">
			<?php include('template/planNav.php') ?>;
				<div class="container">
			<?php
					
				if(isset($_GET['success']) === true && empty($_GET['success']) === true) {
			?>
				<h2>Thank you, we've activated your account...</h2>
				<p>You're free to log in!</p>
				
			<?php
				} else if (isset($_GET['email'], $_GET['emailCode']) === true) {
					
					$email 		= trim($_GET['email']);
					$emailCode = trim($_GET['emailCode']);
	
					if (emailExists($email) === false) {
					
						$errors[] = 'Oops, something went wrong, and we couldn\'t find that email address!';
						
					}else if (activate($email, $emailCode) === false) {
						
						$errors[] = 'We had problems activating your account';
					}
	
					if (empty($errors) === false) {
			?>
					<h2>Oops...</h2>
			<?php 
						echo outputErrors($errors);
					} else {
				
						header('Location: activate.php?success');
						exit();
					}
		
				} else {
					header('Location: index.php');
					exit();
				}
				
			?>				
								
			</div>
		</div><!--- End wrap -->
	</body>
	
	<footer>
		<?php include('template/footer.php')?>
	</footer>
</html>





