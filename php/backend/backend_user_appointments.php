<?php
	session_start();
	include 'backend_sidebar_client.php';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel='stylesheet' href='../../css/fullcalendar.min.css'/>
	<link rel='stylesheet' href='../../css/icons.css'/>
	<link rel="stylesheet" type="text/css" href="../../css/all_icons.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/loader_display.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/shared_backend_css.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/index_stylesheet.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/real_backend_css.css"/>
	<script src='../../js/jquery.min.js'></script>
	<script src='../../js/moment.min.js'></script>
	<script src='../../js/fullcalendar.min.js'></script>
	<script src='../../js/popper.min.js'></script>
	<script src='../../js/scheduler.min.js'></script>
	<script>
		const table_link = "get_appointments_table_user.php";
		const contact_num = '<?php if(!isset($_SESSION['contact_number'])){echo "";}else{echo $_SESSION['contact_number'];} ?>';
		const provider_id = '<?php if(!isset($_SESSION['provider_id'])){echo "";}else{echo $_SESSION['provider_id'];} ?>';
		const user_id = '<?php if(!isset($_SESSION['client_id'])){echo "";}else{echo $_SESSION['client_id'];} ?>';
		const email = '<?php if(!isset($_SESSION['email'])){echo "";}else{echo $_SESSION['email'];} ?>'; 
		const name = '<?php $ucInitial=""; $fname=""; $mname=""; $lname=""; $name_ext=""; if(!isset($_SESSION['fname'])){echo "";}else{$fname=$_SESSION['fname'];} if(!isset($_SESSION['mname'])){echo "";}else{$mname=$_SESSION['mname'];} if(!isset($_SESSION['lname'])){echo "";}else{$lname=$_SESSION['lname']; $ucInitial=ucfirst($mname[0]);} if(!isset($_SESSION['name_ext'])){echo "";}else{$name_ext=$_SESSION['name_ext'];} echo $fname . ' ' . $ucInitial . '. ' . $lname . ' ' . $name_ext; ?>';
	</script>
	<title>User Appointments Manager | Backend</title>
