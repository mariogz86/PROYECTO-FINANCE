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
    <h1 class="title">Jobs</h1>

    <h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; <label id="titulo"></label></h2>

    <div name="gridcat">


        <p class="has-text-left pt-1 pb-1">
            <a name="agregarcat" href="#" class="button is-link is-rounded btn-back"><i class="fas fa-plus"></i> &nbsp;
                Add record</a>
        </p>
        <table id="myTable" class="table table-striped table-bordered">

        </table>
        <br>


    </div>



    <form name="formCompany" class="FormularioAjax " action="<?php echo APP_URL; ?>ajax/jobAjax.php" method="POST"
        autocomplete="off" enctype="multipart/form-data" style="display:none">
        <input type="hidden" name="idjob" value="">
        <p class="has-text-right pt-1 pb-1">
            <button name="regresar" type="reset" class="button is-link is-light is-rounded"><i
                    class="fas fa-arrow-alt-circle-left"></i> &nbsp; Go back</button>
        </p>
        <input type="hidden" name="modulo_Opcion" value="registrar">
        <div class="col-sm-12 col-md-12">
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Company Name <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <?php
                        $consulta_datos = "select * from \"SYSTEM\".obtener_company where u_estado=1;";

                        $datos = $insrol->Ejecutar($consulta_datos);

                        echo ' <select name="cmb_company" class="form-select" id="select_company" required>';
                        echo '<option value="">Select a value </option>';
                        while ($campos_caja = $datos->fetch()) {
                            echo '<option value="' . $campos_caja['id_company'] . '"> ' . $campos_caja['nombre'] . '
                            </option>';
                        }
                        ?>
                        </select>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Full Name <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="text" name="fullname" required>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>City <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="text" name="city" required>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>ZIP Code <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="number" name="codigozip" required>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Address<?php echo CAMPO_OBLIGATORIO; ?></label>
                        <textarea name="direccion" class="input" style="height: 50px;" required></textarea>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <div class="control ">

                        <?php
                        $catalogo = $insrol->ejecutarconsultaarreglo("select c.* from  \"SYSTEM\".catalogo c  where c.codigo='codsatate' ");

                        $consulta_datos = "select * from \"SYSTEM\".obtener_valor_porcatalogo('codsatate' ) where estado=1;";

                        $datos = $insrol->Ejecutar($consulta_datos);
                        echo '<label>' . $catalogo[0]['nombre'] . ' ' . CAMPO_OBLIGATORIO . '</label><br>';

                        echo ' <select name="cmb_estado" class="form-select" id="select_state" required>';
                        echo '<option value="">Select a value </option>';
                        while ($campos_caja = $datos->fetch()) {
                            if ($campos_caja['estado'] == 1) {
                                echo '<option value="' . $campos_caja['id_catalogovalor'] . '"> ' . $campos_caja['nombre'] . '
                            </option>';
                            }
                        }
                        ?>
                        </select>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Phone <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="text" name="phone" maxlength="70" required>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Mobile Phone</label>
                        <input class="input" type="text" name="telefono" maxlength="70">
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>E-mail</label>
                        <input class="input" type="email" name="email" maxlength="70">
                    </div>
                </div>
            </div>

            <div class="columns">

                <div class="column">
                    <div class="control">
                        <label>Company Name</label>
                        <input class="input" type="text" name="companyname" maxlength="70">
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Contact Info</label>
                        <input class="input" type="text" name="contactinfo" maxlength="70">
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Contact Phone</label>
                        <input class="input" type="text" name="contactphone" maxlength="70">
                    </div>
                </div>
                <div class="column">
                    <div class="control ">

                        <?php 
                        $consulta_datos = "select * from  \"SYSTEM\".usuarios where id_rol in (select id_rol from \"SYSTEM\".roles  where rol='Technical' )and u_estado=1";

                        $datos = $insrol->Ejecutar($consulta_datos);
                        echo '<label> Technical</label><br>';

                        echo ' <select name="cmb_tecnico" class="form-select" id="select_tecnico">';
                        echo '<option value="0">Select a value </option>';
                        while ($campos_caja = $datos->fetch()) { 
                                echo '<option value="' . $campos_caja['id_usuario'] . '"> ' . $campos_caja['u_nombre_completo'] . '
                            </option>'; 
                        }
                        ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Contact E-mail</label>
                        <input class="input" type="text" name="contactmail" maxlength="70">
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>NTE </label>
                        <input class="input" type="text" name="nte" value="0.00" pattern="[0-9.]{1,25}" maxlength="25">
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Customer Deductible Fee </label>
                        <input class="input" type="text" name="fee" value="0.00" pattern="[0-9.]{1,25}" maxlength="25">
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



    <p class="has-text-right pt-1 pb-1">
        <button name="regresar_service" type="reset" class="button is-link is-light is-rounded"><i
                class="fas fa-arrow-alt-circle-left"></i> &nbsp; Go back</button>
    </p>
    <div class="accordion" id="accordionExample">
        <div class="card">
            <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                    <button id="btncollapseOne" class="btn btn-link btn-block text-left" type="button"
                        data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                        aria-controls="collapseOne">
                        Add service information
                    </button>
                </h2>
            </div>

            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">
                    <form name="formservice" class="FormularioAjax2" action="<?php echo APP_URL; ?>ajax/jobAjax.php"
                        method="POST" autocomplete="off" enctype="multipart/form-data" style="display:none">
                        <input type="hidden" name="id_servicio" value="">
                        <input type="hidden" name="idjob_service" value="">
                        <input type="hidden" name="modulo_Opcion_service" value="registrarservicio">

                        <div class="col-sm-12 col-md-12">
                            <div class="columns">
                                <div class="column">
                                    <div class="control ">
                                        <?php
                                        $catalogo = $insrol->ejecutarconsultaarreglo("select c.* from  \"SYSTEM\".catalogo c  where c.codigo='codservice' ");

                                        $consulta_datos = "select * from \"SYSTEM\".obtener_valor_porcatalogo('codservice' ) where estado=1;";

                                        $datos = $insrol->Ejecutar($consulta_datos);
                                        echo '<label>' . $catalogo[0]['nombre'] . ' ' . CAMPO_OBLIGATORIO . '</label><br>';

                                        echo ' <select name="cmb_service" class="form-select" id="select_service" required>';
                                        echo '<option value="">Select a value </option>';
                                        while ($campos_caja = $datos->fetch()) {
                                            echo '<option value="' . $campos_caja['id_catalogovalor'] . '"> ' . $campos_caja['nombre'] . '
                            </option>';
                                        }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="control ">
                                        <?php
                                        $catalogo = $insrol->ejecutarconsultaarreglo("select c.* from  \"SYSTEM\".catalogo c  where c.codigo='codappliance' ");

                                        $consulta_datos = "select * from \"SYSTEM\".obtener_valor_porcatalogo('codappliance' ) where estado=1;";

                                        $datos = $insrol->Ejecutar($consulta_datos);
                                        echo '<label>' . $catalogo[0]['nombre'] . ' ' . CAMPO_OBLIGATORIO . '</label><br>';

                                        echo ' <select name="cmb_appliance" class="form-select" id="select_appliance" required>';
                                        echo '<option value="">Select a value </option>';
                                        while ($campos_caja = $datos->fetch()) {
                                            echo '<option value="' . $campos_caja['id_catalogovalor'] . '"> ' . $campos_caja['nombre'] . '
                            </option>';
                                        }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="control ">
                                        <?php
                                        $catalogo = $insrol->ejecutarconsultaarreglo("select c.* from  \"SYSTEM\".catalogo c  where c.codigo='codbrand' ");

                                        $consulta_datos = "select * from \"SYSTEM\".obtener_valor_porcatalogo('codbrand' ) where estado=1;";

                                        $datos = $insrol->Ejecutar($consulta_datos);
                                        echo '<label>' . $catalogo[0]['nombre'] . ' ' . CAMPO_OBLIGATORIO . '</label><br>';

                                        echo ' <select name="cmb_brand" class="form-select" id="select_brand" required>';
                                        echo '<option value="">Select a value </option>';
                                        while ($campos_caja = $datos->fetch()) {
                                            echo '<option value="' . $campos_caja['id_catalogovalor'] . '"> ' . $campos_caja['nombre'] . '
                            </option>';
                                        }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="control">
                                        <label>Model<?php echo CAMPO_OBLIGATORIO; ?></label>
                                        <input class="input" type="text" name="model" maxlength="200" required>
                                    </div>
                                </div>
                            </div>
                            <div class="columns">
                                <div class="column">
                                    <div class="control ">
                                        <?php
                                        $catalogo = $insrol->ejecutarconsultaarreglo("select c.* from  \"SYSTEM\".catalogo c  where c.codigo='codsymptom' ");

                                        $consulta_datos = "select * from \"SYSTEM\".obtener_valor_porcatalogo('codsymptom' ) where estado=1;";

                                        $datos = $insrol->Ejecutar($consulta_datos);
                                        echo '<label>' . $catalogo[0]['nombre'] . ' ' . CAMPO_OBLIGATORIO . '</label><br>';

                                        echo ' <select name="cmb_symptom" class="form-select" id="select_symptom" required>';
                                        echo '<option value="">Select a value </option>';
                                        while ($campos_caja = $datos->fetch()) {
                                            echo '<option value="' . $campos_caja['id_catalogovalor'] . '"> ' . $campos_caja['nombre'] . '
                            </option>';
                                        }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="control">
                                        <label>Service Fee<?php echo CAMPO_OBLIGATORIO; ?> </label>
                                        <input class="input" type="text" name="servicefee" value="0.00"
                                            pattern="[0-9.]{1,25}" maxlength="25" required>
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="control">
                                        <label>Covered</label>
                                        <input class="input" type="text" name="covered" value="0.00"
                                            pattern="[0-9.]{1,25}" maxlength="25">
                                    </div>
                                </div>
                            </div>
                            <div class="columns">
                                <div class="column">
                                    <div class="control">
                                        <label>Problem Detail<?php echo CAMPO_OBLIGATORIO; ?></label>
                                        <textarea name="problemdetail" class="input" style="height: 100px;"
                                            required></textarea>
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="control">
                                        <p class="has-text-centered">

                                            <button type="reset" class="button is-link is-light is-rounded"><i
                                                    class="fas fa-paint-roller"></i> &nbsp;
                                                Clean</button>
                                            <button type="submit" class="button is-info is-rounded"><i
                                                    class="far fa-save"></i> &nbsp;
                                                Save</button>
                                        </p>
                                        <p class="has-text-centered pt-1">
                                            <small>Fields marked with <?php echo CAMPO_OBLIGATORIO; ?> are
                                                mandatory</small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                </form>
            </div>
        </div>
        <!-- <div class="card">
                <div class="card-header" id="headingTwo">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Collapsible Group Item #2
                        </button>
                    </h2>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                    <div class="card-body">

                    </div>
                </div>
            </div> -->

    </div>



    <div name="gridservicio">
        <table id="myTableservicio" class="table table-striped table-bordered">

        </table>
    </div>



