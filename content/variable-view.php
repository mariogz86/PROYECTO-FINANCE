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
    <h1 class="title">Variable</h1>

    <h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; <label id="titulo"></label></h2>

    <p class="has-text-centered">
    <form name="formvariable" class="FormularioAjax " action="<?php echo APP_URL; ?>ajax/variableAjax.php" method="POST"
        autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" name="idhoja" value="">
        <input type="hidden" name="nombrehoja" value="">
        <input type="hidden" name="modulo_Opcion" value="registrar">
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
                        <label>Hoja<?php echo CAMPO_OBLIGATORIO; ?></label><br>

                        <select name="cmb_hoja" class="form-select" id="select_hoja" required>
                            <option value="">Seleccione un valor </option>
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

                        <?php
                             $catalogo =$insrol->ejecutarconsultaarreglo("select c.* from  \"SYSTEM\".catalogo c  where c.codigo='coinvar'"); 
                        
                        $consulta_datos="select * from \"SYSTEM\".obtener_valor_porcatalogo('coinvar' ) where estado=1;"; 

                        $datos = $insrol->Ejecutar($consulta_datos); 
                        echo '<label>'.$catalogo[0]['nombre'].'</label><br>';

                        echo ' <select name="cmb_coincidencia" class="form-select" id="select_coincidencia" >';
                            //echo '<option value="">Seleccione un valor </option>';
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
    </form>


    <div name="gridcat">


        <table id="myTable" class="table table-striped table-bordered">

        </table>

        <table id="myTablevarnuevas" class="table table-striped table-bordered">

        </table>

        <p class="has-text-centered">
            <button name="btnmostrar" type="submit" class="button is-info is-rounded"><i class="fas fa-search"></i>
                &nbsp;
                Cargar variables</button>
            <button name="btnvarnuevas" type="submit" class="button is-info is-rounded"><i class="fas fa-search"></i>
                &nbsp;
                Variables nuevas</button>
            <button name="btnreclasificar" type="submit" class="button is-info is-rounded"><i class="fas fa-search"></i>
                &nbsp;
                Reclasificar variables</button>
            <button name="btnguardar" type="submit" class="button is-info is-rounded"><i class="far fa-save"></i>
                &nbsp;
                Guardar</button>
        </p>


    </div>

    <p class="has-text-centered pt-6">
        <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
    </p>


</div>

<table id="out" style="display:none"></table>

<script>
const btnguardar = document.getElementsByName("btnguardar");
const gridcat = document.getElementsByName("gridcat");
const formvariable = document.getElementsByName("formvariable");
const btnvarnuevas = document.getElementsByName("btnvarnuevas");
const btnreclasificar = document.getElementsByName("btnreclasificar");
const btnmostrar = document.getElementsByName("btnmostrar");

gridcat[0].style.display = "none";
$("#titulo")[0].innerText = "Lista de variables";

$(document).ready(function() {
    btnguardar[0].style.display = "none";
    limpiarcache();
    $('.form-select').select2();
    //para dejar sin seleccion el combo
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();

});

