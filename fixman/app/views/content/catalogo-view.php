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
    <h1 class="title">Catalog</h1>

    <h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; <label id="titulo"></label></h2>

    <div name="gridcat">


        <p class="has-text-left pt-4 pb-4">
            <a name="agregarcat" href="#" class="button is-link is-rounded btn-back"><i class="fas fa-plus"></i> &nbsp;
                Add record</a>
        </p>
        <table id="myTable" class="table table-striped table-bordered"></table>
    </div>

    <form name="formcatalogo" class="FormularioAjax " action="<?php echo APP_URL; ?>ajax/catalogoAjax.php" method="POST"
        autocomplete="off" enctype="multipart/form-data" style="display:none">
        <input type="hidden" name="idcatalogo" value="">
        <p class="has-text-right pt-4 pb-4">
            <button name="regresar" type="reset" class="button is-link is-light is-rounded"><i
                    class="fas fa-arrow-alt-circle-left"></i> &nbsp; Go back</button>
        </p>
        <input type="hidden" name="modulo_Opcion" value="registrar">
        <div class="col-sm-12 col-md-6">
            <div class="columns ">
                <div class="column ">
                    <div class="control ">
                        <label>Name <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="text" name="nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ:# ]{3,70}"
                            maxlength="250" required>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Code <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input id="inputcodigo" class="input" type="text" name="codigo" maxlength="200" required>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Descripción <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <!-- <input class="input" type="text" name="descripcion" maxlength="2000" required> -->
                         <textarea name="descripcion"  id="descripcion" class="input" style="height: 100px;"
                                            required></textarea>
                    </div>
                </div>


            </div>
        </div>
        <p class="has-text-centered">
            <button type="reset" class="button is-link is-light is-rounded"><i class="fas fa-paint-roller"></i> &nbsp;
                Clean</button>
            <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Save</button>
        </p>
        <p class="has-text-centered pt-6">
            <small>Fields marked with <?php echo CAMPO_OBLIGATORIO; ?> are mandatory</small>
        </p>
    </form>
</div>
 

<script>


const button = document.getElementsByName("agregarcat");
const idcatolgo = document.getElementsByName("idcatalogo");
const regresar = document.getElementsByName("regresar");
const formcatalogo = document.getElementsByName("formcatalogo");
const gridcat = document.getElementsByName("gridcat");

regresar[0].addEventListener("click", (event) => {
    event.preventDefault();
    gridcat[0].style.display = "";
    formcatalogo[0].style.display = "none";
    document.getElementsByName("formcatalogo")[0].reset();
    $("#titulo")[0].innerText = "Catalog List";

});

button[0].addEventListener("click", (event) => {
    event.preventDefault();
    $("#titulo")[0].innerText = "New Catalog";
    gridcat[0].style.display = "none";
    formcatalogo[0].style.display = "";
    idcatolgo[0].value = 0;
    document.getElementById("inputcodigo").disabled = false;
});
$(document).on('click', '#modificar', function(e) {

    event.preventDefault();
    $("#titulo")[0].innerText = "Modify Catalog";
    var row = e.currentTarget.attributes['valor'].value;
    var dato = $("#myTable").DataTable().data()[row];
    gridcat[0].style.display = "none";
    formcatalogo[0].style.display = "";

    idcatolgo[0].value = dato.id_catalogo;
    document.getElementsByName("nombre")[0].value = dato.nombre;
    document.getElementsByName("descripcion")[0].value = dato.descripcion;
    document.getElementsByName("codigo")[0].value = dato.codigo;

    document.getElementById("inputcodigo").disabled = true;


});


$(document).on('submit', '#formcatalogo', function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    formData.append("save_catalogo", true);

    $.ajax({
        type: "POST",
        url: "<?php  echo APP_URL.'ajax/catalogoAjax.php' ?>",
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
    $("#titulo")[0].innerText = "Catalog List";

    gridcat[0].style.display = "";
    formcatalogo[0].style.display = "none";
    document.getElementsByName("formcatalogo")[0].reset();
    $.ajax({
        type: "GET",
        url: "<?php  echo APP_URL.'ajax/catalogoAjax.php?cargagrid' ?>",
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
                        data: 'id_catalogo',
                        visible: false,
                    },
                    {
                        width: "30%",
                        title: 'Name',
                        data: 'nombre',

                    },
                    {
                        width: "30%",
                        title: 'Code',
                        data: 'codigo'

                    },
                    {
                        width: "50%",
                        title: 'Description',
                        data: 'descripcion',


                    },
                    {
                        width: "30%",
                        title: 'Estate',
                        data: 'u_estado',
                        render: function(data, type, row, meta) {
                            if (row.u_estado == 1) {
                                return "Active";
                            } else {
                                return "Inactive";
                            }

                        }

                    },

                    // {
                    //     width: "30%",
                    //     title: 'Usuario creación',
                    //     data: 'usuario',


                    // },
                    // {
                    //     width: "30%",
                    //     title: 'Fecha creación',
                    //     data: 'fecha_creacion',


                    // },
                    // {
                    //     width: "30%",
                    //     title: 'Usuario modificación',
                    //     data: 'usuariom',


                    // },
                    // {
                    //     width: "30%",
                    //     title: 'Fecha modificación',
                    //     data: 'fecha_modifica',


                    // },
                    {
                        className: "text-center",
                        title: 'Editar',
                        data: 'id_catalogo',
                        render: function(data, type, row, meta) {
                            return '<td><a id="modificar" title="Edit" href="#" class="button is-info is-rounded is-small" valor="' +
                                meta.row + '">' +
                                '<i class="fas fa-sync fa-fw"></i></a> </td>';
                        }

                    },
                    {
                        className: "text-center",
                        title: 'Acción',
                        data: 'id_catalogo',
                        render: function(data, type, row, meta) {

                            if (row.u_estado == 0) {
                                return '<td><form class="FormularioAcciones" action="<?php  echo APP_URL?>ajax/catalogoAjax.php" method="POST" autocomplete="off" >' +
                                    '<input type="hidden" name="modulo_catalogo" value="activar">' +
                                    '<input type="hidden" name="id_catalogo" value="' +
                                    data + '">' +
                                    '<button type="submit" title="Activate" class="button is-acciones is-rounded is-small">' +
                                    '<i class="fas fa-check-circle"></i>' +
                                    '</button>' +
                                    '</form></td>';
                            } else {
                                return '<td><form class="FormularioAcciones" action="<?php  echo APP_URL?>ajax/catalogoAjax.php" method="POST" autocomplete="off" >' +
                                    '<input type="hidden" name="modulo_catalogo" value="inactivar">' +
                                    '<input type="hidden" name="id_catalogo" value="' +
                                    data + '">' +
                                    '<button type="submit" title="Inactivate" class="button is-acciones is-rounded is-small">' +
                                    '<i class="fas fa-times-circle"></i>' +
                                    '</button>' +
                                    '</form></td>';
                            }

                        }

                    },
                ]
            });



        }
    });
}
cargargrid();
</script>