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
    <h1 class="title">Hoja de formulario</h1>

    <h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; <label id="titulo"></label></h2>

    <div name="gridcat">


        <p class="has-text-left pt-4 pb-4">
            <a name="agregarcat" href="#" class="button is-link is-rounded btn-back"><i class="fas fa-plus"></i> &nbsp;
                Agregar registro</a>
        </p>
        <table id="myTable" class="table table-striped table-bordered">

        </table>

    </div>


    <p class="has-text-centered">
    <form name="formhoja" class="FormularioAjax " action="<?php echo APP_URL; ?>ajax/hojaAjax.php" method="POST"
        autocomplete="off" enctype="multipart/form-data" style="display:none">
        <input type="hidden" name="idhoja" value="">
        <input type="hidden" name="nombrehoja" value="">
        <p class="has-text-right pt-4 pb-4">
            <button name="regresar" type="reset" class="button is-link is-light is-rounded"><i
                    class="fas fa-arrow-alt-circle-left"></i> &nbsp; Regresar</button>
        </p>
        <input type="hidden" name="modulo_Opcion" value="registrar">
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
                        <label>Hoja</label><br>

                        <select name="cmb_hoja" class="form-select" id="select_hoja" required>
                            <option value="0">Seleccione un valor </option>
                        </select>
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

<table id="out" style="display:none"></table>

<script>
//JSON.stringify(tabla.rows( { selected: true } ).data().toArray());

const button = document.getElementsByName("agregarcat");
const idhoja = document.getElementsByName("idhoja");
const nombrehoja = document.getElementsByName("nombrehoja");



const regresar = document.getElementsByName("regresar");
const formhoja = document.getElementsByName("formhoja");
const gridcat = document.getElementsByName("gridcat");


$(document).ready(function() {
    $('.form-select').select2();
    //para dejar sin seleccion el combo
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();

});




// $('#select_form').on('change', function() {
//     var selectVal = $("#select_form option:selected").val();
//     if (selectVal != "" && selectVal != undefined) {
//         limpiarcache();

//         $(".loadersacn")[0].style.display = "";
//         $.ajax({
//             type: "GET",
//             url: "<?php  echo APP_URL.'ajax/hojaAjax.php' ?>",
//             data: "cargarhojas=" + selectVal,
//             success: function(response) {

//                 var res = jQuery.parseJSON(response);
//                 var $select = $('#select_hoja');
//                 if (res.status == 200) {

//                     $("#select_hoja option").remove();
//                     $select.append('<option value="">Seleccione un valor</option>'); 
//                     //var url="archivos/prueba.xlsx";
//                     var url = res.data[0].ruta +"/"+ res.data[0].nombre;




//                     var oReq = new XMLHttpRequest();
//                     oReq.open("GET", url.replace("../", ""), true);
//                     oReq.responseType = "arraybuffer";

//                     oReq.onload = function(e) {
//                         readData();
//                         // console.log(info);
//                         function readData() {
//                             var arraybuffer = oReq.response;
//                             var data = new Uint8Array(arraybuffer);
//                             var arr = new Array();
//                             for (var i = 0; i != data.length; ++i) arr[i] = String.fromCharCode(
//                                 data[i]);
//                             var bstr = arr.join("");

//                             var workbook = XLSX.read(bstr, {
//                                 type: "binary"
//                             });
//                             var first_sheet_name = workbook.SheetNames[2];
//                             var worksheet = workbook.Sheets[first_sheet_name];

//                             var sheet_name_list = workbook.SheetNames;


//                             var longitud = workbook.SheetNames.length;
//                             for (i = 0; i < longitud; i++) {
//                                 $select.append('<option value=' + workbook
//                                     .SheetNames[i] + '>' +
//                                     workbook.SheetNames[i] + '</option>');
//                             }

//                             $(".loadersacn").fadeOut("slow");


//                             if (nombrehoja[0].value != "") {
//                                 $("#select_hoja").val(nombrehoja[0].value);
//                                 $('#select_hoja').change();
//                             }

//                         }
//                     }

//                     oReq.send();



//                 } else {
//                     $("#select_hoja option").remove();
//                     $select.append('<option value="">Seleccione un valor</option>');
//                     $(".loadersacn").fadeOut("slow");
//                 }


//             }
//         });
//     }
// });

$('#select_form').on('change', function() {
    var selectVal = $("#select_form option:selected").val();
    if (selectVal != "" && selectVal != undefined) {
        limpiarcache();

        $(".loadersacn")[0].style.display = "";
        $.ajax({
            type: "GET",
            url: "<?php  echo APP_URL.'ajax/hojaAjax.php' ?>",
            data: "cargarhojas=" + selectVal,
            success: function(response) {

                var res = jQuery.parseJSON(response);
                var $select = $('#select_hoja');
                if (res.status == 200) {

                    $("#select_hoja option").remove();
                    $select.append('<option value="">Seleccione un valor</option>');



                    var longitud = res.data.length;
                    for (i = 0; i < longitud; i++) {
                        $select.append('<option value=' + res.data[i] + '>' +
                            res.data[i] + '</option>');
                    }

                    $(".loadersacn").fadeOut("slow");


                    if (nombrehoja[0].value != "") {
                        $("#select_hoja").val(nombrehoja[0].value);
                        $('#select_hoja').change();
                    }

                } else {
                    $("#select_hoja option").remove();
                    $select.append('<option value="">Seleccione un valor</option>');
                    $(".loadersacn").fadeOut("slow");
                }


            }
        });
    }
});




