<?php include 'modals.php'; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0 text-gray-800">Returned Equipment</h1>
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
            padding: 5px;
        }
        /* Adjust the Number of Items column to fit only two digits */
        #dataTable td:nth-child(1),
        #dataTable th:nth-child(1) {
           
            text-align: center;  /* Center the content */
        }
        /* Adjust the Number of Items column to fit only two digits */
        #dataTable td:nth-child(2),
        #dataTable th:nth-child(2) {
            width: 200px;
            text-align: center;  /* Center the content */
        }

        /* Adjust the Number of Items column to fit only two digits */
        #dataTable td:nth-child(3),
        #dataTable th:nth-child(3) {
            width: 120px;  /* Limit width to accommodate two digits */
            text-align: center;  /* Center the content */
        }

        /* Adjust the Action column to be more compact */
        #dataTable td:nth-child(4),
        #dataTable th:nth-child(4) {
            text-align: center;  /* Center the content */
        }

        #dataTable td:nth-child(5),
        #dataTable th:nth-child(5) {
            width: 200px;  /* Reduce the width of the action column */
            text-align: center;  /* Center the content */
        }

        /* Optional: Make the table more responsive by allowing it to scroll horizontally */
        .table-responsive {
            overflow-x: auto;
        }
        table td, table th {
        white-space: nowrap;
        }

        table {
            table-layout: auto; /* or fixed if you want strict column control */
            width: 100%;
        }

        .table-responsive {
            overflow-x: auto;
        }

    </style>
        <div class="card-body">
        <div class="table-static">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Date Requested</th>
                    <th>Borrower's Name</th>
                    <th >No. of Items</th>
                    <th>Date Returned</th>
                    <th>Returner's Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php require 'fetch_returnlist.php'; ?>
            </tbody>    
        </table>
        </div>
    </div>

<?php include '../../sources2.php'; ?>