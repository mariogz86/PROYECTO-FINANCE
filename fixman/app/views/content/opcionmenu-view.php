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
<?php
	 use app\controllers\FuncionesController; 
	 $insrol = new FuncionesController();  
?>
<div class="container">
    <h1 class="title">Menu option</h1>

    <h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; <label id="titulo"></label></h2>

    <div name="gridcat">


        <p class="has-text-left pt-4 pb-4">
            <a name="agregarcat" href="#" class="button is-link is-rounded btn-back"><i class="fas fa-plus"></i> &nbsp;
                Add record</a>
        </p>
        <table id="myTable" class="table table-striped table-bordered"></table>
    </div>

    <form name="foropcion" class="FormularioAjax " action="<?php echo APP_URL; ?>ajax/opcionAjax.php" method="POST"
        autocomplete="off" enctype="multipart/form-data" style="display:none">
        <input type="hidden" name="idopcion" value="">
        <p class="has-text-right pt-4 pb-4">
            <button name="regresar" type="reset" class="button is-link is-light is-rounded"><i
                    class="fas fa-arrow-alt-circle-left"></i> &nbsp; Go back</button>
        </p>
        <input type="hidden" name="modulo_Opcionmenu" value="registrar">
        <div class="col-sm-12 col-md-10">
            <div class="columns ">
                <div class="column ">
                    <div class="control ">
                        <label>Name option <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="text" name="nombre" maxlength="250" required>
                    </div>
                </div>
                <div class="column">
                    <div class="control ">
                        <label>Menu<?php echo CAMPO_OBLIGATORIO; ?></label><br>

                        <select name="cmb_menu" class="form-select" id="select_box" required>
                            <?php
                                
                                $consulta_datos="select * from \"SYSTEM\".OBTENER_menu;"; 

                                $datos = $insrol->Ejecutar($consulta_datos); 
                                echo '<option value=""   >Select a value </option>';
                                    while($campos_caja=$datos->fetch()){
                                    if($campos_caja['u_estado']==1){
                                        echo '<option value="'.$campos_caja['id_menu'].'"   > '.$campos_caja['nombre'].'</option>';
                                    }
                                        
                                    
                                    }
                                ?>
                        </select>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Order opction <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="number" name="orden" pattern="[0-9]{1,22}" maxlength="22" required>
                    </div>
                </div>

            </div>
            <div class="columns ">
                <div class="column">
                    <div class="control">
                        <label>Icon <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="text" name="icono" maxlength="255" required>
                    </div>
                </div>

                <div class="column">
                    <div class="control">
                        <label>View name<?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="text" name="nombrevista" maxlength="255" required>
                    </div>
                </div>
            </div>
            <div class="columns ">
                <div class="column">
                    <div class="control">
                        <label>Description <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="text" name="descripcion" maxlength="2000">
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
</div>



<script>
$(document).ready(function() {
    $('.form-select').select2();
    //para dejar sin seleccion el combo
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();


});
const button = document.getElementsByName("agregarcat");
const idopcion = document.getElementsByName("idopcion");
const regresar = document.getElementsByName("regresar");
const foropcion = document.getElementsByName("foropcion");
const gridcat = document.getElementsByName("gridcat");




regresar[0].addEventListener("click", (event) => {
    event.preventDefault();
    gridcat[0].style.display = "";
    foropcion[0].style.display = "none";
    document.getElementsByName("foropcion")[0].reset();
    $("#titulo")[0].innerText = "List of Menu Options";

});

button[0].addEventListener("click", (event) => {
    event.preventDefault();
    $("#titulo")[0].innerText = "New menu option";
    gridcat[0].style.display = "none";
    foropcion[0].style.display = "";
    idopcion[0].value = 0;

});
$(document).on('click', '#modificar', function(e) {

    event.preventDefault();
    $("#titulo")[0].innerText = "Modify menu option";
    var row = e.currentTarget.attributes['valor'].value;
    var dato = $("#myTable").DataTable().data()[row];
    gridcat[0].style.display = "none";
    foropcion[0].style.display = "";

    idopcion[0].value = dato.id_opcion;
    $("#select_box").val(dato.id_menu);
    $('#select_box').change();
    document.getElementsByName("nombre")[0].value = dato.nombre;
    document.getElementsByName("nombrevista")[0].value = dato.nombrevista;
    document.getElementsByName("descripcion")[0].value = dato.descripcion;
    document.getElementsByName("orden")[0].value = dato.orden;
    document.getElementsByName("icono")[0].value = dato.icono;
});


$(document).on('submit', '#foropcion', function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    formData.append("save_catalogo", true);

    $.ajax({
        type: "POST",
        url: "<?php  echo APP_URL.'ajax/menuAjax.php' ?>",
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
    $("#titulo")[0].innerText = "List of Menu Options";

    gridcat[0].style.display = "";
    foropcion[0].style.display = "none";
    document.getElementsByName("foropcion")[0].reset();
    $.ajax({
        type: "GET",
        url: "<?php  echo APP_URL.'ajax/opcionAjax.php?cargagrid' ?>",
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
                        title: 'Id opcion',
                        className: "text-center",
                        data: 'id_opcion',
                        visible: false,
                    },
                    {
                        title: 'Id menu',
                        className: "text-center",
                        data: 'id_menu',
                        visible: false,
                    },
                    {
                        width: "30%",
                        title: 'Menu',
                        data: 'menu',

                    },
                    {
                        width: "30%",
                        title: 'Option',
                        data: 'nombre',

                    },
                    {
                        width: "10%",
                        title: 'Order',
                        data: 'orden',
                        className: 'text-center'
                    },
                    {
                        width: "15%",
                        title: 'View name',
                        data: 'nombrevista',
                        className: 'text-center'
                    },
                    {
                        width: "15%",
                        title: 'Icon',
                        data: 'icono',
                        className: 'text-center'
                    },
                    {
                        width: "10%",
                        title: 'Estate',
                        data: 'u_estado',
                        className: 'text-center',
                        render: function(data, type, row, meta) {
                            if (row.u_estado == 1) {
                                return "Active";
                            } else {
                                return "Inactive";
                            }

                        }

                    },

                    // {
                    //     width: "20%",
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
                        title: 'Edit',
                        data: 'id_opcion',
                        render: function(data, type, row, meta) {
                            return '<td><a id="modificar" title="Editar" href="#" class="button is-info is-rounded is-small" valor="' +
                                meta.row + '">' +
                                '<i class="fas fa-sync fa-fw"></i></a> </td>';
                        }

                    },
                    {
                        className: "text-center",
                        title: 'Action',
                        data: 'id_opcion',
                        render: function(data, type, row, meta) {

                            if (row.u_estado == 0) {
                                return '<td><form class="FormularioAcciones" action="<?php  echo APP_URL?>ajax/opcionAjax.php" method="POST" autocomplete="off" >' +
                                    '<input type="hidden" name="modulo_opcion" value="activar">' +
                                    '<input type="hidden" name="id_opcion" value="' +
                                    data + '">' +
                                    '<button type="submit" title="Activar" class="button is-acciones is-rounded is-small">' +
                                    '<i class="fas fa-check-circle"></i>' +
                                    '</button>' +
                                    '</form></td>';
                            } else {
                                return '<td><form class="FormularioAcciones" action="<?php  echo APP_URL?>ajax/opcionAjax.php" method="POST" autocomplete="off" >' +
                                    '<input type="hidden" name="modulo_opcion" value="inactivar">' +
                                    '<input type="hidden" name="id_opcion" value="' +
                                    data + '">' +
                                    '<button type="submit" title="Inactivate" class="button is-acciones is-rounded is-small">' +
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