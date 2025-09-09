<style>
.has-text-centered {
    text-align: center !important;

}

p {
    margin-top: 5px !important;
    margin-bottom: 1rem !important;
}

.modal-body {
    position: relative;
    flex: 1 1 auto;
    /* padding: 1rem; */
}

.modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 120%;
    pointer-events: auto;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 0.3rem;
    outline: 0;
}
</style>
<?php
	 use app\controllers\FuncionesController; 
	 $insrol = new FuncionesController();  
?>
<div class="container">
    <?php
//echo ini_get('default_socket_timeout');
ini_set('max_execution_time', '0'); 
ini_set('default_socket_timeout', 6000);
// echo ini_get('default_socket_timeout');
set_time_limit(0);
//ini_set('memory_limit', '1024'); 

?>
    <h1 class="title">Validaciones</h1>

    <h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; <label id="titulo"></label></h2>

    <p class="has-text-centered">

    <form name="formcarga" class="FormularioAjax " action="<?php echo APP_URL; ?>ajax/aplicarvalidacionAjax.php"
        method="POST" autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" name="modulo_Opcion" value="implementarvalidacion">
        <div class="col-sm-12 col-md-12">
            <div class="columns ">
                <div  class="col-sm-6 col-md-4">
                    <div class="control ">
                        <label>Formulario <?php echo CAMPO_OBLIGATORIO; ?></label><br>

                        <select name="cmb_formulario" class="form-select" id="select_form" required>
                            <?php
                        
                        $consulta_datos="select * from \"SACNSYS\".obtener_formulario;"; 

                        $datos = $insrol->Ejecutar($consulta_datos); 
                        echo '<option value=""   >Seleccione un valor </option>';
                            while($campos_caja=$datos->fetch()){
                                echo '<option value="'.$campos_caja['id_formulario'].'"   > '.$campos_caja['anio'].'->'.$campos_caja['nombre'].'</option>';
                            }
                        ?>
                        </select>
                    </div>
                </div>

                <div  class="col-sm-12 col-md-2">
                    <div class="control ">
                        <label>Boletas<?php echo CAMPO_OBLIGATORIO; ?></label><br>

                        <select name="cmb_boleta" class="form-select" id="select_archivo" required>
                            <option value="">Seleccione un valor </option>
                        </select>

                    </div>
                </div>
                <div  class="col-sm-12 col-md-6">
                    <div class="control ">
                        <label> &nbsp;</label><br>
                        <button name="btnmostrar" type="submit" class="button is-info is-rounded"><i
                                class="fas fa-save"></i>
                            &nbsp;
                            Boleta</button>
                        <button name="btnguardartodas" type="submit" class="button is-info is-rounded"><i
                                class="far fa-save"></i>&nbsp;
                           Todas las boletas </button>
                        <button name="btnreporte" type="submit" class="button is-info is-rounded"><i
                                class="fas fa-file-export"></i>&nbsp;
                            Reporte </button>
                    </div>

                </div>



            </div>


        </div>
    </form>





    <div name="gridcat"> 

        <table id="myTable" class="table table-striped table-bordered">

        </table>






    </div>

    <p class="has-text-centered pt-6">
        <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
    </p>


</div>
<!-- MODAL GESTION DE EMPLEADOS-->
<div class="modal fade" id="modalvalidaciones">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="titulomodal" class="modal-title modal-title-h4"></h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color: #f3f3f3;">×</span>
                </button>
            </div>
            <div class="modal-body">

                <table id="griddatosvalidaciones" class="table table-striped table-bordered">

            </div>
        </div>
    </div>
</div>
<script>
existenboletas = false;
const gridcat = document.getElementsByName("gridcat");
const btnguardar = document.getElementsByName("btnguardartodas");
const btnreporte = document.getElementsByName("btnreporte");

$("#titulo")[0].innerText = "Implementar validaciones para el formulario";
gridcat[0].style.display = "none";

function cargardatos(alerta) {
    var array = JSON.stringify(alerta);
    array = JSON.parse(array);

    if (array.datos != "[]") {
        cargargrid(array.datos);
        cargarcomboboletas();

    }
}

