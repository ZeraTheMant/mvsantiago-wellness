function getAppointmentsOfUserParams(finished, cancelled){
	function getAppointmentsOfUser(){
		loaderPopupControl(true);
		$.ajax({
			url: 'get_appointments_of_user.php',
			type: 'GET',
			cache: true,
			data: {client_id: user_id, is_finished: finished, is_cancelled: cancelled},
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
				getClinicHours(getEventSourceDict(user_id, finished, cancelled), providers_columns);					
				loaderPopupControl(false);
			}
		})
		loaderPopupControl(false);
	}
	return getAppointmentsOfUser;
}

function getEventSourceDict(client_id, is_finished, is_cancelled){
	var array = {
		url: 'get_appointments_of_user.php',
		type: 'GET',
		cache: true,
		data: {client_id: user_id, is_finished: is_finished, is_cancelled: is_cancelled}
	};
	return array;
}

function rescheduleAppointment(){
	document.querySelector('#modal-form-content').style.display = "none";
	document.querySelector('#reschedule-form-content').style.display = "block";
	document.querySelector('.modal-content').style.width = "700px";
	document.querySelector('.modal-header-text').textContent = "Reschedule appointment";
	document.querySelector('#current-appointment-date').textContent = user_appointment_selected.format("MMMM Do YYYY"); 
	document.querySelector('#current-appointment-start').textContent = document.querySelector("#modal-start").value
	document.querySelector('#current-appointment-end').textContent = document.querySelector("#modal-end").value;
	for(var i=0; i<providers_columns.length; i++){
		if(providers_columns[i].id === document.querySelector('#modal-provider').name){
			provider_days_worked = JSON.parse(providers_columns[i].days_worked);
			service_duration = JSON.parse(providers_columns[i].duration);
			break;
		}
	}
	renderRescheduleCalendar();
}

function renderRescheduleCalendar(){
	$('#reschedule-calendar').fullCalendar('destroy');
	selectedDay = null;
	$(document).ready(function(){	
		$('#reschedule-calendar').fullCalendar({
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
				
				if(hasRescheduled){
					$('[data-date='+hasRescheduled+']').css({"backgroundColor": returnClosedBGColor(), "opacity": '1'});
					$('[data-date='+old_appointment_day+']').css({"backgroundColor": "rgba(0, 0, 0, 0)", "opacity": '1'});
				}

			},
			dayClick: function(date, jsEvent, view){
				$('#resched-timeslot-container').html("");
				$('[data-date='+selectedDay+']').css({"backgroundColor": last_cell_color, "opacity": '1'});
				last_cell_color = $(this).css("background-color");
			    var click = date.toDate();
			    selectedDay = click.getFullYear() + '-'
				 		+ ('0' + (click.getMonth() +1) ).slice(-2)
				 		+ "-" +('0' + click.getDate()).slice(-2);

			    if($(this).css("background-color") == "rgba(0, 0, 0, 0)"){
			   	    $('[data-date='+selectedDay+']').css({"backgroundColor": "#bce8f1", "opacity": '1'});
					last_cell_color = 'rgba(0, 0, 0, 0)';
					var start_time = provider_days_worked[moment(date).format('dddd')].start;
					var end_time = provider_days_worked[moment(date).format('dddd')].end;
					
					var start_time_object = new Date(date.format() + ' ' + start_time);
					var end_time_object = new Date(date.format() + ' ' + end_time);

					$.ajax({
						url: "get_appointments_of_certain_day.php",
						method: 'GET',
						data: {date: moment(date).format('YYYY-MM-DD').trim()},
						success: function(result){
							var appointments_scheduled = JSON.parse(result);	
							var ctr = 1000;
							for(var i=start_time_object; i<end_time_object; i.setHours(i.getHours() + 1)){
								//const current_loop_time = moment(i).add(service_duration.minutes, 'minutes').add(service_duration.hours, 'hours').format('YYYY-MM-DD HH:mm:ss');
								const current_loop_time = moment(i).format('YYYY-MM-DD HH:mm:ss');
								if(doesTimeslotNotOverlap(current_loop_time, appointments_scheduled) && isLastTimeslotAvailable(current_loop_time, moment(end_time_object).format('YYYY-MM-DD HH:mm:ss'))){
									var radioDiv = document.createElement('div');
									var radioButton = document.createElement('input');
									var radioLabel= document.createElement('label');
									
									radioDiv.style.padding = "5px";
									radioDiv.style.boxSizing = "border-box";
									radioDiv.style.border = "1px solid gray";					
									
									radioButton.setAttribute("type", "radio");
									radioButton.setAttribute("id", ctr);
									radioButton.setAttribute("name", "day-timeslot");
									radioButton.addEventListener('click', changeNewAppointmentSubtitle);
									radioButton.value = moment(i).format('h:mm A');
									radioButton.style.cursor = "pointer";
									
									radioLabel.setAttribute("for", ctr);
									radioLabel.textContent = moment(i).format('h:mm A');
									radioLabel.style.cursor = "pointer";
									
									radioDiv.appendChild(radioButton);
									radioDiv.appendChild(radioLabel);
									document.querySelector('#resched-timeslot-container').appendChild(radioDiv);
								}
								ctr--;
							}
						}
					});
					
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
				document.querySelector('#new-appointment-date').textContent = moment(date).format("MMMM Do YYYY");
				document.querySelector('#new-appointment-start').textContent = "";
				document.querySelector('#new-appointment-end').textContent = "";
			}
		});
	});
}

