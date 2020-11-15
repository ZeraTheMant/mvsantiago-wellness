function disableDays(){
	for(var i=0; i<daysOfTheWeek.length; i++){
		document.querySelector('#' + daysOfTheWeek[i].toLowerCase() + '').checked = false;
		document.querySelector('#' + daysOfTheWeek[i] + '-time-start').disabled = true;
		document.querySelector('#' + daysOfTheWeek[i] + '-time-end').disabled = true;
		
		document.querySelector('#' + daysOfTheWeek[i] + '-time-start').value = "";
		document.querySelector('#' + daysOfTheWeek[i] + '-time-end').value = "";
	}
}

function viewProvider(e){
	$('#individual-category-table-body').html('');
	var arg;
	if(e.target.className != 'backend-img-thumb'){
		arg =  e.target.parentNode.children;
	}
	else{
		arg = e.target.parentNode.parentNode.children;
	}
	
	disableDays();
	
	loaderPopupControl(true);
	$.ajax({
		url: 'get_individual_provider_services.php',
		method: 'GET',
		data: {
			provider_id: arg[0].textContent
		},
		success: function(result){
			tabButtons[0].style.backgroundColor = "white";
			tabButtons[1].style.backgroundColor = "#eee";
			tabButtons[2].style.backgroundColor = "#eee";
			tabPanels[0].style.display = "block";
			tabPanels[1].style.display = "none";
			tabPanels[2].style.display = "none";
			document.querySelector('#first-modal-element').style.display = 'block';
			document.querySelector('.add-modal-btn').style.display = 'none';
			document.querySelector('.update-modal-btn').style.display = 'inline';
			document.querySelector('.delete-modal-btn').style.display = 'inline';
			document.querySelector('.modal-preview-img').src = "../../img/empty_imgs.png";
			
			fillModalForm(arg);
			
			modalControl(true, 'View Provider', '600px');
			
			var individual_services = JSON.parse(result);
			
			for(var i=0; i<individual_services.length; i++){
				getService(individual_services[i].category_name, individual_services[i].service_name, individual_services[i].category_id);
			}
			
			fillTimeBoxes(JSON.parse(individual_services[0].days_worked));
			
			loaderPopupControl(false);
		}
	});
}

function fillTimeBoxes(days_worked_dict){
	for(var key in days_worked_dict){
		document.querySelector('#' + key.toLowerCase() + '').checked = true;
		document.querySelector('#' + key.toLowerCase() + '-time-start').disabled = false;
		document.querySelector('#' + key.toLowerCase() + '-time-end').disabled = false;
		
		document.querySelector('#' + key.toLowerCase() + '-time-start').value = days_worked_dict[key]['start'];
		document.querySelector('#' + key.toLowerCase() + '-time-end').value = days_worked_dict[key]['end'];
	}
}

function removeService(e){
	var confirmDelete = confirm("Remove this service?");
	if(confirmDelete){
		e.target.parentNode.parentNode.parentNode.removeChild(e.target.parentNode.parentNode);
	}
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
			var service = document.createElement('option');
			service.textContent = services[i].service_name;
			service.setAttribute('id', services[i].service_id);
			e.target.parentNode.parentNode.children[1].lastChild.appendChild(service);
		}
	}
}

function fillModalForm(arg){
	document.querySelector('.modal-selected-id').textContent = arg[0].textContent;
	document.querySelector('#modal-lname').value = arg[7].textContent;
	document.querySelector('#modal-fname').value = arg[5].textContent;
	document.querySelector('#modal-mname').value = arg[6].textContent;
	document.querySelector('#modal-name-ext').value = arg[8].textContent;
	document.querySelector('#modal-gender').value = arg[4].textContent;
	document.querySelector('#modal-position').value = arg[3].textContent;
	document.querySelector('#modal-bio').value = arg[9].textContent;
}

