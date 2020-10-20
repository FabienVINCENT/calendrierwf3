$(document).ready(function(){

	//========================== Affichage Formateurs ===============================

	function listeFormateur(){
		$.post ('/user/listUser',
			'action=listUser',
			function(users) {
				if(users.length > 0){
					let tab='';
					users.forEach( (user)=> {
						tab += '<tr>';
						tab +='<td>'+user.lastname+'</td>';
                        tab +='<td>'+user.firstname+'</td>';
                        tab += '</tr>';
                        tab += '<tr>';
                        tab +='<td>'+user.email+'</td>'
                        tab += '</tr>';
                        tab += '<tr>';;
						tab +='<td>'+user.phoneNumber+'</td>';
						tab += '</tr>';
					});
					$('.insert1').append(tab);
				}
			},'json');
	}                         
	listeFormateur();


	//========================== Affichage Formations ===============================

	function listeFormations(){
		$.post ('/formation/listFormation',
			'action=listFormation',
			function(formations) {
				if(formations.length > 0){
					let line=''
					formations.forEach( (formation)=> {
						line += '<tr>';
						line += '<td>'+formation.nom+' -'+'</td>';
	                	line += '<td>'+formation.ville+'</td>';
						line += '</tr>';
					});
					$('.insert2').append(line);
				}
			},'json');
	}
	listeFormations();

})