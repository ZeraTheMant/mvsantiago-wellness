<?php
	session_start();
	include 'load_working_hours.php';
	include 'backend_sidebar_provider.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel='stylesheet' href='../../css/fullcalendar.min.css'/>
	<link rel='stylesheet' href='../../css/icons.css'/>
	<link rel="stylesheet" type="text/css" href="../../css/all_icons.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/index_stylesheet.css"/>
	<link rel='stylesheet' href='../../css/real_backend_css.css'/>
	<script src='../../js/jquery.min.js'></script>
	<script src='../../js/moment.min.js'></script>
	<script src='../../js/fullcalendar.min.js'></script>
	<script src='../../js/popper.min.js'></script>
	<title>Dashboard Home | Backend</title>
</head>
<body>
	<?php
		if(!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 'provider'){
			header("Location: ../index.php?accessDenied");
		}
		else{
			echo '
				<div class="backend-container">
					' . $sidebar . '
				
					<div class="backend-page-body">
						' . $logo_holder . '
					
						<div class="backend-page-body-container">
							<div class="backend-body-header">
								<div><h3>Provider Dashboard Home</h3></div>
							</div>

							<div class="backend-body-content">
								<hr>
								<div id="dashboard-notif-container">
									<div id="statistics-boxes"></div>

									<div id="dashboard-inner-content">
										<div id="present-calendar-container">
											<div id="calendar-header">
												<p><span class="icon-calendar"></span> Appointments for Today</p>
											</div>
											<div id="hours-schedule"></div>
										</div>
										<div id="activity-feed">
											<div id="feeds-head">
												<span class="icon-notification"></span> <span>Activity Feed</span>
											</div>
											
											<div id="activity-feed-body">
												<div id="activities-tab-container">
													<span id="activities-tab">Activities</span>
													<span id="shit"></span>
												</div>
												
												<div id="pages-container">
													<button id="prev-page" class="modal-btn" disabled>prev</button>
													<button id="next-page" class="modal-btn">next</button>
												</div>
												
												<div id="activity-feed-content"></div>
											</div>
										</div>
									</div>
								</div>
							</div>	
						</div>
					</div>
				</div>
			';
		}
	?>
	

	<!--<div class="displayPopup">
		<div class="popupContent">
			<div class="popupHeader">
				<span class="popupHeaderText">View Appointment</span>
				<div class="popupCloseBtn"><span>&times;</span></div>
			</div>
			<div class="popupForm">
				<div id="appointment-details">
					<p class="popupFormContent">Date: <span id="appointment-date"></span></p>
					<p class="popupFormContent">Appointment ID: <span id="appointment-id"></span></p>
					<p class="popupFormContent">Appointment type: <span id="appointment-type"></span></p>
					<p class="popupFormContent">Booked by: <span id="booked-by"></span></p>
					<p class="popupFormContent">Start: <span id="appointment-start"></span></p>
					<p class="popupFormContent">Procedure: <span id="appointment-procedure"></span></p>
					<p class="popupFormContent">Dentist: <span id="appointment-dentist"></span></p>
					<p class="popupFormContent">Status: <span id="appointment-status"></span></p>
				</div>
				
				<div id="patient-details">
				
				</div>	
			</div>
		</div>
	</div>-->

	<?php include '../../footer.php'; ?>
	<?php include '../../loader_popup.php'; ?>
	
	<script src="../../js/loaderPopupControl.js"></script>
	<script>
		const userID = '<?php echo $_SESSION['client_id']; ?>';
		var linkViewer = "";
		const user_level = '<?php echo $_SESSION['user_level']; ?>';
		const current_user = '<?php echo $_SESSION['email'];?>';
		const provider_id = '<?php echo $_SESSION['provider_id'];?>';

		var schedule_string = <?php echo $working_schedule; ?>;
		var schedule_obj = JSON.parse(schedule_string);
		var business_hours = [];
				
		function getEventSourceDict(){
			var dict = {
				url: "load_appointments_for_dashboard.php",
				type: 'GET',
				cache: true,
				data: {start_datetime: moment().format("YYYY-MM-DD")}
			}
			return dict;
		}
		
		function getDayIndex(key){
			switch(key){
				case "sunday":
					return 0;
				case "monday":
					return 1;
				case "tuesday":
					return 2;
				case "wednesday":
					return 3;
				case "thursday":
					return 4;
				case "friday":
					return 5;
				case "saturday":
					return 6;
			}
		}
		
		for(var key in schedule_obj){
			var index = getDayIndex(key);
			var day = {
				dow: [index]
			};
			business_hours.push(day);
			var day = {
				dow: [index]
			};
			business_hours.push(day);
		}
		
		$(document).ready(function(){
			
			var isCurrentDayWithinBusinessHours = false;
			for(var key in schedule_obj){
				
				if(moment().format('dddd').toLowerCase() === key){
					isCurrentDayWithinBusinessHours = true;
					break;
				}
			}
			
			if(!isCurrentDayWithinBusinessHours){
				var index = getDayIndex(moment().format('dddd').toLowerCase());
				var emptyDay = {
					dow: [index],
					start: '00:00',
					end: '23:00',
				};
				business_hours.length = 0;
				business_hours.push(emptyDay);
			}
			
			var currDayStart;
			var currDayEnd;
			
			for(var i=0; i<business_hours.length; i++){
				const index = getDayIndex(moment().format('dddd').toLowerCase());
				
				if(!isCurrentDayWithinBusinessHours){
					if(index === business_hours[i].dow[0]){
						currDayStart = business_hours[i].start;
						currDayEnd = business_hours[i].end;
						break;
					}
				}
				else{
					if(index === business_hours[i].dow[0]){
						currDayStart = business_hours[i].start;
						currDayEnd = business_hours[i+1].end;
						break;
					}
				}
			}
			
			$("#hours-schedule").fullCalendar({
				header:{
					left: "",
					center: "title",
					right: ""
				},
				defaultView: 'agendaDay',
				eventSources: [
					getEventSourceDict()
					//"load_holidays.php"
				],
				allDaySlot: false,
				businessHours: business_hours,
				height: "auto",
				eventClick: function(event, jsEvent, view){
					popupControl(true);
					setAppointmentPopupValues(
						event.appointment_id,
						event.start,
						event.end,
						event.booked_by,
						event.booked_by_id,
						event.service_name,
						event.appointment_type,
						event.approved,
						event.elapsed,
						event.doctor_name
						
					);
					endDateTime = moment(event.end).format("YYYY-MM-DD hh:mm:ss");
					document.querySelector("#names-input").value = current_user;
					document.querySelector("#email").value = email;
					document.querySelector("#contact").value = contact_no;
					document.querySelector("#appointment-id").style.display = "block";
					document.querySelector("#appointment-id").textContent = "Appointment ID: " + event.appointment_id;
					document.querySelector("#services").value = event.service_name;
					document.querySelector("#dentist").value = event.doctor_name;
					document.querySelector("#timeslots").value = moment(event.start).format("h:mm A").toLowerCase();
				}
			});
			
			try{
				$('#hours-schedule').fullCalendar('option', 'minTime', currDayStart);
				$('#hours-schedule').fullCalendar('option', 'maxTime', currDayEnd);
			}catch(err){																		
				$('#hours-schedule').fullCalendar('option', 'maxTime', currDayEnd);
				$('#hours-schedule').fullCalendar('option', 'minTime', currDayStart);
			}
		});
		
		document.querySelector("#dashboard-home-link").style.fontWeight = "bold";
		document.querySelector("#dashboard-home-link").style.color = "green";
	</script>
	<script src="../../js/backend_provider_dashboard.js"></script>
	<script src="../../js/notifications.js"></script>

</body>