function getAppointmentsOfUserParams(finished, cancelled){
	function getAppointmentsOfUser(){
		loaderPopupControl(true);
		$.ajax({
			url: 'get_appointments_of_provider.php',
			type: 'GET',
			cache: true,
			data: {provider_id: provider_id, is_finished: finished, is_cancelled: cancelled},
			success: function(result){

				var result_json = JSON.parse(result);
				providers_columns = [];
				for(var k=0; k<result_json.length; k++){
					var dict = {};
					dict.id = result_json[k].provider_id;
					dict.title = result_json[k].title;
					dict.days_worked = result_json[k].days_worked;
					dict.duration = result_json[k].service_duration;
					providers_columns.push(dict);
				}
				getClinicHours(getEventSourceDict(provider_id, finished, cancelled), providers_columns);					
				loaderPopupControl(false);
			}
		})
		loaderPopupControl(false);
	}
	return getAppointmentsOfUser;
}

function getEventSourceDict(provider_id, is_finished, is_cancelled){
	var array = {
		url: 'get_appointments_of_provider.php',
		type: 'GET',
		cache: true,
		data: {provider_id: provider_id, is_finished: is_finished, is_cancelled: is_cancelled}
	};
	return array;
}

function changeAppointmentView(){
	if(document.querySelector('#providers-select-user').value === "Upcoming appointments"){
		cancelled_var = 0;
		finished_var = 0;
		getAllProviders(getAppointmentsOfUserParams(0, 0));
	}
	else if(document.querySelector('#providers-select-user').value === "Past appointments"){
		cancelled_var = 0;
		finished_var = 1;
		getAllProviders(getAppointmentsOfUserParams(1, 0));
	}
	else if(document.querySelector('#providers-select-user').value === "Cancelled appointments"){
		cancelled_var = 1;
		finished_var = 0;
		getAllProviders(getAppointmentsOfUserParams(0, 1));
	}
	//getAllProviders(dummyParam);
	getAppointmentTable(provider_id, finished_var, cancelled_var);
}

function addService(){
	getService('Select a category', 'Select a service');
}

function getService(category_select_val='', service_select_val="", category_idz="", bool=true){
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
	
	if(!bool){
		removeServiceBtn.disabled = true;
		serviceSelect.disabled = true;
		categorySelect.disabled = true;
	}
	
	newCol.appendChild(categorySelect)
	newCol2.appendChild(serviceSelect);
	newCol3.appendChild(removeServiceBtn);
	newRow.appendChild(newCol);
	newRow.appendChild(newCol2);
	newRow.appendChild(newCol3);
	document.querySelector('#patient-services-table-body').appendChild(newRow);
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
		if(category_id == services[i].service_category_id && services[i].service_appears_on_first_time == "0"){
			var service = document.createElement('option');
			service.textContent = services[i].service_name;
			service.setAttribute('id', services[i].service_id);
			e.target.parentNode.parentNode.children[1].lastChild.appendChild(service);
		}
	}
}

function cat_id_and_serv_id(){
	var val_per_row = [];
	for(var i=0; i<document.querySelectorAll('#patient-services-table-body tr').length; i++){
		const category_value = document.querySelectorAll('#patient-services-table-body tr')[i].children[0].firstChild;
		var category_id;
		if(category_value.value !== 'Select a category'){
			for(var x=0; x<category_value.children.length; x++){
				if(category_value.value === category_value.children[x].textContent){
					category_id = category_value.children[x].id;
					break;
				}
			}
		}
		
		const service_value = document.querySelectorAll('#patient-services-table-body tr')[i].children[1].firstChild;
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

function removeService(e){
	var confirmDelete = confirm("Remove this service?");
	if(confirmDelete){
		e.target.parentNode.parentNode.parentNode.removeChild(e.target.parentNode.parentNode);
	}
}

function getPanel(tab){
	var panel;
	if(tab === 'Appointment Information'){
		panel = '#panel-1';
	}
	else if(tab === 'Available Services'){
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

function savePatientServices(){
	loaderPopupControl(true);
	$.ajax({
		url: "manipulate_client_services.php",
		method: "POST",
		data: {client_id: document.querySelector('#modal-patient').name, categories_and_services: cat_id_and_serv_id()},
		success: function(result){
			alert("Service accessibility settings for this particular patient/client has been successfully updated.");
			loaderPopupControl(false);
		}
	});
}

function inMyCatsServs(cat_id, serv_id){
	for(var i=0; i<myCatsServs.length; i++){
		if(myCatsServs[i].category_id == cat_id && myCatsServs[i].service_id == serv_id){
			return true;
		}
	}
	return false;
}

function loadClientServices(client_id){
	$("#patient-services-table-body").html("");
	loaderPopupControl(true);
	$.ajax({
		url: 'get_individual_client_services.php',
		method: 'GET',
		data: {
			client_id: client_id
		},
		success: function(result){
			var individual_services = JSON.parse(result);

			
			for(var i=0; i<individual_services.length; i++){
				if(myCatsServs){
					if(inMyCatsServs(individual_services[i].category_id, individual_services[i].service_id)){
						getService(individual_services[i].category_name, individual_services[i].service_name, individual_services[i].category_id, true);
					}
					else{
						getService(individual_services[i].category_name, individual_services[i].service_name, individual_services[i].category_id, false);
					}
				}
				else{
					getService(individual_services[i].category_name, individual_services[i].service_name, individual_services[i].category_id, false);
				}
			}
			
			loaderPopupControl(false);
		}
	});
}

function createReport(){
	if(document.querySelector('#remarks').value !== ''){
		loaderPopupControl(true);
		$.ajax({
			url: "manipulate_reports.php",
			method: "POST",
			data: {
				action: 'insert',
				appointment_id: document.querySelector(".modal-selected-id").textContent.slice(10),
				remarks: document.querySelector("#remarks").value,
				category: document.querySelector("#modal-category").value,
				service: document.querySelector("#modal-service").value,
				provider: document.querySelector("#modal-provider").value,
				client: document.querySelector("#modal-patient").value,
				timestamp: moment().format("YYYY-MM-DD HH:mm:ss"),
				client_id: document.querySelector("#modal-patient").name,
				provider_id: document.querySelector("#modal-provider").name,
				start: document.querySelector("#modal-start").value,
				end: document.querySelector("#modal-end").value,
				date: user_appointment_selected.format("YYYY-MM-DD")
			},
			success: function(result){
				alert("Report successfully created.");
				modalControl(false, "View Appointment", "500px");
				loaderPopupControl(false);
			}
		});
	}
	else{
		alert("Please enter your remarks.");
	}
}

var provider_days_worked;
var providers_columns = [];
var service_duration;
var cancelled_var = 0;
var finished_var = 0;
var hasRescheduled;
var old_appointment_day;
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
document.querySelector('.tab-container').style.gridTemplateColumns = '1fr';

document.querySelector('#providers-select-user').addEventListener('change', changeAppointmentView);
document.querySelector('#add-patient-service').addEventListener('click', addService);
document.querySelector('.save-patient-services').addEventListener('click', savePatientServices);
document.querySelector('#create-report').addEventListener('click', createReport);

getAppointmentTable(provider_id, 0, 0);

getProviderServices("get_category_services.php", '#shit', 'backend', null);
getAllProviders(getAppointmentsOfUserParams(0, 0));
