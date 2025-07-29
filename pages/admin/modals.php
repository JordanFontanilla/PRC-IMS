<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" action ="POST"> 
                    <!-- First Row: User Level and Username -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="userLevel" class="font-weight-bold">User Level</label>
                            <select class="form-control" id="userLevel" required>
                                <option value="" disabled selected>Select Level</option>
                                <option value="Admin">Admin</option>
                                <option value="Employee">Employee</option>
                            </select>
                
                        </div>
                        <div class="form-group col-md-6">
                            <label for="username" class="font-weight-bold">Username</label>
                            <input type="text" class="form-control" id="username" placeholder="Enter Username" required>
                        </div>
                    </div>
                    <!-- Second Row: Password and Confirm Password -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="password" class="font-weight-bold">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" placeholder="Enter Password" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary toggle-password" type="button" data-target="password">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="confirmPassword" class="font-weight-bold">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm Password" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary toggle-password" type="button" data-target="confirmPassword">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!-- Third Row: Last Name, Middle Initial, First Name -->
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="lastName" class="font-weight-bold">Last Name</label>
                            <input type="text" class="form-control" id="lastName" placeholder="Enter Last Name" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="firstName" class="font-weight-bold">First Name</label>
                            <input type="text" class="form-control" id="firstName" placeholder="Enter First Name" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="middleInitial" class="font-weight-bold">Middle Initial</label>
                            <input type="text" class="form-control text-center" id="middleInitial" placeholder="MI" maxlength="1" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <hr>
                    <!-- Fourth Row: Email and Phone Number -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="email" class="font-weight-bold">Email <span class="text-muted">(Optional)</span></label>
                            <input type="email" class="form-control" id="email" placeholder="Enter Email">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phoneNumber" class="font-weight-bold">Phone Number <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control" id="phoneNumber" placeholder="Enter Phone Number">
                        </div>
                    </div>
                    <hr>
                    <!-- Fifth Row: Address -->
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="address" class="font-weight-bold">Address <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control" id="address" placeholder="Enter Address">
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary btn-block">Create User</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- jQuery Script for Toggle Password Visibility -->
<!-- jQuery Script for Password Validation and Add User -->
<script>
    $(document).ready(function () {
        // Toggle password visibility
        $(".toggle-password").click(function () {
            let target = $(this).data("target");
            let input = $("#" + target);
            let icon = $(this).find("i");

            if (input.attr("type") === "password") {
                input.attr("type", "text");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            } else {
                input.attr("type", "password");
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            }
        });

        // Form submission validation and AJAX request
        $("#addUserForm").submit(function (event) {
            event.preventDefault(); // Prevent form submission

            let password = $("#password").val();
            let confirmPassword = $("#confirmPassword").val();

            if (password !== confirmPassword) {
                Swal.fire({
                    icon: "error",
                    title: "Password Mismatch",
                    text: "Passwords do not match. Please try again.",
                });
                return;
            }

            // Collect form data
            var formData = {
                userLevel: $("#userLevel").val(),
                username: $("#username").val(),
                password: password,
                lastName: $("#lastName").val(),
                firstName: $("#firstName").val(),
                middleInitial: $("#middleInitial").val(),
            };

            // Log collected data to the console
            console.log("Form Data Submitted:", formData);

            // Send data to the server using AJAX
            $.ajax({
                url: "pages/admin/process_adduser.php", // Adjust to your actual processing file
                type: "POST",
                data: formData, // Send data as form data
                success: function (response) {
                    if (response === "success") {
                        Swal.fire({
                            icon: "success",
                            title: "User Created",
                            text: "The user has been successfully added!",
                            timer: 2000,
                            timerProgressBar: true
                        }).then(() => {
                            location.reload(); // Reload page or update table dynamically
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: response, // Display the error message from server (e.g., "Username already exists")
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX request failed:", status, error);
                    Swal.fire({
                        icon: "error",
                        title: "AJAX Error",
                        text: "There was an issue with the request. Please try again.",
                    });
                }
            });
        });
    });
</script>
<!-- End of Add User Modal -->
<!-- Add User u Modal -->
<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1" role="dialog" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="viewUserModalLabel">User Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="viewUserForm">
                    <!-- First Row: User Level and Username -->
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-6 text-center">
                            <label for="viewUserLevel" class="font-weight-bold">User Level</label>
                            <input type="text" class="form-control text-center" id="viewUserLevel" readonly>
                        </div>
                        <div class="form-group col-md-6 text-center">
                            <label for="viewUsername" class="font-weight-bold">Username</label>
                            <input type="text" class="form-control text-center" id="viewUsername" readonly>
                        </div>
                    </div>
                    <!-- Second Row: Status (Centered) -->
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-6 text-center">
                            <label for="viewUserStatus" class="font-weight-bold">Status</label>
                            <input type="text" class="form-control text-center" id="viewUserStatus" readonly>
                        </div>
                    </div>
                    <hr>
                    <!-- Third Row: Full Name (Centered) -->
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-9 text-center">
                            <label for="viewFullName" class="font-weight-bold">Full Name</label>
                            <input type="text" class="form-control text-center" id="viewFullName" readonly>
                        </div>
                    </div>
                    <!-- Password Row: Only for Admins -->
                    <div class="form-row justify-content-center" id="viewPasswordRow" style="display: none;">
                        <div class="form-group col-md-9 text-center">
                            <label for="viewPassword" class="font-weight-bold">Password (Hashed)</label>
                            <input type="text" class="form-control text-center" id="viewPassword" readonly>
                        </div>
                    </div>
                    <hr>
                    <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).on('click', '.view-user', function () {
    var username = $(this).data("username");

    $.ajax({
        url: "pages/admin/fetch_user_details.php",
        method: "POST",
        data: { username: username },
        dataType: "json",
        success: function (response) {
            console.log(response); // Log the response for debugging
            if (response.error) {
                alert(response.error);
            } else {
                $("#viewUsername").val(response.username);
                $("#viewUserLevel").val(response.user_level);
                $("#viewFullName").val(response.full_name);
                $("#viewUserStatus").val(response.user_status);

                if (response.is_admin_viewer && response.user_password) {
                    $("#viewPassword").val(response.user_password);
                    $("#viewPasswordRow").show();
                } else {
                    $("#viewPasswordRow").hide();
                }

                $("#viewUserModal").modal("show");
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error, xhr.responseText);
            alert("Error fetching user data. Check the console for details.");
        },
    });
});
</script>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="editUserModalLabel">Edit User Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <!-- User Level and Username -->
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-6 text-center">
                            <label for="editUserLevel" class="font-weight-bold">User Level</label>
                            <select class="form-control text-center" id="editUserLevel">
                                <option value="Admin">Admin</option>
                                <option value="Employee">Employee</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 text-center">
                            <label for="editUsername" class="font-weight-bold">Username</label>
                            <input type="text" class="form-control text-center" id="editUsername" readonly>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-6 text-center">
                            <label for="editUserStatus" class="font-weight-bold">Status</label>
                            <select class="form-control text-center" id="editUserStatus">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <!-- Full Name (3 separate fields) -->
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-4 text-center">
                            <label for="editLastName" class="font-weight-bold">Last Name</label>
                            <input type="text" class="form-control text-center" id="editLastName">
                        </div>
                        <div class="form-group col-md-4 text-center">
                            <label for="editFirstName" class="font-weight-bold">First Name</label>
                            <input type="text" class="form-control text-center" id="editFirstName">
                        </div>
                        <div class="form-group col-md-2 text-center">
                            <label for="editMiddleInitial" class="font-weight-bold">M.I.</label>
                            <input type="text" class="form-control text-center" id="editMiddleInitial" maxlength="1">
                        </div>
                    </div>

                    <hr>

                    <button type="button" class="btn btn-success btn-block" id="saveUserChanges">Save Changes</button>
                    <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).on('click', '.edit-user', function() {
    var username = $(this).data('id');

    $.ajax({
        url: 'pages/admin/fetch_user_details.php',
        type: 'POST',
        data: { username: username },
        dataType: 'json',
        success: function(response) {
            if (response.error) {
                alert(response.error);
            } else {
                const nameParts = response.full_name.split(' ');
                $('#editUsername').val(response.username);
                $('#editUserLevel').val(response.user_level);
                $('#editUserStatus').val(response.user_status === 'ACTIVE' ? '1' : '0');

                $('#editLastName').val(nameParts[0] || '');
                $('#editFirstName').val(nameParts[1] || '');
                $('#editMiddleInitial').val(nameParts[2] || '');
                
                $('#editUserModal').modal('show');
            }
        }
    });
});


$(document).on('click', '#saveUserChanges', function() {
    var username = $('#editUsername').val(); // Assuming username is still in the modal but hidden or readonly

    $.ajax({
        url: 'pages/admin/process_updateuserdetails.php',
        type: 'POST',
        data: {
            username: username, // Include the username
            user_level: $('#editUserLevel').val(),
            user_status: $('#editUserStatus').val(),
            last_name: $('#editLastName').val(),
            first_name: $('#editFirstName').val(),
            middle_initial: $('#editMiddleInitial').val()
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert('User details updated successfully!');
                $('#editUserModal').modal('hide');
                location.reload(); // Refresh the page to reflect the changes
            } else {
                alert('Error: ' + response.error);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            console.log('Response Text:', xhr.responseText); // Log the server response text
            alert('An error occurred while updating the user details.');
        }
    });
});
</script>


<!-- Add Consumable Equipment Modal -->
    <div class="modal fade" id="addConsumableEquipModal" tabindex="-1" role="dialog" aria-labelledby="addConsumableEquipModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addConsumableEquipModalLabel">Add New Consumable Equipment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addConsumableEquipForm">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="stock_number" class="font-weight-bold">Stock Number <span class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" id="stock_number" name="stock_number" placeholder="Enter Stock Number">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="acceptance_date" class="font-weight-bold">Acceptance Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="acceptance_date" name="acceptance_date" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="ris_no" class="font-weight-bold">RIS No. <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ris_no" name="ris_no" placeholder="Enter RIS No." required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="item_description" class="font-weight-bold">Item Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="item_description" name="item_description" rows="3" placeholder="Enter Item Description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="unit" class="font-weight-bold">Unit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="unit" name="unit" placeholder="e.g., pcs, box, ream" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="receipt" class="font-weight-bold">Receipt <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="receipt" name="receipt" min="0" value="0" required placeholder="Enter Receipt Quantity">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="issuance" class="font-weight-bold">Issuance <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="issuance" name="issuance" min="0" value="0" required placeholder="Enter Issuance Quantity">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="end_user_issuance" class="font-weight-bold">End User of Issuance <span class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" id="end_user_issuance" name="end_user_issuance" placeholder="Enter End User">
                            </div>
                        </div>
                        
                        <hr>
                        <button id="submitConsumable" type="button" class="btn btn-primary btn-block">
                            <i class="fas fa-plus"></i> Add Consumable Equipment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Non-Consumable Equipment Modal -->
    <div class="modal fade" id="addNonConsumableEquipModal" tabindex="-1" role="dialog" aria-labelledby="addNonConsumableEquipModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNonConsumableEquipModalLabel">Add Non-Consumable Equipment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addNonConsumableEquipForm">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="ninv_type" class="font-weight-bold">Type <span class="text-danger">*</span></label>
                                <select class="form-control" id="ninv_type" name="type_id" required>
                                    <option value="" disabled selected>Select Type</option>
                                    <!-- PHP code would populate this -->
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="ninv_bnm" class="font-weight-bold">Brand/Model <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ninv_bnm" name="inv_bnm" placeholder="ex: MSI GF63" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="ninv_serialno" class="font-weight-bold">Serial Number <span class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" id="ninv_serialno" name="inv_serialno" placeholder="Enter Serial Number">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="ninv_propno" class="font-weight-bold">Property Number <span class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" id="ninv_propno" name="inv_propno" placeholder="Enter Property Number">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="ninv_propname" class="font-weight-bold">Property Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ninv_propname" name="inv_propname" placeholder="Enter Property Name" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="ninv_status" class="font-weight-bold">Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="ninv_status" name="inv_status" required>
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="ncondition" class="font-weight-bold">Condition <span class="text-muted">(Optional)</span></label>
                                <select class="form-control" id="ncondition" name="condition">
                                    <option value="" selected disabled>Select Condition</option>
                                    <option value="New">New</option>
                                    <option value="Good">Good</option>
                                    <option value="Fair">Fair</option>
                                    <option value="Poor">Poor</option>
                                    <option value="For Repair">For Repair</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="ninv_quantity" class="font-weight-bold">Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="ninv_quantity" name="inv_quantity" min="1" value="1" required placeholder="Enter Quantity">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nprice" class="font-weight-bold">Price <span class="text-muted">(Optional)</span></label>
                                <input type="number" class="form-control" id="nprice" name="price" step="0.01" min="0" value="0.00" placeholder="0.00">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="ndate_acquired" class="font-weight-bold">Date Acquired <span class="text-muted">(Optional)</span></label>
                                <input type="date" class="form-control" id="ndate_acquired" name="date_acquired">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nend_user" class="font-weight-bold">End User <span class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" id="nend_user" name="end_user" placeholder="Enter End User Name">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="naccounted_to" class="font-weight-bold">Accounted To <span class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" id="naccounted_to" name="accounted_to" placeholder="Enter Accountable Person">
                            </div>
                        </div>
                        <hr>
                        <button id="submitNonConsumable" type="button" class="btn btn-primary btn-block">
                            <i class="fas fa-plus"></i> Add Non-Consumable Equipment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Function to load equipment types into a dropdown
        function loadEquipmentTypes(modalType, origin) {
            var dropdownId = (modalType === 'consumable') ? '#cinv_type' : '#ninv_type';
            var url = 'pages/admin/get_equipment_types.php?origin=' + origin;

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        var dropdown = $(dropdownId);
                        dropdown.empty(); // Clear existing options
                        dropdown.append('<option value="" disabled selected>Select Type</option>');
                        
                        response.types.forEach(function(type) {
                            dropdown.append('<option value="' + type.type_id + '">' + type.type_name + '</option>');
                        });
                    } else {
                        console.error('Failed to load types:', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                }
            });
        }

        // Load types when the non-consumable modal is shown
        $('#addNonConsumableEquipModal').on('show.bs.modal', function() {
            loadEquipmentTypes('non-consumable', 'Non-Consumable');
        });

        // Consumable Equipment Submit
        $(document).on('click', '#submitConsumable', function(e) {
            e.preventDefault();

            console.log("Submit consumable button clicked!");

            var formData = {
                stock_number: $('#stock_number').val(),
                acceptance_date: $('#acceptance_date').val(),
                ris_no: $('#ris_no').val(),
                item_description: $('#item_description').val(),
                unit: $('#unit').val(),
                receipt: $('#receipt').val(),
                issuance: $('#issuance').val(),
                end_user_issuance: $('#end_user_issuance').val()
            };

            console.log("Collected Consumable Form Data:", formData);

            // Validate required fields
            var missingFields = [];
            
            if (!formData.acceptance_date) missingFields.push("Acceptance Date");
            if (!formData.ris_no) missingFields.push("RIS No.");
            if (!formData.item_description) missingFields.push("Item Description");

            if (missingFields.length > 0) {
                Swal.fire({
                    title: "Missing Required Fields",
                    text: "Please fill in the following required fields: " + missingFields.join(", "),
                    icon: "warning"
                });
                return;
            }

            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to add this consumable equipment?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, add it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading indicator
                    Swal.fire({
                        title: "Adding Equipment...",
                        text: "Please wait while the equipment is being added.",
                        icon: "info",
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        type: 'POST',
                        url: 'pages/admin/process_addconsumable.php',
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            console.log("AJAX Response:", response);

                            if (response.status === 'success') {
                                Swal.fire({
                                    title: "Added!",
                                    text: response.message,
                                    icon: "success",
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Reset the form
                                    $('#addConsumableEquipForm')[0].reset();
                                    
                                    // Close modal properly
                                    $('#addConsumableEquipModal').modal('hide');
                                    $('.modal-backdrop').remove();
                                    $('body').removeClass('modal-open');
                                    
                                    // Reload page to show new data
                                    location.reload();
                                });

                            } else if (response.status === 'confirm_duplicate') {
                                let duplicateFields = response.duplicates.join(', ');

                                Swal.fire({
                                    title: "Some Fields Already Exist",
                                    text: "The following fields already exist: " + duplicateFields + ".\nDo you still want to add this equipment?",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#3085d6",
                                    cancelButtonColor: "#d33",
                                    confirmButtonText: "Yes, add anyway"
                                }).then((secondResult) => {
                                    if (secondResult.isConfirmed) {
                                        // Show loading again
                                        Swal.fire({
                                            title: "Adding Equipment...",
                                            text: "Please wait while the equipment is being added.",
                                            icon: "info",
                                            allowOutsideClick: false,
                                            showConfirmButton: false,
                                            didOpen: () => {
                                                Swal.showLoading();
                                            }
                                        });

                                        $.ajax({
                                            type: 'POST',
                                            url: 'pages/admin/process_addconsumable.php',
                                            data: {...formData, force_insert: true},
                                            dataType: 'json',
                                            success: function(forceResponse) {
                                                console.log("Force Insert Response:", forceResponse);
                                                
                                                if (forceResponse.status === 'success') {
                                                    Swal.fire({
                                                        title: "Added!",
                                                        text: forceResponse.message,
                                                        icon: "success",
                                                        timer: 1500,
                                                        showConfirmButton: false
                                                    }).then(() => {
                                                        // Reset the form
                                                        $('#addConsumableEquipForm')[0].reset();
                                                        
                                                        // Close modal properly
                                                        $('#addConsumableEquipModal').modal('hide');
                                                        $('.modal-backdrop').remove();
                                                        $('body').removeClass('modal-open');
                                                        
                                                        // Reload page to show new data
                                                        location.reload();
                                                    });
                                                } else {
                                                    Swal.fire({
                                                        title: "Error!",
                                                        text: forceResponse.message || "Unknown error occurred.",
                                                        icon: "error"
                                                    });
                                                }
                                            },
                                            error: function(xhr, status, error) {
                                                console.error("AJAX Error (Force Insert):", xhr.responseText, status, error);
                                                Swal.fire({
                                                    title: "Error!",
                                                    text: "Error in submitting form. Please check the console for details.",
                                                    icon: "error"
                                                });
                                            }
                                        });
                                    }
                                });

                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: response.message || "Unknown error occurred.",
                                    icon: "error"
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", status, error, xhr.responseText);
                            Swal.fire({
                                title: "Error!",
                                text: "Error in submitting form. Please check the console for details. Server response: " + xhr.responseText,
                                icon: "error"
                            });
                        }
                    });
                }
            });
        });

        // Non-Consumable Equipment Submit
        $(document).on('click', '#submitNonConsumable', function(e) {
            e.preventDefault();

            var formData = {
                type_id: $('#ninv_type').val(),
                inv_bnm: $('#ninv_bnm').val().trim(),
                inv_serialno: $('#ninv_serialno').val().trim(),
                inv_propno: $('#ninv_propno').val().trim(),
                inv_propname: $('#ninv_propname').val().trim(),
                inv_status: $('#ninv_status').val(),
                condition: $('#ncondition').val(),
                inv_quantity: $('#ninv_quantity').val() || 1,
                price: $('#nprice').val() || 0.00,
                date_acquired: $('#ndate_acquired').val(),
                end_user: $('#nend_user').val().trim(),
                accounted_to: $('#naccounted_to').val().trim()
            };

            console.log("Collected Non-Consumable Form Data:", formData);

            // Validate required fields
            var missingFields = [];
            
            if (!formData.type_id) missingFields.push("Equipment Type");
            if (!formData.inv_bnm) missingFields.push("Brand/Model");
            if (!formData.inv_propname) missingFields.push("Property Name");
            if (!formData.inv_status) missingFields.push("Status");
            if (!formData.inv_quantity || formData.inv_quantity <= 0) missingFields.push("Quantity (must be greater than 0)");

            if (missingFields.length > 0) {
                Swal.fire({
                    title: "Missing Required Fields",
                    text: "Please fill in the following required fields: " + missingFields.join(", "),
                    icon: "warning"
                });
                return;
            }

            // Validate quantity is a positive number
            if (isNaN(formData.inv_quantity) || parseFloat(formData.inv_quantity) <= 0) {
                Swal.fire({
                    title: "Invalid Quantity",
                    text: "Quantity must be a positive number.",
                    icon: "warning"
                });
                return;
            }

            // Validate price is a valid number (if provided)
            if (formData.price && (isNaN(formData.price) || parseFloat(formData.price) < 0)) {
                Swal.fire({
                    title: "Invalid Price",
                    text: "Price must be a valid number (0 or greater).",
                    icon: "warning"
                });
                return;
            }

            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to add this non-consumable equipment?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, add it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    submitNonConsumableEquipment(formData);
                }
            });
        });

        function submitNonConsumableEquipment(formData, forceInsert = false) {
            // Add force_insert flag if this is a forced submission
            if (forceInsert) {
                formData.force_insert = true;
            }

            // Show loading indicator
            Swal.fire({
                title: "Adding Equipment...",
                text: "Please wait while the equipment is being added.",
                icon: "info",
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                type: 'POST',
                url: 'pages/admin/process_addequipment_nonconsumable.php',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    console.log("AJAX Response:", response);

                    if (response.status === 'warning') {
                        Swal.fire({
                            title: "Possible Duplicate",
                            text: response.message,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Yes, add anyway",
                            cancelButtonText: "Cancel"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Retry with force_insert = true
                                submitNonConsumableEquipment(formData, true);
                            }
                        });
                    } else if (response.status === 'success') {
                        Swal.fire({
                            title: "Added!",
                            text: response.message,
                            icon: "success",
                            timer: 1200,
                            showConfirmButton: false
                        }).then(() => {
                            // Reset the form
                            $('#addNonConsumableEquipForm')[0].reset();
                            
                            // Close modal properly
                            $('#addNonConsumableEquipModal').modal('hide');
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                            
                            // Reload page to show new data
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: response.message,
                            icon: "error"
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", xhr, status, error);
                    Swal.fire({
                        title: "Error!",
                        text: "Error in submitting form.",
                        icon: "error"
                    });
                }
            });
        }
    });
    </script>
    
