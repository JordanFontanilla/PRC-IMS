<?php include("../../function_connection.php"); ?>

<head>
<style>
    .card-hover-effect {
        transition: transform 0.3s ease;
        border: none;
        color: white;
    }

    .card-hover-effect:hover {
        transform: scale(1.05);
    }

    .icon-xl {
        font-size: 20rem;
        transition: color 0.3s ease;
    }

    .bg-non-consumables {
        background-color: rgb(62, 97, 193) !important;
    }

    .bg-consumables {
        background-color: rgb(53, 185, 103) !important;
    }

    .card-hover-effect:hover .icon-primary {
        color: #3b82f6 !important;
    }

    .card-hover-effect:hover .icon-success {
        color: #4ade80 !important;
    }

    .tooltip-custom {
        position: absolute;
        background-color: rgba(0, 0, 0, 0.75);
        color: white;
        border-radius: 5px;
        padding: 5px 10px;
        font-size: 14px;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
</style>
</head>

<h1 class="h3 mb-0 text-gray-800">Select Inventory Type</h1>
<hr>
<div class="row">
    <!-- Non-Consumables Card -->
    <div class="col-md-6 mb-4">
        <div class="card card-hover-effect bg-non-consumables h-100 d-flex justify-content-center align-items-center" id="totalEquipmentCard" style="cursor: pointer;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                <i class="fas fa-laptop mb-3 text-white icon-xl icon-primary"></i>
                <div>
                    <div class="text-white text-uppercase font-weight-bold mb-1">Non-Consumables</div>
                    <div class="h4 font-weight-bold text-white" id="totalEquipment">Loading...</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Consumables Card -->
    <div class="col-md-6 mb-4">
        <div class="card card-hover-effect bg-consumables h-100 d-flex justify-content-center align-items-center" id="totalAvailableEquipmentCard" style="cursor: pointer;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                <i class="fa fa-leaf mb-3 text-white icon-xl icon-success"></i>
                <div>
                    <div class="text-white text-uppercase font-weight-bold mb-1">Consumables</div>
                    <div class="h4 font-weight-bold text-white" id="availableEquipment">Loading...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    // Load saved inventory page on refresh
    const savedPage = sessionStorage.getItem('selectedInventoryPage');
    if (savedPage) {
        $.ajax({
            url: savedPage,
            type: 'GET',
            success: function (response) {
                $('#main-content').html(response);
            },
            error: function () {
                console.error('Failed to load saved inventory page.');
            }
        });
        return; // Stop further execution so cards don't show again
    }

    // Fetch equipment counts
    $.ajax({
        url: 'pages/admin/fetch_dashboardequipmentcount.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            $('#totalEquipment').text(response.allnoncon);
            $('#availableEquipment').text(response.allcon);
        },
        error: function () {
            $('#totalEquipment, #availableEquipment').text('Error');
        }
    });

    // Load Non-Consumables Page
    $('#totalEquipmentCard').on('click', function () {
        sessionStorage.setItem('selectedInventoryPage', 'pages/admin/page_inventorylist_nonconsumable.php');

        Swal.fire({
            title: 'Loading Inventory List',
            text: 'Please wait...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
                $.ajax({
                    url: 'pages/admin/page_inventorylist_nonconsumable.php',
                    type: 'GET',
                    success: function (response) {
                        $('.tooltip-custom').remove(); // Remove lingering tooltips
                        $('#main-content').html(response);
                        Swal.close();
                    },
                    error: function () {
                        Swal.fire('Error', 'Failed to load the inventory page.', 'error');
                    }
                });
            }
        });
    });

    // Load Consumables Page
    $('#totalAvailableEquipmentCard').on('click', function () {
        sessionStorage.setItem('selectedInventoryPage', 'pages/admin/page_inventorylist_consumable.php');

        Swal.fire({
            title: 'Loading Available Request Page',
            text: 'Please wait...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
                $.ajax({
                    url: 'pages/admin/page_inventorylist_consumable.php',
                    type: 'GET',
                    success: function (response) {
                        $('.tooltip-custom').remove(); // Remove lingering tooltips
                        $('#main-content').html(response);
                        Swal.close();
                    },
                    error: function () {
                        Swal.fire('Error', 'Failed to load the page.', 'error');
                    }
                });
            }
        });
    });

    //     // Tooltips
    //     let tooltipTimeout;
    //     let tooltipNonConsumables = $('<div class="tooltip-custom">Laptops and Peripherals</div>').appendTo('body');
    //     let tooltipConsumables = $('<div class="tooltip-custom">Pens, Papers etc...</div>').appendTo('body');

    //     $('#totalEquipmentCard').hover(function (e) {
    //         tooltipTimeout = setTimeout(() => {
    //             tooltipNonConsumables.css({ top: e.pageY + 10, left: e.pageX + 10 }).stop().animate({ opacity: 1 }, 300);
    //         }, 500);
    //     }, function () {
    //         clearTimeout(tooltipTimeout);
    //         tooltipNonConsumables.stop().animate({ opacity: 0 }, 200);
    //     }).mousemove(function (e) {
    //         tooltipNonConsumables.css({ top: e.pageY + 10, left: e.pageX + 10 });
    //     });

    // $('#totalAvailableEquipmentCard').hover(function (e) {
    //     tooltipTimeout = setTimeout(() => {
    //         tooltipConsumables.css({ top: e.pageY + 10, left: e.pageX + 10 }).stop().animate({ opacity: 1 }, 300);
    //     }, 500);
    // }, function () {
    //     clearTimeout(tooltipTimeout);
    //     tooltipConsumables.stop().animate({ opacity: 0 }, 200);
    // }).mousemove(function (e) {
    //     tooltipConsumables.css({ top: e.pageY + 10, left: e.pageX + 10 });
    // });

    // Reset saved page when navigating from sidebar
    $('.load-dashboard, .load-user, .load-inventorylist, .load-requestpage, .load-pendingrequest, .load-borrowedlist, .load-returnlist, .load-missinglist').on('click', function () {
        sessionStorage.removeItem('selectedInventoryPage');
    });
});
</script>

<?php include '../../sources2.php'; ?>
