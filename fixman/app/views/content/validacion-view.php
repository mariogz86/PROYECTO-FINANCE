<style>
.has-text-centered {
    text-align: center !important;

}

p {
    margin-top: 5px !important;

    margin-bottom: 1rem !important;
}

.col-md-6.5 {
    flex: 0 0 44.66667%;
    max-width: 50.66667%;
}

.fileexcel {

    padding: 5px 5px 5px 5px;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    background-color: #cce5ff;
    background-clip: padding-box;
    border: 1px solid #181818;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-radius: .25rem;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}
</style>
<?php
	 use app\controllers\FuncionesController; 
	 $insrol = new FuncionesController();  
?>
<div class="container">
<?php

ini_set('max_execution_time', '0'); 
set_time_limit(0);
//ini_set('memory_limit', '1024'); 

?>
    <h1 class="title">Validaciones</h1>

    <h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; <label id="titulo"></label></h2>

    <p class="has-text-centered">
    <form name="formvalidacion" class="FormularioAjax"
        action="<?php echo APP_URL; ?>ajax/validacionAjax.php?modulo_Opcion=registrar" method="POST" autocomplete="off"
        enctype="multipart/form-data" style="display:none">
        <input type="hidden" name="idvalidacion" value="">
        <p class="has-text-right pt-1 pb-1">
            <button name="regresar" type="reset" class="button is-link is-light is-rounded"><i
                    class="fas fa-arrow-alt-circle-left"></i> &nbsp; Regresar</button>
        </p>
        <div class="col-sm-12 col-md-12">
            <div class="columns">
                <div class="col-sm-12 col-md-8">
                    <div class="control">
                        <label>Nombre validación<?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input id="nombrevalidacion" class="input" type="text" name="nombre" maxlength="2000" required>
                    </div>
                </div>
            </div>
            <div class="columns">
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

                        <?php
                             $catalogo =$insrol->ejecutarconsultaarreglo("select c.* from  \"SYSTEM\".catalogo c  where c.codigo='tipval'"); 
                        
                        $consulta_datos="select * from \"SYSTEM\".obtener_valor_porcatalogo('tipval' ) where estado=1;"; 

                        $datos = $insrol->Ejecutar($consulta_datos); 
                        echo '<label>'.$catalogo[0]['nombre'].'</label><br>';

                        echo ' <select name="cmb_tipvalidacion" class="form-select" id="select_tipvalidacion" required >';
                            echo '<option value="">Seleccione un valor </option>';
                            while($campos_caja=$datos->fetch()){
                            if($campos_caja['estado']==1){
                            echo '<option value="'.$campos_caja['id_catalogovalor'].'">'.$campos_caja['nombre'].'</option>';
                            }


                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="column">
                    <div class="control ">

                        <?php
                             $catalogo =$insrol->ejecutarconsultaarreglo("select c.* from  \"SYSTEM\".catalogo c  where c.codigo='condic'"); 
                        
                        $consulta_datos="select * from \"SYSTEM\".obtener_valor_porcatalogo('condic' ) where estado=1;"; 

                        $datos = $insrol->Ejecutar($consulta_datos); 
                        echo '<label>'.$catalogo[0]['nombre'].'</label><br>';

                        echo ' <select name="cmb_condicion" class="form-select" id="select_condicion" required >';
                            echo '<option value="">Seleccione un valor </option>';
                            while($campos_caja=$datos->fetch()){
                            if($campos_caja['estado']==1){
                            echo '<option value="'.$campos_caja['id_catalogovalor'].'">'.$campos_caja['nombre'].'</option>';
                            }


                            }
                            ?>
                        </select>
                    </div>
                </div>

            </div>


        </div>
        <p class="has-text-centered">
            <button name="resetvalidacion" type="reset" class="button is-link is-light is-rounded"><i
                    class="fas fa-paint-roller"></i> &nbsp;
                Limpiar</button>
            <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Guardar</button>
        </p>
        <p class="has-text-centered pt-6">
            <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
        </p>
    </form>

    <form name="formparametros" class="FormularioAjax"
        action="<?php echo APP_URL; ?>ajax/validacionAjax.php?modulo_Opcion=registrarparametro" method="POST"
        autocomplete="off" enctype="multipart/form-data" style="display:none">
        <p class="has-text-right pt-1 pb-1">
            <button name="regresarparametro" type="reset" class="button is-link is-light is-rounded"><i
                    class="fas fa-arrow-alt-circle-left"></i> &nbsp; Regresar</button>
        </p>
        <input type="hidden" name="idformulario" value="">
        <input type="hidden" name="idvalidacionparametro" value="">
        <div class="col-sm-12 col-md-12">
            <div class="columns">
                <div class="column">
                    <div class="control ">
                        <label>Archivo fuente<?php echo CAMPO_OBLIGATORIO; ?></label><br>

                        <select name="cmb_archivofuente" class="form-select" id="select_archivo" required>
                            <option value="">Seleccione un valor </option>
                        </select>
                    </div>
                </div>
                <div class="column">
                    <div class="control ">
                        <label>Variable<?php echo CAMPO_OBLIGATORIO; ?></label><br>

                        <select name="cmb_variable" class="form-select" id="select_variable" required>
                            <option value="">Seleccione un valor </option>
                        </select>
                    </div>
                </div>
                <div class="column">
                    <div class="control ">

                        <?php
                             $catalogo =$insrol->ejecutarconsultaarreglo("select c.* from  \"SYSTEM\".catalogo c  where c.codigo='param'"); 
                        
                        $consulta_datos="select * from \"SYSTEM\".obtener_valor_porcatalogo('param' ) where estado=1;"; 

                        $datos = $insrol->Ejecutar($consulta_datos); 
                        echo '<label>'.$catalogo[0]['nombre'].' '.CAMPO_OBLIGATORIO.'</label><br>';

                        echo ' <select name="cmb_parametro" class="form-select" id="select_parametro" required >';
                            echo '<option value="">Seleccione un valor </option>';
                            while($campos_caja=$datos->fetch()){
                            if($campos_caja['estado']==1){
                            echo '<option value="'.$campos_caja['id_catalogovalor'].'">'.$campos_caja['nombre'].'</option>';
                            }


                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="column">
                    <div class="control ">

                        <?php
                             $catalogo =$insrol->ejecutarconsultaarreglo("select c.* from  \"SYSTEM\".catalogo c  where c.codigo='tipvalor'"); 
                        
                        $consulta_datos="select * from \"SYSTEM\".obtener_valor_porcatalogo('tipvalor' ) where estado=1;"; 

                        $datos = $insrol->Ejecutar($consulta_datos); 
                        echo '<label>'.$catalogo[0]['nombre'].' '.CAMPO_OBLIGATORIO.'</label><br>';

                        echo ' <select name="cmb_valorparam" class="form-select" id="select_valorparam" required >';
                            echo '<option value="">Seleccione un valor </option>';
                            while($campos_caja=$datos->fetch()){
                            if($campos_caja['estado']==1){
                            echo '<option value="'.$campos_caja['id_catalogovalor'].'">'.$campos_caja['nombre'].'</option>';
                            }


                            }
                            ?>
                        </select>
                    </div>
                </div>

            </div>


        </div>

        <p class="has-text-centered">
            <button name="resetparametro" type="reset" class="button is-link is-light is-rounded"><i
                    class="fas fa-paint-roller"></i> &nbsp;
                Limpiar</button>
            <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Guardar</button>
        </p>
    </form>


    <div name="gridcat">



        <div class="col-sm-12 col-md-12">
            <div class="columns">
                <div class="col-sm-12 col-md-6">
                    <div class="control ">
                        <a name="agregarcat" href="#" class="button is-link is-rounded btn-back"><i
                                class="fas fa-plus"></i> &nbsp;
                            Agregar registro</a>

                    </div>
                </div>
                <div class="col-md-6.5">
                    <div class="control ">
                        <form method="post" action="#" enctype="multipart/form-data">

                            <input class="fileexcel" required type="file" id="fileUpload" name="file"
                                accept=".xls,.xlsx">


                            <div class="mt-1" style="width: 25%; float: right;;"><button type="submit" id="uploadExcel"
                                    class="button is-link is-rounded btn-back" name="submit"><i
                                        class="far fa-file-excel"></i>
                                    &nbsp;Procesar excel</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <table id="myTable" class="table table-striped table-bordered">

        </table>

        <br>
        <form name="cambionombre" class="FormularioAjax " action="<?php echo APP_URL; ?>ajax/validacionAjax.php"
            method="POST" autocomplete="off" enctype="multipart/form-data">
            <input type="hidden" name="hdf_cambiornombre" value="cambio">
            <input type="hidden" name="hdf_seleccionados" value="">
            <div class="col-sm-12 col-md-12">
                <div class="columns ">
                    <div class="control " style="    width: 18%;">
                        <label>Reemplazar nombre validación</label><br>
                    </div>
                    <div class="control " style="    width: 20%;">
                        <input class="input" type="text" name="nombre" required>
                    </div>
                    <div class="control " style="    width: 5%;">
                        <label>Por</label><br>
                    </div>
                    <div class="control " style="    width: 20%;">
                        <input class="input" type="text" name="nombrenuevo" required>
                    </div>
                    <div class="control ">
                        <button name="btnreemplazarnombre" type="submit" class="button is-info is-rounded"><i
                                class="far fa-save"></i> &nbsp;
                            Reemplazar</button>
                    </div>

                </div>



                <p class="has-text-centered">

                </p>
            </div>

        </form>

    </div>
    <div name="gridparametro">
        <table id="myTableparametros" class="table table-striped table-bordered">

        </table>
    </div>

    <div name="gridparametroerror">

        <p class="has-text-right pt-1 pb-1">
            <button name="regresarparametroerror" type="reset" class="button is-link is-light is-rounded"><i
                    class="fas fa-arrow-alt-circle-left"></i> &nbsp; Regresar</button>
        </p>
        <table id="myTableparametroserror" class="table table-striped table-bordered">

        </table>
    </div>





</div>

<script>
const gridcat = document.getElementsByName("gridcat");
const gridparametro = document.getElementsByName("gridparametro");
const gridparametroerror = document.getElementsByName("gridparametroerror");

const button = document.getElementsByName("agregarcat");
const regresar = document.getElementsByName("regresar");
const resetvalidacion = document.getElementsByName("resetvalidacion");
const resetparametro = document.getElementsByName("resetparametro");
const regresarparametro = document.getElementsByName("regresarparametro");
const regresarparametroerror = document.getElementsByName("regresarparametroerror");
const formvalidacion = document.getElementsByName("formvalidacion");
const formparametros = document.getElementsByName("formparametros");
const idvalidacion = document.getElementsByName("idvalidacion");
const idvalidacionparametro = document.getElementsByName("idvalidacionparametro");

const idformulario = document.getElementsByName("idformulario");
const seleccionados = document.getElementsByName("hdf_seleccionados");

const btnreemplazarnombre = document.getElementsByName("btnreemplazarnombre");
btnreemplazarnombre[0].addEventListener("click", (event) => {

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
                dato = value.id_validacion;
            } else {
                dato = dato + "," + value.id_validacion
            }
            seleccionados[0].value = dato;
        });
    }

});




