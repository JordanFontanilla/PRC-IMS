
<?php include 'modals.php'; ?> <!-- Include the modal -->

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0 text-gray-800">User Management</h1>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
        Add User
    </button>
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
<!-- We'll move this into the DataTables length control area -->
<!-- Move this outside the table -->
<div id="userLevelFilterContainer">
  <select id="userLevelFilter" class="form-control form-control-sm">
    <option value="">All Levels</option>
    <option value="Admin">Admin</option>
    <option value="Employee">Employee</option>
  </select>
</div>

        <table class="table table-bordered" id="dataTableUser" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Level</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>    
            <tbody>
            <?php require 'fetch_users.php'; ?>
            </tbody>
        </table>
    </div>
</div>


<?php include '../../sources2.php'; ?>
<style>
    #dataTableUser_length {
    display: flex;
    align-items: center;
    gap: 10px;
}

#userLevelFilter {
    min-width: 120px;
    font-weight: 800;  /* or use “bold” */
    font-size: 0.875rem;
    border-radius: 0.375rem; /* rounded */
    background-color:rgb(16, 155, 255); /* Bootstrap primary */
    color: #fff; /* white text */
    border: none;
    padding: 0.25rem 0.75rem;
    cursor: pointer;
    transition: background-color 0.2s ease-in-out;
}

#userLevelFilter:hover {
    background-color:rgba(0, 105, 217, 0.7); /* darker on hover */
}



</style>