<!-- Import Equipment Modal -->
<div class="modal fade" id="importEquipModal" tabindex="-1" role="dialog" aria-labelledby="importEquipModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importEquipModalLabel">Import Equipment List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="importForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="excelFile">Choose Excel File</label>
                        <input type="file" name="excelFile" id="excelFile" class="form-control" required accept=".xlsx, .xls">
                    </div>
                    <div class="form-group">
                        <label>Item Type:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="importItemType" id="importNonConsumableRadio" value="Non-Consumable" checked>
                            <label class="form-check-label" for="importNonConsumableRadio">Non-Consumable</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="importItemType" id="importConsumableRadio" value="Consumable">
                            <label class="form-check-label" for="importConsumableRadio">Consumable</label>
                        </div>
                    </div>
                    <!-- Format Instructions -->
                    <div id="nonConsumableFormat" class="mt-2">
                        <small class="form-text text-muted">
                            <strong>Non-Consumable Format:</strong><br>
                            Columns: <strong>Type ID, Serial No, Property No, Property Name, Brand/Model, End User, Accounted To</strong>.<br>
                            Data must start from <strong>row 2</strong> (row 1 is for headers).
                        </small>
                    </div>
                    <div id="consumableFormat" class="mt-2" style="display: none;">
                        <small class="form-text text-muted">
                            <strong>Consumable Format:</strong><br>
                            Columns: <strong>Stock No., Acceptance Date, RIS No., Item Description, Receipt, Issuance, End User, Unit</strong>.<br>
                            Data must start from <strong>row 5</strong> (first 4 rows are for headers).
                        </small>
                    </div>
                    <div class="form-group mt-3">
                        <label for="excelSheet">Choose Sheet</label>
                        <div class="input-group">
                            <select name="excelSheet" id="excelSheet" class="form-control" required disabled>
                                <option value="">Select a file first</option>
                            </select>
                            <div class="input-group-append" id="sheetLoadingSpinner" style="display: none;">
                                <span class="input-group-text">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" disabled>Import</button>
                </form>
                <div id="importStatus" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    const $importModal = $('#importEquipModal');
    const $importForm = $('#importForm');
    const $fileInput = $('#excelFile');
    const $sheetSelect = $('#excelSheet');
    const $submitBtn = $importForm.find('button[type="submit"]');
    const $spinner = $('#sheetLoadingSpinner');
    const $nonConsumableFormat = $('#nonConsumableFormat');
    const $consumableFormat = $('#consumableFormat');

    // Function to toggle format instructions
    function toggleFormatInstructions() {
        if ($('input[name="importItemType"]:checked').val() === 'Consumable') {
            $consumableFormat.show();
            $nonConsumableFormat.hide();
        } else {
            $nonConsumableFormat.show();
            $consumableFormat.hide();
        }
    }

    // Handle item type change
    $('input[name="importItemType"]').on('change', toggleFormatInstructions);

    // Handle file input change to fetch sheet names
    $fileInput.on('change', function() {
        if (this.files.length === 0) {
            $sheetSelect.html('<option value="">Select a file first</option>').prop('disabled', true);
            $submitBtn.prop('disabled', true);
            return;
        }

        const formData = new FormData();
        formData.append('excelFile', this.files[0]);

        $spinner.show();
        $sheetSelect.prop('disabled', true);

        $.ajax({
            url: 'pages/admin/fetch_sheets.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    Swal.fire('Error', response.error, 'error');
                    $sheetSelect.html('<option value="">Could not load sheets</option>');
                    $submitBtn.prop('disabled', true);
                } else {
                    $sheetSelect.empty();
                    response.sheets.forEach(function(sheetName) {
                        $sheetSelect.append(`<option value="${sheetName}">${sheetName}</option>`);
                    });
                    $sheetSelect.prop('disabled', false);
                    $submitBtn.prop('disabled', false);
                }
            },
            error: function() {
                Swal.fire('Error', 'Could not retrieve sheet names from the server.', 'error');
                $sheetSelect.html('<option value="">Error loading sheets</option>');
                $submitBtn.prop('disabled', true);
            },
            complete: function() {
                $spinner.hide();
            }
        });
    });

    // Handle form submission
    $importForm.on('submit', function(e) {
        e.preventDefault();

        if ($fileInput[0].files.length === 0) {
            Swal.fire('Warning', 'Please select a file first.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Importing...',
            text: 'Please wait while the data is being imported.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const formData = new FormData(this);

        $.ajax({
            url: 'pages/admin/process_importfile.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    Swal.fire('Import Failed', response.error, 'error');
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Import Successful',
                        text: `Inserted ${response.inserted} records. Found ${response.redundant} duplicate records.`,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                let errorMsg = 'An unexpected error occurred.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                }
                Swal.fire('Import Failed', errorMsg, 'error');
            }
        });
    });

    // Reset modal on close
    $importModal.on('hidden.bs.modal', function() {
        $importForm[0].reset();
        $sheetSelect.html('<option value="">Select a file first</option>').prop('disabled', true);
        $submitBtn.prop('disabled', true);
        toggleFormatInstructions(); // Reset to default view
    });

    // Initial setup
    toggleFormatInstructions();
});
</script>

