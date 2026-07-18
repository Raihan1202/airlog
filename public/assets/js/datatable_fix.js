$(function() {
  $("#logbook_list").DataTable({
    "responsive": true,
    "lengthChange": false,
    "autoWidth": false,
    "pageLength": 5,
    "dom": 'Bfltip',
    "language": {
    "search": ""
    }
  });
  $('.dataTables_filter input').attr('placeholder', 'Search records...');
  $('.dataTables_filter input').keyup(function() {
    if ($(this).val().length > 0) {
      $(this).attr('placeholder', '');
    } else {
      $(this).attr('placeholder', 'Search records...');
    }
  });
});