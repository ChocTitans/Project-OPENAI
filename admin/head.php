<?php 

if (!isset($_SESSION['loggedin']) || ($_SESSION['role'] !== 'admin')) {
    header('Location: ../login.php');
    exit();
}

?>

<head>
    <title>AI-MED</title>
    
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="description" content="AI-MED">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">    
    <link rel="shortcut icon" href="../images/s4.png" type="image/x-icon">
    
    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>
    
    <!-- App CSS -->  
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">

</head> 