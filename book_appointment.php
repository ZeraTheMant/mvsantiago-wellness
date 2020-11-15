<?php
	session_start();
	include 'front_end_top_nav.php';
	include 'bottom_links.php';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel='stylesheet' href='css/fullcalendar.min.css'/>
	<link rel="stylesheet" type="text/css" href="css/index_stylesheet.css"/>
	<link rel="stylesheet" type="text/css" href="css/login_reg.css"/>
	<link rel="stylesheet" type="text/css" href="css/loader_display.css"/>
	<link rel="stylesheet" type="text/css" href="css/shared_backend_css.css"/>
	<script src='js/jquery.min.js'></script>
	<script src='js/moment.min.js'></script>
	<script src='js/fullcalendar.min.js'></script>
	<script src='js/popper.min.js'></script>
	<script>
		const contact_num = '<?php if(!isset($_SESSION['contact_number'])){echo "";}else{echo $_SESSION['contact_number'];} ?>';
		const user_id = '<?php if(!isset($_SESSION['client_id'])){echo "";}else{echo $_SESSION['client_id'];} ?>';
		const email = '<?php if(!isset($_SESSION['email'])){echo "";}else{echo $_SESSION['email'];} ?>'; 
		const name = '<?php $ucInitial=""; $fname=""; $mname=""; $lname=""; $name_ext=""; if(!isset($_SESSION['fname'])){echo "";}else{$fname=$_SESSION['fname'];} if(!isset($_SESSION['mname'])){echo "";}else{$mname=$_SESSION['mname'];} if(!isset($_SESSION['lname'])){echo "";}else{$lname=$_SESSION['lname']; $ucInitial=ucfirst($mname[0]);} if(!isset($_SESSION['name_ext'])){echo "";}else{$name_ext=$_SESSION['name_ext'];} echo $fname . ' ' . $ucInitial . '. ' . $lname . ' ' . $name_ext; ?>';
	</script>
	<title>Book Appointment | M.V. Santiago Wellness Center</title>
