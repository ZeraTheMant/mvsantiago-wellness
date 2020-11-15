function getIndividualProviderServices(provider_id){
	loaderPopupControl(true);
	$.ajax({
		url: "get_individual_provider_services_beta.php",
		method: 'GET',
		data: {provider_id: provider_id},
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
			}
			
			for(var i=0; i<provider_services.length; i++){
				services.push(
					{
						service_id: provider_services[i].service_id,
						service_name: provider_services[i].service_name,
						service_category_id: provider_services[i].category_id,
						service_appears_on_first_time: provider_services[i].appears_on_first_time,
					}
				);
			}
			services = services.reduce((acc, x) =>
			   acc.concat(acc.find(y => y.service_id === x.service_id) ? [] : [x])
			 , []);


			loaderPopupControl(false);	
		}
	});
}
	
var provider_services;
var services = [];
var categories = [];

getIndividualProviderServices(provider_id);