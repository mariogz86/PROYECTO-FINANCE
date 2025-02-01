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
    <h1 class="title">Archivo fuente</h1>

    <h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; <label id="titulo"></label></h2>

    <div name="gridcat">


        <p class="has-text-left">
            <a name="agregarcat" href="#" class="button is-link is-rounded btn-back"><i class="fas fa-plus"></i> &nbsp;
                Agregar registro</a>
        </p>
        <table id="myTable" class="table table-striped table-bordered">

        </table>
        <br>
        <form name="cambioruta" class="FormularioAjax " action="<?php echo APP_URL; ?>ajax/archivoAjax.php"
            method="POST" autocomplete="off" enctype="multipart/form-data">
            <input type="hidden" name="hdf_cambioruta" value="cambioruta">
            <input type="hidden" name="hdf_seleccionados" value="">
            <div class="col-sm-12 col-md-10">
                <div class="columns ">

                    <div class="control " style="    width: 100%;">
                        <input class="input" type="text" name="nuevaruta" required>

                    </div>
                    <div class="control ">
                        <button name="btncambiarruta" type="submit" class="button is-info is-rounded"><i
                                class="far fa-save"></i> &nbsp;
                            Cambiar ruta</button>
                    </div>

                </div>



                <p class="has-text-centered">

                </p>
            </div>

        </form>

    </div>

    <form name="formformulario" class="FormularioAjax " action="<?php echo APP_URL; ?>ajax/archivoAjax.php"
        method="POST" autocomplete="off" enctype="multipart/form-data" style="display:none">
        <input type="hidden" name="idarchivofuente" value="">
        <p class="has-text-right">
            <button name="regresar" type="reset" class="button is-link is-light is-rounded"><i
                    class="fas fa-arrow-alt-circle-left"></i> &nbsp; Regresar</button>
        </p>
        <input type="hidden" name="modulo_Opcion" value="registrar">
        <input type="hidden" name="hdf_seleccionados" value="">
        <div class="col-sm-12 col-md-10">
            <div class="columns ">
                <div class="col-sm-12 col-md-7">
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


            </div>
            <div class="columns">
                <div class="col-sm-12 col-md-10">
                    <div class="control">
                        <label>Ruta <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="text" name="ruta" required>

                    </div>

                </div>
                <div style="margin-top: 30px !important;">
                    <div class="control">

                        <button name="btncarga" type="submit" class="button is-info is-rounded"><i
                                class="fas fa-search"></i> &nbsp;
                            Cargar</button>
                    </div>
                </div>
            </div>

            <table id="myTablearchivos" class="table table-striped table-bordered"></table>

            <p class="has-text-centered">
                <button type="reset" class="button is-link is-light is-rounded"><i class="fas fa-paint-roller"></i>
                    &nbsp;
                    Limpiar</button>
                <button name="btnguardar" type="submit" class="button is-info is-rounded"><i class="far fa-save"></i>
                    &nbsp;
                    Guardar</button>
            </p>
            <p class="has-text-centered pt-6">
                <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
            </p>
        </div>
    </form>
</div>



<script>
const btnguardar = document.getElementsByName("btnguardar");
const button = document.getElementsByName("agregarcat");
const gridcat = document.getElementsByName("gridcat");
const formformulario = document.getElementsByName("formformulario");
const btncambiarruta = document.getElementsByName("btncambiarruta");
const regresar = document.getElementsByName("regresar");

const idarchivofuente = document.getElementsByName("idarchivofuente");
const seleccionados = document.getElementsByName("hdf_seleccionados");
const btncarga = document.getElementsByName("btncarga");

btncambiarruta[0].addEventListener("click", (event) => {

    var table = new DataTable('#myTable');


    if (table.rows({
            selected: true
        }).data().toArray().length == 0) {
        event.preventDefault();
        Swal.fire({
            icon: "warning",
            title: "Advertencia",
            text: "Seleccione un registro de la tabla",
            confirmButtonText: 'Aceptar'
        });
    } else {
        var dato = "";
        var array = table.rows({
            selected: true
        }).data().toArray();
        array.find(function(value, index) {
            if (dato == "") {
                dato = value.id_archivofuente;
            } else {
                dato = dato + "," + value.id_archivofuente
            }
            seleccionados[0].value = dato;
        });
    }

});

$(document).ready(function() {
    $('.form-select').select2();
    //para dejar sin seleccion el combo
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();
    $("#titulo")[0].innerText = "Lista de Archivos fuentes";
});

btnguardar[0].addEventListener("click", (event) => {
    event.preventDefault();
    var tabla = new DataTable('#myTablearchivos');


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
                var dato = JSON.stringify(tabla.rows({
                    selected: true
                }).data().toArray());
                $(".loadersacn")[0].style.display = "";
                $.ajax({
                    type: "POST",
                    url: "<?php  echo APP_URL.'ajax/archivoAjax.php' ?>",
                    data: "modulo_Opcion=registrar&archivos=" + dato + "&formulario=" + document
                        .getElementsByName("cmb_formulario")[0].value,
                    success: function(response) {


                        $(".loadersacn").fadeOut("slow");
                        var res = jQuery.parseJSON(response);

                        if (res.status == 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Archivos fuente',
                                text: 'El registro se guardo con exito',
                                confirmButtonText: 'Aceptar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.querySelector(".FormularioAjax")
                                    .reset();
                                    cargargrid();
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
                    }
                });
            }
        });
    }

});

regresar[0].addEventListener("click", (event) => {
    event.preventDefault();
    gridcat[0].style.display = "";
    formformulario[0].style.display = "none";
    document.getElementsByName("formformulario")[0].reset();
    $("#titulo")[0].innerText = "Lista de archivos fuente";
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();
    cargararchivos([]);

});