<!-- END OF IMPORT EQUIPMENT -->


<!-- View Equipment Modal -->
<div class="modal fade" id="viewEquipModal" tabindex="-1" role="dialog" aria-labelledby="viewEquipModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> <!-- modal-lg for wider modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewEquipModalLabel">Equipment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Side-by-side layout -->
            <div class="modal-body">
                <div class="row">
                    <!-- Left side: Equipment table -->
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tr><th>Type</th><td id="equipType"></td></tr>
                            <tr><th>Brand/Model</th><td id="equipBrand"></td></tr>
                            <tr><th>Serial No.</th><td id="equipSerial"></td></tr>
                            <tr><th>Property No.</th><td id="equipPropNo"></td></tr>
                            <tr><th>Division/Section</th><td id="equipPropName"></td></tr>
                            <tr><th>Status</th><td id="equipStatus"></td></tr>
                            <tr><th>Date Added</th><td id="equipDateAdded"></td></tr>
                            <tr><th>End User</th><td id="equipenduser"></td></tr>
                            <tr><th>Accounted to</th><td id="equipaccountedto"></td></tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $(document).on('click', '.info-inv', function() {
        var inv_id = $(this).data('id');
        var origin = $(this).data('origin'); // Get the origin (consumable/non_consumable)
        
        $.ajax({
            url: 'pages/admin/fetch_inventorydetails.php', // Endpoint to fetch details
            type: 'POST',
            data: { inv_id: inv_id, item_origin: origin },
            dataType: 'json',
            success: function(response) {
                console.log("Response from fetch_inventorydetails.php:", response);
                if (response.success) {
                    if (response.data.item_origin === 'consumable') {
                        $('#equipType').text(response.data.type_name || 'N/A');
                        $('#equipBrand').text(response.data.item_description || 'N/A');
                        $('#equipSerial').text(response.data.stock_number || 'N/A'); // Use stock_number for serial
                        $('#equipPropNo').text(response.data.ris_no || 'N/A');
                        $('#equipPropName').text(response.data.unit || 'N/A'); // Use unit for property name
                        $('#equipStatus').text('N/A'); // Consumables don't have inv_status
                        $('#equipDateAdded').text(response.data.acceptance_date || 'N/A');
                        $('#equipenduser').text(response.data.end_user_issuance || 'N/A');
                        $('#equipaccountedto').text('N/A'); // Consumables don't have accounted_to
                    } else {
                        $('#equipType').text(response.data.type_name);
                        $('#equipBrand').text(response.data.inv_bnm);
                        $('#equipSerial').text(response.data.inv_serialno);
                        $('#equipPropNo').text(response.data.inv_propno);
                        $('#equipPropName').text(response.data.inv_propname);
                        $('#equipStatus').text(response.data.status);
                        $('#equipDateAdded').text(response.data.inv_date_added);
                        $('#equipenduser').text(response.data.end_user);
                        $('#equipaccountedto').text(response.data.accounted_to);
                    }
                } else {
                    alert('Error fetching data.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error fetching details:', status, error, xhr.responseText);
                alert('Failed to fetch details. Check console for more info.');
            }
        });
    });
});
</script>
<!-- view equipment modal end -->


