function getFacilitiesImages(caller){
	$("#facilities-container").html("");
	loaderPopupControl(true);
	$.ajax({
		url: "php/backend/get_facilities_images.php",
		method: "GET",
		data: {caller: caller},
		success: function(result){
			$("#facilities-container").html(result);
			for(var i=0; i<document.querySelectorAll('.actual-row').length; i++){
				document.querySelectorAll('.actual-row')[i].addEventListener('dblclick', selectShowcaseImg);
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
			
			document.querySelector("#facilities-container").onmouseenter = function(){
				clearInterval(startBannerLoop);
			};
			
			document.querySelector("#facilities-container").onmouseleave = function(){
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
	}, 1000);
	
	setTimeout(function(){
		for(var i=1; i<slides.length+1; i++){
			if(i !== slideNum){
				//document.querySelector("#card-" + i.toString()).style.opacity = "1";
				if(slides[i]){
					slides[i].style.opacity = "1";
				}
			}
		}	
	}, 4000); 
}

var bannerStatus = 1;
var bannerCtr = 1;
var bannerTimer = 5000;
var slides;

getFacilitiesImages("frontend");