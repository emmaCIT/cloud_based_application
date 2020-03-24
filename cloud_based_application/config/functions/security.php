<?php

	function sanitize($data){
		
		return htmlentities(strip_tags(mysql_real_escape_string($data)));
	}
	
	function email($to, $subject, $body) {
		
		mail($to, $subject, $body, 'From: health@diabetesmanagement.com');
	}
	
	function securePage(){
		
		if(loggedIn() === false ){
			header('Location: secured.php');
			exit();
		}
	}
	
	function secureAdmin(){
	    global $memberData;
	    if($memberData['type'] == 0){
	        header('Location: patient.php');
	    }else if($memberData['type'] == 1){
	        header('Location: doctor.php');
	    }else {
	        header('Location: index.php');
	    }
	    exit();
	}
	
	function loggedInRedirect(){
		
		if(loggedIn() === true){
			header('Location: patient.php');
			exit();
		}
	}
	
	function arraySanitize($item){
		
		$item = htmlentities(strip_tags(mysql_real_escape_string($item)));
	}

	function outputErrors($errors){
	    $output = array();
	    foreach ($errors as $error) {
	        $output[] = '<li>' . $error . '</li>';
	    }
	    return '<ul>' . implode('', $output) . '</ul>';
	}

?>