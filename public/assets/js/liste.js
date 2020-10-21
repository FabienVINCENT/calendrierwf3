$(document).ready(function () {

	//========================== Affichage Formateurs ===============================

	function listeFormateur() {
		$.post('/api/user/listFormateur',
			function (users) {
				if (users.length > 0) {
					let tab = '';
					users.forEach((user) => {
						tab += '<tr>';
						tab += '<td class="p-2">' + user.lastname + ' ' + user.firstname +' / ' + '<a href="tel:' + user.phoneNumber + '">' + user.phoneNumber + '</a>' + '</td>';
						tab += '</tr>';
					});
					$('.insert1').append(tab);
				}
			}, 'json');
	}
	listeFormateur();


	//========================== Affichage Formations ===============================

	function listeFormations() {
		$.post('/api/formation/listFormation',
			function (formations) {
				if (formations.length > 0) {
					let line = ''
					formations.forEach((formation) => {
						line += '<tr>';
						line += '<td class="p-2">' + '<input type="checkbox" class="form-check-input checkboxformation" data-id="' + formation.id + '">' + formation.nom + ' - ' + formation.ville +'</td>'
						line += '</tr>';
					});
					$('.insert2').append(line);
				}
			}, 'json');
	}
	listeFormations();
})