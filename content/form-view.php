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
    <h1 class="title">Formulario</h1>

    <h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; <label id="titulo"></label></h2>

    <div name="gridcat">


        <p class="has-text-left pt-4 pb-4">
            <a name="agregarcat" href="#" class="button is-link is-rounded btn-back"><i class="fas fa-plus"></i> &nbsp;
                Agregar registro</a>
        </p>
        <table id="myTable" class="table table-striped table-bordered">

        </table>
        <br>
        <form name="cambioruta" class="FormularioAjax " action="<?php echo APP_URL; ?>ajax/formularioAjax.php"
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


    <p class="has-text-centered">
    <form name="formformulario" class="FormularioAjax " action="<?php echo APP_URL; ?>ajax/formularioAjax.php"
        method="POST" autocomplete="off" enctype="multipart/form-data" style="display:none">
        <input type="hidden" name="idformulario" value="">
        <input type="hidden" name="id_detalleactividad" value="">
        <p class="has-text-right pt-4 pb-4">
            <button name="regresar" type="reset" class="button is-link is-light is-rounded"><i
                    class="fas fa-arrow-alt-circle-left"></i> &nbsp; Regresar</button>
        </p>
        <input type="hidden" name="modulo_Opcion" value="registrar">
        <div class="col-sm-12 col-md-10">
            <div class="columns ">
            <div class="col-sm-12 col-md-6">
                    <div class="control ">
                        <?php
                        
                        $consulta_datos="select * from \"SYSTEM\".obtener_valor_porcatalogo('anio' ) where estado=1;"; 

                        $datos = $insrol->Ejecutar($consulta_datos); 
                        $catalogo =$insrol->ejecutarconsultaarreglo("select c.* from  \"SYSTEM\".catalogo c  where c.codigo='anio';"); 
                        echo '<label>'.$catalogo[0]['nombre'].' '. CAMPO_OBLIGATORIO.'</label><br>';

                        echo '<select name="cmb_anio" class="form-select" id="select_anio" required>';
                            echo '<option value="">Seleccione un valor </option>';
                            while($campos_caja=$datos->fetch()){
                            if($campos_caja['estado']==1){
                            echo '<option value="'.$campos_caja['id_catalogovalor'].'"> '.$campos_caja['nombre'].'
                            </option>';
                            }


                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="control ">

                        <?php
                             $catalogo =$insrol->ejecutarconsultaarreglo("select c.* from  \"SYSTEM\".catalogo c  where c.codigo='tipfor' "); 
                        
                        $consulta_datos="select * from \"SYSTEM\".obtener_valor_porcatalogo('tipfor' ) where estado=1;"; 

                        $datos = $insrol->Ejecutar($consulta_datos); 
                        echo '<label>'.$catalogo[0]['nombre'].' '. CAMPO_OBLIGATORIO.'</label><br>';

                        echo ' <select name="cmb_tipocarga" class="form-select" id="select_tipocarga" required>';
                            echo '<option value="">Seleccione un valor </option>';
                            while($campos_caja=$datos->fetch()){
                            if($campos_caja['estado']==1){
                            echo '<option value="'.$campos_caja['id_catalogovalor'].'"> '.$campos_caja['nombre'].'
                            </option>';
                            }


                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="control ">
                        <label>Actividad <?php echo CAMPO_OBLIGATORIO; ?></label><br>

                        <select name="cmb_catatividad" class="form-select" id="select_actividad" required>
                            <?php
                        
                        $consulta_datos="select * from \"SYSTEM\".obtener_valor_porcatalogo('actividad' )  where estado=1;"; 

                        $datos = $insrol->Ejecutar($consulta_datos); 
                        echo '<option value=""   >Seleccione un valor </option>';
                            while($campos_caja=$datos->fetch()){
                               if($campos_caja['estado']==1){
                                echo '<option value="'.$campos_caja['codcat'].'"   > '.$campos_caja['nombre'].'</option>';
                               } 
                            }
                        ?>
                        </select>
                    </div>
                </div>

               
            </div>
            <div class="columns ">

            </div>
            <div class="columns">
                <div class="col-sm-12 col-md-6">
                    <div class="control">
                        <label>Nombre <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="text" name="nombre" maxlength="200" required>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="control ">

                        <?php
                             $catalogo =$insrol->ejecutarconsultaarreglo("select c.* from  \"SYSTEM\".catalogo c  where c.codigo='tdencu'  ;"); 
                        
                        $consulta_datos="select * from \"SYSTEM\".obtener_valor_porcatalogo('tdencu' ) where estado=1;"; 

                        $datos = $insrol->Ejecutar($consulta_datos); 
                        echo '<label>'.$catalogo[0]['nombre'].' '. CAMPO_OBLIGATORIO.'</label><br>';

                        echo ' <select name="cmb_tipoencuesta" class="form-select" id="select_tipoencuesta" required>';
                            echo '<option value="">Seleccione un valor </option>';
                            while($campos_caja=$datos->fetch()){
                            if($campos_caja['estado']==1){
                            echo '<option value="'.$campos_caja['id_catalogovalor'].'"> '.$campos_caja['nombre'].'
                            </option>';
                            }


                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div id="combodetalle" class="col-sm-12 col-md-6" style="display: none;">
                    <div class="control ">
                        <label>Detalle de actividad</label><br>

                        <select name="cmb_detactiv" class="form-select" id="select_detactiv">
                            <option value="0">Seleccione un valor </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Ruta <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="text" name="ruta" required>
                    </div>
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
        </div>

    </form>
    </p>
</div>



<script>
//JSON.stringify(tabla.rows( { selected: true } ).data().toArray());

const button = document.getElementsByName("agregarcat");
const idformulario = document.getElementsByName("idformulario");
const seleccionados = document.getElementsByName("hdf_seleccionados");

const id_detalleactividad = document.getElementsByName("id_detalleactividad");
const regresar = document.getElementsByName("regresar");
const formformulario = document.getElementsByName("formformulario");
const gridcat = document.getElementsByName("gridcat");

const btncambiarruta = document.getElementsByName("btncambiarruta");
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
                dato = value.id_formulario;
            } else {
                dato = dato + "," + value.id_formulario
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

    $("#combodetalle")[0].style.display = "none";
});

regresar[0].addEventListener("click", (event) => {
    event.preventDefault();
    gridcat[0].style.display = "";
    formformulario[0].style.display = "none";
    document.getElementsByName("formformulario")[0].reset();
    $("#titulo")[0].innerText = "Lista de Formulario";
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();
    $("#combodetalle")[0].style.display = "none";



});

button[0].addEventListener("click", (event) => {
    event.preventDefault();
    $("#titulo")[0].innerText = "Nuevo Formulario";
    gridcat[0].style.display = "none";
    formformulario[0].style.display = "";
    idformulario[0].value = 0;
    id_detalleactividad[0].value = 0;
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();
    $("#combodetalle")[0].style.display = "none";
});
$(document).on('click', '#modificar', function(e) {

    event.preventDefault();
    $("#titulo")[0].innerText = "Modificar Formulario";
    var row = e.currentTarget.attributes['valor'].value;
    var dato = $("#myTable").DataTable().data()[row];
    gridcat[0].style.display = "none";
    formformulario[0].style.display = "";

    idformulario[0].value = dato.id_formulario;
    id_detalleactividad[0].value = dato.id_vdetactividad;
    document.getElementsByName("nombre")[0].value = dato.nombre;
    document.getElementsByName("ruta")[0].value = dato.ruta;

    $("#select_anio").val(dato.id_vanio);
    $('#select_anio').change();
    $("#select_tipocarga").val(dato.id_vtipocarga);
    $('#select_tipocarga').change();
    $("#select_actividad").val(dato.cod_actividad);
    $('#select_actividad').change();
    $("#select_tipoencuesta").val(dato.id_vtipoencuesta);
    $('#select_tipoencuesta').change();



    $("#combodetalle")[0].style.display = "none";


});

$('#select_actividad').on('change', function() {
    var selectVal = $("#select_actividad option:selected").val();
    $.ajax({
        type: "GET",
        url: "<?php  echo APP_URL.'ajax/formularioAjax.php' ?>",
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


$(document).on('submit', '#formformulario', function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    formData.append("save_formulario", true);

    $.ajax({
        type: "POST",
        url: "<?php  echo APP_URL.'ajax/formularioAjax.php' ?>",
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
    $("#titulo")[0].innerText = "Lista de Formulario";

    gridcat[0].style.display = "";
    formformulario[0].style.display = "none";
    document.getElementsByName("formformulario")[0].reset();
    $.ajax({
        type: "GET",
        url: "<?php  echo APP_URL.'ajax/formularioAjax.php?cargagrid' ?>",
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
                        data: 'id_formulario',
                        visible: false,
                    },
                    {
                        title: 'id_vanio',
                        className: "text-center",
                        data: 'id_vanio',
                        visible: false,
                    },
                    {
                        title: 'id_vtipocarga',
                        className: "text-center",
                        data: 'id_vtipocarga',
                        visible: false,
                    },
                    {
                        title: 'cod_actividad',
                        className: "text-center",
                        data: 'cod_actividad',
                        visible: false,
                    },
                    {
                        title: 'id_vdetactividad',
                        className: "text-center",
                        data: 'id_vdetactividad',
                        visible: false,
                    },
                    {
                        title: 'id_vtipoencuesta',
                        className: "text-center",
                        data: 'id_vtipoencuesta',
                        visible: false,
                    },
                    {
                        width: "20%",
                        title: 'Año',
                        data: 'anio',

                    },
                    {
                        width: "20%",
                        title: 'Tipo Encuesta',
                        data: 'tipoencuesta',

                    },
                    {
                        width: "20%",
                        title: 'Tipo carga',
                        data: 'tipocarga'

                    },
                    {
                        width: "40%",
                        title: 'Actividad económica',
                        data: 'actividad',


                    },
                    {
                        width: "20%",
                        title: 'Detalle actividad',
                        data: 'detalleact',


                    },
                    {
                        width: "20%",
                        title: 'Formulario',
                        data: 'nombre',


                    },
                    {
                        width: "20%",
                        title: 'Ruta',
                        data: 'ruta',


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
                        title: 'Editar',
                        data: 'id_formulario',
                        render: function(data, type, row, meta) {
                            return '<td><a id="modificar" title="Editar" href="#" class="button is-info is-rounded is-small" valor="' +
                                meta.row + '">' +
                                '<i class="fas fa-sync fa-fw"></i></a> </td>';
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