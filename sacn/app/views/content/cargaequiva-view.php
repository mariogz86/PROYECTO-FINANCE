<style>
.has-text-centered {
    text-align: center !important;

}

p {
    margin-top: 5px !important;

    margin-bottom: 1rem !important;
}

.col-md-65 {
    flex: 0 0 44.66667%;
    max-width: 50.66667%;
}

.fileexcel {

    padding: 5px 5px 5px 5px;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    background-color: #cce5ff;
    background-clip: padding-box;
    border: 1px solid #181818;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-radius: .25rem;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}
</style>
<?php

use app\controllers\FuncionesController;

$insrol = new FuncionesController();
?>
<div class="container">
    <?php

   
ini_set('max_execution_time', '0'); 
ini_set('post_max_size', '100M'); 
set_time_limit(0);
    //ini_set('memory_limit', '1024'); 

    ?>
    <h1 class="title">Carga de Equivalencias</h1>

    <h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; <label id="titulo"></label></h2>
 

    <div name="gridcat">



        <div class="col-sm-12 col-md-12">
            <div class="columns">
               
                <div class="col-md-6.5">
                    <div class="control ">
                        <form name="cambionombre"  
                            action="<?php echo APP_URL; ?>ajax/validacionAjax.php?guardarcarga=excel" method="POST"
                            autocomplete="off" enctype="multipart/form-data">
                            <input class="fileexcel" required type="file" id="fileUpload" name="file"
                                accept=".xls,.xlsx">


                            <div class="mt-1" style="width: 25%; float: right;;"><button type="submit" id="uploadExcel"
                                    class="button is-link is-rounded btn-back" name="submit"><i
                                        class="far fa-file-excel"></i>
                                    &nbsp;Procesar excel</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <table id="myTable" class="table table-striped table-bordered">

        </table>

        

    </div>
    
 





</div>

<script>
const gridcat = document.getElementsByName("gridcat");  
  

gridcat[0].style.display = "none"; 
$("#titulo")[0].innerText =
    "Esta pantalla permite cargar en sistema las equivalencias de los productos e insumos. ";
//proceso para cargar validaciones desde archivo excel
var selectedFile;
document
    .getElementById("fileUpload")
    .addEventListener("change", function(event) {
        selectedFile = event.target.files[0];
    });
document
    .getElementById("uploadExcel")
    .addEventListener("click", function(e) {

        if (selectedFile) {
            limpiarcache();
            event.preventDefault();
            $(".loadersacn")[0].style.display = "";
            var fileReader = new FileReader();
            fileReader.onload = function(event) {
                var data = event.target.result;

                var workbook = XLSX.read(data, {
                    type: "binary"
                });

                let validaciones = XLSX.utils.sheet_to_row_object_array(
                    workbook.Sheets["equivalencias"]
                );

                if (validaciones.length == 0) {
                    $(".loadersacn").fadeOut("slow");
                    Swal.fire({
                        icon: "warning",
                        title: "Carga",
                        text: "El archivo Excel no es el correcto, por favor validar.",
                        confirmButtonText: 'Aceptar'
                    });
                } else {


                    let jsonvalidaciones = JSON.stringify(validaciones).replaceAll("&", "%26").replaceAll("'", "''");
                    let encodedData = encodeURIComponent(jsonvalidaciones);

                  

                    //limpiamos el file
                    document.getElementById("fileUpload").value = "";
                    selectedFile = null;
                    //enviamos los datos al server para ser procesados y guardar en la base de datos
                    $.ajax({
                        type: "POST",
                        url: "<?php echo APP_URL . 'ajax/cargaequivaAjax.php' ?>",
                        data: "guardarcarga=excel&validaciones=" + encodedData,
                        success: function(response) {
                            $(".loadersacn").fadeOut("slow");
                            var res = jQuery.parseJSON(response);

                            if (res.status == 200) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Carga",
                                    text: "Carga realizada con éxito",
                                    confirmButtonText: 'Aceptar'
                                });

                                cargargrid();


                            } else {

                                if (res.status == 500) {
                                    Swal.fire({
                                        icon: "warning",
                                        title: "Carga",
                                        text: "Carga con error en parametros, por favor validar",
                                        confirmButtonText: 'Aceptar'
                                    });

                                    cargargridparametroserror(res.data);

                                }

                                if (res.status == 404) {
                                    Swal.fire({
                                        icon: "warning",
                                        title: "Carga",
                                        text: "Error al guardar datos, consulte con el administrador",
                                        confirmButtonText: 'Aceptar'
                                    });
                                }


                            }


                        }
                    });
                }

            };
            fileReader.readAsBinaryString(selectedFile);
        }
    });

 

