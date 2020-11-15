function blurServiceImg(e){
	this.firstElementChild.children[0].style.opacity = "0.4";
	//this.firstElementChild.children[1].style.display = "block";
	$(this.firstElementChild.children[1]).show("fast")
}

function unblurServiceImg(e){
	this.firstElementChild.children[0].style.opacity = "1";
	$(this.firstElementChild.children[1]).hide("fast")
}

function loadServices(){
	loaderPopupControl(true);
	$.ajax({
		url: "php/backend/get_categories_frontend.php",
		method: "get",
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



document.querySelector('#header-services').style.color = "green";
loadServices();