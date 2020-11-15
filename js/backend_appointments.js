function getEventSourceDict(provider_id, is_finished, is_cancelled){
	var dict = {
		url: 'get_appointments.php',
		type: 'GET',
		cache: true,
		data: {provider_id: provider_id, is_finished: is_finished, is_cancelled: is_cancelled}
	}

	return dict;
}

function getProvidersColumns(provider_id){
	var provider_columns = [];
	for(var i=0; i<providers_dict.length; i++){
		var provider_level = ''; 
		if(providers_dict[i].provider_level.toLowerCase() === 'doctor' || providers_dict[i].provider_level.toLowerCase() === 'dentist'){
			provider_level = 'Dr.'; 
		}
		if(provider_id){
			if(provider_id === providers_dict[i].provider_id){
				return [
					{
						id: providers_dict[i].provider_id,
						title: provider_level + ' ' + providers_dict[i].fname + ' ' + providers_dict[i].mname[0].toUpperCase() + '. ' + providers_dict[i].lname + ' ' + providers_dict[i].name_ext
					}
				];
			}
		}
		else{
			provider_columns.push(
				{
					id: providers_dict[i].provider_id,
					title: provider_level + ' ' + providers_dict[i].fname + ' ' + providers_dict[i].mname[0].toUpperCase() + '. ' + providers_dict[i].lname + ' ' + providers_dict[i].name_ext
				}
			);	
		}
	}
	return provider_columns;
}

function renderCalendar(eventSourceDict, providersColumns){
	$('#calendar').fullCalendar('destroy');
	$(document).ready(function(){	
		$('#calendar').fullCalendar({
			selectable: true,
			header:{
				left: 'today month agendaWeek',
				center: 'title',
				right: 'prev next',
			},
			eventSources: [
				eventSourceDict
			],

			schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
			resources : providersColumns,
			allDaySlot: false,
			//eventRender: function(event){
			//	$('[data-date='+moment(event.start).format('YYYY-MM-DD')+']').css({"backgroundColor": "#00ff00", "opacity": '1'});
			//},
			dayRender: function(date, cell){
				//$('[data-date='+selectedDay+']').css({"backgroundColor": "#ffcccc", "opacity": '1'});
				if(moment(date).format('YYYY-MM-DD') == selectedDay && last_cell_color == 'rgba(0, 0, 0, 0)'){
					$('[data-date='+selectedDay+']').css({"backgroundColor": "#bce8f1", "opacity": '1'});
				}
				if (moment(date) < moment().add(2, 'days')){
					cell.css("background", returnClosedBGColor());
				}
				//if(provider.getDaysAvailable().indexOf(moment(date).format('dddd')) == -1){
				//	cell.css("background", "#ff6347");
				//}
				
				Object.keys(days_closed).forEach(function(element){
					if(moment(date).format('dddd') == element && days_closed[element] == 'Closed'){
						cell.css("background", returnClosedBGColor());
					}
				});
				
				for(var i=0; i<specified_closed_days.length; i++){
					var current_year = moment().format('YYYY');
					var closed_day = moment(specified_closed_days[i]['month'] + ' ' + specified_closed_days[i]['day'] + ' ' + current_year).format('YYYY-MM-DD');
					if(moment(date).format('YYYY-MM-DD') === closed_day){
						cell.css("background", returnClosedBGColor());
					}
				}
				if(providers_dict && document.querySelector('#providers-select').value !== "All providers"){
					var selected_provider_days_worked = getSelectedProviderDaysWorked(selected_provider_id);
					if(selected_provider_days_worked){
						if(!Object.keys(selected_provider_days_worked).includes(moment(date).format('dddd'))){
							cell.css("background", returnClosedBGColor());
						}
					}

				}
			},
			dayClick: function(date, jsEvent, view){
				$('[data-date='+selectedDay+']').css({"backgroundColor": last_cell_color, "opacity": '1'});	
				var click = date.toDate();				
				selectedDay = click.getFullYear() + '-'
				 		+ ('0' + (click.getMonth() +1) ).slice(-2)
				 		+ "-" +('0' + click.getDate()).slice(-2);
						
				last_cell_color = $(this).css("background-color");
				$('[data-date='+selectedDay+']').css({"backgroundColor": "#bce8f1", "opacity": '1'});	

				for(var i=0; i<specified_closed_days.length; i++){
					var current_year = moment().format('YYYY');
					var closed_day = moment(specified_closed_days[i]['month'] + ' ' + specified_closed_days[i]['day'] + ' ' + current_year).format('YYYY-MM-DD');
					if(moment(date).format('YYYY-MM-DD') === closed_day){
						alert("This day is specified to be closed.");
					}
				}
				if(view.name == 'month' || view.name == 'basicWeek'){
					if(last_cell_color !== "rgb(255, 204, 204)"){
						$('#calendar').fullCalendar('changeView', 'agendaDay');
						$('#calendar').fullCalendar('gotoDate', date);	
					}      
				}
			},
			eventClick: function(event, jsEvent, view){
				loadClientServices(event.client_id);
				
				for(var i=0; i<tabButtons.length; i++){
					if(i == 0){
						tabButtons[i].style.backgroundColor = "white";
						tabPanels[i].style.display = "block";
					}
					else{
						tabButtons[i].style.backgroundColor = "#eee";
						tabPanels[i].style.display = "none";
					}
				}
				
				modalControl(true, "View Appointment", "500px");
				if(provider_id === event.provider_id && appointments_view_user.value == "Past appointments"){
					if(disappearingBtns){
						for(var i=0; i<disappearingBtns.length; i++){
							disappearingBtns[i].style.display = "inline";
						}
					}
					document.querySelector('.tab-container').style.display = 'grid';
					document.querySelector('.tab-container').style.gridTemplateColumns = '1fr 1fr 1fr';
				}
				else{
					if(disappearingBtns){
						for(var i=0; i<disappearingBtns.length; i++){
							disappearingBtns[i].style.display = "none";
						}
					}
					document.querySelector('.tab-container').style.display = 'grid';
					document.querySelector('.tab-container').style.gridTemplateColumns = '1fr';
				}
				document.querySelector('.modal-selected-id').textContent = event.id_code;

				document.querySelector('#modal-service').value = event.title;
				document.querySelector('#modal-category').value = event.category;
				document.querySelector('#modal-provider').value = event.provider;
				document.querySelector('#modal-provider').name = event.provider_id;
				document.querySelector('#modal-patient').value = event.client;
				document.querySelector('#modal-patient').name = event.client_id;
				document.querySelector('#modal-date').value = moment(event.start).format("dddd") + ", " + moment(event.start).format("MMMM Do YYYY");
				document.querySelector('#modal-start').value = moment(event.start).format("h:mm A"); 
				document.querySelector('#modal-end').value = moment(event.end).format("h:mm A"); 
				user_appointment_selected = moment(event.start);
				
				if(appointments_view_user && appointments_view_user.value !== "Upcoming appointments" && document.querySelector(".modal-footer")){
					document.querySelector(".modal-footer").style.display = "none";
				}
				else if(document.querySelector(".modal-footer")){
					document.querySelector(".modal-footer").style.display = "block";
				}
			}
		});
	});
}