btnguardar[0].addEventListener("click", (event) => {
    event.preventDefault();

    var selectarchivo = $("#select_archivo option:selected").val();
    var selectform = $("#select_form option:selected").val();
    var selecthoja = $("#select_hoja option:selected").val();
    var selectcoincidencia = $("#select_coincidencia option:selected").val();


    if ($('#myTablevarnuevas_wrapper')[0] != undefined) {
        var tabla = new DataTable('#myTablevarnuevas');


        if (tabla.rows({
                selected: true
            }).data().toArray().length == 0) {

            Swal.fire({
                icon: "warning",
                title: "Advertencia",
                text: "Seleccione un registro de la tabla",
                confirmButtonText: 'Aceptar'
            });
        } else {

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Quieres realizar la acción solicitada",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, realizar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    var solicitud = "";
                    if (localStorage.getItem('reclasificar') == '0') {
                        solicitud = "registrar";
                    } else {
                        solicitud = "actualizar";
                    }
                    var dato = JSON.stringify(tabla.rows({
                        selected: true
                    }).data().toArray());
                    $(".loadersacn")[0].style.display = "";
                    $.ajax({
                        type: "POST",
                        url: "<?php  echo APP_URL.'ajax/variableAjax.php' ?>",
                        data: "modulo_Opcion=" + solicitud + "&variables=" + dato +
                            "&formulario=" +
                            selectform + "&hoja=" + selecthoja + "&archivo=" + selectarchivo +
                            "&coincidir=" + selectcoincidencia,
                        success: function(response) {


                            $(".loadersacn").fadeOut("slow");
                            var res = jQuery.parseJSON(response);

                            if (res.status == 200) {
                                btnguardar[0].style.display = "";
                                if (localStorage.getItem('reclasificar') == '0') {
                                    var texto = "Los datos se guardaron con éxito";
                                } else {
                                    var texto = "Los datos se actualizaron con éxito";
                                }

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Variables',
                                    text: texto,
                                    confirmButtonText: 'Aceptar'
                                }).then((result) => {
                                    if (result.isConfirmed) {

                                        cargavariablesnuevas([]);
                                        $.ajax({
                                            type: "GET",
                                            url: "<?php  echo APP_URL.'ajax/variableAjax.php' ?>",
                                            data: "cargagrid=cargar&formulario=" +
                                                selectform + "&hoja=" +
                                                selecthoja + "&archivo=" +
                                                selectarchivo,
                                            success: function(response) {

                                                var res = jQuery
                                                    .parseJSON(
                                                        response);

                                                cargargrid(res.data);

                                            }
                                        });

                                    }
                                });

                            } else {
                                Swal.fire({
                                    icon: "Error",
                                    title: "Error",
                                    text: "No se pudieron guardar los registros.",
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        }
                    });
                }
            });
        }
    }

});


function doSearch(buscar)

{
    var celda = "";
    var coincidencia=parseInt($("#select_coincidencia option:selected")[0].innerHTML);
    var encontrocoincidencia=1;

    const tableReg = document.getElementById('out');

    const searchText = buscar;
    // Recorremos todas las filas con contenido de la tabla

    for (let i = 1; i < tableReg.rows.length; i++) {

        const cellsOfRow = tableReg.rows[i].getElementsByTagName('td');

        // Recorremos todas las celdas

        for (let j = 0; j < cellsOfRow.length; j++) {

            const compareWith = cellsOfRow[j].innerHTML.toUpperCase();

            // Buscamos el texto en el contenido de la celda

            if (searchText.length == compareWith.length && compareWith.indexOf(searchText) > -1) {
                celda = cellsOfRow[j].attributes.id.value.replace("sjs-", "")

                j = cellsOfRow.length;
                if(coincidencia==encontrocoincidencia){
                i = tableReg.rows.length;
                encontrocoincidencia=1;
                }else{
                    encontrocoincidencia=encontrocoincidencia+1;
                }
                

            }

        }
    }
    return celda;
}

btnmostrar[0].addEventListener("click", (event) => {
    var $select2 = $('#select_archivo');
    var selectarchivo = $("#select_archivo option:selected").val();
    cargavariablesnuevas([]);
    if (selectarchivo != "" && selectarchivo != undefined) {
        var selectform = $("#select_form option:selected").val();
        var selecthoja = $("#select_hoja option:selected").val();
        if (selecthoja != "" && selecthoja != undefined) {

            $(".loadersacn")[0].style.display = "";
            $.ajax({
                type: "GET",
                url: "<?php  echo APP_URL.'ajax/variableAjax.php' ?>",
                data: "cargagrid=cargar&formulario=" +
                    selectform + "&hoja=" +
                    selecthoja + "&archivo=" +
                    selectarchivo,
                success: function(response) {

                    var res = jQuery.parseJSON(response);

                    $(".loadersacn").fadeOut("slow");

                    if (res.status == 200) {
                        
                        btnguardar[0].style.display = "none";
                        cargargrid(res.data);
                        $("#select_coincidencia").val(res.coincidencia);
                        $('#select_coincidencia').change();
                    } else {
                        cargargrid([]);
                        Swal.fire({
                            icon: "warning",
                            title: "Advertencia",
                            text: "No existen variables para los datos seleccinados",
                            confirmButtonText: 'Aceptar'
                        });



                    }

                }
            });
        } else {
            $('#select_archivo').prop("selectedIndex", 0);
            $('#select_archivo').change();

            Swal.fire({
                icon: "warning",
                title: "Advertencia",
                text: "Seleccione un registro de la lista de hojas",
                confirmButtonText: 'Aceptar'
            });
        }

    }
});

