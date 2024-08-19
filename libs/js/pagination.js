var dataTable = $(".datatable").DataTable({
  responsive: true,
  language: {
    search: "Buscar:",
    lengthMenu: "Mostrar _MENU_ elementos",
    info: '<p class="siguiente">Mostrando del _START_ al _END_ de _TOTAL_ elementos en total</p>',
    paginate: {
      previous: '<p class="siguiente">Anterior</p>',
      next: '<p class="siguiente">Siguiente</p>',
      last: '<p class="siguiente">Ultimo</p>',
    },
  },
  buttons: [ //Descomentar para que salga el botón de Excel
    //{
      // extend: "excel",
      // text: "Exportar en Excel",
      // className: "btn-md btn-excel",
    //},
  ],
  dom:
    "<'row'<'col-md-3'l><'col-md-5 text-center'B><'col-md-3'f>>" +
    "<'row'<'col-md-12'tr>>" +
    "<'row'<'col-md-5'i><'col-md-7'p>>",
  drawCallback: function (settings) {
    if (!$(".datatable").parent().hasClass("table-responsive")) {
      $(".datatable").wrap("<div class='table-responsive'></div>");
    }
  },
});

dataTable.columns().every(function () {
  var column = this;

  $(".filter-column", this.footer()).on("keyup change", function () {
    if (column.search() !== this.value) {
      column.search(this.value).draw();
      this.focus();
    }
  });
});
