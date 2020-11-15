function getProviderServices(link, select_box, caller, arg_val){
	loaderPopupControl(true);
	$.ajax({
		url: link,
		method: 'GET',
		data: {client_id: arg_val},
		success: function(result){

			provider_services = JSON.parse(result);
			for(var i=0; i<provider_services.length; i++){
				categories.push(
					{
						category_id: provider_services[i].category_id,
						category_name: provider_services[i].category_name,
					}
				);
			}
			categories = categories.reduce((acc, x) =>
			   acc.concat(acc.find(y => y.category_id === x.category_id) ? [] : [x])
			 , []);
			for(var i=0; i<categories.length; i++){
				var category = document.createElement('option');
				category.textContent = categories[i].category_name;
				category.setAttribute('id', categories[i].category_id);
				document.querySelector(select_box).appendChild(category);
			}
			
			if(caller === 'frontend'){
				for(var i=0; i<provider_services.length; i++){
					services.push(
						{
							service_id: provider_services[i].service_id,
							service_name: provider_services[i].service_name,
							service_duration: provider_services[i].duration,
							service_category_id: provider_services[i].category_id,
							service_appears_on_first_time: provider_services[i].appears_on_first_time
						}
					);
				}
				services = services.reduce((acc, x) =>
				   acc.concat(acc.find(y => y.service_id === x.service_id) ? [] : [x])
				 , []);


				for(var i=0; i<provider_services.length; i++){
					 var level = '';
					 
					 if(provider_services[i].provider_level === 'Doctor'){
						 level = 'Dr.'
					 }
					 providers.push(
						{
							provider_id: provider_services[i].provider_id,
							provider_name: level + ' ' + provider_services[i].fname + ' ' + provider_services[i].mname[0].toUpperCase() + '. ' + provider_services[i].lname + ' ' + provider_services[i].name_ext,
							service_id: provider_services[i].service_id,
							days_worked: provider_services[i].days_worked
						}
					 );
				}				 
			}
			else{
				$.ajax({
					url: "get_all_services.php",
					method: 'GET',
					success: function(result){
						var all_services = JSON.parse(result);
						for(var i=0; i<all_services.length; i++){
							services.push(
								{
									service_id: all_services[i].service_id,
									service_name: all_services[i].service_name,
									service_duration: all_services[i].duration,
									service_category_id: all_services[i].category_id,
									service_appears_on_first_time: all_services[i].appears_on_first_time
								}
							);
						}
					}
				})
			}
			loaderPopupControl(false);	
		}
	});

	/*document.querySelector('#appointment-service').options.length = 0;
	document.querySelector('#appointment-provider').options.length = 0;
	getFromDB(
		[
			'php/backend/get_data_into_select.php', 
			'GET', 
			'#appointment-service',
			'SELECT * FROM services INNER JOIN category ON services.category_id = category.category_id WHERE services.category_id = ' + e.target.options[e.target.selectedIndex].id + '', 
			'service_name', 
			'service',
			'service_id'
		]
	);
	var provider = document.createElement('option');
	provider.textContent = '---Choose a provider---';
	document.querySelector('#appointment-provider').appendChild(provider);
	document.querySelector('#front-slide-next').disabled = true;*/
}

function getUserServices(link){
	$.ajax({
		url: link,
		method: "GET",
		data: {client_id: user_id},
		success: function(result){
			var all_services = JSON.parse(result);
			for(var i=0; i<all_services.length; i++){
				user_services.push(
					{
						service_id: all_services[i].service_id,
						service_name: all_services[i].service_name,
						service_category_id: all_services[i].category_id
					}
				);
			}
		}
	});
}


var provider_services;
var services = [];
var providers = [];
var categories = [];
var user_services = [];