<style>
.has-text-centered {
    text-align: center !important;
    pad
}

p {
    margin-top: 5px !important;
    margin-bottom: 1rem !important;
}
</style>
<div class="container">
    <h1 class="title">Roles</h1>

    <h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; <label id="titulo"></label></h2>

    <div name="gridcat">


        <p class="has-text-left pt-4 pb-4">
            <a name="agregarcat" href="#" class="button is-link is-rounded btn-back"><i class="fas fa-plus"></i> &nbsp;
                Agregar registro</a>
        </p>
        <table id="myTable" class="table table-striped table-bordered"></table>
    </div>

    <form name="formrol" class="FormularioAjax " action="<?php echo APP_URL; ?>ajax/rolAjax.php" method="POST"
        autocomplete="off" enctype="multipart/form-data" style="display:none">
        <input type="hidden" name="idrol" value="">
        <p class="has-text-right pt-4 pb-4">
            <button name="regresar" type="reset" class="button is-link is-light is-rounded"><i
                    class="fas fa-arrow-alt-circle-left"></i> &nbsp; Regresar</button>
        </p>
        <input type="hidden" name="modulo_Opcion" value="registrar">
        <div class="col-sm-12 col-md-6">
            <div class="columns ">
                <div class="column ">
                    <div class="control ">
                        <label>Rol <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="text" name="rol" 
                            maxlength="250" required>
                    </div>
                </div>
            </div> 
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Descripción <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="text" name="descripcion" maxlength="2000" required>
                    </div>
                </div>


            </div>
        </div>
        <p class="has-text-centered">
            <button type="reset" class="button is-link is-light is-rounded"><i class="fas fa-paint-roller"></i> &nbsp;
                Limpiar</button>
            <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Guardar</button>
        </p>
        <p class="has-text-centered pt-6">
            <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
        </p>
    </form>
</div>



<script>
const button = document.getElementsByName("agregarcat");
const idrol = document.getElementsByName("idrol");
const regresar = document.getElementsByName("regresar");
const formrol = document.getElementsByName("formrol");
const gridcat = document.getElementsByName("gridcat");

regresar[0].addEventListener("click", (event) => {
    event.preventDefault();
    gridcat[0].style.display = "";
    formrol[0].style.display = "none";
    document.getElementsByName("formrol")[0].reset();
    $("#titulo")[0].innerText = "Lista de Roles";

});

button[0].addEventListener("click", (event) => {
    event.preventDefault();
    $("#titulo")[0].innerText = "Nuevo Rol";
    gridcat[0].style.display = "none";
    formrol[0].style.display = "";
    idrol[0].value = 0;
    document.getElementById("inputcodigo").disabled = false;
});
$(document).on('click', '#modificar', function(e) {

    event.preventDefault();
    $("#titulo")[0].innerText = "Modificar Rol";
    var row = e.currentTarget.attributes['valor'].value;
    var dato = $("#myTable").DataTable().data()[row];
    gridcat[0].style.display = "none";
    formrol[0].style.display = "";

    idrol[0].value = dato.id_rol;
    document.getElementsByName("rol")[0].value = dato.rol;
    document.getElementsByName("descripcion")[0].value = dato.descripcion;
});


$(document).on('submit', '#formrol', function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    formData.append("save_catalogo", true);

    $.ajax({
        type: "POST",
        url: "<?php  echo APP_URL.'ajax/rolAjax.php' ?>",
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
    $("#titulo")[0].innerText = "Lista de Roles";

    gridcat[0].style.display = "";
    formrol[0].style.display = "none";
    document.getElementsByName("formrol")[0].reset();
    $.ajax({
        type: "GET",
        url: "<?php  echo APP_URL.'ajax/rolAjax.php?cargagrid' ?>",
        success: function(response) {
            $(".loadersacn").fadeOut("slow");
            var res = jQuery.parseJSON(response);
            var estilo = "";

            $('#myTable').DataTable({
                data: res.data,
                language: {
                    "url": "<?php  echo APP_URL?>config/Spanish.json"
                },
                //searching: false, 
                "createdRow": function(row, data, dataIndex) {
                    if (data.u_estado == 0) {
                        $(row).addClass('redClass');
                    }
                },
                destroy: true,
                responsive: true,
                columns: [{
                        title: 'Id catálogo',
                        className: "text-center",
                        data: 'id_rol',
                        visible: false,
                    },
                    {
                        width: "30%",
                        title: 'Nombre',
                        data: 'rol',

                    }, 
                    {
                        width: "50%",
                        title: 'Descripción',
                        data: 'descripcion', 
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
                        width: "30%",
                        title: 'Usuario modificación',
                        data: 'usuariom',


                    },
                    {
                        width: "30%",
                        title: 'Fecha modificación',
                        data: 'fecha_modifica',


                    },
                    {
                        className: "text-center",
                        title: 'Editar',
                        data: 'id_rol',
                        render: function(data, type, row, meta) {
                            return '<td><a id="modificar" title="Editar" href="#" class="button is-info is-rounded is-small" valor="' +
                                meta.row + '">' +
                                '<i class="fas fa-sync fa-fw"></i></a> </td>';
                        }

                    },
                    {
                        className: "text-center",
                        title: 'Acción',
                        data: 'id_rol',
                        render: function(data, type, row, meta) {

                            if (row.u_estado == 0) {
                                return '<td><form class="FormularioAjax" action="<?php  echo APP_URL?>ajax/rolAjax.php" method="POST" autocomplete="off" >' +
                                    '<input type="hidden" name="modulo_rol" value="activar">' +
                                    '<input type="hidden" name="id_rol" value="' +
                                    data + '">' +
                                    '<button type="submit" title="Activar" class="button is-acciones is-rounded is-small">' +
                                    '<i class="fas fa-check-circle"></i>' +
                                    '</button>' +
                                    '</form></td>';
                            } else {
                                return '<td><form class="FormularioAjax" action="<?php  echo APP_URL?>ajax/rolAjax.php" method="POST" autocomplete="off" >' +
                                    '<input type="hidden" name="modulo_rol" value="inactivar">' +
                                    '<input type="hidden" name="id_rol" value="' +
                                    data + '">' +
                                    '<button type="submit" title="Inactivar" class="button is-acciones is-rounded is-small">' +
                                    '<i class="fas fa-times-circle"></i>' +
                                    '</button>' +
                                    '</form></td>';
                            }

                        }

                    },
                ],
                
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