<?php 
session_start();
if (!isset($_SESSION['loggedin']) || ($_SESSION['role'] !== 'admin')) {
    header('Location: ../login.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en"> 


<?php include './head.php';

include '../include/config.php';
$success = false;
$sucessmsg = false;
$successusermsg = false;
if (isset($_POST['change_model'])) {
    $selectedModel = $_POST['selected_model'];

    // Update the model name in the database
    $query = "UPDATE models SET model_name = '$selectedModel' WHERE id = 1"; // Assuming 1 is the ID of the model
    $result = mysqli_query($conn, $query);

    if ($result) {
        $success = true;
    }
	
}

$queryModel = "SELECT model_name FROM models WHERE id = 1"; // Assuming 1 is the ID of the model
$resultModel = mysqli_query($conn, $queryModel);

if ($resultModel && mysqli_num_rows($resultModel) > 0) {
    $row = mysqli_fetch_assoc($resultModel);
    $modelUtilise = $row['model_name'];
}

$querySystemMessage = "SELECT message FROM system_messages LIMIT 1"; // Assuming only one system message exists
$resultSystemMessage = mysqli_query($conn, $querySystemMessage);

if ($resultSystemMessage && mysqli_num_rows($resultSystemMessage) > 0) {
    $rowSystemMessage = mysqli_fetch_assoc($resultSystemMessage);
    $systemMessage = $rowSystemMessage['message'];
}

$queryUserMessage = "SELECT message FROM user_content LIMIT 1"; // Assuming only one system message exists
$resultUserMessage = mysqli_query($conn, $queryUserMessage);

if ($resultUserMessage && mysqli_num_rows($resultUserMessage) > 0) {
    $rowUserMessage = mysqli_fetch_assoc($resultUserMessage);
    $UserMessage = $rowUserMessage['message'];
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['updatedSystemMessage'])) {
        $updatedSystemMessage = $_POST['updatedSystemMessage'];
        $updatedSystemMessage = mysqli_real_escape_string($conn, $updatedSystemMessage);

        $query = "UPDATE system_messages SET message = '$updatedSystemMessage' WHERE id = 1"; 
        $result = mysqli_query($conn, $query);

		if ($result) {
			$sucessmsg = true;
			// Retrieve the updated value from the database
			$queryUpdatedSystemMessage = "SELECT message FROM system_messages WHERE id = 1"; // Modify this query accordingly
			$resultUpdatedSystemMessage = mysqli_query($conn, $queryUpdatedSystemMessage);
		
			if ($resultUpdatedSystemMessage && mysqli_num_rows($resultUpdatedSystemMessage) > 0) {
				$rowUpdatedSystemMessage = mysqli_fetch_assoc($resultUpdatedSystemMessage);
				$systemMessage = $rowUpdatedSystemMessage['message'];
			}
		}
		
    }
    if (isset($_POST['updatedUserMessage'])) {
		$updatedUserMessage = $_POST['updatedUserMessage'];
		$updatedUserMessage = mysqli_real_escape_string($conn, $updatedUserMessage);

		$query = "UPDATE user_content SET message = '$updatedUserMessage' WHERE id = 1"; 
		$result = mysqli_query($conn, $query);

		
		if ($result) {
			$successusermsg = true;
			// Retrieve the updated value from the database
			$queryUpdatedUserMessage = "SELECT message FROM user_content WHERE id = 1"; // Modify this query accordingly
			$resultUpdatedUserMessage = mysqli_query($conn, $queryUpdatedUserMessage);
		
			if ($resultUpdatedUserMessage && mysqli_num_rows($resultUpdatedUserMessage) > 0) {
				$rowUpdatedUserMessage = mysqli_fetch_assoc($resultUpdatedUserMessage);
				$UserMessage = $rowUpdatedUserMessage['message'];
			}
		}
	}
}
?>
<style>
    .custom-input {
        width: 360px;
    }
</style>

<body class="app">   	

