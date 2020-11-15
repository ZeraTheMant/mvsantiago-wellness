function getLogoInfo(){
	document.querySelector('#about-section').style.display = 'none';
	document.querySelector('#showcase-section').style.display = 'none';
	document.querySelector('#facilities-section').style.display = 'none';
	document.querySelector('#contact-section').style.display = 'none';
	document.querySelector('#logo-section').style.display = 'block';
	loaderPopupControl(true);
	document.querySelector("#logo-img-holder").src = "";
	
	$.ajax({
		url: "update_logo.php",
		method: "POST",
		data: {caller: "GET"},
		success: function(result){
			document.querySelector("#logo-img-holder").src = result;
			loaderPopupControl(false);
		}
	})
}

function getContactInfo(){
	document.querySelector('#about-section').style.display = 'none';
	document.querySelector('#showcase-section').style.display = 'none';
	document.querySelector('#facilities-section').style.display = 'none';
	document.querySelector('#contact-section').style.display = 'block';
	document.querySelector('#logo-section').style.display = 'none';
	loaderPopupControl(true);

	$.ajax({
		url: "manipulate_contact_info.php",
		method: "POST",
		data: {action: "get"},
		success: function(result){

			const data = JSON.parse(result);
			
			document.querySelector('#company-nums').value = data.contact_number;
			document.querySelector('#company-email').value = data.contact_email;
			document.querySelector('#company-location').value = data.contact_address;
			document.querySelector('#company-hours').value = data.business_hours;
			loaderPopupControl(false);
		}
	})
}

function changeFacilitiesFormDisplay(flag){
    document.querySelector('#facilities-card-display-order').disabled = flag;
    document.querySelector('#facilities-card-header-text').disabled = flag;
    tinyMCE.get('facilities-card-desc').getBody().setAttribute('contenteditable', !flag);
    document.querySelector('#submit-facilities-form').disabled = flag;
    document.querySelector('#modal-img2').disabled = flag;
}

function changeSliderFormDisplay(flag){
    document.querySelector('#showcase-card-display-order').disabled = flag;
    document.querySelector('#showcase-card-header-text').disabled = flag;
    document.querySelector('#showcase-card-subheader-text').disabled = flag;
    document.querySelector('#showcase-card-link').disabled = flag;
    tinyMCE.get('showcase-card-desc').getBody().setAttribute('contenteditable', !flag);
    document.querySelector('#submit-showcase-form').disabled = flag;
    document.querySelector('#modal-img').disabled = flag;
}

function getShowcaseImages(caller){
	document.querySelector('#contact-section').style.display = 'none';
	document.querySelector('#about-section').style.display = 'none';
	document.querySelector('#showcase-section').style.display = 'grid';
	document.querySelector('#facilities-section').style.display = 'none';
	document.querySelector('#logo-section').style.display = 'none';
	$("#showcase-thumbs").html("");
	loaderPopupControl(true);
	$.ajax({
		url: "get_showcase_images.php",
		method: "GET",
		data: {caller: caller},
		success: function(result){
			$("#showcase-thumbs").html(result);
			
			for(var i=0; i<document.querySelectorAll('.actual-row').length; i++){
				document.querySelectorAll('.actual-row')[i].addEventListener('dblclick', selectShowcaseImg);
			}
			
			
			$.ajax({
				url: "get_homepage_info.php",
				method: "GET",
				data: {caller: caller},
				success: function(response){
					const homepageInfo = JSON.parse(response);
					
					setTimeout(function(){
						document.querySelector('#fb-link').value = homepageInfo.fb_link;
						document.querySelector('#ig-link').value = homepageInfo.ig_link;
						tinyMCE.get('home-part').setContent(homepageInfo.home_info);
					}, 5000);
				}
			});
			
			$.ajax({
			   url: "manipulate_slides_limit.php",
			   method: "POST",
			   data: {caller: 'GET'},
			   success: function(response){
			       document.querySelector('#max-slides').value = response;
			       
			       if(document.querySelectorAll('.actual-row').length >= parseInt(response)){
			           changeSliderFormDisplay(true);
			       }
			       else{
			           changeSliderFormDisplay(false);
			       }
			   }
			});
			
			loaderPopupControl(false);
		}
	});
}

