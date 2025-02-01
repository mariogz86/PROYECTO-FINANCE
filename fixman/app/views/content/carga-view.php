<style>
.has-text-centered {
    text-align: center !important;

}

p {
    margin-top: 5px !important;
    margin-bottom: 1rem !important;
}
</style>
<?php
	 use app\controllers\FuncionesController; 
	 $insrol = new FuncionesController();  
?>
<div class="container">
    <?php

ini_set('max_execution_time', '0'); 
ini_set('post_max_size', '10M'); 
set_time_limit(0);
//ini_set('memory_limit', '1024'); 

?>
    <h1 class="title">Cargar archivo fuente</h1>

    <h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; <label id="titulo"></label></h2>

    <p class="has-text-centered">
    <form name="formcarga" class="FormularioAjax " action="<?php echo APP_URL; ?>ajax/cargaAjax.php" method="POST"
        autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" name="modulo_Opcion" value="registrar">
        <div class="col-sm-12 col-md-12">
            <div class="columns ">
                <div class="column">
                    <div class="control ">
                        <label>Formulario <?php echo CAMPO_OBLIGATORIO; ?></label><br>

                        <select name="cmb_formulario" class="form-select" id="select_form" required>
                            <?php
                        
                        $consulta_datos="select * from \"SYSTEM\".obtener_formulario;"; 

                        $datos = $insrol->Ejecutar($consulta_datos); 
                        echo '<option value=""   >Seleccione un valor </option>';
                            while($campos_caja=$datos->fetch()){
                                echo '<option value="'.$campos_caja['id_formulario'].'"   > '.$campos_caja['anio'].'->'.$campos_caja['nombre'].'</option>';
                            }
                        ?>
                        </select>
                    </div>
                </div>

                <div class="column">
                    <div class="control ">
                        <label>Archivo fuente<?php echo CAMPO_OBLIGATORIO; ?></label><br>

                        <select name="cmb_archivofuente" class="form-select" id="select_archivo" required>
                            <option value="">Seleccione un valor </option>
                            <option value="0">Todos los archivos </option>
                        </select>

                    </div>
                </div>
                <div class="column">
                    <div class="control ">
                        <label> &nbsp;</label><br>
                        <button name="btnmostrar" type="submit" class="button is-info is-rounded"><i
                                class="fas fa-search"></i>
                            &nbsp;
                            Cargar</button>
                    </div>
                </div>


            </div>


        </div>
    </form>


    <div name="gridcat">


        <table id="myTable" class="table table-striped table-bordered">

        </table>

        <table id="myTablevarnuevas" class="table table-striped table-bordered">

        </table>

        <p class="has-text-centered">

            <button name="btnguardar" type="submit" class="button is-info is-rounded"><i class="fas fa-search"></i>
                &nbsp;
                Guardar</button>
            <button name="btnguardarnuevas" type="submit" class="button is-info is-rounded"><i class="far fa-save"></i>
                &nbsp;
                Guardar boletas nuevas</button>
        </p>


    </div>

    <p class="has-text-centered pt-6">
        <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
    </p>


</div>
<script>
const btnguardar = document.getElementsByName("btnguardar");
const btnguardarnuevas = document.getElementsByName("btnguardarnuevas");
const gridcat = document.getElementsByName("gridcat");

$("#titulo")[0].innerText = "Cargar los datos de los archivos al formulario.";
gridcat[0].style.display = "none";

function cargardatos(alerta) {
    var array = JSON.stringify(alerta);
    array = JSON.parse(array);

    if (array.Erroarchivos == "1") {
        cargargrid(array.datos)

    } else {
        cargargridboletas(array.datos)
    }
}




$(document).ready(function() {
    limpiarcache();
    $('.form-select').select2();
    //para dejar sin seleccion el combo
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();

});