gridcat[0].style.display = "none";
gridparametro[0].style.display = "none";
gridparametroerror[0].style.display = "none";
$("#titulo")[0].innerText = "Lista de validaciones";
//proceso para cargar validaciones desde archivo excel
var selectedFile;
document
    .getElementById("fileUpload")
    .addEventListener("change", function(event) {
        selectedFile = event.target.files[0];
    });
document
    .getElementById("uploadExcel")
    .addEventListener("click", function(e) {
        if (selectedFile) {
            event.preventDefault();
            $(".loadersacn")[0].style.display = "";
            var fileReader = new FileReader();
            fileReader.onload = function(event) {
                var data = event.target.result;

                var workbook = XLSX.read(data, {
                    type: "binary"
                });

                let validaciones = XLSX.utils.sheet_to_row_object_array(
                    workbook.Sheets["validaciones"]
                );

                if (validaciones.length == 0) {
                    $(".loadersacn").fadeOut("slow");
                    Swal.fire({
                        icon: "warning",
                        title: "Carga",
                        text: "El archivo Excel no es el correcto, por favor validar.",
                        confirmButtonText: 'Aceptar'
                    });
                } else {


                    let jsonvalidaciones = JSON.stringify(validaciones);

                    let parametros = XLSX.utils.sheet_to_row_object_array(
                        workbook.Sheets["parametros"]
                    );
                    let jsonparametros = JSON.stringify(parametros);

                    //limpiamos el file
                    document.getElementById("fileUpload").value = "";
                    selectedFile = null;
                    //enviamos los datos al server para ser procesados y guardar en la base de datos
                    $.ajax({
                        type: "POST",
                        url: "<?php  echo APP_URL.'ajax/validacionAjax.php' ?>",
                        data: "guardarcarga=excel&validaciones=" + jsonvalidaciones + "&parametros=" +
                            jsonparametros,
                        success: function(response) {
                            $(".loadersacn").fadeOut("slow");
                            var res = jQuery.parseJSON(response);

                            if (res.status == 200) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Carga",
                                    text: "Carga realizada con éxito",
                                    confirmButtonText: 'Aceptar'
                                });

                                cargargrid();


                            } else {

                                if (res.status == 500) {
                                    Swal.fire({
                                        icon: "warning",
                                        title: "Carga",
                                        text: "Carga con error en parametros, por favor validar",
                                        confirmButtonText: 'Aceptar'
                                    });

                                    cargargridparametroserror(res.data);

                                }

                                if (res.status == 400) {

                                }


                            }


                        }
                    });
                }

            };
            fileReader.readAsBinaryString(selectedFile);
        }
    });

