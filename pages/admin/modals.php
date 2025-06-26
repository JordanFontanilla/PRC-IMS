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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addConsumableEquipModalLabel">Add New Equipment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addConsumableEquipForm">
                    <!-- First Row: Type, Brand, Model -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="cinv_type" class="font-weight-bold">Type</label>
                            <select class="form-control" id="cinv_type" required>
                                <option value="" disabled selected>Select Type</option>
                                <?php
                                require '../../function_connection.php';
                                $query = "SELECT type_id, type_name FROM tbl_type WHERE type_origin = 'Consumable'";
                                $result = $conn->query($query);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['type_id'] . "'>" . htmlspecialchars($row['type_name']) . "</option>";
                                    }
                                } else {
                                    echo "<option value='' disabled>No Types Available</option>";
                                }
                                $conn->close();
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="cbrand" class="font-weight-bold">Brand</label>
                            <input type="text" class="form-control" id="cbrand" placeholder="ex: MSI" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="cmodel" class="font-weight-bold">Model</label>
                            <input type="text" class="form-control" id="cmodel" placeholder="Model" required>
                        </div>
                    </div>

                    <!-- Second Row: Serial Number -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="cserialno1" class="font-weight-bold">Serial Number</label>
                            <input type="text" class="form-control" id="cserialno1" placeholder="Enter Serial Number" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cpropertyno" class="font-weight-bold">Property Number</label>
                            <input type="text" class="form-control" id="cpropertyno" placeholder="Enter Property Number" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="cpropertyname" class="font-weight-bold">Property Name</label>
                            <input type="text" class="form-control" id="cpropertyname" placeholder="Enter Property Name" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="cquantity" class="font-weight-bold">Quanity</label>
                            <input type="number" class="form-control" id="cquantity" min="1" placeholder="Enter Quantity, Leave blank for 1">
                        </div>
                    </div>

                    <hr>

                    <!-- Submit Button -->
                    <button id="submitConsumable" type="button" class="btn btn-primary btn-block">Add Consumable Equipment</button>

                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $(document).on('click', '#submitConsumable', function(e) {
        e.preventDefault();

        console.log("Submit button clicked!");

        var formData = {
            cinv_type: $('#cinv_type').val(),
            cbrand: $('#cbrand').val(),
            cmodel: $('#cmodel').val(),
            cserialno1: $('#cserialno1').val(),
            cpropertyno: $('#cpropertyno').val(),
            cpropertyname: $('#cpropertyname').val(),
            cquantity: $('#cquantity').val() || 1
        };

        console.log("Collected Form Data:", formData);

        // Validate required fields
        if (!formData.cinv_type || !formData.cbrand || !formData.cmodel || !formData.cserialno1 || !formData.cpropertyno || !formData.cpropertyname) {
            Swal.fire({
                title: "Missing Fields",
                text: "Please fill in all required fields.",
                icon: "warning"
            });
            return;
        }

        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to add this equipment?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, add it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // 🆕 UPDATED AJAX
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
                                timer: 1200,
                                showConfirmButton: false
                            }).then(() => {
                                $('#addConsumableEquipModal').modal('hide');
                                $('.modal-backdrop').remove();
                                $('body').removeClass('modal-open');
                                location.reload();
                            });

                        } else if (response.status === 'confirm_duplicate') {
                            // 🆕 When only some fields exist (Serial, Property No, Property Name)
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
                                    // Second confirm to force insert
                                    $.ajax({
                                        type: 'POST',
                                        url: 'pages/admin/process_addconsumable.php',
                                        data: {...formData, force_insert: true}, // send force_insert flag
                                        dataType: 'json',
                                        success: function(forceResponse) {
                                            if (forceResponse.status === 'success') {
                                                Swal.fire({
                                                    title: "Added!",
                                                    text: forceResponse.message,
                                                    icon: "success",
                                                    timer: 1200,
                                                    showConfirmButton: false
                                                }).then(() => {
                                                    $('#addConsumableEquipModal').modal('hide');
                                                    $('.modal-backdrop').remove();
                                                    $('body').removeClass('modal-open');
                                                    location.reload();
                                                });
                                            } else {
                                                Swal.fire({
                                                    title: "Error!",
                                                    text: forceResponse.message,
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
    });
});
</script>


<!-- Add Non- consumable Equipment Modal -->
<div class="modal fade" id="addNonConsumableEquipModal" tabindex="-1" role="dialog" aria-labelledby="addNonConsumableEquipModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNonConsumableEquipModalLabel">Add Non-Consumable Equipment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addNonConsumableEquipForm">
                    <!-- First Row: Type, Brand, Model -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inv_type" class="font-weight-bold">Type</label>
                            <select class="form-control" id="inv_type" required>
                                <option value="" disabled selected>Select Type</option>
                                <?php
                                require '../../function_connection.php';
                                $query = "SELECT type_id, type_name, type_origin FROM tbl_type WHERE type_origin = 'Non-Consumable'";
                                $result = $conn->query($query);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['type_id'] . "'>" . htmlspecialchars($row['type_name']) . "</option>";
                                    }
                                } else {
                                    echo "<option value='' disabled>No Types Available</option>";
                                }
                                $conn->close();
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="brand" class="font-weight-bold">Brand</label>
                            <input type="text" class="form-control" id="brand" placeholder="ex: MSI" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="model" class="font-weight-bold">Model</label>
                            <input type="text" class="form-control" id="model" placeholder="Model" required>
                        </div>
                    </div>  

                    <!-- Second Row: Serial Number -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="serialno" class="font-weight-bold">Serial Number</label>
                            <input type="text" class="form-control" id="serialno" placeholder="Enter Serial Number" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="propertyno" class="font-weight-bold">Property Number*</label>
                            <input type="text" class="form-control" id="propertyno" placeholder="Enter Property Number" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="propertyname" class="font-weight-bold">Division/Section*</label>
                            <input type="text" class="form-control" id="propertyname" placeholder="Enter Division/Section" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="enduser" class="font-weight-bold">End User</label>
                            <input type="text" class="form-control" id="enduser" placeholder="Enter End User Name">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="accountedto" class="font-weight-bold">Accountable Personel</label>
                            <input type="text" class="form-control" id="accountedto" placeholder="Enter Name of Accountable Personel">
                        </div>
                    </div>

                    <hr>

                    <!-- Submit Button -->
                    <button  id="submitNonConsumable" type="button" class="btn btn-primary btn-block">Add NonCon Equipment</button>
                </form>
            </div>
        </div>
    </div>
</div>  

<script>
$(document).ready(function () {
    $(document).on('click', '#submitNonConsumable', function(e) {
        e.preventDefault();

        var formData = {
            type_id: $('#inv_type').val(),
            brand: $('#brand').val().trim(),
            model: $('#model').val().trim(),
            serialno: $('#serialno').val().trim(),
            propertyno: $('#propertyno').val().trim(),
            propertyname: $('#propertyname').val().trim(),
            enduser: $('#enduser').val().trim(),
            accountedto: $('#accountedto').val().trim()
        };

        if (!formData.type_id || !formData.propertyno || !formData.propertyname) {
            Swal.fire({
                title: "Missing Important Fields",
                text: "Please fill in all required fields.",
                icon: "warning"
            });
            return;
        }

        // Alert if brand, model, and serialno are all empty
        if (!formData.brand && !formData.model && !formData.serialno) {
            Swal.fire({
                title: "All Identification Fields Empty",
                text: "Brand, Model, and Serial Number are empty. Are you sure you want to continue?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, continue",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    triggerMainConfirmation(formData);
                }
            });
        } else {
            triggerMainConfirmation(formData);
        }
    });

    function triggerMainConfirmation(formData) {
    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to add this equipment?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, add it!"
    }).then((result) => {
        if (result.isConfirmed) {
            // First AJAX attempt
            $.ajax({
                type: 'POST',
                url: 'pages/admin/process_addequipment_nonconsumable.php',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'warning') {
                        // Handle partial duplicate warning (e.g., type_id + other fields)
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
                                // Proceed with second request (force add)
                                $.ajax({
                                    type: 'POST',
                                    url: 'pages/admin/process_addequipment_nonconsumable.php',
                                    data: formData,
                                    dataType: 'json',
                                    success: function(response) {
                                        if (response.status === 'success') {
                                            Swal.fire({
                                                title: "Added!",
                                                text: response.message,
                                                icon: "success",
                                                timer: 1200,
                                                showConfirmButton: false
                                            }).then(() => {
                                                $('#addNonConsumableEquipModal').modal('hide');
                                                $('.modal-backdrop').remove();
                                                $('body').removeClass('modal-open');
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
                                    error: function() {
                                        Swal.fire({
                                            title: "Error!",
                                            text: "Error in submitting form.",
                                            icon: "error"
                                        });
                                    }
                                });
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
                            $('#addNonConsumableEquipModal').modal('hide');
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
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
                    Swal.fire({
                        title: "Error!",
                        text: "Error in submitting form.",
                        icon: "error"
                    });
                }
            });
        }
    });
}

});
</script>


<!-- <script>
document.getElementById("previewData").addEventListener("click", function() {
    var fileInput = document.getElementById("excelFile");
    if (!fileInput.files.length) {
        alert("Please select an Excel file first.");
        return;
    }

    var formData = new FormData();
    formData.append("excel_file", fileInput.files[0]);

    fetch("pages/admin/process_importfile.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            let previewBody = document.getElementById("previewBody");
            previewBody.innerHTML = ""; // Clear existing preview
            data.records.forEach(row => {
                let tr = document.createElement("tr");
                row.forEach(cell => {
                    let td = document.createElement("td");
                    td.textContent = cell;
                    tr.appendChild(td);
                });
                previewBody.appendChild(tr);
            });
            document.getElementById("previewTable").style.display = "block";
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => console.error("Error:", error));
});

document.getElementById("importData").addEventListener("click", function() {
    var fileInput = document.getElementById("excelFile");
    if (!fileInput.files.length) {
        alert("Please select an Excel file first.");
        return;
    }

    var formData = new FormData();
    formData.append("excel_file", fileInput.files[0]);
    formData.append("confirm_import", true); // Send confirmation flag

    fetch("pages/admin/process_importfile.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("Data imported successfully!");
            location.reload();
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => console.error("Error:", error));
});
</script> -->

<!-- START OF IMPORT EQUIPMENT -->
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
<!-- edit Equipment Modal end -->



<!-- edit equipment type -->
 <!-- Edit Type Modal -->
<div class="modal fade" id="editTypeModal" tabindex="-1" role="dialog" aria-labelledby="editTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTypeModalLabel">Edit Inventory Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editTypeForm">
                    <input type="hidden" id="editTypeId" name="type_id">
                    <div class="form-group">
                        <label for="editTypeName">Type Name</label>
                        <input type="text" class="form-control" id="editTypeName" name="type_name" required>

                        <label for="editTypeOrigin">Origin</label>
                        <select class="form-control" id="editTypeOrigin" name="type_origin" required>
                            <option value="Consumable">Consumable</option>
                            <option value="Non-Consumable">Non-Consumable</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $(document).on('click', '.edit-type', function() {
        var type_id = $(this).data('id');
        
        $.ajax({
            url: 'pages/admin/fetch_typedetails.php', // Fetch details for the selected type
            type: 'POST',
            data: { type_id: type_id },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    
                    $('#editTypeId').val(response.data.type_id);
                    $('#editTypeName').val(response.data.type_name);
                    $('#editTypeOrigin').val(response.data.type_origin);
                    $('#editTypeModal').modal('show');
                } else {
                    alert('Error fetching data.');
                }
            },
            error: function() {
                alert('Failed to fetch details.');
            }
        });
    });

    $('#editTypeForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: 'pages/admin/process_updatetype.php', // Process update request
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Type updated successfully!');
                    location.reload(); // Reload page to reflect changes
                } else {
                    alert('Update failed.');
                }
            },
            error: function() {
                alert('Error processing request.');
            }
        });
    });
});
</script>
<!-- end equip edit type -->

