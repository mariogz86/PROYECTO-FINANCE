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
    <h1 class="title">Opción por rol</h1>
    <form name="formopcionrol" class="FormularioAjax " action="<?php echo APP_URL; ?>ajax/opcionrolAjax.php"
        method="POST" autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" name="idopcionrol" value="">
        <input type="hidden" name="modulo_Opcionrol" value="registrar">
        <div class="col-sm-12 col-md-12">
            <div class="columns ">
                <div class="column">
                    <div class="control ">
                        <label>Roles <?php echo CAMPO_OBLIGATORIO; ?></label><br>

                        <select name="cmb_rol" class="form-select" id="select_rol" required>
                            <?php
                        
                        $consulta_datos="select * from \"SYSTEM\".obtener_roles where u_estado=1;"; 

                        $datos = $insrol->Ejecutar($consulta_datos); 
                        echo '<option value=""   >Select a value </option>';
                            while($campos_caja=$datos->fetch()){
                                echo '<option value="'.$campos_caja['id_rol'].'"   > '.$campos_caja['rol'].'</option>';
                            }
                        ?>
                        </select>
                    </div>
                </div>

                <div class="column">
                <div class="control ">
                        <label>Opción <?php echo CAMPO_OBLIGATORIO; ?></label><br>

                        <select name="cmb_opcion" class="form-select" id="select_opcion" required>
                            <?php
                        
                        $consulta_datos="select * from \"SYSTEM\".obtener_opcionmenu where u_estado=1;"; 

                        $datos = $insrol->Ejecutar($consulta_datos); 
                        echo '<option value=""   >Select a value </option>';
                            while($campos_caja=$datos->fetch()){
                                echo '<option value="'.$campos_caja['id_opcion'].'"   > '.$campos_caja['nombre'].'</option>';
                            }
                        ?>
                        </select>
                    </div>
                </div>
                <div class="column">
                    <div class="control ">
                        <label> &nbsp;</label><br>
                        <button name="btnmostrar" type="submit" class="button is-info is-rounded"><i
                                class="far fa-save"></i>
                            &nbsp;
                            Asociar opción al rol</button>
                    </div>

                </div>



            </div>


        </div>

    </form>

    <div name="gridcat">

        <table id="myTable" class="table table-striped table-bordered"></table>
    </div>

    <p class="has-text-centered pt-6">
        <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
    </p>
</div>
</div>



<script>
$(document).ready(function() {
    $('.form-select').select2();
    //para dejar sin seleccion el combo
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();


});
const idopcionrol = document.getElementsByName("idopcionrol");
const formopcionrol = document.getElementsByName("formopcionrol");
const gridcat = document.getElementsByName("gridcat");

// $('#select_rol').on('change', function() {
//     var selectVal = $("#select_rol option:selected").val();
//     if (selectVal != "" && selectVal != undefined) {
//         $.ajax({
//             type: "GET",
//             url: "<?php  echo APP_URL.'ajax/opcionrolAjax.php' ?>",
//             data: "cargagrid=" + $("#select_rol option:selected").val(),
//             success: function(response) {
//                 $(".loadersacn").fadeOut("slow");
//                 var res = jQuery.parseJSON(response);
//                 if (res.status == 200) {
//                     llenargird(res.data);

//                 } else {
//                     cargargrid([]);
//                 }

//             }
//         });
//     }
// });



function cargargrid() {
    $(".loadersacn")[0].style.display = "";
    

    gridcat[0].style.display = "";

    document.getElementsByName("formopcionrol")[0].reset();
    $.ajax({
        type: "GET",
        url: "<?php  echo APP_URL.'ajax/opcionrolAjax.php?cargagrid=0' ?>",
        success: function(response) {
            $(".loadersacn").fadeOut("slow");
            var res = jQuery.parseJSON(response);
            var estilo = "";

            llenargird(res.data);



        }
    });
}
cargargrid();

function llenargird(datos){
    $('#myTable').DataTable({
                data: datos,
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
                        data: 'rolopcion_id',
                        visible: false,
                    },
                    {
                        title: 'Id rol',
                        className: "text-center",
                        data: 'id_rol',
                        visible: false,
                    },
                    {
                        title: 'Id opcion',
                        className: "text-center",
                        data: 'id_opcion',
                        visible: false,
                    },
                    {
                        width: "30%",
                        title: 'Rol',
                        data: 'rol',

                    },
                    {
                        width: "30%",
                        title: 'Opción',
                        data: 'opcion',

                    },                    
                    {
                        width: "10%",
                        title: 'Estado',
                        data: 'u_estado',
                        className: 'text-center',
                        render: function(data, type, row, meta) {
                            if (row.u_estado == 1) {
                                return "Activo";
                            } else {
                                return "Inactivo";
                            }

                        }

                    },

                    {
                        width: "20%",
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
                        title: 'Acción',
                        data: 'rolopcion_id',
                        render: function(data, type, row, meta) {

                            if (row.u_estado == 0) {
                                return '<td><form class="FormularioAcciones" action="<?php  echo APP_URL?>ajax/opcionrolAjax.php" method="POST" autocomplete="off" >' +
                                    '<input type="hidden" name="modulo_opcion" value="activar">' +
                                    '<input type="hidden" name="rolopcion_id" value="' +
                                    data + '">' +
                                    '<button type="submit" title="Activar" class="button is-acciones is-rounded is-small">' +
                                    '<i class="fas fa-check-circle"></i>' +
                                    '</button>' +
                                    '</form></td>';
                            } else {
                                return '<td><form class="FormularioAcciones" action="<?php  echo APP_URL?>ajax/opcionrolAjax.php" method="POST" autocomplete="off" >' +
                                    '<input type="hidden" name="modulo_opcion" value="inactivar">' +
                                    '<input type="hidden" name="rolopcion_id" value="' +
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
</script>