function cargarformularioparametro(alerta) {
    
    var array = JSON.stringify(alerta);
    array = JSON.parse(array);

    if (array.cargargrid == "0") {
        $("#titulo")[0].innerText = "Agregar parametros para validación => " + $("#nombrevalidacion")[0].value;

        formparametros[0].style.display = "";
        formvalidacion[0].style.display = "none";
        idvalidacion[0].value = array.idvalidacion;
        idvalidacionparametro[0].value = array.idvalidacion;
        document.getElementsByName("cambionombre")[0].reset();

        $("#select_variable").prop("selectedIndex", 0);
        $('#select_variable').change();
        $("#select_valorparam").prop("selectedIndex", 0);
        $('#select_valorparam').change();
        $("#select_parametro").prop("selectedIndex", 0);
        $('#select_parametro').change();
        $("#select_archivo").prop("selectedIndex", 0);
        $('#select_archivo').change();
        cargargridparametros(idvalidacion[0].value);
        gridparametro[0].style.display = "";
    } else {
        cargargrid();
        gridparametro[0].style.display = "none";
        document.getElementsByName("cambionombre")[0].reset();
    }
}

$(document).ready(function() {

    limpiarcache();
    $('.form-select').select2();
    //para dejar sin seleccion el combo
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();
    gridparametro[0].style.display = "none";

});

