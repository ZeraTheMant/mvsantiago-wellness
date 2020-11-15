function loadData(provider_id){
	loaderPopupControl(true);
	$(".backend-body-content").html("");
	$.ajax({
		url: 'get_individual_services_for_client.php',
		method: "GET",
		data: {client_id: userID},
		success: function(result){
			$(".backend-body-content").html(result);
			loaderPopupControl(false);
		}
	});
}

loadData(provider_id)