function isUpdateFormFilled(){
	return tinyMCE.get('home-part').getContent() !== "" &&
		   document.querySelector("#fb-link").value !== "" &&
		   document.querySelector("#ig-link").value !== "" && 
		   document.querySelector("#max-slides").value !== "";	
}

function prepareUpdateHomeInfo(e){
	if(isUpdateFormFilled()){
		loaderPopupControl(true);
		$.ajax({
			url: "update_home_info.php",
			method: 'POST',
			data: {
				home_info: tinyMCE.get('home-part').getContent(),
				fb_link: $.trim(document.querySelector("#fb-link").value),
				ig_link: $.trim(document.querySelector("#ig-link").value),
				slider_limit: document.querySelector("#max-slides").value
			},
			success: function(result){
				loaderPopupControl(false);
				alert("Homepage info updated successfully.");
			}
		})
	}
	else{
		alert("Please fill up the home info form.");
	}
}

document.querySelector("#facilities-limit-btn").onclick = function(){
	loaderPopupControl(true);
	$.ajax({
		url: "update_facilities_limit.php",
		method: 'POST',
		data: {
			slider_limit: document.querySelector("#max-slides-facilities").value
		},
		success: function(result){
			loaderPopupControl(false);
			getFacilitiesInfo("backend");
			alert("Facilities image limit updated successfully.");
		}
	})
}

function selectShowcaseImg(e){
	var arg;
	if(e.target.className != 'backend-img-thumb'){
		arg =  e.target.parentNode.children;
	}
	else{
		arg = e.target.parentNode.parentNode.children;
	}
	
	showcaseId = arg[7].textContent;
	document.querySelector('.modal-preview-img').src = arg[8].textContent;
	document.querySelector('#showcase-card-display-order').value = arg[0].textContent;
	document.querySelector('#showcase-card-header-text').value = arg[2].textContent;
	document.querySelector('#showcase-card-subheader-text').value = arg[3].textContent;
	document.querySelector('#showcase-card-link').value = arg[4].textContent;
	tinyMCE.get('showcase-card-desc').setContent(arg[6].textContent);
}

function formIsValid(){
	return document.querySelector('.modal-preview-img').src !== "../../img/question_mark.png" && document.querySelector('#showcase-card-display-order').value !== "" && document.querySelector('#showcase-card-header-text').value !== "" && document.querySelector('#showcase-card-subheader-text').value !== "" && tinyMCE.get('showcase-card-desc').getContent() !== "" && (document.querySelectorAll('.actual-row').length <= parseInt(document.querySelector('#max-slides').value));
}

document.querySelector('#delete-showcase-img').onclick = function(){
    var deleteConfirm = confirm("Delete this image?");
    if(deleteConfirm){
        loaderPopupControl(true);
        $.ajax({
           url: "delete_showcase_img.php",
           method: "POST",
           data: {img_id: showcaseId},
           success: function(result){
               if(document.querySelectorAll('.actual-row').length >= parseInt(document.querySelector('#max-slides').value)){
		           changeSliderFormDisplay(true);
		       }
		       else{
		           changeSliderFormDisplay(false);
		       }
		       getShowcaseImages('backend');
               loaderPopupControl(false);
               
                document.querySelector('#showcase-card-display-order').value = "";
                document.querySelector('#showcase-card-header-text').value = "";
                document.querySelector('#showcase-card-subheader-text').value = "";
                document.querySelector('#showcase-card-link').value = "";
                tinyMCE.get('showcase-card-desc').setContent("");
                document.querySelector('.modal-preview-img').src = "../../img/question_mark.png";
               
               alert("Image successfully deleted.");
           }
        });
    }
}

