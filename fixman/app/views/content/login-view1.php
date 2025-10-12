<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fixman Appliances - Inicio de Sesión</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #2c3e50;
      background-image: url('https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1920&q=80');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      font-family: 'Segoe UI', sans-serif;
    }

    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(10, 20, 30, 0.75);
    }

    .content-wrapper {
      position: relative;
      z-index: 2;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      color: white;
    }

    .brand-section {
      text-align: left;
      padding: 30px;
      color: #d4af37;
    }

    .brand-section h1 {
      font-weight: 700;
      letter-spacing: 2px;
      margin-bottom: 0;
    }

    .brand-section h4 {
      margin-top: -5px;
      letter-spacing: 1px;
    }

    .brand-section p {
      color: #f0f0f0;
      margin-top: 10px;
      font-size: 1.1rem;
    }

    .icons-row {
      display: flex;
      align-items: center;
      margin-top: 25px;
      gap: 35px;
    }

    .icons-row svg {
      width: 60px;
      height: 60px;
      fill: #d4af37;
      transition: transform 0.3s ease;
    }

    .icons-row svg:hover {
      transform: scale(1.15);
    }

    .card-login {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0,0,0,0.3);
      padding: 30px;
      width: 350px;
      color: #333;
    }

    .card-login h5 {
      font-weight: 600;
      color: #222;
    }

    .form-control:focus {
      border-color: #d4af37;
      box-shadow: 0 0 0 0.2rem rgba(212,175,55,0.25);
    }

    .btn-primary {
      background-color: #003366;
      border-color: #003366;
    }

    .btn-primary:hover {
      background-color: #002244;
    }

    .forgot a {
      color: #003366;
      text-decoration: none;
    }

    .forgot a:hover {
      text-decoration: underline;
    }

    @media (max-width: 992px) {
      .content-wrapper {
        flex-direction: column;
      }
      .brand-section {
        text-align: center;
      }
      .icons-row {
        justify-content: center;
      }
    }
  </style>
</head>
<body>
  <div class="overlay"></div>

  <div class="container-fluid content-wrapper">
    <div class="row align-items-center justify-content-center w-100">
      
      <!-- IZQUIERDA: LOGO Y TEXTO -->
      <div class="col-lg-6 brand-section">
        <h1>FIXMAN</h1>
        <h4>APPLIANCES</h4>
        <p>REPAIR SERVICES</p>

        <!-- Íconos SVG dorados -->
        <div class="icons-row">  
          <img src="app/views/img/refri.png" style="height: 250px;" alt="">
           <img src="app/views/img/cocina.png" style="height: 250px;" alt="">
           <img src="app/views/img/lavadora.png" style="height: 250px;" alt="">
 
        </div>
      </div>

      <!-- DERECHA: LOGIN -->
      <div class="col-lg-4">
        <div class="card-login mx-auto">
          <div class="text-center mb-3">
            <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" width="70" alt="user icon">
            <h5 class="mt-2">Credenciales</h5>
          </div>

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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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