   
 <div class="container is-fluid">
     <!-- <h1 class="title">Inicio</h1> -->
     <!-- <div class="columns is-flex is-justify-content-center">
    	<figure class="image is-128x128">
    		<?php
    			if(is_file("./app/views/fotos/".$_SESSION['foto'])){
    				echo '<img class="is-rounded" src="'.APP_URL.'app/views/fotos/'.$_SESSION['foto'].'">';
    			}else{
    				echo '<img class="is-rounded" src="'.APP_URL.'app/views/fotos/default.png">';
    			}
    		?>
		</figure>
  	</div> -->

     <!-- Script JS -->

     <div class="columns is-flex is-justify-content-center">
	 <?php 		echo '<img class="is-rounded" src="'.APP_URL.'app/views/img/Logo_cm_azul.png">'; ?>
	 
     </div>
	 
	 <div class="columns is-flex is-justify-content-center"> 
         <h2 class="subtitle">¡Bienvenido <?php echo $_SESSION['nombre']." ".$_SESSION['apellido']; ?>!</h2>
     </div>

	 <div class="columns is-flex is-justify-content-center" style="opacity: 60%;"> 
	 <img src="app/views/img/fondosacn.png" style=" height: 250px;" alt="">
	 </div>
	 <br>
                             <p class="font-size-70 font-w700 has-text-centered text-colortitulo3"
                                 style="font-family: futura Md BT;font-size: xxx-large;">
                                 Sistema Automatizado de Cuentas Nacionales
                             </p> 

 </div>
  <script>

    localStorage.removeItem('subopcionActiva');
    localStorage.removeItem('menuSeleccionado');
</script>
 
 