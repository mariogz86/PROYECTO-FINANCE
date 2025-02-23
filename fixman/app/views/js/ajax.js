/* Enviar formularios via AJAX */

    /* Boton cerrar sesion */
var btn_exit=document.querySelectorAll(".btn-exit");

btn_exit.forEach(exitSystem => {
    exitSystem.addEventListener("click", function(e){

        e.preventDefault();
        
        Swal.fire({
            title: 'Do you want to log out of the system?',
            text: "The current session will be closed and you will log out of the system",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6e7881',
            confirmButtonText: 'go out',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                let url=this.getAttribute("href");
                window.location.href=url;
            }
        });

    });
});

    var formularios_ajax=document.querySelectorAll(".FormularioAjax");

    formularios_ajax.forEach(formularios => {
    
        formularios.addEventListener("submit",function(e){
            
            e.preventDefault();
    
            Swal.fire({
                title: 'You are sure?',
                text: "You want to perform the requested action",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, perform',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed){
                    $(".loadersacn")[0].style.display = "";
                    let data = new FormData(this);
                    let method=this.getAttribute("method");
                    let action=this.getAttribute("action");
    
                    let encabezados= new Headers();
    
                    let config={
                        method: method,
                        headers: encabezados,
                        mode: 'cors',
                        cache: 'no-cache',
                        body: data
                    };
    
                    fetch(action,config)
                    .then(
                        response=>{
                           devuelto = response.clone();
                           return response.json();
                        }).then(
                            data=> {
                               $(".loadersacn").fadeOut("slow")
                               return alertas_ajax(data)},
                            error=>{
                              
                                console.log("Error de JSON: ");
                                return devuelto.text().then(
                                       error=>{
                                           Swal.fire({
                                               icon: "error",
                                               title: "Error",
                                               text: error,
                                               confirmButtonText: 'Accept'
                                           });
                                           $(".loadersacn").fadeOut("slow");
                                               console.log(error);
                                              }
                                         )
                              });
                    // .then(respuesta => respuesta.json())
                    // .then(respuesta =>{ 
                    //     $(".loadersacn").fadeOut("slow"); 
                    //     return alertas_ajax(respuesta);
                    // });
                }
            });
    
        });
    
    });
    function cargarfunciones(){

        $(document).on('submit', '.FormularioAjax, .FormularioAjax2, .FormularioAcciones', function (e) {
            e.preventDefault();
    
            Swal.fire({
                title: 'You are sure?',
                text: "You want to perform the requested action",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, perform',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed){
                    $(".loadersacn")[0].style.display = "";
                    let data = new FormData(this);
                    let method=this.getAttribute("method");
                    let action=this.getAttribute("action");
    
                    let encabezados= new Headers();
    
                    let config={
                        method: method,
                        headers: encabezados,
                        mode: 'cors',
                        cache: 'no-cache',
                        body: data
                    };
    
                    fetch(action,config)
                    .then(
                        response=>{
                           devuelto = response.clone();
                           return response.json();
                        }).then(
                             data=> {
                                $(".loadersacn").fadeOut("slow")
                                return alertas_ajax(data)},
                             error=>{
                               
                                 console.log("Error de JSON: ");
                                 return devuelto.text().then(
                                        error=>{
                                            Swal.fire({
                                                icon: "error",
                                                title: "Error",
                                                text: error,
                                                confirmButtonText: 'Aceptar'
                                            });
                                            $(".loadersacn").fadeOut("slow");
                                                console.log(error);
                                               }
                                          )
                               });
                    // .then(respuesta => respuesta.json())
                    // .then(respuesta =>{ 
                    //     $(".loadersacn").fadeOut("slow"); 
                    //     return alertas_ajax(respuesta);
                    // });
                }
            });
    
        });
}




function alertas_ajax(alerta){
    // localStorage.clear();
    // localStorage.setItem('alerta', []);
    // localStorage.setItem('alerta', JSON.stringify(alerta));
    if(alerta.tipo=="simple"){

        Swal.fire({
            icon: alerta.icono,
            title: alerta.titulo,
            text: alerta.texto,
            confirmButtonText: 'Accept'
        });

    }else if(alerta.tipo=="recargar"){

        Swal.fire({
            icon: alerta.icono,
            title: alerta.titulo,
            text: alerta.texto,
            confirmButtonText: 'Accept'
        }).then((result) => {
            if(result.isConfirmed){
                location.reload();
            }
        });

    }else if(alerta.tipo=="limpiar"){

        Swal.fire({
            icon: alerta.icono,
            title: alerta.titulo,
            text: alerta.texto,
            confirmButtonText: 'Accept'
        }).then((result) => {
            if(result.isConfirmed){
                
                if (document.querySelector(alerta.classform).attributes["name"] != undefined){
                   
                    switch (document.querySelector(alerta.classform).attributes["name"].value) {
                        case "formvariable":
                            cargargrid_post();
                          break;
                        case "formvalidacion":
                          cargarformularioparametro(alerta);
                          break;  
                          case "formcarga":
                            cargardatos(alerta);
                          break;   
                          case "formvercarga":
                            cargardatos(alerta);
                          break; 
                          case "accionservicio":
                            cargaformularioservicio(0);
                          break;  
                          case "formservice":
                            cargaformularioservicio(1);
                          break;                         
                        default:
                            document.querySelector(".FormularioAjax").reset();
                            cargargrid();
                      }
                }
                else{
                 

                    switch (alerta.classform) {
                       
                          case ".FormularioAcciones":
                            cargaformularioservicio(0);
                          break;                         
                        default:
                            document.querySelector(".FormularioAjax").reset();
                            cargargrid();
                      }
                }
                
            }
        });

    }else if(alerta.tipo=="redireccionar"){
        window.location.href=alerta.url;
    }
}

function limpiarcache() {
    // Clear Cache
    if ('caches' in window) {
      caches.keys().then((names) => {
        names.forEach((name) => {
          caches.delete(name);
        });
      });
    }
  
    // Clear localStorage
    localStorage.clear();
  
    // Clear sessionStorage
    sessionStorage.clear();
  }
  


cargarfunciones();



