function setTestimonialsContainer(flag, data){
	$('#testimonials-container').html(data);
	loaderPopupControl(flag);
}

function loadTestimonials(){
	setTestimonialsContainer(true, '');
	getTestimonials();
}

function getTestimonials(){
	$.ajax({
		url: 'php/backend/get_testimonials.php',
		method: 'GET',
		data: {approval_status: '1', view: 'front end'},
		success: function(data){
			setTestimonialsContainer(false, data);
			getGridColumns(document.querySelectorAll('.thumb-img').length);
		}
	});
}

function getGridColumns(testimonials){
	if(testimonials > 3){
		document.querySelector('.outer-testimonial-box').style.gridColumn = '';
		document.querySelector('#testimonials-container').style.gridTemplateColumns = '1fr 1fr 1fr 1fr';
	}
	else if(testimonials === 3){
		document.querySelector('.outer-testimonial-box').style.gridColumn = '';
		document.querySelector('#testimonials-container').style.gridTemplateColumns = '1fr 1fr 1fr';
	}
	else if(testimonials === 2){
		document.querySelector('.outer-testimonial-box').style.gridColumn = '';
		document.querySelector('#testimonials-container').style.gridTemplateColumns = '1fr 1fr';
	}
	else if(testimonials === 1){
		document.querySelector('.outer-testimonial-box').style.gridColumn = '2/4';
	}
}

function checkIfUserHasPostedTestimonial(user_id){
	$.ajax({
		url: "php/backend/check_if_user_has_posted_testimonial.php",
		method: "GET",
		data: {user_id: user_id},
		success: function(result){
			if(result){
				document.querySelector('#add-testimonial').style.display = "none";
			}
		}
	})
}

function testimonialModalControl(flag, headerText, width){
	if(flag){
		document.querySelectorAll('.modal-popup')[1].style.display = 'flex';
		document.querySelectorAll('.modal-popup')[1].style.alignItems = 'center';
		document.querySelectorAll('.modal-content')[1].style.width = width;
		document.querySelectorAll('.modal-header-text')[1].textContent = headerText;
	}
	else{
		document.querySelectorAll('.modal-popup')[1].style.display = 'none';
		document.querySelectorAll('.modal-form')[1].reset();
	}
}

function closeTestiModal(e){
	if(e.target === document.querySelectorAll('.modal-close-btn')[1] ||
	   e.target === document.querySelectorAll('.modal-close-btn > span')[1]){
		document.querySelectorAll('.modal-popup')[1].style.display = 'none';
		document.querySelectorAll('.modal-form')[1].reset();;
	}
}

function addTestimonial(){
	testimonialModalControl(true, 'Send Testimonial', '500px');
	document.querySelector('.modal-preview-img').src = user_img;
	const selectedDOB = moment(dob);
	const datesDifference = moment.duration(moment().diff(selectedDOB));
	const age = parseInt(datesDifference.asYears());
	document.querySelector('#modal-sender-age').value = age;
	document.querySelector('#modal-sender-name').value = name;
}

function sendTestimonial(){
	loaderPopupControl(true);
	$.ajax({
		url: "php/backend/send_testimonial.php",
		method: "POST",
		data: {
			client_id: user_id, 
			approval_status: '0', 
			message: document.querySelector('#modal-testimonial').value,
			address: document.querySelector('#modal-sender-address').value
		},
		success: function(result){
			alert(result);
			document.querySelectorAll('.modal-popup')[1].style.display = 'none';
			document.querySelectorAll('.modal-form')[1].reset();
			document.querySelector('#add-testimonial').style.display = "none";
			loadTestimonials();
		}
	});
}


if(user_id !== ""){
	checkIfUserHasPostedTestimonial(user_id)
}

var tabPanels = document.querySelectorAll('.tab-panel');
tabPanels[0].style.display = "block";
tabPanels[0].style.backgroundColor = "white";
tabPanels[0].style.color = "black";
document.querySelector('.modal-halved').style.marginTop = "30px";
document.querySelector('.modal-halved').style.marginBottom = "10px";

document.querySelector('.modal-preview-img').style.position = "relative";
document.querySelector('.modal-preview-img').style.top = "50%";
document.querySelector('.modal-preview-img').style.transform = "translateY(-50%)";


loadTestimonials();

if(document.querySelector('#add-testimonial')){
	document.querySelector('#add-testimonial').addEventListener("click", addTestimonial);
}

document.querySelectorAll('.modal-body')[1].style.padding = "10px";
document.querySelectorAll('.modal-body')[1].style.backgroundColor = "white";
document.querySelectorAll('.modal-close-btn')[1].addEventListener("click", closeTestiModal);
document.querySelectorAll('.modal-close-btn > span')[1].addEventListener("click", closeTestiModal);
document.querySelector('.send-testi-btn').addEventListener('click', sendTestimonial);
