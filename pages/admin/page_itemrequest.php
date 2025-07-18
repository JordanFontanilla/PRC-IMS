<?php ?>

<!-- Main Content -->
<div class="container-fluid">
  <style>
  .layout-flex {
    display: flex;
    flex-wrap: nowrap;
    align-items: flex-start;
  }

  .form-section {
    width: 25%;
    padding-right: 15px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .table-section {
    width: 75%;
    overflow-x: auto;
    max-height: 400px; /* Adjust height if needed */
    overflow-y: auto;
    background-color: rgb(238, 238, 238);
    padding: 15px;
    border-radius: 5px;
    margin-top: 32px; /* Adjust this to align with "Manual Add" */
  }
  .table-section table {
  font-size: 13px; /* Adjust as needed */
}   


    table td, table th {
      white-space: nowrap !important;
    }

    thead {
      background-color: #007bff !important;
      color: white !important;
      text-align: center;
    }

    .dataTables_filter,
    .dataTables_paginate{
      float: right !important;
      text-align: right !important;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .dataTables_filter input {
      width: 150px;
      padding: 5px;
    }

    .toggle-icon {
      padding: 5px 10px;
      border-radius: 50%;
      font-size: 20px;
      text-align: center;
      color: white;
    }

    .fa-plus.toggle-icon {
      background-color: green;
    }

    .fa-minus.toggle-icon {
      background-color: red;
    }

    
  </style>

  <h1 class="h3 mb-0 text-gray-800">Item Request</h1>
  <hr>

  <div class="layout-flex align-items-start">

    <!-- Left Side: Form + Buttons -->
    <div class="form-section">
      <form>
        <div class="form-group">
          <label for="borrowerName">Borrower's Name:</label>
          <input type="text" class="form-control" id="borrowerName" placeholder="Enter Name" required>
        </div>
        <div class="form-group" style="display: none;">
          <label>Item Type:</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="itemType" id="nonConsumableRadio" value="Non-Consumable" checked>
            <label class="form-check-label" for="nonConsumableRadio">Non-Consumable</label>
          </div>
        </div>
        <div class="form-group">
          <label for="searchItem">Add via Serial/Name/Model:</label>
          <input type="text" class="form-control" id="searchItem" placeholder="Serial, Name, or Model">
          <div id="searchDropdown" class="dropdown-menu w-100" style="max-height:200px; overflow-y:auto; position:absolute;"></div>
        </div>
        <div class="form-group">
          <label for="borrowerRemark">Reason:</label>
          <textarea class="form-control" id="borrowerRemark" rows="4" maxlength="200" style="resize: none;" placeholder="Enter Reason"></textarea>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex flex-column gap-2">
          <button type="button" id="proceedRequest" class="btn btn-success btn-block">Proceed</button>
          <button type="button" id="addItems" class="btn btn-primary btn-block">Manual Add</button>
        </div>
      </form>
    </div>



    <div class="divider"></div>


    <!-- Right Side: Table -->
    <div class="table-section">
      <h5>Equipment List:</h5>
      <table id="selectedInvs" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Type</th>
            <th>Brand/Model</th>
            <th>Serial No.</th>
            <th>Property No.</th>
            <th>Division/Section</th>
            <th>Quantity</th>
            <th style="width: 5px;"></th>
          </tr>
        </thead>
        <tbody>
          <!-- Data will be populated here -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include '../../sources2.php'; ?>

<script>
// Variables to manage debounce and selected items list
const debounceDelay = 500;
let debounceTimeout;
let selectedItemsList = {}; // Using an object to track selected item IDs

// Function to handle the input event for the barcode scan
$(document).on('input', '#searchItem', function () {
    const query = $(this).val().trim();
    const $dropdown = $('#searchDropdown');
    $dropdown.empty().hide();

    if (query.length >= 1) {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(function () {
            const itemType = $('input[name="itemType"]:checked').val();
            $.ajax({
                url: 'pages/admin/fetch_available_items.php',
                type: 'GET',
                data: { search: query, origin: itemType },
                dataType: 'json',
                success: function (data) {
                    $dropdown.empty();
                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(item => {
                            $dropdown.append(`
                                <a class="dropdown-item search-suggestion" href="#" data-id="${item.inv_id}" data-origin="${item.origin}">
                                    <strong>${item.inv_bnm}</strong> <small>(${item.type_name})</small><br>
                                    <span style="font-size:11px;">Serial: ${item.inv_serialno} | PropNo: ${item.inv_propno}</span>
                                </a>
                            `);
                        });
                        $dropdown.show();
                    } else {
                        $dropdown.html('<span class="dropdown-item disabled">No match found</span>').show();
                    }
                },
                error: function () {
                    $dropdown.html('<span class="dropdown-item disabled">Error searching</span>').show();
                }
            });
        }, debounceDelay);
    } else {
        $dropdown.empty().hide();
    }
});

// Handle click on dropdown suggestion
$(document).on('click', '.search-suggestion', function (e) {
    e.preventDefault();
    const invId = $(this).data('id');
    const origin = $(this).data('origin');
    // Fetch full item details by ID and add to table
    $.ajax({
        url: 'pages/admin/fetch_available_items.php',
        type: 'GET',
        data: { inv_id: invId, origin: origin },
        dataType: 'json',
        success: function (data) {
            if (Array.isArray(data) && data.length > 0) {
                addItemToTable(data[0]);
            }
            $('#searchDropdown').empty().hide();
            $('#searchItem').val('');
        }
    });
});

// Hide dropdown if clicking outside
$(document).on('click', function (e) {
    if (!$(e.target).closest('#searchItem, #searchDropdown').length) {
        $('#searchDropdown').empty().hide();
    }
});

// Function to handle the AJAX call to check the serial number
// Function to handle the AJAX call to check the serial number and add all matching items
function checkSerial(serial) {
    $.ajax({
        url: 'pages/admin/check_serial.php',
        type: 'GET',
        data: { serial: serial },
        success: function (response) {
            const data = JSON.parse(response);
            if (data.length > 0) {
                // Loop through each item and add them to the table
                data.forEach(item => {
                    addItemToTable(item); // Add each item to the table
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Match Found',
                    text: 'No matching items found for this serial number.',
                    timer: 1000,
                    showConfirmButton: false
                });
            }
            $('#searchItem').val(''); // Clear the input field after checking
        },
        error: function () {
            alert('Error checking the serial number.');
        }
    });
}


// Function to add the item to the table
function addItemToTable(item) {
    // Check if the item is already in the table by ID
    const existsInTable = $('#selectedInvs tbody tr').toArray().some(function (row) {
        return $(row).data('id') == item.inv_id && $(row).data('origin') == item.origin;
    });

    if (existsInTable) {
        Swal.fire({
            icon: 'warning',
            title: 'Duplicate Item',
            text: 'This item has already been added to the table!',
            timer: 1000,
            showConfirmButton: false
        });
        return;
    }

    // Add the item as a new row to the table
    const row = ` 
        <tr data-id="${item.inv_id}" data-origin="${item.origin}">
            <td>${item.type_name}</td>
            <td>${item.inv_bnm}</td>
            <td>${item.inv_serialno || 'N/A'}</td>
            <td>${item.inv_propno || 'N/A'}</td>
            <td>${item.inv_propname}</td>
            <td class="text-center">1</td>
            <td class="text-center">
                <i class="fa fa-trash toggle-icon bg-danger text-white rounded-circle p-2" data-id="${item.inv_id}"></i>
            </td>
        </tr>`;
    $('#selectedInvs tbody').append(row);

    // Track added item by ID
    selectedItemsList[item.inv_id] = { origin: item.origin };
}

// Event listener for deleting an item when the trash icon is clicked
$(document).on('click', '#selectedInvs .fa-trash', function () {

    const itemId = $(this).data('id');
    console.log("Item ID:", itemId);

    // Remove the item ID from the selectedItemsList (delete the property)
    delete selectedItemsList[itemId];
    console.log("Item ID removed from selectedItemsList:", itemId);

    // Remove the item row from the table
    const row = $(this).closest('tr');
    row.remove();
});

// Confirm request button - Alerts the number of items selected
$(document).on('click', '#proceedRequest', function () {
    const selectedCount = Object.keys(selectedItemsList).length;
    const borrowerName = $('#borrowerName').val().trim();
    const reason = $('#borrowerRemark').val().trim();

    // Validate borrower name and reason
    if (!borrowerName) {
    Swal.fire({
        icon: 'warning',
        title: 'Missing Information',
        text: "Please enter the borrower's name.",
        timer: 1000,
        showConfirmButton: false
    });
    return;
}
if (!reason) {
    Swal.fire({
        icon: 'warning',
        title: 'Missing Information',
        text: 'Please provide a reason.',
        timer: 1000,
        showConfirmButton: false
    });
    return;
}
if (selectedCount === 0) {
    Swal.fire({
        icon: 'warning',
        title: 'No Items Selected',
        text: 'Please select at least one item.'
    });
}
 else {
        // Log borrower name, reason, and selected items
        console.log('Borrower Name:', borrowerName);
        console.log('Reason:', reason);
        console.log('Selected Items:');

        // Log details of each selected item
        $('#selectedInvs tbody tr').each(function () {
            const row = $(this);
            const type = row.find('td:nth-child(1)').text();
            const brand = row.find('td:nth-child(2)').text();
            const serial = row.find('td:nth-child(3)').text();
            const propertyNo = row.find('td:nth-child(4)').text();
            const propertyName = row.find('td:nth-child(5)').text();

            console.log({
                type: type,
                brand: brand,
                serial: serial,
                propertyNo: propertyNo,
                propertyName: propertyName
            });
        });

        // Initialize signature canvas
        const signatureHTML = `
            <p>Request to borrow the items you've selected.</p>
            <p>Sign your signature to confirm:</p>
            <canvas id="signatureCanvas" width="400" height="250" style="border:1px solid #ccc; display:block; margin: 0 auto;"></canvas>
            <div style="text-align: center; margin-top: 10px;">
                <button type="button" id="clearSignature" style="padding: 6px 12px; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Clear Signature</button>
            </div>
        `;

        Swal.fire({
            title: 'Confirmation',
            html: signatureHTML,
            showCancelButton: true,
            confirmButtonText: 'Confirm Request',
            cancelButtonText: 'Cancel',
            didOpen: () => {
                const canvas = document.getElementById('signatureCanvas');
                const ctx = canvas.getContext('2d');
                let drawing = false;
                window.isSigned = false;

                // Signature drawing functionality
                canvas.addEventListener('mousedown', (e) => {
                    drawing = true;
                    const rect = canvas.getBoundingClientRect();
                    ctx.beginPath();
                    ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
                });

                canvas.addEventListener('mouseup', () => {
                    drawing = false;
                    ctx.beginPath();
                });

                canvas.addEventListener('mouseout', () => {
                    drawing = false;
                    ctx.beginPath();
                });

                canvas.addEventListener('mousemove', (e) => {
                    if (!drawing) return;
                    const rect = canvas.getBoundingClientRect();
                    ctx.lineWidth = 2;
                    ctx.lineCap = 'round';
                    ctx.strokeStyle = '#000';
                    ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
                    ctx.stroke();
                    window.isSigned = true;
                });

                // Clear signature button
                document.getElementById('clearSignature').addEventListener('click', () => {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.beginPath();
                    window.isSigned = false;
                });
            },
            preConfirm: () => {
                return new Promise((resolve) => {
                    if (!window.isSigned) {
                        Swal.showValidationMessage('Please sign before confirming!');
                        resolve(false);
                        return;
                    }

                    // Get the signature data as an image (base64)
                    const canvas = document.getElementById('signatureCanvas');
                    const signatureData = canvas.toDataURL('image/png');

                    // Prepare the data to send via AJAX
                    const items = [];
                    $('#selectedInvs tbody tr').each(function () {
                        const row = $(this);
                        const invId = $(this).data('id');
                        const origin = $(this).data('origin'); // Should always be 'non_consumable' now

                        items.push({
                            invId: invId,
                            origin: origin,
                            type: row.find('td:nth-child(1)').text(),
                            brand: row.find('td:nth-child(2)').text(),
                            serial: row.find('td:nth-child(3)').text(),
                            propertyNo: row.find('td:nth-child(4)').text(),
                            propertyName: row.find('td:nth-child(5)').text(),
                            quantity: 1 // Always 1 for non-consumables
                        });
                    });

                    // Send the request via AJAX
                    $.ajax({
                        url: 'pages/admin/process_borrow_request.php',
                        type: 'POST',
                        data: {
                            borrowerName: borrowerName,
                            borrowerRemark: reason,
                            signatureData: signatureData,
                            items: JSON.stringify(items)
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Success',
                                    html: 'Request has been sent! <br> Please wait for Administrator approval.',
                                    icon: 'success',
                                    timer: 2000,
                                    timerProgressBar: true
                                }).then(() => {
                                    // Reset the form
                                    $('#borrowerName').val('');
                                    $('#borrowerRemark').val('');
                                    $('#selectedInvs tbody').empty();
                                    selectedItemsList = {};
                                    resolve(true); // Resolve the promise to close the modal
                                });
                            } else {
                                Swal.fire('Error', data.message || 'There was an error processing your request.', 'error');
                                resolve(false); // Resolve with false to keep modal open or show validation message
                            }
                        },
                        error: function (xhr, status, error) {
                            Swal.fire('Error', 'Invalid server response. Please try again.', 'error');
                            resolve(false); // Resolve with false on AJAX error
                        }
                    });
                });
            }
        });
    }
});

