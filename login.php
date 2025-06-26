<?php
session_start();
if (isset($_SESSION['user_id'])) {
    // If the user is already logged in, log session details to the console
    echo "<script type='text/javascript'>
            console.log('Session Details:');
            console.log('User ID: " . $_SESSION['user_id'] . "');
            console.log('Username: " . $_SESSION['username'] . "');
            console.log('Full Name: " . $_SESSION['userfullname'] . "');
            console.log('User Level: " . $_SESSION['user_level'] . "');
          </script>";
    // Redirect to the appropriate page (index.php or other dashboard)
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>IMS - Login</title>
    <link rel="icon" href="img/favicon.png" type="image/png">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <?php require 'sources.php'; ?>
    <style>
    @keyframes rgbGradient {
        0% { background: #224abe; }
        50% { background:   rgb(46, 91, 225); }
        100% { background: #224abe; }

    }

    .animated-rgb {
        animation: rgbGradient 5s infinite alternate;
    }
</style>
    <script src="vendor/jquery/jquery.min.js"></script> <!-- Ensure jQuery is included -->
</head>

<body class="animated-rgb">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-10 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-5 d-none d-lg-flex justify-content-center align-items-center">
                                <img src="img/logo.png" alt="Logo" class="img-fluid ml-5" style="max-width: 100%; height: auto;">
                            </div>
                            <div class="col-lg-7">
                                <div class="p-5">
                                    <div class="text-center">
                                    <h1 class="h1 text-gray-900 mb-1">Professional Regulation Commission</h1>
                                    <hr>
                                    <h1 class="h5 text-gray-900 mb-2">Inventory Management System</h1>
                                    </div>
                                    
                                    <!-- Login Form -->
                                    <form id="loginForm" class="user">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="loginuser" id="loginuser" placeholder="Username" required>
                                        </div>
                                        <div class="form-group position-relative">
                                            <input type="password" class="form-control form-control-user" name="loginpass" id="loginpass" placeholder="Password" required>
                                            <span class="position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;" onclick="togglePassword()">
                                                <i class="fas fa-eye" id="toggleIcon"></i>
                                            </span>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
                                        <hr>
                                    </form>

                                    <!-- Error Message Display -->
                                    <div id="loginMessage" class="text-danger text-center"></div>
                                    <hr>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("loginpass");
            var toggleIcon = document.getElementById("toggleIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }


        $(document).ready(function() {
    $("#loginForm").submit(function(event) {
        event.preventDefault();

        $.ajax({
            type: "POST",
            url: "process_login.php",
            data: $(this).serialize(),
            success: function(response) {
                console.log("Server response:", response); // helpful for debugging

                if (response === "index.php") {
                    Swal.fire({
                        icon: "success",
                        title: "Login Successful!",
                        text: "Redirecting to dashboard...",
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = response;
                    });
                } else if (response === "inactive") {
                    Swal.fire({
                        icon: "warning",
                        title: "Account Suspended",
                        text: "Your account is currently inactive. Please contact your local administrator.",
                        confirmButtonColor: "#d33"
                    });
                } else if (response === "invalid") {
                    Swal.fire({
                        icon: "error",
                        title: "Login Failed",
                        text: "Invalid username or password.",
                    });
                } else if (response === "db_error") {
                    Swal.fire({
                        icon: "error",
                        title: "Server Error",
                        text: "A database error occurred. Please try again later.",
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Login Failed",
                        text: "Unexpected error. Please try again.",
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: "Something went wrong. Please try again later.",
                });
            }
        });
    });
});


        // Prevent going back to the previous page after logout
        if (window.history && window.history.pushState) {
            window.history.pushState('forward', null, './');
            window.history.forward();
        }

    </script>
</body>
<script type="text/javascript">
    // Get the session details from PHP and log them in the console
    console.log('Session Details:');
    console.log('User ID: <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "Not Set"; ?>');
    console.log('Username: <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : "Not Set"; ?>');
    console.log('Full Name: <?php echo isset($_SESSION['userfullname']) ? $_SESSION['userfullname'] : "Not Set"; ?>');
    console.log('User Level: <?php echo isset($_SESSION['user_level']) ? $_SESSION['user_level'] : "Not Set"; ?>');
</script>
</html>