</div>



<script>
//JSON.stringify(tabla.rows( { selected: true } ).data().toArray());

const button = document.getElementsByName("agregarcat");
const idjob = document.getElementsByName("idjob");
const idjob_service = document.getElementsByName("idjob_service");
const id_servicio = document.getElementsByName("id_servicio");

const seleccionados = document.getElementsByName("hdf_seleccionados");

const id_detalleactividad = document.getElementsByName("id_detalleactividad");
const regresar = document.getElementsByName("regresar");
const regresar_service = document.getElementsByName("regresar_service");

const formCompany = document.getElementsByName("formCompany");
const formservice = document.getElementsByName("formservice");

const accordionExample = document.getElementById("accordionExample")


const gridcat = document.getElementsByName("gridcat");
const gridservicio = document.getElementsByName("gridservicio");

function pantallaprincipal() {
    gridcat[0].style.display = "";
    accordionExample.style.display = "none";
    gridservicio[0].style.display = "none";
    formCompany[0].style.display = "none";
    formservice[0].style.display = "none";
    regresar_service[0].style.display = "none";
    document.getElementsByName("formCompany")[0].reset();
    $("#titulo")[0].innerText = "job list";
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();
}

$(document).ready(function() {
    $('.form-select').select2();
    //para dejar sin seleccion el combo
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();
    gridservicio[0].style.display = "none";
    accordionExample.style.display = "none"
    regresar_service[0].style.display = "none";

});


