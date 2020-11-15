function load_unseen_notification(){
	if(all_pending_apps){
		for(var i=0; i<all_pending_apps.length; i++){
			//alert(user_apps[i].start_datetime + " " + moment(user_apps[i].start_datetime).isBefore(moment()));
			//if(user_apps[i].is_finished == "0" && user_apps[i].is_cancelled == "0" && !user_apps[i].is_notified){
			if(all_pending_apps[i].is_confirmed == "0"){

				if(parseInt(all_pending_apps[i].book_to_start_days_diff) > 2){
					if(moment().diff(moment(all_pending_apps[i].start_datetime), "days") > -3){
						deleteAppointment(all_pending_apps[i].appointment_id, all_pending_apps[i].start_datetime, all_pending_apps[i].client_id == user_id);
						break;
					}
				}
				else{
					if(Math.abs(moment().diff(moment(all_pending_apps[i].start_datetime), "days")) >= 0 && Math.abs(moment().diff(moment(all_pending_apps[i].start_datetime), "days")) < 3){
						deleteAppointment(all_pending_apps[i].appointment_id, all_pending_apps[i].start_datetime, all_pending_apps[i].client_id == user_id);
						break;
					}
				}
			}

			if(isSameDay(moment(all_pending_apps[i].start_datetime), moment()) && all_pending_apps[i].has_sent_day_of_notif_msg == "0"){
				//user_apps[i].is_notified = "1";
				if(all_pending_apps[i].client_id == user_id){

					alert("Your appointment for " + all_pending_apps[i].start_datetime + " is today. Don't miss it.");
				}
				manipulateNotifications(linkViewer + 'manipulate_appointment_notifs.php', all_pending_apps[i].appointment_id, all_pending_apps[i].client_id, all_pending_apps[i].start_datetime, all_pending_apps[i].contact_number);
				break;
			}
				
			if(isFinished(moment(all_pending_apps[i].end_datetime))){
				
				if(all_pending_apps[i].is_confirmed == "0"){
					deleteAppointment(all_pending_apps[i].appointment_id, all_pending_apps[i].start_datetime);
				}
				else{
					if(all_pending_apps[i].client_id == user_id){
						alert("Your appointment for " + all_pending_apps[i].start_datetime + " has now ended.");
					}
					manipulateNotifications(linkViewer + "finish_appointment.php", all_pending_apps[i].appointment_id, all_pending_apps[i].client_id, all_pending_apps[i].start_datetime, all_pending_apps[i].contact_number);
					break;
				}
			}
			//}
		}	
	}
}

function withinTheWeek(app_start, current_day){
	//alert((Math.abs(current_day.diff(app_start), "days") < 6 && Math.abs(current_day.diff(app_start), "days") > 0))
	return (Math.abs(current_day.diff(app_start, "days")) < 7 && Math.abs(current_day.diff(app_start, "days")) > 0);
}

function deleteAppointment(appointment_id, start_datetime, is_user){
	$.ajax({
		url: linkViewer + "delete_appointment.php",
		method: "POST",
		data: {appointment_id: appointment_id},
		success: function(result){
			if(result){
				all_pending_apps = JSON.parse(result);
			}
			
			if(is_user){
				alert("Your appointment for " + start_datetime + " was not confirmed and has been automatically deleted.");
			}
		}
	})
}

function isFinished(moment_obj){
	return moment().isAfter(moment_obj);
}

function manipulateNotifications(link, appointment_id, user_id, start_date, contact){
	$.ajax({
		url: link,
		method: "POST",
		data: {id: appointment_id, client_id: user_id, start_datetime: start_date, contact: contact},
		success: function(result){
			if(result){
				all_pending_apps = JSON.parse(result);
				check_week_notifs();
			}
		}
	});
}

function manipulateNotifications2(link, appointment_id, user_id, start_date, dummy){
	$.ajax({
		url: link,
		method: "POST",
		data: {id: appointment_id, client_id: user_id, start_datetime: start_date, dummy: dummy},
	});
}

function isSameDay(moment1, moment2){
	return moment1.date() === moment2.date() && moment1.month() === moment2.month() && moment1.year() === moment2.year();
}

