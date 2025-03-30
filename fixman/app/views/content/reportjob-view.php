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
    <h1 class="title">Report Jobs</h1>

    <h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; <label id="titulo"></label></h2>

    <div class="col-sm-12 col-md-12">
        <div class="columns">
            <div class="column">
                <div class="control ">
                    <label>start date<?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input id="select_fechainicio" name="fechainicio" class="form-control" type="date" required>
                </div>
            </div>

            <div class="column">
                <div class="control ">
                    <label>end date<?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input id="select_fechafin" name="fechafin" class="form-control" type="date" required>
                </div>
            </div>
            <div class="column">
                <div class="control" style="padding-top: 35px;">
                    <button name="btnreporte" type="submit" class="button is-info is-rounded"><i
                            class="far fa-save"></i> &nbsp; By date range</button>
                </div>
            </div>
        </div>
    </div>

    <div name="gridcat">
        <table id="myTable" class="table table-striped table-bordered">

        </table>
        <br>


    </div>

</div>

<script>
const gridcat = document.getElementsByName("gridcat");
const btnreporte = document.getElementsByName("btnreporte");

$(document).ready(function() {
    $('#datepicker, #datepicker2').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });
});
btnreporte[0].addEventListener("click", (event) => {
    event.preventDefault();
    if ($("#select_fechainicio")[0].value == '') {
        Swal.fire({
            icon: "warning",
            title: "Date",
            text: "Select the start date",
            confirmButtonText: 'Accept'
        });
    } else {
        if ($("#select_fechafin")[0].value == '') {
            Swal.fire({
                icon: "warning",
                title: "Date",
                text: "Select the end date",
                confirmButtonText: 'Accept'
            });
        } else {
            $(".loadersacn")[0].style.display = "";
            $.ajax({
                type: "GET",
                url: "<?php echo APP_URL . 'ajax/reportjobAjax.php?cargagridfecha=' ?>"+$("#select_fechainicio")[0].value+"&fechafin="+$("#select_fechafin")[0].value,
                success: function(response) {
                    $(".loadersacn").fadeOut("slow");
                    var res = jQuery.parseJSON(response);
                    if(res.status=="200"){
                        llenardatos(res.data);
                    }else{
                        llenardatos([]);
                    }
                   
                }
            });
        }
    }

});



function cargargrid() {
    $(".loadersacn")[0].style.display = "";
    $("#titulo")[0].innerText = "job list";

    gridcat[0].style.display = "";


    $.ajax({
        type: "GET",
        url: "<?php echo APP_URL . 'ajax/reportjobAjax.php?cargagrid' ?>",
        success: function(response) {
            $(".loadersacn").fadeOut("slow");
            var res = jQuery.parseJSON(response);
            llenardatos(res.data)
        }
    });
}


function llenardatos(data) {
    $('#myTable').DataTable({
        layout: {
            topStart: {
                buttons: [{
                    extend: 'excel',
                    text: 'download excel'
                }]
            }
        },
        data: data,
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
                // width: "30%",
                title: 'Reference Number',
                className: "text-center",
                data: 'num_referencia',
            },
            {
                //  width: "30%",
                title: 'Company Name',
                className: "text-center",
                data: 'company',
            },
            {
                // width: "20%",
                title: 'Client',
                data: 'full_name',

            },
            {
                // width: "30%",
                title: 'Pay',
                className: "text-center",
                data: 'pay',
            },
            {
                // width: "30%",
                title: 'Technical',
                className: "text-center",
                data: 'tecnico',
            },

            {
                //width: "30%",
                title: 'Total Service',
                data: 'totalservice',
                render: $.fn.dataTable.render.number(',', '.', 2)

            },
            {
                //width: "30%",
                title: 'Total labor',
                data: 'totallabor',
                render: $.fn.dataTable.render.number(',', '.', 2)

            },
            {
                //width: "30%",
                title: 'Total parts',
                data: 'totalparte',
                render: $.fn.dataTable.render.number(',', '.', 2)

            },
            {
                // width: "30%",
                title: 'Total invoice',
                data: 'totalfactura',
                render: $.fn.dataTable.render.number(',', '.', 2)

            },
            {
                // width: "30%",
                title: 'Jobs state',
                data: 'estadojob',

            },
            {
                // width: "30%",
                title: 'Date',
                data: 'fecha_creacion',

            },
            {
                // width: "30%",
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

        ],
        // order: [
        //     [2, 'asc']
        // ],
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
cargargrid();
</script>