</head>
<body>
	<?php
		if(!isset($_SESSION['user_level'])){
			header("Location: ../../index.php?accessDenied");
		}
		else{
			echo '
				<div class="backend-container">
					' . $sidebar . '
					
					<div class="backend-page-body">
						' . $logo_holder . '
						
						<div class="backend-page-body-container">
							<div class="backend-body-header">
								<div><h3>User Appointments Manager</h3></div>
							</div>
							
							<div class="backend-body-content">
								<select id="providers-select-user">
									<option selected>Upcoming appointments</option>
									<option>Past appointments</option>
									<option>Cancelled appointments</option>
								</select>
								<div class="body-content-container">
								
									<div id="backend-main-calendar-container"><div id="calendar"></div></div>
									<div id="appointment-instructions">
										<h1>Rescheduling and Cancellation Guide</h1>
										<ol>
											<img src="../../img/guide.png"/>
											<li>
												Select an appointment by left-clicking it.
											</li>
											<img src="../../img/buttons.png"/>
											<li>
												From the buttons on the lower side, choose whether to reschedule or to cancel.
											</li>
											<div class="modal-halved">
												<img src="../../img/resched.png"/>
												<img src="../../img/cancel.png"/>
											</div>
											<li>
												Complete your chosen task.
											</li>
										</ol>
									</div>
									<fieldset>
										<legend>Legend</legend>
										<div id="legend-box-container" class="backend-only">
											<div>
												<div class="legend-container">
													<div id="day-open" class="legend-box"></div>
													<span>Available day</span>
												</div>
												
												<div class="legend-container">
													<div id="day-current" class="legend-box"></div>
													<span>Current day</span>
												</div>
												
												<div class="legend-container">
													<div id="confirmed-appointment" class="legend-box"></div>
													<span>Confirmed Appointments</span>
												</div>
											</div>
											
											<div>
												<div class="legend-container">
													<div id="day-closed" class="legend-box"></div>
													<span>Closed day</span>
												</div>
												
												<div class="legend-container">
													<div id="day-selected" class="legend-box"></div>
													<span>Selected day</span>
												</div>
												
												<div class="legend-container">
													<div id="unconfirmed-appointment" class="legend-box"></div>
													<span>Unconfirmed Appointments</span>
												</div>
											</div>
										</div>
									</fieldset>
									
									<div id="appointment-table-container">
										<div id="appointment-table"></div>
										<p id="dummy-para" style="display: none"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			';
		}
	?>
	
	<div class="modal-popup">
		<div class="modal-content">
			<div class="modal-header">
				<span class="modal-header-text"></span>
				<div class="modal-close-btn"><span>&times;</span></div>
			</div>
			
			<div id="modal-form-content">
				<div class="modal-body">
					<div class="tab-container">
						<button>Appointment Information</button>
					</div>
					
					<div class="tab-panel" id="panel-1">
						<form class="modal-form" id="providers-modal-form" method="post" enctype="multipart/form-data">
							
								<label class="modal-label" id="first-modal-element">ID: <span class="modal-selected-id"></span></label>
								
								<label class="modal-label">Service:</label>
								<input class="modal-input" type="text" id="modal-service" disabled/>
								
								<label class="modal-label">Catalog:</label>
								<input class="modal-input" type="text" id="modal-category" disabled/>
								
								<label class="modal-label">Provider:</label>
								<input class="modal-input" type="text" id="modal-provider" disabled/>
								
								<label class="modal-label">Patient:</label>
								<input class="modal-input" type="text" id="modal-patient" disabled/>

								<label class="modal-label">Date:</label>
								<input class="modal-input" type="text" id="modal-date" disabled/>
								
								<div class="modal-halved">
									<div>
										<label class="modal-label">Start:</label>
										<input class="modal-input" type="text" id="modal-start" disabled/>
									</div>
									
									<div>
										<label class="modal-label">End:</label>
										<input class="modal-input" type="text" id="modal-end" disabled/>
									</div>
								</div>
						</form>
					</div>
				</div>

				<hr id="wew">
				<div class="modal-footer">
					<button type="button" class="reschedule-modal-btn modal-btn">Reschedule appointment</button>
					<button type="button" class="cancel-modal-btn modal-btn">Cancel appointment</button>
				</div>
			</div>
			
			<div id="reschedule-form-content">
				<div id="resched-form-header">
					<span><em>To change provider and/or service, cancel this appointment and <a href="http://localhost/itsp/book_appointment.php">book</a> a new one.</em></span>
				
					<span id="current-appointment-span">Your current appointment: <span id="current-appointment-date"></span> from <span id="current-appointment-start"></span> to <span id="current-appointment-end"></span>.</span>
					<span>Your new appointment: <span id="no-display"><span id="new-appointment-date"></span> from <span id="new-appointment-start"></span> to <span id="new-appointment-end"></span>.</span></span>
				</div>
			
				<div id="resched-form-halved">
					<div id="backend-reschedule-calendar-container"><div id="reschedule-calendar"></div></div>
					<div>
						<div id="resched-timeslot-container"></div>
					</div>
				
				</div>
				<div class="inside-form-footer">
					<button class="confirm-reschedule-btn modal-btn" type="button">Confirm</button>
				</div>
			</div>
			
			<div id="cancellation-form-content">
				<p class="modal-instructions">Please indicate the reason for this appointment's cancellation. <span class="required-asterisk">*</span></p>
				 
				<div class="modal-halved">
				    <div>
                        <p class="modal-label">
                            <input type="radio" name="reason" value="Emergency"> Emergency
                        </p>
                        <p class="modal-label">
                            <input type="radio" name="reason" value="Change of plans"> Change of plans
                        </p>
                        <p class="modal-label">
                            <input type="radio" name="reason" value="Other reason"> Other (specify below)
                        </p>
                        <input style="margin-left: 20px;" type="text" class="modal-input" id="other-reason"/>
                    </div>
                    <div>
                        <p class="modal-label">
                            <input type="radio" name="reason" value="Other commitments"> Other commitments
                        </p>                                                  
                        <p class="modal-label">
                            <input type="radio" name="reason" value="Appointment made in error"> Appointment made in error
                        </p>
                        <p class="modal-label">
                            <input type="radio" name="reason" value="No reason"> No reason
                        </p>
                    </div>
                </div>
				<div class="inside-form-footer">
					<button class="confirm-cancel-btn modal-btn" type="button">Send reason</button>
				</div>
			</div>
		</div>
	</div>
	
	<?php include '../../loader_popup.php'; ?>
	<?php include '../../footer.php'; ?>
	
	<script src="../../js/loaderPopupControl.js"></script>
	<script src="../../js/get_user_appointments_dict.js"></script>
	<script src="../../js/modalControl.js"></script>
	<script>
		function loadClientServices(id){
			return null;
		}
	
		var linkViewer = "";
		const userID = '<?php echo $_SESSION['client_id']; ?>';
		var user_appointments_dict;
		getUserAppointmentsDict("get_all_pending_appointments_of_user.php", user_id);
		
		document.querySelector("#dashboard-appointments-link").style.fontWeight = "bold";
		document.querySelector("#dashboard-appointments-link").style.color = "green";
	</script>
	<script src="../../js/backend_appointments.js"></script>
	<script src="../../js/backend_user_appointments.js"></script>
	<script src="../../js/notifications.js"></script>
</body>
</html>