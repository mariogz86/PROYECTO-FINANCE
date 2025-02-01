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
    <h1 class="title">Clonar configuración</h1>
    <h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp;Formulario</h2>
    <form name="formhoja" class="FormularioAjax "
        action="<?php echo APP_URL; ?>ajax/clonarAjax.php?modulo_Opcion=clonarformulario" method="POST"
        autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" name="anio" value="">
        <div class="col-sm-12 col-md-10">
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

                        <?php
                        
                        $consulta_datos="select * from \"SYSTEM\".obtener_valor_porcatalogo('anio' );"; 

                        $datos = $insrol->Ejecutar($consulta_datos); 
                        $catalogo =$insrol->ejecutarconsultaarreglo("select c.* from  \"SYSTEM\".catalogo c  where c.codigo='anio'"); 
                        echo 'Nuevo '.'<label>'.$catalogo[0]['nombre'].' '. CAMPO_OBLIGATORIO.'</label><br>';

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
                <div class="column">
                    <div class="control">
                        <label>Nombre nuevo formulario <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="text" name="nombre" maxlength="200" required>
                    </div>
                </div>
            </div>



            <p class="has-text-centered">
                <button name="resetparametro" type="reset" class="button is-link is-light is-rounded"><i
                        class="fas fa-paint-roller"></i> &nbsp;
                    Limpiar</button>
                <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp;
                    Guardar</button>
            </p>
            <p class="has-text-centered pt-6">
                <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
            </p>
        </div>

    </form>
</div>
<script>
const resetparametro = document.getElementsByName("resetparametro");
const nombre = document.getElementsByName("nombre");
const anio = document.getElementsByName("anio");
$(document).ready(function() {
    $('.form-select').select2();
    //para dejar sin seleccion el combo
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();

});

resetparametro[0].addEventListener("click", (event) => {
    event.preventDefault();
    cargargrid();

});

$('#select_form').on('change', function() {

    var selectVal = $("#select_form option:selected").val();
    if (selectVal != "") {
        var selectformulario = $("#select_form option:selected")[0].innerHTML;
        
        nombre[0].value = selectformulario.substr(10);
    }

});

$('#select_anio').on('change', function() {

var selectVal = $("#select_anio option:selected").val();
if (selectVal != "") {
    var selectanio = $("#select_anio option:selected")[0].innerHTML;
    
    anio[0].value = selectanio;
}

});




function cargargrid() {
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();
    nombre[0].value = "";
    location.reload();
}
</script>