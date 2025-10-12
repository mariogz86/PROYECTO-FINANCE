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