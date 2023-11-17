<?php 

if (!isset($_SESSION['loggedin']) || ($_SESSION['role'] !== 'admin')) {
    header('Location: ../login.php');
    exit();
}

?>
<footer class="app-footer">
		    <div class="container text-center py-3">
            <small class="copyright">Designed with <span class="sr-only">love</span><i class="fas fa-heart" style="color: #fb866a;"></i> by <a class="app-link" target="_blank">HB & LM & IB</a></small>
		       
		    </div>
</footer>