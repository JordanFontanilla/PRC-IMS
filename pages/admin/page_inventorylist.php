<?php include 'modals.php';
?>

<!-- Include the modal -->

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0 text-gray-800">Inventory List</h1>
    <div class="ml-auto">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNonConsumableEquipModal"><i class="fas fa-plus"></i>
            Add
        </button>
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#importEquipModal"><i class="fas fa-file-import"></i>
            Import
        </button>
        <button type="button" class="btn btn-warning" id="exportExcelNonConsum"><i class="fas fa-file-export"></i>
            Export
        </button>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#printFilterModal">
            <i class="fas fa-print"></i> Print
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
            <?php require 'fetch_inventory.php'; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../sources2.php'; ?>

<script>
document.getElementById("exportExcelNonConsum").addEventListener("click", function () {
    Swal.fire({
        title: "Exporting...",
        text: "Please wait while the file is being generated.",
        icon: "info",
        allowOutsideClick: false,
        showConfirmButton: false
    });

    fetch('pages/admin/process_exporttoexcel.php')
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.blob(); // Convert response to blob
        })
        .then(blob => {
            const filename = "EQUIPMENTLIST_" + new Date().toISOString().slice(0,10).replace(/-/g, '') + ".xlsx";

            // Create download link
            const link = document.createElement("a");
            const url = window.URL.createObjectURL(blob);
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();

            // Cleanup
            window.URL.revokeObjectURL(url);
            link.remove();

            Swal.fire({
                title: "Export Successful!",
                text: "Your Excel file has been downloaded.",
                icon: "success",
                timer: 2000,
                showConfirmButton: false
            });
        })
        .catch(error => {
            console.error("Export failed:", error);
            Swal.fire("Error", "An error occurred during export.", "error");
        });
});
</script>
