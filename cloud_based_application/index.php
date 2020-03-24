<?php
include ('config/init.php');
loggedInRedirect();

if (empty($_POST) === false) {

    $requiredFields = array(
        'username',
        'password',
        'password_again',
        'fName',
        'lName',
        'DOB',
        'gender',
        'phone_number',
        'email',
        'address'
    );
    foreach ($_POST as $key => $value) {
        if (empty($value) && in_array($key, $requiredFields) === true) {
            $errors[] = 'All Fields are required please';
            break 1;
        }
    }

    if (empty($errors) === true) {

        if (memberExists($_POST['username']) === true) {
            $errors[] = 'Sorry, the username \'' . $_POST['username'] . '\' is already taken.';
        }

        if (preg_match("/\\s/", $_POST['username']) == true) {
            $errors[] = 'Your username must not contain any spaces.';
        }

        if (strlen($_POST['password']) < 8) {

            $errors[] = 'Your password must be at least 8 characters';
        }

        if ($_POST['password'] !== $_POST['password_again']) {

            $errors[] = 'Your passwords do not match';
        }
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {

            $errors[] = 'A valid email address is required';
        }
        if (emailExists($_POST['email']) === true) {

            $errors[] = 'Sorry, the email \'' . $_POST['email'] . '\' is already in use';
        }
        if (preg_match("/\\s/", $_POST['phone_number']) == true) {
            $errors[] = 'Your phone number must not contain any spaces.';
        }
        if (phone_exists($_POST['phone_number']) === true) {
            $errors[] = 'Sorry, the phone number \'' . $_POST['phone_number'] . '\' is already in use';
        }
    }
}

?>

<!DOCTYPE HTML>
<html>
<head>
<title>Home</title>
<meta name="viewport" content="width=device-width, initial-scale=1">	
			<?php include('config/css.php'); ?>
			<?php include('config/js.php'); ?>
	</head>
<body>
	<div id="wrap">
			<?php include('template/planNav.php') ?>;
	<?php

if (isset($_GET['success']) && empty($_GET['success'])) {

    echo 'You\'ve been registered successfully! Please check your email to activate your account.';
} else {

    if (empty($_POST) === false && empty($errors) === true) {

        $registerData = array(

            'username' => $_POST['username'],
            'password' => $_POST['password'],
            'email' => $_POST['email'],
            'emailCode' => md5($_POST['email'] + microtime()),
            'DOB' => $_POST['DOB'],
            'gender' => $_POST['gender'],
            'phone_number' => $_POST['phone_number'],
            'address' => $_POST['address'],
            'type' => $_POST['type'],
            'fName' => $_POST['fName'],
            'lName' => $_POST['lName']
        );

        registerMember($registerData);
        header('Location: index.php?success');
    } else if (empty($errors) === false) {

        echo outputErrors($errors);
    }
}

?>
				<div class="container">
			<div class="row">
				<div class="col-md-4">
					<div class="panel panel-success">
						<div class="panel-heading">
							<strong>Register</strong>
						</div>
						<!--- End panel heading -->
						<div class="panel-body">
							<form action="" method="post">
								<div class="form-group">
									<input type="text" class="form-control" name="username"
										autocomplete="off" placeholder="Enter Your Username*">
								</div>
								<div class="form-group">
									<input type="password" class="form-control" name="password"
										placeholder="Enter Your Password* (Min 8 Characters)">
								</div>
								<div class="form-group">
									<input type="password" class="form-control"
										name="password_again" placeholder="Enter Your Password Again">
								</div>
								<div class="form-group">
									<input type="text" class="form-control" name="fName"
										placeholder="Enter Your First Name*">
								</div>
								<div class="form-group">
									<input type="text" class="form-control" name="lName"
										placeholder="Enter Your Last Name*">
								</div>
								<div class="form-group">
									<input type="text" class="form-control" name="email"
										placeholder="Enter Your Active Email*">
								</div>
								<div class="form-group">
									<input type="date" class="form-control" name="DOB"
										placeholder="Date of Birth*">
								</div>
								<div class="form-group">
									<input type="number" class="form-control" name="phone_number"
										placeholder="Enter Your Phone Number">
								</div>
								<div class="form-group">
									<input type="text" class="form-control" name="address"
										placeholder="Enter Your Address">
								</div>
								<div class="form-group">
									<select class="form-control myselect" name="type"> <option class="form-control" value="0">Patient </option>
									<option class="form-control" value="1">Doctor
									</option>
									</select>
								</div>
								<div class="form-group" data-toggle="buttons">
									<label class="btn btn-link-outline active"> <input type="radio"
										class="form-control" name="gender">Male
									</label> <label class="btn btn-link-outline"> <input
										type="radio" class="form-control" name="gender">Female
									</label>
								</div>
								
								<input type="submit" class="btn btn-success" value="Register">
							</form>

						</div>
						<!--- End panel body -->
					</div>
					<!--- End panel-->
					<a href="login.php">
						<button type="button" class="btn btn-success btn-block">Login</button>
					</a>
				</div>
				<!--- End col for login and register-->
				<div class="col-md-8">
					<div class="panel panel-success">
						<div class="panel-heading">
							<strong>
								<center>
									<h2>Welcome to the Diabetes Management System</h2>
									<h3>Track and Monitor your Blood Glucose Level Readings</h3>
								</center>
							</strong>
						</div>
						<!--- End panel heading -->
						<div class="panel-body">
							<center>
								<div>
									<h3>
										<label>Track Blood Sugar Levels</label>
									</h3>
								</div>
								<div>
									<h3>
										<label>Email Consultation</label>
									</h3>
								</div>
								<div>
									<h3>
										<label>Share Readings</label>
									</h3>
								</div>
								<div>
									<h3>
										<label>Insulin Doses</label>
									</h3>
								</div>
								<div>
									<h3>
										<label>Trends</label>
									</h3>
								</div>
								
							</center>
						</div>
						<!--- End panel body -->
					</div>
					<!--- End panel-->
				</div>


			</div>
			<!--- End row-->
		</div>
		<!--- End container -->
	</div>
	<!--- End wrap -->
</body>
<footer>
		<?php include('template/footer.php')?>
	</footer>
</html>
