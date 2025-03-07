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
<!-- modal diagnosticar servicios-->
<div class="modal fade" id="modaldiagnostico">
    <div class="modal-dialog modal-lx">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="titulomodaldiagnostico" class="modal-title modal-title-h4"></h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color: #f3f3f3;">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formdatosservicio" action="#" method="POST" autocomplete="off" enctype="multipart/form-data">
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
                                <div class="control">
                                    <label>Problem Detail<?php echo CAMPO_OBLIGATORIO; ?></label>
                                    <textarea name="problemdetail" class="input" style="height: 100px;"
                                        required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <form name="formdiagnostico" class="FormularioAjax6"
                    action="<?php echo APP_URL; ?>ajax/managejobAjax.php" method="POST" autocomplete="off"
                    enctype="multipart/form-data">
                    <input type="hidden" name="id_diagnostico" value="">
                    <input type="hidden" name="id_servicio" value="">
                    <input type="hidden" name="idjob_service" value="">
                    <input type="hidden" name="modulo_diagnostico" value="registrardiagnostico">
                    <div class="col-sm-12 col-md-12">
                        <div class="columns">
                            <div class="column">
                                <div class="control">
                                    <label>Serial #</label>
                                    <input class="input" type="text" name="serial" maxlength="70" required>
                                </div>
                            </div>
                            <div class="column">
                                <div class="control">
                                    <label>Labor fee</label>
                                    <input class="input" type="text" name="laborfee" value="0.00" pattern="[0-9.]{1,25}"
                                        required maxlength="25">
                                </div>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column">
                                <div class="control ">
                                    <label>Job description<?php echo CAMPO_OBLIGATORIO; ?></label>
                                    <textarea name="diagnostico" class="input" style="height: 100px;"
                                        required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="has-text-centered">
                        <button type="reset" class="button is-link is-light is-rounded"><i
                                class="fas fa-paint-roller"></i> &nbsp;
                            Clean</button>
                        <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i>
                            &nbsp;
                            Save</button>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <h1 class="title">Jobs</h1>

    <h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; <label id="titulo"></label></h2>

    <div name="gridcat"> 
        <table id="myTable" class="table table-striped table-bordered">

        </table>
        <br>


    </div>



    <form name="formCompany" id="formtrabajo" class="FormularioAjax "
        action="<?php echo APP_URL; ?>ajax/managejobAjax.php" method="POST" autocomplete="off"
        enctype="multipart/form-data" style="display:none">
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


    </form>



    <p class="has-text-right pt-1 pb-1">
        <button name="regresar_service" type="reset" class="button is-link is-light is-rounded"><i
                class="fas fa-arrow-alt-circle-left"></i> &nbsp; Go back</button>
    </p>



    <div name="gridservicio">
        <table id="myTableservicio" class="table table-striped table-bordered">

        </table>
    </div>


    <!-- OPCIONES PARA EL FORMULARIO DE CITA DEL TRABAJO -->

    <div class="accordion" id="accordioncita" style="display: none;">
        <div class="card">
            <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                    <button id="btncollapsecita" class="btn btn-link btn-block text-left" type="button"
                        data-toggle="collapse" data-target="#collapsecita" aria-expanded="true"
                        aria-controls="collapsecita">
                        <i class="far fa-calendar-alt"></i>
                        Appoinment Schedule
                    </button>
                </h2>
            </div>

            <div id="collapsecita" class="collapse" aria-labelledby="headingOne" data-parent="#accordioncita">
                <div class="card-body">
                    <form name="formcita" id="formcitajob" class="FormularioAjax3"
                        action="<?php echo APP_URL; ?>ajax/managejobAjax.php" method="POST" autocomplete="off"
                        enctype="multipart/form-data">
                        <input type="hidden" name="id_cita" value="">
                        <input type="hidden" name="idjob_cita" value="">
                        <input type="hidden" name="modulo_Opcion_cita" value="registrarcita">
                        <div class="col-sm-12 col-md-12">
                            <div class="columns">
                                <div class="col-sm-12 col-md-6">
                                    <div class="control ">
                                        <label>Appoinment Date<?php echo CAMPO_OBLIGATORIO; ?></label>
                                        <input id="select_fechacita" name="fechacita" class="form-control" type="date"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="columns">
                                <div class="col-sm-12 col-md-1">
                                    <div class="control ">
                                        <label>Start time </label>
                                        <div class="buttonclock clock">
                                            <i class="far fa-clock"></i>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-1">
                                    <div class="control ">
                                        <label>Hour</label>
                                        <select id="select_horaini" name="horaini" class="form-select">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-1">
                                    <div class="control ">
                                        <label>Min</label>
                                        <select id="select_minini" name="minini" class="form-select">
                                            <option value="00">00</option>
                                            <option value="05">05</option>
                                            <option value="10">10</option>
                                            <option value="15">15</option>
                                            <option value="20">20</option>
                                            <option value="25">25</option>
                                            <option value="30">30</option>
                                            <option value="35">35</option>
                                            <option value="40">40</option>
                                            <option value="45">45</option>
                                            <option value="50">50</option>
                                            <option value="55">55</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-1">
                                    <div class="control ">
                                        <label>AM/PM</label>
                                        <select id="select_tiempoini" name="tiempoini" class="form-select">
                                            <option value="AM">AM</option>
                                            <option value="PM">PM</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-1" style="margin-left: 10px;">
                                    <div class="control ">
                                        <label>End time</label>
                                        <div class="buttonclock clockend">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-1">
                                    <div class="control ">
                                        <label>Hour</label>
                                        <select id="select_horafin" name="horafin" class="form-select">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-1">
                                    <div class="control ">
                                        <label>Min</label>
                                        <select id="select_minfin" name="minfin" class="form-select">
                                            <option value="00">00</option>
                                            <option value="05">05</option>
                                            <option value="10">10</option>
                                            <option value="15">15</option>
                                            <option value="20">20</option>
                                            <option value="25">25</option>
                                            <option value="30">30</option>
                                            <option value="35">35</option>
                                            <option value="40">40</option>
                                            <option value="45">45</option>
                                            <option value="50">50</option>
                                            <option value="55">55</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-1">
                                    <div class="control ">
                                        <label>AM/PM</label>
                                        <select id="select_tiempofin" name="tiempofin" class="form-select">
                                            <option value="AM">AM</option>
                                            <option value="PM">PM</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="columns">
                                <div class="column">
                                    <div class="control">
                                        <label>Customer Notes</label>
                                        <textarea name="nota" class="input" style="height: 150px;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>


                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingTwo">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                        data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="far fa-money-bill-alt"></i>
                        Payment Information

                    </button>

                </h2>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordioncita">
                <div class="card-body">
                    <form name="formpago" id="formpagojob" class="FormularioAjax4"
                        action="<?php echo APP_URL; ?>ajax/managejobAjax.php" method="POST" autocomplete="off"
                        enctype="multipart/form-data">
                        <input type="hidden" name="id_pago" value="">
                        <input type="hidden" name="idjob_pago" value="">
                        <input type="hidden" name="modulo_Opcion_pago" value="registrarpago">
                        <div class="col-sm-12 col-md-12">
                            <div class="columns">
                                <div class="column">
                                    <div class="control ">
                                        <?php
                                        $catalogo = $insrol->ejecutarconsultaarreglo("select c.* from  \"SYSTEM\".catalogo c  where c.codigo='codpayment' ");

                                        $consulta_datos = "select * from \"SYSTEM\".obtener_valor_porcatalogo('codpayment' ) where estado=1;";

                                        $datos = $insrol->Ejecutar($consulta_datos);
                                        echo '<label>' . $catalogo[0]['nombre'] . ' ' . CAMPO_OBLIGATORIO . '</label><br>';

                                        echo ' <select id="select_pago" name="cmb_pago" class="form-select"  required>';
                                        echo '<option value="">Select a value </option>';
                                        while ($campos_caja = $datos->fetch()) {
                                            echo '<option value="' . $campos_caja['id_catalogovalor'] . '"> ' . $campos_caja['nombre'] . ' </option>';
                                        }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="columns">
                                <div class="column">
                                    <div class="control">
                                        <label>Payment description</label>
                                        <textarea name="notapayment" class="input" style="height: 150px;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>