button[0].addEventListener("click", (event) => {
    event.preventDefault();
    $("#titulo")[0].innerText = "Nuevo archivo fuente";
    gridcat[0].style.display = "none";
    formformulario[0].style.display = "";
    idarchivofuente[0].value = 0;
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();
    cargararchivos([]);
});




btncarga[0].addEventListener("click", (event) => {


    var selectVal = $("#select_form option:selected").val();
    var ruta = document.getElementsByName("ruta")[0].value.trim();

    if (ruta != "") {
        event.preventDefault();
        $.ajax({
            type: "GET",
            url: "<?php  echo APP_URL.'ajax/archivoAjax.php' ?>",
            data: "buscararchivos=" + selectVal + "&ruta=" + ruta,
            processData: false,
            contentType: false,
            success: function(response) {
                var res = jQuery.parseJSON(response);

                $(".loadersacn").fadeOut("slow");

                if (res.status == 200) {



                    cargararchivos(res.archivos);

                } else {
                    Swal.fire({
                        icon: "warning",
                        title: "Advertencia",
                        text: "Ruta indicada no existe, por favor validar.",
                        confirmButtonText: 'Aceptar'
                    });

                }




            }
        });
    }
});

function cargararchivos(datos) {
    //incio tabla archivos
    $('#myTablearchivos').DataTable({
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
            },
            {
                title: 'Nombre archivo',
                data: 'Nombre',

            },
            {
                title: 'Ruta',
                data: 'Ruta',

            },

        ],
        order: [
            [1, 'desc']
        ],
        //paging: false,
        scrollCollapse: true,
        scrollX: false,
        scrollY: 300,
        select: {
            style: 'multi',
            selector: 'td:first-child'
        }
    });
}

function cargargrid() {
    $(".loadersacn")[0].style.display = "";
    $("#titulo")[0].innerText = "Lista de archivos fuente";

    gridcat[0].style.display = "";
    formformulario[0].style.display = "none";
    document.getElementsByName("formformulario")[0].reset();
    $.ajax({
        type: "GET",
        url: "<?php  echo APP_URL.'ajax/archivoAjax.php?cargagrid' ?>",
        success: function(response) {
            $(".loadersacn").fadeOut("slow");
            var res = jQuery.parseJSON(response);
            var estilo = "";


            $('#myTable').DataTable({
                // layout: {
                //             topStart: {
                //                 buttons: [ {
                //                     extend: 'excel',
                //                     text: 'Descargar archivo excel'
                //                 }
                //             ]
                //             }
                //         },
                data: res.data,
                language: {
                    "url": "<?php  echo APP_URL?>config/es-MX.json"
                },
                //searching: false,
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
                    },
                    {
                        title: 'Id Formulario',
                        className: "text-center",
                        data: 'id_archivofuente',
                        visible: false,
                    },
                    {
                        title: 'Id Formulario',
                        className: "text-center",
                        data: 'id_formulario',
                        visible: false,
                    },
                    {
                        width: "20%",
                        title: 'Año',
                        data: 'anio',

                    },
                    {
                        width: "20%",
                        title: 'Formulario',
                        data: 'nombreform',


                    },
                    {
                        width: "20%",
                        title: 'Archivo',
                        data: 'nombrearchivo',


                    },
                    {
                        width: "20%",
                        title: 'Ruta',
                        data: 'ruta',


                    },
                    {
                        width: "30%",
                        title: 'Estado',
                        data: 'u_estado',
                        render: function(data, type, row, meta) {
                            if (row.u_estado == 1) {
                                return "Activo";
                            } else {
                                return "Inactivo";
                            }

                        }

                    },
                    {
                        width: "30%",
                        title: 'Usuario creación',
                        data: 'usuario',


                    },
                    {
                        width: "30%",
                        title: 'Fecha creación',
                        data: 'fecha_creacion',


                    },
                    {
                        width: "10%",
                        title: 'Usuario modificación',
                        data: 'usuariom',


                    },
                    {
                        width: "10%",
                        title: 'Fecha modificación',
                        data: 'fecha_modifica',


                    },
                    {
                        className: "text-center",
                        title: 'Acción',
                        data: 'id_archivofuente',
                        render: function(data, type, row, meta) {

                            if (row.u_estado == 0) {
                                return '<td><form class="FormularioAjax" action="<?php  echo APP_URL?>ajax/archivoAjax.php" method="POST" autocomplete="off" >' +
                                    '<input type="hidden" name="modulo_archivo" value="activar">' +
                                    '<input type="hidden" name="id_archivofuente" value="' +
                                    data +
                                    '">' +
                                    '<button type="submit" title="Activar" class="button is-acciones is-rounded is-small">' +
                                    '<i class="fas fa-check-circle"></i>' +
                                    '</button>' +
                                    '</form></td>';
                            } else {
                                return '<td><form class="FormularioAjax" action="<?php  echo APP_URL?>ajax/archivoAjax.php" method="POST" autocomplete="off" >' +
                                    '<input type="hidden" name="modulo_archivo" value="inactivar">' +
                                    '<input type="hidden" name="id_archivofuente" value="' +
                                    data +
                                    '">' +
                                    '<button type="submit" title="Inactivar" class="button is-acciones is-rounded is-small">' +
                                    '<i class="fas fa-times-circle"></i>' +
                                    '</button>' +
                                    '</form></td>';
                            }

                        }

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
                },
                "createdRow": function(row, data, dataIndex) {
                    if (data.u_estado == 0) {
                        $(row).addClass('redClass');
                    }
                },
            });






        }
    });
}
cargargrid();
</script>