function changeNewAppointmentSubtitle(e){
	document.querySelector('#no-display').style.display = "inline";
	document.querySelector('#new-appointment-start').textContent = e.target.value;
	document.querySelector('#new-appointment-end').textContent = moment("2000-11-24 " + e.target.value, "YYYY-MM-DD h:mm A").add(service_duration[0].minutes, "minutes").format("h:mm A");
}

function doesTimeslotNotOverlap(time, appointments_scheduled){
	for(var x=0; x<appointments_scheduled.length; x++){
		if(document.querySelector('#modal-provider').name === appointments_scheduled[x].provider_id && time >= appointments_scheduled[x].start_datetime && time <= appointments_scheduled[x].end_datetime){
			if(time === appointments_scheduled[x].end_datetime){
				return true;
			}
			return false;
		}
	}
	return true;
}

function isLastTimeslotAvailable(time, end_day_time){
	return moment(time).add(service_duration[0].minutes, 'minutes').add(service_duration[0].hours, 'hours').format('YYYY-MM-DD HH:mm:ss') <= end_day_time;
}

function confirmRescheduleAppointment(){
	if(document.querySelector('#new-appointment-start').textContent !== ""){
		loaderPopupControl(true);
		$.ajax({
			url: "manipulate_appointments.php",
			method: "POST",
			data: {
				action: "update",
				book_datetime: moment().format("YYYY-MM-DD HH:mm:ss"),
				start_datetime: moment(selectedDate + ' ' + document.querySelector('#new-appointment-start').textContent, "YYYY-MM-DD h:mm A").format('YYYY-MM-DD HH:mm:ss'),
				end_datetime: moment(selectedDate + ' ' + document.querySelector('#new-appointment-end').textContent, "YYYY-MM-DD h:mm A").format('YYYY-MM-DD HH:mm:ss'),
				appointment_id: document.querySelector('.modal-selected-id').textContent.slice(10),
				old_start: moment(user_appointment_selected.format("YYYY-MM-DD") + " " + document.querySelector('#current-appointment-start').textContent, "YYYY-MM-DD H:mm a").format("YYYY-MM-DD H:mm a"),
				old_end: moment(user_appointment_selected.format("YYYY-MM-DD") + " " + document.querySelector('#current-appointment-end').textContent, "YYYY-MM-DD H:mm a").format("YYYY-MM-DD H:mm a"),
				fullName: name,
				emailAdd: email,
				contact: contact_num,
				providerName: document.querySelector('#modal-provider').value,
				serviceName: document.querySelector('#modal-service').value,
				categoryName: document.querySelector('#modal-category').value,
				provider_id: document.querySelector('#modal-provider').name,
				client_id: document.querySelector('#modal-patient').name,
				book_to_start_days_diff: Math.abs(moment().diff(moment(selectedDate + ' ' + document.querySelector('#new-appointment-start').textContent, "YYYY-MM-DD h:mm A"), "days"))
			},
			success: function(result){
				getUserAppointmentsDict("get_all_pending_appointments_of_user.php", user_id);
				hasRescheduled = selectedDate;
				old_appointment_day = user_appointment_selected.format("YYYY-MM-DD");
				alert(result)
				loaderPopupControl(false);
				document.querySelector('.modal-popup').style.display = 'none';
				document.querySelector('.modal-form').reset();
				document.querySelector('#modal-form-content').style.display = "block";
				document.querySelector('#reschedule-form-content').style.display = "none";
				document.querySelector('.modal-content').style.width = "400px";
				document.querySelector('#no-display').style.display = "none";
				$('#resched-timeslot-container').html("");
				
				getAllProviders(getAppointmentsOfUserParams(0, 0));
				document.querySelector('#providers-select-user').value = "Upcoming appointments";
				getAppointmentTable("", 0, 0);
			}
		});	
	}
}

