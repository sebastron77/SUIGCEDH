<%-- 
    Document   : desp_tipo_reportes
    Created on : 8/02/2018, 12:35:37 PM
    Author     : perla
--%>

<%@page contentType="text/html"%>
<%@page pageEncoding="ISO-8859-1"%>
<%@page
    import = "com.generales.*"
    import = "java.sql.*"
    import = "own.codec.*"
    %>

    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
            <title>Reportes</title>
            <%
                out.println("<link href='" + request.getContextPath() + Config.getParametro("css") + "' rel='stylesheet' type='text/css' />");
                String nompag[] = request.getRequestURI().split("/");
            %>
            <link  href='../comun/css/style_tables.css' rel='stylesheet' type='text/css' /> 
            <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
            <link rel="stylesheet" type="text/css" href="../comun/css/jquery.datepick.css"> 
            <script type="text/javascript" src="../comun/js/jquery.plugin.js"></script> 
            <script type="text/javascript" src="../comun/js/jquery.datepick.js"></script>
            <script type="text/javascript" src="../comun/js/jquery.datepick-es.js"></script>


            <script language="javascript">
                $(function () {
                    $('#fecha_ingreso_inicio').datepick({appendText: "(yyyy-mm-dd)"});
                    $('#fecha_ingreso_fin').datepick({appendText: "(yyyy-mm-dd)"});

                    $("#nombre_solicitante").keyup(function () {
                        $.ajax({
                            type: "POST",
                            url: "get_solicitantes.jsp",
                            data: 'keyword=' + $(this).val(),
                            beforeSend: function () {
                                $("#nombre_solicitante").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                            },
                            success: function (data) {
                                $("#suggesstion-box").show();
                                $("#suggesstion-box").html(data);
                                $("#nombre_solicitante").css("background", "#FFF");
                            }
                        });
                    });

                    $("#folio").keyup(function () {
                        $.ajax({
                            type: "POST",
                            url: "get_folio.jsp",
                            data: 'folio=' + $(this).val(),
                            beforeSend: function () {
                                $("#folio").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                            },
                            success: function (data) {
                                $("#suggesstion-folio").show();
                                $("#suggesstion-folio").html(data);
                                $("#folio").css("background", "#FFF");
                            }
                        });
                    });


                });

                function selectCountry(val) {
                    $("#nombre_solicitante").val(val);
                    $("#suggesstion-box").hide();
                }

                function selectFolio(val) {
                    $("#folio").val(val);
                    $("#suggesstion-folio").hide();
                }
                
                function Exportar(tipo) {
                    //alert(tipo);
                    var namePag = "";
                    var FormExportar = $('#datos_reporte');
                    
                    if(tipo == 1){
                        namePag = "exce_exporta_excel.jsp";
                    }else if(tipo == 2){
                        namePag = "exce_exportar_pdf.jsp";                        
                    }else if(tipo == 3){
                        namePag = "exce_exporta_excel_anual.jsp";                        
                    }else if(tipo == 4){
                        namePag = "exce_exportar_pdf_anual.jsp";                        
                    }
                    FormExportar.attr("action", namePag );
                    FormExportar.submit();
                }
            </script>
        </head>
        <body style="margin:0px; width:100%;" >
            <%
                Conexion c = null;
                Connection lcnt_con = null;
                Statement lst_stm = null;
                ResultSet lrs_res = null;
                String ls_query = new String("");
                String debug = new String("si");

                String emergent_error = new String("");
                boolean lb_connection = false;
                boolean perfil_admon = false;

                // Conexión a la base de datos
                try {
                    if (!Config.getParametro("debug").equals("si")) {
                        debug = "no";
                    }
                    c = new Conexion();
                    lcnt_con = c.getConnection();
                    lst_stm = lcnt_con.createStatement();
                    lb_connection = true;
                } catch (Exception e) {
                    emergent_error = "Error al conectarse a la base de datos ";
                    if (debug.equals("si")) {
                        emergent_error += e;
                    }
                    lb_connection = false;
                }

                //********* Validación fundamental para evitar ejecución de código sin privilegios suficientes **********//
                try {
                    if (!Permisos.PermisoModulo(nompag[nompag.length - 1].toString(), Integer.parseInt(session.getAttribute("id_user").toString()))) {
                        if (lb_connection) {
                            lst_stm.close();
                            lcnt_con.close();
                        }
                        emergent_error += " Privilegios insuficientes. ";
                        lb_connection = false;
                    }
                } catch (Exception e) {
                    emergent_error += " Privilegios insuficientes. ";
                    if (debug.equals("si")) {
                        emergent_error += "Trace: " + e.toString();
                    }
                    lb_connection = false;
                }
                //*******************************************************************************************************//

                if (lb_connection) {
                    try {
                        perfil_admon = Permisos.PermisoModulo("aux_administrador.jsp", Integer.parseInt(session.getAttribute("id_user").toString()));

            %>
            <form id="datos_reporte" name="datos_reporte" action="" method="POST" target="_blank" >                
                <div class="etiqueta">Reportes Solicitudes</div>   
                </br>
                <table class="tablaDatos" border="1" width="80%">            
                    <tbody>                        
                        <tr>
                            <td class="campo" style="width: 25%">Sujeto obligado:</td>
                            <td class="derecha" colspan="3" >
                                <select id="id_cat_sujetos_obligados" name="id_cat_sujetos_obligados">
                                    <option value="0">--Seleccione Sujeto Obligado--</option>     
                                    <%                                        ls_query = "SELECT id_cat_sujetos_obligados,siglas,nombre_sujeto_obligado "
                                                + "FROM cat_sujetos_obligados "
                                                + "WHERE id_cat_sujetos_obligados > 0 "
                                                + " AND visible = true  "
                                                + "ORDER by nombre_sujeto_obligado ";
                                        lrs_res = lst_stm.executeQuery(ls_query);
                                        while (lrs_res.next()) {
                                    %>
                                    <option value="<%=lrs_res.getString("id_cat_sujetos_obligados")%>"><%=lrs_res.getString("nombre_sujeto_obligado")%></option>   
                                    <%
                                        }
                                    %>
                                </select>
                            </td>
                        </tr>                                                            
                        <tr>
                            <td class="campo" >Solicitante</td>
                            <td class="derecha" colspan="3">
                                <div class="frmSearch">                                    
                                    <input type="text" name="nombre_solicitante" id="nombre_solicitante" class="cuadro_texto" maxlength="10" style="width: 50%;">
                                    <div id="suggesstion-box"></div>
                                </div>
                            </td>
                        </tr>                      
                        <tr>
                            <td class="campo" >Folio</td>
                            <td class="derecha" colspan="3">
                                <div class="frmSearch">                                    
                                    <input type="text" name="folio" id="folio" class="cuadro_texto" maxlength="10" style="width: 20%;">
                                    <div id="suggesstion-folio"></div>
                                </div>
                            </td>
                        </tr>                      
                        <tr>
                            <td class="campo" >Fecha de Ingreso:</td>
                            <td class="derecha">
                                <input type="text" name="fecha_ingreso_inicio" id="fecha_ingreso_inicio" class="cuadro_texto" maxlength="10" style="width: 50%;">
                            </td>
                            <td class="campo" style="width: 25%">A la fecha:</td>
                            <td class="derecha">                                
                                <input type="text" name="fecha_ingreso_fin" id="fecha_ingreso_fin" class="cuadro_texto" maxlength="10" style="width: 50%;">
                            </td>
                        </tr>                      
                        <tr>
                            <td class="campo" >Determinación:</td>
                            <td class="derecha" colspan="3">
                                <select id="tipo_determinacion" name="tipo_determinacion">
                                    <option value="0">--Selecciona Tipo Determinación--</option>                                        
                                    <option value="Procedente">Procedente</option>                                        
                                    <option value="Reservada">Reservada</option>                                                                           
                                    <option value="Confidencial">Confidencial</option>                                                                           
                                    <option value="Incompetencia">Incompetencia</option>                                                                           
                                    <option value="Inexistencia">Inexistencia</option>                                                                           
                                    <option value="Desistida">Desistida</option>                                                                           
                                    <option value="Desistida">Desistida</option>                                                                           
                                    <option value="Desechada">Desechada</option>                                                                           
                                </select>
                            </td>
                        </tr>                      
                        <tr>
                            <td class="campo" >Descripción Solicitud:</td>
                            <td class="derecha" colspan="3">
                                <textarea name="descripcion_solicitud" id="descripcion_solicitud" class="cuadro_texto"  style="width: 50%;"></textarea>
                            </td>
                        </tr>                      
                    </tbody>
                </table>
                <br>
                <div align="center"> 
                    <table class="tablaDatos" border="1" width="50%" style="text-align: center;"> 
                        <tr>
                            <td colspan="2">Reporte Simple</td>
                            <td style="width: 30%"></td>
                            <td colspan="2">Reporte Anual</td>
                        </tr>
                        <tr>
                            <td>
                                <img border="0" width="40px" onclick="javascript:Exportar(1)" src="<%=request.getContextPath()%>/medios/iconos/downloadEXCEL.png" title="Generar en Formato Excel"  alt=""/>
                            </td>
                            <td>
                                <img border="0" width="40px" onclick="javascript:Exportar(2)" src="<%=request.getContextPath()%>/medios/iconos/downloadPDF.png" title="Generar en Formato PDF"  alt=""/>
                            </td>
                            <td>&nbsp;</td>
                            <td>
                                <img border="0" width="40px" onclick="javascript:Exportar(3)" src="<%=request.getContextPath()%>/medios/iconos/downloadEXCEL.png" title="Generar en Formato Excel"  alt=""/>
                            </td>
                            <td>
                                <img border="0" width="40px" onclick="javascript:Exportar(4)" src="<%=request.getContextPath()%>/medios/iconos/downloadPDF.png" title="Generar en Formato PDF"  alt=""/>
                            </td>
                        </tr>
                    </table>
                    
                    
                    &emsp;&emsp;&emsp;
                    
                </div>
            </form>

            <%            } catch (Exception e) {
                emergent_error = "Error al actualizar la base de datos ";
                emergent_error += e + " Query actual: " + ls_query;
            %>
            <div align="center" class="error"><%=emergent_error%></div>
            <%
                } finally {
                    lst_stm.close();
                    lcnt_con.close();
                }
            } else {
            %>
            <div class="error"><%=emergent_error%></div>
            <%
                }
            %>
        </body>
    </html>
