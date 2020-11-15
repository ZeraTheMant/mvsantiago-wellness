window.onload = function() {
    window.addEventListener("beforeunload", function (e) {
        if (formSubmitting) {
            return undefined;
        }

        var confirmationMessage = '';

        (e || window.event).returnValue = confirmationMessage; //Gecko + IE
        return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
    });
};

function changePassword(e){
	e.preventDefault();
	if(passwordChanges){
		setPasswordChanges(false);
	}
	else{
		setPasswordChanges(true);
	}
	changePasswordFormControl();
}

function checkIfConfirmPasswordsAreCorrect(){
	return (
		(document.querySelector('#newPass').value === document.querySelector('#newPassConfirm').value) &&
		(document.querySelector('#newPass').value.length > 5 && document.querySelector('#newPassConfirm').value.length > 5) &&
		(document.querySelector('#oldPass').value.length > 5)
	);
}

function changePasswordFormControl(){
	if(passwordChanges){

		document.querySelector('#change-password-form').style.opacity = 0;
		document.querySelector('#change-password-form').style.height = 0;
		document.querySelector('#change-password-form').style.marginTop = 0;
		document.querySelector('#change-password-form').style.overflow = 'hidden';
		
		for(var i=0; i<document.querySelectorAll('.end').length; i++){
			document.querySelectorAll('.end')[i].value = '';
		}
	}
	else{
		document.querySelector('#change-password-form').style.opacity = 1;
		document.querySelector('#change-password-form').style.height = 'auto';
		document.querySelector('#change-password-form').style.marginTop = '10px';
		document.querySelector('#change-password-form').style.overflow = 'visible';
	}
}

function setPasswordChanges(flag){ 
	if(flag){
		passwordChanges = true; 
	}
	else{
		passwordChanges = false; 
	}
}

function getUserFormValues(){
	return [
		document.querySelector('#user-id').textContent,
		document.querySelector('#user-email').value,
		document.querySelector('#user-contact').value,
		document.querySelector('#user-address').value,
		new_password,
		document.querySelector('#oldPass').value,
		document.querySelector('#newPassConfirm').value
	];
}

function manipulateUserSettings(dataArray, actionTaken){
	$.ajax({
		url: 'manipulate_user_settings.php',
		method: 'POST',
		data: {
			action: actionTaken,
			user_id: dataArray[0],
			email: dataArray[1],
			contact: dataArray[2],
			address: dataArray[3],
			newPass: dataArray[4],
			oldPass: dataArray[5],
			newPassConfirm: dataArray[6],
			user_img: dataArray[7]
		},
		success: function(response){
			if(response == ''){
				alert("Information not updated. Please alter data if you wish to update this your information.");
			}
			else{
				alert(response);
			}
			loadData();

		}
	});
}

function submitUserForm(){	
	loaderPopupControl(true);
	var dataArray = getUserFormValues();
	if(document.querySelector('#modal-img').value === ''){
		dataArray.push(document.querySelector('.modal-preview-img').src);
		manipulateUserSettings(dataArray, 'update');
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
			manipulateUserSettings(dataArray, 'update');
		};
		
		$.ajax(settings);
	}
}

function verify_submit_user_form(e){
	e.preventDefault();
	if(!passwordChanges){
		if(checkIfConfirmPasswordsAreCorrect()){
			new_password = document.querySelector('#newPass').value;
			submitUserForm();
		}
		else{
			alert("Password values are invalid.");
		}
	}
	else{
		submitUserForm();
	}
}

function fillUserSettingsForm(){
	document.querySelector('#user-id').textContent = currentUserID;
	document.querySelector('#user-full-name').textContent = currentUserFullName;
	document.querySelector('#user-email').value = currentUserEmail;
	document.querySelector('#user-contact').value = currentUserContact;
	document.querySelector('#user-dob').textContent = currentUserDOB;
	document.querySelector('#user-gender').textContent = currentUserGender;
	document.querySelector('#user-address').value = currentUserAddress;
	document.querySelector('#user-level').textContent = currentUserLevel;
	document.querySelector('.modal-preview-img').src = currentUserImg;
	document.querySelector('#oldPass').value = '';
	document.querySelector('#newPass').value = '';
	document.querySelector('#newPassConfirm').value = '';
}

function loadData(){
	$('.backend-body-content').html('');
	loaderPopupControl(true);
	$.ajax({
		url: "get_user_info.php",
		method: "GET",
		data: {user_id: currentUserID, getter: "backend"},
		success: function(result){
			$('.backend-body-content').html(result);
			document.querySelector('#modal-img').addEventListener('change', prePrevImg);
			document.querySelector('#change-password-btn').addEventListener('click', changePassword);
			document.querySelector('#user-settings-form').addEventListener('submit', verify_submit_user_form);
			changePasswordFormControl();
			loaderPopupControl(false);
		}
	});
}

var passwordChanges = true;
var formSubmitting = false;
var setFormSubmitting = function() { formSubmitting = true; };
var new_password = "";

loadData();