btnvarnuevas[0].addEventListener("click", (event) => {
    event.preventDefault();
    limpiarcache();
    //gridcat[0].style.display = "none";
    var $select2 = $('#select_archivo');
    var selectarchivo = $("#select_archivo option:selected").val();
    if (selectarchivo != "" && selectarchivo != undefined) {
        var selectform = $("#select_form option:selected").val();
        var selecthoja = $("#select_hoja option:selected").val();
        if (selecthoja != "" && selecthoja != undefined) {

            $(".loadersacn")[0].style.display = "";
            $.ajax({
                type: "GET",
                url: "<?php  echo APP_URL.'ajax/variableAjax.php' ?>",
                data: "varnuevas=cargar&formulario=" +
                    selectform + "&hoja=" +
                    selecthoja + "&archivo=" +
                    selectarchivo,
                success: function(response) {

                    var res = jQuery.parseJSON(response);

                    $(".loadersacn").fadeOut("slow");

                    if (res.status == 200) {
                        localStorage.setItem('variables', []);
                        localStorage.setItem('variables', JSON.stringify(res.data));
                        localStorage.setItem('reclasificar', '0');

                        //cargavariablesnuevas(res.data);

                        btnguardar[0].style.display = "";
                        //otro ejemplo
                        $.ajax({
                            type: "GET",
                            url: res.rutaformulario.replace("../", ""),
                            processData: false,
                            dataType: "binary",
                            responseType: "arraybuffer",
                            success: function(ab) {

                                $("#out").html();

                                var wb = XLSX.read(ab);
                                //
                                var ws = wb.Sheets[$("#select_hoja option:selected")[0]
                                    .innerHTML];
                                var html = XLSX.utils.sheet_to_html(ws);
                                $("#out").html(html);

                                var array = localStorage.getItem('variables');
                                array = JSON.parse(array);

                                var ddDatavariablesnuevas = [];

                                for (let j = 0; j < array.length; j++) {

                                    // Creas un nuevo objeto.
                                    var objeto = {
                                        id: '0',
                                        variable: array[j].nombrevariable,
                                        posicion: doSearch(array[j].nombrevariable),
                                    }
                                    //Lo agregas al array.
                                    ddDatavariablesnuevas.push(objeto);
                                }

                                cargavariablesnuevas(ddDatavariablesnuevas);
                            }
                        });
                        //fin
                    } else {
                        cargargrid([]);
                        Swal.fire({
                            icon: res.icono,
                            title: res.titulo,
                            text: res.texto,
                            confirmButtonText: 'Aceptar'
                        });



                    }

                }
            });
        } else {
            $('#select_archivo').prop("selectedIndex", 0);
            $('#select_archivo').change();

            Swal.fire({
                icon: "warning",
                title: "Advertencia",
                text: "Seleccione un registro de la lista de hojas",
                confirmButtonText: 'Aceptar'
            });
        }

    } else {
        Swal.fire({
            icon: "warning",
            title: "Advertencia",
            text: "Seleccione un registro de la lista de archivos",
            confirmButtonText: 'Aceptar'
        });
    }


});

