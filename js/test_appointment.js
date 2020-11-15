window.onload = function(){
	nextImg();
	backImg();
};

var bannerStatus = 1;

function changeStep(next_step){
	for(var x=0; x<document.querySelectorAll('.steps-boxes').length; x++){
		if(document.querySelectorAll('.steps-boxes')[x].textContent === next_step){
			setTimeout(function(){
				next_step_box = document.querySelectorAll('.steps-boxes')[x];
				next_step_box.setAttribute('id', 'current-step-box');
			}, 300);
			break;
		}
	}
}

function backImg(){
	//alert(bannerStatus)
	if(bannerStatus == 3){
		document.querySelector('#imgban1').style.opacity = '0';
		setTimeout(function(){
			document.querySelector("#imgban3").style.right = '0px';
			document.querySelector("#imgban3").style.zIndex = '1000';
			document.querySelector("#imgban2").style.right = '-800px';
			document.querySelector("#imgban2").style.zIndex = '1500';
			document.querySelector("#imgban1").style.right = '800px';
			document.querySelector("#imgban1").style.zIndex = '500';
		}, 500);
		setTimeout(function(){
			document.querySelector('#imgban1').style.opacity = '1';
		}, 1000);
		bannerStatus = 1;
		nextImg();
	}
	else if(bannerStatus == 1){
		document.querySelector('#imgban3').style.opacity = '0';
		setTimeout(function(){
			document.querySelector("#imgban2").style.right = '0px';
			document.querySelector("#imgban2").style.zIndex = '1000';
			document.querySelector("#imgban1").style.right = '-800px';
			document.querySelector("#imgban1").style.zIndex = '1500';
			document.querySelector("#imgban3").style.right = '800px';
			document.querySelector("#imgban3").style.zIndex = '500';
		}, 500);
		setTimeout(function(){
			document.querySelector('#imgban3').style.opacity = '1';
		}, 1000);
		bannerStatus = 3;
	}
}

function nextImg(){		
	if(bannerStatus == 1){
		document.querySelector('#imgban2').style.opacity = '0';
		setTimeout(function(){
			document.querySelector("#imgban3").style.right = '0px';
			document.querySelector("#imgban3").style.zIndex = '1000';
			document.querySelector("#imgban2").style.right = '-800px';
			document.querySelector("#imgban2").style.zIndex = '1500';
			document.querySelector("#imgban1").style.right = '800px';
			document.querySelector("#imgban1").style.zIndex = '500';
		}, 500);
		setTimeout(function(){
			document.querySelector('#imgban2').style.opacity = '1';
		}, 1000);
		bannerStatus = 2;
	}
	
	else if(bannerStatus == 2){
		document.querySelector('#imgban1').style.opacity = '0';
		setTimeout(function(){
			document.querySelector("#imgban2").style.right = '0px';
			document.querySelector("#imgban2").style.zIndex = '1000';
			document.querySelector("#imgban1").style.right = '-800px';
			document.querySelector("#imgban1").style.zIndex = '1500';
			document.querySelector("#imgban3").style.right = '800px';
			document.querySelector("#imgban3").style.zIndex = '500';
		}, 500);
		setTimeout(function(){
			document.querySelector('#imgban1').style.opacity = '1';
		}, 1000);
		bannerStatus = 3;
	}
	
	else{
		document.querySelector('#imgban3').style.opacity = '0';
		setTimeout(function(){
			document.querySelector("#imgban1").style.right = '0px';
			document.querySelector("#imgban1").style.zIndex = '1000';
			document.querySelector("#imgban3").style.right = '-800px';
			document.querySelector("#imgban3").style.zIndex = '1500';
			document.querySelector("#imgban2").style.right = '800px';
			document.querySelector("#imgban2").style.zIndex = '500';
		}, 500);
		setTimeout(function(){
			document.querySelector('#imgban3').style.opacity = '1';
		}, 1000);
		bannerStatus = 1;
	}
}

function fillBox(services, providers){
	var checker = document.createElement('option');
	checker.textContent = '---Choose a service---';
	document.querySelector('#appointment-service').appendChild(checker);
	
	var provider = document.createElement('option');
	provider.textContent = '---Choose a provider---';
	document.querySelector('#appointment-provider').appendChild(provider);
	
	for(var x=0; x<services.length; x++){
		var service = document.createElement('option');
		service.textContent = services[x];
		document.querySelector('#appointment-service').appendChild(service);
	}
	
	for(var x=0; x<providers.length; x++){
		var doctor = document.createElement('option');
		doctor.textContent = providers[x];
		document.querySelector('#appointment-provider').appendChild(doctor);
	}
}

