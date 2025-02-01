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
    <h1 class="title">Visualizar datos</h1>

    <h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; <label id="titulo"></label></h2>

    <p class="has-text-centered">
    <form name="formvercarga" class="FormularioAjax " action="<?php echo APP_URL; ?>ajax/vercargaAjax.php" method="POST"
        autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" name="modulo_Opcion" value="vercarga">
        <div class="col-sm-12 col-md-12">
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
                        <label>Archivo fuente<?php echo CAMPO_OBLIGATORIO; ?></label><br>

                        <select name="cmb_archivofuente" class="form-select" id="select_archivo" required>
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
                            Cargar</button>
                    </div>
                </div>


            </div>


        </div>
    </form>


    <div name="gridcat">


        <table id="myTable" class="table table-striped table-bordered">

        </table>


    </div>

    <p class="has-text-centered pt-6">
        <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
    </p>


</div>
<script>
const gridcat = document.getElementsByName("gridcat");

$("#titulo")[0].innerText = "Visualizar carga de datos de los archivos asociados al formulario.";
gridcat[0].style.display = "none";

function cargardatos(alerta) {
    var array = JSON.stringify(alerta);
    array = JSON.parse(array);
    if (array.datos != "[]") {
        cargargrid(array.datos);
    }


}




$(document).ready(function() {
    limpiarcache();
    $('.form-select').select2();
    //para dejar sin seleccion el combo
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();

});


$('#select_form').on('change', function() {
    var selectVal = $("#select_form option:selected").val();
    if (selectVal != "" && selectVal != undefined) {


        $(".loadersacn")[0].style.display = "";
        $.ajax({
            type: "GET",
            url: "<?php  echo APP_URL.'ajax/variableAjax.php' ?>",
            data: "cargarhojas=" + selectVal,
            success: function(response) {

                var res = jQuery.parseJSON(response);
                var $select2 = $('#select_archivo');
                if ((res.status == 200) && (res.data2 != undefined)) {


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

function cargargrid(datos) {


    if ($('#myTablevarnuevas_wrapper')[0] != undefined) {
        $('#myTablevarnuevas_wrapper')[0].style.display = "none";

    }

    if ($('#myTable_wrapper')[0] != undefined) {
        $('#myTable_wrapper')[0].style.display = "";
    }

    gridcat[0].style.display = "";
    $('#myTable').DataTable({
        data: datos,
        language: {
            "url": "<?php  echo APP_URL?>config/es-MX.json"
        },
        destroy: true,
        responsive: true,
        columns: [{
                width: "20%",
                title: 'Archivo',
                data: 'nombrearchivo',
            },
            {
                width: "20%",
                title: 'Hoja',
                data: 'nombre',
            },
            {
                width: "20%",
                title: 'Boleta',
                data: 'boleta',
            },
            {
                width: "20%",
                title: 'Variable',
                data: 'nombrevariable',
            },
            {
                width: "20%",
                title: 'Valor',
                data: 'valor',
            },


        ],
        order: [
            [1, 'asc']
        ],
        //paging: false,
        scrollCollapse: true,
        scrollX: false,
        scrollY: 400,
        // select: {
        //     style: 'multi',
        //     selector: 'td:first-child'
        // }
    });

}

function cargargridboletas(datos) {

    if ($('#myTable_wrapper')[0] != undefined) {
        $('#myTable_wrapper')[0].style.display = "none";

    }

    if ($('#myTablevarnuevas_wrapper')[0] != undefined) {
        $('#myTablevarnuevas_wrapper')[0].style.display = "";
    }
    gridcat[0].style.display = "";
    $('#myTablevarnuevas').DataTable({
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
                width: "5%",
            },
            {
                width: "10%",
                title: 'Boleta',
                data: 'BOLETA',
            },
            {
                width: "15%",
                title: 'Archivo',
                data: 'archivo',


            },
            {
                width: "5%",
                title: 'Existe',
                data: 'existe',
            },
            // {
            //     width: "20%",
            //     title: 'Caso',
            //     data: 'CASO',


            // },

            {
                width: "10%",
                title: 'CIIU',
                data: 'CIIU',
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
        }
    });

}
</script>