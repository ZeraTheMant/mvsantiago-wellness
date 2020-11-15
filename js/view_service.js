function loadService(){
	$("#main-banner").html("");
	$("#service-providers").html("");		
	loaderPopupControl(true);
	$.ajax({
		url: "../php/backend/load_service.php",
		method: "GET",
		data: {
			service_id: service_id
		},
		success: function(result){
			var outputs = JSON.parse(result);
			$("#main-banner").html(outputs.images);		
			$("#service-providers").html(outputs.info);		
			slides = document.querySelectorAll('.imgban');
			var startBannerLoop = setInterval(function(){
				if(bannerCtr > slides.length){
					bannerCtr = 1;
				}
				
				bannerLoop(bannerCtr);
				bannerCtr++;
			}, bannerTimer);
			
			document.querySelector(".main-banner").onmouseenter = function(){
				clearInterval(startBannerLoop);
			};
			
			document.querySelector(".main-banner").onmouseleave = function(){
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
			//document.querySelector("#imgban" + i.toString()).style.opacity = "0";
		}
	}
			
	setTimeout(function(){
		for(var i=1; i<slides.length+1; i++){
			if(i === slideNum){
				if(slides[i]){
					slides[i].style.zIndex = "1000";
					slides[i].style.right = "0";	
				}
				//document.querySelector("#imgban" + slideNum.toString()).style.right = "0";
				//document.querySelector("#imgban" + slideNum.toString()).style.zIndex = "1000";

			}
			else{
				if(slides[i]){
					slides[i].style.zIndex = "-1";
					slides[i].style.right = "-1200px";	
				}
				//document.querySelector("#imgban" + i.toString()).style.right = "-1200px";
				//document.querySelector("#imgban" + i.toString()).style.zIndex = "-1";
			}
		}
	}, 500);
	
	setTimeout(function(){
		for(var i=1; i<slides.length+1; i++){
			if(i !== slideNum){
				if(slides[i]){
					slides[i].style.opacity = "1";	
				}
				//document.querySelector("#imgban" + i.toString()).style.opacity = "1";
			}
		}	
	}, 1000); 
}

var bannerStatus = 1;
var bannerCtr = 1;
var bannerTimer = 5000;
var slides;

loadService();