</div>

<!-- MODAL GESTION DE EMPLEADOS-->
<div class="modal fade" id="modalmovimiento">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="titulomodal" class="modal-title modal-title-h4"></h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color: #f3f3f3;">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="formmovimiento" class="FormularioAjax5"
                    action="<?php echo APP_URL; ?>ajax/managejobAjax.php" method="POST" autocomplete="off"
                    enctype="multipart/form-data">
                    <input type="hidden" name="idjob_movimiento" value="">
                    <input type="hidden" name="modulo_movimiento" value="registrarmovimiento">
                    <div class="col-sm-12 col-md-12">
                        <div class="columns">
                            <div class="column">
                                <div class="control ">
                                    <?php
                                        $catalogo = $insrol->ejecutarconsultaarreglo("select c.* from  \"SYSTEM\".catalogo c  where c.codigo='codjob' ");

                                        $consulta_datos = "select * from \"SYSTEM\".obtener_valor_porcatalogo('codjob' ) where estado=1;";

                                        $datos = $insrol->Ejecutar($consulta_datos);
                                        echo '<label>Job State' . CAMPO_OBLIGATORIO . '</label><br>';

                                        echo ' <select id="select_estadojob" name="cmb_estadojob" class="form-select"  required>';
                                        echo '<option value="">Select a value </option>';
                                        while ($campos_caja = $datos->fetch()) {
                                            echo '<option value="' . $campos_caja['id_catalogovalor'] . '"> ' . $campos_caja['nombre'] . ' </option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column">
                                <div class="control">
                                    <label>Reason for change of status<?php echo CAMPO_OBLIGATORIO; ?></label>
                                    <textarea name="notacambioestado" class="input" style="height: 150px;"
                                        required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="has-text-centered">

                        <button type="reset" class="button is-link is-light is-rounded"><i
                                class="fas fa-paint-roller"></i> &nbsp;
                            Clean</button>
                        <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i>
                            &nbsp;
                            Save</button>
                    </p>
                </form>
                <table id="griddmovimiento" class="table table-striped table-bordered">

            </div>
        </div>
    </div>
</div>
<!-- Fin modal de movimientos -->

<script>
$(document).ready(function() {
    $('#datepicker, #datepicker2').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });

    $('#modalmovimiento').on('hidden.bs.modal', function(event) {
        pantallaprincipal();
    })
});
</script>
<script>
//JSON.stringify(tabla.rows( { selected: true } ).data().toArray());
 
