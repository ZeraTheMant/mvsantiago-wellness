function getIndexInfo(caller){
	$("#showcase").html("");
	$("#index-services-body").html("");
	$("#index-right").html("");
	loaderPopupControl(true);
	$.ajax({
		url: "php/backend/get_showcase_images.php",
		method: "GET",
		data: {caller: caller},
		success: function(result){
			var data = JSON.parse(result);
			$("#showcase").html(data.showcase);
			$("#index-services-body").html(data.services);
			$("#index-right").html(data.testimonial);
			for(var i=0; i<document.querySelectorAll('.actual-row').length; i++){
				document.querySelectorAll('.actual-row')[i].addEventListener('dblclick', selectShowcaseImg);
			}
			
			for(var i=0; i<document.querySelectorAll('#showcase-info-content').length; i++){
				var showCaseInfoContent = window.getComputedStyle(document.querySelectorAll('#showcase-info-content')[i]);
				var showcaseInfo = document.querySelectorAll("#showcase-info")[i];
				var showcaseInfoFaded = document.querySelectorAll("#showcase-info-faded")[i];
				

				showcaseInfo.style.width = stringify(parseInt(showCaseInfoContent.getPropertyValue("width").slice(0, -2)) + 20);
				showcaseInfo.style.height = stringify(parseInt(showCaseInfoContent.getPropertyValue("height").slice(0, -2)) + 30);
				showcaseInfoFaded.style.width = stringify(parseInt(showCaseInfoContent.getPropertyValue("width").slice(0, -2)) + 20);
				showcaseInfoFaded.style.height = stringify(parseInt(showCaseInfoContent.getPropertyValue("height").slice(0, -2)) + 30);
			}
			
			
			slides = document.querySelectorAll('.showcase-card');
			bannerLoop(1);
			var startBannerLoop = setInterval(function(){
				if(bannerCtr > slides.length){
					bannerCtr = 1;
				}
				
				bannerLoop(bannerCtr);
				bannerCtr++;
			}, bannerTimer);
			
			document.querySelector("#showcase").onmouseenter = function(){
				clearInterval(startBannerLoop);
			};
			
			document.querySelector("#showcase").onmouseleave = function(){
				startBannerLoop = setInterval(function(){
					if(bannerCtr > slides.length){
						bannerCtr = 1;
					}				
					bannerLoop(bannerCtr);
					bannerCtr++;
				}, bannerTimer);
			};
			
			document.querySelector('#imgbanbtn-next').onclick = function(){
				if(bannerCtr >= slides.length){
					bannerCtr = 1;
					bannerLoop(bannerCtr);
				}
				else{
					bannerCtr++;
					bannerLoop(bannerCtr);
				}
			}

			document.querySelector('#imgbanbtn-prev').onclick = function(){
				if(bannerCtr === 1){
					bannerCtr = slides.length
					bannerLoop(slides.length);
				}
				else{
					bannerCtr--
					bannerLoop(bannerCtr);
				}
			}

			loaderPopupControl(false);
		}
	});
}

function bannerLoop(slideNum){
	for(var i=1; i<slides.length+1; i++){
		if(i !== slideNum){
			if(slides[i]){
				slides[i].style.opacity = "0";
			}
			//document.querySelector("#card-" + i.toString()).style.opacity = "0";
		}
	}
			
	setTimeout(function(){
		for(var i=1; i<slides.length+1; i++){
			if(i === slideNum){
				//document.querySelector("#card-" + slideNum.toString()).style.right = "0";
				//document.querySelector("#card-" + slideNum.toString()).style.zIndex = "1000";
				if(slides[i]){
					slides[i].style.zIndex = "1000";
					slides[i].style.right = "0";
				}
			}
			else{
				//document.querySelector("#card-" + i.toString()).style.right = "-1200px";
				//document.querySelector("#card-" + i.toString()).style.zIndex = "-1";
				if(slides[i]){
					slides[i].style.zIndex = "-1";
					slides[i].style.right = "-1200px";
				}
			}
		}
	}, 500);
	
	setTimeout(function(){
		for(var i=1; i<slides.length+1; i++){
			if(i !== slideNum){
				//document.querySelector("#card-" + i.toString()).style.opacity = "1";
				if(slides[i]){
					slides[i].style.opacity = "1";
				}
			}
		}	
	}, 1000); 
}

var bannerStatus = 1;
var bannerCtr = 1;
var bannerTimer = 5000;
var slides;

/*if(document.querySelector('#link-to-book'))
document.querySelector('#link-to-book').onclick = function(){
	if(email === ""){
		alert("Please log-in first to schedule an appointment. If you do not have an account yet, please register first.");
		modalLogin();
	}
	else{
		window.location = "http://localhost/itsp/book_appointment.php";
	}
};*/


getIndexInfo("frontend");