regresar_service[0].addEventListener("click", (event) => {
    event.preventDefault();
    pantallaprincipal();

});

regresar[0].addEventListener("click", (event) => {
    event.preventDefault();
    pantallaprincipal();
});

button[0].addEventListener("click", (event) => {
    event.preventDefault();
    $("#titulo")[0].innerText = "New job";
    gridcat[0].style.display = "none";
    formCompany[0].style.display = "";
    idjob[0].value = 0;
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();
});
$(document).on('click', '#modificar', function(e) {

    event.preventDefault();
    $("#titulo")[0].innerText = "Modify job";
    var row = e.currentTarget.attributes['valor'].value;
    var dato = $("#myTable").DataTable().data()[row];
    gridcat[0].style.display = "none";
    formCompany[0].style.display = "";

    idjob[0].value = dato.id_trabajo;

    document.getElementsByName("fullname")[0].value = dato.full_name;
    document.getElementsByName("city")[0].value = dato.city;
    document.getElementsByName("direccion")[0].value = dato.address;
    document.getElementsByName("codigozip")[0].value = dato.codigozip;
    document.getElementsByName("email")[0].value = dato.email;
    document.getElementsByName("phone")[0].value = dato.phone;
    document.getElementsByName("telefono")[0].value = dato.phone_movil;
    document.getElementsByName("companyname")[0].value = dato.company_name;
    document.getElementsByName("contactinfo")[0].value = dato.contact_info;
    document.getElementsByName("contactphone")[0].value = dato.contact_phone;
    document.getElementsByName("contactmail")[0].value = dato.contact_email;
    document.getElementsByName("nte")[0].value = dato.valor_nte;
    document.getElementsByName("fee")[0].value = dato.customer_fee;

    $("#select_company").val(dato.id_company);
    $('#select_company').change();

    $("#select_state").val(dato.estadocliente);
    $('#select_state').change();

    $("#select_tecnico").val(dato.id_tecnico);
    $('#select_tecnico').change();
});