function returnClosedBGColor(){
	return '#ffcccc';
}

function getSelectedProviderDaysWorked(provider_id){
	for(var i=0; i<providers_dict.length; i++){
		if(providers_dict[i].provider_id === provider_id){
			return JSON.parse(providers_dict[i].days_worked);
		}
	}
	return null;
}

function getClinicHours(eventSourceDict, providersColumns){
	loaderPopupControl(true);
	$.ajax({
		url: 'get_clinic_hours_frontend.php',
		method: 'GET',
		data: {action: 'backend'},
		success: function(result){
			days_available = JSON.parse(result);
			days_closed = JSON.parse(days_available['days_open']);
			specified_closed_days = JSON.parse(days_available['specified_closed_days']);
			renderCalendar(eventSourceDict, providersColumns);
			loaderPopupControl(false);
		}
	});
}

function changeProviderAppointmentView(e){
	getClinicHours(getEventSourceDict(e.target.options[e.target.selectedIndex].id, returnAppointmentView(), 0), getProvidersColumns(e.target.options[e.target.selectedIndex].id));
	selected_provider_id = e.target.options[e.target.selectedIndex].id;
	getAppointmentTable(selected_provider_id, returnAppointmentView(), 0);
}

function getAllProviders(function_param){
	function_param();
}

function getAppointmentTable(provider_id, is_finished, is_cancelled){
	$('#appointment-table').html('');
	$.ajax({
		url: table_link,
		method: "GET",
		data: {client_id: user_id, provider_id: provider_id, is_finished: is_finished, is_cancelled: is_cancelled},
		success: function(result){
			$('#appointment-table').html(result);
		}
	});
}

var selectedDay;
var days_available;
var days_closed;
var specified_closed_days;
var last_cell_color;
var providers_dict;
var selected_provider_id;
var tabButtons = document.querySelectorAll('.tab-container button');
var tabPanels = document.querySelectorAll('.tab-panel');
var user_appointment_selected;
var appointments_view_user;
var disappearingBtns;

if(document.querySelector('#providers-select-user')){
	appointments_view_user = document.querySelector('#providers-select-user');
}

if(document.querySelectorAll('.disappearing')){
	disappearingBtns = document.querySelectorAll('.disappearing');
}

tabButtons[0].style.backgroundColor = "white";
tabPanels[0].style.display = "block";
tabPanels[0].style.backgroundColor = "white";
tabPanels[0].style.color = "black";

if(document.querySelector('#providers-select')){
	document.querySelector('#providers-select').addEventListener('change', changeProviderAppointmentView);
}

document.querySelector('.tab-container').style.display = 'grid';
document.querySelector('.tab-container').style.gridTemplateColumns = '1fr';