const regresar = document.getElementsByName("regresar");
const regresar_service = document.getElementsByName("regresar_service");

const idjob = document.getElementsByName("idjob");
const idjob_service = document.getElementsByName("idjob_service");
const id_servicio = document.getElementsByName("id_servicio");
const idjob_cita = document.getElementsByName("idjob_cita");
const id_cita = document.getElementsByName("id_cita");
const idjob_pago = document.getElementsByName("idjob_pago");
const id_pago = document.getElementsByName("id_pago");
const idjob_movimiento = document.getElementsByName("idjob_movimiento");
const id_diagnostico = document.getElementsByName("id_diagnostico");





const formCompany = document.getElementsByName("formCompany");
const formcita = document.getElementsByName("formcita");
const formpago = document.getElementsByName("formpago");

const accordioncita = document.getElementById("accordioncita")

const gridcat = document.getElementsByName("gridcat");
const gridservicio = document.getElementsByName("gridservicio");

function pantallaprincipal() {
    gridcat[0].style.display = "";
    accordioncita.style.display = "none";
    gridservicio[0].style.display = "none";
    formCompany[0].style.display = "none";

    regresar_service[0].style.display = "none";
    document.getElementsByName("formCompany")[0].reset();

    document.getElementsByName("formcita")[0].reset();
    document.getElementsByName("formpago")[0].reset();
    $("#titulo")[0].innerText = "job list";
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();    
    cargargrid();
}