</head>
<body>
	<?php
		if(!isset($_SESSION['email'])){
			echo '
				<script type="text/javascript">
					alert("Please log-in first to schedule an appointment. If you do not have an account yet, please register first.");
					window.location = "http://localhost/itsp/index.php";
				</script>
			';
			exit();
		}
	?>

	<div class="wrapper">
	
		<?php
			echo $top_nav;
			include 'frontend_navbar.php';
		?>
	
		<div id="slider-outer-container">
			<div id="container">
				<div id="appointment-box-header">
					<div>
						<h2>Book appointment</h2>
					</div>
					<div id="steps-boxes-container">
						<div class="outer-steps-boxes"><div class="steps-boxes" id="current-step-box"><span>1</span></div></div>
						<div class="outer-steps-boxes"><div class="steps-boxes"><span>2</span></div></div>
						<div class="outer-steps-boxes"><div class="steps-boxes"><span>3</span></div></div>
					</div>
				</div>
				
				<div class="imgban" id="imgban1">
					<div class="relative">
						<h2>Step 3 - Confirm Appointment</h2>
						<p id="summary-greeting">Thank you for using our online appointment system.</p>
						<p>The following are the details of your provisional appointment, for your confirmation</p>
						<div id="summary-body">
							<div>
								<div class="summary-halved">
									<label class="appointment-form-info">Service:</label>
									<p id="summary-service"></p>
								</div>
								
								<div class="summary-halved">
									<label class="appointment-form-info">Category:</label>
									<p id="summary-category"></p>
								</div>
									
								<div class="summary-halved">
									<label class="appointment-form-info">Provider:</label>
									<p id="summary-provider"></p>
								</div>
									
								<div class="summary-halved">						
									<label class="appointment-form-info">Date:</label>
									<p id="summary-date"></p>
								</div>
									
								<div class="summary-halved">						
									<label class="appointment-form-info">Time:</label>
									<p id="summary-time"></p>
								</div>
								
								<div class="summary-halved">						
									<label class="appointment-form-info">Duration:</label>
									<p id="summary-duration"></p>
								</div>
								
								<br><br>
								<p id="terms-para"><input style="padding: 5px; transform: scale(1.5);" type="checkbox" id="terms-box"/>Check here to indicate that you have read and agreed to the <a href="terms.php" target="_blank">M.V. Santiago Wellness Center Terms of Service</a></p>
							</div>
							<div id="second-half">
								<h2 id="patient-name"></h2>
								<div class="summary-halved">						
									<label class="appointment-form-info">Contact number:</label>
									<p id="summary-contact"></p>
								</div>
								
								<div class="summary-halved">						
									<label class="appointment-form-info">Email:</label>
									<p id="summary-email"></p>
								</div>
								
								<div class="summary-halved">						
									<label class="appointment-form-info">Address:</label>
									<p id="summary-address"></p>
								</div>
							</div>
						</div>
						
						<div class="control-btns">
							<div class="control-btns-container">
								<button type="button" class="back-btn">&#x23ea; Back</button>
								<button type="button" class="confirm-btn">Finish &#x2713;</button>
							</div>
						</div>
					</div>
				</div>
				
				<div class="imgban" id="imgban2">
					<div class="relative">
						<h2>Step 2 - Select Date and Timeslot</h2>
						<div id="calendar-and-legend-container">
							<div id="date-section-container">
								<div id="cucklendar"><div id="calendar"></div></div>
								<fieldset>					
									<legend>Legend:</legend>
									<div id="legend-box-container">
										<div class="legend-container">
											<div id="day-open" class="legend-box"></div>
											<span>Available day</span>
										</div>
										
										<div class="legend-container">
											<div id="day-closed" class="legend-box"></div>
											<span>Closed day</span>
										</div>
										
										<div class="legend-container">
											<div id="day-current" class="legend-box"></div>
											<span>Current day</span>
										</div>
										
										<div class="legend-container">
											<div id="day-selected" class="legend-box"></div>
											<span>Selected day</span>
										</div>
									</div>
								</fieldset>
							</div>
							<div id="calendar-legend">
								<div id="hours-info-container">
									<p class="instruction"><em>Can only book from at least 2 days after the current date</em></p>
									<div>
										<br>
										<label class="appointment-form-info">Duration:</label>
										<p id="appointment-duration"></p>
										<label class="appointment-form-info appointment-form-info-marginned">Provider:</label>
										<p id="step-two-provider-name"></p>
									</div>
									<div>
										<br>
										<label class="appointment-form-info">Schedule:</label>
										<div id="step-two-provider-sched"></div>
									</div>
									<div class="spanned">
										<label class="appointment-form-info appointment-form-info-marginned">Available timeslots:</label>
										<p>
											<select id="hours-container">
												<option selected disabled>---Choose a timeslot---</option>
											</select>
										</p>
									</div>
								</div>
							</div>
						</div>
						
						<div class="control-btns">
							<div class="control-btns-container">
								<button type="button" class="back-btn">&#x23ea; Back</button>
								<button type="button" class="next-btn" id="date-slide-next" disabled>Next &#x23e9;</button>
							</div>
						</div>
					</div>
				</div>
				
				<div class="imgban" id="imgban3">
					<div class="relative">
						<h2>Step 1 - Select Category and Service</h2>
						<h4>Select Category</h4>
						<select class="appointment-select" id="appointment-category">
							<option selected disabled>---Choose a category---</option>
						</select>
						
						<h4>Select Service</h4>
						<select class="appointment-select" id="appointment-service">
							<option selected disabled>---Choose a service---</option>
						</select>
						
						<h4>Select Provider</h4>
						<select class="appointment-select" id="appointment-provider">
							<option selected disabled>---Choose a provider---</option>
						</select>
						
						<div class="control-btns">
							<div class="control-btns-container">
								<button type="button" class="next-btn" id="front-slide-next" disabled>Next &#x23e9;</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<?php
			echo $bottom_links;
			include 'footer.php';
		?>
	</div>
	
	<?php include 'login_and_register.php'; ?>
	<?php include 'loader_popup.php'; ?>
	<script>
		var linkViewer = "php/backend/";
		var user_appointments_dict;
		document.querySelector('#go-to-book-appointment').style.color = "green";
	</script>
	<script src="js/loaderPopupControl.js"></script>
	<script src="js/get_user_appointments_dict.js"></script>
	<script>
		getUserAppointmentsDict("php/backend/get_all_pending_appointments_of_user.php", user_id);
	</script>
	<script src="js/classes/provider.js"></script>
	<script src="js/get_provider_services.js"></script>
	<script src="js/test_appointment.js"></script>
	<script src="js/notifications.js"></script>
</body>
</html>