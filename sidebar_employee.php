<ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
    <div class="sidebar-brand-icon">
        <i class="fas fa-desktop"></i>
    </div>
    <div class="sidebar-brand-text mx-3">PRC•CAR<br><sub>IMS</sub></div>
</a>
<hr class="sidebar-divider my-0">
<li class="nav-item">
    <a class="nav-link load-dashboard" href="javascript:void(0);">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
</li>
<hr class="sidebar-divider">
<div class="sidebar-heading">Management</div>


<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
        aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-briefcase"></i>
        <span>Inventory</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Operations:</h6>
            <a class="collapse-item load-inventorylist" href="javascript:void(0);">Inventory List</a>
            <a class="collapse-item load-request" href="javascript:void(0);">Request Equipment</a>
            <hr>
            <a class="collapse-item load-borrowedlist" href="javascript:void(0);">Borrowed Equipment</a>
            <a class="collapse-item load-returnlist" href="javascript:void(0);">Returned Equipment</a>
            <a class="collapse-item load-missinglist" href="#">Missing List</a>
            <a class="collapse-item load-consumeditems" href="#">Consumed Items</a>
        </div>
    </div>
</li>

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
            <a class="collapse-item load-invtype" href="javascript:void(0);">Audit Log</a>
            <hr>
            <a class="collapse-item load-invtype" href="javascript:void(0);">Backup Database</a>
            <a class="collapse-item load-invtype" href="javascript:void(0);">Import Data</a>
            <a class="collapse-item load-invtype" href="javascript:void(0);">Request History</a>
        </div>
    </div>
    

    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLogs"
        aria-expanded="true" aria-controls="collapseLogs">
        <i class="fas fa-fw fa-wrench"></i>
        <span>Logs</span>
    </a>
    <div id="collapseLogs" class="collapse" aria-labelledby="headingLogs"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Operations:</h6>
            <a class="collapse-item load-invtype" href="javascript:void(0);">Request Logs</a>
            <a class="collapse-item load-invtype" href="javascript:void(0);">Approval Logs</a>
            <a class="collapse-item load-invtype" href="javascript:void(0);">Return Logs</a>
            <a class="collapse-item load-invtype" href="javascript:void(0);">Login Logs</a>
            <a class="collapse-item load-invtype" href="javascript:void(0);">Audit Logs</a>
        </div>
    </div>
    
</li>

<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>