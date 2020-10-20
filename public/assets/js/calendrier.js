window.onload = () =>
{
	let calendarElt = document.querySelector("#calendrier")

	let calendar= new FullCalendar.Calendar (calendarElt, {

		initialView: 'dayGridMonth',
		locale: 'fr',
		timeZone: 'Europe/Paris',
		headerToolbar: {
			start: 'prev,next today',
			center: 'title',
			end: 'dayGridMonth,timeGridWeek,list'
		},
		editable: true,
		eventResizableFromStart: true,
		selectable: true,
		weekends: false,
		weekNumbers: true,
		businessHours: {
		  // days of week. an array of zero-based day of week integers (0=Sunday)
		  daysOfWeek: [ 1, 2, 3, 4, 5 ], // Monday - Thursday
		  startTime: '08:00', // a start time (10am in this example)
		  endTime: '18:00', // an end time (6pm in this example)
		},
		dateClick : function(info) {

			const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

			let dateObjet= new Date (info.dateStr);

			$('#modalAjoutDate').modal('show');
			$('#modalAjoutDateTitle').html('Enregistrement pour le ' + dateObjet.toLocaleDateString('fr-FR',options));
		},
	})

	calendar.render()

	function reloadData() {
		$.get('/event_display', function (data) {

			data.forEach (evenement => {
			calendar.addEvent(evenement)

			})
		})
	}
	reloadData();
}