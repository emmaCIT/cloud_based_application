<?php 
	include_once('config/init.php'); 
	securePage();
	
	
	
	if(empty($_POST) === false){
		
		$requiredFields = array('title', 'diaryDate', 'dairyNote');
		foreach ($_POST as $key=>$value) {
		
			if(empty($value) && in_array($key, $requiredFields) === true){
			
				$errors[] = 'All fields are required please';
				break 1;	
			}
		}
		
	}
	
	
	$sql = "SELECT * FROM `diarys` WHERE `memberID` = $sessionMemberID";
	$result = mysql_query($sql);
	if(!$result){
		
		$errors[] = 'Could not connect and show your data.';
	}else if(!mysql_num_rows($result)){
		
		$errors[] = 'There is no diary record in the database.';
	}
	
?>



<!DOCTYPE HTML>
<html>
	<head>
		<title>Profile</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">	
		<?php include('config/css.php'); ?>
		<?php include('config/js.php'); ?>
	</head>
	<body>
		<div id="wrap">
		<?php include('template/contentNav.php') ?>;
			<div class="container">
				<div class="row">
					<div class="col-md-3">
						<div class="panel panel-success">
							<div class="panel-heading">
								<strong>Member Profile</strong>
							</div><!--- End panel heading -->
							<div class="panel-body">
								<div class="thumbnail">
									<?php
										if(isset($_FILES['profile']) === true && isset($_POST['uploadP'])){
											if(empty($_FILES['profile']['name']) === true){
												echo 'please choose a file!';
											}else{
												
												$allowed = array('jpg', 'jpeg', 'gif', 'png');
												
												$file_name = $_FILES['profile']['name'];
												$bits = (explode('.', $file_name));
												$file_extn = strtolower(end($bits));
												$file_temp = $_FILES['profile']['tmp_name'];
												
												if(in_array($file_extn, $allowed) === true){
													
													changeProfileImage($sessionMemberID, $file_temp, $file_extn);
													header('Location: ' . $currentFile);
													exit();
												}else{
													
													echo 'Incorrect file type. Allowed file type: ';
													echo implode(', ', $allowed);
												}
											}
										}
									
      	                 				if(empty($memberData['profile']) === false){
											echo '<img src="', $memberData['profile'],  '" alt="', $memberData['fName'], '\'s Profile Image">';
      	                 				}
      	                 			?>
      	                 			
      	                 			 <div>
        								<h5>Profile Picture</h5>
        									<form action="" method="post" enctype="multipart/form-data">
        										<input type="file" name="profile" value="Choose Picture"> <br/>
        										<input type="submit" class="btn btn-success" role="button" name="uploadP" value"Upload">
        									</form>
      								</div>
      	                 		</div>
							</div><!--- End panel body -->
							<panel class="panel panel-default">
								<div class="panel-body">
									<form role="form">
									  <div class="form-group">
									    <label for="email">fName: <?php echo $memberData['fName']; ?></label>
									  </div>
									  <div class="form-group">
									    <label for="pwd">lName: <?php echo $memberData['lName']; ?></label>
									  </div>
									  <div class="form-group">
									    <label for="pwd">email: <?php echo $memberData['email']; ?></label>
									  </div>
									</form>
								</div>
							</panel>
						</div>	<!--- End panel-->
						
						
					</div><!--- End Col-->
					<div class="col-md-5">
						<div class="row">
					            <div class="col-md-12">	
					            	<div class="panel panel-success">
					            		<div class="panel-heading">
					            			<strong>Search for Diary</strong>
					            		</div>
					            		<div class="panel-body"> 
										 <form action="" method="post">
											 <div class="input-group">
											   <input type="text" name="search" class="form-control" autocomplete="off" placeholder="Search for Diary" onkeydown="searchq()";>
											    <span class="input-group-btn">
											   		<button type="submit" class="btn btn-success">Submit</button>
											   </span>
											 </div>
										</form>
										<div id="output"></div>
										</div><!--- End panel body-->
									</div><!--- End panel-->
					            </div><!--- End inner col-->
					        </div><!--- End inner row-->
					        <div class="row">
					            <div class="col-md-12">	
								<div class="panel panel-success">
									<div class="panel-heading">
										<strong>Daily Diary</strong>
									</div><!--- End panel heading -->
									<div class="panel-body">
										<?php
											if(isset($_POST['saveDiary']) && empty($errors) === true){
												
													$diaryData = array(
															'memberID' 	 	=> $sessionMemberID,
															'title'	 		=> $_POST['title'],
															'diaryDate'		=> $_POST['diaryDate'],
															'diaryNote'	 	=> $_POST['diaryNote']
													);
													
													insertDiary($sessionMemberID, $diaryData);
													
												}else if (empty($errors) === false){
													echo outputErrors($errors);
												}
												
										?>
										
										<form action="" method="post">
											
											<div class="form-group">
												<input type="text" class="form-control" name='title' placeholder="Enter Diary Title" oncliked=show();>
											</div>
											<div class="form-group">
												<input type="date" class="form-control" name='diaryDate' >
											</div>
											
											<div class="form-group">
												<textarea class="form-control" rows="10"  name="diaryNote" placeholder="Enter Daily Diary Note"></textarea>
											</div>
		
											<input type="submit" class="btn btn-success" name="saveDiary" value="save">
							
										</form>
									</div><!--- End panel body -->		
								</div>	<!--- End panel-->
						 	</div><!--- End inner col-->
					    </div><!--- End inner row-->
					</div><!--- End Col-->
					<div class="col-md-4">
						<div class="panel panel-success">
							<div class="panel-heading">
								<strong>List of Diary</strong>
							</div><!--- End panel heading -->
							<?php 
								if (empty($errors) === false){
											echo outputErrors($errors);
									}
								?>
								<div class="table-responsive">
									<table class="table">
								        <thead>
								          <tr>
								            <th>Date</th>
								            <th>Diary Title</th>
								          </tr>
								        </thead>
								        <tbody>
								       	<?php 
								       		if(is_resource($result)){
									       		while($row = mysql_fetch_array($result)){
										            echo '<tr>';
										            echo '<td>'.$row['diaryDate'].'</td>';
										            echo '<td>'.$row['title'].'</td>';
										            echo '</tr>';
										        } 
											}
								          ?>
								        </tbody>
							      </table>
								</div><!--- End table div-->
						</div>	<!--- End panel-->
					</div><!--- End Col-->
				</div><!--- End Row -->
			</div><!--- End container -->	
		</div><!--- End wrap -->
	</body>
	<footer>
		<?php include('template/footer.php')?>
	</footer>	
</html>