regresar[0].addEventListener("click", (event) => {
    event.preventDefault();
    gridcat[0].style.display = "";
    formhoja[0].style.display = "none";
    nombrehoja[0].value = "";
    document.getElementsByName("formhoja")[0].reset();
    $("#titulo")[0].innerText = "Lista de hoja de formulario";
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();
    $("#select_hoja option").remove();
    $('#select_hoja').change();

    $("#select_form").val(0);
    $('#select_form').change();


});

button[0].addEventListener("click", (event) => {
    event.preventDefault();
    $("#titulo")[0].innerText = "Nueva hoja de formulario";
    gridcat[0].style.display = "none";
    formhoja[0].style.display = "";
    idhoja[0].value = 0;
    nombrehoja[0].value = "";

    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();
    $("#select_hoja option").remove();
});
$(document).on('click', '#modificar', function(e) {

    event.preventDefault();
    $("#titulo")[0].innerText = "Modificar hoja de formulario";
    var row = e.currentTarget.attributes['valor'].value;
    var dato = $("#myTable").DataTable().data()[row];
    gridcat[0].style.display = "none";
    formhoja[0].style.display = "";

    idhoja[0].value = dato.id_hoja;
    nombrehoja[0].value = dato.nombre;



    $("#select_form").val(dato.id_formulario);
    $('#select_form').change();


});




$(document).on('submit', '#formhoja', function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    formData.append("save_formulario", true);

    $.ajax({
        type: "POST",
        url: "<?php  echo APP_URL.'ajax/hojaAjax.php' ?>",
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
    $("#titulo")[0].innerText = "Lista de hoja de formulario";

    gridcat[0].style.display = "";
    formhoja[0].style.display = "none";
    document.getElementsByName("formhoja")[0].reset();
    $.ajax({
        type: "GET",
        url: "<?php  echo APP_URL.'ajax/hojaAjax.php?cargagrid' ?>",
        success: function(response) {
            $(".loadersacn").fadeOut("slow");
            var res = jQuery.parseJSON(response);
            var estilo = "";


            $('#myTable').DataTable({
                data: res.data,
                language: {
                    "url": "<?php  echo APP_URL?>config/es-MX.json"
                },
                //searching: false, 
                destroy: true,
                responsive: true,
                // columnDefs: [{
                //     orderable: false,
                //     render: DataTable.render.select(),
                //     targets: 0
                // }, ],
                columns: [{
                        title: 'Año',
                        className: "text-center",
                        data: 'anio',

                    },
                    {
                        title: 'id_hoja',
                        className: "text-center",
                        data: 'id_hoja',
                        visible: false,
                    },
                    {
                        title: 'id_formulario',
                        className: "text-center",
                        data: 'id_formulario',
                        visible: false,
                    },
                    {
                        width: "20%",
                        title: 'Formulario',
                        data: 'nombreform',


                    },
                    {
                        width: "20%",
                        title: 'Hoja',
                        data: 'nombre',


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
                        data: 'id_hoja',
                        render: function(data, type, row, meta) {
                            return '<td><a id="modificar" title="Editar" href="#" class="button is-info is-rounded is-small" valor="' +
                                meta.row + '">' +
                                '<i class="fas fa-sync fa-fw"></i></a> </td>';
                        }

                    },
                    {
                        className: "text-center",
                        title: 'Acción',
                        data: 'id_hoja',
                        render: function(data, type, row, meta) {

                            if (row.u_estado == 0) {
                                return '<td><form class="FormularioAjax" action="<?php  echo APP_URL?>ajax/hojaAjax.php" method="POST" autocomplete="off" >' +
                                    '<input type="hidden" name="modulo_hoja" value="activar">' +
                                    '<input type="hidden" name="id_hoja" value="' +
                                    data +
                                    '">' +
                                    '<button type="submit" title="Activar" class="button is-acciones is-rounded is-small">' +
                                    '<i class="fas fa-check-circle"></i>' +
                                    '</button>' +
                                    '</form></td>';
                            } else {
                                return '<td><form class="FormularioAjax" action="<?php  echo APP_URL?>ajax/hojaAjax.php" method="POST" autocomplete="off" >' +
                                    '<input type="hidden" name="modulo_hoja" value="inactivar">' +
                                    '<input type="hidden" name="id_hoja" value="' +
                                    data +
                                    '">' +
                                    '<button type="submit" title="Inactivar" class="button is-acciones is-rounded is-small">' +
                                    '<i class="fas fa-times-circle"></i>' +
                                    '</button>' +
                                    '</form></td>';
                            }

                        }

                    },
                ],
                order: [
                    [1, 'asc']
                ],
                paging: false,
                scrollCollapse: true,
                scrollX: false,
                scrollY: 400,
                "createdRow": function(row, data, dataIndex) {
                    if (data.u_estado == 0) {
                        $(row).addClass('redClass');
                    }
                },
                // select: {
                //     style: 'multi',
                //     selector: 'td:first-child'
                // }
            });






        }
    });
}
cargargrid();
</script>