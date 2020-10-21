$(document).ready(function () {

	let modeFonctionnement = ""

	// On récupère le calendrier et on l'initialise
	let calendarElt = document.querySelector("#calendrier")
	let calendar = new FullCalendar.Calendar(calendarElt, {

		initialView: 'dayGridMonth',
		locale: 'fr',
		timeZone: 'Europe/Paris',
		dayHeaderFormat: {
			weekday: 'long',
		},
		headerToolbar: { // affichage des boutons (, -> pas despace)
			start: 'prev,next today',
			center: 'title',
			end: 'dayGridMonth,timeGridWeek,list'
		},
		eventClick: afficheInfo,
		// editable: true,
		// eventResizableFromStart: true,
		// selectable: true,
		weekends: false,
		weekNumbers: true,
		businessHours: {
			// Jour de la semaine à afficher, 0 = dimanche
			daysOfWeek: [1, 2, 3, 4, 5],
			startTime: '08:00', // Début de journée (avant grisé)
			endTime: '18:00', // Fin de journée (après grisé)
		},
		dateClick: dateClickAdd,
	})
	// On lance le rendu du calendrier
	calendar.render()

	/**
	 * Function de gestion d'ajout de date
	 */
	function dateClickAdd(info) {
		if (modeFonctionnement !== 'liste') {
			const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
			let dateObjet = new Date(info.dateStr);
			$('#modalAjoutDate').modal('show');
			$('#modalAjoutDateTitle').html('Enregistrement pour le ' + dateObjet.toLocaleDateString('fr-FR', options));
			$('#date').val(info.dateStr);
		}
	}

	function afficheInfo(info) {
		const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
		const options2 = { timeZone: "UTC" };
		let dateObjet = new Date(info.event.start);
		let dateObjet2 = new Date(info.event.end);
		let formateur = info.event.extendedProps.description;


		$('#modalAfficheInfo').modal('show');
		console.log(info);

		$('#modalAfficheFormation').html('Formation : ' + info.event.title);
		if (info.event.end === null) {
			$('#modalAfficheDatesFormation').html('<tr><td class="p-2">Formation sur la journée</td></tr>');

		} else if ($('#listeFormation').is(':checked')) {

			$('#modalAfficheDatesFormation').html('<tr>'
				+ '<td class="p-2">' + 'Date de début : ' + dateObjet.toLocaleDateString('fr-FR', options) + '</td>' + '</tr>'
				+ '<tr>'
				+ '<td class="p-2">'
				+ 'Date de fin : '
				+ dateObjet2.toLocaleDateString('fr-FR', options) + '</td>' + '</tr>');

		} else {

			$('#modalAfficheDatesFormation').html('<tr>' + '<td class="p-2">' + 'Date de début : '
				+ dateObjet.toLocaleDateString('fr-FR', options) + ' de '
				+ dateObjet.toLocaleTimeString('fr-FR', options2) + '</td>' + '</tr>' + '<tr>' + '<td class="p-2">' + 'Date de fin : '
				+ dateObjet2.toLocaleDateString('fr-FR', options) + ' à '
				+ dateObjet2.toLocaleTimeString('fr-FR', options2) + '</td>'
				+ '</tr>' + '<tr>' + '<td class="p-2">' + 'Formateur : ' + formateur + '</td>' + '</tr>');
		}
	}

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

		// Si la checkbox list all est cochée, on recupère la liste des formations
		if ($('#listeFormation').is(':checked')) {
			// On récupère la liste des formation dont la date de fin est postérieur a aujourd'hui
			$.get('/api/formation/listnotended', function (data) {
				data.forEach(evenement => {
					calendar.addEvent(evenement)
				})
			});
			// On change le mode de fonctionnement
			modeFonctionnement = 'liste';
		} else {
			// on vérifie quelle checkbox de formation est cochée :
			let checkedCheckbox = [];
			$('.checkboxformation').each((k, checkbox) => {
				if ($(checkbox).is(':checked')) {
					checkedCheckbox.push(checkbox);
				}
			});

			// Si la liste est 
			if (checkedCheckbox.length === 0) {
				// Pas de checkbox cochées, on fait rien
				// On change le mode de fonctionnement
				modeFonctionnement = 'null';
			} else if (checkedCheckbox.length === 1) {
				// Il y a une checkbox, alors on affiche les event 'animer'
				let idFormation = $(checkedCheckbox[0]).data('id');
				$.get('/api/animer/' + idFormation, function (data) {
					data.forEach(evenement => {
						calendar.addEvent(evenement)
					})
					// On change le mode de fonctionnement
					modeFonctionnement = 'formation';
				});
			} else {
				// Il y a plus qu'une formation alors on les affiches comme sur l'accueil

				// On change le mode de fonctionnement
				modeFonctionnement = 'liste';
			}
		}
	}

	reloadData();

	// Si on click sur une des checkbox, on reload les data
	$('#listeFormation').change(reloadData);
	$('.insert2').change(reloadData);

	// Gestion du datepicker
	$("#date").datepicker();
	$("#date").datepicker("option", "dateFormat", 'yy-mm-dd');



	$('#js-valid-ajout').click((e) => {
		e.preventDefault();
		$.ajax({
			type: 'POST',
			url: '/addAnimer',
			data: $('#modalAjoutDate').find('form').serialize(),
			success: function (retour) {
				$('.js-alert-form').each(function (div) {
					$(this).remove();
				})
				if (retour === true) {
					$('#modalAjoutDate').modal('hide');
					reloadData();
				} else {
					$('#modalAjoutDate').find('.modal-body')
						.prepend('<div class="alert alert-danger js-alert-form" role="alert">' + retour.error + '</div>')
				}
			}
		})
	})

})