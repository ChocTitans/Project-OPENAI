<?php 
session_start();
if (!isset($_SESSION['loggedin']) || ($_SESSION['role'] !== 'admin')) {
    header('Location: ../login.php');
    exit();
}

?>

<?php 

include '../include/config.php';

$query = "SELECT * FROM users WHERE role = 'user'";
$result = mysqli_query($conn, $query);

$queryadmin = "SELECT * FROM users WHERE role = 'admin'";
$resultadmin = mysqli_query($conn, $queryadmin);

?>


<!DOCTYPE html>
<html lang="en"> 
<?php include './head.php' ?>

<body class="app">   	

<?php include './sideheader.php' ?>
    <div class="app-wrapper">
	    
	    <div class="app-content pt-3 p-md-3 p-lg-4">
		    <div class="container-xl">
			    
			    <div class="row g-3 mb-4 align-items-center justify-content-between">
				    <div class="col-auto">
			            <h1 class="app-page-title mb-0">Les utilisateurs</h1>
				    </div>
				    <div class="col-auto">
					     <div class="page-utilities">
						    <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
							    <div class="col-auto">
								    <form class="table-search-form row gx-1 align-items-center">
					                    <div class="col-auto">
					                        <input type="text" id="search-orders" name="searchorders" class="form-control search-orders" placeholder="Search">
					                    </div>
					                    <div class="col-auto">
					                        <button type="submit" class="btn app-btn-secondary">Search</button>
					                    </div>
					                </form>
					                
							    </div><!--//col-->
						    </div><!--//row-->
					    </div><!--//table-utilities-->
				    </div><!--//col-auto-->
			    </div><!--//row-->
			   
			    
			    <nav id="orders-table-tab" class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
				    <a class="flex-sm-fill text-sm-center nav-link active" id="orders-all-tab" data-bs-toggle="tab" href="#orders-all" role="tab" aria-controls="orders-all" aria-selected="true">Utilisateurs</a>
				    <a class="flex-sm-fill text-sm-center nav-link"  id="orders-pending-tab" data-bs-toggle="tab" href="#orders-pending" role="tab" aria-controls="orders-pending" aria-selected="false">Administrateurs</a>
				</nav>
				
				
				<div class="tab-content" id="orders-table-tab-content">
			        <div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
					    <div class="app-card app-card-orders-table shadow-sm mb-5">
						    <div class="app-card-body">
							    <div class="table-responsive">
							        <table class="table app-table-hover mb-0 text-left">
										<thead>
											<tr>
												<th class="cell">ID</th>
												<th class="cell">Email</th>
												<th class="cell">Nom / Prenom</th>
												<th class="cell">Date de Naissance</th>
												<th class="cell">Status</th>
												<th class="cell"></th>
											</tr>
										</thead>
										<tbody>
											<?php
											if (mysqli_num_rows($result) > 0) {
    											while ($row = mysqli_fetch_assoc($result)) {
											
													echo '<tr>';
													echo '<td class="cell">#' . $row['id'] . '</td>'; // User ID
													echo '<td class="cell"><span class="truncate">' . $row['email'] . '</span></td>'; // Email
													echo '<td class="cell">' . $row['first_name'] . ' ' . $row['last_name'] . '</td>'; // First name and last name
													echo '<td class="cell"><span>' . date('d M', strtotime($row['date_of_birth'])) . '</span><span class="note">' . date('g:i A', strtotime($row['date_of_birth'])) . '</span></td>'; // Assuming a date column exists
													echo '<td class="cell"><span class="badge bg-success">' . $row['role'] . '</span></td>'; // Role
													echo '<td class="cell"><a class="btn-sm app-btn-secondary" href="user_conversation.php?user_id=' . $row['id'] . '">Voir</a></td>';
													echo '</tr>';
											
											    }
											}?>
											
										</tbody>
									</table>
						        </div><!--//table-responsive-->
						       
						    </div><!--//app-card-body-->		
						</div><!--//app-card-->
						
			        </div><!--//tab-pane-->
			        
			        
			        <div class="tab-pane fade" id="orders-pending" role="tabpanel" aria-labelledby="orders-pending-tab">
					    <div class="app-card app-card-orders-table mb-5">
						    <div class="app-card-body">
							    <div class="table-responsive">
							        <table class="table mb-0 text-left">
										<thead>
											<tr>
												<th class="cell">ID</th>
												<th class="cell">Email</th>
												<th class="cell">Nom / Prenom</th>
												<th class="cell">Date de Naissance</th>
												<th class="cell">Status</th>
												<th class="cell"></th>
											</tr>
										</thead>
										<tbody>
										<?php
											if (mysqli_num_rows($resultadmin) > 0) {
    											while ($row = mysqli_fetch_assoc($resultadmin)) {
											
													echo '<tr>';
													echo '<td class="cell">#' . $row['id'] . '</td>'; // User ID
													echo '<td class="cell"><span class="truncate">' . $row['email'] . '</span></td>'; // Email
													echo '<td class="cell">' . $row['first_name'] . ' ' . $row['last_name'] . '</td>'; // First name and last name
													echo '<td class="cell"><span>' . date('d M', strtotime($row['date_of_birth'])) . '</span><span class="note">' . date('g:i A', strtotime($row['date_of_birth'])) . '</span></td>'; // Assuming a date column exists
													echo '<td class="cell"><span class="badge bg-warning">' . $row['role'] . '</span></td>'; // Role
													echo '<td class="cell"><a class="btn-sm app-btn-secondary" href="user_conversation.php?user_id=' . $row['id'] . '">Voir</a></td>';
													echo '</tr>';
											
											    }
											}?>
										</tbody>
									</table>
						        </div><!--//table-responsive-->
						    </div><!--//app-card-body-->		
						</div><!--//app-card-->
			        </div><!--//tab-pane-->
				</div><!--//tab-content-->
				
				
			    
		    </div><!--//container-fluid-->
	    </div><!--//app-content-->
<?php include './footer.php' ?>
	    
    </div><!--//app-wrapper-->    					

 
    <!-- Javascript -->          
    <script src="assets/plugins/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>  
    
    
    <!-- Page Specific JS -->
    <script src="assets/js/app.js"></script> 

</body>
</html> 

