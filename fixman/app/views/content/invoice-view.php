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
        <table id="myTable" class="table table-striped table-bordered">

        </table>
        <br>


    </div>

</div>

<script>
$(document).ready(function() {

});

const gridcat = document.getElementsByName("gridcat");

$(document).on('click', '#factura', function(e) {

    event.preventDefault();
    var row = e.currentTarget.attributes['valor'].value;
    var dato = $("#myTable").DataTable().data()[row];
    //para ocultar
    //$("#modalvalidaciones").modal("hide");

    $.ajax({
        type: "POST",
        url: "<?php echo APP_URL . 'ajax/invoiceAjax.php' ?>",
        data: "generarfactura=" + dato.id_trabajo,
        xhrFields: {
            responseType: 'blob' // Importante para manejar archivos binarios
        },
        success: function(data) {

            // Crear una URL para el blob y abrirla en una nueva pestaña
            var blob = new Blob([data], {
                type: 'application/pdf'
            });
            var url = window.URL.createObjectURL(blob);
            window.open(url);


        }
    })


});

$(document).on('click', '#reporte', function(e) {

    event.preventDefault();
    var row = e.currentTarget.attributes['valor'].value;
    var dato = $("#myTable").DataTable().data()[row];
    //para ocultar
    //$("#modalvalidaciones").modal("hide");

    $.ajax({
        type: "POST",
        url: "<?php echo APP_URL . 'ajax/invoiceAjax.php' ?>",
        data: "generarreporte=" + dato.id_trabajo,
        xhrFields: {
            responseType: 'blob' // Importante para manejar archivos binarios
        },
        success: function(data) {

            // Crear una URL para el blob y abrirla en una nueva pestaña
            var blob = new Blob([data], {
                type: 'application/pdf'
            });
            var url = window.URL.createObjectURL(blob);
            window.open(url);


        }
    })


});

$(document).on('click', '#enviarfactura', function(e) {

    event.preventDefault();
    var row = e.currentTarget.attributes['valor'].value;
    var dato = $("#myTable").DataTable().data()[row];
    //para ocultar
    //$("#modalvalidaciones").modal("hide");
    $(".loadersacn")[0].style.display = "";
    $.ajax({
        type: "POST",
        url: "<?php echo APP_URL . 'ajax/invoiceAjax.php' ?>",
        data: "enviarfactura=" + dato.id_trabajo,
        success: function(data) {
            var res = jQuery.parseJSON(data);
            if (res.status == 200) {
                $(".loadersacn").fadeOut("slow");
                Swal.fire({
                    icon: 'info',
                    title: 'Send E-mail',
                    text: 'Mail sent successfully',
                    confirmButtonText: 'Accept'
                });

            }



        }
    })


});

$(document).on('click', '#enviarreporte', function(e) {

    event.preventDefault();
    var row = e.currentTarget.attributes['valor'].value;
    var dato = $("#myTable").DataTable().data()[row];
    //para ocultar
    //$("#modalvalidaciones").modal("hide");
    $(".loadersacn")[0].style.display = "";
    $.ajax({
        type: "POST",
        url: "<?php echo APP_URL . 'ajax/invoiceAjax.php' ?>",
        data: "enviarreporte=" + dato.id_trabajo,
        success: function(data) {
            var res = jQuery.parseJSON(data);
            if (res.status == 200) {
                $(".loadersacn").fadeOut("slow");
                Swal.fire({
                    icon: 'info',
                    title: 'Send E-mail',
                    text: 'Mail sent successfully',
                    confirmButtonText: 'Accept'
                });

            }



        }
    })


});





function cargargrid() {
    $(".loadersacn")[0].style.display = "";
    $("#titulo")[0].innerText = "job list";

    gridcat[0].style.display = "";


    $.ajax({
        type: "GET",
        url: "<?php echo APP_URL . 'ajax/invoiceAjax.php?cargagrid' ?>",
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
                                    '<div style="margin: 2px;"><a id="reporte" title="Generate Report" href="#" class="button is-reporte is-rounded is-small" valor="' +
                                    meta.row + '">' +
                                    '<i class="fas fa-file-medical-alt"></i></a></div> ';
                                      cadena = cadena +
                                    '<div style="margin: 2px;"><a id="factura" title="Generate Invoice" href="#" class="button is-factura is-rounded is-small" valor="' +
                                    meta.row + '">' +
                                    '<i class="fas fa-file-medical-alt"></i></a></div> ';
                                    <?php
                                    if($_SESSION['rol']=='Administrator'){
                                        echo "cadena = cadena +
                                        '<div style=\"margin: 2px;\"><a id=\"enviarfactura\" title=\"Send Invoice\" href=\"#\" class=\"button is-mail is-rounded is-small\" valor=\"' +
                                        meta.row + '\">' +
                                        '<i class=\"far fa-paper-plane\"></i></a></div> ';
                                        cadena = cadena +
                                        '<div style=\"margin: 2px;\"><a id=\"enviarreporte\" title=\"Send Report\" href=\"#\" class=\"button is-mailreport is-rounded is-small\" valor=\"' +
                                        meta.row + '\">' +
                                        '<i class=\"far fa-paper-plane\"></i></a></div> ';";
                                    }
                           
                               
                                ?>
                                 cadena = cadena +'</div></li></td>';

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