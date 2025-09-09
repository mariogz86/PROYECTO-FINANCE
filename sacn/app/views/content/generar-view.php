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
// echo ini_get('memory_limit');
// echo ini_get('max_execution_time');
// echo ini_get('post_max_size');
ini_set('max_execution_time', '0'); 
set_time_limit(0);
//ini_set('memory_limit', '1024'); 

?>
    <h1 class="title">Generar Plantilla</h1>

    <h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; <label id="titulo"></label></h2>

    <p class="has-text-centered">
    <form name="formcarga" class="FormularioAjax " action="<?php echo APP_URL; ?>ajax/generarAjax.php" method="POST"
        autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" name="generar" value="generarplantilla">
        <div class="col-sm-12 col-md-12">
            <div class="columns ">
                <div class="column">
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

                <div class="column">
                    <div class="control ">
                        <label>Boletas<?php echo CAMPO_OBLIGATORIO; ?></label><br>

                        <select name="cmb_boleta" class="form-select" id="select_archivo" required>
                            <option value="">Seleccione un valor </option>
                        </select>

                    </div>
                </div>
                <div class="column">
                    <div class="control ">
                        <label> &nbsp;</label><br>
                        <button name="btnmostrar" type="submit" class="button is-info is-rounded"><i
                                class="fas fa-search"></i>
                            &nbsp;
                            Generar</button>
                        <!-- <button name="btnguardartodas" type="submit" class="button is-info is-rounded"><i
                                class="far fa-save"></i>&nbsp;
                            Generar todas las boletas </button> -->
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
                <!-- <div class="column">
                    <div class="control">
                        <label>Nombre archivo <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="text" name="nombre" required>

                    </div>

                </div> -->
            </div>

        </div>
    </form>


    <p class="has-text-centered pt-6">
        <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
    </p>


</div>

<script>
$("#titulo")[0].innerText = "Plantilla para el formulario y boleta.";

//proceso boton generar todas las boletas una a una
//const btnguardar = document.getElementsByName("btnguardartodas");
// btnguardar[0].addEventListener("click", (event) => {
//     var selectVal = $("#select_form option:selected").val();

//     var ultimaboleta = "";

//     if (selectVal != "" && selectVal != undefined) {
//         event.preventDefault();
//         if (document.getElementsByName("ruta")[0].value.trim() == '') {
//             Swal.fire({
//             icon: "warning",
//             title: "Generar Plantilla",
//             text: "Ruta indicada no existe, por favor validar.",
//             confirmButtonText: 'Aceptar'
//         });
//             return;
//         }

//         //var datosboletas = [];
//         $("#select_archivo option").each(function() {
//             ultimaboleta = $(this).attr('value');

//         });

//         $(".loadersacn")[0].style.display = "";
//         //descomentariar cuando se pase esto al servidor
//         // setTimeout(function() {
//         //     cargaformulario();
//         // }, 2400000);






//         $("#select_archivo option").each(function() {
//             boleta = $(this).attr('value');
//             if ((boleta != "0") && (boleta != "")) {


//                 $.when($.ajax({
//                     type: "POST",
//                     url: "<?php  echo APP_URL.'ajax/generarAjax.php' ?>",
//                     data: "modulo_Opcion=generarplantilla&cmb_boleta=" + boleta +
//                         "&cmb_formulario=" + $("#select_form option:selected").val() +
//                         "&ruta=" + document.getElementsByName("ruta")[0].value.trim(),
//                     success: function(response) {

//                         var res = jQuery.parseJSON(response);
//                         if (res.status == 200) {

//                             if(res.boleta==ultimaboleta)
//                             {
//                                 $(".loadersacn").fadeOut("slow");

//                             }

//                         }

//                     }
//                 }));


//             }
//         });




//     }


// });





$(document).ready(function() {
    limpiarcache();
    $('.form-select').select2();
    //para dejar sin seleccion el combo
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();

});


$('#select_form').on('change', function() {
    cargarcomboboletas();

});

function cargarcomboboletas() {
    var selectVal = $("#select_form option:selected").val();
    if (selectVal != "" && selectVal != undefined) {


        $(".loadersacn")[0].style.display = "";
        $.ajax({
            type: "GET",
            url: "<?php  echo APP_URL.'ajax/generarAjax.php' ?>",
            data: "cargarboletas=" + selectVal,
            success: function(response) {

                var res = jQuery.parseJSON(response);
                var $select2 = $('#select_archivo');
                if (res.status == 200) {


                    $("#select_archivo option").remove();
                    $select2.append('<option value="">Seleccione un valor</option>');
                    $select2.append('<option value="0">Todas las boletas</option>');

                    var longitud2 = res.data.length;
                    for (i = 0; i < longitud2; i++) {
                        $select2.append('<option value=' + res
                            .data[i].boleta + '>' +
                            res.data[i].boleta + '</option>');
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
}
</script>