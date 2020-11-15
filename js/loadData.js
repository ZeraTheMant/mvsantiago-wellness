function loadData(link, chosen_method, sent_data, clickEvent){
	$('.backend-body-content').html('');
	loaderPopupControl(true);
	$.ajax({
		url: link,
		method: chosen_method,
		data: sent_data,
		success: function(result){
			$('.backend-body-content').html(result);
			for(var i=0; i<document.querySelectorAll('.actual-row').length; i++){
				document.querySelectorAll('.actual-row')[i].addEventListener('dblclick', clickEvent);
			}
			loaderPopupControl(false);
		}
	});
}