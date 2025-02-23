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
    <h1 class="title">Company</h1>

    <h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; <label id="titulo"></label></h2>

    <div name="gridcat">


        <p class="has-text-left pt-4 pb-4">
            <a name="agregarcat" href="#" class="button is-link is-rounded btn-back"><i class="fas fa-plus"></i> &nbsp;
                Add record</a>
        </p>
        <table id="myTable" class="table table-striped table-bordered">

        </table>
        <br>


    </div>


    <p class="has-text-centered">
    <form name="formCompany" class="FormularioAjax " action="<?php echo APP_URL; ?>ajax/companyAjax.php" method="POST"
        autocomplete="off" enctype="multipart/form-data" style="display:none">
        <input type="hidden" name="idCompany" value="">
        <p class="has-text-right pt-4 pb-4">
            <button name="regresar" type="reset" class="button is-link is-light is-rounded"><i
                    class="fas fa-arrow-alt-circle-left"></i> &nbsp; Go back</button>
        </p>
        <input type="hidden" name="modulo_Opcion" value="registrar">
        <div class="col-sm-12 col-md-12">
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Company Name <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="text" name="nombre" required>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>City <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="text" name="ciudad" required>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Email</label>
                        <input class="input" type="email" name="email" maxlength="70"  >
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Address<?php echo CAMPO_OBLIGATORIO; ?></label>
                        <textarea name="direccion" class="input" style="height: 110px;" required></textarea>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <div class="control ">

                        <?php
                            $catalogo =$insrol->ejecutarconsultaarreglo("select c.* from  \"SYSTEM\".catalogo c  where c.codigo='codsatate' "); 

                        $consulta_datos="select * from \"SYSTEM\".obtener_valor_porcatalogo('codsatate' ) where estado=1;"; 

                        $datos = $insrol->Ejecutar($consulta_datos); 
                        echo '<label>'.$catalogo[0]['nombre'].' '. CAMPO_OBLIGATORIO.'</label><br>';

                        echo ' <select name="cmb_estado" class="form-select" id="select_state" required>';
                            echo '<option value="">Select a value </option>';
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
                        <label>ZIP Code <?php echo CAMPO_OBLIGATORIO; ?></label>
                        <input class="input" type="number" name="codigozip" required>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Credit Limit </label>
                        <input class="input" type="text" name="credito" value="0.00" pattern="[0-9.]{1,25}"
                            maxlength="25" required>
                    </div>

                </div>
            </div>

            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Contact Full Name</label>
                        <input class="input" type="text" name="nombrecompleto" maxlength="70"  >
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Contact Phone</label>
                        <input class="input" type="text" name="telefono" maxlength="70"  >
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>NTE </label>
                        <input class="input" type="text" name="nte" value="0.00" pattern="[0-9.]{1,25}" maxlength="25"
                            required>
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
    </p>
</div>



<script>
//JSON.stringify(tabla.rows( { selected: true } ).data().toArray());

const button = document.getElementsByName("agregarcat");
const idCompany = document.getElementsByName("idCompany");
const seleccionados = document.getElementsByName("hdf_seleccionados");

const id_detalleactividad = document.getElementsByName("id_detalleactividad");
const regresar = document.getElementsByName("regresar");
const formCompany = document.getElementsByName("formCompany");
const gridcat = document.getElementsByName("gridcat");



$(document).ready(function() {
    $('.form-select').select2();
    //para dejar sin seleccion el combo
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();

});

regresar[0].addEventListener("click", (event) => {
    event.preventDefault();
    gridcat[0].style.display = "";
    formCompany[0].style.display = "none";
    document.getElementsByName("formCompany")[0].reset();
    $("#titulo")[0].innerText = "list of companies";
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();



});

button[0].addEventListener("click", (event) => {
    event.preventDefault();
    $("#titulo")[0].innerText = "New company";
    gridcat[0].style.display = "none";
    formCompany[0].style.display = "";
    idCompany[0].value = 0;
    $('.form-select').prop("selectedIndex", 0);
    $('.form-select').change();
});
$(document).on('click', '#modificar', function(e) {

    event.preventDefault();
    $("#titulo")[0].innerText = "Modify company";
    var row = e.currentTarget.attributes['valor'].value;
    var dato = $("#myTable").DataTable().data()[row];
    gridcat[0].style.display = "none";
    formCompany[0].style.display = "";

    idCompany[0].value = dato.id_company;

    document.getElementsByName("nombre")[0].value = dato.nombre;
    document.getElementsByName("ciudad")[0].value = dato.ciudad;
    document.getElementsByName("direccion")[0].value = dato.direccion;
    document.getElementsByName("codigozip")[0].value = dato.codigozip;
    document.getElementsByName("email")[0].value = dato.email;
    document.getElementsByName("nombrecompleto")[0].value = dato.nombrecompleto;
    document.getElementsByName("telefono")[0].value = dato.telefono;
    document.getElementsByName("credito")[0].value = dato.credito;
    document.getElementsByName("nte")[0].value = dato.NTE;

    $("#select_state").val(dato.id_valestado);
    $('#select_state').change();

});


 

