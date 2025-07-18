
<?php
session_start();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0 text-gray-800">User Management</h1>
    <?php if (isset($_SESSION['user_level']) && $_SESSION['user_level'] == 'Admin'): ?>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
        Add User
    </button>
    <?php endif; ?>
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
<script>
$(document).ready(function() {
    var table;
    if ($.fn.dataTable.isDataTable('#dataTableUser')) {
        table = $('#dataTableUser').DataTable();
    } else {
        table = $('#dataTableUser').DataTable({
            "order": [], // Example option
            "columnDefs": [{
                "targets": -1, // Last column (Action)
                "orderable": false
            }]
        });
    }

    // Move the custom filter dropdown into the DataTables wrapper
    var filterContainer = $('#userLevelFilterContainer');
    var filterHtml = filterContainer.html();
    filterContainer.remove();
    $('#dataTableUser_length').append(filterHtml);

    // Apply the filter
    $(document).on('change', '#userLevelFilter', function() {
        table.column(2).search($(this).val()).draw();
    });

    // Handle delete button click
    $(document).on('click', '.delete-user', function() {
        var username = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'pages/admin/process_delete_user.php',
                    type: 'POST',
                    data: { username: username },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Deleted!',
                                'The user has been deleted.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message,
                                'error'
                            );
                        }
                    }
                });
            }
        });
    });
});
</script>