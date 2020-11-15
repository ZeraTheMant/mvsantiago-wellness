function viewTestimonial(e){
	var arg = e.target.parentNode.children;
	document.querySelector('#first-modal-element').style.display = 'block';
	
	if(document.querySelector('#testimonial-status').value === "Approved testimonials"){
		document.querySelector('.approve-testi-btn').style.display = 'none';
		document.querySelector('.hide-testi-btn').style.display = 'inline';
		document.querySelector('.delete-testi-btn').style.display = 'inline';
	}
	else{
		document.querySelector('.approve-testi-btn').style.display = 'inline';
		document.querySelector('.hide-testi-btn').style.display = 'none';
		document.querySelector('.delete-testi-btn').style.display = 'inline';
	}
	
	modalControl(true, 'View Testimonial', '600px');
	fillCategoryForm(arg);
}

function fillCategoryForm(arg){
	document.querySelector('.modal-selected-id').textContent = arg[0].textContent;
	document.querySelector('#modal-sender-name').value = arg[1].textContent;
	document.querySelector('.modal-preview-img').src = arg[6].textContent;
	document.querySelector('#modal-sender-address').value = arg[2].textContent;
	document.querySelector('#modal-testimonial').value = arg[4].textContent;
	const selectedDOB = moment(arg[5].textContent);
	const datesDifference = moment.duration(moment().diff(selectedDOB));
	const age = parseInt(datesDifference.asYears());
	document.querySelector('#modal-sender-age').value = age;
}

function changeTestimonialView(e){
	if(e.target.value === "Approved testimonials"){
		loadData('get_testimonials.php', 'GET', {approval_status: '1', view: 'backend'}, viewTestimonial);
	}
	else{
		loadData('get_testimonials.php', 'GET', {approval_status: '0', view: 'backend'}, viewTestimonial);
	}
}

function manipulateTestimonials(action, testimonial_id){
	$.ajax({
		url: "manipulate_testimonials.php",
		method: "POST",
		data: {action: action, testimonial_id: document.querySelector('.modal-selected-id').textContent},
		success: function(result){
			if(document.querySelector('#testimonial-status').value === "Approved testimonials"){
				loadData('get_testimonials.php', 'GET', {approval_status: '1', view: 'backend'}, viewTestimonial);
			}
			else{
				loadData('get_testimonials.php', 'GET', {approval_status: '0', view: 'backend'}, viewTestimonial);
			}
			
			if(action === "approve"){
				alert("Testimonial approved.");
			}
			else if(action === "hide"){
				alert("Testimonial returned to pending.");
			}
			else{
				alert("Testimonial deleted.");
			}
			
			document.querySelector('.modal-popup').style.display = 'none';
			document.querySelector('.modal-form').reset();
			
			loaderPopupControl(false);
		}
	});
}

function changeTestimonialStatus(e){
	var action;
	if(e.target.textContent === "Approve"){
		action = "approve";
	}
	else if(e.target.textContent === "Return to pending"){
		action = "hide";
	}
	else{
		action = "delete";
	}
	
	if(action === "delete"){
		var confirmDelete = confirm("Delete this testimonial permanently?");
		if(confirmDelete){
			loaderPopupControl(true);
			manipulateTestimonials(action, document.querySelector('.modal-selected-id').textContent);
		}
	}
	else{
		loaderPopupControl(true);
		manipulateTestimonials(action, document.querySelector('.modal-selected-id').textContent);
	}
}


var tabButtons = document.querySelectorAll('.tab-container button');
var tabPanels = document.querySelectorAll('.tab-panel');

tabButtons[0].style.backgroundColor = "white";
tabPanels[0].style.display = "block";
tabPanels[0].style.backgroundColor = "white";
tabPanels[0].style.color = "black";

document.querySelector('.tab-container').style.display = 'grid';
document.querySelector('.tab-container').style.gridTemplateColumns = '1fr';
document.querySelector('#modal-img-container').style.marginTop = '30px';

document.querySelector('#testimonial-status').addEventListener('change', changeTestimonialView);
document.querySelector('.approve-testi-btn').addEventListener('click', changeTestimonialStatus);
document.querySelector('.hide-testi-btn').addEventListener('click', changeTestimonialStatus);
document.querySelector('.delete-testi-btn').addEventListener('click', changeTestimonialStatus);

loadData('get_testimonials.php', 'GET', {approval_status: '1', view: 'backend'}, viewTestimonial);