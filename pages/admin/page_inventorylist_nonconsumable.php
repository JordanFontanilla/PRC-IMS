<?php
session_start();
?>

<!-- Include the modal -->

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0 text-gray-800">Inventory List: Non-Consumable </h1>
    <div class="ml-auto">
        <?php if (isset($_SESSION['user_level']) && $_SESSION['user_level'] == 'Admin'): ?>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNonConsumableEquipModal"><i class="fas fa-plus"></i>
            Add
        </button>
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#importEquipModal"><i class="fas fa-file-import"></i>
            Import
        </button>
        <?php endif; ?>
        <button type="button" class="btn btn-warning" id="exportExcelNonConsum"><i class="fas fa-file-export"></i>
            Export
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

    // Get selected type filter value
    var selectedType = document.getElementById("typeFilter") ? document.getElementById("typeFilter").value : "";

    fetch('pages/admin/process_exporttoexcel.php?type=' + encodeURIComponent(selectedType) + '&origin=nonconsumable')
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
      .column(1) // "Type" column is index 1
      .search(selectedType)
      .draw();
  });

  $(document).on('click', '.report-missing', function() {
    var invId = $(this).data('id');
    var origin = $(this).data('origin');

    Swal.fire({
        title: 'Are you sure?',
        text: "This item will be reported as missing.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, report it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'pages/admin/process_report_missing.php',
                type: 'POST',
                data: { inv_id: invId, origin: origin },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire(
                            'Reported!',
                            'The item has been reported as missing.',
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
                }
            });
        }
    });
});

    $(document).on('click', '.delete-inv', function() {
    var invId = $(this).data('id');
    var origin = $(this).data('origin');

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'pages/admin/process_delete_inventory.php',
                type: 'POST',
                data: { inv_id: invId, origin: origin },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            'Deleted!',
                            'The record has been deleted.',
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
                }
            });
        }
    });
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