<!-- Edit Equipment Modal -->
<div class="modal fade" id="editEquipModal" tabindex="-1" role="dialog" aria-labelledby="editEquipModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEquipModalLabel">Edit Equipment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editEquipForm">
                    <input type="hidden" id="editequipId">
                    <input type="hidden" id="editequipOrigin">

                    <!-- Non-Consumable Fields -->
                    <div id="nonConsumableFields">
                        <table class="table table-bordered">
                            <tr>
                                <th>Type</th>
                                <td>
                                    <select id="editequipType" class="form-control"></select>
                                </td>
                            </tr>
                            <tr>
                                <th>Brand/Model</th>
                                <td><input type="text" id="editequipBrand" class="form-control" /></td>
                            </tr>
                            <tr>
                                <th>Serial No.</th>
                                <td><input type="text" id="editequipSerial" class="form-control" /></td>
                            </tr>
                            <tr>
                                <th>Property No.</th>
                                <td><input type="text" id="editequipPropNo" class="form-control" /></td>
                            </tr>
                            <tr>
                                <th>Division/Section</th>
                                <td><input type="text" id="editequipPropName" class="form-control" /></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <select id="editequipStatus" class="form-control">
                                        <option value="1">Available</option>
                                        <option value="2">Unavailable</option>
                                        <option value="3">Pending For Approval</option>
                                        <option value="4">Borrowed</option>
                                        <option value="5">Returned</option>
                                        <option value="6">Missing</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Date Added</th>
                                <td><input type="text" id="editequipDateAdded" class="form-control" disabled /></td>
                            </tr>
                            <tr>
                                <th>End User</th>
                                <td><input type="text" id="editequipenduser" class="form-control" /></td>
                            </tr>
                            <tr>
                                <th>Accountable Personel</th>
                                <td><input type="text" id="editequipaccountedto" class="form-control" /></td>
                            </tr>
                        </table>
                    </div>

                    <!-- Consumable Fields -->
                    <div id="consumableFields" style="display: none;">
                        <table class="table table-bordered">
                            <tr>
                                <th>Stock Number</th>
                                <td><input type="text" id="editStockNumber" class="form-control" /></td>
                            </tr>
                            <tr>
                                <th>Acceptance Date</th>
                                <td><input type="date" id="editAcceptanceDate" class="form-control" /></td>
                            </tr>
                            <tr>
                                <th>RIS No.</th>
                                <td><input type="text" id="editRisNo" class="form-control" /></td>
                            </tr>
                            <tr>
                                <th>Item Description</th>
                                <td><textarea id="editItemDescription" class="form-control"></textarea></td>
                            </tr>
                            <tr>
                                <th>Unit</th>
                                <td><input type="text" id="editUnit" class="form-control" /></td>
                            </tr>
                            <tr>
                                <th>Receipt</th>
                                <td><input type="number" id="editReceipt" class="form-control" /></td>
                            </tr>
                            <tr>
                                <th>Issuance</th>
                                <td><input type="number" id="editIssuance" class="form-control" /></td>
                            </tr>
                            <tr>
                                <th>End User of Issuance</th>
                                <td><input type="text" id="editEndUserIssuance" class="form-control" /></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveChangesBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
