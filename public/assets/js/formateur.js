$(document).ready(function () {


	// On récupère le calendrier et on l'initialise
	let calendarElt = document.querySelector("#calendrier")
	let calendar = new FullCalendar.Calendar(calendarElt, {

		initialView: 'listWeek',
		locale: 'fr',
		timeZone: 'Europe/Paris',
		dayHeaderFormat: {
			weekday: 'long',
		},
		headerToolbar: { // affichage des boutons (, -> pas despace)
			start: 'prev,next today',
			center: 'title',
			end: 'dayGridMonth,timeGridWeek,listWeek'
		},
		weekends: false,
		weekNumbers: true,
		businessHours: {
			// Jour de la semaine à afficher, 0 = dimanche
			daysOfWeek: [1, 2, 3, 4, 5],
			startTime: '08:00', // Début de journée (avant grisé)
			endTime: '18:00', // Fin de journée (après grisé)
		},
	})
	// On lance le rendu du calendrier
	calendar.render()



	/**
	 * Fonction qui reload les events
	 */
	function reloadData() {
		// calendar.removeEvents();
		// $('#calendar').fullCalendar('removeEventSources');
		let allEvent = calendar.getEvents();
		allEvent.forEach(e => {
			e.remove();
		});

		let idFormateur = window.location.pathname.split("/").pop();
		$.get('/api/formateur/listAnimer/' + idFormateur, function (data) {
			data.forEach(evenement => {
				calendar.addEvent(evenement)

			})
		});
	}

	reloadData();

	$('#listFormateur').change(() => {
		window.location.href = '/formateur/' + $('#listFormateur').val();
	})


})