$(document).on('click', '#modificar', function(e) {

    event.preventDefault();
    $("#titulo")[0].innerText = "Modificar validación";
    var row = e.currentTarget.attributes['valor'].value;
    var dato = $("#myTable").DataTable().data()[row];
    gridcat[0].style.display = "none";
    formvalidacion[0].style.display = "";
    formparametros[0].style.display = "none";

    idvalidacion[0].value = dato.id_validacion;
    $("#select_form").val(dato.id_formulario);
    $('#select_form').change();
    $("#select_tipvalidacion").val(dato.idv_tipovalidacion);
    $('#select_tipvalidacion').change();
    $("#select_condicion").val(dato.idv_condicion);
    $('#select_condicion').change();


    document.getElementsByName("nombre")[0].value = dato.nombre;



});

button[0].addEventListener("click", (event) => {
    event.preventDefault();
    $("#titulo")[0].innerText = "Nuevo registro de validación";
    gridcat[0].style.display = "none";
    formvalidacion[0].style.display = "";
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();
    idvalidacion[0].value = 0;
    gridparametro[0].style.display = "none";
    document.getElementById("fileUpload").value = "";
});

regresar[0].addEventListener("click", (event) => {
    event.preventDefault();
    gridcat[0].style.display = "";
    formvalidacion[0].style.display = "none";
    document.getElementsByName("formvalidacion")[0].reset();
    $("#titulo")[0].innerText = "Lista de validaciones";
    cargargrid();
    gridparametro[0].style.display = "none";
    document.getElementById("fileUpload").value = "";
});

