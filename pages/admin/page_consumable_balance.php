<?php
session_start();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0 text-gray-800">Consumable Balance</h1>
    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#importBeginningBalanceModal"><i class="fas fa-file-import"></i>
        Import Beginning Balance
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

        /* Prevent wrapping in table cells */
        table td, table th {
            white-space: nowrap;
        }
        
    </style>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTableConsumableBalance" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Stock No.</th>
                        <th>Item Description</th>
                        <th>Unit</th>
                        <th>Beginning Balance</th>
                        <th>Receipts</th>
                        <th>Issuance</th>
                        <th>Ending Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php require 'fetch_consumable_balance.php'; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../sources2.php'; ?>

<script>
$(document).ready(function() {
    $('#dataTableConsumableBalance').DataTable();
});
</script>
