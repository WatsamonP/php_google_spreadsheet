$(document).ready(function(){
	$('#data_table').Tabledit({
		deleteButton: true,
		editButton: true,   		
		columns: {
		  identifier: [0, 'id'],                    
		  editable: [[1, 'name'], [2, 'gender'], [3, 'age'], [4, 'designation'], [5, 'address']]
		},
		hideIdentifier: true,		
	});
});