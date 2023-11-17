

<?php

if (!isset($_SESSION['loggedin']) || ($_SESSION['role'] !== 'admin')) {
    header('Location: ../login.php');
    exit();
}
// Assuming you have a connection to your database established
include '../include/config.php';
if(isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    
    // Fetch user details
    $userQuery = "SELECT * FROM users WHERE id = $userId";
    $userResult = mysqli_query($conn, $userQuery);
    $user = mysqli_fetch_assoc($userResult);
    
    // Fetch conversations/messages for the user
    $conversationQuery = "SELECT * FROM conversations WHERE user_id = $userId";
    $conversationResult = mysqli_query($conn, $conversationQuery);

    ?>
    
<!DOCTYPE html>
<html lang="en"> 
<?php include './head.php' ?>

<body class="app">   	

<?php include './sideheader.php' ?>
    <div class="app-wrapper">
	    
	    <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="col-auto">
			            <h1 class="app-page-title mb-0">Nom & Prenom : <?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h1>
				</div>
		    <div class="container-xl">
            <div class="col-auto">
					     <div class="page-utilities">
						    <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                                <div class="col-auto">
                                    <div class="col-auto">
                                        <a href="users.php" class="btn app-btn-secondary">Retour</a>
                                    </div>
                                    <br>
                                </div><!--//col-->
					    </div><!--//table-utilities-->
				    </div><!--//col-auto-->
				<div class="tab-content" id="orders-table-tab-content">
			        <div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
					    <div class="app-card app-card-orders-table shadow-sm mb-5">
						    <div class="app-card-body">
							    <div class="table-responsive">
							        <table class="table app-table-hover mb-0 text-left">
										<thead>
											<tr>
												<th class="cell">ID</th>
                                                <th class="cell">Q Utilisateurs</th>
												<th class="cell">Message de l'IA</th>
											</tr>
										</thead>
										<tbody>
										<?php
                                            while ($message  = mysqli_fetch_assoc($conversationResult)) {
                                        
                                                echo '<tr>';
                                                echo '<td class="cell">#' . $message['id'] . '</td>'; // User ID
                                                echo '<td class="cell">' . $message['user_input'] . '</td>';
                                                echo '<td class="cell">' . $message['chat_response'] . '</td>';

                                                echo '</tr>';
                                        
                                            }
										?>
											
										</tbody>
									</table>
						        </div><!--//table-responsive-->
						       
						    </div><!--//app-card-body-->		
						</div><!--//app-card-->
						
			        </div><!--//tab-pane-->
				</div><!--//tab-content-->
				
				
			    
		    </div><!--//container-fluid-->
	    </div><!--//app-content-->
<?php } ?>

<?php include './footer.php' ?>
	    
    </div><!--//app-wrapper-->    					

 
    <!-- Javascript -->          
    <script src="assets/plugins/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>  
    
    
    <!-- Page Specific JS -->
    <script src="assets/js/app.js"></script> 

</body>
</html> 
