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
    <h1 class="title">Usuario</h1>

    <h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; <label id="titulo"></label></h2>

    <div name="gridcat">


        <p class="has-text-left pt-4 pb-4">
            <a name="agregarcat" href="#" class="button is-link is-rounded btn-back"><i class="fas fa-plus"></i> &nbsp;
                Agregar registro</a>
        </p>
        <table id="myTable" class="table table-striped table-bordered">

        </table>
        <br>


    </div>


    <p class="has-text-centered">
    <form name="formusuario" class="FormularioAjax " action="<?php echo APP_URL; ?>ajax/usuarioAjax.php" method="POST"
        autocomplete="off" enctype="multipart/form-data" style="display:none">
        <input type="hidden" name="idusuario" value="">
        <p class="has-text-right pt-4 pb-4">
            <button name="regresar" type="reset" class="button is-link is-light is-rounded"><i
                    class="fas fa-arrow-alt-circle-left"></i> &nbsp; Regresar</button>
        </p>
        <input type="hidden" name="modulo_Opcion" value="registrar">
        <div class="col-sm-12 col-md-12">
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Nombres <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="text" name="usuario_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}"
                            maxlength="40" required>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Apellidos <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="text" name="usuario_apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}"
                            maxlength="40" required>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Usuario <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="text" name="usuario_usuario" pattern="[a-zA-Z0-9]{3,20}"
                            maxlength="20" required>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Email <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="email" name="usuario_email" maxlength="70" required>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="col-sm-12 col-md-6">
                    <div class="control ">
                        <label>Roles <?php echo CAMPO_OBLIGATORIO; ?></label><br>

                        <select name="cmb_rol" class="form-select" id="select_rol" required>
                            <?php
                        
                        $consulta_datos="select * from \"SACNSYS\".obtener_roles where u_estado=1;"; 

                        $datos = $insrol->Ejecutar($consulta_datos); 
                        echo '<option value=""   >Seleccione un valor </option>';
                            while($campos_caja=$datos->fetch()){
                                echo '<option value="'.$campos_caja['id_rol'].'"   > '.$campos_caja['rol'].'</option>';
                            }
                        ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="columns">




            </div>

        </div>
        <p class="has-text-centered">
            <button type="reset" class="button is-link is-light is-rounded"><i class="fas fa-paint-roller"></i>
                &nbsp;
                Limpiar</button>
            <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp;
                Guardar</button>
        </p>
        <p class="has-text-centered pt-6">
            <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
        </p>


    </form>
    </p>
</div>



<script>
//esta propiedad en el input ejecuta la funcion JS
//onfocusout="executeFunc()"  
function executeFunc() {
    document.getElementsByName("usuario_usuario")[0].value = document.getElementsByName("usuario_nombre")[0].value +
        document.getElementsByName("usuario_apellido")[0].value;
}
//JSON.stringify(tabla.rows( { selected: true } ).data().toArray());

const button = document.getElementsByName("agregarcat");
const idusuario = document.getElementsByName("idusuario");
const seleccionados = document.getElementsByName("hdf_seleccionados");

const id_detalleactividad = document.getElementsByName("id_detalleactividad");
const regresar = document.getElementsByName("regresar");
const formusuario = document.getElementsByName("formusuario");
const gridcat = document.getElementsByName("gridcat");



$(document).ready(function() {
    $('.form-select').select2();
    //para dejar sin seleccion el combo
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();

    $("#combodetalle")[0].style.display = "none";
});

regresar[0].addEventListener("click", (event) => {
    event.preventDefault();
    gridcat[0].style.display = "";
    formusuario[0].style.display = "none";
    document.getElementsByName("formusuario")[0].reset();
    $("#titulo")[0].innerText = "Esta pantalla permite agregar, modificar,bloquear, desbloquear e inactivar un usuario del sistema.";
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();
    $("#combodetalle")[0].style.display = "none";



});

