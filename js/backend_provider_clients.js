function resetCategoryForm(){
	document.querySelector('.modal-preview-img').src = "../../img/question_mark.png";
	document.querySelector('.modal-selected-id').textContent = '';
	document.querySelector('#modal-category-name').value = "";
	document.querySelector('#modal-description').value = "";
}

function isFormValid(){
	return document.querySelector('#modal-user-level').value !== '---Select user level---';
}

function verify_submit_modal_form(e){
	e.preventDefault();
	//alert(document.querySelector('#individual-category-table').rows.length);
	if(isFormValid()){
		submitCategoryForm(e);
	}
	else{
		alert("Please select a user level.");
	}
}

function submitCategoryForm(e){	
	loaderPopupControl(true);
	var actionTaken = "update";

	var dataArray = getCategoryFormValues();
	manipulateCategories(dataArray, actionTaken);
}

function manipulateCategories(dataArray, actionTaken){
	$.ajax({
		url: 'manipulate_clients.php',
		method: 'POST',
		data: {
			action: actionTaken,
			user_id: dataArray[1],
			user_level: dataArray[0]
		},
		success: function(response){
			if(response == ''){
				alert("Information not updated. Please alter data if you wish to update this client's information.");
			}
			else{
				alert(response);
			}
			loadData('get_clients.php', 'GET', {none: 'none'}, viewCategory);
			modalControl(false, 'Add Category', '600px');
		}
	});
}

function getCategoryFormValues(){
	return [
		document.querySelector('#modal-user-level').value[0].toLowerCase() + document.querySelector('#modal-user-level').value.slice(1),
		document.querySelector('.modal-selected-id').textContent
	];
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

	document.querySelector('#first-modal-element').style.display = 'block';

	$.ajax({
		url: 'get_user_services.php',
		method: 'GET',
		data: {client_id: arg[0].textContent},
		success: function(result){
			tabButtons[0].style.backgroundColor = "white";
			tabButtons[1].style.backgroundColor = "#eee";
			tabButtons[2].style.backgroundColor = "#eee";
			tabPanels[0].style.display = "block";
			tabPanels[1].style.display = "none";
			tabPanels[2].style.display = "none";
			
			var individual_services = JSON.parse(result);
			
			for(var i=0; i<individual_services.length; i++){
				getService(individual_services[i].category_name, individual_services[i].service_name, individual_services[i].category_id);
			}
			get_user_apps(arg[0].textContent, 0, 0);
			modalControl(true, 'View User', '600px');
			fillCategoryForm(arg);
			loaderPopupControl(false);
		}
	});
}

function removeService(e){
	var confirmDelete = confirm("Remove this service?");
	if(confirmDelete){
		e.target.parentNode.parentNode.parentNode.removeChild(e.target.parentNode.parentNode);
	}
}

function getService(category_select_val='', service_select_val="", category_idz=""){

	var newRow = document.createElement('tr');
	var newCol = document.createElement('td');
	var newCol2 = document.createElement('td');
	var newCol3 = document.createElement('td');
	
	var categorySelect = document.createElement('select');
	categorySelect.setAttribute('class', 'individual-category');
	categorySelect.style.width = "100%";
	categorySelect.addEventListener('change', changeCategory);
	var defaultCategory = document.createElement('option');
	defaultCategory.textContent = 'Select a category';
	defaultCategory.disabled = true;
	categorySelect.appendChild(defaultCategory);
	
	for(var x=0; x<categories.length; x++){
		var category = document.createElement('option');
		category.textContent = categories[x].category_name;
		category.setAttribute('id', categories[x].category_id);
		categorySelect.appendChild(category);
	}
	categorySelect.value = category_select_val;
	
	var serviceSelect = document.createElement('select');
	serviceSelect.style.width = "100%";
	serviceSelect.setAttribute('class', 'individual-service');
	var defaultService = document.createElement('option');
	defaultService.textContent = 'Select a service';
	defaultService.disabled = true;
	serviceSelect.appendChild(defaultService);
	
	if(category_idz){
		var selectedCategoryID;

		for(var x=0; x<categorySelect.children.length; x++){

			if(categorySelect.children[x].textContent === categorySelect.value){
				selectedCategoryID = categorySelect.children[x].id;
				break;
			}
		}

		for(var x=0; x<services.length; x++){
			if(selectedCategoryID === services[x].service_category_id){
				if(services[x].service_appears_on_first_time == "0"){
					var service = document.createElement('option');
					service.textContent = services[x].service_name;
					service.setAttribute('id', services[x].service_id);
					serviceSelect.appendChild(service);
				}	
			}
		}	
	}
	
	serviceSelect.value = service_select_val;
	
	var removeServiceBtn = document.createElement('button');
	removeServiceBtn.textContent = "Remove service";
	removeServiceBtn.addEventListener('click', removeService);
	removeServiceBtn.setAttribute('class', 'modal-btn full-width-btn');
	
	newCol.appendChild(categorySelect)
	newCol2.appendChild(serviceSelect);
	newCol3.appendChild(removeServiceBtn);
	newRow.appendChild(newCol);
	newRow.appendChild(newCol2);
	newRow.appendChild(newCol3);
	document.querySelector('#individual-category-table-body').appendChild(newRow);
}

