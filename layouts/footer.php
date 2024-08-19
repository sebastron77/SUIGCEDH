     </div>
     </div>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
     <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
     <script type="text/javascript" src="libs/js/functions.js"></script>
     <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script> Para las gráficas -->
     <!-- LO COMENTE POR QUE YA NO SE USA ESE BUSCADOR -->
     <!-- <script type="text/javascript" src="libs/js/busqueda.js"></script> -->
     <!-- <script type="text/javascript" src="asignacion_vehiculo.js"></script> -->

     <!-- <script type="text/javascript" src="libs/js/functions_vehiculo.js"></script>   -->


     <!-- <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css"> -->
     <!-- <link rel="stylesheet" type="text/css" href="dataTables.bootstrap.css"> -->

     <!-- <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.11.1.min.js"></script> -->

     <!-- <script src="//code.jquery.com/jquery-1.11.3.min.js"></script> -->
     <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">

     <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>

     <!-- <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script> -->
     <!-- Responsive extension -->
     <!-- <script src="https://cdn.datatables.net/responsive/2.1.0/js/responsive.bootstrap.min.js"></script> -->
     <!-- Buttons extension -->
     <!-- <script src="//cdn.datatables.net/buttons/1.2.1/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.2.1/js/buttons.bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.2.1/js/buttons.html5.min.js"></script> -->
     <!-- LA COMENTE PORQUE NO EXISTE -->
     <!-- <script type="text/javascript" src="prueba_agregar.js"></script> -->

     <!-- Antes no tenia el /libs/js/, sólo pagination.js-->
     <!-- <script src="/libs/js/pagination.js"></script>  -->
     <script type="text/javascript" src="libs/js/pagination.js"></script>

     <link href="https://harvesthq.github.io/chosen/chosen.css" rel="stylesheet" />
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
     <script src="https://harvesthq.github.io/chosen/chosen.jquery.js"></script>

     <script language="javascript">
       function myFunction() {
         var popup = document.getElementById("myPopup");
         popup.classList.toggle("show");
       }
       $(document).ready(function() {
         $("#se_turna_a_area").change(function() {
           //$('#cbx_localidad').find('option').remove().end().append(
           //    '<option value="whatever"></option>').val('whatever');

           $("#se_turna_a_area option:selected").each(function() {
             nombre_area = $(this).val();
             $.post("buscar.php", {
               nombre_area: nombre_area
             }, function(data) {
               $("#se_turna_a_trabajador").html(data);
             })
           })

         })
       });

       $(document).ready(function() {
         $("#se_turna_a_area").change(function() {
           //$('#cbx_localidad').find('option').remove().end().append(
           //    '<option value="whatever"></option>').val('whatever');

           $("#se_turna_a_area option:selected").each(function() {
             nombre_area2 = $(this).val();
             $.post("buscar2.php", {
               nombre_area2: nombre_area2
             }, function(data) {
               $("#se_turna_a_trabajador_editar").html(data);
             })
           })

         })
       });


       $(document).ready(function() {
         $('#myTable').DataTable();
       });
     </script>

     <script>
       $(function() {
         // Clona la fila oculta que tiene los campos base, y la agrega al final de la tabla
         $("#adicional").on('click', function() {
           $("#tabla tbody tr:eq(0)").clone().removeClass('fila-fija').appendTo("#tabla");
         });

         // Evento que selecciona la fila y la elimina 
         $(document).on("click", ".eliminar", function() {
           var parent = $(this).parents().get(0);
           $(parent).remove();
         });
       });
     </script>

     </body>

     <script>
       $(document).ready(function() {
         $('[data-toggle="popover"]').popover();
       });
     </script>

     </html>

     <?php if (isset($db)) {
        $db->db_disconnect();
      } ?>