$(document).on('click', '#servicios', function(e) {

    event.preventDefault();
    accordionExample.style.display = "";
    regresar_service[0].style.display = "";
    var row = e.currentTarget.attributes['valor'].value;
    var dato = $("#myTable").DataTable().data()[row];

    $("#titulo")[0].innerText = "Add service to job " + "-> Reference Number: " + dato.num_referencia;


    gridcat[0].style.display = "none";
    formservice[0].style.display = "";

    id_servicio[0].value = 0;
    idjob_service[0].value = dato.id_trabajo;

    cargargridservicios(dato.id_trabajo)
});

function cargaformularioservicio(expandirformulario) {
    gridcat[0].style.display = "none";
    formservice[0].style.display = "";
    accordionExample.style.display = "";
    regresar_service[0].style.display = "";
    if (expandirformulario == 1) {
        $('#btncollapseOne').trigger('click');
    }

    document.querySelector('.FormularioAjax2').reset();
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();
    cargargridservicios(idjob_service[0].value)
}

$(document).on('click', '#editservicios', function(e) {

    event.preventDefault();
    $('#btncollapseOne').trigger('click');
    var row = e.currentTarget.attributes['valor'].value;
    var dato = $("#myTableservicio").DataTable().data()[row];


    id_servicio[0].value = dato.id_servicio;
    idjob_service[0].value = dato.id_trabajo;

    document.getElementsByName("model")[0].value = dato.model;
    document.getElementsByName("servicefee")[0].value = dato.servicefee;
    document.getElementsByName("covered")[0].value = dato.covered;
    document.getElementsByName("problemdetail")[0].value = dato.problemdetail;

    $("#select_service").val(dato.id_valservice);
    $('#select_service').change();

    $("#select_appliance").val(dato.id_valappliance);
    $('#select_appliance').change();

    $("#select_brand").val(dato.id_valbrand);
    $('#select_brand').change();

    $("#select_symptom").val(dato.id_valsymptom);
    $('#select_symptom').change();


});