$(document).ready(function() {
    limpiarcache();

});

 
 
 
 

function cargargrid() {
    $(".loadersacn")[0].style.display = "";
    

    gridcat[0].style.display = "";  
    $.ajax({
        type: "GET",
        url: "<?php echo APP_URL . 'ajax/cargaequivaAjax.php?cargagrid' ?>",
        success: function(response) {
            $(".loadersacn").fadeOut("slow");
            var res = jQuery.parseJSON(response);
            var datos = [];

            if (res.status == 200) {
                datos = res.data;
            }

            $('#myTable').DataTable({
                data: datos,
                language: {
                    "url": "<?php echo APP_URL ?>config/Spanish.json"
                },
                stateSave: true,
                //searching: false, 
                destroy: true,
                responsive: true,
              
                columns: [
                    {
                        title: 'id_carga',
                        className: "text-center",
                        data: 'id_carga',
                        visible: false,
                    },  
                    {
                        //width: "30%",
                        title: 'Formulario',
                        data: 'formulario',

                    },
                    {
                        //width: "30%",
                        title: 'Archivo',
                        data: 'archivo',

                    },
                    {
                        //width: "30%",
                        title: 'Hoja',
                        data: 'hoja',
                        className: 'dt-center',

                    },
                    {
                        //width: "50%",
                        title: 'Descripcion',
                        data: 'descripcion', 
                    }, 
                    {
                        //width: "",
                        title: 'Codigo INIDE',
                        data: 'codinide', 
                    }, 
                    {
                      //  width: "50%",
                        title: 'Resultado INIDE',
                        data: 'valorinide', 
                    }, 
                    {
                      //  width: "50%",
                        title: 'Codigo Nacional',
                        data: 'codnacional', 
                    }, 
                    {
                        //width: "50%",
                        title: 'Resultado Codigo Nacional',
                        data: 'valorcodnacional', 
                    }, 
                    
                    
                    
                    
                
                    // {
                    //     className: "text-center",
                    //     title: 'Editar',
                    //     data: 'id_validacion',
                    //     render: function(data, type, row, meta) {
                    //         return '<td><a id="modificar" title="Editar" href="#" class="button is-info is-rounded is-small" valor="' +
                    //             meta.row + '">' +
                    //             '<i class="fas fa-sync fa-fw"></i></a> </td>';
                    //     }

                    // },
                    // {
                    //     className: "text-center",
                    //     title: 'Acción',
                    //     data: 'id_validacion',
                    //     render: function(data, type, row, meta) {

                    //         if (row.u_estado == 0) {
                    //             return '<td><form class="FormularioAjax" action="<?php echo APP_URL ?>ajax/validacionAjax.php" method="POST" autocomplete="off" >' +
                    //                 '<input type="hidden" name="accion" value="activar">' +
                    //                 '<input type="hidden" name="id_validacion" value="' +
                    //                 data + '">' +
                    //                 '<button type="submit" title="Activar" class="button is-acciones is-rounded is-small">' +
                    //                 '<i class="fas fa-check-circle"></i>' +
                    //                 '</button>' +
                    //                 '</form></td>';
                    //         } else {
                    //             return '<td><form class="FormularioAjax" action="<?php echo APP_URL ?>ajax/validacionAjax.php" method="POST" autocomplete="off" >' +
                    //                 '<input type="hidden" name="accion" value="inactivar">' +
                    //                 '<input type="hidden" name="id_validacion" value="' +
                    //                 data + '">' +
                    //                 '<button type="submit" title="Inactivar" class="button is-acciones is-rounded is-small">' +
                    //                 '<i class="fas fa-times-circle"></i>' +
                    //                 '</button>' +
                    //                 '</form></td>';
                    //         }

                    //     }

                    // },
                ],
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
                },
            });



        }
    });
}
cargargrid();
</script>