regresarparametro[0].addEventListener("click", (event) => {
    event.preventDefault();
    gridcat[0].style.display = "";
    formvalidacion[0].style.display = "none";
    formparametros[0].style.display = "none";
    document.getElementsByName("formvalidacion")[0].reset();
    $("#titulo")[0].innerText = "Lista de validaciones";
    cargargrid();
    gridparametro[0].style.display = "none";
    document.getElementById("fileUpload").value = "";
});

regresarparametroerror[0].addEventListener("click", (event) => {
    event.preventDefault();
    gridcat[0].style.display = "";
    formvalidacion[0].style.display = "none";
    formparametros[0].style.display = "none";
    document.getElementsByName("formvalidacion")[0].reset();
    $("#titulo")[0].innerText = "Lista de validaciones";
    cargargrid();
    gridparametro[0].style.display = "none";
    gridparametroerror[0].style.display = "none";
    document.getElementById("fileUpload").value = "";
});

resetvalidacion[0].addEventListener("click", (event) => {
    event.preventDefault();
    document.querySelector(".FormularioAjax").reset();
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();
    document.getElementById("fileUpload").value = "";
});

resetparametro[0].addEventListener("click", (event) => {
    event.preventDefault();

    $("#select_variable").prop("selectedIndex", 0);
    $('#select_variable').change();
    $("#select_valorparam").prop("selectedIndex", 0);
    $('#select_valorparam').change();
    $("#select_parametro").prop("selectedIndex", 0);
    $('#select_parametro').change();
    $("#select_archivo").prop("selectedIndex", 0);
    $('#select_archivo').change();
});

$('#select_form').on('change', function() {
    var selectVal = $("#select_form option:selected").val();
    if (selectVal != "" && selectVal != undefined) {

        idformulario[0].value = $("#select_form option:selected").val();
        $(".loadersacn")[0].style.display = "";
        $.ajax({
            type: "GET",
            url: "<?php  echo APP_URL.'ajax/variableAjax.php' ?>",
            data: "cargarhojas=" + selectVal,
            success: function(response) {

                var res = jQuery.parseJSON(response);
                var $select2 = $('#select_archivo');
                if (res.status == 200) {
                    $("#select_archivo option").remove();
                    $select2.append('<option value="">Seleccione un valor</option>');
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
                    $(".loadersacn").fadeOut("slow");
                }


            }
        });
    }
});