// Manual Add Button Click - Open modal and load items
$(document).on('click', '#addItems', function () {
    $('#manualAddModal').modal('show');

    $.ajax({
        url: 'pages/admin/fetch_available_items.php',
        type: 'GET',
        success: function (response) {
            $('#modalItemTableBody').html(response);

            // Destroy previous DataTable instance if it exists
            if ($.fn.DataTable.isDataTable('#dataTableReqItem')) {
                $('#dataTableReqItem').DataTable().destroy();
            }

            // Collect all item IDs already added in the main table
            let alreadyAddedIds = {};
            $('#selectedInvs tbody tr').each(function () {
                const id = $(this).data('id');
                if (id) alreadyAddedIds[id] = true;
            });

            // Update toggle icons based on selectedItemsList
            $('#modalItemTableBody .toggle-icon').each(function () {
                const itemId = $(this).data('id');

                if (alreadyAddedIds[itemId]) {
                    // Hide items already in the main table
                    $(this).closest('tr').hide();
                } else if (selectedItemsList[itemId]) {
                    // Show as selected (minus)
                    $(this)
                        .removeClass('fa-plus bg-success')
                        .addClass('fa-minus bg-danger')
                        .data('state', 'minus');
                } else {
                    // Show as unselected (plus)
                    $(this)
                        .removeClass('fa-minus bg-danger')
                        .addClass('fa-plus bg-success')
                        .data('state', 'plus');
                }
            });

            // Reinitialize DataTable
            $('#dataTableReqItem').DataTable();
        },
        error: function () {
            alert('Error loading items');
        }
    });
});

