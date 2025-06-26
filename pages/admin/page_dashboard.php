<?php include("../../function_connection.php");

 ?>
<head><style>

@keyframes rgbShadow {
    0% { box-shadow: 0 0 15px 5px red; }
    33% { box-shadow: 0 0 15px 5px green; }
    66% { box-shadow: 0 0 15px 5px blue; }
    100% { box-shadow: 0 0 15px 5px red; }
}

/* Override Bootstrap card styles to apply RGB shadow */
.card.border-left-primary,
.card.border-left-success,
.card.border-left-info,
.card.border-left-danger {
    transition: transform 0.25s ease-in-out, box-shadow 0.8s ease-in-out;
}

.card.border-left-primary:hover,
.card.border-left-success:hover,
.card.border-left-info:hover,
.card.border-left-danger:hover {
    animation: rgbShadow 1.5s infinite alternate;
    transform: scale(1.05);
}


#equipmentPieChart {
    max-width: 500px !important;  /* Keep width wide */
    max-height: 300px !important; /* Shortened height */
    width: 100% !important;
    height: auto !important;  /* Maintain aspect ratio */
}

</style></head>
<h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
<hr>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2" id="totalEquipmentCard" style="cursor: pointer;">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Total Equipment</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalEquipment">Loading...</div>
                </div>
                <div class="col-auto">
                    <i class="fa fa-warehouse fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2" id="totalAvailableEquipmentCard" style="cursor: pointer;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Available Equipment</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="availableEquipment">Loading...</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-laptop  fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2" id="totalPendingEquipmentCard" style="cursor: pointer;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info   text-uppercase mb-1">
                            Pending Request</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="pendingEquipment">Loading...</div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2" id="totalMissingEquipmentCard" style="cursor: pointer;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Missing Equipment</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="missingEquipment">Loading...</div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-eye-slash fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-6"> <!-- Keep the wider layout -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Equipment Status Overview</h6>
        </div>
        <div class="card-body d-flex justify-content-center align-items-center">
            <canvas id="equipmentPieChart"></canvas>
        </div>
    </div>
</div>


<!-- Inventory Count - Scrollable -->
<div class="col-lg-3" id="inventorycountcard">
    <div class="card shadow mb-4"> <!-- Match the height of the pie chart -->
        <a href="#collapseInventory" class="d-block card-header py-3" data-toggle="collapse"
           role="button" aria-expanded="true" aria-controls="collapseInventory">
            <h6 class="m-0 font-weight-bold text-primary">Inventory Count</h6>
        </a>
        <div class="collapse show" id="collapseInventory">
            <div class="card-body" style="max-height: 290px; overflow-y: auto;"> 
                <ul id="inventoryList" class="list-group list-group-flush">
                    <li class="list-group-item text-center">Loading...</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Recent Borrowers - Scrollable -->
<div class="col-lg-3" id="recentborrowerscard">
    <div class="card shadow mb-4"> <!-- Match the height of the pie chart -->
        <a href="#collapseBorrowers" class="d-block card-header py-3" data-toggle="collapse"
           role="button" aria-expanded="true" aria-controls="collapseBorrowers">
            <h6 class="m-0 font-weight-bold text-primary">Recent Borrowers</h6>
        </a>
        <div class="collapse show" id="collapseBorrowers">
            <div class="card-body" style="max-height: 290px; overflow-y: auto;"> 
                <ul id="borrowersList" class="list-group list-group-flush">
                    <li class="list-group-item text-center">Loading...</li>
                </ul>
            </div>
        </div>
    </div>
</div>




<script>
$(document).ready(function() {
    // Fetch equipment count via AJAX
    $.ajax({
        url: 'pages/admin/fetch_dashboardequipmentcount.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#totalEquipment').text(response.allequip);
            $('#availableEquipment').text(response.allavailequip);
            $('#missingEquipment').text(response.allmissequip);
            $('#pendingEquipment').text(response.allpendequip);
        },
        error: function(xhr, status, error) {
            console.error("Error fetching equipment count:", error);
            $('#totalEquipment, #availableEquipment, #missingEquipment, #pendingEquipment').text('Error');
        }
    });

    // Click event for Total Equipment card
    $('#totalEquipmentCard').on('click', function() {
        Swal.fire({
            title: 'Loading Inventory List',
            text: 'Please wait...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
                $.ajax({
                    url: 'pages/admin/page_inventorylist.php',
                    type: 'GET',
                    success: function(response) {
                        $('#main-content').html(response);
                        Swal.close();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading inventory page:", error);
                        Swal.fire('Error', 'Failed to load the inventory page.', 'error');
                    }
                });
            }
        });
    });

    $('#totalAvailableEquipmentCard').on('click', function() {
        Swal.fire({
            title: 'Loading Pending Request Page',
            text: 'Please wait...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
                $.ajax({
                    url: 'pages/admin/page_inventorytypeselect.php',
                    type: 'GET',
                    success: function(response) {
                        $('#main-content').html(response);
                        Swal.close();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading available inventory page:", error);
                        Swal.fire('Error', 'Failed to load the page.', 'error');
                    }
                });
            }
        });
    });

        // Click event for Total Equipment card
        $('#totalPendingEquipmentCard').on('click', function() {
        Swal.fire({
            title: 'Loading Pending Requests List',
            text: 'Please wait...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
                $.ajax({
                    url: 'pages/admin/page_pendingrequest.php',
                    type: 'GET',
                    success: function(response) {
                        $('#main-content').html(response);
                        Swal.close();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading pending request page:", error);
                        Swal.fire('Error', 'Failed to load the page.', 'error');
                    }
                });
            }
        });
    });


    $('#totalMissingEquipmentCard').on('click', function() {
        Swal.fire({
            title: 'Loading Missing Equipment List',
            text: 'Please wait...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
                $.ajax({
                    url: 'pages/admin/page_missinginventorylist.php',
                    type: 'GET',
                    success: function(response) {
                        $('#main-content').html(response);
                        Swal.close();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading missing inventory page:", error);
                        Swal.fire('Error', 'Failed to load the page.', 'error');
                    }
                });
            }
        });
    });

    // Hover effect for dashboard cards

});