function fillCategoryForm(arg){
	document.querySelector('.modal-selected-id').textContent = arg[0].textContent;
	document.querySelector('#modal-lname').value = arg[10].textContent;
	document.querySelector('#modal-fname').value = arg[8].textContent;
	document.querySelector('#modal-mname').value = arg[9].textContent;
	document.querySelector('#modal-name-ext').value = arg[11].textContent;
	document.querySelector('#modal-gender').value = arg[5].textContent;
	document.querySelector('#modal-user-level').value = arg[7].textContent[0].toUpperCase() + arg[7].textContent.slice(1);
	document.querySelector('.modal-preview-img').src = arg[6].firstChild.src;
	document.querySelector('#modal-address').value = arg[12].textContent;
	document.querySelector('#modal-dob').value = arg[4].textContent;
	document.querySelector('#modal-email').value = arg[2].textContent;
	document.querySelector('#modal-contact').value = arg[3].textContent;
	document.querySelector('#modal-age').value = arg[13].textContent;
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

function addService(){
	getService('Select a category', 'Select a service');
}

function changeCategory(e){
	e.target.parentNode.parentNode.children[1].lastChild.options.length = 0;
	
	var service_default = document.createElement('option');
	service_default.textContent = 'Select a service';
	service_default.disabled = true;
	service_default.selected = true;
	e.target.parentNode.parentNode.children[1].lastChild.appendChild(service_default);
		
	const category_id = e.target.options[e.target.selectedIndex].id;
	for(var i=0; i<services.length; i++){
		if(category_id == services[i].service_category_id){
			if(services[i].service_appears_on_first_time == "0"){
				var service = document.createElement('option');
				service.textContent = services[i].service_name;
				service.setAttribute('id', services[i].service_id);
				e.target.parentNode.parentNode.children[1].lastChild.appendChild(service);
			}
		}
	}
}

function saveClientServices(){
	loaderPopupControl(true);
	$.ajax({
		url: "manipulate_client_services.php",
		method: "POST",
		data: {client_id: document.querySelector(".modal-selected-id").textContent, categories_and_services: cat_id_and_serv_id()},
		success: function(result){
			alert("Service accessibility settings for this particular patient/client has been successfully updated.");
			loaderPopupControl(false);
		}
	});
}

function cat_id_and_serv_id(){
	var val_per_row = [];
	for(var i=0; i<document.querySelectorAll('#individual-category-table-body tr').length; i++){
		const category_value = document.querySelectorAll('#individual-category-table-body tr')[i].children[0].firstChild;
		var category_id;
		if(category_value.value !== 'Select a category'){
			for(var x=0; x<category_value.children.length; x++){
				if(category_value.value === category_value.children[x].textContent){
					category_id = category_value.children[x].id;
					break;
				}
			}
		}
		
		const service_value = document.querySelectorAll('#individual-category-table-body tr')[i].children[1].firstChild;
		var service_id;
		if(service_value.value !== 'Select a service'){
			for(var x=0; x<service_value.children.length; x++){
				if(service_value.value === service_value.children[x].textContent){
					service_id = service_value.children[x].id;
					break;
				}
			}
		}
		
		val_per_row.push(
			{
				category_id: category_id,
				service_id: service_id
			}
		)
	}
	return val_per_row;
}

function changeAppointmentView(e){
	if(e.target.value == "Upcoming Appointments"){
		get_user_apps(document.querySelector(".modal-selected-id").textContent, 0, 0);
	}
	else{
		get_user_apps(document.querySelector(".modal-selected-id").textContent, 1, 0);
	}
}


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

document.querySelector('#add-provider-service').addEventListener('click', addService);
document.querySelector("#save-client-services-settings").addEventListener("click", saveClientServices);
document.querySelector("#user-appointment-type").addEventListener("change", changeAppointmentView);

document.querySelector('#modal-img-container').style.marginTop = '30px';
document.querySelector('.modal-instructions').style.marginTop = '-30px';

//document.querySelector('.modal-form').addEventListener('submit', verify_submit_modal_form);
loadData('get_provider_clients.php', 'GET', {provider_id: provider_id}, viewCategory);
