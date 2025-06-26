<script>
$(document).ready(function () {
    // Function to load content via Ajax
    function loadContent(url, clearSelectedInventory = false) {
        if (clearSelectedInventory) {
            sessionStorage.removeItem('selectedInventoryPage');
        }

        $.ajax({
            url: url,
            type: 'GET',
            success: function (response) {
                $('#main-content').html(response);
                sessionStorage.setItem('lastPage', url);
            },
            error: function () {
                alert('Failed to load content.');
            }
        });
    }

    // Load the last loaded page from sessionStorage, or default to dashboard if not set
    var lastPage = sessionStorage.getItem('lastPage') || 'pages/admin/page_dashboard.php';
    loadContent(lastPage); // Load the last page or the default dashboard

    // Use delegated event handling for dynamic compatibility
    $(document).on('click', '.load-dashboard', function () {
        loadContent('pages/admin/page_dashboard.php');
    });

    $(document).on('click', '.load-user', function () {
        loadContent('pages/admin/page_user.php');
    });

    $(document).on('click', '.load-inventorylist', function () {
        sessionStorage.removeItem('selectedInventoryPage');
        loadContent('pages/admin/page_inventorytypeselect.php');
    });

    $(document).on('click', '.load-request', function () {
        loadContent('pages/admin/page_request.php');
    });

    $(document).on('click', '.load-pendingrequest', function () {
        loadContent('pages/admin/page_pendingrequest.php');
    });

    $(document).on('click', '.load-borrowedlist', function () {
        loadContent('pages/admin/page_borrowedlist.php');
    });

    $(document).on('click', '.load-return', function () {
        loadContent('pages/admin/page_returns.php');
    });

    $(document).on('click', '.load-invtype', function () {
        loadContent('pages/admin/page_inventorytype.php');
    });

    $(document).on('click', '.load-auditlog', function () {
        loadContent('pages/admin/page_auditlog.php');
    });

    $(document).on('click', '.load-returnlist', function () {
        loadContent('pages/admin/page_returnlist.php');
    });

    $(document).on('click', '.load-missinglist', function () {
        loadContent('pages/admin/page_missinginventorylist.php');
    });

    $(document).on('click', '.load-requestpage', function () {
        loadContent('pages/admin/page_itemrequest.php');
    });
});
</script>