<script>
$(document).ready(function() {
    // On form submit
    $('#editEquipForm').on('submit', function(e) {
        e.preventDefault();  // Prevent default form submission
        // Get the form data
        var formData = {
            type_id: $('#inv_type').val(),
            brand: $('#brand').val(),
            model: $('#model').val(),
            serialno: $('#serialno').val(),
            propertyno: $('#propertyno').val(),
            propertyname: $('#propertyname').val()
        };

        // AJAX request
        $.ajax({
            type: 'POST',
            url: 'pages/admin/process_addequipment.php',  // The PHP file to process the data
            data: formData,
            dataType: 'json',
            success: function(response) {
                // Handle response
                if (response.status === 'success') {
                    alert(response.message);  // Success message
                    $('#editEquipModal').modal('hide');  // Hide the modal
                    // Optionally, reload the equipment list or update the UI here
                    
                    // Ensure the backdrop (dim screen) is removed
                    $('.modal-backdrop').remove(); // Remove the backdrop
                    $('body').removeClass('modal-open');  // Remove modal-open class
                } else {
                    alert(response.message);  // Error message
                }
            },
            error: function() {
                alert('Error in submitting form.');
            }
        });
    });
});
</script>
<!-- end of edit equipment modal -->

<!-- Start Select Equip Modal -->
<div class="modal fade" id="selectEquipModal" tabindex="-1" role="dialog" aria-labelledby="selectEquipModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectEquipModalLabel">Select Equipment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="equipTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Equipment Type</th>
                            <th>Brand Name</th>
                            <th>Serial Number</th>
                            <th>Property Number</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="equipTableBody">
                        <!-- Equipment data will be inserted here by AJAX -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Select EQUIP MODAL -->

