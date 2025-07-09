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
                    <hr>
                    <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
   $(document).ready(function () {
    $(".view-user").click(function () {
        var username = $(this).data("username");

        $.ajax({
            url: "pages/admin/fetch_user_details.php", // Ensure the correct path
            method: "POST",
            data: { username: username },
            dataType: "json",
            success: function (response) {
                if (response.error) {
                    alert(response.error);
                } else {
                    $("#viewUsername").val(response.username);
                    $("#viewUserLevel").val(response.user_level);
                    $("#viewFullName").val(response.full_name);
                    $("#viewUserStatus").val(response.user_status); // Updated for status
                    $("#viewUserModal").modal("show");
                }
            },
            error: function () {
                alert("Error fetching user data.");
            },
        });
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
                                <label for="cinv_type" class="font-weight-bold">Type <span class="text-danger">*</span></label>
                                <select class="form-control" id="cinv_type" name="type_id" required>
                                    <option value="" disabled selected>Select Type</option>
                                    <!-- PHP code would populate this -->
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="cinv_bnm" class="font-weight-bold">Brand/Model <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cinv_bnm" name="inv_bnm" placeholder="ex: MSI GF63" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="cinv_serialno" class="font-weight-bold">Serial Number <span class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" id="cinv_serialno" name="inv_serialno" placeholder="Enter Serial Number">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="cinv_propno" class="font-weight-bold">Property Number <span class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" id="cinv_propno" name="inv_propno" placeholder="Enter Property Number">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="cinv_propname" class="font-weight-bold">Property Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cinv_propname" name="inv_propname" placeholder="Enter Property Name" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="cinv_status" class="font-weight-bold">Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="cinv_status" name="inv_status" required>
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="ccondition" class="font-weight-bold">Condition <span class="text-muted">(Optional)</span></label>
                                <select class="form-control" id="ccondition" name="condition">
                                    <option value="" selected disabled>Select Condition</option>
                                    <option value="New">New</option>
                                    <option value="Good">Good</option>
                                    <option value="Fair">Fair</option>
                                    <option value="Poor">Poor</option>
                                    <option value="For Repair">For Repair</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="cinv_quantity" class="font-weight-bold">Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="cinv_quantity" name="inv_quantity" min="1" value="1" required placeholder="Enter Quantity">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="cprice" class="font-weight-bold">Price <span class="text-muted">(Optional)</span></label>
                                <input type="number" class="form-control" id="cprice" name="price" step="0.01" min="0" value="0.00" placeholder="0.00">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="cdate_acquired" class="font-weight-bold">Date Acquired <span class="text-muted">(Optional)</span></label>
                                <input type="date" class="form-control" id="cdate_acquired" name="date_acquired">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="cend_user" class="font-weight-bold">End User <span class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" id="cend_user" name="end_user" placeholder="Enter End User Name">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="caccounted_to" class="font-weight-bold">Accounted To <span class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" id="caccounted_to" name="accounted_to" placeholder="Enter Accountable Person">
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
        // Consumable Equipment Submit
        $(document).on('click', '#submitConsumable', function(e) {
            e.preventDefault();

            console.log("Submit consumable button clicked!");

            var formData = {
                type_id: $('#cinv_type').val(),
                inv_bnm: $('#cinv_bnm').val() || '',
                inv_serialno: $('#cinv_serialno').val() || '',
                inv_propno: $('#cinv_propno').val() || '',
                inv_propname: $('#cinv_propname').val() || '',
                inv_status: $('#cinv_status').val() || 1,
                condition: $('#ccondition').val() || '',
                inv_quantity: $('#cinv_quantity').val() || 1,
                price: $('#cprice').val() || 0.00,
                date_acquired: $('#cdate_acquired').val() || '',
                end_user: $('#cend_user').val() || '',
                accounted_to: $('#caccounted_to').val() || ''
            };

            console.log("Collected Consumable Form Data:", formData);

            // Validate required fields
            var missingFields = [];
            
            if (!formData.type_id) missingFields.push("Equipment Type");
            if (!formData.inv_bnm) missingFields.push("Brand/Model");
            if (!formData.inv_propname) missingFields.push("Property Name");
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
                            console.error("AJAX Error:", xhr.responseText, status, error);
                            Swal.fire({
                                title: "Error!",
                                text: "Error in submitting form. Please check the console for details.",
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
                        <input type="file" name="excelFile" id="excelFile" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Import</button>
                </form>
                <div id="importStatus" style="margin-top: 10px;"></div> <!-- Status message after import -->
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    const $importForm = $('#importForm');
    const $fileInput = $('#excelFile');
    const $statusDiv = $('#importStatus');
    const $submitBtn = $importForm.find('button[type="submit"]');

    // Initialize modal events
    $('#importEquipModal').on('hidden.bs.modal', resetModal);

    // Handle form submission
    $importForm.on('submit', function(e) {
        e.preventDefault();
        clearStatus();

        if (!hasSelectedFile()) {
            showError('Please select a file first.');
            return;
        }

        disableForm(true);
        showInfo('Importing, please wait...');

        const formData = new FormData(this);

        sendImportRequest(formData);
    });

    // Check if file was selected
    function hasSelectedFile() {
        return $fileInput[0].files.length > 0;
    }

    // Send AJAX request to server
    function sendImportRequest(formData) {
        $.ajax({
            url: 'pages/admin/process_importfile.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: handleServerResponse,
            error: handleAjaxError,
            complete: () => disableForm(false)
        });
    }

    // Handle server JSON response
    function handleServerResponse(response) {
        let result;

        try {
            result = typeof response === 'object' ? response : JSON.parse(response);
        } catch (err) {
            console.error('JSON parse error:', err, 'Response:', response);
            showError('Unexpected response from server.');
            return;
        }

        if (result.error) {
            showError(result.error);
        } else {
            showSuccessAlert(result.inserted, result.redundant);
        }
    }

    // Handle communication failure
    function handleAjaxError(xhr, status, error) {
        console.error('AJAX error:', status, error);
        showError('Failed to communicate with the server.');
    }

    // SweetAlert success display
    function showSuccessAlert(inserted, redundant) {
        Swal.fire({
            icon: 'success',
            title: 'Import Status',
            text: `Inserted ${inserted} equipment. Contained ${redundant} redundant equipment.`,
            confirmButtonText: 'OK'
        }).then(() => location.reload());
    }

    // UI utilities
    function disableForm(disable) {
        $submitBtn.prop('disabled', disable);
    }

    function clearStatus() {
        $statusDiv.html('');
    }

    function showError(message) {
        $statusDiv.html(`<div class="alert alert-danger">${message}</div>`);
    }

    function showInfo(message) {
        $statusDiv.html(`<div class="alert alert-info">${message}</div>`);
    }

    function resetModal() {
        $importForm[0].reset();
        clearStatus();
        disableForm(false);
    }
});
</script>

<!-- END OF IMPORT EQUIPMENT -->

<!-- END OF IMPORT EQUIPMEMT -->
<!-- End of Add Equipment Modal -->



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
                    <div class="col-md-8">
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

                    <!-- Right side: QR Code -->
                    <div class="col-md-4 d-flex align-items-center justify-content-center">
                        <img id="equipQR" src="" alt="QR Code" style="max-width: 100%; height: auto;">
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
        
        $.ajax({
            url: 'pages/admin/fetch_inventorydetails.php', // Endpoint to fetch details
            type: 'POST',
            data: { inv_id: inv_id },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#equipType').text(response.data.type_name);
                    $('#equipBrand').text(response.data.inv_bnm);
                    $('#equipSerial').text(response.data.inv_serialno);
                    $('#equipPropNo').text(response.data.inv_propno);
                    $('#equipPropName').text(response.data.inv_propname);
                    $('#equipStatus').text(response.data.status);
                    $('#equipDateAdded').text(response.data.inv_date_added);
                    $('#equipenduser').text(response.data.end_user);
                    $('#equipaccountedto').text(response.data.accounted_to);

                    // Dynamically generate QR code image src
                    const qrSrc = `pages/admin/generate_qr.php?type=${encodeURIComponent(response.data.type_name)}&brand=${encodeURIComponent(response.data.inv_bnm)}&serial=${encodeURIComponent(response.data.inv_serialno)}&propno=${encodeURIComponent(response.data.inv_propno)}&propname=${encodeURIComponent(response.data.inv_propname)}&status=${encodeURIComponent(response.data.status)}`;


                    $('#equipQR').attr('src', qrSrc);
                } else {
                    alert('Error fetching data.');
                }
            },
            error: function() {
                alert('Failed to fetch details.');
            }
        });
    });
});
</script>
<!-- view equipment modal end -->


