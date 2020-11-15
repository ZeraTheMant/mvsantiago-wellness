function loadData(provider_id){
	loaderPopupControl(true);
	$(".backend-body-content").html("");
	$.ajax({
		url: 'get_individual_provider_services_beta2.php',
		method: "GET",
		data: {provider_id: provider_id},
		success: function(result){
			$(".backend-body-content").html(result);
			loaderPopupControl(false);
		}
	});
}

loadData(provider_id)