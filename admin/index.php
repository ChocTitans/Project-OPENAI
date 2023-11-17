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

$query = "SELECT COUNT(*) as total_messages FROM conversations";
$result = mysqli_query($conn, $query);

$queryusers = "SELECT COUNT(*) as total_users FROM users";
$resultusers = mysqli_query($conn, $queryusers);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalMessages = $row['total_messages'];
}

if ($resultusers) {
    $row = mysqli_fetch_assoc($resultusers);
    $totalusers = $row['total_users'];
}

$queryModel = "SELECT model_name FROM models WHERE id = 1"; // Assuming 1 is the ID of the model
$resultModel = mysqli_query($conn, $queryModel);

if ($resultModel && mysqli_num_rows($resultModel) > 0) {
    $row = mysqli_fetch_assoc($resultModel);
    $modelUtilise = $row['model_name'];
}

?>

<body class="app">   	

<?php include './sideheader.php' ?>

    <div class="app-wrapper">
	    
	    <div class="app-content pt-3 p-md-3 p-lg-4">
		    <div class="container-xl">
			    
			    <h1 class="app-page-title">Aperçu</h1>
			    
			    <div class="app-card alert alert-dismissible shadow-sm mb-4 border-left-decoration" role="alert">
				    <div class="inner">
					    <div class="app-card-body p-3 p-lg-4">
						    <h3 class="mb-3">Bienvenue, Admin !</h3>
						    <div class="row gx-5 gy-3">
						        <div class="col-12 col-lg-9">
							        
							        <div>Cette section est réservée aux administrateurs. Vous pourrez y voir les messages des utilisateurs.</div>
							    </div><!--//col-->
							    <div class="col-12 col-lg-3">
								    <a class="btn app-btn-primary" href="./users.php"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-earmark-arrow-down me-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path d="M4 0h5.5v1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h1V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2z"/>
  <path d="M9.5 3V0L14 4.5h-3A1.5 1.5 0 0 1 9.5 3z"/>
  <path fill-rule="evenodd" d="M8 6a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 1 1 .708-.708L7.5 10.293V6.5A.5.5 0 0 1 8 6z"/>
	</svg>Utilisateurs</a>
							    </div><!--//col-->
						    </div><!--//row-->
						    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					    </div><!--//app-card-body-->
					    
				    </div><!--//inner-->
			    </div><!--//app-card-->
				    
			    <div class="row g-4 mb-4">
				    <div class="col-6 col-lg-3">
					    <div class="app-card app-card-stat shadow-sm h-100">
						    <div class="app-card-body p-3 p-lg-4">
							    <h4 class="stats-type mb-1">Total Des Messages</h4>
								<div class="stats-figure"><?php echo $totalMessages; ?></div>
							    <div class="stats-meta text-success">
								    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
	</svg> 20%</div>
						    </div><!--//app-card-body-->
						    <a class="app-card-link-mask" href="#"></a>
					    </div><!--//app-card-->
				    </div><!--//col-->
				    
				    <div class="col-6 col-lg-3">
					    <div class="app-card app-card-stat shadow-sm h-100">
						    <div class="app-card-body p-3 p-lg-4">
							    <h4 class="stats-type mb-1">Total Des Utilisateurs</h4>
								<div class="stats-figure"><?php echo $totalusers; ?></div>
							    <div class="stats-meta text-success">
								    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-down" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"/>
	</svg> 5% </div>
						    </div><!--//app-card-body-->
						    <a class="app-card-link-mask" href="#"></a>
					    </div><!--//app-card-->
				    </div><!--//col-->
				    <div class="col-6 col-lg-3">
					    <div class="app-card app-card-stat shadow-sm h-100">
						    <div class="app-card-body p-3 p-lg-4">
							    <h4 class="stats-type mb-1">Modele utilisé</h4>
							    <div class="stats-figure"><?php echo $modelUtilise; ?></div>
								<div class="stats-meta">
									<a href="gpt_message.php">GPT Content</a>
								</div>
						    </div><!--//app-card-body-->
						    <a class="app-card-link-mask" href="#"></a>
					    </div><!--//app-card-->
				    </div><!--//col-->

			    </div><!--//row-->
			    
			    </div><!--//row-->
			    <div class="row g-4 mb-4">
				    <div class="col-12 col-lg-6">
			        </div><!--//col-->
			        <div class="col-12 col-lg-6">
				        <div class="app-card app-card-stats-table h-100 shadow-sm">

				        </div><!--//app-card-->
			        </div><!--//col-->
			    </div><!--//row-->
			    
		    </div><!--//container-fluid-->
	    </div><!--//app-content-->
	    
		<?php include './footer.php' ?>

	    
    </div><!--//app-wrapper-->    					

 
    <!-- Javascript -->          
    <script src="assets/plugins/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>  

    <!-- Charts JS -->
    <script src="assets/plugins/chart.js/chart.min.js"></script> 
    <script src="assets/js/index-charts.js"></script> 
    
    <!-- Page Specific JS -->
    <script src="assets/js/app.js"></script> 

</body>
</html> 