button[0].addEventListener("click", (event) => {
    event.preventDefault();
    $("#titulo")[0].innerText = "Nuevo Usuario";
    gridcat[0].style.display = "none";
    formusuario[0].style.display = "";
    idusuario[0].value = 0;
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();
});
$(document).on('click', '#modificar', function(e) {

    event.preventDefault();
    $("#titulo")[0].innerText = "Modificar Usuario";
    var row = e.currentTarget.attributes['valor'].value;
    var dato = $("#myTable").DataTable().data()[row];
    gridcat[0].style.display = "none";
    formusuario[0].style.display = "";

    idusuario[0].value = dato.id_usuario;

    document.getElementsByName("usuario_nombre")[0].value = dato.u_nombre_completo;
    document.getElementsByName("usuario_apellido")[0].value = dato.u_apellido_completo;
    document.getElementsByName("usuario_email")[0].value = dato.u_email;
    document.getElementsByName("usuario_usuario")[0].value = dato.usuario;

    $("#select_rol").val(dato.id_rol);
    $('#select_rol').change();




    $("#combodetalle")[0].style.display = "none";


});

$('#select_actividad').on('change', function() {
    var selectVal = $("#select_actividad option:selected").val();
    $.ajax({
        type: "GET",
        url: "<?php  echo APP_URL.'ajax/usuarioAjax.php' ?>",
        data: "Cargardetalleactividad=" + selectVal,
        success: function(response) {

            var res = jQuery.parseJSON(response);
            var $select = $('#select_detactiv');
            if (res.status == 200) {
                $("#combodetalle")[0].style.display = "";
                $("#select_detactiv option").remove();
                $select.append('<option value="">Seleccione un valor</option>');
                $.each(res.data, function(id_catalogovalor, nombre) {
                    $select.append('<option value=' + nombre.id_catalogovalor + '>' + nombre
                        .nombre +
                        '</option>');
                });

                if (parseInt(id_detalleactividad[0].value) > 0) {
                    $("#select_detactiv").val(id_detalleactividad[0].value);
                    $('#select_detactiv').change();
                }


            } else {
                $("#select_detactiv option").remove();
                $select.append('<option value="">Seleccione un valor</option>');
                $("#combodetalle")[0].style.display = "none";
            }


        }
    });
});


$(document).on('submit', '#formusuario', function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    formData.append("save_formulario", true);

    $.ajax({
        type: "POST",
        url: "<?php  echo APP_URL.'ajax/usuarioAjax.php' ?>",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {


            $(".loadersacn").fadeOut("slow");


            var res = jQuery.parseJSON(response);

        }
    });

});

