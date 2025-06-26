$(document).ready(function () {
  $('#dataTable').DataTable();

  var userTable = $('#dataTableUser').DataTable({
    order: [],
    columnDefs: [
      { orderable: false, targets: -1 }
    ]
  });

  // Inject beside the 'Show entries' selector
  var $lengthContainer = $('#dataTableUser_length');
  $lengthContainer.addClass('d-flex align-items-center gap-2'); // Bootstrap flex row

  // Append filter beside it
  $lengthContainer.append($('#userLevelFilterContainer'));

  // Filter logic
  $('#userLevelFilter').on('change', function () {
    var selectedLevel = $(this).val();
    userTable.column(2).search(selectedLevel).draw();
  });



});
//end of Add User Data Table