btnguardarnuevas[0].addEventListener("click", (event) => {
    event.preventDefault();


    if ($('#myTablevarnuevas_wrapper')[0] != undefined) {
        var tabla = new DataTable('#myTablevarnuevas');




        Swal.fire({
            title: '¿Estás seguro?',
            text: "Quieres realizar la acción solicitada",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, realizar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                var solicitud = "";

                var dato = JSON.stringify(tabla.data().filter((datos) => datos.existe == "NO")
                    .toArray()).replaceAll("&", "%26");
                $(".loadersacn")[0].style.display = "";
                $.ajax({
                    type: "POST",
                    url: "<?php  echo APP_URL.'ajax/cargaAjax.php' ?>",
                    data: "Cargaguardar=guardar&datos=" + dato + "&nuevas=1",
                    success: function(response) {


                        $(".loadersacn").fadeOut("slow");
                        var res = jQuery.parseJSON(response);

                        if (res.status == 200) {
                            btnguardar[0].style.display = "";


                            Swal.fire({
                                icon: 'success',
                                title: 'Carga de Datos',
                                text: 'Los datos se guardaron con éxito',
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
                                text: "No se pudieron guardar los registros.",
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        $(".loadersacn").fadeOut("slow");
                        Swal.fire({
                            icon: 'success',
                            title: 'Carga de Datos',
                            text: 'Los datos se guardaron con éxito',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                        // 
                        // console.log(thrownError);
                    }
                });
            }
        });

    }

});

btnguardar[0].addEventListener("click", (event) => {
    event.preventDefault();


    if ($('#myTablevarnuevas_wrapper')[0] != undefined) {
        var tabla = new DataTable('#myTablevarnuevas');


        if (tabla.rows({
                selected: true
            }).data().toArray().length == 0) {

            Swal.fire({
                icon: "warning",
                title: "Advertencia",
                text: "Seleccione un registro de la tabla",
                confirmButtonText: 'Aceptar'
            });
        } else {

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Quieres realizar la acción solicitada",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, realizar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    var solicitud = "";

                    var dato = JSON.stringify(tabla.rows({
                        selected: true
                    }).data().toArray()).replaceAll("&", "%26");
                    $(".loadersacn")[0].style.display = "";
                    $.ajax({
                        type: "POST",
                        url: "<?php  echo APP_URL.'ajax/cargaAjax.php' ?>",
                        data: "Cargaguardar=guardar&datos=" + dato + "&nuevas=0",
                        success: function(response) {


                            $(".loadersacn").fadeOut("slow");
                            var res = jQuery.parseJSON(response);

                            if (res.status == 200) {
                                btnguardar[0].style.display = "";


                                Swal.fire({
                                    icon: 'success',
                                    title: 'Carga de Datos',
                                    text: 'Los datos se guardaron con éxito',
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
                                    text: "No se pudieron guardar los registros.",
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            $(".loadersacn").fadeOut("slow");
                            Swal.fire({
                                icon: 'success',
                                title: 'Carga de Datos',
                                text: 'Los datos se guardaron con éxito',
                                confirmButtonText: 'Aceptar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            });
                            // $(".loadersacn").fadeOut("slow");
                            // console.log(thrownError);
                        }
                    });
                }
            });
        }
    }

});

$('#select_form').on('change', function() {
    var selectVal = $("#select_form option:selected").val();
    if (selectVal != "" && selectVal != undefined) {


        $(".loadersacn")[0].style.display = "";
        $.ajax({
            type: "GET",
            url: "<?php  echo APP_URL.'ajax/variableAjax.php' ?>",
            data: "cargarhojas=" + selectVal,
            success: function(response) {

                var res = jQuery.parseJSON(response);
                var $select2 = $('#select_archivo');
                if ((res.status == 200) && (res.data2 != undefined)) {


                    $("#select_archivo option").remove();
                    $select2.append('<option value="">Seleccione un valor</option>');
                    $select2.append('<option value="0">Todos los archivos </option>');




                    var longitud2 = res.data2.length;
                    for (i = 0; i < longitud2; i++) {
                        $select2.append('<option value=' + res
                            .data2[i].id_archivofuente + '>' +
                            res.data2[i].nombrearchivo + '</option>');
                    }




                    $(".loadersacn").fadeOut("slow");



                } else {

                    $("#select_archivo option").remove();
                    $select2.append('<option value="">Seleccione un valor</option>');
                    $select2.append('<option value="0">Todos los archivos </option>');
                    $(".loadersacn").fadeOut("slow");
                }


            }
        });
    }
});

function cargargrid(datos) {


    if ($('#myTablevarnuevas_wrapper')[0] != undefined) {
        $('#myTablevarnuevas_wrapper')[0].style.display = "none";

    }

    if ($('#myTable_wrapper')[0] != undefined) {
        $('#myTable_wrapper')[0].style.display = "";
    }

    gridcat[0].style.display = "";
    $('#myTable').DataTable({
        data: datos,
        language: {
            "url": "<?php  echo APP_URL?>config/es-MX.json"
        },
        destroy: true,
        responsive: true,
        columns: [{
                width: "20%",
                title: 'Archivo',
                data: 'nombrearchivo',


            },
            {
                width: "20%",
                title: 'Ruta',
                data: 'ruta',


            },

        ],
        order: [
            [1, 'asc']
        ],
        //paging: false,
        scrollCollapse: true,
        scrollX: false,
        scrollY: 400,
        // select: {
        //     style: 'multi',
        //     selector: 'td:first-child'
        // }
    });

}

function cargargridboletas(datos) {

    if ($('#myTable_wrapper')[0] != undefined) {
        $('#myTable_wrapper')[0].style.display = "none";

    }

    if ($('#myTablevarnuevas_wrapper')[0] != undefined) {
        $('#myTablevarnuevas_wrapper')[0].style.display = "";
    }
    gridcat[0].style.display = "";
    $('#myTablevarnuevas').DataTable({
        data: datos,
        language: {
            "url": "<?php  echo APP_URL?>config/es-MX.json"
        },
        destroy: true,
        responsive: true,
        columnDefs: [{
            orderable: false,
            render: DataTable.render.select(),
            targets: 0
        }, ],
        columns: [{
                className: "text-center",
                data: null,
                width: "5%",
            },
            {
                width: "10%",
                title: 'Boleta',
                data: 'BOLETA',
            },
            {
                width: "15%",
                title: 'Archivo',
                data: 'archivo',


            },
            {
                width: "5%",
                title: 'Existe',
                data: 'existe',
            },
            // {
            //     width: "20%",
            //     title: 'Caso',
            //     data: 'CASO',


            // },

            {
                width: "10%",
                title: 'CIIU',
                data: 'CIIU',
            },

        ],
        order: [
            [1, 'asc']
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

}
</script>