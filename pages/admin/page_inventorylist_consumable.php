<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!-- Include the modal -->

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0 text-gray-800">Inventory List: Consumable</h1>
    <div class="ml-auto d-flex align-items-center">
        <select id="inventoryMonthFilter" class="form-control form-control-sm" style="width:auto; margin-right: 10px;">
            <option value="">Latest Month</option>
        </select>
        <div id="filterContainer" class="d-inline-flex align-items-center gap-2" style="margin-right: 10px;">
            <label for="filterColumn" class="mb-0 mr-2">Filter by:</label>
            <select id="filterColumn" class="form-control form-control-sm w-auto d-inline-block">
                <option value="">All Columns</option>
                <option value="1">Stock Number</option>
                <option value="2">Acceptance Date</option>
                <option value="3">RIS No.</option>
            </select>
            <input type="text" id="filterValue" class="form-control form-control-sm w-auto d-inline-block" placeholder="Search...">
        </div>
        <button type="button" class="btn btn-danger btn-sm" id="deleteMonthBtnInv" style="display:none; margin-right: 5px;"><i class="fas fa-trash"></i> Delete Month</button>
        <button type="button" class="btn btn-success btn-sm" id="archiveForwardBtnInv" style="margin-right: 5px;"><i class="fas fa-archive"></i> Archive & Forward</button>
        <?php if (isset($_SESSION['user_level']) && $_SESSION['user_level'] == 'Admin'): ?>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addConsumableEquipModal"><i class="fas fa-plus"></i>
            Add
        </button>
        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#importEquipModal"><i class="fas fa-file-import"></i>
            Import
        </button>
        <?php endif; ?>
        <button type="button" class="btn btn-warning btn-sm" id="exportExcelConsum"><i class="fas fa-file-export"></i>
            Export
        </button>
        <button type="button" class="btn btn-success btn-sm" id="reportExcelConsum"><i class="fas fa-file-excel"></i>
            Reports
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
        <table class="table table-bordered" id="dataTableConsumable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Stock Number</th>
                    <th>Acceptance Date</th>
                    <th>RIS No.</th>
                    <th>Item Description</th>
                    <th>Unit</th>
                    <th>Receipt</th>
                    <th>Issuance</th>
                    
                    <th>End User of Issuance</th>
                    <th>Action</th>
                </tr>
            </thead>    
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<?php include '../../sources2.php'; ?>

<script>
$(document).ready(function () {
  var invTable = $('#dataTableConsumable').DataTable({
    "ajax": {
        "url": "pages/admin/fetch_inventory_consumable.php",
        "type": "GET",
        "data": function(d) {
            d.month = sessionStorage.getItem('selectedMonth') || $('#inventoryMonthFilter').val();
            d.filter_column = $('#filterColumn').val();
            d.filter_value = $('#filterValue').val();
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
        { "data": 7 },
        { "data": 8 },
        { "data": 9, "orderable": false }
    ]
  });

  // Populate month filter and set selected month
  $.ajax({
      url: 'pages/admin/fetch_consumable_months.php',
      type: 'GET',
      dataType: 'json',
      success: function(data) {
          var filter = $('#inventoryMonthFilter');
          // Clear existing options except the default
          filter.find('option:not(:first)').remove();
          $.each(data, function(key, value) {
              filter.append('<option value="' + value.year + '-' + value.month + '">' + value.month_name + ' ' + value.year + '</option>');
          });
          // Set the dropdown to the stored value
          var storedMonth = sessionStorage.getItem('selectedMonth');
          if (storedMonth) {
              filter.val(storedMonth);
              invTable.ajax.reload();
              if (storedMonth !== "") {
                  $('#deleteMonthBtnInv').show();
              }
          }
      }
  });

  // Handle filters change
  $('#inventoryMonthFilter, #filterColumn, #filterValue').on('change keyup', function() {
      var selectedMonth = $('#inventoryMonthFilter').val();
      sessionStorage.setItem('selectedMonth', selectedMonth);
      invTable.ajax.reload();
      if (selectedMonth !== "") {
          $('#deleteMonthBtnInv').show();
      } else {
          $('#deleteMonthBtnInv').hide();
      }
  });

  // Delete Month button click
  $('#deleteMonthBtnInv').on('click', function() {
      var selectedMonth = $('#inventoryMonthFilter').val();
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

  // Archive and Forward button click
  $('#archiveForwardBtnInv').on('click', function() {
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
$('#reportExcelConsum').on('click', function() {
    $('#reportGenerationModal').modal('show');
});
</script>
<style>
    #filterContainer {
        min-width: 120px;
        font-weight: 800;
        font-size: 0.875rem;
        border-radius: 0.375rem;
        background-color:rgb(16, 155, 255);
        color: white !important;
        border: none;
        padding: 0.25rem 0.75rem;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out;
    }

    #filterContainer:hover {
        background-color:rgba(0, 105, 217, 0.7);
    }
</style>