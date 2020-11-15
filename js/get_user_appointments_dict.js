function getUserAppointmentsDict(link, some_id){
	loaderPopupControl(true);
	$.ajax({
		url: link,
		method: "GET",
		data: {id: some_id},
		success: function(result){
			if(result){
				user_appointments_dict = JSON.parse(result);
			}
			loaderPopupControl(false);
		}
	});
}