// Load types into dropdown (for non-consumables)
function loadTypeOptions(selectedTypeId) {
    $.ajax({
        url: 'pages/admin/fetch_alltypes.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            var $select = $('#editequipType');
            $select.empty();
            data.forEach(function(type) {
                const selected = (type.type_id == selectedTypeId) ? 'selected' : '';
                $select.append(`<option value="${type.type_id}" ${selected}>${type.type_name}</option>`);
            });
        },
        error: function() {
            alert('Error loading type options.');
        }
    });
}

// Handle edit button click
$(document).on('click', '.edit-inv', function() {
    var invId = $(this).data('id');
    var origin = $(this).data('origin'); // Get the origin (consumable/non_consumable)
    
    $('#editequipId').val(invId);
    $('#editequipOrigin').val(origin);

    $.ajax({
        url: 'pages/admin/fetch_inventorydetails.php',
        method: 'POST',
        data: { inv_id: invId, item_origin: origin },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                if (response.data.item_origin === 'consumable') {
                    $('#nonConsumableFields').hide();
                    $('#consumableFields').show();

                    $('#editStockNumber').val(response.data.stock_number);
                    $('#editAcceptanceDate').val(response.data.acceptance_date);
                    $('#editRisNo').val(response.data.ris_no);
                    $('#editItemDescription').val(response.data.item_description);
                    $('#editUnit').val(response.data.unit);
                    $('#editReceipt').val(response.data.receipt);
                    $('#editIssuance').val(response.data.issuance);
                    $('#editEndUserIssuance').val(response.data.end_user_issuance);
                } else {
                    $('#consumableFields').hide();
                    $('#nonConsumableFields').show();

                    $('#editequipBrand').val(response.data.inv_bnm);
                    $('#editequipSerial').val(response.data.inv_serialno);
                    $('#editequipPropNo').val(response.data.inv_propno);
                    $('#editequipPropName').val(response.data.inv_propname);
                    $('#editequipStatus').val(getStatusValue(response.data.status));
                    $('#editequipDateAdded').val(response.data.inv_date_added);
                    $('#editequipenduser').val(response.data.end_user);
                    $('#editequipaccountedto').val(response.data.accounted_to);
                    loadTypeOptions(response.data.type_id);
                }
                $('#editEquipModal').modal('show');
            } else {
                alert('Failed to fetch inventory details: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error fetching details:', status, error, xhr.responseText);
            alert('Failed to fetch details. Check console for more info.');
        }
    });
});

function getStatusValue(statusText) {
    var statusMap = {
        'Available': 1,
        'Unavailable': 2,
        'Pending For Approval': 3,
        'Borrowed': 4,
        'Returned': 5,
        'Missing': 6,
        'In Use': 7
    };
    return statusMap[statusText] || 1;
}

