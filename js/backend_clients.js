function resetCategoryForm(){
	document.querySelector('.modal-preview-img').src = "../../img/question_mark.png";
	document.querySelector('.modal-selected-id').textContent = '';
	document.querySelector('#modal-category-name').value = "";
	document.querySelector('#modal-description').value = "";
}

function isFormValid(){
	return (
		document.querySelector('#admin-email').value !== '' &&
		document.querySelector('#admin-lname').value !== '' &&
		document.querySelector('#admin-mname').value !== '' &&
		document.querySelector('#admin-fname').value !== '' &&
		document.querySelector('#admin-month-box').value !== 'Month*' &&
		document.querySelector('#admin-day-box').value !== 'Month*' &&
		document.querySelector('#admin-year-box').value !== 'Month*'
	);
}

function verify_submit_modal_form(e){
	e.preventDefault();
	//alert(document.querySelector('#individual-category-table').rows.length);
	if(isFormValid()){
	   	submitCategoryForm(e, 'insert')
	}
	else{
		alert("Please fill up all the required fields.");
	}
}

function manipulateCategories(e, actionTaken, img_link){
	const monthString = document.querySelector('#admin-month-box').value;
	const dayString = document.querySelector('#admin-day-box').value;
	const yearString = document.querySelector('#admin-year-box').value;
	const dob = moment(monthString + " " + dayString + " " + yearString).format("YYYY-MM-DD");
	$.ajax({
		url: 'manipulate_clients.php',
		method: 'POST',
		data: {
			action: actionTaken,
			email: document.querySelector('#admin-email').value,
			lname: document.querySelector('#admin-lname').value,
			mname: document.querySelector('#admin-mname').value,
			fname: document.querySelector('#admin-fname').value,
			name_ext: document.querySelector('#admin-name-ext').value,
			dob: dob,
			user_level: 'admin',
			user_img: img_link
		},
		success: function(response){
			alert(response)
			loadData('get_clients.php', 'GET', {user_level: getUsersView()}, viewCategory);
			emailPopup(false);
			loaderPopupControl(false);	
		},
		error: function(result){
		    alert(result);
		}
	});
}

function submitCategoryForm(e, actionTaken){
	loaderPopupControl(true);
	if(document.querySelector('#admin-img').value === ''){
		manipulateCategories(e, actionTaken, document.querySelector('.admin-preview-img').src)
	}
	else{
		var $files = $('#admin-img').get(0).files;
		var formData = new FormData();
		formData.append("image", $files[0]);
		var settings = getImgurDict();		
		settings.data = formData;
		settings.success = function(response){
			const imgurResponse = JSON.parse(response);
			const imgLink = imgurResponse.data.link;
			manipulateCategories(e, actionTaken, imgLink);
		};
		
		$.ajax(settings);
	}
}

function viewCategory(e){
	loaderPopupControl(true);
	$('#individual-category-table-body').html('');

	var arg;
	if(e.target.className != 'backend-img-thumb'){
		arg =  e.target.parentNode.children;
	}
	else{
		arg = e.target.parentNode.parentNode.children;
	}
	
	if(document.querySelector('#client-status').value == "Regular users"){
	    document.querySelector('#conditions-container').style.display = 'block';
	}
	else{
	     document.querySelector('#conditions-container').style.display = 'none';
	}

	document.querySelector('#first-modal-element').style.display = 'block';

	$.ajax({
		url: 'get_user_services.php',
		method: 'GET',
		data: {client_id: arg[14].textContent},
		success: function(result){
			tabButtons[0].style.backgroundColor = "white";
			tabButtons[1].style.backgroundColor = "#eee";
			tabButtons[2].style.backgroundColor = "#eee";
			tabPanels[0].style.display = "block";
			tabPanels[1].style.display = "none";
			tabPanels[2].style.display = "none";
			
			var individual_services = JSON.parse(result);
			
			for(var i=0; i<individual_services.length; i++){
				getService(individual_services[i].category_name, individual_services[i].service_name);
			}
			get_user_apps(arg[0].textContent, 0, 0);
			modalControl(true, 'View Account', '800px');
			fillCategoryForm(arg);
			get_user_apps(arg[14].textContent, 0, 0);
			loaderPopupControl(false);
		}
	});
}

