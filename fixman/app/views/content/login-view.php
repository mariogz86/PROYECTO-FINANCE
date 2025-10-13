<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fixman Login</title> 
    <link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('app/views/img/fondo4.jpg');
            background-size: cover;
            background-position: center;
            backdrop-filter: blur(3px);
            min-height: 100vh;
        }

        .bg-blur {
                background-color: rgb(81 91 101 / 39%);
            border-radius: 12px;
        }

        .text-colortitulo3 {
            color: #d4af37;
            /* dorado */
        }

        .icons-row img {
            margin: 0 10px;
        }

        @media (max-width: 767.98px) {
            .left-section {
                text-align: center;
            }
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center">

    <div class="container py-5">
        <div class="row shadow-lg bg-blur p-4 align-items-center justify-content-center">

            <!-- Sección izquierda: Título + íconos -->
            <div class="text-center col-12 col-md-6 left-section mb-4 mb-md-0">
                <p class="fs-1 fw-bold text-colortitulo3" style="font-family: 'Futura Md BT', sans-serif;">FIXMAN</p>
                <p class="fs-1 fw-bold text-colortitulo3" style="font-family: 'Futura Md BT', sans-serif;">APPLIANCES</p>
                <p class="fs-1 fw-bold text-white" style="font-family: 'Futura Md BT', sans-serif;">REPAIR SERVICES</p>

                <!-- Íconos -->
                <div class="icons-row mt-4">
                    <img src="app/views/img/refri1.png" style="height: 250px;" alt="Refrigerator">
                    <img src="app/views/img/cocina1.png" style="height: 250px;" alt="Stove">
                    <img src="app/views/img/lavadora1.png" style="height: 250px;" alt="Washing Machine">
                </div>

                <p class="font-italic text-white mt-5">
                    Copyright &copy; <span class="js-year-copy">2025</span>
                </p>
            </div>

            <!-- Sección derecha: Login -->
            <div class="col-12 col-md-5">
                <div class="card p-4 shadow-sm border-0">
                    <div class="text-center mb-3">
                        <i class="bi bi-person-circle" style="font-size: 4rem; color: cornflowerblue;"></i>
                        <h5 class="mt-2">Credentials</h5>
                    </div>
                    <form name="formlogin" class="box2 login" action="" method="POST" autocomplete="off"> 
                        <?php
                        if (isset($_POST['login_usuario']) && isset($_POST['login_clave'])) {
                            $insLogin->iniciarSesionControlador();
                        } else {


                            if (isset($_POST['clave_anterior']) && isset($_POST['clave_nueva'])) {
                                $insLogin->cambiarclave();
                            } else {
                                $_SESSION['cambioclave'] = "0";
                            }
                        }
                        ?>
                        <?php if ($_SESSION['cambioclave'] == "0") { ?> 
                            <div class="field">
                                <label class="label"><i class="fas fa-user-secret"></i> &nbsp; User</label>
                                <div class="control">
                                    <input class="input" type="text" name="login_usuario" maxlength="20" required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label"><i class="fas fa-key"></i> &nbsp; Password</label>
                                <div class="control">
                                    <input class="input" type="password" name="login_clave" maxlength="100"
                                        required>
                                </div>
                            </div>

                            <p class="has-text-centered mb-4 mt-3">
                                <button type="submit" class="button is-info is-rounded" id="btnlogin">
                                    Start
                                    session</button>
                            </p>

                            <p class="has-text-centered">
                                <a name="linkreset" class="link-effect font-w700" href="">
                                    <i class="si si-key"></i>
                                    <span class="title is-5 has-text-centered text-primary-dark">If you forget your
                                        password
                                        Click
                                        here...</span>
                                </a>
                            </p>
                        <?php } else if ($_SESSION['cambioclave'] == "1") { ?>
                            <h5 class="title is-5 has-text-centered">Enter your details below: </h5>
                            <div class="field">
                                <label class="label"><i class="fas fa-key"></i> &nbsp; Old Password</label>
                                <div class="control">
                                    <input class="input" type="password" name="clave_anterior" maxlength="100"
                                        required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label"><i class="fas fa-key"></i> &nbsp; New Password</label>
                                <div class="control">
                                    <input class="input" type="password" name="clave_nueva" maxlength="100"
                                        required>
                                </div>
                            </div>

                            <p class="has-text-centered mb-4 mt-3">
                                <button type="submit" class="button is-info is-rounded" id="btnlogin">Save</button>
                            </p>
                        <?php } else { ?>
                            <p class="has-text-centered">
                                <a name="linklogin" class="link-effect font-w700" href="">
                                    <i class="si si-user"></i>
                                    <span class="title is-5 has-text-centered text-primary-dark">Start
                                        session</span>
                                </a>
                            </p>

                        <?php }  ?>


                    </form>

                    <!-- Formulario resetear contraseña -->
                    <form name="formreset" class="box login" action="" method="POST" autocomplete="off"
                        style="display:none">
                      
                        <h5 class="title is-5 has-text-centered"> Reset Password</h5>

                        <?php
                        if (isset($_POST['usuario_email'])) {
                            $insLogin->resetearcontraseña();
                        }
                        ?>
                        <div id="mensaje">

                        </div>

                        <div class="field">
                            <label class="label"><i class="fas fa-envelope"></i> &nbsp; mail</label>
                            <div class="control">
                                <input class="input" type="email" name="usuario_email" maxlength="70" required>
                            </div>
                        </div>



                        <p class="has-text-centered mb-4 mt-3">
                            <button type="submit" class="button is-info is-rounded"
                                id="btnreset">Reset</button>
                        </p>

                        <p class="has-text-centered">
                            <a name="linklogin" class="link-effect font-w700" href="">
                                <i class="si si-user"></i>
                                <span class="title is-5 has-text-centered text-primary-dark">Start
                                    session</span>
                            </a>
                        </p>

                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS + Icons -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="<?php echo APP_URL; ?>app/views/js/bootstrap.bundle.min.js"></script> 
    <link rel="stylesheet" href="<?php echo APP_URL; ?>app/views/css/bootstrap-icons.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" /> -->
    <!-- <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> -->
</body>

</html>

 <script>
    
    localStorage.removeItem('subopcionActiva');
    localStorage.removeItem('menuSeleccionado');
     const button = document.getElementsByName("linkreset");
     const buttonreset = document.getElementsByName("linklogin");
     const correo = document.getElementsByName("usuario_email");

     const formulariologin = document.getElementsByName("formlogin");
     const formularioreset = document.getElementsByName("formreset");


     $(document).on('click', '#btnlogin', function(e) {
         const usuario = document.getElementsByName("login_usuario");
         const clave = document.getElementsByName("login_clave");
         if (usuario[0].value.trim() != "" && clave[0].value.trim() != "") {
             $(".loadersacn")[0].style.display = "";
         }

     });

     $(document).on('click', '#btnreset', function(e) {

         if (correo[0].value.trim() != "") {
             $(".loadersacn")[0].style.display = "";
             e.preventDefault();
             $.ajax({
                 type: "GET",
                 url: "ajax/loginAjax.php?correo=" + correo[0].value,
                 success: function(response) {

                     var res = jQuery.parseJSON(response);

                     if (res.status == 404) {

                         document.getElementById("mensaje").innerHTML = res.message;
                     } else {

                         document.getElementById("mensaje").innerHTML = res.message;
                     }
                     $(".loadersacn").fadeOut("slow");

                 }
             });
         }

     });

     button[0].addEventListener("click", (event) => {
         event.preventDefault();
         formulariologin[0].style.display = "none";
         formularioreset[0].style.display = "";

         limpiartextos();
     });

     buttonreset[0].addEventListener("click", (event) => {
         event.preventDefault();
         formulariologin[0].style.display = "";
         formularioreset[0].style.display = "none";

         limpiartextos();

     });

     function limpiartextos() {
         var elements = document.querySelectorAll("input");
         document.getElementsByClassName("message")[0].textContent = "";
         document.getElementById("mensaje").innerHTML = "";

         // Por cada input field le añadimos una funcion 'onFocus'
         for (var i = 0; i < elements.length; i++) {
             elements[i].value = "";
         }
     }
 </script>