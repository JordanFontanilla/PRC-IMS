<?php include 'modals.php'; ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Pending Return</h1>
</div>
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

<div class="cardbody">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">List of Items for Return Approval</h6>
    </div>
    <div class="table-static">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTablePendingReturn" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Date Borrowed</th>
                        <th>Borrower's Name</th>
                        <th>No. of Items</th>
                        <th>Returner's Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php require 'fetch_pending_return.php'; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../sources2.php'; ?>

<script>
$(document).ready(function() {
    $('#dataTablePendingReturn').DataTable();

    $(document).on('click', '.approve-return', function() {
        var breq_token = $(this).data('id');

        Swal.fire({
            title: 'Approve this return?',
            text: "This action will mark all items in this request as 'Returned' and cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'pages/admin/process_approve_return.php',
                    type: 'POST',
                    data: { breq_token: breq_token },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire(
                                'Approved!',
                                response.message,
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
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'An error occurred while processing the request.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script> 