document.querySelector('#delete-facilities-img').onclick = function(){
    var deleteConfirm = confirm("Delete this image?");
    if(deleteConfirm){
        loaderPopupControl(true);
        $.ajax({
           url: "delete_facilities_img.php",
           method: "POST",
           data: {img_id: facilitiesId},
           success: function(result){
		       getFacilitiesInfo('backend');
               loaderPopupControl(false);
               
                document.querySelector('#facilities-card-display-order').value = "";
                document.querySelector('#facilities-card-header-text').value = "";
                document.querySelector('#showcase-card-subheader-text').value = "";
                tinyMCE.get('facilities-card-desc').setContent("");
                document.querySelector('.modal-preview-img2').src = "../../img/question_mark.png";
               
               alert("Image successfully deleted.");
           }
        });
    }
}


function getShowcaseFormValues(){
	return [
		showcaseId,
		document.querySelector('#showcase-card-display-order').value,
		document.querySelector('#showcase-card-header-text').value,
		document.querySelector('#showcase-card-subheader-text').value,
	    tinyMCE.get('showcase-card-desc').getContent(),
		document.querySelector('#showcase-card-link').value
	];
}

function manipulateShowcaseImages(dataArray, action){
	$.ajax({
		url: 'manipulate_showcase_images.php',
		method: 'POST',
		data: {
			action: action,
			showcase_id: dataArray[0],
			display_order: dataArray[1],
			header: dataArray[2],
			subheader: dataArray[3],
			description: dataArray[4],
			img_link: dataArray[6],
			links_to: dataArray[5]
		},
		success: function(response){
			alert(response)
			showcaseId = "";
			document.querySelector('#showcase-card-display-order').value = "";
			document.querySelector('#showcase-card-header-text').value = "";
			document.querySelector('#showcase-card-subheader-text').value = "";
			tinyMCE.get('showcase-card-desc').setContent("");
			document.querySelector('#showcase-card-link').value = "";
			document.querySelector('.modal-preview-img').src = "../../img/question_mark.png";
			getShowcaseImages("backend");
		}
	});
}

function manipulateFacilitiesImages(dataArray, action){

	$.ajax({
		url: 'manipulate_facilities_images.php',
		method: 'POST',
		data: {
			action: action,
			facility_id: dataArray[0],
			display_order: dataArray[1],
			header: dataArray[2],
			description: dataArray[3],
			img_link: dataArray[4]
		},
		success: function(response){
			alert(response)
			facilitiesId = "";
			document.querySelector('#facilities-card-display-order').value = "";
			document.querySelector('#facilities-card-header-text').value = "";
			document.querySelector('#facilities-card-desc').value = "";
			document.querySelector('.modal-preview-img2').src = "../../img/question_mark.png";
			getFacilitiesInfo("backend");
		}
	});
}

function getFacilitiesFormValues(){
	return [
		facilitiesId,
		document.querySelector('#facilities-card-display-order').value,
		document.querySelector('#facilities-card-header-text').value,
		document.querySelector('#facilities-card-desc').value
	];
}

function updateFacilitiesForm(e){
	loaderPopupControl(true);
	var actionTaken;
	if(facilitiesId){
		actionTaken = 'update';
	}
	else{
		actionTaken = 'insert';
	}
	var dataArray = getFacilitiesFormValues();
	if(document.querySelector('#modal-img2').value === ''){
		dataArray.push(document.querySelector('.modal-preview-img2').src);
		manipulateFacilitiesImages(dataArray, actionTaken);
	}
	else{		
		var $files = $('#modal-img2').get(0).files;
		var formData = new FormData();
		formData.append("image", $files[0]);
		var settings = getImgurDict();		
		settings.data = formData;
		
		settings.success = function(response){
			const imgurResponse = JSON.parse(response);
			const imgLink = imgurResponse.data.link;
			dataArray.push(imgLink);
			manipulateFacilitiesImages(dataArray, actionTaken);
		};
		
		$.ajax(settings);
	}
}

