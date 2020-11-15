function modalControl(flag, headerText, width){
	if(flag){
		document.querySelector('.modal-popup').style.display = 'flex';
		document.querySelector('.modal-popup').style.alignItems = 'center';
		document.querySelector('.modal-content').style.width = width;
		document.querySelector('.modal-header-text').textContent = headerText;
	}
	else{
		document.querySelector('.modal-popup').style.display = 'none';
		document.querySelector('.modal-form').reset();
		//document.querySelector('.services-form').reset();
	}
}

function closePopup(e){
	if(e.target === document.querySelector('.modal-close-btn') ||
	   e.target === document.querySelector('.modal-close-btn > span')){
		document.querySelector('.modal-popup').style.display = 'none';
		document.querySelector('.modal-form').reset();
		if(document.querySelector('#reschedule-form-content')){
			document.querySelector('#modal-form-content').style.display = "block";
			document.querySelector('#reschedule-form-content').style.display = "none";
			document.querySelector('.modal-content').style.width = "400px";
			document.querySelector('#no-display').style.display = "none";
			$('#resched-timeslot-container').html("");
		}
		if(document.querySelector('#cancellation-form-content')){
			document.querySelector('#cancellation-form-content').style.display = "none";
		}
		//document.querySelector('.services-form').reset();
		//document.querySelector('#preview-img').src = '';
	}
}


document.querySelector('.modal-close-btn').addEventListener('click', closePopup);
document.querySelector('.modal-close-btn > span').addEventListener('click', closePopup);