<script>
$(document).ready(function(){
    $(".load-inv").click(function(){
        $.ajax({
            url: "modals.php",
            type: "POST",
            data: { action: "loadEquipmentModal" },
            success: function(response){
                $("#modalContainer").html(response);
                $("#addEquipmentModal").modal("show"); // Show modal
            }
        });
    });
});
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

</script>

<!-- end viewreturningItemList -->

<!-- Print Filter Modal -->
<div class="modal fade" id="printFilterModal" tabindex="-1" role="dialog" aria-labelledby="printFilterModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="printFilterModalLabel"><i class="fas fa-filter"></i> Print Inventory List</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="printFilterForm">
          <!-- Select Type -->
          <div class="form-group">
            <label for="filterType">Type</label>
            <select class="form-control" id="filterType" name="filterType">
              <option value="">All</option>
              <?php
              // Include DB connection
              require '../../function_connection.php';

              // Fetch types from the database
              $typeQuery = "SELECT type_id, type_name FROM tbl_type ORDER BY type_name ASC";
              $typeResult = $conn->query($typeQuery);

              if ($typeResult && $typeResult->num_rows > 0) {
                  while ($row = $typeResult->fetch_assoc()) {
                      echo '<option value="' . htmlspecialchars($row['type_id']) . '">' . htmlspecialchars($row['type_name']) . '</option>';
                  }
              }
              ?>
            </select>
          </div>

          <!-- Select Status -->
          <div class="form-group">
            <label for="filterStatus">Status</label>
            <select class="form-control" id="filterStatus" name="filterStatus">
              <option value="">All</option>
              <option value="1">Available</option>
              <option value="2">Unavailable</option>
              <option value="3">Pending</option>
              <option value="4">Borrowed</option>
              <option value="5">Returned</option>
              <option value="6">Missing</option>
            </select>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times"></i> Cancel
        </button>
        <button type="button" class="btn btn-success" id="applyPrintFilterBtn">
          <i class="fas fa-print"></i> Print
        </button>
      </div>

    </div>
  </div>
