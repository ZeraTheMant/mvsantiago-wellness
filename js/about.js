function getAboutData(){
	$("#staff-profiles").html("");
	$('#page-info').html("");
	$('#mission-text').html("");
	$('#vision-text').html("");
	loaderPopupControl(true);
	$.ajax({
		url: "php/backend/get_about_data.php",
		method: "GET",
		success: function(result){
			var data = JSON.parse(result);
			$('#page-info').html(data.about_texts.about_main_text);
			$('#mission-text').html(data.about_texts.mission_text);
			$('#vision-text').html(data.about_texts.vision_text);
			$("#staff-profiles").html(data.providers);
			loaderPopupControl(false);
		}
	});
	
	$.ajax({
		url: 'php/backend/update_about_img.php',
		method: 'POST',
		data: {caller: "GET"},
		success: function(response){
			document.querySelector("#photo-holder").src = response;
		}
	});
}

getAboutData();