function getProviders(e){
	document.querySelector('#appointment-provider').options.length = 0;
	
	var provider = document.createElement('option');
	provider.textContent = '---Choose a provider---';
	provider.disabled = true;
	provider.selected = true;
	document.querySelector('#appointment-provider').appendChild(provider);
	document.querySelector('#front-slide-next').disabled = true;
	
	const service_id = e.target.options[e.target.selectedIndex].id;
	
	for(var i=0; i<services.length; i++){
		if(service_id == services[i].service_id){
			service_duration = JSON.parse(services[i].service_duration);
			var hoursValue = '';
			var minutesValue = '';
			if(service_duration[0].hours != '0'){
				var hoursPlural = ' hours';
				if(service_duration[0].hours === '1'){
					hoursPlural = ' hour';
				}
				hoursValue = service_duration[0].hours + hoursPlural;
			}
			if(service_duration[0].minutes != '0'){
				var minutesPlural = ' minutes';
				if(service_duration[0].minutes === '1'){
					minutesPlural = ' minute';
				}
				minutesValue = service_duration[0].minutes + minutesPlural;
			}
			var space = '';
			if(minutesValue){
				space = ' ';
			}
			document.querySelector('#appointment-duration').textContent = hoursValue + space + minutesValue;
			document.querySelector('#summary-duration').textContent = hoursValue + space + minutesValue;
		}
	}
	
	for(var i=0; i<providers.length; i++){
		if(service_id == providers[i].service_id){
			var provider = document.createElement('option');
			provider.textContent = providers[i].provider_name;
			provider.setAttribute('id', providers[i].provider_id);
			document.querySelector('#appointment-provider').appendChild(provider);
		}
	}
}

function loadServices(e){
	document.querySelector('#appointment-service').options.length = 0;
	document.querySelector('#appointment-provider').options.length = 0;
	
	var service_default = document.createElement('option');
	service_default.textContent = '---Choose a service---';
	service_default.disabled = true;
	service_default.selected = true;
	document.querySelector('#appointment-service').appendChild(service_default);
	
	var provider = document.createElement('option');
	provider.textContent = '---Choose a provider---';
	provider.disabled = true;
	provider.selected = true;
	document.querySelector('#appointment-provider').appendChild(provider);
	document.querySelector('#front-slide-next').disabled = true;
	
	const category_id = e.target.options[e.target.selectedIndex].id;

	for(var i=0; i<services.length; i++){
		if(category_id == services[i].service_category_id){
			if(services[i].service_appears_on_first_time == "1"){
				var service = document.createElement('option');
				service.textContent = services[i].service_name;
				service.setAttribute('id', services[i].service_id);
				document.querySelector('#appointment-service').appendChild(service);	
			}
		}
	}

	if(user_services.length > 0){
		for(var i=0; i<user_services.length; i++){
			if(category_id == user_services[i].service_category_id){
				var service = document.createElement('option');
				service.textContent = user_services[i].service_name;
				service.setAttribute('id', user_services[i].service_id);
				document.querySelector('#appointment-service').appendChild(service);
			}
		}
	}
}

function enableNext(){
	if(
		document.querySelector('#appointment-service').value !== '---Choose a service---' &&
		document.querySelector('#appointment-provider').value !== '---Choose a provider---'				
	  ){
		document.querySelector('#front-slide-next').disabled = false;
	}
}

function checkIfDayIsAvailable(day){
	//0 is sunday, 6 is saturday
	day_value = null;
	if(day === 'Sunday'){
		day_value = 0;
	}
	else if(day === 'Monday'){
		day_value = 1;
	}
	else if(day === 'Monday'){
		day_value = 1;
	}
	else if(day === 'Tuesday'){
		day_value = 2;
	}
	else if(day === 'Thursday'){
		day_value = 4;
	}
	else if(day === 'Friday'){
		day_value = 5;
	}
	else if(day === 'Saturday'){
		day_value = 6;
	}			
}

function nextStep(e){
	current_step_box = document.querySelector('#current-step-box');
	current_step_box.removeAttribute('id');
	next_step_box = null;
	
	if(bannerStatus == 1){
		changeStep('1');	

	}
	else if(bannerStatus == 2){
		changeStep('2');		

	}
	else if(bannerStatus == 3){
		changeStep('3');
	
	}
	
	if(e.target.id === 'front-slide-next'){
		renderCalendar();
		document.querySelector('#summary-category').textContent = document.querySelector('#appointment-category').value;
		document.querySelector('#summary-service').textContent = document.querySelector('#appointment-service').value;
		document.querySelector('#summary-provider').textContent = document.querySelector('#appointment-provider').value;
	}
}