function stringify2(int_value){
	return int_value.toString() + "px";
}

function watchMedia(x) {
	if(document.querySelector('.menus-container')){
		var menus = window.getComputedStyle(document.querySelector('.menus-container'));
		//document.querySelector('.navbar-menu-mobile').style.width = stringify2(parseInt(menus.getPropertyValue("width").slice(0, -2)));
		
		if(x.matches){ // If media query matches
			//document.querySelector('.navbar-menu-mobile').style.marginTop = '200px';
			document.querySelector('.menus-container').nextElementSibling.style.marginTop = stringify2(parseInt(menus.getPropertyValue("height").slice(0, -2)));
		} 
		else{
			document.querySelector('.menus-container').nextElementSibling.style.marginTop = "auto";
		}
	}
}

function openMobileNav(e){
	if(document.querySelector('.navbar-menu-mobile')){
		if(mobileNavOpened){
			document.querySelector('.navbar-menu-mobile').style.display = 'none';
			mobileNavOpened = false;
		}
		else{
			document.querySelector('.navbar-menu-mobile').style.display = 'block';
			mobileNavOpened = true;
		}
	}
}

function check_week_notifs(){
	if(all_pending_apps){
		for(var i=0; i<all_pending_apps.length; i++){
			if(withinTheWeek(moment(all_pending_apps[i].start_datetime), moment()) && moment(all_pending_apps[i].week_of_date).isBefore(moment(all_pending_apps[i].start_datetime))){
				if(moment().format("YYYY-MM-DD") !== moment(all_pending_apps[i].week_of_date).format("YYYY-MM-DD")){
					if(all_pending_apps[i].client_id == user_id){
						alert("Your appointment for " + all_pending_apps[i].start_datetime + " is " + String(Math.abs(moment().diff(moment(all_pending_apps[i].start_datetime), "days")) + 1) + " days from now. Don't miss it.");
					}
				
					manipulateNotifications2(linkViewer + 'send_daily_email.php', all_pending_apps[i].appointment_id, all_pending_apps[i].client_id, all_pending_apps[i].start_datetime, moment().format("YYYY-MM-DD"));
				}
			}
		}
	}
}
	

setInterval(function(){ 
	load_unseen_notification();		 
}, 5000);

//setInterval(function(){ 
		 
//}, 5000);

;
var all_pending_apps;
var mobileNavOpened = false;
manipulateNotifications(linkViewer + "get_appointments_for_notif.php", "", "", "", "");

var mediaWatcher = window.matchMedia("(max-width: 1500px)")
watchMedia(mediaWatcher) // Call listener function at run time
mediaWatcher.addListener(watchMedia)

if(document.querySelector('.mobile-toggle')){
	document.querySelector('.mobile-toggle').addEventListener('click', openMobileNav);
}

if(document.querySelector('.navbar-menu-mobile')){
	document.querySelector('.navbar-menu-mobile').style.display = 'none';
}

if(document.querySelector("#section-nums") && document.querySelector("#section-email") && document.querySelector("#section-address")){
	$.ajax({
		url: linkViewer + "manipulate_contact_info.php",
		method: "POST",
		data: {action: "get"},
		success: function(result){
			const data = JSON.parse(result);
			document.querySelector("#section-nums").textContent = data.contact_number;
			document.querySelector("#section-email").textContent = data.contact_email;
			document.querySelector("#section-address").textContent = data.contact_address;
		}
	})
}

if(document.querySelector("#fb-link1") && document.querySelector("#fb-link2") && document.querySelector("#ig-link1") && document.querySelector("#ig-link2")){
	$.ajax({
		url: linkViewer + "get_homepage_info.php",
		method: "GET",
		data: {caller: "get"},
		success: function(result){
			const data = JSON.parse(result);
			document.querySelector("#fb-link1").setAttribute('href', data.fb_link);
			document.querySelector("#fb-link2").setAttribute('href', data.fb_link);
			document.querySelector("#ig-link1").setAttribute('href', data.ig_link);
			document.querySelector("#ig-link2").setAttribute('href', data.ig_link);
		}
	})
}
