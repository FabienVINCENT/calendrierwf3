$(document).ready(function () {
	const AFFICHE_JF = true;
	let urlJF = 'https://etalab.github.io/jours-feries-france-data/json/metropole.json';
	let modeFonctionnement = "";

	// Lance select2.js sur le champ formateur quand on est admin (recherche)
	$('.js-select2-formateur').select2({ theme: "bootstrap" });

	// On récupère le calendrier et on l'initialise
	let calendarElt = document.querySelector("#calendrier")
	let calendar = new FullCalendar.Calendar(calendarElt, {
		initialView: 'dayGridMonth',
		locale: 'fr',
		timeZone: 'Europe/Paris',
		dayHeaderFormat: {
			weekday: 'long',
		},
		headerToolbar: { // affichage des boutons ("," -> pas despace)
			start: 'prev,next today',
			center: 'title',
			end: 'dayGridMonth,timeGridWeek,listWeek'
		},
		eventClick: afficheInfo, // Quand on click sur un évènement on lance cette fonction
		weekends: false,
		weekNumbers: true,
		businessHours: {
			// Jour de la semaine à afficher, 0 = dimanche
			daysOfWeek: [1, 2, 3, 4, 5],
			startTime: '08:00', // Début de journée (avant grisé)
			endTime: '18:00', // Fin de journée (après grisé)
		},
		dateClick: dateClickAdd, // Quand on click sur une date on lance cette fonction
	})
	// On lance le rendu du calendrier
	calendar.render()

	/**
	 * Function de gestion d'ajout de date
	 */
	function dateClickAdd(info) {
		if (modeFonctionnement !== 'liste') {
			// si une seul checkbox est cochée, alors on selectionne la formation dans le select d'ajout
			let checkedCheckbox = [];
			$('.checkboxformation').each((k, checkbox) => {
				if ($(checkbox).is(':checked')) {
					checkedCheckbox.push($(checkbox).data('id'));
				}
			});
			if ($(checkedCheckbox).length === 1) {
				$('#fkAnimerFormation').val(checkedCheckbox[0]);
				$('#fkAnimerFormation').trigger('change');
			}
			// On affiche la modal
			const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
			let dateObjet = new Date(info.dateStr);
			$('#modalAjoutDate').modal('show');
			$('#modalAjoutDateTitle').html('Enregistrement pour le ' + dateObjet.toLocaleDateString('fr-FR', options));
			// On rempli la date clicquée
			$('#date').val(info.dateStr);
		}
	}

	/**
	 * Function de gestion des affichages d'infos
	 * @param info : information de levenement cliqué
	 */
	function afficheInfo(info) {
		// Définition des variables utiles
		const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
		const options2 = { timeZone: "UTC" };
		let dateObjet = new Date(info.event.start);
		let dateObjet2 = new Date(info.event.end);
		let formateur = info.event.extendedProps.description;

		$('#modalAfficheInfo').modal('show');
		$('#modalAfficheFormation').html('Formation : ' + info.event.title);


		//modale pour afficher les infos d'une formations se déroulant sur une journée
		if (info.event.end === null) {
			$('#modalAfficheDatesFormation').html('<table><tr><td class="p-2">Formation sur la journée</td></tr>'
				+ '<tr><td class="p-2">Formateur : '
				+ formateur + '</td></tr></table>')
			// Affiche le bouton de suppression si le formateur est le propriétaire du crénau ou si admin
			if (isAdmin || idFormateur == info.event.extendedProps.idFormateur) {
				$('#modalAfficheDatesFormation').append('<a data-animer="' + info.event.id + '" class="btn btn-danger m-2 js-deleteAnimer">Supprimer</a>');
			};
		}
		//modale pour afficher les infos des formations sur la liste de vue générale
		else if ($('#listeFormation').is(':checked')) {

			$('#modalAfficheDatesFormation').html('<table><tr>'
				+ '<td class="p-2">' + 'Date de début : ' + dateObjet.toLocaleDateString('fr-FR', options) + '</td>' + '</tr>'
				+ '<tr>'
				+ '<td class="p-2">'
				+ 'Date de fin : '
				+ dateObjet2.toLocaleDateString('fr-FR', options) + '</td>' + '</tr></table>'
				+ '<button type="submit" id="js-ensavoirplus" data-formation="' + info.event.id + '" class="btn btn-info m-2">EN SAVOIR PLUS</button>');
		}
		//modale pour afficher les infos d'une formations se déroulant sur une plage horaire définie
		else {

			$('#modalAfficheDatesFormation').html('<table><tr>' + '<td class="p-2">' + 'Date de début : '
				+ dateObjet.toLocaleDateString('fr-FR', options) + ' de '
				+ dateObjet.toLocaleTimeString('fr-FR', options2) + '</td>' + '</tr>' + '<tr>' + '<td class="p-2">' + 'Date de fin : '
				+ dateObjet2.toLocaleDateString('fr-FR', options) + ' à '
				+ dateObjet2.toLocaleTimeString('fr-FR', options2) + '</td>'
				+ '</tr>' + '<tr>' + '<td class="p-2">' + 'Formateur : ' + formateur + '</td>' + '</tr></table>')
			// Affiche le bouton de suppression si le formateur est le propriétaire du crénau ou si admin
			if (isAdmin || idFormateur == info.event.extendedProps.idFormateur) {
				$('#modalAfficheDatesFormation').append('<a data-animer="' + info.event.id + '" class="btn btn-danger m-2 js-deleteAnimer">Supprimer</a>');
			};
		}
	}

	/**
	 * Fonction qui reload les events
	 */
	function reloadData() {
		let allEvent = calendar.getEvents();
		allEvent.forEach(e => {
			// On remove tous les evenements
			e.remove();
		});

		// On affiche les jours férié via api gouv.fr
		if (AFFICHE_JF) {
			$.get(urlJF, function (data) {
				let events = [];

				Object.keys(data).forEach(key => {
					let date = new Date(key);
					let now = new Date();
					now.setFullYear(now.getFullYear() - 1); // On filtre les JF antérieur a 1 an
					if (date > now) {
						let event = [];
						event['title'] = 'Jour Férié';
						event['description'] = data[key];
						event['start'] = key;
						event['backgroundColor'] = '#F25E57';
						event['borderColor'] = '#F25E57';
						calendar.addEvent(event); // on ajoute au calendrier
					}
				});

			});
		}

		// Si la checkbox list all est cochée, on recupère la liste des formations
		if ($('#listeFormation').is(':checked')) {
			// On récupère la liste des formation dont la date de fin est postérieur a aujourd'hui
			$.get('/api/formation/listnotended', function (data) {
				data.forEach(evenement => {
					// On vérifie que l'evenement doit etre affiché
					if ($('.checkboxformation[data-id="' + evenement.id + '"]').is(':checked')) {
						calendar.addEvent(evenement)
					}
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
				// Il y a plus qu'une formation alors on les affiche comme sur l'accueil
				let tableau = [];
				$(checkedCheckbox).each((k, checkbox) => {
					if ($(checkbox).is(':checked')) {
						tableau.push($(checkbox).data('id'));
					}
				});
				$.ajax({
					type: 'POST',
					data: JSON.stringify(tableau),
					url: '/api/animerfull/',
					success: function (data) {
						data.forEach(evenement => {
							calendar.addEvent(evenement)
						})
					}
				})
				// On change le mode de fonctionnement
				modeFonctionnement = 'formation';
			}
		}
	}

	reloadData();


	/**
	 * Gestion du drag & drop
	 */
	calendar.on('eventChange', (e) => {
		let id = e.oldEvent.id;
		let newDate = e.event.startStr;

		$.ajax({
			type: 'POST',
			data: JSON.stringify(newDate),
			url: '/api/editAnimer/' + id,
			success: function (retour) {
				if (retour == false) {
					alert('erreur au changement de date')
				}
			}
		})
	});


	// Gestion du cochage / décochage des checkboxs formations en fct du check général
	$('#listeFormation').change(function () {
		if ($('#listeFormation').is(':checked')) {
			$('.checkboxformation').each((k, checkbox) => $(checkbox).prop("checked", true));
		} else {
			$('.checkboxformation').each((k, checkbox) => $(checkbox).prop("checked", false));
		}
		reloadData();
	});

	$('.listformation').change(reloadData);

	// Gestion du datepicker
	$("#date").datepicker();
	$("#date").datepicker("option", "dateFormat", 'yy-mm-dd');

	// Fonction ajax qui géré l'ajout de crénau
	$('#js-valid-ajout').click((e) => {
		e.preventDefault();
		$.ajax({
			type: 'POST',
			url: '/api/addAnimer',
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

	// Gestion du bouton "En savoir plus" de la modale
	$('#modalAfficheInfo').click((e) => {
		if (e.target.nodeName != 'BUTTON') {
			e.preventDefault();
		} else {
			$('input[type=checkbox]').each((k, checkbox) => {
				let idFormation = $('#js-ensavoirplus').data('formation');
				if ($(checkbox).data('id') == idFormation) {
					$(checkbox).prop("checked", true);
				}
				else { $(checkbox).prop("checked", false); }
			});

			$('#modalAfficheInfo').modal('hide');
			reloadData();
		}
	});

	// Gestion du bouton de suppression d'un animer (crénaux formateur)
	$('#modalAfficheInfo').click((e) => {
		e.preventDefault();
		if (e.target.nodeName == 'A') {
			let idAnimer = $(e.target).data('animer');

			// je demande confirmation avant de décencher la méthode ajax
			if (window.confirm("Etes-vous sûr de vouloir supprimer ce créneau?")) {
				$.ajax({
					type: 'GET',
					url: '/api/deleteAnimer/' + idAnimer,
					success: function (retour) {
						if (retour) {
							$('#modalAfficheInfo').modal('hide');
							reloadData();
						} else {
							alert('Probleme de suppression');
						}
					}
				})
			}

		}
	});

	//Gestion de la disponibilité
	$('#fkAnimerUser').change(function () {
		let idUserSelect = $('#fkAnimerUser').val();
		let date = $('#date').val();

		$.ajax({
			type: 'POST',
			data: JSON.stringify([date, idUserSelect]),
			url: '/api/isDispo/',
			success: function (retour) {
				$('.js-alert-form').each(function (div) {
					$(this).remove();
				})
				if (retour) {
					$('#modalAjoutDate .modal-body').append('<div class="alert alert-success js-alert-form" role="alert">' +
						'Le formateur est disponible' +
						'</div>')
				} else {
					$('#modalAjoutDate .modal-body').append('<div class="alert alert-danger js-alert-form" role="alert">' +
						'Le formateur a déjà une autre formation, attention !' +
						'</div > ')

				}
			}
		})

	})


})