$(document).ready(function() {
    // Fetch equipment count via AJAX
    $.ajax({
        url: 'pages/admin/fetch_dashboardequipmentcount.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#totalEquipment').text(response.allequip);
            $('#availableEquipment').text(response.allavailequip);
            $('#missingEquipment').text(response.allmissequip);
            $('#borrowedEquipment2').text(response.allborrequip2);
            $('#borrowedEquipment').text(response.allborrequip);

            // Initialize Pie Chart after receiving data
            var ctx = document.getElementById('equipmentPieChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Available', 'Pending', 'Missing', 'Borrowed'],
                    datasets: [{
                        data: [
                            response.allavailequip, // Available count
                            response.allborrequip2,  // Pending count
                            response.allmissequip,   // Missing count
                            response.allborrequip
                        ],
                        backgroundColor: ['#28a745', '#17a2b8', '#dc3545', '#f2daba']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        },
        error: function(xhr, status, error) {
            console.error("Error fetching equipment count:", error);
            $('#totalEquipment, #availableEquipment, #missingEquipment, #pendingEquipment, #borrowedEquipment').text('Error');
        }
    });
});


$(document).ready(function() {
    $.ajax({
        url: 'pages/admin/fetch_inventorycount.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            var listHtml = "";
            response.forEach(function(item) {
                listHtml += `<li class="list-group-item d-flex justify-content-between align-items-center">
                                ${item.type_name}
                                <span class="badge badge-primary badge-pill">${item.count}</span>
                             </li>`;
            });
            $('#inventoryList').html(listHtml);
        },
        error: function(xhr, status, error) {
            console.error("Error fetching inventory count:", error);
            $('#inventoryList').html('<li class="list-group-item text-danger">Error loading data.</li>');
        }
    });
});


$(document).ready(function() {
    $.ajax({
        url: 'pages/admin/fetch_recentborrowers.php', // PHP script to retrieve data
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            var listHtml = "";
            if (response.length > 0) {
                response.forEach(function(borrower) {
                    listHtml += `<li class="list-group-item d-flex justify-content-between align-items-center">
                                    ${borrower.emp_name}
                                    <span class="badge badge-primary badge-pill">${borrower.item_count}</span>
                                 </li>`;
                });
            } else {
                listHtml = '<li class="list-group-item text-center">No recent borrowers</li>';
            }
            $('#borrowersList').html(listHtml);
        },
        error: function(xhr, status, error) {
            console.error("Error fetching recent borrowers:", error);
            $('#borrowersList').html('<li class="list-group-item text-danger">Error loading data.</li>');
        }
    });
});

    function logAuditAction(actionText) {
        fetch('pages/admin/log_audit_action.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=' + encodeURIComponent(actionText)
        });
    }

    document.getElementById("totalEquipmentCard").addEventListener("click", function () {
        logAuditAction("Clicked Total Equipment card");
    });

    document.getElementById("totalAvailableEquipmentCard").addEventListener("click", function () {
        logAuditAction("Clicked Available Equipment card");
    });

    document.getElementById("totalPendingEquipmentCard").addEventListener("click", function () {
        logAuditAction("Clicked Pending Request card");
    });

    document.getElementById("equipmentPieChart").addEventListener("click", function () {
        logAuditAction("Clicked Equipment Pie Chart card");
    });

    document.getElementById("inventorycountcard").addEventListener("click", function () {
        logAuditAction("Clicked Inventory Count card");
    });

    document.getElementById("recentborrowerscard").addEventListener("click", function () {
        logAuditAction("Clicked Recent Borrowers card");
    });




</script>




<!-- JavaScript for Real-time Clock and Collapse Handling -->

<?php include '../../sources2.php'; ?>