<!-- Edit Equipment Modal -->
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
                    <table class="table table-bordered">
                        <input type="hidden" id="editequipId">

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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveChangesBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- edit Equipment Modal end -->

<script>
// Load types into dropdown
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
    $('#editEquipModal').data('inv-id', invId);

    $.ajax({
        url: 'pages/admin/fetch_inventorydetails.php',
        method: 'POST',
        data: { inv_id: invId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#editequipId').val(response.data.inv_id);
                $('#editequipBrand').val(response.data.inv_bnm);
                $('#editequipSerial').val(response.data.inv_serialno);
                $('#editequipPropNo').val(response.data.inv_propno);
                $('#editequipPropName').val(response.data.inv_propname);
                $('#editequipStatus').val(getStatusValue(response.data.status));
                $('#editequipDateAdded').val(response.data.inv_date_added);
                $('#editequipenduser').val(response.data.end_user);
                $('#editequipaccountedto').val(response.data.accounted_to);

                // Load dropdown with types and select current
                loadTypeOptions(response.data.type_id);
            } else {
                alert('Failed to fetch inventory details: ' + response.message);
            }
        },
        error: function() {
            alert('Error fetching inventory details.');
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
        'Missing': 6
    };
    return statusMap[statusText] || 1;
}

$(document).on('click', '#saveChangesBtn', function() {
    var invId = $('#editEquipModal').data('inv-id');
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
      
      <label for="typeOrigin">Type Origin</label>
      <select class="form-control" id="typeOrigin" name="typeOrigin" required>
        <option value="" disabled selected>Select Origin</option>
        <option value="Consumable">Consumable</option>
        <option value="Non-Consumable">Non-Consumable</option>
      </select>
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

<script>
$(document).ready(function () {
    $('#dataTableInvType').DataTable({
        ajax: 'pages/admin/fetch_invtype.php',
        columns: [
            { title: "ID" },
            { title: "Name" },
            { title: "Origin" },
            { title: "Action", orderable: false }
        ],
        order: [],
        responsive: true
    });
});

$(document).ready(function() {
    $('#addTypeForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        var typeName = $('#typeName').val().trim();
        var typeOrigin = $('#typeOrigin').val().trim();

        if (typeName === '') {
            Swal.fire('Missing Input', 'Please enter a type name.', 'warning');
            return;
        }

        if (typeOrigin === '') {
            Swal.fire('Missing Input', 'Please select a type origin.', 'warning');
            return;
        }

        $.ajax({
            url: 'pages/admin/process_addtype.php',
            method: 'POST',
            data: {
                typeName: typeName,
                typeOrigin: typeOrigin
            },
            success: function(response) {
                console.log("Server Response:", response); // Check the object

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

                    // Reload DataTable via AJAX
                    $('#dataTableInvType').DataTable().ajax.reload(null, false);

                    // Reset form
                    $('#addTypeForm')[0].reset();
                    });
                } else if (response.status === 'exists') {
                    Swal.fire('Duplicate Type', 'This type name already exists.', 'info');
                } else if (response.status === 'empty') {
                    Swal.fire('Missing Data', 'Type name cannot be empty.', 'warning');
                } else {
                    Swal.fire('Unexpected Response', JSON.stringify(response), 'question');
                }
            },

            error: function(xhr, status, error) {
                console.log("AJAX Error: ", status, error);
                Swal.fire('Request Failed', 'An error occurred while processing the request.', 'error');
            }
        });
    });
});
</script>

