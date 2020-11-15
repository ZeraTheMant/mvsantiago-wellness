function getContactInfo(){
	loaderPopupControl(true);
	$.ajax({
		url: 'php/backend/get_contact_info.php',
		method: 'GET',
		success: function(result){		
			var contact_info = JSON.parse(result);
			document.querySelector('#company-contact').textContent = contact_info.contact_number;
			document.querySelector('#company-hours').textContent = contact_info.business_hours;
			document.querySelector('#company-email').textContent = contact_info.contact_email;
			document.querySelector('#company-address').textContent = contact_info.contact_address;
			loaderPopupControl(false);
		}
		


	});
}

function isFormFilledUp(){
	return (contactFormName.value != '' && contactFormEmail.value != '' && contactFormContact.value != '' && contactFormMessage.value != '');
}

function sendMessage(){
	var dateSent = moment().format('YYYY-MM-DD HH:mm:ss');
	$.ajax({
		url: 'php/backend/insert_contact_message.php',
		method: 'POST',
		data: {is_read: 1, dateSent: dateSent, senderName: contactFormName.value, senderEmail: contactFormEmail.value, senderContact: contactFormContact.value, senderMessage: contactFormMessage.value},
		success: function(data){
			
			/*$.ajax({
			   url: '../../backend/itexmo.php',
			   method: 'POST',
			   data: {
				   number: document.querySelector('#contact-form-contact').value,
				   message: 'Hello, ' + document.querySelector('#contact-form-name').value + ', your message has been received.',
			   },
			   success: function(result){

			   }
			});*/
			
			
			const body = contactFormName.value + " has sent a message.";
			//insertNotif(data, body, 'message');
			alert("Message sent to the administrator. Thank you for contacting us.");
			loaderPopupControl(false);
			contactForm.reset();
		}
	});
}


const selectedHref = 'front end';
var contactForm = document.querySelector('#contact-form');
var contactFormName = document.querySelector('#contact-form-name');
var contactFormEmail = document.querySelector('#contact-form-email');
var contactFormContact = document.querySelector('#contact-form-contact');
var contactFormMessage = document.querySelector('#contact-form-message');
var showCaseInfoContent = window.getComputedStyle(document.querySelector('#contact-form-container'));
//alert(parseInt(showCaseInfoContent.getPropertyValue("width").slice(0, -2)))
document.querySelector('iframe').setAttribute('width', parseInt(showCaseInfoContent.getPropertyValue("width").slice(0, -2)))

		
contactForm.onsubmit = function(e){
	e.preventDefault();
	if(isFormFilledUp()){
		loaderPopupControl(true);
		sendMessage();
	}
	else{
		alert("Please fill up the contact form completely");
	}
};

getContactInfo();