<?php ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0 text-gray-800">Request Equipment</h1>
        <h6 class="h6 mb-0 text-gray-800">**Note: Click checkboxes to select 'available' items to borrow**</h6>
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
                    <th>Type</th>
                    <th>Brand/Model</th>
                    <th>Serial No.</th>
                    <th>Property No.</th>
                    <th>Property Name</th>
                    <th style="width: 5px;"><i class=" fas fa-check"></i></th>
                </tr>
            </thead>
            <tbody>
            <?php require 'fetch_reqinventory.php'; ?>
            </tbody>    
        </table>
        </div>
    <button type="button" class="btn btn-primary" id="openConfirmModal">
    Confirm
    </button>
    </div>

<?php include '../../sources2.php'; ?>