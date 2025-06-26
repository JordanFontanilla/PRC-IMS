<?php include 'modals.php';
// Check if the user is already logged in and active
if (isset($_SESSION['is_active']) && $_SESSION['is_active'] == 1) {
    // Check if the user is Admin or Employee and redirect accordingly
    if ($_SESSION['user_level'] === 'Admin') {
        header("Location: index.php");  // Redirect to Admin dashboard
        exit;
    } elseif ($_SESSION['user_level'] === 'Employee') {
        header("Location: index_employee.php");  // Redirect to Employee dashboard
        exit;
    }
}
?> <!-- Include the modal -->

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0 text-gray-800">Borrowed Equipment List</h1>
</div>
<hr>
<!-- Begin Page Content -->
<div class="container-fluid">
    <style>
        .dataTables_filter {
            float: right !important;
            text-align: right !important;
        }
        .dataTables_paginate {
            float: right !important;
        }
        thead {
            background-color: #007bff !important; /* Bootstrap primary color */
            color: white !important;
            text-align: center;
        }
        
        .dataTables_length label {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .dataTables_length select {
            width: auto !important;
        }
        .dataTables_length,
        .dataTables_filter {
            display: flex;
            align-items: center;
            gap: 5px; /* Adds spacing */
        }

        .dataTables_filter label {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Fix search input width */
        .dataTables_filter input {
            width: 150px; /* Adjust width as needed */
            padding: 5px;}
    </style>

    <div class="card-body">
    <div class="table-static">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Date Approved</th>
                    <th>Borrower's Name</th>
                    <th>No. of Items</th>
                    <th>Action</th>
                </tr>
            </thead>    
            <tbody>
            <?php require 'fetch_borrowedlist.php'; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../../sources2.php'; ?>
                