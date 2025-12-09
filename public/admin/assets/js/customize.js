$(document).ready(function () {
  $.fn.dataTable.ext.errMode = 'none';

  $('#table').DataTable({
    language: {
      search: "Tìm kiếm:",
      lengthMenu: "Hiển thị _MENU_ dòng",
      info: "Hiển thị _START_ đến _END_ của _TOTAL_ dòng",
      infoEmpty: "Không có dữ liệu để hiển thị",
      zeroRecords: "Không tìm thấy dữ liệu phù hợp",
      emptyTable: "Không có dữ liệu nào trong bảng",
      paginate: {
        first: "Đầu",
        last: "Cuối",
        next: "<i class='fas fa-chevron-right'></i>",
        previous: "<i class='fas fa-chevron-left'></i>"
      },
    },
    responsive: true,
    pageLength: 10,
    lengthMenu: [5, 10, 25, 50],
    order: [],
    autoWidth: false,
  });
});
