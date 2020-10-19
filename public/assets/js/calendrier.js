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
		selectable: true
	})
	
	calendar.render()
}