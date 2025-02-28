 <html lang="en" class="no-focus">

 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

     <meta name="description"
         content="Codebase - Bootstrap 4 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
     <meta name="author" content="pixelcave">
     <meta name="robots" content="noindex, nofollow">
     <meta property="og:title" content="Codebase - Bootstrap 4 Admin Template &amp; UI Framework">
     <meta property="og:site_name" content="Codebase">
     <meta property="og:description"
         content="Codebase - Bootstrap 4 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
     <meta property="og:type" content="website">
     <meta property="og:url" content="">
     <meta property="og:image" content="">
     <link rel="stylesheet" id="css-main" href="app/views/css/codebase.css">
 </head>

 <body>
     <div class="loadersacn" style="display: none;">
         <div class="loadersacntexto">*** Wait a moment please ***</div>
     </div>
     <div id="page-container" class="main-content-boxed">
         <main id="main-container">

             <!-- <div class="bg-image" style="background-image: url('public/assets/img/photos/photo<?php echo date("d");  ?>@2x.jpg');"> -->
             <div class="bg-image" style="background-image: url('app/views/img/SACN_Fondo3.jpg');">
                 <!-- <div class="bg-image" style="background-image: url('app/views/img/fondosacn.png');"> -->


                 <div class="row mx-0 ">

                     <div class="has-text-centered hero-static col-md-6 col-xl-8 d-none d-md-flex align-items-md-start">
                         <!-- <div class="titulosistema invisible" data-toggle="appear">
                            <br>
                            <br>
                             <p class="font-size-70 font-w700 has-text-centered text-colortitulo3"
                                 style="font-family: futura Md BT;">
                                 &nbsp;&nbsp;Sistema Automatizado
                             </p>
                             <p class="font-size-70 font-w700 has-text-centered text-colortitulo3"
                             style="font-family: futura Md BT;"> de</p>

                             <p class="font-size-70 font-w700 has-text-centered text-colortitulo3"
                             style="font-family: futura Md BT;">
                                 Cuentas Nacionales

                                 <img src="app/views/img/fondosacn.png" alt="" style="padding-top: 5%;">
                             </p>

                             <p class="font-italic " style="padding-top: 5%;">
                                 Copyright &copy; <span class="js-year-copy">2024</span>
                             </p>
                         </div> -->
                     </div>
                     <div class="hero-static col-md-6 col-xl-4 d-flex align-items-center bg-white invisible"
                         data-toggle="appear" data-class="animated fadeInRight">
                         <div class="content content-full">
                             <!-- Header -->

                             <!-- Formulario login -->
                             <form name="formlogin" class="box2 login" action="" method="POST" autocomplete="off">
                                 <p class="has-text-centered">
                                     <i class="fas fa-user-circle fa-5x"></i>
                                 </p>


                                 <?php
                                        if(isset($_POST['login_usuario']) && isset($_POST['login_clave'])){
                                            $insLogin->iniciarSesionControlador();
                                        } else{

                                        
                                        if(isset($_POST['clave_anterior']) && isset($_POST['clave_nueva'])){
                                            $insLogin->cambiarclave();
                                        }else{
                                            $_SESSION['cambioclave']="0";
                                        }
                                        }
                                    ?>
                                 <?php if($_SESSION['cambioclave']=="0"){ ?>
                                 <h5 class="title is-5 has-text-centered">Credentials </h5>
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
                                 <?php } 
                                        else if($_SESSION['cambioclave']=="1"){ ?>
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
                                 <p class="has-text-centered">
                                     <i class="fas fa-user-circle fa-5x"></i>
                                 </p>
                                 <h5 class="title is-5 has-text-centered"> Reset Password</h5>

                                 <?php
                                        if(isset($_POST['usuario_email'])){
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
         </main>
     </div>

     <script src="app/views/js/jquery.appear.min.js"></script>
     <script src="app/views/js/codebase.js"></script>

 </body>

 </html>


 <script>
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