// Toggle icon click inside the modal
$(document).on('click', '.toggle-icon', function () {
    const icon = $(this);
    const invId = icon.data('id');

    if (icon.data('state') === 'plus') {
        icon.removeClass('fa-plus bg-success').addClass('fa-minus bg-danger').data('state', 'minus');
        selectedItemsList[invId] = true; // Add to tracking object
    } else {
        icon.removeClass('fa-minus bg-danger').addClass('fa-plus bg-success').data('state', 'plus');
        delete selectedItemsList[invId]; // Remove from tracking object
    }
});

// Add Selected Items from Modal to Main Table
$(document).on('click', '#addSelectedItems', function () {
    const itemIds = Object.keys(selectedItemsList);

    if (itemIds.length > 0) {
        itemIds.forEach(function (itemId) {
            // Skip if already in the table
            let exists = false;
            $('#selectedInvs tbody tr').each(function () {
                if ($(this).data('id') == itemId) {
                    exists = true;
                    return false;
                }
            });

            if (!exists) {
                $('#modalItemTableBody .toggle-icon').each(function () {
                    if ($(this).data('id') == itemId) {
                        const row = $(this).closest('tr');
                        const type = row.find('td:nth-child(1)').text();
                        const brand = row.find('td:nth-child(2)').text();
                        const serial = row.find('td:nth-child(3)').text();
                        const propertyNo = row.find('td:nth-child(4)').text();
                        const propertyName = row.find('td:nth-child(5)').text();

                        $('#selectedInvs tbody').append(`
                            <tr data-id="${itemId}">
                                <td>${type}</td>
                                <td>${brand}</td>
                                <td>${serial}</td>
                                <td>${propertyNo}</td>
                                <td>${propertyName}</td>
                                <td class="text-center align-middle">
                                    <i class="fa fa-trash toggle-icon bg-danger text-white rounded-circle p-2" data-id="${itemId}"></i>
                                </td>
                            </tr>
                        `);
                    }
                });
            }
        });

        $('#manualAddModal').modal('hide');
    } else {
            Swal.fire({
            icon: 'warning',
            title: 'No items selected',
            text: "Please select an available item to request.",
            timer: 1500,
            showConfirmButton: true,
            timerProgressBar: true
        });
    }
});

document.addEventListener('click', (e) => {
    const target = e.target;

    // If user clicked on a focusable element, do nothing
    if (target.closest('input, textarea, [contenteditable], .modal, .dropdown-menu')) {
        return;
    }

    // Only focus if body is focused and searchItem exists
    if (document.activeElement === document.body) {
        const searchItem = document.getElementById('searchItem');
        if (searchItem) {
            searchItem.focus();
        }
    }
}); 

// Optional: focus on load if #searchItem exists
window.addEventListener('load', () => {
    const searchItem = document.getElementById('searchItem');
    if (searchItem) {
        searchItem.focus();
    }
});






function logAuditAction(actionText) {
        fetch('pages/admin/log_audit_action.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=' + encodeURIComponent(actionText)
        });
    }

    document.getElementById("addItems").addEventListener("click", function () {
        logAuditAction("Clicked manual add option");
    });

    document.getElementById("searchItem").addEventListener("click", function () {
        logAuditAction("Clicked search item option");
    });
    
    document.getElementById("proceedRequest").addEventListener("click", function () {
        logAuditAction("Clicked proceed");
    });
</script>