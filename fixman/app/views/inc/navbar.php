<div class="full-width navBar">
    <div class="full-width navBar-options">
        <i class="fas fa-exchange-alt fa-fw" id="btn-menu"></i>

        <nav class="navBar-options-list">
            <ul class="list-unstyle">
                <form name="form_reloj">
                    <input type="text" name="reloj" size="10" style="color: White;
    font-family: Verdana, Arial, Helvetica;
    font-size: 10pt;
    text-align: center;
    height: 24px;
    background: border-box;" onfocus="window.document.form_reloj.reloj.blur()">
                </form>
                <li class="text-condensedLight noLink">
                    <a class="btn-exit" href="<?php echo APP_URL."index.php?views=logOut/"; ?>">
                        <i class="fas fa-power-off"></i>
                    </a>
                </li>

                <li class="text-condensedLight noLink">
                    <small><?php echo $_SESSION['usuario']; ?></small>
                </li>
                <li class="noLink">
                    <?php
                        if(is_file("./app/views/fotos/".$_SESSION['foto'])){
                            echo '<img class="is-rounded img-responsive" src="'.APP_URL.'app/views/fotos/'.$_SESSION['foto'].'">';
                        }else{
                            echo '<img class="is-rounded img-responsive" src="'.APP_URL.'app/views/fotos/default.png">';
                        }
                    ?>
                </li>
            </ul>
        </nav>
    </div>
</div>
<script type="text/javascript">
n = 0;
alerta = false;
var id = window.setInterval(function() {
    document.onmousemove = function() {
        n = 0;
    };
    n++;
    if (n >= 3000) {
        if (alerta == false) {
            alerta = true;
            Swal.fire({
                icon: "warning",
                title: "Sesión de usuario",
                text: "El sistema terminará la sesión por falta de actividad.",
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                alerta = false;
                location.href = "<?php echo APP_URL."index.php?views=logOut/"; ?>";

            });
        }

    }
}, 1200);
</script>