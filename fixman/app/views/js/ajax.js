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

    var formularios_ajax=document.querySelectorAll(".FormularioAjax, .FormularioAjax2, .FormularioAjax3, .FormularioAjax4, .FormularioAjax5, .FormularioAjax6, .FormularioAjax7, .FormularioAcciones, .FormularioAccionespartes, .Formularioreporteservicio, .FormularioAjaxjob");

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

        $(document).on('submit', '.FormularioAjax, .FormularioAjax2, .FormularioAjax3, .FormularioAjax4, .FormularioAjax5, .FormularioAjax6, .FormularioAjax7, .FormularioAcciones, .FormularioAccionespartes, .Formularioreporteservicio, .FormularioAjaxjob', function (e) {
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
                           case "formreporteservicio":
                            cargaformularioreporteservicio(1);
                          break;
                          case "accionreporteservicio":
                            cargaformularioreporteservicio(0);
                          break;   
                          case "formcita":
                            quedarenpantalla(alerta);
                          break;    
                          case "formpago":
                            quedarenpantalla(alerta);
                          break;       
                          case "formmovimiento":
                            cargargridmovimiento_save(alerta);
                          break;       
                          case "formdiagnostico":
                            quedarenmodaldiagnostico(alerta);
                          break;
                          case "formpartes":
                            quedarenmodalparte(alerta);
                          break;   
                          case "accionpartes":
                            quedarenmodalparte(alerta);
                          break;   
                          case "FormularioAjaxjob":
                            cargajob(alerta.idjob);
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
  
//PARA LA ACTIVACION DEL MENU SELECCIONADO  
document.addEventListener('DOMContentLoaded', function () {
    // Detectar clics en botones que abren submenús
    const subMenuButtons = document.querySelectorAll('.btn-subMenu');

    subMenuButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault(); // evitar navegación si es necesario

            // Aquí podrías usar un atributo o ID único
            const menuText = this.querySelector('.navLateral-body-cr')?.textContent.trim();

            if (menuText) {
                localStorage.setItem('menuSeleccionado', menuText);
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    // Detectar clics en subopciones
    document.querySelectorAll('#navLateral a[id]').forEach(link => {
        link.addEventListener('click', function () {
            const subopcionId = this.id;
            localStorage.setItem('subopcionActiva', subopcionId);
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const menuGuardado = localStorage.getItem('menuSeleccionado');

    if (menuGuardado) {
        const subMenuButtons = document.querySelectorAll('.btn-subMenu');

        subMenuButtons.forEach(button => {
            const menuText = button.querySelector('.navLateral-body-cr')?.textContent.trim();

            if (menuText === menuGuardado) {
                // Aquí agregas clases para "abrir" el submenú
                const subMenu = button.parentElement.querySelector('.sub-menu-options');
                if (subMenu) {
                    subMenu.style.display = 'block'; // o `.classList.add('open')` si usas clases
                }

                button.classList.add('btn-subMenu-show'); // si quieres resaltar el botón
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const subopcionActiva = localStorage.getItem('subopcionActiva');

    if (subopcionActiva) {
        const elementoActivo = document.getElementById(subopcionActiva);

        if (elementoActivo) {
            // Aplicamos clase 'active'
            elementoActivo.classList.add('activarsubopcion');

            // También puedes abrir el submenú automáticamente si está dentro de uno
            // const subMenu = elementoActivo.closest('.sub-menu-options');
            // if (subMenu) {
            //     subMenu.style.display = 'block'; // o usa classList.add('open')
            // }

            // // Si quieres resaltar también el menú principal padre
            // const menuBtn = subMenu?.previousElementSibling;
            // if (menuBtn?.classList.contains('btn-subMenu')) {
            //     menuBtn.classList.add('active');
            // }
        }
    }
});

cargarfunciones();