function cargargridservicios(idtrabajo) {
    $(".loadersacn")[0].style.display = "";
    gridcat[0].style.display = "none";
    formCompany[0].style.display = "none";
    formservice[0].style.display = "";
    gridservicio[0].style.display = "";
    document.getElementsByName("formservice")[0].reset();
    $.ajax({
        type: "GET",
        url: "<?php echo APP_URL . 'ajax/jobAjax.php?cargagridservicio' ?>=" + idtrabajo,
        success: function(response) {
            $(".loadersacn").fadeOut("slow");
            var res = jQuery.parseJSON(response);
            var estilo = "";

            var datos = [];

            if (res.status == 200) {
                datos = res.data;
            }
            $('#myTableservicio').DataTable({
                // layout: {
                //             topStart: {
                //                 buttons: [ {
                //                     extend: 'excel',
                //                     text: 'Descargar archivo excel'
                //                 }
                //             ]
                //             }
                //         },
                data: datos,
                // language: {
                //     "url": "<?php echo APP_URL ?>config/es-MX.json"
                // },
                //searching: false, 
                destroy: true,
                responsive: true,
                columnDefs: [{
                    orderable: false,
                    render: DataTable.render.select(),
                    targets: 0
                }, ],
                columns: [
                    // {
                    //     className: "text-center",
                    //     data: null,
                    // },
                    {
                        data: 'id_servicio',
                        visible: false,
                    },
                    {
                        data: 'id_trabajo',
                        visible: false,
                    },
                    {
                        data: 'id_valservice',
                        visible: false,
                    },
                    {
                        data: 'id_valappliance',
                        visible: false,
                    },
                    {
                        data: 'id_valbrand',
                        visible: false,
                    },
                    {
                        data: 'id_valsymptom',
                        visible: false,
                    },
                    {
                        width: "15%",
                        title: 'Service',
                        className: "text-center",
                        data: 'tiposervicio',
                    },
                    {
                        width: "15%",
                        title: 'Appliance',
                        className: "text-center",
                        data: 'appliance',
                    },
                    {
                        width: "15%",
                        title: 'brand',
                        data: 'brand',

                    },
                    {
                        width: "15%",
                        title: 'Model',
                        className: "text-center",
                        data: 'model',
                    },
                    {
                        width: "15%",
                        title: 'Symptom',
                        className: "text-center",
                        data: 'symptom',
                    },

                    {
                        width: "10%",
                        className: "text-center",
                        title: 'Problem Detail',
                        data: 'problemdetail',
                        render: function(data, type, row, meta) {
                            cadena =
                                '<td ><button type="button" class="button is-eliminar is-rounded is-small" data-toggle="tooltip" data-placement="right"' +
                                'title="' +
                                data + '">' +
                                '<i class="fas fa-info-circle"></i></button></td>';
                            return cadena;
                        }

                    },
                    {
                        width: "10%",
                        title: 'Service Fee',
                        data: 'servicefee',
                        render: $.fn.dataTable.render.number(',', '.', 2)

                    },
                    {
                        width: "10%",
                        title: 'Covered',
                        data: 'covered',
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {

                        className: "text-center",
                        title: 'Actions',
                        data: 'id_servicio',
                        render: function(data, type, row, meta) {
                            cadena =
                                '<div style="width: 160px;"><div style="float: left;margin-right: 2px;"><a id="editservicios" title="Edit Service" href="#" class="button is-services is-rounded is-small" valor="' +
                                meta.row + '">' +
                                '<i class="far fa-edit"></i></a></div> ';

                            cadena = cadena + '<td>' +
                                '<div style="float: left;">';
                            cadena = cadena + '<div>';
                            cadena = cadena +
                                '<form name="accionservicio" class="FormularioAcciones" action="<?php echo APP_URL ?>ajax/jobAjax.php" method="POST" autocomplete="off" >' +
                                '<input type="hidden" name="modulo_jobservice" value="eliminar">' +
                                '<input type="hidden" name="id_servicio" value="' +
                                data + '">' +
                                '<button type="submit" title="Eliminate" class="button is-acciones is-rounded is-small">' +
                                '<i class="far fa-trash-alt"></i>' +
                                '</button>' +
                                '</form>';

                            cadena = cadena + '</div>';
                            cadena = cadena + '</div></td>';
                            return cadena;

                        }

                    },

                ],
                // order: [
                //     [0, 'asc']
                // ],
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
                    if (data.u_bloqueado == 1) {
                        $(row).addClass('bloqueado');
                    }
                },
            });




            $('[data-toggle="tooltip"]').tooltip();

            document.querySelectorAll(".tooltip-btn").forEach((btn) => {
                btn.addEventListener("mouseenter", () => {
                    btn.setAttribute("data-show", "true");
                });

                btn.addEventListener("mouseleave", () => {
                    btn.removeAttribute("data-show");
                });
            });

        }


    });
}

