<section class="full-width navLateral scroll" id="navLateral">
    <div class="full-width navLateral-body" >
        <div class="full-width navLateral-body-logo has-text-centered tittles is-uppercase">
            Sistema automatizado de cuentas nacionales
        </div>
        <figure class="full-width" style="height: 77px;">
            <div class="navLateral-body-cl">
                <?php
                    if(is_file("./app/views/fotos/".$_SESSION['foto'])){
                        echo '<img class="is-rounded img-responsive" src="'.APP_URL.'app/views/fotos/'.$_SESSION['foto'].'">';
                    }else{
                        echo '<img class="is-rounded img-responsive" src="'.APP_URL.'app/views/fotos/default.png">';
                    }
                ?>
            </div>
            <figcaption class="navLateral-body-cr">
                <span>
                    <?php echo $_SESSION['nombre']; ?><br>
                    <small>Rol: <?php echo $_SESSION['rol']; ?></small>
                </span>
            </figcaption>
        </figure>
        <div class="full-width tittles navLateral-body-tittle-menu has-text-centered is-uppercase">
            <i class="fas fa-th-large fa-fw"></i> &nbsp; <?php echo APP_NAME; ?>
        </div>
        <nav class="full-width">
            <ul class="full-width list-unstyle menu-principal">

                <li class="full-width">
                    <a href="<?php echo APP_URL."index.php?views=dashboard/"; ?>" class="full-width">
                        <div class="navLateral-body-cl">
                            <i class="fab fa-dashcube fa-fw"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Inicio
                        </div>
                    </a>
                </li>

                <li class="full-width divider-menuprincipal-h"></li>

                <!-- menu catalogos -->
                <?php 
					use app\controllers\FuncionesController;
					$insCaja = new FuncionesController();

					if ($url[0]!="logOut"){
						echo $insCaja->configurarMenu();
					}

					
					
				?>


                <li class="full-width divider-menuprincipal-h"></li>

                <li class="full-width mt-5">
                    <a href="<?php echo APP_URL."index.php?views=logOut/"; ?>" class="full-width btn-exit">
                        <div class="navLateral-body-cl">
                            <i class="fas fa-power-off"></i>
                        </div>
                        <div class="navLateral-body-cr">
                            Cierre sesión
							
                        </div>
                       
                    </a>
                </li>

            </ul>

        </nav>

    </div>
</section>
<script language="JavaScript">
function mueveReloj() {
    momentoActual = new Date()
    hora = momentoActual.getHours()
    minuto = momentoActual.getMinutes()
    segundo = momentoActual.getSeconds()

    str_segundo = new String(segundo)
    if (str_segundo.length == 1)
        segundo = "0" + segundo

    str_minuto = new String(minuto)
    if (str_minuto.length == 1)
        minuto = "0" + minuto

    str_hora = new String(hora)
    if (str_hora.length == 1)
        hora = "0" + hora

    horaImprimible = hora + " : " + minuto + " : " + segundo

    document.form_reloj.reloj.value = horaImprimible

    setTimeout("mueveReloj()", 1000)
}
</script>