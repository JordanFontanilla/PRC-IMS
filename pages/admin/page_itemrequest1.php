<h1 class="h3 mb-0 text-gray-800">Item Request</h1>
<hr>

<!-- Form for Name and Remarks -->
<form>
    <div class="form-row mb-3">
        <div class="col-md-6">
            <label for="borrowerName">Borrower's Name:</label>
            <input type="text" class="form-control" id="borrowerName" placeholder="Enter Name" required>
        </div>
        <div class="col-md-6">
            <label for="remarks">Remarks:</label>
            <input type="text" class="form-control" id="remarks" placeholder="Enter Remarks">
        </div>
    </div>
</form>

<!-- Input field to add items with Add button beside it -->
<div class="form-row mb-3">
    <div class="col-md-3">
        <label for="searchItem">Add via Serial Number:</label>
        <input type="text" class="form-control" id="searchItem" placeholder="Serial Number">
    </div>
    <div class="col-md-3">
        <label for="searchItem">Add via Property Name:</label>
        <input type="text" class="form-control" id="searschItemPropName" placeholder="Property Name">
    </div>
    <div class="col-md-4">
        <!-- Button that triggers the modal -->
        <button type="button" id="addItems" class="btn btn-primary mt-4" data-toggle="modal" data-target="#itemModal">Manual Add</button>
    </div>
</div>

<!-- Equipment Request Table with DataTables -->
<h5>Equipment List:</h5>
<table id="dataTable" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Type</th>
            <th>Brand/Model</th>
            <th>Serial No.</th>
            <th>Property No.</th>
            <th>Property Name</th>
            <th style="width: 5px;"></th>
        </tr>
    </thead>
    <tbody>
        <!-- Data will be populated here (using DataTables) -->
    </tbody>
</table>

<!-- Signature Section -->
<div class="form-group mb-3">
    <label for="signature">Signature:</label>
    <div id="signature-pad" style="border: 1px solid #ccc; width: 100%; height: 200px;"></div>
</div>

<!-- Footer with Action Buttons -->
<div class="form-group">
    <button type="button" id="clearSignature" class="btn btn-danger">Clear Signature</button>
    <button type="button" id="confirmRequest" class="btn btn-success">Confirm</button>
</div>

<!-- Modal for Manual Item Add -->
<div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemModalLabel">Manual Item Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="modalItemTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Brand/Model</th>
                            <th>Serial No.</th>
                            <th>Property No.</th>
                            <th>Property Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic content will be populated via JavaScript -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Add Selected Items</button>
            </div>
        </div>
    </div>
</div>

<script>

$(document).ready(function () {

// When the "Add Manual" button is clicked
$('#addItems').on('click', function () {
    // Clear existing rows from modal table
    $('#modalItemTable tbody').empty();

    // Fetch data from tbl_inv where inv_status = 1
    $.ajax({
        url: 'pages/admin/fetch_items.php',  // URL of your server-side script
        method: 'GET',
        data: { inv_status: 1 },  // Passing the condition for inv_status
        success: function (data) {
            let items = JSON.parse(data);  // Assuming data is returned in JSON format
            items.forEach(function (item) {
                // Append each item to the modal table
                $('#modalItemTable tbody').append(`
                    <tr>
                        <td>${item.type_name}</td>
                        <td>${item.inv_bnm}</td>
                        <td>${item.inv_serialno}</td>
                        <td>${item.inv_propno}</td>
                        <td>${item.inv_propname}</td>
                        <td><button class="btn btn-success btn-sm">Select</button></td>
                    </tr>
                `);
            });
        },
        error: function (err) {
            console.error('Error fetching items: ', err);
        }
    });
});

});

</script>