$('#select_archivo').on('change', function() {
    var selectVal = $("#select_archivo option:selected").val();
    var selectValform = idformulario[0].value;
    if (selectVal != "" && selectVal != undefined) {


        $(".loadersacn")[0].style.display = "";
        $.ajax({
            type: "GET",
            url: "<?php  echo APP_URL.'ajax/validacionAjax.php' ?>",
            data: "cargarvariables=" + selectVal + "&formulario=" + selectValform,
            success: function(response) {

                var res = jQuery.parseJSON(response);
                var $select2 = $('#select_variable');
                if (res.status == 200) {
                    $("#select_variable option").remove();
                    $select2.append('<option value="">Seleccione un valor</option>');
                    var longitud2 = res.data.length;
                    for (i = 0; i < longitud2; i++) {
                        $select2.append('<option value=' + res
                            .data[i].id_variable + '>' +
                            res.data[i].nombrevariable + '</option>');
                    }
                    $(".loadersacn").fadeOut("slow");



                } else {
                    $("#select_variable option").remove();
                    $select2.append('<option value="">Seleccione un valor</option>');
                    $(".loadersacn").fadeOut("slow");
                }


            }
        });
    }
});

function cargargridparametros(idvalidacion) {
    gridparametroerror[0].style.display = "none";
    $(".loadersacn")[0].style.display = "";
    $.ajax({
        type: "GET",
        url: "<?php  echo APP_URL.'ajax/validacionAjax.php?cargagridparametro' ?>=" + idvalidacion,
        success: function(response) {
            $(".loadersacn").fadeOut("slow");
            var res = jQuery.parseJSON(response);
            var datos = [];

            if (res.status == 200) {
                datos = res.data;
            }

            $('#myTableparametros').DataTable({
                data: datos,
                language: {
                    "url": "<?php  echo APP_URL?>config/Spanish.json"
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
                        title: 'id_validacion',
                        className: "text-center",
                        data: 'id_validacion',
                        visible: false,
                    },
                    {
                        title: 'id_parametro',
                        className: "text-center",
                        data: 'id_parametro',
                        visible: false,
                    },
                    {
                        title: 'id_validacion',
                        className: "text-center",
                        data: 'id_validacion',
                        visible: false,
                    },
                    {
                        title: 'idv_tipovalor',
                        className: "text-center",
                        data: 'idv_tipovalor',
                        visible: false,
                    },
                    {
                        width: "30%",
                        title: 'Nombre Archivo',
                        data: 'nombrearchivo',

                    },
                    {
                        width: "30%",
                        title: 'Variable',
                        data: 'nombrevariable',

                    },
                    {
                        width: "30%",
                        title: 'Parámetro',
                        data: 'tipoparametro',

                    },
                    {
                        width: "30%",
                        title: 'Valor',
                        data: 'tipovalor',


                    },
                    {
                        className: "text-center",
                        title: 'Acción',
                        data: 'id_parametro',
                        render: function(data, type, row, meta) {

                            return '<td><form class="FormularioAjax" action="<?php  echo APP_URL?>ajax/validacionAjax.php" method="POST" autocomplete="off" >' +
                                '<input type="hidden" name="accioneliminar" value="eliminar">' +
                                '<input type="hidden" name="id_parametro" value="' +
                                data + '">' +
                                '<input type="hidden" name="id_validacion" value="' +
                                row.id_validacion + '">' +
                                '<button type="submit" title="Eliminar" class="button is-acciones is-rounded is-small">' +
                                '<i class="fas fa-times-circle"></i>' +
                                '</button>' +
                                '</form></td>';
                            cargareventos();

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

function cargargridparametroserror(datos) {
    gridparametroerror[0].style.display = "";
    gridcat[0].style.display = "none";
    gridparametro[0].style.display = "none";
    $("#titulo")[0].innerText = "Lista de variables que no se encuentra información.";
    $('#myTableparametroserror').DataTable({
        buttons: [
            'copy', 'excel', 'pdf'
        ],
        layout: {
            topStart: 'buttons'
        },
        data: datos,
        language: {
            "url": "<?php  echo APP_URL?>config/Spanish.json"
        },
        //searching: false, 
        destroy: true,
        responsive: true,
        columns: [{
                width: "30%",
                title: 'Nombre validación',
                data: 'validacion',

            },
            {
                width: "30%",
                title: 'Nombre archivo',
                data: 'archivo',

            },
            {
                width: "30%",
                title: 'Nombre variable',
                data: 'variable',


            },

        ],
        scrollCollapse: true,
        scrollX: false,
        scrollY: 400,
    });
}

function cargargrid() {
    $(".loadersacn")[0].style.display = "";
    $("#titulo")[0].innerText = "Lista de validaciones";

    gridcat[0].style.display = "";
    formvalidacion[0].style.display = "none";
    gridparametroerror[0].style.display = "none";
    document.getElementsByName("formvalidacion")[0].reset();
    $.ajax({
        type: "GET",
        url: "<?php  echo APP_URL.'ajax/validacionAjax.php?cargagrid' ?>",
        success: function(response) {
            $(".loadersacn").fadeOut("slow");
            var res = jQuery.parseJSON(response);
            var datos = [];

            if (res.status == 200) {
                datos = res.data;
            }

            $('#myTable').DataTable({
                data: datos,
                language: {
                    "url": "<?php  echo APP_URL?>config/Spanish.json"
                },
                stateSave: true,
                //searching: false, 
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
                    },
                    {
                        title: 'id_validacion',
                        className: "text-center",
                        data: 'id_validacion',
                        visible: false,
                    },
                    {
                        title: 'id_formulario',
                        className: "text-center",
                        data: 'id_formulario',
                        visible: false,
                    },
                    {
                        title: 'idv_tipovalidacion',
                        className: "text-center",
                        data: 'idv_tipovalidacion',
                        visible: false,
                    },
                    {
                        title: 'idv_condicion',
                        className: "text-center",
                        data: 'idv_condicion',
                        visible: false,
                    },
                    {
                        width: "30%",
                        title: 'Año',
                        data: 'anio',

                    },
                    {
                        width: "30%",
                        title: 'Nombre formulario',
                        data: 'formulario',

                    },
                    {
                        width: "30%",
                        title: 'Nombre validación',
                        data: 'nombre',

                    },
                    {
                        width: "30%",
                        title: 'Condición',
                        data: 'condicion',
                        className: 'dt-center',

                    },
                    {
                        width: "50%",
                        title: 'Tipo validación',
                        data: 'tipovalidacion',


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
                        data: 'id_validacion',
                        render: function(data, type, row, meta) {
                            return '<td><a id="modificar" title="Editar" href="#" class="button is-info is-rounded is-small" valor="' +
                                meta.row + '">' +
                                '<i class="fas fa-sync fa-fw"></i></a> </td>';
                        }

                    },
                    {
                        className: "text-center",
                        title: 'Acción',
                        data: 'id_validacion',
                        render: function(data, type, row, meta) {

                            if (row.u_estado == 0) {
                                return '<td><form class="FormularioAjax" action="<?php  echo APP_URL?>ajax/validacionAjax.php" method="POST" autocomplete="off" >' +
                                    '<input type="hidden" name="accion" value="activar">' +
                                    '<input type="hidden" name="id_validacion" value="' +
                                    data + '">' +
                                    '<button type="submit" title="Activar" class="button is-acciones is-rounded is-small">' +
                                    '<i class="fas fa-check-circle"></i>' +
                                    '</button>' +
                                    '</form></td>';
                            } else {
                                return '<td><form class="FormularioAjax" action="<?php  echo APP_URL?>ajax/validacionAjax.php" method="POST" autocomplete="off" >' +
                                    '<input type="hidden" name="accion" value="inactivar">' +
                                    '<input type="hidden" name="id_validacion" value="' +
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