function addProvider(){
	/*tabButtons[1].style.backgroundColor = "#eee";
	tabPanels[0].style.display = "block";
	tabPanels[1].style.display = "none";*/
	tabButtons[0].style.backgroundColor = "white";
	tabButtons[1].style.backgroundColor = "#eee";
	tabButtons[2].style.backgroundColor = "#eee";
	tabPanels[0].style.display = "block";
	tabPanels[1].style.display = "none";
	tabPanels[2].style.display = "none";
	
	document.querySelector('#first-modal-element').style.display = 'none';
	document.querySelector('.add-modal-btn').style.display = 'inline';
	document.querySelector('.update-modal-btn').style.display = 'none';
	document.querySelector('.delete-modal-btn').style.display = 'none';
	document.querySelector('.modal-preview-img').src = "../../img/empty_imgs.png";
	document.querySelector('.modal-selected-id').textContent = '';
	$('#individual-category-table-body').html('');
	disableDays();
	modalControl(true, 'Add Provider', '600px');
}

function getPanel(tab){
	var panel;
	if(tab === 'Personal Information'){
		panel = '#panel-1';
	}
	else if(tab === 'Services'){
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

function isFormValid(){
	return (
		document.querySelector('#modal-lname').value.trim()  !== '' &&
		document.querySelector('#modal-fname').value.trim()   !== '' &&
		document.querySelector('#modal-mname').value.trim()   !== '' &&
		document.querySelector('#modal-gender').value  !== '---Select gender---' &&
		document.querySelector('#modal-position').value  !== '---Select position---' &&
		document.querySelector('#modal-bio').value.trim()  !== ''
	);
	
}

function areAllServicesUnique(){
	if(document.querySelector('#individual-category-table').rows.length == 1){
		alert("Provider must at least have 1 service.");
		return false;
	}
	var category_and_service;
	for(var i=1; i<document.querySelector('#individual-category-table').rows.length; i++){
		if(document.querySelector('#individual-category-table').rows[i].cells[0].firstChild.value === 'Select a category' || document.querySelector('#individual-category-table').rows[i].cells[1].firstChild.value === 'Select a service'){
			alert("Please select a category and service.");
			return false;
		}
		if(category_and_service === document.querySelector('#individual-category-table').rows[i].cells[0].firstChild.value + ' ' + document.querySelector('#individual-category-table').rows[i].cells[1].firstChild.value){
			alert("Cannot have duplicate services.");
			return false;
		}
		category_and_service = document.querySelector('#individual-category-table').rows[i].cells[0].firstChild.value + ' ' + document.querySelector('#individual-category-table').rows[i].cells[1].firstChild.value;
	}
	return true;
}

function isProviderDaysValid(){
	var at_least_1_day_checked = false;
	for(var i=0; i<document.querySelectorAll('.checkDay').length; i++){
		if(document.querySelectorAll('.checkDay')[i].checked){
			at_least_1_day_checked = true;
			if((!moment(document.querySelectorAll('.checkDay')[i].parentNode.parentNode.parentNode.parentNode.children[1].children[0].value, 'HH:mm', true).isValid() && moment(document.querySelectorAll('.checkDay')[i].parentNode.parentNode.parentNode.parentNode.children[2].children[0].value, 'HH:mm', true).isValid()) || (document.querySelectorAll('.checkDay')[i].parentNode.parentNode.parentNode.parentNode.children[1].children[0].value >= document.querySelectorAll('.checkDay')[i].parentNode.parentNode.parentNode.parentNode.children[2].children[0].value)){
				return false;
			}
		}
	}
	return at_least_1_day_checked;
}

function verify_submit_modal_form(e){
	e.preventDefault();
	//alert(document.querySelector('#individual-category-table').rows.length);
	if(isFormValid()){
		if(areAllServicesUnique()){
			if(isProviderDaysValid()){
				submitProviderForm(e);
			}

			else{
				alert("Working days and hours settings error. Please double check the provider settings if the values are valid.");
			}
		}
	}
	else{
		alert("Please fill out all the required fields.");
	}
}

function manipulateProviders(dataArray, actionTaken){
	$.ajax({
		url: 'manipulate_providers.php',
		method: 'POST',
		data: {
			action: actionTaken,
			provider_fname: dataArray[1],
			provider_lname: dataArray[0],
			provider_mname: dataArray[2],
			provider_name_ext: dataArray[3],
			provider_bio: dataArray[6],
			provider_img: dataArray[10],
			provider_level: dataArray[5],
			provider_gender: dataArray[4],
			provider_id: dataArray[7],
			days_worked: dataArray[8],
			cat_and_serv_id: dataArray[9]
		},
		success: function(response){
			if(response == ''){
				alert("Information not updated. Please alter data if you wish to update this provider's information.");
			}
			else{
				alert(response);
			}
			loadData('get_providers.php', 'GET', {none: 'none'}, viewProvider);
			modalControl(false, 'View Provider', '600px');
		}
	});
}

function getProviderWorkingDays(){
	var working_days = {};
	for(var i=0; i<document.querySelectorAll('.checkDay').length; i++){
		if(document.querySelectorAll('.checkDay')[i].checked){
			working_days[document.querySelectorAll('.checkDay')[i].parentNode.textContent] = {
				'start': document.querySelectorAll('.checkDay')[i].parentNode.parentNode.parentNode.parentNode.children[1].children[0].value,
				'end': document.querySelectorAll('.checkDay')[i].parentNode.parentNode.parentNode.parentNode.children[2].children[0].value
			}
		}
	}
	return working_days;
}

function getProviderFormValues(){
	var working_days = getProviderWorkingDays();
	return [
		document.querySelector('#modal-lname').value,
		document.querySelector('#modal-fname').value,
		document.querySelector('#modal-mname').value,
		document.querySelector('#modal-name-ext').value,
		document.querySelector('#modal-gender').value,
		document.querySelector('#modal-position').value,
		document.querySelector('#modal-bio').value,
		document.querySelector('.modal-selected-id').textContent,
		working_days,
		cat_id_and_serv_id()
	];
}

function submitProviderForm(e){	
	loaderPopupControl(true);
	var actionTaken;
	if(document.querySelector('.modal-selected-id').textContent === ''){
		actionTaken = 'insert';
	}
	else{
		actionTaken = 'update';
	}
	var dataArray = getProviderFormValues();
	if(document.querySelector('#modal-img').value === ''){
		dataArray.push(document.querySelector('.modal-preview-img').src);
		manipulateProviders(dataArray, actionTaken);
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
			manipulateProviders(dataArray, actionTaken);
		};
		
		$.ajax(settings);
	}
}

function deleteProvider(){
	const confirmDelete = confirm("Delete this provider?");
	if(confirmDelete){
		loaderPopupControl(true);
		var dataArray = getProviderFormValues();
		manipulateProviders(dataArray, 'delete');
	}
}

function addService(){
	getService('Select a category', 'Select a service');
}

function getService(category_select_val='', service_select_val="", category_idz=""){
	var newRow = document.createElement('tr');
	var newCol = document.createElement('td');
	var newCol2 = document.createElement('td');
	var newCol3 = document.createElement('td');
	
	var categorySelect = document.createElement('select');
	categorySelect.setAttribute('class', 'individual-category');
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
	categorySelect.setAttribute('class', 'individual-service');
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
				var service = document.createElement('option');
				service.textContent = services[x].service_name;
				service.setAttribute('id', services[x].service_id);
				serviceSelect.appendChild(service);	
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

//showPanels(tabButtons[0]);

var tabButtons = document.querySelectorAll('.tab-container button');
var tabPanels = document.querySelectorAll('.tab-panel');
var provider_services;
const daysOfTheWeek = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

tabButtons.forEach(function(node){
	node.addEventListener('click', showPanels);
});

//var info_window_style = window.getComputedStyle(tabPanels[0]);
//alert(info_window_style.getPropertyValue('height'))

tabButtons[0].style.backgroundColor = "white";
tabPanels[0].style.display = "block";
tabPanels[0].style.backgroundColor = "white";
tabPanels[0].style.color = "black";


loadData('get_providers.php', 'GET', {none: 'none'}, viewProvider);
getProviderServices("get_category_services.php", '#shit', 'backend');
document.querySelector('.backend-body-add-btn').addEventListener('click', addProvider);
document.querySelector('.delete-modal-btn').addEventListener('click', deleteProvider);
document.querySelector('.modal-form').addEventListener('submit', verify_submit_modal_form);
document.querySelector('#add-provider-service').addEventListener('click', addService);

document.querySelector('.tab-container').style.display = 'grid';
document.querySelector('.tab-container').style.gridTemplateColumns = '1fr 1fr 1fr';
document.querySelector('.modal-form').style.display = 'grid';
document.querySelector('.modal-form').style.gridTemplateColumns = '1fr 1fr';
document.querySelector('#modal-img-container').style.position = 'relative';
document.querySelector('#modal-img-container').style.top = '50%';
document.querySelector('#modal-img-container').style.transform = 'translateY(-50%)';