function cancelAppointment(){
	var confirm_cancel = confirm("Are you sure you want to cancel this appointment?");
	if(confirm_cancel){
		document.querySelector('#modal-form-content').style.display = "none";
		document.querySelector('#cancellation-form-content').style.display = "block";
		document.querySelector('.modal-content').style.width = "500px";
		document.querySelector('.modal-header-text').textContent = "Cancel appointment";
		clearCancellationForm();
	}
}

function clearCancellationForm(){
    var reasonsList = document.getElementsByName('reason');
    for(var i=0; i<reasonsList.length; i++){
        document.getElementsByName('reason')[i].checked = false;
    }
    document.querySelector('#other-reason').value = "";
}

function getSelectedReason(return_boolean_or_value){
    var reasonsList = document.getElementsByName('reason');
    for(var i=0; i<reasonsList.length; i++){
        if(document.getElementsByName('reason')[i].checked == true){

            if(return_boolean_or_value == 'boolean'){
                return true;
            }
            else if(return_boolean_or_value == 'value'){
                if(document.getElementsByName('reason')[i].value !== 'Other reason'){
                    return document.getElementsByName('reason')[i].value;
                }
                else{
                    return document.querySelector('#other-reason').value;
                }
            }
        }
    }

    return false;
}

function confirmCancelAppointment(){
    if(getSelectedReason('boolean') == true){
        if(getSelectedReason('value') !== ""){
        	loaderPopupControl(true);
    		$.ajax({
    			url: "manipulate_appointments.php",
    			method: "POST",
    			data: {
    				action: "delete",
    				cancellation_reason: getSelectedReason('value'),
    				appointment_id: document.querySelector('.modal-selected-id').textContent.slice(10),
    				provider_id: document.querySelector('#modal-provider').name,
    				client_id: document.querySelector('#modal-patient').name,
    				book_datetime: moment().format("YYYY-MM-DD HH:mm:ss"),
    				emailAdd: email,
    				fullName: name,
    
    				timestamp: moment().format("YYYY-MM-DD HH:mm:ss"),
    				category: document.querySelector("#modal-category").value,
    				service: document.querySelector("#modal-service").value,
    				provider: document.querySelector("#modal-provider").value,
    				date: user_appointment_selected.format("YYYY-MM-DD"),
    				client: document.querySelector("#modal-patient").value,
    				end: document.querySelector("#modal-end").value,
    				start: document.querySelector("#modal-start").value
    			},
    			success: function(result){
    				alert(result)
    				loaderPopupControl(false);
    				document.querySelector('.modal-popup').style.display = 'none';
    				document.querySelector('.modal-form').reset();
    				document.querySelector('#modal-form-content').style.display = "block";
    				document.querySelector('#cancellation-form-content').style.display = "none";
    				document.querySelector('.modal-content').style.width = "400px";
    				$('#resched-timeslot-container').html("");
    				
    				getAllProviders(getAppointmentsOfUserParams(0, 0));
    				document.querySelector('#providers-select-user').value = "Upcoming appointments";
    				getAppointmentTable("", 0, 0);
    			}
    		});	
        }
        else{
            alert("Please enter a reason for cancelling the appointment");
        }

	}
	else{
		alert("Please enter a reason for cancelling the appointment");
	}
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
	getAppointmentTable("", finished_var, cancelled_var);
}

var provider_days_worked;
var providers_columns = [];
var service_duration;
var cancelled_var = 0;
var finished_var = 0;
var hasRescheduled;
var old_appointment_day;

getAppointmentTable("", 0, 0);

document.querySelector('#no-display').style.display = "none";
document.querySelector('#reschedule-form-content').style.display = "none";
document.querySelector('#reschedule-form-content').style.padding = "10px";
document.querySelector('.reschedule-modal-btn').addEventListener("click", rescheduleAppointment);
document.querySelector('.confirm-reschedule-btn').addEventListener('click', confirmRescheduleAppointment);
document.querySelector('.cancel-modal-btn').addEventListener('click', cancelAppointment);
document.querySelector('.confirm-cancel-btn').addEventListener('click', confirmCancelAppointment);
document.querySelector('#providers-select-user').addEventListener('change', changeAppointmentView);
getAllProviders(getAppointmentsOfUserParams(0, 0));