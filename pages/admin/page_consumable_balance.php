<?php
session_start();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0 text-gray-800">Consumable Balance</h1>
    <div>
        <select id="consumableMonthFilter" class="form-control" style="display:inline-block; width:auto; margin-right: 10px;">
            <option value="">Latest Month</option>
        </select>
        <button type="button" class="btn btn-danger" id="deleteMonthBtn" style="display:none;"><i class="fas fa-trash"></i> Delete Selected Month</button>
        <button type="button" class="btn btn-success" id="archiveForwardBtn"><i class="fas fa-archive"></i>
            Archive and Forward to Next Month
        </button>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addConsumableBalanceModal"><i class="fas fa-plus"></i>
            Add New Balance
        </button>
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#importBeginningBalanceModal"><i class="fas fa-file-import"></i>
            Import Beginning Balance
        </button>
    </div>
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded by DataTables -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../sources2.php'; ?>

<script>
$(document).ready(function() {
    var table = $('#dataTableConsumableBalance').DataTable({
        "ajax": {
            "url": "pages/admin/fetch_consumable_balance.php",
            "type": "GET",
            "data": function(d) {
                d.month = sessionStorage.getItem('selectedMonth') || $('#consumableMonthFilter').val();
            }
        },
        "columns": [
            { "data": 0 },
            { "data": 1 },
            { "data": 2 },
            { "data": 3 },
            { "data": 4 },
            { "data": 5 },
            { "data": 6 },
            { "data": 7, "orderable": false }
        ]
    });

    // Populate month filter and set selected month
    $.ajax({
        url: 'pages/admin/fetch_consumable_months.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            var filter = $('#consumableMonthFilter');
            // Clear existing options except the default
            filter.find('option:not(:first)').remove();
            $.each(data, function(key, value) {
                filter.append('<option value="' + value.year + '-' + value.month + '">' + value.month_name + ' ' + value.year + '</option>');
            });
            // Set the dropdown to the stored value
            var storedMonth = sessionStorage.getItem('selectedMonth');
            if (storedMonth) {
                filter.val(storedMonth);
                table.ajax.reload();
            }
        }
    });

    // Handle month filter change
    $('#consumableMonthFilter').on('change', function() {
        sessionStorage.setItem('selectedMonth', $(this).val());
        table.ajax.reload();
        if ($(this).val() !== "") {
            $('#deleteMonthBtn').show();
        } else {
            $('#deleteMonthBtn').hide();
        }
    });

    // Delete Month button click
    $('#deleteMonthBtn').on('click', function() {
        var selectedMonth = $('#consumableMonthFilter').val();
        if (selectedMonth === "") {
            Swal.fire('No Month Selected', 'Please select a month to delete.', 'info');
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete all records for the selected month. This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'pages/admin/process_delete_monthly_balance.php',
                    type: 'POST',
                    data: { month: selectedMonth },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Deleted!', 'The records for the selected month have been deleted.', 'success').then(() => {
                                location.reload(); // Reload to update dropdown and table
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    }
                });
            }
        });
    });

    // Archive and Forward button click
    $('#archiveForwardBtn').on('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will archive the current month and forward the balances to the next month.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'pages/admin/process_archive_forward_month.php',
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success', response.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    }
                });
            }
        });
    });

    // Add form submission
    $('#addConsumableBalanceForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'pages/admin/process_add_consumable_balance.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success', response.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            }
        });
    });

    // Edit button click
    table.on('click', '.edit-consumable-balance', function() {
        var id = $(this).data('id');
        $.ajax({
            url: 'pages/admin/fetch_consumable_balance_details.php',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#edit_balance_id').val(response.data.id);
                    $('#edit_stock_number').val(response.data.stock_number);
                    $('#edit_item_description').val(response.data.item_description);
                    $('#edit_unit').val(response.data.unit);
                    $('#edit_beginning_balance').val(response.data.beginning_balance);
                    $('#editConsumableBalanceModal').modal('show');
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            }
        });
    });

    // Edit form submission
    $('#editConsumableBalanceForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'pages/admin/process_update_consumable_balance.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success', response.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            }
        });
    });

    // Delete button click
    table.on('click', '.delete-consumable-balance', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won\'t be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'pages/admin/process_delete_consumable_balance.php',
                    type: 'POST',
                    data: { id: id },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Deleted!', 'The record has been deleted.', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    }
                });
            }
        });
    });
});
</script>