function getService(category_name, service_name){
	var newRow = document.createElement('tr');
	var newCol = document.createElement('td');
	var newCol2 = document.createElement('td');
	
	var category = document.createElement('input');
	category.style.width = "100%";
	category.value = category_name;
	category.disabled = true;
	
	var service = document.createElement('input');
	service.style.width = "100%";
	service.value = service_name;
	service.disabled = true;
	
	newCol.appendChild(category);
	newCol2.appendChild(service);
	newRow.appendChild(newCol);
	newRow.appendChild(newCol2);
	document.querySelector('#individual-category-table-body').appendChild(newRow);
}

function fillCategoryForm(arg){
	document.querySelector('.modal-selected-id').textContent = arg[0].textContent;
	document.querySelector('.modal-selected-id').name = arg[14].textContent;
	document.querySelector('#modal-lname').value = arg[10].textContent;
	document.querySelector('#modal-fname').value = arg[8].textContent;
	document.querySelector('#modal-mname').value = arg[9].textContent;
	document.querySelector('#modal-name-ext').value = arg[11].textContent;
	//document.querySelector('.modal-preview-img').src = arg[6].firstChild.src;
	document.querySelector('#modal-address').value = arg[12].textContent;
	document.querySelector('#modal-dob').value = arg[4].textContent;
	document.querySelector('#modal-email').value = arg[2].textContent;
	document.querySelector('#modal-contact').value = arg[3].textContent;
	document.querySelector('#modal-age').value = arg[13].textContent;
	
	if(arg[15].textContent == '0'){
	    document.querySelector('#heart-bullet').style.display = 'none';
	}
	else{
	    document.querySelector('#heart-bullet').style.display = 'block';
	}
	
	if(arg[16].textContent == '0'){
	    document.querySelector('#skin-bullet').style.display = 'none';
	}
	else{
	    document.querySelector('#skin-bullet').style.display = 'block';
	}
	
	if(arg[17].textContent == '0'){
	    document.querySelector('#allergy-bullet').style.display = 'none';
	}
	else{
	    document.querySelector('#allergy-bullet').style.display = 'block';
	}
}

function getPanel(tab){
	var panel;
	if(tab === 'Personal Information'){
		panel = '#panel-1';
	}
	else if(tab === 'Eligible Services'){
		panel = '#panel-2';
	}
	else{
		panel = '#panel-3';
	}
	return panel;
}

function showPanels(e){
	tabButtons.forEach(function(node){
		node.style.backgroundColor = "";
		node.style.color = "black";
	});
	
	tabPanels.forEach(function(node){
		node.style.display = "none";
	});
	
	e.target.style.backgroundColor = "white";
	var panel = getPanel(e.target.textContent);
	
	document.querySelector(panel).style.display = "block";
	document.querySelector(panel).style.backgroundColor = "white";
	document.querySelector(panel).style.color = "black";
}

function get_user_apps(userId, is_cancelled, is_finished){
	$('#individual-appointment-table').html('');
	$.ajax({
		url: 'get_appointments_of_user_backend.php',
		method: 'GET',
		data: {client_id: userId, is_cancelled: is_cancelled, is_finished: is_finished},
		success: function(result){
			//alert(result);
			$('#individual-appointment-table').html(result);
		}
	});
}

function changeAppointmentView(e){
	if(e.target.value == "Upcoming Appointments"){
		get_user_apps(document.querySelector(".modal-selected-id").name, 0, 0);
	}
	else{
		get_user_apps(document.querySelector(".modal-selected-id").name, 1, 0);
	}
}

function getUsersView(){
	if(document.querySelector("#client-status").value === "Administrators"){
		return "admin";
	}
	else{
		return "client";
	}
}

function getUsersView2(){
	if(document.querySelector("#client-status").value === "Administrators"){
		return "Regular users";
	}
	else{
		return "Administrators";
	}
}

function changeUsersView(){
	loadData('get_clients.php', 'GET', {user_level: getUsersView()}, viewCategory);
}