function submitShowcaseImage(e){	
	loaderPopupControl(true);
	var actionTaken;
	if(showcaseId){
		actionTaken = 'update';
	}
	else{
		actionTaken = 'insert';
	}
	var dataArray = getShowcaseFormValues();
	if(document.querySelector('#modal-img').value === ''){
		dataArray.push(document.querySelector('.modal-preview-img').src);
		manipulateShowcaseImages(dataArray, actionTaken);
	}
	else{		
		var $files = $('#modal-img').get(0).files;
		var formData = new FormData();
		formData.append("image", $files[0]);
		var settings = getImgurDict();		
		settings.data = formData;
		
		settings.success = function(response){
			const imgurResponse = JSON.parse(response);
			const imgLink = imgurResponse.data.link;
			dataArray.push(imgLink);
			manipulateShowcaseImages(dataArray, actionTaken);
		};
		
		$.ajax(settings);
	}
}

function prepareSubmitShowcaseImage(e){
	e.preventDefault();
	if(formIsValid()){
		submitShowcaseImage(e);
	}
	else{
		alert('Please complete the form.');
	}
}

function changeCMSSelection(){
	if(document.querySelector('#cms-status').value === 'Homepage'){
		getShowcaseImages("backend");
	}
	else if(document.querySelector('#cms-status').value === 'About us page'){
		getAboutInfo();
	}
	else if(document.querySelector('#cms-status').value === 'Facilities'){
		getFacilitiesInfo("backend");
	}
	else if(document.querySelector('#cms-status').value === 'Logo'){
		getLogoInfo();
	}
	else{
		getContactInfo();
	}
}

function isAboutFormFilled(){
	return tinyMCE.get('about-textarea').getContent() !== "" &&
		   tinyMCE.get('about-mission').getContent() !== "" &&
		   tinyMCE.get('about-vision').getContent() !== "";
}

function isFacilitiesFormFilled(){
	return document.querySelector('.modal-preview-img2').src !== "../../img/question_mark.png" && document.querySelector('#facilities-card-display-order').value !== "" && document.querySelector('#facilities-card-header-text').value !== "" && document.querySelector('#facilities-card-desc').value !== "";
}

function getAboutInfo(){
	loaderPopupControl(true);
	document.querySelector('#contact-section').style.display = 'none';
	document.querySelector('#showcase-section').style.display = 'none';
	document.querySelector('#about-section').style.display = 'block';
	document.querySelector('#facilities-section').style.display = 'none';
	document.querySelector('#logo-section').style.display = 'none';
	
	$.ajax({
		url: 'get_about_info.php',
		method: 'GET',
		success: function(response){

			const aboutTxtContent = JSON.parse(response);
			tinyMCE.get('about-textarea').setContent(aboutTxtContent.about_main_text);
			tinyMCE.get('about-mission').setContent(aboutTxtContent.mission_text);
			tinyMCE.get('about-vision').setContent(aboutTxtContent.vision_text);
			loaderPopupControl(false);
		}
	});
	
	$.ajax({
		url: 'update_about_img.php',
		method: 'POST',
		data: {caller: "GET"},
		success: function(response){
			document.querySelector("#about-img-holder").src = response;
		}
	});
}

