<?php

	function memberCount(){
		
		return mysql_result(mysql_query("SELECT COUNT(`memberID`) FROM `members` WHERE `active` = 1"), 0);
		
	}
	
/*	function mailMember($subject, $body){
			
		$query = mysql_query("SELECT `email`, `fName` FROM `members` WHERE `allowEmail` = 1 AND `type` = 0");
		while(($row = mysql_fetch_assoc($query)) !== false){
			email($row['email'], $subject, "Hello " . $row['fName'] . ",\n\n" . $body . "\n\n- Diabetes Management System");
		}
	} */
	
	
	
	function changeProfileImage($memberID, $file_temp, $file_extn){
		
		$file_path = 'images/profile/' . substr(md5(time()), 0, 10) . '.' . $file_extn;
		move_uploaded_file($file_temp, $file_path);
		mysql_query("UPDATE `members` SET `profile` = '" . mysql_real_escape_string($file_path) .  "' WHERE  `memberID` = " . (int)$memberID);	
	}
	
	/*
	 * This function is used to activate the member's account.
	 */
	function activate($email, $emailCode) {
		
		$email 		=mysql_real_escape_string($email);
		$emailCode =mysql_real_escape_string($emailCode);
	
		if (mysql_result(mysql_query("SELECT COUNT(`memberID`) FROM `members` WHERE `email` = '$email' AND `emailCode` = '$emailCode' AND `active` = 0"), 0) == 1) {
			//Update user active status.
			mysql_query("UPDATE `members` SET `active` = 1 WHERE `email` = '$email'");
			return true;
		} else  {
			return false;
		}
	}
	
	function recover($mode, $email) {
		
		$mode 		 = sanitize($mode);
		$email 		 = sanitize($email);
	
		$memberData	 = memberData(memberIDFromEmail($email), 'memberID', 'fName', 'username');
	
		if ($mode == 'username') {
	
			email($email, 'Your username', "Hello " . $memberData['fName'] . ", \n\nYour username is: " . $memberData['username'] . "\n\n-Diabetes Management System");
		} else if ($mode == 'password') {
		
			$generatedPassword = substr(md5(rand(999, 999999)), 0, 8);
			changePassword($memberData['memberID'], $generatedPassword);
	
			updateMember($memberData['memberID'], array('passwordChange' => '1'));
			email($email, 'Your password recovery', "Hello " . $memberData['fName'] . ", \n\nYour new password is: " . $generatedPassword . "\n\n-Diabetes Management System");
		}
	}
	
	function memberIDFromEmail($email){
		
		$email = sanitize($email);
		return mysql_result(mysql_query("SELECT `memberID` FROM `members` WHERE `email` = '$email'"), 0, 'memberID');
	}
		
	
	/*
	 * The code below is for logging into the Diabetes Management System and displaying the Member's Data.
	 */
	function memberData($memberID){
		
		$data = array();
		$memberID = (int) $memberID;
		
		$func_num_args = func_num_args();
		$func_get_args = func_get_args();
		
		
		if($func_num_args > 1){
			
			unset($func_get_args[0]);
			
			$fields = '`' . implode('`, `', $func_get_args) . '`';
			$data = mysql_fetch_assoc(mysql_query("SELECT $fields FROM `members` WHERE `memberID` = $memberID"));
			
			 return $data;
		}
	
	}
	
	function loggedIn() {
		
			return (isset($_SESSION['memberID'])) ? true : false;
	}
	
	function memberExists($username) {
		
		$username = sanitize($username);
		return (mysql_result(mysql_query("SELECT COUNT(`memberID`) FROM `members` WHERE `username` = '$username'"), 0) == 1) ? true : false;
	}
	
	function phoneExists($phonenumber) {
	    
	    $phonenumber = sanitize($phonenumber);
	    return (mysql_result(mysql_query("SELECT COUNT(`memberID`) FROM `members` WHERE `phone_number` = '$phonenumber'"), 0) == 1) ? true : false;
	}
	
	function emailExists($email) {
		
		$email = sanitize($email);
		return (mysql_result(mysql_query("SELECT COUNT(`memberID`) FROM `members` WHERE `email` = '$email'"), 0) == 1) ? true : false;
	}
	
	function memberActive($username) {
		
		$username = sanitize($username);
		return (mysql_result(mysql_query("SELECT COUNT(`memberID`) FROM `members` WHERE `username` = '$username' AND `active` = 1"),0) == 1) ? true : false;
	}
	
	function memberidFromUsername($username) {
		
		$username = sanitize($username);
		return mysql_result(mysql_query("SELECT `memberID` FROM `members` WHERE `username` = '$username'"), 0, 'memberID');
	}
	
	/*
	 * This is for Logging into the Diabetes Management System.
	 */
	function login($username, $password){
		
		$memberID = memberidFromUsername($username);
		
		$username = sanitize($username);
		$password = md5($password);
		return (mysql_result(mysql_query("SELECT COUNT(`memberID`) FROM `members` WHERE `username` = '$username' AND `password` = '$password'"), 0) == 1) ? $memberID : false;
	
	}
	
	
	function loginRole($login){
		
		return mysql_fetch_assoc(mysql_query("SELECT * FROM `members` WHERE `memberID` = $login"));
	}
	
	/*
	 * The code below is for Registering users to the Diabetes Management System.
	 */
	function registerMember($registerData) {
		
		array_walk($registerData, 'arraySanitize');
		$registerData['password'] = md5($registerData['password']);

		$fields = '`' . implode('`, `', array_keys($registerData)) . '`';
		$data = '\'' . implode('\', \'', $registerData) . '\'';

		mysql_query("INSERT INTO `members` ($fields) VALUES ($data)");
		email($registerData['email'], 'Activate your account', "Hello " . $registerData['fName'] . ", \n\nYou need to activate your account, therefore use the link below:\n\nhttp://localhost/cloud_based_application/cloud_based_application/activate.php?email=" . $registerData['email'] . "&emailCode=" . $registerData['emailCode'] . "\n\n- Diabetes Management System");
	}
	
	/*
	 * Updating members' information in the database.
	 */
	function updateMember($memberID, $updateData) {
		
		$update = array();
		array_walk($updateData, 'arraySanitize');
	
		foreach ($updateData as $field=>$data){
			
			$update[] = '`' . $field . '` = \'' . $data . '\'';
		}
		mysql_query("UPDATE `members` SET " . implode(', ', $update) . " WHERE `memberID` = $memberID");
	}
	
	
	function changePassword($memberID, $password) {
		
		$memberID = (int) $memberID;
		$password = md5($password);

		mysql_query("UPDATE `members` SET `password` = '$password', `passwordChange` = 0 WHERE `memberID` = $memberID");
	}