function backStep(e){
	
	current_step_box = document.querySelector('#current-step-box');
	current_step_box.removeAttribute('id');
	next_step_box = null;
	
	if(bannerStatus == 1){
		changeStep('2');			
	}
	else if(bannerStatus == 2){
		changeStep('3');			
	}
	else if(bannerStatus == 3){
		changeStep('1');			
	}
}

function returnClosedBGColor(){
	return '#ffcccc';
}

function renderCalendar(){
	$('#calendar').fullCalendar('destroy');
	$(document).ready(function(){	
		$('#calendar').fullCalendar({
			selectable: true,
			header:{
				left: 'today',
				center: 'title',
				right: 'next',
			},
			schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
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
				
				
				if(!Object.keys(provider_days_worked).includes(moment(date).format('dddd'))){
					cell.css("background", returnClosedBGColor());
				}
				
				if(user_appointments_dict){
					for(var i=0; i<user_appointments_dict.length; i++){
						if(moment(date).format('YYYY-MM-DD') === moment(user_appointments_dict[i].start_datetime).format('YYYY-MM-DD')){
							cell.css("background", returnClosedBGColor());
						}
					}
				}
				
				if(closedDays.includes(moment(date).format('YYYY-MM-DD'))){
				    cell.css("background", returnClosedBGColor());
				}
			},
			dayClick: function(date, jsEvent, view){
				
				$('#hours-container').html('');

				$('[data-date='+selectedDay+']').css({"backgroundColor": last_cell_color, "opacity": '1'});
				last_cell_color = $(this).css("background-color");
			    var click = date.toDate();
			    selectedDay = click.getFullYear() + '-'
				 		+ ('0' + (click.getMonth() +1) ).slice(-2)
				 		+ "-" +('0' + click.getDate()).slice(-2);
				
				var timeslot = document.createElement('option');
				timeslot.textContent = '---Choose a timeslot---';
				timeslot.selected = true;
				timeslot.disabled = true;
				document.querySelector('#hours-container').appendChild(timeslot);
				document.querySelector('#date-slide-next').disabled = true;
						
			    if($(this).css("background-color") == "rgba(0, 0, 0, 0)"){
			   	    $('[data-date='+selectedDay+']').css({"backgroundColor": "#bce8f1", "opacity": '1'});
					last_cell_color = 'rgba(0, 0, 0, 0)';
					var start_time = provider_days_worked[moment(date).format('dddd')].start;
					var end_time = provider_days_worked[moment(date).format('dddd')].end;
					
					var start_time_object = new Date(date.format() + ' ' + start_time);
					var end_time_object = new Date(date.format() + ' ' + end_time);

					$.ajax({
						url: "php/backend/get_appointments_of_certain_day.php",
						method: 'GET',
						data: {date: moment(date).format('YYYY-MM-DD').trim()},
						success: function(result){
							var appointments_scheduled = JSON.parse(result);		
							for(var i=start_time_object; i<end_time_object; i.setHours(i.getHours() + 1)){
								//const current_loop_time = moment(i).add(service_duration.minutes, 'minutes').add(service_duration.hours, 'hours').format('YYYY-MM-DD HH:mm:ss');
								const current_loop_time = moment(i).format('YYYY-MM-DD HH:mm:ss');
								if(doesTimeslotNotOverlap(current_loop_time, appointments_scheduled) && isLastTimeslotAvailable(current_loop_time, moment(end_time_object).format('YYYY-MM-DD HH:mm:ss'))){
									var timeslot = document.createElement('option');
									timeslot.textContent = moment(i).format('h:mm A');
									document.querySelector('#hours-container').appendChild(timeslot);
								}
							}
						}
					});
					
					/*for(var x=0; x<hours.length; x++){
						var timeslot = document.createElement('label');
						timeslot.textContent = hours[x];
						document.querySelector('#hours-container').appendChild(timeslot);
					}*/
					document.querySelector('#summary-date').textContent = moment(date).format('dddd') + ', ' + moment(date).format('MMMM Do YYYY');
					selectedDate = moment(date).format('YYYY-MM-DD');
			    }
				
				if(user_appointments_dict){
					for(var i=0; i<user_appointments_dict.length; i++){
						if(moment(date).format('YYYY-MM-DD') === moment(user_appointments_dict[i].start_datetime).format('YYYY-MM-DD')){
							alert("You already have an appointment scheduled on this day. Only 1 appointment per user is allowed for each day.");
							break;
						}
					}
				}
		
				for(var i=0; i<specified_closed_days.length; i++){
					var current_year = moment().format('YYYY');
					var closed_day = moment(specified_closed_days[i]['month'] + ' ' + specified_closed_days[i]['day'] + ' ' + current_year).format('YYYY-MM-DD');
					if(moment(date).format('YYYY-MM-DD') === closed_day){
						alert("This day is specified by the administrator to be closed.");
					}
				}
			}
		});
	});
}