</div>

<script>
$('#applyPrintFilterBtn').on('click', function () {
    const type = $('#filterType').val();
    const status = $('#filterStatus').val();

    $.ajax({
        url: 'pages/admin/check_inventory_exists.php',
        method: 'GET',
        data: {
            type: type,
            status: status
        },
        dataType: 'json',
        success: function (response) {
            if (response.exists) {
                // Build the URL
                let url = 'pages/admin/process_createpdf.php?';
                if (type) url += 'type=' + encodeURIComponent(type) + '&';
                if (status) url += 'status=' + encodeURIComponent(status);

                // Open the report
                window.open(url, '_blank');

                // Close the modal
                $('#printFilterModal').modal('hide');
            } else {
                // Show SweetAlert if no data found
                Swal.fire({
                    icon: 'warning',
                    title: 'No matching data',
                    text: 'No inventory items match the selected filters.',
                    confirmButtonColor: '#007bff'
                });
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong while checking the data.',
                confirmButtonColor: '#dc3545'
            });
        }
    });
});
</script>



<!-- Modal for Manual Add (Available Items) -->
<style>
    #dataTableModal {
        font-size: 13px; /* Smaller font size */
    }

    #dataTableModal th,
    #dataTableModal td {
        padding: 4px 8px; /* Tighter cell padding */
        white-space: nowrap; /* Prevent text from wrapping */
        vertical-align: middle; /* Align text vertically center */
    }

    /* Optional: Reduce the header font size slightly */
    #dataTableModal thead th {
        font-size: 12.5px;
    }
</style>



<div class="modal fade" id="manualAddModal" tabindex="-1" role="dialog" aria-labelledby="manualAddModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manualAddModalLabel">Available Items</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Scrollable table container -->
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table id="dataTableReqItem" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Brand/Model</th>
                                <th>Serial No.</th>
                                <th>Property No.</th>
                                <th>Division/Section</th>
                                <th>Select</th>
                            </tr>
                        </thead>
                        <tbody id="modalItemTableBody">
                            <!-- Data will be populated here from the database via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addSelectedItems">Add Selected Items</button>
            </div>
        </div>
    </div>
</div>