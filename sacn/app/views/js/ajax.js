/* Enviar formularios via AJAX */

/* Boton cerrar sesion */
const urlsistema = document.getElementsByName("urlsistema");
var btn_exit = document.querySelectorAll(".btn-exit");

btn_exit.forEach(exitSystem => {
    exitSystem.addEventListener("click", function (e) {

        e.preventDefault();

        Swal.fire({
            title: '¿Quieres salir del sistema?',
            text: "La sesión actual se cerrará y saldrás del sistema",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6e7881',
            confirmButtonText: 'Si, salir',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                let url = this.getAttribute("href");
                window.location.href = url;
            }
        });

    });
});

var formularios_ajax = document.querySelectorAll(".FormularioAjax");

formularios_ajax.forEach(formularios => {

    formularios.addEventListener("submit", function (e) {

        e.preventDefault();

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
                $(".loadersacn")[0].style.display = "";
                let data = new FormData(this);
                let method = this.getAttribute("method");
                let action = this.getAttribute("action");

                let encabezados = new Headers();

                let config = {
                    method: method,
                    headers: encabezados,
                    mode: 'cors',
                    cache: 'no-cache',
                    body: data
                };

                fetch(action, config)
                    .then(
                        response => {
                            devuelto = response.clone();
                            return response.json();
                        }).then(
                            data => {
                                $(".loadersacn").fadeOut("slow")
                                return alertas_ajax(data)
                            },
                            error => {

                                console.log("Error de JSON: ");
                                return devuelto.text().then(
                                    error => {
                                        Swal.fire({
                                            icon: "error",
                                            title: "Error",
                                            text: error,
                                            confirmButtonText: 'Aceptar'
                                        });
                                        $.ajax({
                                            type: "GET",
                                            url: urlsistema[0].value,
                                            data: "error=" + error.replaceAll("\n", "%26").replaceAll("\t", "%26").replaceAll("&", " "),
                                            processData: false,
                                            contentType: false,
                                            success: function (response) {

                                            }
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
function cargarfunciones() {

    $(document).on('submit', '.FormularioAjax', function (e) {
        e.preventDefault();

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
                $(".loadersacn")[0].style.display = "";
                let data = new FormData(this);
                let method = this.getAttribute("method");
                let action = this.getAttribute("action");

                let encabezados = new Headers();

                let config = {
                    method: method,
                    headers: encabezados,
                    mode: 'cors',
                    cache: 'no-cache',
                    body: data
                };

                fetch(action, config)
                    .then(
                        response => {
                            devuelto = response.clone();
                            return response.json();
                        }).then(
                            data => {
                                $(".loadersacn").fadeOut("slow")
                                return alertas_ajax(data)
                            },
                            error => {
                                
                                console.log("Error de JSON: ");
                                return devuelto.text().then(
                                    error => {
                                        Swal.fire({
                                            icon: "error",
                                            title: "Error",
                                            text: error,
                                            confirmButtonText: 'Aceptar'
                                        });
                                        $.ajax({
                                            type: "GET",
                                            url: urlsistema[0].value,
                                            data: "error=" +error.replaceAll("\n", "%26").replaceAll("\t", "%26").replaceAll("&", " "),
                                            processData: false,
                                            contentType: false,
                                            success: function (response) {
        
                                            }
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




function alertas_ajax(alerta) {
    // localStorage.clear();
    // localStorage.setItem('alerta', []);
    // localStorage.setItem('alerta', JSON.stringify(alerta));
    if (alerta.tipo == "simple") {

        Swal.fire({
            icon: alerta.icono,
            title: alerta.titulo,
            text: alerta.texto,
            confirmButtonText: 'Aceptar'
        });

    } else if (alerta.tipo == "recargar") {

        Swal.fire({
            icon: alerta.icono,
            title: alerta.titulo,
            text: alerta.texto,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed) {
                location.reload();
            }
        });

    } else if (alerta.tipo == "limpiar") {

        Swal.fire({
            icon: alerta.icono,
            title: alerta.titulo,
            text: alerta.texto,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed) {

                if (document.querySelector(".FormularioAjax").attributes["name"] != undefined) {

                    switch (document.querySelector(".FormularioAjax").attributes["name"].value) {
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
                        default:
                            document.querySelector(".FormularioAjax").reset();
                            cargargrid();
                    }
                }
                else {
                    document.querySelector(".FormularioAjax").reset();
                    cargargrid();
                }

            }
        });

    } else if (alerta.tipo == "redireccionar") {
        window.location.href = alerta.url;
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