<!-- End of AddType Modal -->



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

    // Fetch item details via AJAX
    $.ajax({
        url: 'pages/admin/fetch_pendingitems.php', // Adjust the file path
        method: 'POST',
        data: { breq_token: breq_token },
        success: function(response) {
            var tableContent = response; // The response is the table rows from PHP

            // SweetAlert with Returner Name input & dynamically loaded table
            Swal.fire({
                title: 'Return this batch of Equipment?',
                width: '75%',
                heightAuto: false,
                html: `
                    <p>All items under this batch will be made available and cannot be undone.</p>
                    <input id="returnerName" class="swal2-input" placeholder="Enter Returner Name">
                    <hr>
                    <div class="swal2-overflow">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Brand/Model</th>
                                    <th>Property No.</th>
                                    <th>Property Name</th>
                                </tr>
                            </thead>
                            <tbody>${tableContent}</tbody>
                        </table>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Yes, return it!',
                cancelButtonText: 'No, cancel',
                preConfirm: () => {
                    var returnerName = document.getElementById('returnerName').value.trim();
                    if (!returnerName) {
                        Swal.showValidationMessage('Returner\'s Name is required!');
                    }
                    return returnerName;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    var returnerName = result.value;
                    var selectedRequests = [breq_token];

                    // Send the selected request tokens and returner's name via AJAX
                    $.ajax({
                        url: 'pages/admin/process_bulkreturn.php',
                        method: 'POST',
                        data: {
                            request_ids: selectedRequests,
                            returner_name: returnerName
                        },
                        success: function(response) {
                            if (response === 'success') {
                                Swal.fire(
                                    'Returned!',
                                    'The selected request(s) have been returned.',
                                    'success'
                                );
                                setTimeout(function() {
                                    location.reload();
                                }, 1200);
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'An error occurred while returning the request(s). Please try again.',
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
        },
        error: function() {
            Swal.fire('Error!', 'Failed to fetch items. Please try again.', 'error');
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
              <label for="manualType">Type</label>
              <select class="form-control" id="manualType" name="manualType"></select>
            </div>
            <div class="form-group col-md-6">
              <label for="manualBrand">Brand/Model</label>
              <input type="text" class="form-control" id="manualBrand" name="manualBrand" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="manualSerial">Serial No.</label>
              <input type="text" class="form-control" id="manualSerial" name="manualSerial">
            </div>
            <div class="form-group col-md-6">
              <label for="manualPropNo">Property No.</label>
              <input type="text" class="form-control" id="manualPropNo" name="manualPropNo">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="manualPropName">Property Name</label>
              <input type="text" class="form-control" id="manualPropName" name="manualPropName" required>
            </div>
            <div class="form-group col-md-6">
              <label for="manualQuantity">Quantity</label>
              <input type="number" class="form-control" id="manualQuantity" name="manualQuantity" min="1" value="1" required>
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Add Equipment</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function () {
    // Manual Add Equipment form submission
    $('#manualAddForm').on('submit', function (e) {
        e.preventDefault();

        var formData = {
            manualType: $('#manualType').val(),
            manualBrand: $('#manualBrand').val(),
            manualSerial: $('#manualSerial').val(),
            manualPropNo: $('#manualPropNo').val(),
            manualPropName: $('#manualPropName').val(),
            manualQuantity: $('#manualQuantity').val()
        };

        console.log("Manual Add Form Data:", formData); // Debugging line

        // Validate required fields
        var missingFields = [];
        
        if (!formData.manualType) missingFields.push("Type");
        if (!formData.manualBrand) missingFields.push("Brand/Model");
        if (!formData.manualPropName) missingFields.push("Property Name");
        if (!formData.manualQuantity || formData.manualQuantity <= 0) missingFields.push("Quantity (must be greater than 0)");

        if (missingFields.length > 0) {
            Swal.fire({
                title: "Missing Required Fields",
                text: "Please fill in the following required fields: " + missingFields.join(", "),
                icon: "warning"
            });
            return;
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
            url: 'pages/admin/process_manual_add.php',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log("Manual Add AJAX Response:", response);

                if (response.status === 'success') {
                    Swal.fire({
                        title: "Added!",
                        text: response.message,
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Reset the form
                        $('#manualAddForm')[0].reset();
                        
                        // Close modal properly
                        $('#manualAddModal').modal('hide');
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');
                        
                        // Reload page to show new data
                        location.reload();
                    });

                } else if (response.status === 'exists') {
                    Swal.fire({
                        title: "Duplicate Entry",
                        text: "This equipment already exists in the system.",
                        icon: "info"
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
                console.error("AJAX Error:", xhr.responseText, status, error);
                Swal.fire({
                    title: "Error!",
                    text: "Error in submitting form. Please check the console for details.",
                    icon: "error"
                });
            }
        });
    });
});
</script>