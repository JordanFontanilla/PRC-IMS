<?php
    $sidebar_class = 'bg-gradient-primary'; // Default for Admin
    if (isset($_SESSION['user_level']) && $_SESSION['user_level'] === 'Employee') {
        $sidebar_class = 'bg-gradient-info'; // Blue for Employee
    }

    $is_admin = isset($_SESSION['user_level']) && $_SESSION['user_level'] === 'Admin';
?>
<ul class="navbar-nav <?= $sidebar_class ?> sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon">
            <i class="fas fa-desktop"></i>
        </div>
        <div class="sidebar-brand-text mx-3">PRC•CAR<br><sub>IMS</sub></div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item">
        <a class="nav-link load-dashboard" id="sidedashboard" href="javascript:void(0);">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">Management</div>

    <li class="nav-item">
    <?php if ($is_admin): ?>
        <a class="nav-link load-user" id="sideuser" href="javascript:void(0);">
            <i class="fas fa-user"></i>
            <span>User</span>
        </a>
        <?php endif; ?>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" id="invdropdown" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-briefcase"></i>
            <span>Inventory</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Operations:</h6>
                <a class="collapse-item load-inventorylist" id="invlist" href="javascript:void(0);">Inventory List</a>
                <a class="collapse-item load-requestpage" id="reqequip" href="javascript:void(0);">Request Equipment</a>

                <?php if ($is_admin): ?>
                    <a class="collapse-item load-pendingrequest" href="javascript:void(0);">Pending Request [Admin]</a>
                    <hr>
                <?php endif; ?>
                <h6 class="collapse-header">Item Status:</h6>
                <a class="collapse-item load-borrowedlist" href="javascript:void(0);">Borrowed Equipment</a>
                <a class="collapse-item load-returnlist" href="javascript:void(0);">Returned Equipment</a>
                <a class="collapse-item load-missinglist" href="javascript:void(0);">Missing Equipment</a>
            </div>
        </div>
    </li>

    <?php if ($is_admin): ?>
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Utilities</div>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                aria-expanded="true" aria-controls="collapseUtilities">
                <i class="fas fa-fw fa-wrench"></i>
                <span>Utilities</span>
            </a>
            <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Operations:</h6>
                    <a class="collapse-item load-invtype" href="javascript:void(0);">Inventory Type</a>
                    <a class="collapse-item load-auditlog" href="javascript:void(0);">Audit Log</a>
                    <!-- <hr> -->
                    <!-- <a class="collapse-item" href="javascript:void(0);" id="backupButton">Backup Database</a> -->
                </div>
            </div>
        </li>
    <?php endif; ?>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>

<form id="backupForm" method="post" style="display: none;">
    <button type="submit" name="backup_db">Backup Database</button>
</form>

<script>
        function logAuditAction(actionText) {
        fetch('pages/admin/log_audit_action.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=' + encodeURIComponent(actionText)
        });
    }

    document.getElementById("sidedashboard").addEventListener("click", function () {
        logAuditAction("Clicked Dashboard Side Nav");
    });

    document.getElementById("sideuser").addEventListener("click", function () {
        logAuditAction("Clicked User Management Side Nav");
    });

    document.getElementById("invdropdown").addEventListener("click", function () {
        logAuditAction("Clicked Inventory Dropdown Side Nav");
    });

    document.getElementById("invlist").addEventListener("click", function () {
        logAuditAction("Clicked Inventory List Side Nav");
    });

    document.getElementById("reqequip").addEventListener("click", function () {
        logAuditAction("Clicked Request Equipment Side Nav");
    });

</script>