function cargargrid() {
    $(".loadersacn")[0].style.display = "";
    $("#titulo")[0].innerText = "job list";

    gridcat[0].style.display = "";
    formCompany[0].style.display = "none";
    document.getElementsByName("formCompany")[0].reset();
    $.ajax({
        type: "GET",
        url: "<?php echo APP_URL . 'ajax/jobAjax.php?cargagrid' ?>",
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
                // language: {
                //     "url": "<?php echo APP_URL ?>config/es-MX.json"
                // },
                //searching: false, 
                destroy: true,
                responsive: true,
                columnDefs: [{
                    orderable: false,
                    render: DataTable.render.select(),
                    targets: 0
                }, ],
                columns: [
                    // {
                    //     className: "text-center",
                    //     data: null,
                    // },
                    {
                        data: 'id_trabajo',
                        visible: false,
                    },
                    {
                        data: 'id_company',
                        visible: false,
                    },
                    {
                        data: 'id_cliente',
                        visible: false,
                    },
                    {
                        data: 'id_tecnico',
                        visible: false,
                    },
                    {
                        width: "30%",
                        title: 'Reference Number',
                        className: "text-center",
                        data: 'num_referencia',
                    },
                    {
                        width: "30%",
                        title: 'Company Name',
                        className: "text-center",
                        data: 'nombre',
                    },
                    {
                        width: "20%",
                        title: 'Company Status',
                        data: 'estadocompany',

                    },
                    {
                        width: "30%",
                        title: 'Full Name',
                        className: "text-center",
                        data: 'full_name',
                    },
                    {
                        width: "30%",
                        title: 'City',
                        className: "text-center",
                        data: 'city',
                    },

                    {
                        width: "30%",
                        title: 'Phone',
                        data: 'phone',

                    },
                    {
                        width: "30%",
                        title: 'Email',
                        data: 'email',

                    },
                    {
                        width: "30%",
                        title: 'Jobs state',
                        data: 'estadojob',

                    },
                    {
                        width: "30%",
                        title: 'Status',
                        data: 'u_estado',
                        render: function(data, type, row, meta) {
                            if (row.u_estado == 1) {
                                return "Active";
                            } else {
                                return "Inactive";
                            }

                        }

                    },
                    {
                        className: "text-center",
                        title: 'Actions',
                        data: 'id_trabajo',
                        render: function(data, type, row, meta) {
                            cadena = '<td>' +
                                '<li class="nav-item dropdown">' +
                                '<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">' +
                                '<i class="fas fa-cog" style="font-size: x-large;"></i>' +
                                '</a>' +
                                '<div class="dropdown-menu">';
                            cadena = cadena +
                                '<div><div style="margin: 2px;"><a id="modificar" title="Edit" href="#" class="button is-info is-rounded is-small" valor="' +
                                meta.row + '">' +
                                '<i class="fas fa-sync fa-fw"></i></a></div> ';

                            cadena = cadena +
                                '<div style="margin: 2px;"><a id="servicios" title="Services" href="#" class="button is-services is-rounded is-small" valor="' +
                                meta.row + '">' +
                                '<i class="fas fa-tools"></i></a></div> ';

                            cadena = cadena + '<td>' +
                                '<div style="">';
                            cadena = cadena + '<div>';
                            if (row.u_estado == 0) {


                                cadena = cadena +
                                    '<form class="FormularioAcciones" action="<?php echo APP_URL ?>ajax/jobAjax.php" method="POST" autocomplete="off" >' +
                                    '<input type="hidden" name="modulo_job" value="activar">' +
                                    '<input type="hidden" name="id_trabajo" value="' +
                                    data + '">' +
                                    '<button type="submit" title="Activate" class="button is-acciones is-rounded is-small">' +
                                    '<i class="fas fa-check-circle"></i>' +
                                    '</button>' +
                                    '</form>';
                            } else {
                                cadena = cadena +
                                    '<form class="FormularioAcciones" action="<?php echo APP_URL ?>ajax/jobAjax.php" method="POST" autocomplete="off" >' +
                                    '<input type="hidden" name="modulo_job" value="inactivar">' +
                                    '<input type="hidden" name="id_trabajo" value="' +
                                    data + '">' +
                                    '<button type="submit" title="Inactivate" class="button is-acciones is-rounded is-small">' +
                                    '<i class="fas fa-times-circle"></i>' +
                                    '</button>' +
                                    '</form>';
                            }
                            cadena = cadena + '</div>';
                            cadena = cadena + '</div>';
                            '</div>' +
                            '</li></td>';
                            return cadena;

                        }

                    },
                ],
                order: [
                    [2, 'asc']
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
                    if (data.u_bloqueado == 1) {
                        $(row).addClass('bloqueado');
                    }
                },
            });






        }
    });
}
cargargrid();
</script>