function getFacilitiesInfo(caller){
	loaderPopupControl(true);
	document.querySelector('#contact-section').style.display = 'none';
	document.querySelector('#showcase-section').style.display = 'none';
	document.querySelector('#about-section').style.display = 'none';
	document.querySelector('#facilities-section').style.display = 'grid';
	document.querySelector('#logo-section').style.display = 'none';
	
	$("#facilities-thumbs").html("");
	loaderPopupControl(true);
	$.ajax({
		url: "get_facilities_images.php",
		method: "GET",
		data: {caller: caller},
		success: function(result){
			$("#facilities-thumbs").html(result);
			
			for(var i=0; i<document.querySelectorAll('.actual-row').length; i++){
				document.querySelectorAll('.actual-row')[i].addEventListener('dblclick', selectFacilitiesImg);
			}
			
			$.ajax({
			   url: "facilities_slides_limit.php",
			   method: "GET",
			   success: function(response){
			       document.querySelector('#max-slides-facilities').value = response;
			       
			       if(document.querySelectorAll('.fac-row').length >= parseInt(response)){
			           changeFacilitiesFormDisplay(true);
			       }
			       else{
			           changeFacilitiesFormDisplay(false);
			       }
			   }
			});
			
			loaderPopupControl(false);
		}
	});
}

function selectFacilitiesImg(e){
	var arg;
	if(e.target.className != 'backend-img-thumb'){
		arg =  e.target.parentNode.children;
	}
	else{
		arg = e.target.parentNode.parentNode.children;
	}
	
	facilitiesId = arg[5].textContent;

	document.querySelector('.modal-preview-img2').src = arg[6].textContent;
	document.querySelector('#facilities-card-display-order').value = arg[0].textContent;
	document.querySelector('#facilities-card-header-text').value = arg[2].textContent;
	document.querySelector('#facilities-card-desc').value = arg[4].textContent;
}

function updateFacilitiesPrep(e){
	e.preventDefault();
	if(isFacilitiesFormFilled()){
		updateFacilitiesForm(e);
	}
	else{
		alert('Please fill out all the required details.');
	}
}

function isContactFormFilled(){
	return (
		document.querySelector('#company-nums').value !== "" && 
		document.querySelector('#company-email').value !== "" && 
		document.querySelector('#company-hours').value !== "" && 
		document.querySelector('#company-location').value !== ""
	);
}

function updateContactForm(e){
	loaderPopupControl(true);
	$.ajax({
		url: "manipulate_contact_info.php",
		method: "POST",
		data: {
			action: "post",
			numbers: document.querySelector('#company-nums').value,
			email: document.querySelector('#company-email').value,
			location: document.querySelector('#company-location').value,
			hours: document.querySelector('#company-hours').value
		},
		success: function(result){

			alert("Contact information successfully updated.");
			loaderPopupControl(false);
		}
	})
}

function contactFormPrep(e){

	e.preventDefault();
	if(isContactFormFilled()){
		updateContactForm(e);
	}
	else{
		alert('Please fill out all the required details.');
	}
}

function updateAboutPrep(e){
	e.preventDefault();
	if(isAboutFormFilled()){
		updateAboutForm(e);
	}
	else{
		alert('Please fill out all the required details.');
	}
}

function updateAboutImg(imgLink){
	$.ajax({
		url: 'update_about_img.php',
		method: "POST",
		data: {img_link: imgLink, caller: "POST"},
		success: function(result){
			alert("About page image updated.");
			loaderPopupControl(false);
		}
	});
}

function updateLogo(imgLink){
	$.ajax({
		url: 'update_logo.php',
		method: "POST",
		data: {img_link: imgLink, caller: "POST"},
		success: function(result){
			alert("Logo image updated.");
			loaderPopupControl(false);
		}
	});
}

function updateAboutImgForm(e){
	loaderPopupControl(true);

	if(document.querySelector('#about-img').value !== ''){
		var $files = $('#about-img').get(0).files;
		var formData = new FormData();
		formData.append("image", $files[0]);
		var settings = getImgurDict();		
		settings.data = formData;
		
		settings.success = function(response){
			const imgurResponse = JSON.parse(response);
			const imgLink = imgurResponse.data.link;
			updateAboutImg(imgLink);
		};
		
		$.ajax(settings);
	}
	else{
		alert("Please insert an image.");
		loaderPopupControl(false);
	}
}

