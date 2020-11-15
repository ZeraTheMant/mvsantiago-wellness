for(var i=2018; i>1899; i--){
	var year = document.createElement('option');
	year.textContent = i;
	document.querySelector('#year-box').appendChild(year);
}

function createBaseDay(){
	var baseDayOption = document.createElement('option');
	baseDayOption.textContent = 'Day*';
	baseDayOption.selected = true;
	baseDayOption.disabled = true;
	document.querySelector('#day-box').appendChild(baseDayOption);
}

function leapYear(year){
  return ((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0);
}

function addDays(dayNum){
	var day = document.createElement('option');
	day.textContent = dayNum;
	document.querySelector('#day-box').appendChild(day);
}

function checkDays(){
	document.querySelector('#day-box').length = 0;
	createBaseDay();
	for(var i=1; i<32; i++){
		if(getMonthCategory(document.querySelector('#month-box').value) === '30days' && i === 31){
			break;
		}
		if(getMonthCategory(document.querySelector('#month-box').value) === 'feb' && i === 29){
			if(leapYear(document.querySelector('#year-box').value)){
				addDays(i);
				break;
			}
			else{
				break;
			}
		}
		addDays(i);
	}
}

function getMonthCategory(month){
	var monthCategory;
	
	if(month === 'February'){
		monthCategory = 'feb';
	}
	else if(month === 'April' || month === 'June' || month === 'September' || month === 'November'){
		monthCategory = '30days'
	}
	else if(month === 'Day*'){
		monthCategory = 'none';
	}
	else{
		monthCategory = '31days';
	}
	return monthCategory
}

function changeAvailableDays(){
	checkDays();
}

function isLoginFormFilledUp(){
	return document.querySelector('#login-email').value !== '' && document.querySelector('#login-password').value !== '';
}

function loginUser(){
	loaderPopupControl(true);
	$.ajax({
		url: linkViewer + 'login_official.php',
		method: 'POST',
		data: {
			email: document.querySelector('#login-email').value,
			password: document.querySelector('#login-password').value,
			initial_query: "SELECT * FROM clients WHERE email = :email"
		},
		success: function(result){
			if(result === 'credentials'){
				alert("Login failed. Invalid credentials given. Please try again.");
				loaderPopupControl(false);
			}
			else if(result === 'email'){
				loaderPopupControl(false);
				alert("Your email address is not yet verified. Please verify it first before you can log-in.");
			}
			else{
				alert("Login success. Welcome, " + result + '.');
				modalControl(false, '', '');
				location.reload();

			}
		},
		error: function(result){
			alert("Login failed. Invalid credentials given. Please try again.");
			loaderPopupControl(false);
			//modalControl(false, '', '');
			//location.reload();
		}
	});
}

function loginUserCheck(){
	if(isLoginFormFilledUp()){
		loginUser();
	}
	else{
		alert("Please fill up the login form.");
	}
}

function isRegisterFormFilledUp(){
	return (
		document.querySelector('#register-fname').value !== '' &&
		document.querySelector('#register-mi').value !== '' &&
		document.querySelector('#register-lname').value !== '' &&
		document.querySelector('#register-email').value !== '' &&
		document.querySelector('#register-contact').value !== '' &&
		document.querySelector('#register-address').value !== '' &&
		document.querySelector('#month-box').value !== 'Month*' &&
		document.querySelector('#day-box').value !== 'Day*' &&
		document.querySelector('#year-box').value !== 'Year*' &&
		document.querySelector('#gender-box').value !== 'Gender*'
	);
}

document.querySelector('#register-contact').onkeyup = function(e){
	document.querySelector('#register-contact').style.border = '';
	for(var i=0; i<document.querySelector('#register-contact').value.length ; i++){
		if(numbers_one_to_ten.indexOf(parseInt(document.querySelector('#register-contact').value[i])) === -1){
			document.querySelector('#register-contact').style.border = '3px solid red';
			break;
		}
	}
}

function getCondition(check_box){
    if(check_box.checked){
        return 1;
    }
    else{
        return 0;
    }
}

function resetRegisterForm(){
	document.querySelector('#register-fname').value = '';
	document.querySelector('#register-lname').value = '';
	document.querySelector('#register-mi').value = '';
	document.querySelector('#register-email').value = '';
	document.querySelector('#register-contact').value = '';
	document.querySelector('#month-box').value = 'Month*';
	document.querySelector('#day-box').value = 'Day*';
	document.querySelector('#year-box').value = 'Year';
	document.querySelector('#gender-box').value = 'Gender*';
	document.querySelector('#register-address').value = '';
	document.querySelector('#heart-cond').checked = false;
	document.querySelector('#skin-cond').checked = false;
	document.querySelector('#allergy').checked = false;
}

function registerUser(){
	loaderPopupControl(true);
	$.ajax({
		url: linkViewer + 'register_official.php',
		method: 'POST',
		data: {
			fname: document.querySelector('#register-fname').value,
			lname: document.querySelector('#register-lname').value,
			mi: document.querySelector('#register-mi').value,
			email: document.querySelector('#register-email').value,
			contact_number: document.querySelector('#register-contact').value,
			dob: moment(document.querySelector('#month-box').value + ' ' + document.querySelector('#day-box').value + ', ' + document.querySelector('#year-box').value).format('YYYY-MM-DD'),
			gender: document.querySelector('#gender-box').value,
			address: document.querySelector('#register-address').value,
			user_level: 'client',
			heart_cond: getCondition(document.querySelector('#heart-cond')),
			skin_cond: getCondition(document.querySelector('#skin-cond')),
			allergy: getCondition(document.querySelector('#allergy'))
		},
		success: function(result){
			if(result == 1){
				alert("Registration successful. An email has been sent to your address to verify your account. Only verified users can log-in.");
				loaderPopupControl(false);
				switchViewToLogin();
			}
			else{
				alert(result);
				loaderPopupControl(false);
			}
		},
		error: function(result){
			loaderPopupControl(false);
			modalControl(false, '', '');
		}
	});
}

function registerFormCheck(){
	if(isRegisterFormFilledUp()){
		registerUser();
	}
	else{
		alert("Please fill up the registration form.");
	}
}

createBaseDay();
checkDays();
var numbers_one_to_ten = [0, 1, 2, 3, 4, 5, 6, 8, 9];


document.querySelector('#month-box').addEventListener('change', changeAvailableDays)
document.querySelector('#year-box').addEventListener('change', changeAvailableDays)
document.querySelector('#login-btn').addEventListener('click', loginUserCheck);
document.querySelector('#register-btn').addEventListener('click', registerFormCheck);