btnreclasificar[0].addEventListener("click", (event) => {
    event.preventDefault();
    limpiarcache();
    //gridcat[0].style.display = "none";
    var $select2 = $('#select_archivo');
    var selectarchivo = $("#select_archivo option:selected").val();
    if (selectarchivo != "" && selectarchivo != undefined) {
        var selectform = $("#select_form option:selected").val();
        var selecthoja = $("#select_hoja option:selected").val();
        if (selecthoja != "" && selecthoja != undefined) {

            $(".loadersacn")[0].style.display = "";
            $.ajax({
                type: "GET",
                url: "<?php  echo APP_URL.'ajax/variableAjax.php' ?>",
                data: "reclasificar=cargar&formulario=" +
                    selectform + "&hoja=" +
                    selecthoja + "&archivo=" +
                    selectarchivo,
                success: function(response) {

                    var res = jQuery.parseJSON(response);

                    $(".loadersacn").fadeOut("slow");

                    if (res.status == 200) {
                        localStorage.setItem('variables', []);
                        localStorage.setItem('variables', JSON.stringify(res.data));
                        localStorage.setItem('reclasificar', '1');

                        //cargavariablesnuevas(res.data);

                        btnguardar[0].style.display = "";
                        //otro ejemplo
                        $.ajax({
                            type: "GET",
                            url: res.rutaformulario.replace("../", ""),
                            processData: false,
                            dataType: "binary",
                            responseType: "arraybuffer",
                            success: function(ab) {

                                $("#out").html();

                                var wb = XLSX.read(ab);
                                //
                                var ws = wb.Sheets[$("#select_hoja option:selected")[0]
                                    .innerHTML];
                                var html = XLSX.utils.sheet_to_html(ws);
                                $("#out").html(html);

                                var array = localStorage.getItem('variables');
                                array = JSON.parse(array);

                                var ddDatavariablesnuevas = [];

                                for (let j = 0; j < array.length; j++) {


                                    // Creas un nuevo objeto.
                                    var objeto = {
                                        id: array[j].id_variable,
                                        variable: array[j].nombrevariable,
                                        posicion: doSearch(array[j].nombrevariable),
                                    }
                                    //Lo agregas al array.
                                    ddDatavariablesnuevas.push(objeto);
                                }

                                cargavariablesnuevas(ddDatavariablesnuevas);
                            }
                        });
                        //fin
                    } else {
                        cargargrid([]);
                        Swal.fire({
                            icon: res.icono,
                            title: res.titulo,
                            text: res.texto,
                            confirmButtonText: 'Aceptar'
                        });



                    }

                }
            });
        } else {
            $('#select_archivo').prop("selectedIndex", 0);
            $('#select_archivo').change();

            Swal.fire({
                icon: "warning",
                title: "Advertencia",
                text: "Seleccione un registro de la lista de hojas",
                confirmButtonText: 'Aceptar'
            });
        }

    } else {
        Swal.fire({
            icon: "warning",
            title: "Advertencia",
            text: "Seleccione un registro de la lista de archivos",
            confirmButtonText: 'Aceptar'
        });
    }
});

$('#select_archivo').on('change', function() {
    var $select2 = $('#select_archivo');
    var selectarchivo = $("#select_archivo option:selected").val();
    $('#select_coincidencia').prop("selectedIndex", 0);
    $('#select_coincidencia').change();
    if (selectarchivo != "" && selectarchivo != undefined) {
        var selectform = $("#select_form option:selected").val();
        var selecthoja = $("#select_hoja option:selected").val();
        if (selecthoja != "" && selecthoja != undefined) {

            $(".loadersacn")[0].style.display = "";
            $.ajax({
                type: "GET",
                url: "<?php  echo APP_URL.'ajax/variableAjax.php' ?>",
                data: "cargagrid=cargar&formulario=" +
                    selectform + "&hoja=" +
                    selecthoja + "&archivo=" +
                    selectarchivo,
                success: function(response) {

                    var res = jQuery.parseJSON(response);

                    $(".loadersacn").fadeOut("slow");

                    if (res.status == 200) {

                        btnguardar[0].style.display = "none";
                        cargargrid(res.data);
                        $("#select_coincidencia").val(res.coincidencia);
                        $('#select_coincidencia').change();
                    } else {
                        cargargrid([]);
                        Swal.fire({
                            icon: "warning",
                            title: "Advertencia",
                            text: "No existen variables para los datos seleccinados",
                            confirmButtonText: 'Aceptar'
                        });



                    }

                }
            });
        } else {
            $('#select_archivo').prop("selectedIndex", 0);
            $('#select_archivo').change();

            Swal.fire({
                icon: "warning",
                title: "Advertencia",
                text: "Seleccione un registro de la lista de hojas",
                confirmButtonText: 'Aceptar'
            });
        }

    }
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
                var $select = $('#select_hoja');
                var $select2 = $('#select_archivo');
                if (res.status == 200) {

                    $("#select_hoja option").remove();
                    $select.append('<option value="">Seleccione un valor</option>');

                    $("#select_archivo option").remove();
                    $select2.append('<option value="">Seleccione un valor</option>');

                    var longitud = res.data.length;
                    for (i = 0; i < longitud; i++) {
                        $select.append('<option value=' + res
                            .data[i].id_hoja + '>' +
                            res.data[i].nombre + '</option>');
                    }

                    var longitud2 = res.data2.length;
                    for (i = 0; i < longitud2; i++) {
                        $select2.append('<option value=' + res
                            .data2[i].id_archivofuente + '>' +
                            res.data2[i].nombrearchivo + '</option>');
                    }




                    $(".loadersacn").fadeOut("slow");



                } else {
                    $("#select_hoja option").remove();
                    $select.append('<option value="">Seleccione un valor</option>');

                    $("#select_archivo option").remove();
                    $select2.append('<option value="">Seleccione un valor</option>');
                    $(".loadersacn").fadeOut("slow");
                }


            }
        });
    }
});