function updateLogoForm(e){	
	loaderPopupControl(true);

	if(document.querySelector('#logo-img').value !== ''){
		var $files = $('#logo-img').get(0).files;
		var formData = new FormData();
		formData.append("image", $files[0]);
		var settings = getImgurDict();		
		settings.data = formData;
		
		settings.success = function(response){
			const imgurResponse = JSON.parse(response);
			const imgLink = imgurResponse.data.link;
			updateLogo(imgLink);
		};
		
		$.ajax(settings);
	}
	else{
		alert("Please insert an image.");
		loaderPopupControl(false);
	}
}

function updateLogoPrep(e){
	e.preventDefault();
	if(document.querySelector('#logo-img-holder').src !== "../../img/question_mark.png"){
		updateLogoForm(e);
	}
	else{
		alert('Please fill out all the required details.');
	}
}

function updateAboutImgPrep(e){
	e.preventDefault();
	if(document.querySelector('#about-img-holder').src !== "../../img/question_mark.png"){
		updateAboutImgForm(e);
	}
	else{
		alert('Please fill out all the required details.');
	}
}

function updateAboutForm(){
	loaderPopupControl(true);
	$.ajax({
		url: 'update_about_page.php',
		method: 'POST',
		data: {
			aboutHeader: tinyMCE.get('about-textarea').getContent(),
		    mission: tinyMCE.get('about-mission').getContent(),
		    vision: tinyMCE.get('about-vision').getContent()
		},
		success: function(reponse){
			loaderPopupControl(false);
			getAboutInfo();
			alert(reponse);
		}
	});
}

function clearModalImg2(e){
	var link;
	if(e.target.id === 'img-clear-btn-from-backend-providers'){
		link = '../../img/empty_imgs.png';
	}
	else{
		link = '../../img/question_mark.png';
	}
	document.querySelector('.modal-preview-img2').src = link;
	document.querySelector('#modal-img2').value = '';
}

function prevImg2(input){
	var reader = new FileReader();
	reader.onload = function(e){
		document.querySelector('.modal-preview-img2').src = e.target.result;
	};
	reader.readAsDataURL(input.files[0]);
}

function prevImg3(input){
	var reader = new FileReader();
	reader.onload = function(e){
		document.querySelector('#logo-img-holder').src = e.target.result;
	};
	reader.readAsDataURL(input.files[0]);
}

function prevImg4(input){
	var reader = new FileReader();
	reader.onload = function(e){
		document.querySelector("#about-img-holder").src = e.target.result;
	};
	reader.readAsDataURL(input.files[0]);
}

function prePrevImg2(){
	prevImg2(this);
}

function prePrevImg3(){
	prevImg3(this);
}

function prePrevImg4(){
	prevImg4(this);
}


if(document.querySelector('.modal-clear-img2')){
	document.querySelector('.modal-clear-img2').addEventListener('click', clearModalImg2);
}	

if(document.querySelector('#modal-img2')){
	document.querySelector('#modal-img2').addEventListener('change', prePrevImg2);
}

if(document.querySelector('#logo-img')){
	document.querySelector('#logo-img').addEventListener('change', prePrevImg3);
}

if(document.querySelector('#about-img')){
	document.querySelector('#about-img').addEventListener('change', prePrevImg4);
}

document.querySelector("#showcase-form").addEventListener("submit", prepareSubmitShowcaseImage);
document.querySelector("#update-home-info").addEventListener("click", prepareUpdateHomeInfo);
document.querySelector('#cms-status').addEventListener('change', changeCMSSelection);
document.querySelector('#submit-about-page-info').addEventListener('click', updateAboutPrep);
document.querySelector('#submit-facilities-form').addEventListener('click', updateFacilitiesPrep);
document.querySelector('#logo-form').addEventListener('submit', updateLogoPrep);
document.querySelector('#about-img-form').addEventListener('submit', updateAboutImgPrep);
document.querySelector('#contact-info').addEventListener('submit', contactFormPrep);
var showcaseId;
var facilitiesId;

getShowcaseImages('backend');