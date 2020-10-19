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
			end: 'dayGridMonth,timeGridWeek'
		},
		editable: true,
		eventResizableFromStart: true,
		selectable: true,
		dateClick : function(info) {

			const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

			let dateObjet= new Date (info.dateStr);

				$('#modalAjoutDate').modal('show');
				$('#modalAjoutDateTitle').html('Enregistrement pour le ' + dateObjet.toLocaleDateString('fr-FR',options));
			}
	})
	
	calendar.render()
}