function changeUserLevel(){
	loaderPopupControl(true);
	const newView = document.querySelector("#modal-user-level").value.toLowerCase();
	document.querySelector("#client-status").value = getUsersView2();
	$.ajax({
		url: "change_user_level.php",
		method: "POST",
		data: {client_id: document.querySelector(".modal-selected-id").textContent, user_level: newView},
		success: function(result){
			alert("User level successfully changed.");
			loadData('get_clients.php', 'GET', {user_level: newView}, viewCategory);
			modalControl(false, 'View Account', '800px');
			loaderPopupControl(false);
		}
	});
}

function createAdminPopup(){
	modalControl(false, "", "");
	emailPopup(true);
}

function emailPopup(flag){ 
	if(flag){
		document.querySelector('#emailPopup').style.display = 'flex';
		document.querySelector('#emailPopup').style.alignItems = 'center';
	}
	else{
		document.querySelector('#emailPopup').style.display = 'none';
	}
}

function closeEmailPopup(e){
	emailPopup(false);
	document.querySelector('#emailForm').reset();
}

function clearModalImg(e){
	var link;
	if(e.target.id === 'admin-img-clear-btn-from-backend-providers'){
		link = '../../img/empty_imgs.png';
	}
	else{
		link = '../../img/question_mark.png';
	}
	document.querySelector('.admin-preview-img').src = link;
	document.querySelector('#admin-img').value = '';
}

function prevImg(input){
	var reader = new FileReader();
	reader.onload = function(e){
		document.querySelector('.admin-preview-img').src = e.target.result;
	};
	reader.readAsDataURL(input.files[0]);
}

function prePrevImg(){
	prevImg(this);
}

function createBaseDay(){
	var baseDayOption = document.createElement('option');
	baseDayOption.textContent = 'Day*';
	baseDayOption.selected = true;
	baseDayOption.disabled = true;
	document.querySelector('#admin-day-box').appendChild(baseDayOption);
}

function leapYear(year){
  return ((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0);
}

function addDays(dayNum){
	var day = document.createElement('option');
	day.textContent = dayNum;
	document.querySelector('#admin-day-box').appendChild(day);
}

function checkDays(){
	document.querySelector('#admin-day-box').length = 0;
	createBaseDay();
	for(var i=1; i<32; i++){
		if(getMonthCategory(document.querySelector('#admin-month-box').value) === '30days' && i === 31){
			break;
		}
		if(getMonthCategory(document.querySelector('#admin-month-box').value) === 'feb' && i === 29){
			if(leapYear(document.querySelector('#admin-year-box').value)){
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
	
for(var i=2000; i>1899; i--){
	var year = document.createElement('option');
	year.textContent = i;
	document.querySelector('#admin-year-box').appendChild(year);
}

document.querySelector('#admin-month-box').addEventListener('change', changeAvailableDays);
document.querySelector('#admin-year-box').addEventListener('change', changeAvailableDays);

createBaseDay();
checkDays();

var tabButtons = document.querySelectorAll('.tab-container button');
var tabPanels = document.querySelectorAll('.tab-panel');

tabButtons.forEach(function(node){
	node.addEventListener('click', showPanels);
});

tabButtons[0].style.backgroundColor = "white";
tabPanels[0].style.display = "block";
tabPanels[0].style.backgroundColor = "white";
tabPanels[0].style.color = "black";

document.querySelector('.tab-container').style.display = 'grid';
document.querySelector('.tab-container').style.gridTemplateColumns = '1fr 1fr 1fr';

document.querySelector("#user-appointment-type").addEventListener("change", changeAppointmentView);
document.querySelector("#client-status").addEventListener("change", changeUsersView);
document.querySelector("#change-user-settings").addEventListener('click', changeUserLevel);
document.querySelector("#create-admin").addEventListener('click', createAdminPopup);
document.querySelector('#emailPopup .popupCloseBtn').addEventListener('click', closeEmailPopup);
document.querySelector('#emailPopup .popupCloseBtn > span').addEventListener('click', closeEmailPopup);

document.querySelector('.admin-clear-img').addEventListener('click', clearModalImg);
document.querySelector('#admin-img').addEventListener('change', prePrevImg);

document.querySelector('#modal-img-container').style.marginTop = '30px';
document.querySelector('.modal-instructions').style.marginTop = '-30px';

document.querySelector('#emailForm').addEventListener('submit', verify_submit_modal_form);
loadData('get_clients.php', 'GET', {user_level: getUsersView()}, viewCategory);
