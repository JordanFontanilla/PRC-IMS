<?php include 'modals.php';
?>

<!-- Include the modal -->

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0 text-gray-800">Inventory List: Non-Consumable </h1>
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
            <?php 
        // open your DB connection (you can reuse or reopen—it’s a cheap call)
        require '../../function_connection.php';
        $typeRes = $conn->query("
            SELECT DISTINCT type_name 
            FROM tbl_type 
            WHERE type_origin = 'Non-Consumable'
            ORDER BY type_name
        ");
        ?>
        <!-- filter dropdown, styled small and btn-like if you want -->
        <div id="typeFilterContainer" style="display: none;">
        <select id="typeFilter" class="form-control form-control-sm">
            <option value="">All Types</option>
            <?php while($t = $typeRes->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($t['type_name']) ?>">
                <?= htmlspecialchars($t['type_name']) ?>
            </option>
            <?php endwhile; ?>
        </select>
        </div>
        <table class="table table-bordered" id="dataTableNonConsumable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Brand/Model</th>
                    <th>Serial No.</th>
                    <th>Property No.</th>
                    <th>Division/Section</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>    
            <tbody>
            <?php require 'fetch_inventory_nonconsumable.php'; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../../sources2.php'; ?>

<script>
$('#generatePDFBtn').click(function() {
    window.open('pages/admin/process_createpdf.php', '_blank'); // Opens in a new tab
});

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
$(document).ready(function () {
  var invTable = $('#dataTableNonConsumable').DataTable({
    order: [], // or keep your LIFO order if needed
    columnDefs: [
      { orderable: false, targets: -1 } // disable sort on "Action"
    ]
  });

  // Position dropdown beside "Show N entries"
  var $lenContainer = $('#dataTableNonConsumable_length');
  $lenContainer.addClass('d-flex align-items-center gap-2');
  $lenContainer.append($('#typeFilterContainer').show());

  // Hook up the dropdown filter
  $('#typeFilter').on('change', function () {
    var selectedType = $(this).val();
    invTable
      .column(0) // "Type" column is index 0
      .search(selectedType)
      .draw();
  });
});
</script>
<style>
    #typeFilter {
    min-width: 120px;
    font-weight: 800;  /* or use “bold” */
    font-size: 0.875rem;
    border-radius: 0.375rem; /* rounded */
    background-color:rgb(16, 155, 255); /* Bootstrap primary */
    color: white !important; /* white text */
    border: none;
    padding: 0.25rem 0.75rem;
    cursor: pointer;
    transition: background-color 0.2s ease-in-out;
}

#typeFilter:hover {
    background-color:rgba(0, 105, 217, 0.7); /* darker on hover */
}

</style>