$(document).on('click', '#saveChangesBtn', function() {
    var invId = $('#editequipId').val();
    var origin = $('#editequipOrigin').val();

    if (origin === 'consumable') {
        var formData = {
            inv_id: invId,
            acceptance_date: $('#editAcceptanceDate').val(),
            ris_no: $('#editRisNo').val(),
            item_description: $('#editItemDescription').val(),
            unit: $('#editUnit').val(),
            receipt: $('#editReceipt').val(),
            issuance: $('#editIssuance').val(),
            end_user_issuance: $('#editEndUserIssuance').val(),
            updateConsumable: true
        };
        $.ajax({
            url: 'pages/admin/process_updateconsumable.php', // New PHP for consumables
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $('#editEquipModal').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error updating consumable:', status, error, xhr.responseText);
                Swal.fire('Error', 'Error updating consumable equipment.', 'error');
            }
        });
    } else {
        var equipBrand = $('#editequipBrand').val();
        var equipType = $('#editequipType').val();
        var equipSerial = $('#editequipSerial').val();
        var equipPropNo = $('#editequipPropNo').val();
        var equipPropName = $('#editequipPropName').val();
        var equipStatus = $('#editequipStatus').val();
        var editequipenduser = $('#editequipenduser').val();
        var editequipaccountedto = $('#editequipaccountedto').val();

        $.ajax({
            url: 'pages/admin/process_updateequipment.php',
            method: 'POST',
            data: {
                inv_id: invId,
                equipSerial: equipSerial,
                equipPropNo: equipPropNo,
                equipPropName: equipPropName,
                equipType: equipType,
                checkDuplicates: true
            },
            dataType: 'json',
            success: function(response) {
                if (response.blockUpdate) {
                    Swal.fire({
                        title: 'Update Blocked',
                        html: 'Serial No., Property No., Property Name, and Type all match an existing record.<br><strong>Update cannot proceed.</strong>',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } else if (response.duplicate) {
                    let duplicateFields = [];
                    if (response.duplicateSerial) duplicateFields.push("Serial No.");
                    if (response.duplicatePropNo) duplicateFields.push("Property No.");
                    if (response.duplicatePropName) duplicateFields.push("Property Name");
                    if (response.duplicateType) duplicateFields.push("Type");

                    Swal.fire({
                        title: 'Duplicate Warning',
                        html: `<strong>The following field(s) already exist:</strong><br><br> 
                               <ul style="text-align: left;">
                                   ${duplicateFields.map(field => `<li>${field}</li>`).join('')}
                               </ul><br>Would you like to save anyway?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Save',
                        cancelButtonText: 'No, Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            updateEquipment(invId, equipBrand, equipType, equipSerial, equipPropNo, equipPropName, equipStatus, editequipenduser, editequipaccountedto);
                        }
                    });
                } else {
                    updateEquipment(invId, equipBrand, equipType, equipSerial, equipPropNo, equipPropName, equipStatus, editequipenduser, editequipaccountedto);
                }
            },
            error: function() {
                Swal.fire('Error', 'Error checking for duplicates.', 'error');
            }
        });
    }
});

function updateEquipment(invId, equipBrand, equipType, equipSerial, equipPropNo, equipPropName, equipStatus, editequipenduser, editequipaccountedto) {
    $.ajax({
        url: 'pages/admin/process_updateequipment.php',
        method: 'POST',
        data: {
            inv_id: invId,
            equipBrand: equipBrand,
            equipSerial: equipSerial,
            equipPropNo: equipPropNo,
            equipStatus: equipStatus,
            equipType: equipType,
            equipPropName: equipPropName,
            editequipenduser: editequipenduser,
            editequipaccountedto: editequipaccountedto,
            updateEquipment: true
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'Success!',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $('#editEquipModal').modal('hide');
                    location.reload();
                });
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Error updating equipment.', 'error');
        }
    });
}
</script>


<!-- AddType Modal -->
<div class="modal fade" id="addTypeModal" tabindex="-1" role="dialog" aria-labelledby="addTypeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addTypeModalLabel">Add Inventory Type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="addTypeForm" method="POST">
        <div class="modal-body">
          <div class="form-group">
            <label for="typeName">Type Name</label>
            <input type="text" class="form-control" id="typeName" name="typeName" placeholder="Enter Inventory Type" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- EditType Modal -->
<div class="modal fade" id="editTypeModal" tabindex="-1" role="dialog" aria-labelledby="editTypeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editTypeModalLabel">Edit Inventory Type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="editTypeForm" method="POST">
        <div class="modal-body">
          <input type="hidden" id="editTypeId" name="type_id">
          <div class="form-group">
            <label for="editTypeName">Type Name</label>
            <input type="text" class="form-control" id="editTypeName" name="typeName" required>
          </div>
          <div class="form-group">
            <label for="editTypeOrigin">Type Origin</label>
            <select class="form-control" id="editTypeOrigin" name="typeOrigin" required>
              <option value="Consumable">Consumable</option>
              <option value="Non-Consumable">Non-Consumable</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    $('#addTypeForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        var typeName = $('#typeName').val().trim();
        var typeOrigin = 'Non-Consumable'; // Set default value

        if (typeName === '') {
            Swal.fire('Missing Input', 'Please enter a type name.', 'warning');
            return;
        }

        $.ajax({
            url: 'pages/admin/process_addtype.php',
            method: 'POST',
            data: {
                typeName: typeName,
                typeOrigin: typeOrigin
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Inventory Type added successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        timer: 2000,
                        timerProgressBar: true
                    }).then(() => {
                        $('#addTypeModal').modal('hide');
                        $('#dataTableInvType').DataTable().ajax.reload(null, false);
                        $('#addTypeForm')[0].reset();
                    });
                } else if (response.status === 'exists') {
                    Swal.fire('Duplicate Type', 'This type name already exists.', 'info');
                } else {
                    Swal.fire('Error', response.message || 'An unknown error occurred.', 'error');
                }
            },
            error: function() {
                Swal.fire('Request Failed', 'An error occurred while processing the request.', 'error');
            }
        });
    });

    // Handle Edit Type button click
    $(document).on('click', '.edit-type', function() {
        var typeId = $(this).data('id');
        $.ajax({
            url: 'pages/admin/fetch_typedetails.php',
            method: 'POST',
            data: { type_id: typeId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#editTypeId').val(response.data.type_id);
                    $('#editTypeName').val(response.data.type_name);
                    $('#editTypeOrigin').val(response.data.type_origin);
                    $('#editTypeModal').modal('show');
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            }
        });
    });

    // Handle Edit Type form submission
    $('#editTypeForm').on('submit', function(e) {
        e.preventDefault();
        var typeId = $('#editTypeId').val();
        var typeName = $('#editTypeName').val().trim();
        var typeOrigin = $('#editTypeOrigin').val();

        $.ajax({
            url: 'pages/admin/process_updatetype.php',
            method: 'POST',
            data: {
                type_id: typeId,
                type_name: typeName,
                type_origin: typeOrigin
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#editTypeModal').modal('hide');
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        $('#dataTableInvType').DataTable().ajax.reload(null, false);
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            }
        });
    });
});
</script>

<!-- Import Beginning Balance Modal -->
<div class="modal fade" id="importBeginningBalanceModal" tabindex="-1" role="dialog" aria-labelledby="importBeginningBalanceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importBeginningBalanceModalLabel">Import Beginning Balance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="importBeginningBalanceForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="excelFileBeginningBalance">Choose Excel File</label>
                        <input type="file" name="excelFileBalance" id="excelFileBeginningBalance" class="form-control" required accept=".xlsx, .xls">
                    </div>
                    <div class="form-group">
                        <label for="excelSheetBeginningBalance">Choose Sheet</label>
                        <select name="excelSheet" id="excelSheetBeginningBalance" class="form-control" required disabled>
                            <option value="">Select a file first</option>
                        </select>
                    </div>
                    <small class="form-text text-muted">
                        <strong>Format:</strong><br>
                        Column A: <strong>Stock No.</strong><br>
                        Column B: <strong>Beginning Balance</strong><br>
                        Column C: <strong>Unit</strong><br>
                        Column D: <strong>Item Description</strong>
                    </small>
                    <button type="submit" class="btn btn-primary mt-3">Import</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const $balanceModal = $('#importBeginningBalanceModal');
    const $balanceForm = $('#importBeginningBalanceForm');
    const $balanceFileInput = $('#excelFileBeginningBalance');
    const $balanceSheetSelect = $('#excelSheetBeginningBalance');
    const $balanceSubmitBtn = $balanceForm.find('button[type="submit"]');

    $balanceFileInput.on('change', function() {
        if (this.files.length === 0) {
            $balanceSheetSelect.html('<option value="">Select a file first</option>').prop('disabled', true);
            $balanceSubmitBtn.prop('disabled', true);
            return;
        }

        const formData = new FormData();
        formData.append('excelFileBalance', this.files[0]);

        $balanceSheetSelect.prop('disabled', true);

        $.ajax({
            url: 'pages/admin/fetch_sheets.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    Swal.fire('Error', response.error, 'error');
                    $balanceSheetSelect.html('<option value="">Could not load sheets</option>');
                    $balanceSubmitBtn.prop('disabled', true);
                } else {
                    $balanceSheetSelect.empty();
                    response.sheets.forEach(function(sheetName) {
                        $balanceSheetSelect.append(`<option value="${sheetName}">${sheetName}</option>`);
                    });
                    $balanceSheetSelect.prop('disabled', false);
                    $balanceSubmitBtn.prop('disabled', false);
                }
            },
            error: function() {
                Swal.fire('Error', 'Could not retrieve sheet names from the server.', 'error');
                $balanceSheetSelect.html('<option value="">Error loading sheets</option>');
                $balanceSubmitBtn.prop('disabled', true);
            }
        });
    });

    $balanceForm.on('submit', function(e) {
        e.preventDefault();

        if ($balanceFileInput[0].files.length === 0) {
            Swal.fire('Warning', 'Please select a file first.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Importing...',
            text: 'Please wait while the data is being imported.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const formData = new FormData(this);

        $.ajax({
            url: 'pages/admin/process_import_beginning_balance.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Import Successful',
                        text: response.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Import Failed', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Import Failed', 'An unexpected error occurred.', 'error');
            }
        });
    });

    $balanceModal.on('hidden.bs.modal', function() {
        $balanceForm[0].reset();
        $balanceSheetSelect.html('<option value="">Select a file first</option>').prop('disabled', true);
        $balanceSubmitBtn.prop('disabled', true);
    });
});
</script>

<!-- End of Import Beginning Balance Modal -->

<!-- Add Consumable Balance Modal -->
<div class="modal fade" id="addConsumableBalanceModal" tabindex="-1" role="dialog" aria-labelledby="addConsumableBalanceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addConsumableBalanceModalLabel">Add Consumable Balance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addConsumableBalanceForm">
                    <div class="form-group">
                        <label for="stock_number">Stock Number</label>
                        <input type="text" class="form-control" id="stock_number" name="stock_number" required>
                    </div>
                    <div class="form-group">
                        <label for="item_description">Item Description</label>
                        <input type="text" class="form-control" id="item_description" name="item_description" required>
                    </div>
                    <div class="form-group">
                        <label for="unit">Unit</label>
                        <input type="text" class="form-control" id="unit" name="unit" required>
                    </div>
                    <div class="form-group">
                        <label for="beginning_balance">Beginning Balance</label>
                        <input type="number" class="form-control" id="beginning_balance" name="beginning_balance" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Balance</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Consumable Balance Modal -->
<div class="modal fade" id="editConsumableBalanceModal" tabindex="-1" role="dialog" aria-labelledby="editConsumableBalanceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editConsumableBalanceModalLabel">Edit Consumable Balance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editConsumableBalanceForm">
                    <input type="hidden" id="edit_balance_id" name="id">
                    <div class="form-group">
                        <label for="edit_stock_number">Stock Number</label>
                        <input type="text" class="form-control" id="edit_stock_number" name="stock_number" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_item_description">Item Description</label>
                        <input type="text" class="form-control" id="edit_item_description" name="item_description" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_unit">Unit</label>
                        <input type="text" class="form-control" id="edit_unit" name="unit" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_beginning_balance">Beginning Balance</label>
                        <input type="number" class="form-control" id="edit_beginning_balance" name="beginning_balance" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Create more for TINGI TINGI functionality -->
<!-- Modal for Viewing Pending Items -->
<div class="modal fade" id="viewPendingItemListModal" tabindex="-1" role="dialog" aria-labelledby="viewPendingItemListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewPendingItemListModalLabel">Equipment/s in this List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Display Borrower's Name and Number of Items side by side -->
                <div class="form-row mb-3">
  <div class="col">
    <label for="borrower-name-input1">Borrower's Name:</label>
    <input type="text" class="form-control text-center" id="borrower-name-input1" readonly>
  </div>
  <div class="col">
    <label for="num-items-input1">Number of Items:</label>
    <input type="text" class="form-control text-center" id="num-items-input1" readonly>
  </div>
</div>

<div class="form-group d-flex">
  <label for="borrower-reason" class="mr-2 mt-1" style="white-space: nowrap;">Reason:</label>
  <textarea class="form-control" id="borrower-reason" rows="4" readonly style="height: 100px; text-align: justify; resize: none;"></textarea>
</div>
    <!-- Table for showing items associated with the breq_token -->
    <label>Items</label>
        <table class="table table-bordered" id="pendingItemsTable">
            <thead>
                <tr>
                <th>Type</th>
                <th>Brand / Model</th>
                <th>Property No.</th>
                <th>Property Name</th>
                           

                </tr>
            </thead>
            <tbody>
                <!-- Dynamic rows will be inserted here by AJAX -->
            </tbody>
            </table>
                
            </div>
            <div class="modal-footer">
                <!-- <button type="button" id="accept-button" class="btn btn-primary">Accept</button> -->
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<script>
$(document).on('click', '.info-pendingrequest', function() {
    // Get the breq_token from the data-id attribute    
    var breq_token = $(this).data('id');
var emp_name = $(this).closest('tr').find('td').eq(1).text();
var num_items = $(this).closest('tr').find('td').eq(2).text();

// Set known fields right away
$('#borrower-name-input1').val(emp_name);
$('#num-items-input1').val(num_items);

// Get reason from database
$.ajax({
    url: 'pages/admin/fetch_reason.php',
    type: 'POST',
    data: { breq_token: breq_token },
    success: function(response) {
        $('#borrower-reason').val(response);
    },
    error: function() {
        console.log("Error fetching reason.");
        $('#borrower-reason').val("Error loading reason");
    }
});


    // Optionally log the data for debugging
    console.log('Request Token: ' + breq_token);
    console.log('Employee Name: ' + emp_name);
    console.log('Number of Items: ' + num_items);

    // Fetch and display the inventory details based on breq_token
    $.ajax({
        url: 'pages/admin/fetch_pendingitems.php',
        type: 'POST',
        data: { breq_token: breq_token },
        success: function(response) {
            // Insert the inventory rows into the modal's table
            $('#pendingItemsTable tbody').html(response);
        },
        error: function() {
            console.log("Error fetching inventory data.");
        }
    });
});

$(document).on('click', '.info-bulkapprove', function() {
    // Get the breq_token of the clicked button
    var breq_token = $(this).data('id');

    // You might want to add the clicked request to an array for bulk approval
    var selectedRequests = [];
    selectedRequests.push(breq_token); // Here we are adding only one request, but you can add more logic for multiple selections

    // Confirm the action before proceeding
    Swal.fire({
        title: 'Approve Equipment Request?',
        text: "All items under this will all be approved and cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, approve it!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Send the selected request tokens to the backend via AJAX
            $.ajax({
                url: 'pages/admin/process_bulkapprove.php',  // The PHP file that will handle the approval
                method: 'POST',
                data: {
                    request_ids: selectedRequests  // Send the array of request IDs
                },
                success: function(response) {
                    if (response === 'success') {
                        Swal.fire(
                            'Approved!',
                            'The selected request(s) have been approved.',
                            'success'
                        );
                        // Reload the table or update the UI here if needed
                        setTimeout(function() {
                            location.reload();  // Refresh the page
                        }, 1200); 
                    } else {
                        Swal.fire(
                            'Error!',
                            'An error occurred while approving the request(s). Please try again.',
                            'error'
                        );
                    }
                },
                error: function() {
                    Swal.fire(
                        'Error!',
                        'Failed to communicate with the server. Please try again later.',
                        'error'
                    );
                }
            });
        }
    });
});


// bulkdecline start
$(document).on('click', '.info-bulkdecline', function() {
    // Get the breq_token of the clicked button
    var breq_token = $(this).data('id');

    // You might want to add the clicked request to an array for bulk approval
    var selectedRequests = [];
    selectedRequests.push(breq_token); // Here we are adding only one request, but you can add more logic for multiple selections

    // Confirm the action before proceeding
    Swal.fire({
        title: 'Decline Equipment Request?',
        text: "All items under this will all be declined and cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, decline it!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Send the selected request tokens to the backend via AJAX
            $.ajax({
                url: 'pages/admin/process_bulkdecline.php',  // The PHP file that will handle the approval
                method: 'POST',
                data: {
                    request_ids: selectedRequests  // Send the array of request IDs
                },
                success: function(response) {
                    if (response === 'success') {
                        Swal.fire(
                            'Declined!',
                            'The selected request(s) have been declined.',
                            'success'
                        );
                        // Reload the table or update the UI here if needed
                        setTimeout(function() {
                            location.reload();  // Refresh the page
                        }, 1200); 
                    } else {
                        Swal.fire(
                            'Error!',
                            'An error occurred while declining the request(s). Please try again.',
                            'error'
                        );
                    }
                },
                error: function() {
                    Swal.fire(
                        'Error!',
                        'Failed to communicate with the server. Please try again later.',
                        'error'
                    );
                }
            });
        }
    });
});





</script>
<!-- end viewPendingItemList -->


<!-- Modal for Returning Items -->
<div class="modal fade" id="viewReturningItemListModal" tabindex="-1" role="dialog" aria-labelledby="viewReturningItemListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewReturningItemListModalLabel">Returning Items</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Display Borrower's Name and Number of Items side by side -->
                <div class="form-row mb-3">
                    <div class="col">
                        <label for="borrower-name-input">Borrower's Name:</label>
                        <input type="text" class="form-control text-center" id="borrower-name-input" readonly>
                    </div>
                    <div class="col">
                        <label for="num-items-input">Number of Items:</label>
                        <input type="text" class="form-control text-center" id="num-items-input" readonly>
                    </div>
                </div>

                <!-- Table for showing items associated with the breq_token -->
                <label>Items</label>
                <table class="table table-bordered" id="pendingItemsTable">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Brand / Model</th>
                            <th>Property No.</th>
                            <th>Property Name</th>
                            <th style="width: 5px;"><i class=" fas fa-check"></i></th>

                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic rows will be inserted here by AJAX -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end viewPendingItemList -->
<!-- end viewPendingItemList -->


<script>
$(document).on('click', '.info-returningrequest', function() {
    // Get the breq_token from the data-id attribute
    var breq_token = $(this).data('id');
    
    // Get the emp_name from the current row
    var emp_name = $(this).closest('tr').find('td').eq(1).text();
    
    // Get the number of items from the current row
    var num_items = $(this).closest('tr').find('td').eq(2).text();
    
    // Set the borrower's name and number of items in the input fields
    $('#borrower-name-input').val(emp_name);
    $('#num-items-input').val(num_items);

    // Optionally log the data for debugging
    console.log('Request Token: ' + breq_token);
    console.log('Employee Name: ' + emp_name);
    console.log('Number of Items: ' + num_items);

    // Fetch and display the inventory details based on breq_token
    $.ajax({
        url: 'pages/admin/fetch_returningitems.php',
        type: 'POST',
        data: { breq_token: breq_token },
        success: function(response) {
            // Insert the inventory rows into the modal's table
            $('#pendingItemsTable tbody').html(response);
        },
        error: function() {
            console.log("Error fetching inventory data.");
        }
    });
});


$(document).on('click', '.info-bulkreturn', function() {
    var breq_token = $(this).data('id'); // Get breq_token from button

    Swal.fire({
        title: 'Confirm Return?',
        text: "Are you sure you want to mark this equipment as returned?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, return it!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Enter Returner\'s Name',
                input: 'text',
                inputPlaceholder: 'Name of person returning the item',
                showCancelButton: true,
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
                preConfirm: (returnerName) => {
                    if (!returnerName) {
                        Swal.showValidationMessage('Returner\'s name is required.');
                    }
                    return returnerName;
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((nameResult) => {
                if (nameResult.isConfirmed) {
                    const returnerName = nameResult.value;
                    $.ajax({
                        url: 'pages/admin/process_bulkreturn.php',
                        method: 'POST',
                        data: {
                            request_ids: [breq_token], // Send as an array
                            returner_name: returnerName
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Returned!',
                                    response.message,
                                    'success'
                                );
                                setTimeout(function() {
                                    location.reload();
                                }, 1200);
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
                                'Failed to communicate with the server. Please try again later.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    });
});


$(document).on('click', '.report-missing', function() {
    var invId = $(this).data('id');
    Swal.fire({
        title: 'Report as Missing',
        text: 'Are you sure you want to report this equipment as missing?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, report as missing',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'pages/admin/process_report_missing.php',
                type: 'POST',
                data: { inv_id: invId },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire('Success', response.message, 'success');
                        // Optionally, remove the row or reload the table
                        location.reload();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'AJAX error: ' + error, 'error');
                }
            });
        }
    });
});
</script>


<!-- Manual Add Equipment Modal -->
<div class="modal fade" id="manualAddModal" tabindex="-1" role="dialog" aria-labelledby="manualAddModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="manualAddModalLabel">Manual Add Equipment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Place your manual add form here -->
        <form id="manualAddForm">
          <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="acceptance_date" class="font-weight-bold">Acceptance Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="acceptance_date" name="acceptance_date" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="ris_no" class="font-weight-bold">RIS No. <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ris_no" name="ris_no" placeholder="Enter RIS No." required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="item_description" class="font-weight-bold">Item Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="item_description" name="item_description" rows="3" placeholder="Enter Item Description" required></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="receipt" class="font-weight-bold">Receipt <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="receipt" name="receipt" min="0" value="0" required placeholder="Enter Receipt Quantity">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="issuance" class="font-weight-bold">Issuance <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="issuance" name="issuance" min="0" value="0" required placeholder="Enter Issuance Quantity">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="end_user_issuance" class="font-weight-bold">End User of Issuance <span class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" id="end_user_issuance" name="end_user_issuance" placeholder="Enter End User">
                            </div>
                        </div>
