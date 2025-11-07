 <style>
    /* Cambiar el color de los bordes de la tabla principal */
.fc-theme-standard td, 
.fc-theme-standard th {
  border: 1px solid #050505ff !important; /* Color dorado, cámbialo por el que quieras */
}

/* Cambiar color de fondo al pasar el mouse sobre un día */
.fc-daygrid-day:hover {
  background-color: rgba(212, 175, 55, 0.1); /* fondo dorado claro */
}

/* Opcional: bordes más gruesos */
.fc-theme-standard td, 
.fc-theme-standard th {
  border-width: 2px !important;
}

/* Opcional: bordes redondeados en eventos */
.fc-event {
  border-radius: 5px !important;
  border: 1px solid #080808ff !important;
}
/* Color de fondo de las celdas de los días */
.fc-daygrid-day {
  background-color: #a8a896ff; /* color claro por defecto, cámbialo */
  color: #333; /* texto de los días */
}

/* Color de texto de los números de día */
.fc-daygrid-day-number {
  color: #060606ff; /* azul Bootstrap */
  font-weight: bold;
}
 </style>
 <div class="container">
     <h2>📅 Appointment Calendar</h2>
     <div id="calendar"></div>
 </div>
 <script>
     document.addEventListener('DOMContentLoaded', function() {
         var calendarEl = document.getElementById('calendar');

         var calendar = new FullCalendar.Calendar(calendarEl, {
             initialView: 'dayGridMonth', // vista mensual
             //locale: 'es', // idioma español
             events: "<?php echo APP_URL . 'ajax/calendarioAjax.php?cargagrid' ?>", // aquí obtiene los datos desde PHP
             eventColor: '#378006',
             eventClick: function(info) {
                 Swal.fire({
                     title: `${info.event.extendedProps.jobref}`,
                     html: `
          <b>Fecha:</b> ${info.event.startStr}<br>
          <b>Estado:</b> ${info.event.extendedProps.estado}<br>
          <b>Nota:</b> ${info.event.extendedProps.nota}
        `,
                     icon: 'info'
                 });
             }
         });

         calendar.render();
     });
 </script>