function isLastTimeslotAvailable(time, end_day_time){
	return moment(time).add(service_duration[0].minutes, 'minutes').add(service_duration[0].hours, 'hours').format('YYYY-MM-DD HH:mm:ss') <= end_day_time;
}

function doesTimeslotNotOverlap(time, appointments_scheduled){
	for(var x=0; x<appointments_scheduled.length; x++){
		if(provider_id === appointments_scheduled[x].provider_id && time >= appointments_scheduled[x].start_datetime && time <= appointments_scheduled[x].end_datetime){
			if(time === appointments_scheduled[x].end_datetime){
				return true;
			}
			return false;
		}
	}
	return true;
}

function loadAppointmentCategories(){
	getFromDB(
		[
			'php/backend/get_data_into_select.php', 
			'GET', 
			'#appointment-category', 
			'SELECT * FROM category', 
			'category_name', 
			'category', 
			'category_id'
		]
	);
}

function getFromDB(data_array){
	loaderPopupControl(true);
	$.ajax({
		url: data_array[0],
		method: data_array[1],
		data: {
			query: data_array[3],
			column: data_array[4],
			text: data_array[5],
			selected_id: data_array[6]
		},
		success: function(categories){
			$(data_array[2]).html(categories);
			loaderPopupControl(false);
		}
	})
}

function getSelectedSelectOptionID(element){
	for(var i=0; i<element.children.length; i++){
		var optionValue = element.children[i].textContent
		if(optionValue.trim() === element.value.trim()){
			return element.children[i].id;
		}
	}
	return null;
}

function getServiceDuration(service_id){
	for(var i=0; i<services.length; i++){
		if(service_id === services[i].service_id){
			return services[i].service_duration;
		}
	}
	return null
}

function confirmAppointment(){
    if(document.querySelector('#terms-box').checked){
    	var service_id = getSelectedSelectOptionID(document.querySelector('#appointment-service'));
    	var provider_id = getSelectedSelectOptionID(document.querySelector('#appointment-provider'));
    	var start_time = moment(selectedDate + ' ' + document.querySelector('#hours-container').value, 'YYYY-MM-DD h:mm A').format('YYYY-MM-DD HH:mm:ss').trim();
    	var duration = JSON.parse(getServiceDuration(service_id));
    	var end_time = moment(start_time).add(duration[0].hours, 'hours').add(duration[0].minutes, 'minutes').format('YYYY-MM-DD HH:mm:ss');
    
    	loaderPopupControl(true);
    	$.ajax({
    		url: 'php/backend/manipulate_appointments.php',
    		method: 'POST',
    		data: {
    			action: 'insert',
    			service_id: service_id,
    			provider_id: provider_id,
    			client_id: user_id,
    			book_datetime: moment().format('YYYY-MM-DD HH:mm:ss'),
    			start_datetime: start_time,
    			end_datetime: end_time,
    			is_finished: 0,
    			is_cancelled: 0,
    			fullName: name,
    			emailAdd: email,
    			providerName: document.querySelector('#appointment-provider').value,
    			serviceName: document.querySelector('#appointment-service').value,
    			categoryName: document.querySelector('#appointment-category').value,
    			has_sent_day_of_notif_msg: 0,
    			contact: contact_num,
    			book_to_start_days_diff: Math.abs(moment().diff(moment(selectedDate + ' ' + document.querySelector('#hours-container').value, 'YYYY-MM-DD h:mm A'), "days"))
    		},
    		success: function(result){
    			alert(result);
    			alert("You are now being redirected to your user appointments page.")
    			loaderPopupControl(false);
    			window.location = './php/backend/backend_user_appointments.php';
    		}
    	});
    }
    else{
		alert("Please check the terms and conditions button.");
	}
}