function cargargrid() {
    $(".loadersacn")[0].style.display = "";
    $("#titulo")[0].innerText = "Esta pantalla permite agregar, modificar,bloquear, desbloquear e inactivar un usuario del sistema.";

    gridcat[0].style.display = "";
    formusuario[0].style.display = "none";
    document.getElementsByName("formusuario")[0].reset();
    $.ajax({
        type: "GET",
        url: "<?php  echo APP_URL.'ajax/usuarioAjax.php?cargagrid' ?>",
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
                columns: [
                    // {
                    //     className: "text-center",
                    //     data: null,
                    // },
                    {
                        title: 'Id Usuario',
                        className: "text-center",
                        data: 'id_usuario',
                        visible: false,
                    },
                    {
                        title: 'idrol',
                        className: "text-center",
                        data: 'id_rol',
                        visible: false,
                    },

                    {
                        //width: "100%",
                        title: 'Nombres',
                        className: "text-center",
                        data: 'u_nombre_completo',
                    },

                    {
                        // width: "30%",
                        title: 'Apellidos',
                        className: "text-center",
                        data: 'u_apellido_completo',
                    },
                    {
                        // width: "30%",
                        title: 'Login Usuario',
                        data: 'usuario',

                    },
                    {
                        // width: "30%",
                        title: 'Correo',
                        data: 'u_email',

                    },
                    {
                        // width: "30%",
                        title: 'Rol',
                        data: 'rol',

                    },
                    {
                        // width: "30%",
                        title: 'Usuario bloqueado',
                        data: 'u_bloqueado',
                        render: function(data, type, row, meta) {
                            if (row.u_bloqueado == 1) {
                                return "Si";
                            } else {
                                return "No";
                            }

                        }

                    },
                    {
                        // width: "30%",
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
                    // {
                    //     // width: "30%",
                    //     title: 'Usuario creación',
                    //     data: 'usuario',


                    // },
                    // {
                    //     // width: "30%",
                    //     title: 'Fecha creación',
                    //     data: 'fecha_creacion',


                    // },
                    // {
                    //     // width: "30%",
                    //     title: 'Usuario modificación',
                    //     data: 'usuariom',


                    // },
                    // {
                    //     // width: "30%",
                    //     title: 'Fecha modificación',
                    //     data: 'fecha_modifica',


                    // },
                    {

                        className: "text-center",
                        title: 'Acciónes',
                        data: 'id_usuario',
                        render: function(data, type, row, meta) {
                            cadena =
                                '<div style="width: 150px;"><div style="float: left;margin-right: 2px;"><a id="modificar" title="Editar" href="#" class="button is-info is-rounded is-small" valor="' +
                                meta.row + '">' +
                                '<i class="fas fa-sync fa-fw"></i></a></div> ';

                            cadena = cadena + '<td>' +
                                '<div style="float: left;">';
                            if (row.u_bloqueado == 0) {

                                cadena = cadena +
                                    '<form class="FormularioAjax" action="<?php  echo APP_URL?>ajax/usuarioAjax.php" method="POST" autocomplete="off">' +
                                    '<input type="hidden" name="modulo_usuario" value="bloquear">' +
                                    '<input type="hidden" name="id_usuario" value="' +
                                    data + '">' +

                                    '<button type="submit" title="Bloquear" class="button is-accionesbloquear is-rounded is-small">' +
                                    '<i class="fas fa-lock"></i>' +
                                    '</button>' +
                                    '</form>';
                            } else {
                                cadena = cadena +
                                    '<form class="FormularioAjax" action="<?php  echo APP_URL?>ajax/usuarioAjax.php" method="POST" autocomplete="off">' +
                                    '<input type="hidden" name="modulo_usuario" value="desbloquear">' +
                                    '<input type="hidden" name="id_usuario" value="' +
                                    data + '">' +
                                    '<button type="submit" title="Desbloquear" class="button is-accionesbloquear is-rounded is-small">' +
                                    '<i class="fas fa-unlock"></i>' +
                                    '</button>' +
                                    '</form>';
                            }
                            cadena = cadena + '</div>';
                            cadena = cadena + '<div>';
                            if (row.u_estado == 0) {


                                cadena = cadena +
                                    '<form class="FormularioAjax" action="<?php  echo APP_URL?>ajax/usuarioAjax.php" method="POST" autocomplete="off" >' +
                                    '<input type="hidden" name="modulo_usuario" value="activar">' +
                                    '<input type="hidden" name="id_usuario" value="' +
                                    data + '">' +
                                    '<button type="submit" title="Activar" class="button is-acciones is-rounded is-small">' +
                                    '<i class="fas fa-check-circle"></i>' +
                                    '</button>' +
                                    '</form>';
                            } else {
                                cadena = cadena +
                                    '<form class="FormularioAjax" action="<?php  echo APP_URL?>ajax/usuarioAjax.php" method="POST" autocomplete="off" >' +
                                    '<input type="hidden" name="modulo_usuario" value="inactivar">' +
                                    '<input type="hidden" name="id_usuario" value="' +
                                    data + '">' +
                                    '<button type="submit" title="Inactivar" class="button is-acciones is-rounded is-small">' +
                                    '<i class="fas fa-times-circle"></i>' +
                                    '</button>' +
                                    '</form>';
                            }
                            cadena = cadena + '</div>';
                            cadena = cadena + '</div></td>';
                            return cadena;

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
                    if (data.u_bloqueado == 1) {
                        $(row).addClass('bloqueado');
                    }
                },
            });






        }
    });
}
cargargrid();
</script>