<?php include 'modals.php';
?>

<!-- Include the modal -->

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0 text-gray-800">Inventory List: Consumable</h1>
    <div class="ml-auto">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addConsumableEquipModal"><i class="fas fa-plus"></i>
            Add
        </button>
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#importEquipModal"><i class="fas fa-file-import"></i>
            Import
        </button>
        <button type="button" class="btn btn-warning" id="exportExcelConsum"><i class="fas fa-file-export"></i>
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
                    <th>ID</th>
                    <th>Type ID</th>
                    <th>Serial No.</th>
                    <th>Property No.</th>
                    <th>Property Name</th>
                    <th>Inventory Price</th>
                    <th>Status</th>
                    <th>Brand/Model</th>
                    <th>Date Added</th>
                    <th>Date Acquired</th>
                    <th>Price</th>
                    <th>Condition</th>
                    <th>Quantity</th>
                    <th>End User</th>
                    <th>Accounted To</th>
                    <th>Action</th>
                </tr>
            </thead>    
            <tbody>
            <?php require 'fetch_inventory_consumable.php'; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../../sources2.php'; ?>

<script>
$('#generatePDFBtn').click(function() {
    window.open('pages/admin/process_createpdf.php', '_blank'); // Opens in a new tab
});

document.getElementById("exportExcelConsum").addEventListener("click", function () {
    Swal.fire({
        title: "Exporting...",
        text: "Please wait while the file is being generated.",
        icon: "info",
        allowOutsideClick: false,
        showConfirmButton: false
    });

    fetch('pages/admin/process_exporttoexcel.php?origin=consumable')
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.blob();
        })
        .then(blob => {
            const filename = "EQUIPMENTLIST_" + new Date().toISOString().slice(0,10).replace(/-/g, '') + ".xlsx";
            const link = document.createElement("a");
            const url = window.URL.createObjectURL(blob);
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
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