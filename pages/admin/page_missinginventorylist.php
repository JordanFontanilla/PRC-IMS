<?php include 'modals.php'; 
?> <!-- Include the modal -->

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0 text-gray-800">Missing Inventory List</h1>
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
                    <th>Status</th>
                    <th>Action</th>
                    
                </tr>
            </thead>    
            <tbody>
            <?php require 'fetch_missinginventory.php'; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../../sources2.php'; ?>
                
<script>
$(document).ready(function () {
    $(document).on('click', '.update-status', function () {
        const invId = $(this).data('id');
        const itemType = $(this).data('item-type');

        Swal.fire({
            title: 'Mark as Found?',
            text: "This will mark the item as 'Available'.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, mark as found',
            cancelButtonText: 'No, cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'pages/admin/process_mark_as_found.php',
                    type: 'POST',
                    data: { inv_id: invId, item_type: itemType },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'The item has been marked as found.',
                                icon: 'success'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error!', 'An error occurred.', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error!', 'Failed to communicate with the server.', 'error');
                    }
                });
            }
        });
    });
});
</script>
                