$(document).on('click', '#modificar', function(e) {

    event.preventDefault();
    var row = e.currentTarget.attributes['valor'].value;
    var dato = $("#myTable").DataTable().data()[row];
    var idvalidacion = dato.id_validacion;
    var boleta = dato.boleta;
    $("#titulomodal")[0].innerHTML = ' Boleta: ' + dato.boleta + '<br> Validación: ' + dato.validacion +
        '<br> Fórmula de ' + dato.tipovalidacion + ': ' + dato.descripcion+
        '<br> variables: ' +dato.formula; 


    //para ocultar
    //$("#modalvalidaciones").modal("hide");

    $.ajax({
        type: "GET",
        url: "<?php  echo APP_URL.'ajax/aplicarvalidacionAjax.php' ?>",
        data: "obtenerdatosvalidacion=datos&idvalidacion=" + idvalidacion +
            "&boleta=" + boleta,
        success: function(response) {

            var res = jQuery.parseJSON(response);
            if (res.status == 200) {

                cargargridvalidaciones(res.data);


                // para mostrar modal
                $("#modalvalidaciones").modal({
                    backdrop: "static",
                    keyboard: false
                });

            }
            //  else {
            //      cargargrid([]);
            //  }

        }
    })


});


btnreporte[0].addEventListener("click", (event) => {
    var selectVal = $("#select_form option:selected").val();
    var dato="";

    if (selectVal != "" && selectVal != undefined) {

        event.preventDefault();


        var tabla = new DataTable('#myTable');

        if (tabla.rows({
                selected: true
            }).data().toArray().length == 0) {
                  dato = JSON.stringify(tabla.rows().data().toArray()).replaceAll("&", "%26");
            }else{
                  dato = JSON.stringify(tabla.rows({
                        selected: true
                    }).data().toArray()).replaceAll("&", "%26");
            }

        let encodedData = encodeURIComponent(dato);
            $(".loadersacn")[0].style.display = "";
        $.ajax({
            type: "POST",
            url: "<?php  echo APP_URL.'ajax/aplicarvalidacionAjax.php' ?>",
            //data: "reporte=listarval&cmb_formulario=" + $("#select_form option:selected").val(),
            data: "reporte=listarval&datos=" + encodedData+"&cmb_formulario="+$("#select_form option:selected").val(),
            xhrFields: {
                responseType: 'blob' // Importante para manejar archivos binarios
            },
            success: function(data) {
                $(".loadersacn").fadeOut("slow");
                // Crear una URL para el blob y abrirla en una nueva pestaña
                var blob = new Blob([data], {
                    type: 'application/pdf'
                });
                var url = window.URL.createObjectURL(blob);
                window.open(url);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
});


btnguardar[0].addEventListener("click", (event) => {
    var selectVal = $("#select_form option:selected").val();
    var ultimaboleta = "";

    if (existenboletas == false) {
        Swal.fire({
            icon: "warning",
            title: "Validaciones",
            text: "No existen boletas para validar",
            confirmButtonText: 'Aceptar'
        });
    } else {



        if (selectVal != "" && selectVal != undefined) {
            event.preventDefault();

            $(".loadersacn")[0].style.display = "";

            $.ajax({
                type: "GET",
                url: "<?php  echo APP_URL.'ajax/aplicarvalidacionAjax.php' ?>",
                data: "listarvalaciones=listarval&cmb_formulario=" + $("#select_form option:selected")
                    .val(),
                success: function(response) {

                    var res = jQuery.parseJSON(response);
                    if (res.status == 200) {
                        //variables a ejecutar 
                        var datosnocumplenvalidacion = [];
                        param1 = 0;
                        param2 = 0;
                        param3 = 0;
                        nombrevalidacion = res.data[0].nombrevalidacion;
                        validacion = res.data[0].tipovalidacion;
                        idvalidacion = res.data[0].idvalidacion;
                        boleta = res.data[0].boleta;
                        nombreval = res.data[0].nombrevalidacion;
                        ciu = res.data[0].ciu;
                        rs = res.data[0].razonsocial;
                        idformulario = $("#select_form option:selected").val();
                        for (let j = 0; j <= res.data.length - 1; j++) {
                            datovalidacion = res.data[j];
                            

                            if (nombrevalidacion=="HTCSI_SNFGr2019_valid1215"){
                                var datoprueba="";
                            }

                            if (nombrevalidacion == datovalidacion.nombrevalidacion) {

                                switch (datovalidacion.parametro) {
                                    case "1":
                                        param1 = roundUsingToFixed(datovalidacion.resultado);
                                        break;
                                    case "2":
                                        param2 = roundUsingToFixed(datovalidacion.resultado);
                                        break;
                                    case "3":
                                        param3 = roundUsingToFixed(datovalidacion.resultado);
                                        break;

                                }
                            } else {

                                if (retornarobjeto(validacion, param1, param2, param3, idvalidacion,
                                        idformulario, nombrevalidacion, validacion, boleta, ciu, rs)
                                    .cumple ==
                                    "NO") {
                                    datosnocumplenvalidacion.push(retornarobjeto(validacion, param1,
                                        param2,
                                        param3, idvalidacion, idformulario,
                                        nombrevalidacion,
                                        validacion, boleta, ciu, rs));
                                }





                                nombrevalidacion = datovalidacion.nombrevalidacion;
                                validacion = datovalidacion.tipovalidacion;
                                idvalidacion = datovalidacion.idvalidacion;
                                boleta = datovalidacion.boleta;
                                param1 = 0;
                                param2 = 0;
                                param3 = 0;
                                ciu = datovalidacion.ciu;
                                rs = datovalidacion.razonsocial;

                                switch (datovalidacion.parametro) {
                                    case "1":
                                        param1 = roundUsingToFixed(datovalidacion.resultado);
                                        break;
                                    case "2":
                                        param2 = roundUsingToFixed(datovalidacion.resultado);
                                        break;
                                    case "3":
                                        param3 = roundUsingToFixed(datovalidacion.resultado);
                                        break;

                                }
                            }


                        } //fin del ciclo for
                        if (retornarobjeto(validacion, param1, param2, param3, idvalidacion,
                                idformulario, nombrevalidacion, validacion, boleta, ciu, rs)
                            .cumple ==
                            "NO") {
                            datosnocumplenvalidacion.push(retornarobjeto(validacion, param1, param2,
                                param3, idvalidacion, idformulario, nombrevalidacion,
                                validacion, boleta, ciu, rs));
                        }

                        var dato = JSON.stringify(datosnocumplenvalidacion).replaceAll("&", "%26");


                        $.ajax({
                            type: "POST",
                            url: "<?php  echo APP_URL.'ajax/aplicarvalidacionAjax.php' ?>",
                            data: "Cargaguardar=guardar&datos=" + dato,
                            success: function(response) {


                                $(".loadersacn").fadeOut("slow");
                                var res = jQuery.parseJSON(response);

                                if (res.status == 200) {
                                    btnguardar[0].style.display = "";


                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Implementar validacion',
                                        text: 'Existen validaciones que no cumplieron la condición parametrizada.',
                                        confirmButtonText: 'Aceptar'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.reload();
                                        }
                                    });

                                } else {
                                    Swal.fire({
                                        icon: "Error",
                                        title: "Error",
                                        text: "Error al implementar las validaciones",
                                        confirmButtonText: 'Aceptar'
                                    });
                                }
                            }
                        });



                    } //fin del estatus 200
                    else {
                        $(".loadersacn").fadeOut("slow");
                        Swal.fire({
                            icon: "warning",
                            title: "Validaciones",
                            text: "No hay inconsistencias para el formulario seleccionado",
                            confirmButtonText: 'Aceptar'
                        });
                    }
                }
            });







        }
    }


});

function retornarobjeto(validacion, param1, param2, param3, idvalidacion, idformulario,
    nombrevalidacion, validacion, boleta, ciu, rs) {
    var objeto = {
        cumple: "SI",
    }
    switch (validacion) {
        case "Validación_1":
            total = param1 - param2;
            if (total != 0) {
                var objeto = {
                    id_validacion: idvalidacion,
                    id_formulario: idformulario,
                    validacion: nombrevalidacion,
                    cumple: "NO",
                    tipovalidacion: validacion,
                    parametro1: param1,
                    parametro2: param2,
                    parametro3: "0",
                    discrepancia: total,
                    boleta: boleta,
                    ciu: ciu,
                    rs: rs
                }
                //Lo agregas al array.

                //datosnocumplenvalidacion.push(objeto);
            }
            break;
        case "Validación_2":
            resta = param2 - param3;
            total = param1 - resta.toFixed(2);
            if (total != 0) {
                var objeto = {
                    id_validacion: idvalidacion,
                    id_formulario: idformulario,
                    validacion: nombrevalidacion,
                    cumple: "NO",
                    tipovalidacion: validacion,
                    parametro1: param1,
                    parametro2: param2,
                    parametro3: param3,
                    discrepancia: total,
                    boleta: boleta,
                    ciu: ciu,
                    rs: rs

                }
                //Lo agregas al array.

                // datosnocumplenvalidacion.push(objeto);
            }
            break;
        case "Validación_3":
            if ((parseFloat(param1) > 0) && (parseFloat(param2) > 0)) {
                cumpe = "SI";
            } else {
                var objeto = {
                    id_validacion: idvalidacion,
                    id_formulario: idformulario,
                    validacion: nombrevalidacion,
                    cumple: "NO",
                    tipovalidacion: validacion,
                    parametro1: param1,
                    parametro2: param2,
                    parametro3: "0",
                    discrepancia: "",
                    boleta: boleta,
                    ciu: ciu,
                    rs: rs
                }
                //Lo agregas al array.

                // datosnocumplenvalidacion.push(objeto);
            }
            break;

    }

    return objeto;
}

function roundUsingToFixed(value, digits = 2) {
    return parseFloat(value).toFixed(digits);
}



$(document).ready(function() {
    limpiarcache();
    $('.form-select').select2();
    //para dejar sin seleccion el combo
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();

});


$('#select_form').on('change', function() {
    cargaformulario();
});

function cargaformulario() {
    cargarcomboboletas();
    var selectVal = $("#select_form option:selected").val();
    if (selectVal != "" && selectVal != undefined) {
        $.ajax({
            type: "GET",
            url: "<?php  echo APP_URL.'ajax/aplicarvalidacionAjax.php' ?>",
            data: "cargarvalidaciones=" + $("#select_form option:selected").val(),
            success: function(response) {
                $(".loadersacn").fadeOut("slow");
                var res = jQuery.parseJSON(response);
                if (res.status == 200) {
                    cargargrid(res.data);

                } else {
                    cargargrid([]);
                }

            }
        });
    }
}

function cargarcomboboletas() {
    var selectVal = $("#select_form option:selected").val();
    if (selectVal != "" && selectVal != undefined) {


        $(".loadersacn")[0].style.display = "";
        $.ajax({
            type: "GET",
            url: "<?php  echo APP_URL.'ajax/aplicarvalidacionAjax.php' ?>",
            data: "cargarboletas=" + selectVal,
            success: function(response) {

                var res = jQuery.parseJSON(response);
                var $select2 = $('#select_archivo');
                if (res.status == 200) {
                    existenboletas = true;

                    $("#select_archivo option").remove();
                    $select2.append('<option value="">Seleccione un valor</option>');
                    //$select2.append('<option value="0">Todas las boletas</option>');





                    var longitud2 = res.data.length;
                    for (i = 0; i < longitud2; i++) {
                        $select2.append('<option value=' + res
                            .data[i].boleta + '>' +
                            res.data[i].boleta + '</option>');
                    }




                    $(".loadersacn").fadeOut("slow");



                } else {
                    existenboletas = false;
                    $("#select_archivo option").remove();
                    $select2.append('<option value="">Seleccione un valor</option>');
                    $(".loadersacn").fadeOut("slow");
                }


            }
        });
    }
}
function repararCaracteres(cadena) {
  try {
    return decodeURIComponent(escape(cadena));
  } catch (e) {
    return cadena;
  }
}

function repararJson(obj) {
  if (typeof obj === 'string') {
    return repararCaracteres(obj);
  } else if (Array.isArray(obj)) {
    return obj.map(repararJson);
  } else if (typeof obj === 'object' && obj !== null) {
    const nuevo = {};
    for (const key in obj) {
      nuevo[key] = repararJson(obj[key]);
    }
    return nuevo;
  }
  return obj;
}

function cargargrid(datos) {

    const jsonCorregido = repararJson(datos);

    let exportFormatter = {
        format: {
            body: function(data, row, column, node) {
                // Strip $ from salary column to make it numeric
                return column === 5 ? data.replace(/[$,]/g, '') : data;
            }
        }
    };

    if ($('#myTable_wrapper')[0] != undefined) {
        $('#myTable_wrapper')[0].style.display = "";
    }

    gridcat[0].style.display = "";
    $('#myTable').DataTable({
        layout: {
            topStart: {
                buttons: [{
                        extend: 'excel',
                        title: 'Validaciones para el formulario que no cumplieron la condición.',
                        exportOptions: {
                            columns: function(column, data, node) {
                                if (column > 11) {
                                    return false;
                                }
                                if (column <= 1) {
                                    return false;
                                }
                                return true;
                            },
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Validaciones para el formulario que no cumplieron la condición.',
                        exportOptions: {
                            columns: function(column, data, node) {
                                if (column > 11) {
                                    return false;
                                }
                                if (column <= 2) {
                                    return false;
                                }
                                return true;
                            },
                        },
                         customize: function(doc) {
                            doc.defaultStyle.fontSize = 10; // tamaño de letra
                            doc.pageOrientation = 'landscape'; // horizontal para más columnas
                            doc.pageSize = 'A4'; // o 'LEGAL', 'A3', etc.
                        }       
                    }
                ]
            },
            topEnd: null,
            top: ['pageLength', 'search']
        },
        data: jsonCorregido,
        language: {
            "url": "<?php  echo APP_URL?>config/es-MX.json",
            "decimal": ".", //separador decimales
            "thousands": "," //Separador miles
        },
        destroy: true,
        responsive: true,
        columnDefs: [{
            orderable: false,
            render: DataTable.render.select(),
            targets: 0
        }, ],
        columns: [
            {
                className: "text-center",
                data: null,
                width: "5%",
            },
            {
                width: "8%",
                title: 'id',
                data: 'id_validacionaplicada',
                visible: false,
            }, 
            {
                width: "8%",
                title: 'formula',
                data: 'formula',
                visible: false,
            }, 
            {
                width: "30%",
                title: 'Razon social',
                data: 'razonsocial',
            }, {
                width: "8%",
                title: 'CIIU',
                data: 'ciu',
            },
            {
                width: "8%",
                title: 'Boleta',
                data: 'boleta',
            },
            {
                width: "10%",
                title: 'Validación',
                data: 'validacion',
            },
            {
                width: "10%",
                title: 'Tipo validacion',
                data: 'tipovalidacion',
                // "render": function(data, type, full, meta) {
                //     return '<span data-toggle="tooltip" title="' + full.descripcion + '">' + data +
                //         '</span>';
                // }

            },
            {
                width: "10%",
                title: 'Parametro 1',
                data: 'parametro1',
                render: $.fn.dataTable.render.number(',', '.', 2)
            },
            {
                width: "10%",
                title: 'Parametro 2',
                data: 'parametro2',
                render: $.fn.dataTable.render.number(',', '.', 2)
            },
            {
                width: "10%",
                title: 'Parametro 3',
                data: 'parametro3',
                render: $.fn.dataTable.render.number(',', '.', 2)
            },
            {
                width: "10%",
                title: 'Discrepancia',
                data: 'discrepancia',
                render: $.fn.dataTable.render.number(',', '.', 2)
            },
            {
                width: "10%",
                className: "text-center",
                title: 'Datos validación',
                data: 'id_validacionaplicada',
                render: function(data, type, row, meta) {
                    return '<td><a id="modificar" title="Ver" href="#" class="button is-info is-rounded is-small" valor="' +
                        meta.row + '">' +
                        '<i class="fas fa-sync fa-fw"></i></a> </td>';
                }

            },
        ],
        order: [
            [0, 'desc']
        ],
        //paging: false,
        scrollCollapse: true,
        scrollX: false,
        scrollY: 400,
        select: {
            style: 'multi',
            selector: 'td:first-child'
        } 
    });

    $('#myTable').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
    });

}

function cargargridvalidaciones(datos) {




    $('#griddatosvalidaciones').DataTable({
        data: datos,
        language: {
            "url": "<?php  echo APP_URL?>config/es-MX.json"
        },
        destroy: true,
        responsive: true,
        columns: [{
                title: 'Archivo',
                className: "text-center",
                data: 'archivo',

            },
            {
                title: 'Boleta',
                className: "text-center",
                data: 'boleta',

            },
            {
                width: "20%",
                title: 'Variable',
                data: 'variable',


            },
            {
                width: "20%",
                title: 'Valor',
                data: 'valor',
                render: $.fn.dataTable.render.number(',', '.', 2)

            },
            {
                width: "20%",
                title: 'Parametro',
                data: 'parametro',


            },

        ],
        order: [
            [1, 'asc']
        ],
        //paging: false,
        
    });

}
</script>