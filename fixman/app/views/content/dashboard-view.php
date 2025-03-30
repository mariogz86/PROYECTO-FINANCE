   
 
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
         <h2 class="subtitle">Welcome <?php echo $_SESSION['nombre']." ".$_SESSION['apellido']; ?>!</h2>
     </div>

	 <div class="container mt-5">
        <!-- <h2 class="text-center">Property Management</h2> -->
        <div class="row" id="dashboard"></div>
    </div>

	 <div class="columns is-flex is-justify-content-center" style="opacity: 60%;"> 
	 <img src="app/views/img/logo.png" alt="">
	 </div>



 </div>



    <script>
        fetch("<?php echo APP_URL . 'ajax/dashboardAjax.php?cargarestadostrabajo' ?>")
            .then(response => response.json())
            .then(data => {
                const dashboard = document.getElementById('dashboard');
                data.data.forEach(item => { 
                    const estado = item.estadojob.toLowerCase(); // Convertir en minúscula para buscar imagen
                    const card = `
                        <div class="col-md-2">
                            <div class="card text-center">
							
                                <img src=" <?php echo APP_URL .'app/views/img/' ?>${item.estadojob}.png" class="card-img-top" alt="${item.estadojob}" style="height:50px; object-fit:contain;">
                                <div class="card-body">
                                    <h5 class="card-title">${item.estadojob}</h5>
                                    <p class="card-text fs-3 fw-bold">${item.cantidad}</p>
                                </div>
                            </div>
                        </div>`;
                    dashboard.innerHTML += card;
                });
            });
    </script>
 
 