$(document).ready(function() {
    $('.form-select').select2();
    //para dejar sin seleccion el combo
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();
    gridservicio[0].style.display = "none";
    accordioncita.style.display = "none";
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

 
$(document).on('click', '#modificar', function(e) {

    event.preventDefault();
    $("#titulo")[0].innerText = "Job details";
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

    let inputs = document.querySelectorAll("#formtrabajo input,#formtrabajo textarea, #formtrabajo select");
    inputs.forEach(input => input.disabled = true);




});


$(document).on('click', '#movimientos', function(e) {

    event.preventDefault();
    var row = e.currentTarget.attributes['valor'].value;
    var dato = $("#myTable").DataTable().data()[row];
    idjob_movimiento[0].value = dato.id_trabajo;

    $("#titulomodal")[0].innerHTML = ' Job Reference: ' + dato.num_referencia;
    //para ocultar
    //$("#modalvalidaciones").modal("hide");

    $.ajax({
        type: "GET",
        url: "<?php  echo APP_URL.'ajax/managejobAjax.php' ?>",
        data: "obtenermovimientosjob=" + dato.id_trabajo,
        success: function(response) {

            var res = jQuery.parseJSON(response);
            if (res.status == 200) {

                cargargridmovimientos(res.data);


                // para mostrar modal
                $("#modalmovimiento").modal({
                    backdrop: "static",
                    keyboard: false
                });

            }

        }
    })


});

function cargargridmovimiento_save(alerta) {
    $.ajax({
        type: "GET",
        url: "<?php  echo APP_URL.'ajax/managejobAjax.php' ?>",
        data: "obtenermovimientosjob=" + idjob_movimiento[0].value,
        success: function(response) {

            var res = jQuery.parseJSON(response);
            if (res.status == 200) {

                cargargridmovimientos(res.data);

            }

        }
    })
    document.querySelector('.FormularioAjax5').reset();
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();

}

function cargargridmovimientos(datos) {

    $('#griddmovimiento').DataTable({
        data: datos,
        language: {
            "url": "<?php  echo APP_URL?>config/es-MX.json"
        },
        destroy: true,
        responsive: true,
        columns: [{
                data: 'id_movimiento',
                visible: false,
            },
            {
                data: 'id_trabajo',
                visible: false,
            },
            {
                title: 'Job state',
                className: "text-center",
                data: 'estadojob',

            },
            {
                className: "text-center",
                title: 'Reason',
                data: 'nota',
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
                width: "20%",
                title: 'Date',
                data: 'fecha_creacion',


            },
            {
                title: 'User',
                className: "text-center",
                data: 'usuario',

            },

        ],
        order: [
            [0, 'desc']
        ],
        //paging: false,

    });

}


$(document).on('click', '#servicios', function(e) {

    event.preventDefault();
    regresar_service[0].style.display = "";
    var row = e.currentTarget.attributes['valor'].value;
    var dato = $("#myTable").DataTable().data()[row];

    $("#titulo")[0].innerText = "Add service to job " + "-> Reference Number: " + dato.num_referencia;


    gridcat[0].style.display = "none";

    id_servicio[0].value = 0;
    idjob_service[0].value = dato.id_trabajo;

    cargargridservicios(dato.id_trabajo)

});

$(document).on('click', '#cita', function(e) {

    event.preventDefault();
    $('#btncollapsecita').trigger('click');
    regresar_service[0].style.display = "";
    var row = e.currentTarget.attributes['valor'].value;
    var dato = $("#myTable").DataTable().data()[row];
    accordioncita.style.display = "";

    $("#titulo")[0].innerText = "Add to job " + "-> Reference Number: " + dato
        .num_referencia;


    gridcat[0].style.display = "none";


    idjob_cita[0].value = dato.id_trabajo;
    idjob_pago[0].value = dato.id_trabajo;
    $(".loadersacn")[0].style.display = "";
    $.ajax({
        type: "GET",
        url: "<?php echo APP_URL . 'ajax/managejobAjax.php?cargadatoscita' ?>=" + dato.id_trabajo,
        success: function(response) {
            $(".loadersacn").fadeOut("slow");
            var res = jQuery.parseJSON(response);
            var estilo = "";

            var datos = [];

            if (res.status == 200) {
                datos = res.data;
                id_cita[0].value = datos[0].id_cita;
                $("#select_fechacita")[0].value = datos[0].fecha;

                $("#select_horaini").val(datos[0].horaini);
                $('#select_horaini').change();

                $("#select_horafin").val(datos[0].horafin);
                $('#select_horafin').change();

                $("#select_minini").val(datos[0].minini);
                $('#select_minini').change();

                $("#select_minfin").val(datos[0].minfin);
                $('#select_minfin').change();

                $("#select_tiempofin").val(datos[0].tiempofin);
                $('#select_tiempofin').change();

                $("#select_tiempoini").val(datos[0].tiemponi);
                $('#select_tiempoini').change();

                document.getElementsByName("nota")[0].value = datos[0].nota;

            } else {
                id_cita[0].value = 0;
            }
        }


    });

    $.ajax({
        type: "GET",
        url: "<?php echo APP_URL . 'ajax/managejobAjax.php?cargadatospago' ?>=" + dato.id_trabajo,
        success: function(response) {
            $(".loadersacn").fadeOut("slow");
            var res = jQuery.parseJSON(response);
            var estilo = "";

            var datos = [];

            if (res.status == 200) {
                datos = res.data;
                id_pago[0].value = datos[0].id_payment;
                $("#select_pago").val(datos[0].id_valpayment);
                $('#select_pago').change();

                document.getElementsByName("notapayment")[0].value = datos[0].nota;

            } else {
                id_pago[0].value = 0;
            }
        }


    });

    let inputs = document.querySelectorAll(
        "#formcitajob input,#formcitajob textarea, #formcitajob select,#formpagojob input,#formpagojob textarea, #formpagojob select"
    );
    inputs.forEach(input => input.disabled = true);

});

function cargaformularioservicio(expandirformulario) {
    gridcat[0].style.display = "none";
    regresar_service[0].style.display = "";
    if (expandirformulario == 1) {
        $('#btncollapseOne').trigger('click');
    }

    document.querySelector('.FormularioAjax2').reset();
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();
    cargargridservicios(idjob_service[0].value)
}

function quedarenpantalla(alerta) {
    if (alerta.classform == ".FormularioAjax3") {
        id_cita[0].value = alerta.idgenerado;
    } else {
        id_pago[0].value = alerta.idgenerado;
    }


}

$(document).on('click', '#editservicios', function(e) {

    event.preventDefault();
    $('#btncollapseOne').trigger('click');
    var row = e.currentTarget.attributes['valor'].value;
    var dato = $("#myTableservicio").DataTable().data()[row];
    document.getElementsByName("formdiagnostico")[0].reset();

    
    id_servicio[0].value = dato.id_servicio;
    idjob_service[0].value = dato.id_trabajo;

  

    $.ajax({
        type: "GET",
        url: "<?php echo APP_URL . 'ajax/managejobAjax.php?cargadiagnostico' ?>=" + dato.id_servicio,
        success: function(response) {
            $(".loadersacn").fadeOut("slow");
            var res = jQuery.parseJSON(response);
            var estilo = "";

            var datos = [];

            if (res.status == 200) {
                datos = res.data;
                id_diagnostico[0].value=datos[0].id_diagnostico;

                document.getElementsByName("diagnostico")[0].value = datos[0].nota;
                document.getElementsByName("laborfee")[0].value = datos[0].laborfee;
                document.getElementsByName("serial")[0].value = datos[0].serial;

            } else {
                id_diagnostico[0].value = 0;
            }
        }


    });

    document.getElementsByName("model")[0].value = dato.model;
    document.getElementsByName("problemdetail")[0].value = dato.problemdetail;

    $("#select_service").val(dato.id_valservice);
    $('#select_service').change();

    $("#select_appliance").val(dato.id_valappliance);
    $('#select_appliance').change();

    $("#select_brand").val(dato.id_valbrand);
    $('#select_brand').change();

    let inputs = document.querySelectorAll(
        "#formdatosservicio input,#formdatosservicio textarea, #formdatosservicio select");
    inputs.forEach(input => input.disabled = true);


    $("#titulomodaldiagnostico")[0].innerHTML = ' Diagnosis for service';
    // para mostrar modal
    $("#modaldiagnostico").modal({
        backdrop: "static",
        keyboard: false
    });


});

function quedarenmodaldiagnostico(alerta){
    id_diagnostico[0].value=alerta.id_diagnostico;
    
}


function cargargridservicios(idtrabajo) {
    $(".loadersacn")[0].style.display = "";
    gridcat[0].style.display = "none";
    formCompany[0].style.display = "none";

    gridservicio[0].style.display = "";

    $.ajax({
        type: "GET",
        url: "<?php echo APP_URL . 'ajax/managejobAjax.php?cargagridservicio' ?>=" + idtrabajo,
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
                            cadena ='<td>'+
                                '<div style="width: 160px;"><div style="float: left;margin-right: 2px;"><a id="editservicios" title="Diagnosis" href="#" class="button is-diagnosticar is-rounded is-small" valor="' +
                                meta.row + '">' +
                                '<i class="fas fa-diagnoses"></i></a></div> '; 

                            return cadena + '</td>';

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
        url: "<?php echo APP_URL . 'ajax/managejobAjax.php?cargagrid' ?>",
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
                            cadena = "";
                            if (row.estadojob == "Booked") {
                                cadena = '<td><div style=""><div>';
                                cadena = cadena +
                                    '<form class="FormularioAjax" action="<?php echo APP_URL ?>ajax/managejobAjax.php" method="POST" autocomplete="off" >' +
                                    '<input type="hidden" name="modulo_job" value="aceptar">' +
                                    '<input type="hidden" name="id_trabajo" value="' +
                                    data + '">' +
                                    '<button type="submit" title="Accept Job" class="button is-accept is-rounded is-small">' +
                                    '<i class="fas fa-check-circle"></i>' +
                                    '</button>' +
                                    '</form>';
                                cadena = cadena + '</div></div></td>';
                            } else {
                                cadena = '<td>' +
                                    '<li class="nav-item dropdown">' +
                                    '<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">' +
                                    '<i class="fas fa-cog" style="font-size: x-large;"></i>' +
                                    '</a>' +
                                    '<div class="dropdown-menu">';
                                cadena = cadena +
                                    '<div><div style="margin: 2px;"><a id="modificar" title="Job details" href="#" class="button is-info is-rounded is-small" valor="' +
                                    meta.row + '">' +
                                    '<i class="fas fa-sync fa-fw"></i></a></div> ';

                                cadena = cadena +
                                    '<div style="margin: 2px;"><a id="servicios" title="Services" href="#" class="button is-services is-rounded is-small" valor="' +
                                    meta.row + '">' +
                                    '<i class="fas fa-tools"></i></a></div> ';
                                cadena = cadena +
                                    '<div><div style="margin: 2px;"><a id="cita" title="Appointment schedule and Payment information" href="#" class="button is-cita is-rounded is-small" valor="' +
                                    meta.row + '">' +
                                    '<i class="far fa-calendar-alt"></i></a></div> ';

                                cadena = cadena +
                                    '<div><div style="margin: 2px;"><a id="movimientos" title="Status history" href="#" class="button is-history is-rounded is-small" valor="' +
                                    meta.row + '">' +
                                    '<i class="fas fa-history"></i></a></div> ';

                                // cadena = cadena +
                                //     '<div style="">';
                                // cadena = cadena + '<div>';
                                // if (row.u_estado == 0) {


                                //     cadena = cadena +
                                //         '<form class="FormularioAcciones" action="<?php echo APP_URL ?>ajax/managejobAjax.php" method="POST" autocomplete="off" >' +
                                //         '<input type="hidden" name="modulo_job" value="activar">' +
                                //         '<input type="hidden" name="id_trabajo" value="' +
                                //         data + '">' +
                                //         '<button type="submit" title="Activate" class="button is-acciones is-rounded is-small">' +
                                //         '<i class="fas fa-check-circle"></i>' +
                                //         '</button>' +
                                //         '</form>';
                                // } else {
                                //     cadena = cadena +
                                //         '<form class="FormularioAcciones" action="<?php echo APP_URL ?>ajax/managejobAjax.php" method="POST" autocomplete="off" >' +
                                //         '<input type="hidden" name="modulo_job" value="inactivar">' +
                                //         '<input type="hidden" name="id_trabajo" value="' +
                                //         data + '">' +
                                //         '<button type="submit" title="Inactivate" class="button is-acciones is-rounded is-small">' +
                                //         '<i class="fas fa-times-circle"></i>' +
                                //         '</button>' +
                                //         '</form>';
                                // }
                                // cadena = cadena + '</div>';
                                // cadena = cadena + '</div>';
                                '</div>' +
                                '</li></td>';

                            }

                            return cadena;

                        }

                    },
                ],
                order: [
                    [2, 'asc']
                ],
                //paging: false,
                //scrollCollapse: true,
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