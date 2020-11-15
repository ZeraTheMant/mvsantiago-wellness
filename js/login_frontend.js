function modalLogin(){
	modalControl(true, '', '');
	clearLoginRegForms();
	switchViewToLogin();
	document.querySelector(".navbar-menu-mobile").style.display = 'none';
}

function modalRegister(){
	modalControl(true, '', '');
	clearLoginRegForms();
	switchViewToReg();
}

function clearLoginRegForms(){
	document.querySelector('#login-email').value = '';
	document.querySelector('#login-password').value = '';
	
	document.querySelector('#register-fname').value = '';
	document.querySelector('#register-mi').value = '';
	document.querySelector('#register-lname').value = '';
	document.querySelector('#month-box').value = 'Month*';
	document.querySelector('#day-box').value = 'Day*';
	document.querySelector('#year-box').value = 'Year*';
	document.querySelector('#gender-box').value = 'Gender*';
	document.querySelector('#register-email').value = '';
	document.querySelector('#register-contact').value = '';
	document.querySelector('#register-address').value = '';
}

function setPopupHeaderText(text){
	document.querySelector('.modal-header-text').textContent = text;
}

function switchViewToReg(){
	setPopupHeaderText('Register');
	document.querySelector('#login-email').value = '';
	document.querySelector('#login-password').value = '';
	document.querySelector('#login-body').style.display = 'none';
	document.querySelector('#register-body').style.display = 'block';
	document.querySelector('.modal-content').style.height = '540px';
	document.querySelector('.modal-content').style.width = '600px';
}

function switchViewToLogin(){
	setPopupHeaderText('Login');

	document.querySelector('#register-body').style.display = 'none';
	document.querySelector('#login-body').style.display = 'block';
	document.querySelector('.modal-content').style.height = '310px';
	document.querySelector('.modal-content').style.width = '350px';
}

document.querySelector('#login-btn-header').addEventListener('click', modalLogin);
document.querySelector('#login-btn-header2').addEventListener('click', modalLogin);
document.querySelector('#register-btn-header').addEventListener('click', modalRegister);
document.querySelector('#register-btn-header2').addEventListener('click', modalRegister);
document.querySelector('#to-register').addEventListener('click', switchViewToReg);
document.querySelector('#to-login').addEventListener('click', switchViewToLogin);

document.querySelector('#go-to-book-appointment').onclick = function(){
	if(email === ""){
		alert("Please log-in first to schedule an appointment. If you do not have an account yet, please register first.");
		modalLogin();
	}
	else{
		window.location = "http://mvsantiagowellness.com/book_appointment.php";
	}
};
