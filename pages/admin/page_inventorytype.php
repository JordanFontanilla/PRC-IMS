
<?php
session_start();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0 text-gray-800">Inventory Type List</h1>
    <?php if (isset($_SESSION['user_level']) && $_SESSION['user_level'] == 'Admin'): ?>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addTypeModal">
        Add Type
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
        <table class="table table-bordered" id="dataTableInvType" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Origin</th>
                    <th>Action</th>
                </tr>
            </thead>    
            <tbody>
            
            </tbody>
        </table>
    </div>
</div>
<?php include '../../sources2.php'; ?>
<script>
$(document).ready(function () {
    $('#dataTableInvType').DataTable({
        ajax: 'pages/admin/fetch_invtype.php',
        columns: [
            { title: "ID" },
            { title: "Name" },
            { title: "Origin" },
            { title: "Action", orderable: false }
        ],
        order: [],
        responsive: true
    });
});
</script>
