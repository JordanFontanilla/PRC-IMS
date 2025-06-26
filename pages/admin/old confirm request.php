
<!-- Confirm Borrow Item Request Modal -->
<div class="modal fade" id="confirmItemRequest" tabindex="-1" aria-labelledby="confirmItemRequestLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Add 'modal-lg' for large size -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmItemRequestLabel">Confirm Equipment Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding-bottom: 20px;"> <!-- Optional: Add extra padding to the body -->
                <form action="">
                    <div class="form-row">
                        <div class="col-8">
                            <label for="borrowerName">Borrower's Name:</label>
                            <input type="text" class="form-control" id="borrowerName" placeholder="LASTNAME, Firstname MI. or Nickname" minlength="3" required>
                        </div>
                        <div class="col-4">
                            <label for="remarks">Remarks:</label>
                            <input type="text" class="form-control" id="remarks" placeholder="Additional remarks">
                        </div>
                    </div>
                    <br>
                    <h6>Are you sure you want to borrow the following items?</h6>
                    <div style="max-height: 250px; overflow-y: auto; position: relative;">
    <table class="table">
        <thead style="position: sticky; top: 0; background-color: #fff; z-index: 1;">
            <tr>
                <th>Type</th>
                <th>Serial No.</th>
                <th>Property No.</th>
                <th>Brand/Model</th>
            </tr>
        </thead>
        <tbody id="selectedItemsBody" style="text-align: center;">
            <!-- Selected items will be dynamically inserted here -->
        </tbody>
    </table>
</div>

                    <hr>
                    <!-- Signature Section Final -->
                    <div class="form-group">
                        <label for="signature" class="text-center d-block"><strong>Write your Signature:</strong></label>
                        <canvas id="signature-pad" style="border: 1px solid #ccc; width: 100%; height: 200px;"></canvas>
                        <input type="hidden" id="signatureData" name="signatureData">
                        <button type="button" id="clearSignature" class="btn btn-danger mt-2">Clear Signature</button>
                        <button type="button" class="btn btn-primary mt-2 ml-auto" id="confirmRequestBtn" style="float: right;">Request Borrow</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
               
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function () {
    let canvas = document.getElementById('signature-pad');
    let ctx = canvas.getContext('2d');
    let isDrawing = false;

    function resizeCanvas() {
        let tempImage = ctx.getImageData(0, 0, canvas.width, canvas.height); // Save existing drawing
        canvas.width = canvas.offsetWidth;  // Reset width
        canvas.height = 200;  // Set height manually
        ctx.putImageData(tempImage, 0, 0); // Restore previous drawing
    }

    // Fix: Resize canvas when modal is fully shown
    $('#confirmItemRequest').on('shown.bs.modal', function () {
        resizeCanvas(); // Ensure proper canvas rendering inside modal
    });

    // Start drawing
    $('#signature-pad').on('mousedown touchstart', function (e) {
        isDrawing = true;
        let rect = canvas.getBoundingClientRect();
        let x = (e.offsetX !== undefined) ? e.offsetX : e.touches[0].clientX - rect.left;
        let y = (e.offsetY !== undefined) ? e.offsetY : e.touches[0].clientY - rect.top;
        ctx.beginPath();
        ctx.moveTo(x, y);
    });

    // Draw
    $('#signature-pad').on('mousemove touchmove', function (e) {
        if (!isDrawing) return;
        let rect = canvas.getBoundingClientRect();
        let x = (e.offsetX !== undefined) ? e.offsetX : e.touches[0].clientX - rect.left;
        let y = (e.offsetY !== undefined) ? e.offsetY : e.touches[0].clientY - rect.top;
        ctx.lineTo(x, y);
        ctx.stroke();
    });

    // Stop drawing
    $(document).on('mouseup touchend', function () {
        isDrawing = false;
    });

    // Clear Signature Button
    $('#clearSignature').on('click', function () {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    });

    // Capture selected items for the request
    var selectedItems = [];
    $(document).on('change', '.info-inv', function() {
        var rowData = {
            id: $(this).data('id'),
            type: $(this).closest('tr').find('td:nth-child(1)').text(),
            serialno: $(this).closest('tr').find('td:nth-child(2)').text(),
            propno: $(this).closest('tr').find('td:nth-child(3)').text(),
            bnm: $(this).closest('tr').find('td:nth-child(4)').text(),
            checked: $(this).prop('checked')
        };

        if (rowData.checked) {
            selectedItems.push(rowData);
        } else {
            selectedItems = selectedItems.filter(item => item.id !== rowData.id);
        }
    });

    // When Confirm button is clicked, populate the modal with selected items
    $('#openConfirmModal').click(function() {
        if (selectedItems.length > 0) {
            var tbody = $('#selectedItemsBody');
            tbody.empty();  // Clear the previous entries

            // Populate the modal with the selected items
            selectedItems.forEach(item => {
                tbody.append(`
                    <tr data-id="${item.id}">
                        <td class="item-type">${item.type}</td>
                        <td class="item-serialno">${item.serialno}</td>
                        <td class="item-propno">${item.propno}</td>
                        <td class="item-bnm">${item.bnm}</td>
                    </tr>
                `);
            });

            // Show the modal
            $('#confirmItemRequest').modal('show');
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'No Items Selected',
                // imageUrl: "pages/admin/oiia.gif",
                // imageWidth: 200,
                // imageHeight: 200,
                text: 'Please select at least one item before confirming.',
            });
        }
    });

       // Handle Request button in the modal using AJAX
       $('#confirmRequestBtn').click(function() {
        var selectedItemsToRequest = [];
        $('#selectedItemsBody tr').each(function() {
            var row = $(this);
            var item = {
                id: row.data('id'),
                type: row.find('.item-type').text(),
                serialno: row.find('.item-serialno').text(),
                propno: row.find('.item-propno').text(),
                brand: row.find('.item-bnm').text()
            };
            selectedItemsToRequest.push(item);
        });

        // Get the signature data (Base64 image)
        var signatureData = canvas.toDataURL('image/png');
        var borrowerName = $('#borrowerName').val().trim();
        var borrowRemarks = $('#remarks').val().trim();

        // Validate borrower name and remarks
        if (!borrowerName) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please provide a borrower name.',
            });
            return;
        }

        if (!borrowRemarks) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please provide a remark for your request.',
            });
            return;
        }

        if (selectedItemsToRequest.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No items selected to borrow.',
            });
            return;
        }


        // Confirmation alert before proceeding
        Swal.fire({
            title: "Confirm Request?",
            text: "Do you want to request to borrow these items?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, request it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with AJAX request
                $.ajax({
                    url: 'pages/admin/process_borrow_request.php',
                    method: 'POST',
                    data: {
                        selected_items: selectedItemsToRequest,
                        borrower_name: borrowerName,
                        borrower_remark: borrowRemarks,
                        signature_data: signatureData
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Request Submitted!',
                            text: 'Your request has been submitted. Wait for Admin\'s confirmation.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            $('#borrowRequestModal').modal('hide'); // Hide modal
                            $('.modal-backdrop').remove(); // Remove backdrop
                            $('body').removeClass('modal-open'); // Remove modal-open class
                            location.reload(); // Refresh page
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Submission Failed',
                            text: 'There was an error submitting your request.',
                        });
                        console.log("AJAX error:", status, error);
                    }
                });
            }
        });
    });
});
</script>
<!-- End Confirm Item Request Modal -->
