<?php
	session_start();
	include 'load_working_hours.php';
	include 'backend_sidebar.php';
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
	<link rel='stylesheet' href='../../css/shared_backend_css.css'/>
	<link rel='stylesheet' href='../../css/real_backend_css.css'/>
	<script src='../../js/jquery.min.js'></script>
	<script src='../../js/moment.min.js'></script>
	<script src='../../js/fullcalendar.min.js'></script>
	<script src='../../js/popper.min.js'></script>
	<title>Dashboard Home | Backend</title>
	<style>
		#statistics-boxes{
			grid-template-columns: repeat(4, 1fr);
		}
	</style>
</head>
<body>
	<?php
		if(!isset($_SESSION['user_level']) || ($_SESSION['user_level'] != 'admin' && $_SESSION['user_level'] != 'semiadmin')){
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
								<div><h3>Admin Dashboard Home</h3></div>
							</div>

							<div class="backend-body-content">
								<hr>
								<div id="dashboard-notif-container">
									<div id="statistics-boxes"></div>

									<div id="dashboard-inner-content">
										<div id="present-calendar-container">
											<div id="calendar-header">
												<p><span class="icon-calendar"></span> Appointment Statistics</p>
											</div>
											<canvas id="appointment-stats"></canvas>
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
	
	<script src="../../js/Chart.min.js"></script>
	<script src="../../js/loaderPopupControl.js"></script>
	<script>
		var linkViewer = "";
		const userID = '<?php echo $_SESSION['client_id']; ?>';
		const user_level = '<?php echo $_SESSION['user_level']; ?>';
		const current_user = '<?php echo $_SESSION['email'];?>';
		var schedule_string = <?php echo $working_schedule; ?>;
		var schedule_obj = JSON.parse(schedule_string);
		var business_hours = [];
				

		
		document.querySelector("#dashboard-home-link").style.fontWeight = "bold";
		document.querySelector("#dashboard-home-link").style.color = "green";
	</script>
	<script src="../../js/backend_admin_dashboard.js"></script>
	<script src="../../js/notifications.js"></script>

</body>