function fillSummaryWithUserInfo(){
	$.ajax({
		url: "php/backend/get_user_info.php",
		method: "GET",
		data: {user_id: user_id, getter: "frontend"},
		success: function(result){
			var user_data = JSON.parse(result);
			document.querySelector('#patient-name').textContent = user_data.fname + " " + user_data.mname + " " + user_data.lname + " " + user_data.name_ext;
			document.querySelector('#summary-contact').textContent = user_data.contact_number;
			document.querySelector('#summary-email').textContent = user_data.email;
			document.querySelector('#summary-address').textContent = user_data.address;
		}
	});
}



for(var x=0; x<document.querySelectorAll('.next-btn').length; x++){
	document.querySelectorAll('.next-btn')[x].addEventListener('click', nextStep);
	document.querySelectorAll('.next-btn')[x].addEventListener('click', nextImg);
}

for(var x=0; x<document.querySelectorAll('.back-btn').length; x++){
	document.querySelectorAll('.back-btn')[x].addEventListener('click', backStep);
	document.querySelectorAll('.back-btn')[x].addEventListener('click', backImg);
}

document.querySelector('.confirm-btn').addEventListener('click', confirmAppointment);
document.querySelector('#appointment-category').addEventListener('change', loadServices);
document.querySelector('#appointment-service').addEventListener('change', function(e){
	enableNext();
	//getDoctors(e);
	getProviders(e);
});

document.querySelector('#appointment-provider').addEventListener('change', function(e){
	enableNext();
	selectedDay = null;
	$('#hours-container').html('');
	var timeslot = document.createElement('option');
	timeslot.textContent = '---Choose a timeslot---';
	timeslot.selected = true;
	timeslot.disabled = true;
	document.querySelector('#hours-container').appendChild(timeslot);
	
	provider_id =  e.target.options[e.target.selectedIndex].id;
	document.querySelector('#step-two-provider-name').textContent = document.querySelector('#appointment-provider').value;
	document.querySelector('#summary-provider').textContent = document.querySelector('#appointment-provider').value;
	$('#step-two-provider-sched').html('');
	loaderPopupControl(true);
	$.ajax({
		url: 'php/backend/get_clinic_hours_frontend.php',
		method: 'GET',
		data: {
			action: 'front_end',
			provider_id: provider_id
		},
		success: function(result){
			days_available = JSON.parse(result);
			days_closed = JSON.parse(days_available['days_open']);
			specified_closed_days = JSON.parse(days_available['specified_closed_days']);
			provider_days_worked = JSON.parse(days_available['days_worked']);
			
			var sched_string = '';
			for(var i=0; i< Object.keys(provider_days_worked).length; i++){
				var label = document.createElement('label');
				var start_time = moment('2000-12-05 ' + provider_days_worked[Object.keys(provider_days_worked)[i]].start).format('h:mm A');
				var end_time = moment('2000-12-05 ' + provider_days_worked[Object.keys(provider_days_worked)[i]].end).format('h:mm A');
				label.setAttribute('class', 'timeslot-label');
				label.textContent = Object.keys(provider_days_worked)[i] + ": " + start_time + " - " + end_time;
				document.querySelector('#step-two-provider-sched').appendChild(label);
			}
			loaderPopupControl(false);
		}
	});
	//last_cell_color = "#ffcccc";
	//$('#cucklendar').fullCalendar('rerenderEvents');
	//$('[data-date='+selectedDay+']').css({"backgroundColor": "#ffcccc", "opacity": '1'});*/
});
document.querySelector('#hours-container').addEventListener('change', function(e){
	if(e.target.value !== '---Choose a timeslot---'){
		document.querySelector('#date-slide-next').disabled = false;
		document.querySelector('#summary-time').textContent = document.querySelector('#hours-container').value;
	}
});

var selectedDay;
var last_cell_color;
var provider_id;
var provider_days_worked;
var service_duration;
var days_closed;
var specified_closed_days;
var days_available;
var selectedDate;
var closedDays = [];

$.ajax({
	url: 'php/backend/count_days.php',
	method: 'GET',
	success: function(result){
        var dbresult = JSON.parse(result);
        if(dbresult.length > 0){
            for(var i=0; i<dbresult.length; i++){
                closedDays.push(dbresult[i]);
            }
        }
	}
});

getUserServices("php/backend/get_user_services.php");
getProviderServices("php/backend/get_provider_services.php", '#appointment-category', 'frontend', user_id);
fillSummaryWithUserInfo();