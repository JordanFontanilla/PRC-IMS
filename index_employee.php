<?php
// Start the session to access session variables
session_start();

// Include the database connection (function_connection.php)
require 'function_connection.php';

// Check if the session variables are set (i.e., the user is logged in)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    // If session variables are not set, redirect the user to login.php
    header('Location: login.php');
    exit;
}
// If session variables are set, user is logged in, and you can continue with the page content
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Employee Dashboard</title>
    
    <link rel="icon" href="img/favicon.png" type="image/png">
</head>




<body id="page-top">
<div id="wrapper">
<!-- include here sidebar-->
<?php include 'sidebar_employee.php'; ?>

<!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- MAIN TOP -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <!-- Topbar Search -->
                    <h2> <b>Inventory Management System Employee</b></h2>
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
   <span class="mr-2 d-none d-lg-inline text-gray-600 small">
       <?php 
           $userLevel = isset($_SESSION['user_level']) ? $_SESSION['user_level'] : 'Guest';
           $userFullName = isset($_SESSION['userfullname']) ? $_SESSION['userfullname'] : 'Guest';
           echo htmlspecialchars( $userFullName) . " | " . htmlspecialchars($userLevel);
       ?>
   </span>
   <img class="img-profile rounded-circle" src="img/logo.png">
</a>

                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- PAGE CONTENT -->
                <div class="container-fluid">
                    <!-- Content Row -->
                    <div class="row">
                        <!-- Big White Box Container -->
                        <div class="col-12">
                            <div class="card1 shadow mb-4">
                                <div class="card-body" style="height: 610px; overflow-y: auto;">

                                    <!-- Content that will be loaded here via Ajax -->
                                    <div id="main-content" >
                                        <!-- MAIN content will load here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Main Content -->
            <!-- Footer -->
            <footer>
                <!-- [Footer code remains unchanged] -->
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
</div>
    <!-- End of Page Wrapper -->


    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="process_logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'sources.php'; ?>
    <?php include 'js/ajax/pageloader.php';?>
    
    
</body>
</html>