function cargargrid() {
    $(".loadersacn")[0].style.display = "";
    $("#titulo")[0].innerText = "list of companies";

    gridcat[0].style.display = "";
    formCompany[0].style.display = "none";
    document.getElementsByName("formCompany")[0].reset();
    $.ajax({
        type: "GET",
        url: "<?php  echo APP_URL.'ajax/companyAjax.php?cargagrid' ?>",
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
                //     "url": "<?php  echo APP_URL?>config/es-MX.json"
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
                        title: 'Id Usuario',
                        className: "text-center",
                        data: 'id_company',
                        visible: false,
                    },
                    {
                        title: 'id_valestado',
                        className: "text-center",
                        data: 'id_valestado',
                        visible: false,
                    },

                    {
                        width: "30%",
                        title: 'Company Name',
                        className: "text-center",
                        data: 'nombre',
                    },

                    {
                        width: "30%",
                        title: 'City',
                        className: "text-center",
                        data: 'ciudad',
                    },
                    {
                        width: "20%",
                        title: 'Company Status',
                        data: 'estado',

                    },
                    {
                        width: "30%",
                        title: 'Phone',
                        data: 'telefono',

                    },
                    {
                        width: "30%",
                        title: 'Email',
                        data: 'email',

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
                        width: "30%",
                        title: 'Credit Limit',
                        data: 'credito',
                        render: $.fn.dataTable.render.number( ',', '.', 2)

                    },
                    {
                        width: "30%",
                        title: 'NTE',
                        data: 'NTE',
                        render: $.fn.dataTable.render.number( ',', '.', 2)

                    },
                    // {
                    //     width: "30%",
                    //     title: 'User creation',
                    //     data: 'usuario',


                    // },
                    // {
                    //     width: "30%",
                    //     title: 'Creation date',
                    //     data: 'fecha_creacion',


                    // },
                    // {
                    //     width: "30%",
                    //     title: 'User modification',
                    //     data: 'usuariom',


                    // },
                    // {
                    //     width: "30%",
                    //     title: 'Modification date',
                    //     data: 'fecha_modifica',


                    // },
                    {
                        className: "text-center",
                        title: 'Edit',
                        data: 'id_company',
                        render: function(data, type, row, meta) {
                            return '<td><a id="modificar" title="Edit" href="#" class="button is-info is-rounded is-small" valor="' +
                                meta.row + '">' +
                                '<i class="fas fa-sync fa-fw"></i></a> </td>';
                        }

                    },
                    {

                        className: "text-center",
                        title: 'Actions',
                        data: 'id_company',
                        render: function(data, type, row, meta) {
                            cadena = '<div  ';

                            cadena = cadena + '<td>' +
                                '<div style="float: left;">';

                            if (row.u_estado == 0) {


                                cadena = cadena +
                                    '<form class="FormularioAcciones" action="<?php  echo APP_URL?>ajax/companyAjax.php" method="POST" autocomplete="off" >' +
                                    '<input type="hidden" name="modulo_company" value="activar">' +
                                    '<input type="hidden" name="id_company" value="' +
                                    data + '">' +
                                    '<button type="submit" title="Activate" class="button is-acciones is-rounded is-small">' +
                                    '<i class="fas fa-check-circle"></i>' +
                                    '</button>' +
                                    '</form>';
                            } else {
                                cadena = cadena +
                                    '<form class="FormularioAcciones" action="<?php  echo APP_URL?>ajax/companyAjax.php" method="POST" autocomplete="off" >' +
                                    '<input type="hidden" name="modulo_company" value="inactivar">' +
                                    '<input type="hidden" name="id_company" value="' +
                                    data + '">' +
                                    '<button type="submit" title="Inactivate" class="button is-acciones is-rounded is-small">' +
                                    '<i class="fas fa-times-circle"></i>' +
                                    '</button>' +
                                    '</form>';
                            }
                            cadena = cadena + '</div>';
                            cadena = cadena + '</div></td>';
                            return cadena;

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