<?php include './sideheader.php' ?>	

    
    <div class="app-wrapper">
	    
	    <div class="app-content pt-3 p-md-3 p-lg-4">
		    <div class="container-xl">
			    
			    <h1 class="app-page-title">GPT Models & Messages</h1>
                <div class="row gy-4">
				<div class="col-12 col-lg-6">
		                <div class="app-card app-card-account shadow-sm d-flex flex-column align-items-start">
						    <div class="app-card-header p-3 border-bottom-0">
						        <div class="row align-items-center gx-3">
							        <div class="col-auto">
								        <div class="app-icon-holder">
										    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" d="M10 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm6 5c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
										</svg>
									    </div><!--//icon-holder-->
						                
							        </div><!--//col-->
							        <div class="col-auto">
								        <h4 class="app-card-title">User Message</h4>
							        </div><!--//col-->
						        </div><!--//row-->
						    </div><!--//app-card-header-->
						    <div class="app-card-body px-4 w-100">
								<form id="updateSystemMessageForm" action="" method="post">
									<div class="item border-bottom py-3">
										<div class="row justify-content-between align-items-center">
											<div class="col-auto">
												<div class="item-data">
												<textarea disabled id="UserMessageInput" name="updatedUserMessage" class="form-control search-input custom-input" rows="10"><?php echo $UserMessage; ?></textarea>
												</div>
											</div><!--//col-->
											<div class="col text-end">
												<a id="editButton" class="btn-sm app-btn-secondary" href="#" onclick="enableEditUser()">Modifier</a>
											</div><!--//col-->
										</div><!--//row-->
									</div><!--//item-->
										<?php if ($successusermsg) { 
											echo '<p class="badge bg-success">Vous avez changé le message avec succès !</p>';
										}?>
									</div><!--//app-card-body-->
									<div class="app-card-footer p-4 mt-auto">
										<button type="submit" class="btn app-btn-secondary">Mise à jour</button>
									</div><!--//app-card-footer-->
								</form>	
							</div><!--//app-card-->
	                </div><!--//col-->
	                <div class="col-12 col-lg-6">
		                <div class="app-card app-card-account shadow-sm d-flex flex-column align-items-start">
						    <div class="app-card-header p-3 border-bottom-0">
						        <div class="row align-items-center gx-3">
							        <div class="col-auto">
								        <div class="app-icon-holder">
										    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-shield-check" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
											<path fill-rule="evenodd" d="M5.443 1.991a60.17 60.17 0 0 0-2.725.802.454.454 0 0 0-.315.366C1.87 7.056 3.1 9.9 4.567 11.773c.736.94 1.533 1.636 2.197 2.093.333.228.626.394.857.5.116.053.21.089.282.11A.73.73 0 0 0 8 14.5c.007-.001.038-.005.097-.023.072-.022.166-.058.282-.111.23-.106.525-.272.857-.5a10.197 10.197 0 0 0 2.197-2.093C12.9 9.9 14.13 7.056 13.597 3.159a.454.454 0 0 0-.315-.366c-.626-.2-1.682-.526-2.725-.802C9.491 1.71 8.51 1.5 8 1.5c-.51 0-1.49.21-2.557.491zm-.256-.966C6.23.749 7.337.5 8 .5c.662 0 1.77.249 2.813.525a61.09 61.09 0 0 1 2.772.815c.528.168.926.623 1.003 1.184.573 4.197-.756 7.307-2.367 9.365a11.191 11.191 0 0 1-2.418 2.3 6.942 6.942 0 0 1-1.007.586c-.27.124-.558.225-.796.225s-.526-.101-.796-.225a6.908 6.908 0 0 1-1.007-.586 11.192 11.192 0 0 1-2.417-2.3C2.167 10.331.839 7.221 1.412 3.024A1.454 1.454 0 0 1 2.415 1.84a61.11 61.11 0 0 1 2.772-.815z"/>
											<path fill-rule="evenodd" d="M10.854 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 8.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
											</svg>
									    </div><!--//icon-holder-->
						                
							        </div><!--//col-->
							        <div class="col-auto">
								        <h4 class="app-card-title">GPT Model</h4>
							        </div><!--//col-->
						        </div><!--//row-->
						    </div><!--//app-card-header-->
						    <div class="app-card-body px-4 w-100">
							    
							    <div class="item border-bottom py-3">
								<form method="post" action="">
									<div class="row justify-content-between align-items-center">
										<div class="col-auto">
											<div class="item-data">
												<select class="form-select w-auto" name="selected_model">
													<option value="gpt-3.5-turbo">GPT 3.5-turbo</option>
													<option value="gpt-4">GPT 4</option>
													<!-- Add more options if needed -->
												</select>
											</div>
										</div><!--//col-->
										<div class="col text-end">
											<button type="submit" class="btn-sm app-btn-secondary" name="change_model">Change</button>
										</div><!--//col-->
									</div><!--//row-->
								</form>

							    </div><!--//item-->
								<?php if ($success) { 
												echo '<p class="badge bg-success">Vous avez changé le model avec succès !</p>';
											}?>
						    </div><!--//app-card-body-->
						    
						    <div class="app-card-footer p-4 mt-auto">
								<a class="btn app-btn-secondary" href="#">Model utilisé : <?php echo $modelUtilise; ?></a>
						    </div><!--//app-card-footer-->
						   
						</div><!--//app-card-->
	                </div>
	                <div class="col-12 col-lg-6">
		                <div class="app-card app-card-account shadow-sm d-flex flex-column align-items-start">
						    <div class="app-card-header p-3 border-bottom-0">
						        <div class="row align-items-center gx-3">
							        <div class="col-auto">
								        <div class="app-icon-holder">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-left-text" viewBox="0 0 16 16">
										<path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
										<path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
										</svg>
									    </div><!--//icon-holder-->
						                
							        </div><!--//col-->
							        <div class="col-auto">
								        <h4 class="app-card-title">System message</h4>
							        </div><!--//col-->
						        </div><!--//row-->
						    </div><!--//app-card-header-->
						    <div class="app-card-body px-4 w-100">
								<form id="updateSystemMessageForm" action="" method="post">
									<div class="item border-bottom py-3">
										<div class="row justify-content-between align-items-center">
											<div class="col-auto">
												<div class="item-data">
												<textarea id="systemMessageInput" name="updatedSystemMessage" class="form-control search-input custom-input" rows="10"><?php echo $systemMessage; ?></textarea>
												</div>
											</div>
											<div class="col text-end">
												<a id="editButton" class="btn-sm app-btn-secondary" href="#" onclick="enableEdit()">Modifier</a>
											</div><!--//col-->
										</div><!--//row-->
									</div><!--//item-->
									<?php if ($sucessmsg) { 
										echo '<p class="badge bg-success">Vous avez changé le message avec succès !</p>';
									}?>
								</div><!--//app-card-body-->
								<div class="app-card-footer p-4 mt-auto">
									<button type="submit" class="btn app-btn-secondary">Mise à jour</button>
								</div><!--//app-card-footer-->
								</form>	
								</div><!--//app-card-footer-->	
							</div><!--//app-card-->
	                </div>
					
                </div><!--//row-->
			    
		    </div><!--//container-fluid-->
	    </div><!--//app-content-->
	    
		<?php include './footer.php' ?>

	    
    </div><!--//app-wrapper-->    					

 
    <!-- Javascript -->        
	
	<script>
    function enableEdit() {
        var systemMessageInput = document.getElementById('systemMessageInput');
        var updatedSystemMessage = document.getElementById('updatedSystemMessage');

        systemMessageInput.removeAttribute('disabled');
        systemMessageInput.focus();

        updatedSystemMessage.value = systemMessageInput.value;
    }
	function enableEditUser() {
        var UserMessageInput = document.getElementById('UserMessageInput');
        var updatedUserMessage = document.getElementById('updatedUserMessage');

        UserMessageInput.removeAttribute('disabled');
        UserMessageInput.focus();

        updatedUserMessage.value = UserMessageInput.value;
    }
</script>

    <script src="assets/plugins/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>  
    
    <!-- Page Specific JS -->
    <script src="assets/js/app.js"></script> 

</body>
</html> 