function cargavariablesnuevas(datos) {


    columnas = [{
            className: "text-center",
            data: null,
            width: "20%",
        },
        {
            title: 'id_variable',
            className: "text-center",
            data: 'id',
            visible: false,
        },
        {
            title: 'Nombre variable',
            data: 'variable',
            width: "20%",

        },
        {
            title: 'Posición',
            data: 'posicion',
            width: "20%",

        }

    ];


    if ($('#myTable_wrapper')[0] != undefined) {
        $('#myTable_wrapper')[0].style.display = "none";
    }
    //incio tabla archivos
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
        columns: columnas,
        order: [
            [1, 'desc']
        ],
        //paging: false,
        scrollCollapse: true,
        scrollX: false,
        scrollY: 300,
        select: {
            style: 'multi',
            selector: 'td:first-child'
        }
    });
}


function cargargrid(datos) {
    if ($('#myTablevarnuevas_wrapper')[0] != undefined) {
        $('#myTablevarnuevas_wrapper')[0].style.display = "none";
    }

    $("#titulo")[0].innerText = "Lista de variables";

    gridcat[0].style.display = "";



    $('#myTable').DataTable({
        data: datos,
        language: {
            "url": "<?php  echo APP_URL?>config/es-MX.json"
        },
        destroy: true,
        responsive: true,
        columns: [{
                title: 'id_variable',
                className: "text-center",
                data: 'id_variable',
                visible: false,
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
                title: 'id_archivofuente',
                className: "text-center",
                data: 'id_archivofuente',
                visible: false,
            },
            {
                width: "20%",
                title: 'Variable',
                data: 'nombrevariable',


            },
            {
                width: "20%",
                title: 'Posición',
                data: 'posicion',


            },
            {
                width: "20%",
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
                title: 'Acción',
                data: 'id_variable',
                render: function(data, type, row, meta) {

                    if (row.u_estado == 0) {
                        return '<td><form class="FormularioAjax" action="<?php  echo APP_URL?>ajax/variableAjax.php" method="POST" autocomplete="off" >' +
                            '<input type="hidden" name="modulo_variable" value="activar">' +
                            '<input type="hidden" name="id_variable" value="' +
                            data +
                            '">' +
                            '<button type="submit" title="Activar" class="button is-acciones is-rounded is-small">' +
                            '<i class="fas fa-check-circle"></i>' +
                            '</button>' +
                            '</form></td>';
                    } else {
                        return '<td><form class="FormularioAjax" action="<?php  echo APP_URL?>ajax/variableAjax.php" method="POST" autocomplete="off" >' +
                            '<input type="hidden" name="modulo_variable" value="inactivar">' +
                            '<input type="hidden" name="id_variable" value="' +
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
        //paging: false,
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
//cargargrid();

function cargargrid_post() {
    var $select2 = $('#select_archivo');
    var selectarchivo = $("#select_archivo option:selected").val();
    if (selectarchivo != "" && selectarchivo != undefined) {
        var selectform = $("#select_form option:selected").val();
        var selecthoja = $("#select_hoja option:selected").val();
        if (selecthoja != "" && selecthoja != undefined) {

            $(".loadersacn")[0].style.display = "";
            $.ajax({
                type: "GET",
                url: "<?php  echo APP_URL.'ajax/variableAjax.php' ?>",
                data: "cargagrid=cargar&formulario=" +
                    selectform + "&hoja=" +
                    selecthoja + "&archivo=" +
                    selectarchivo,
                success: function(response) {

                    var res = jQuery.parseJSON(response);

                    $(".loadersacn").fadeOut("slow");

                    if (res.status == 200) {

                        cargargrid(res.data);
                        $("#select_coincidencia").val(res.coincidencia);
                        $('#select_coincidencia').change();
                    } else {
                        cargargrid([]);
                        Swal.fire({
                            icon: "warning",
                            title: "Advertencia",
                            text: "No existen variables para los datos seleccinados",
                            confirmButtonText: 'Aceptar'
                        });



                    }

                }
            });
        } else {
            $('#select_archivo').prop("selectedIndex", 0);
            $('#select_archivo').change();

            Swal.fire({
                icon: "warning",
                title: "Advertencia",
                text: "Seleccione un registro de la lista de hojas",
                confirmButtonText: 'Aceptar'
            });
        }

    }
}
</script>