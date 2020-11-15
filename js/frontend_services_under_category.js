function blurServiceImg(e){
	this.firstElementChild.children[0].style.opacity = "0.4";
	//this.firstElementChild.children[1].style.display = "block";
	$(this.firstElementChild.children[1]).show("fast")
}

function unblurServiceImg(e){
	this.firstElementChild.children[0].style.opacity = "1";
	$(this.firstElementChild.children[1]).hide("fast")
}

function loadServices(category_id){
	loaderPopupControl(true);
	$.ajax({
		url: "../php/backend/get_categories_services_frontend.php",
		method: "get",
		data: {category_id: category_id},
		success: function(result){
			$("#services-container").html(result);
			
			var service_categories = document.querySelectorAll(".individual-category");

			for(var i=0; i<service_categories.length; i++){
				service_categories[i].addEventListener('mouseenter', blurServiceImg);
				service_categories[i].addEventListener('mouseleave', unblurServiceImg);
			}
			
			loaderPopupControl(false);
		}
	});
}


document.querySelector('#category-content-right a.read-more').onclick = function(){
	if(email === ""){
		alert("Please log-in first to schedule an appointment. If you do not have an account yet, please register first.");
		modalLogin();
	}
	else{
		window.location = "http://mvsantiagowellness.com/book_appointment.php";
	}
};
document.querySelector('#header-services').style.color = "green";
loadServices(category_id);