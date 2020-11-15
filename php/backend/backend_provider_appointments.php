<?php
	session_start();
	include 'backend_sidebar_provider.php';
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
		const table_link = "get_appointments_table_provider.php";
		const user_id = '<?php if(!isset($_SESSION['client_id'])){echo "";}else{echo $_SESSION['client_id'];} ?>';
		const provider_id = '<?php if(!isset($_SESSION['provider_id'])){echo "";}else{echo $_SESSION['provider_id'];} ?>';
		const email = '<?php if(!isset($_SESSION['email'])){echo "";}else{echo $_SESSION['email'];} ?>'; 
		const name = '<?php $ucInitial=""; $fname=""; $mname=""; $lname=""; $name_ext=""; if(!isset($_SESSION['fname'])){echo "";}else{$fname=$_SESSION['fname'];} if(!isset($_SESSION['mname'])){echo "";}else{$mname=$_SESSION['mname'];} if(!isset($_SESSION['lname'])){echo "";}else{$lname=$_SESSION['lname']; $ucInitial=ucfirst($mname[0]);} if(!isset($_SESSION['name_ext'])){echo "";}else{$name_ext=$_SESSION['name_ext'];} echo $fname . ' ' . $ucInitial . '. ' . $lname . ' ' . $name_ext; ?>';
	</script>
	<title>Provider Appointments Manager | Backend</title>
</head>
<body>
	<?php
		if(!isset($_SESSION['user_level']) || $_SESSION['user_level'] != "provider"){
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
								<div><h3>Provider Appointments Manager</h3></div>
							</div>
							
							<div class="backend-body-content">
								<hr>
								<select id="providers-select-user">
									<option selected>Upcoming appointments</option>
									<option>Past appointments</option>
								</select>
								<div class="body-content-container">
								
									<div id="backend-main-calendar-container"><div id="calendar"></div></div>
    									<div id="appointment-instructions">
    										<h1>Adding Services and Remarks Guide</h1>
    										<ol>
    											<img src="../../img/pastapps.png"/>
    											<li>
    												Select the past appointments section above the calendar.
    											</li>
    											<img src="../../img/provguide.png"/>
    											<li>
    												Select a finished appointment by left-clicking it.
    											</li>
    											<div class="modal-halved">
    												<img src="../../img/patientservices.png"/>
    												<img src="../../img/report.png"/>
    											</div>
    											<li>
    												Choose whether to allow further services or to add remarks.
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
						<button class="disappearing">Available Services</button>
						<button class="disappearing">Make Report</button>
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
					
					<div class="tab-panel" id="panel-2">
						<h2>Patient/Client's Available Services</h2>
						<p>Lists all the services available to the selected patient/client.</p>
						<table id="patient-services-table">
							<thead>
								<th>Service Category</th>
								<th>Service Name</th>
								<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
							</thead>
							<tbody id="patient-services-table-body"></tbody>
						</table>
						<button type="button" class="modal-btn" id="add-patient-service">Add service</button>
						
						<div id="patient-services-div-options">
							<button type="button" class="modal-btn save-patient-services">Save settings</button>
						</div>
					</div>
					
					<div class="tab-panel" id="panel-3">
						<p>Enter your remarks regarding the completed procedure.</p>
						<textarea class="modal-input" rows="7" id="remarks"></textarea><br>
						<button type="button" class="modal-btn" id="create-report">Create report</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<?php include '../../loader_popup.php'; ?>
	<select id="shit" style="display: none;"></select>
	
	<script src="../../js/loaderPopupControl.js"></script>
	<script src="../../js/get_user_appointments_dict.js"></script>
	<script src="../../js/modalControl.js"></script>
	<script src="../../js/get_provider_services.js"></script>
	<script>
		var user_appointments_dict;
		var linkViewer = "";
		var myCatsServs;
		
		$.ajax({
			url: "get_ps.php",
			method: "GET",
			data: {provider_id: provider_id},
			success: function(result){
				if(result){
					myCatsServs = JSON.parse(result);
				}
			}
		});
		
		const userID = '<?php echo $_SESSION['client_id']; ?>';
		getUserAppointmentsDict("get_all_pending_appointments_of_provider.php", provider_id);
		
		document.querySelector("#dashboard-appointments-link").style.fontWeight = "bold";
		document.querySelector("#dashboard-appointments-link").style.color = "green";
	</script>
	<script src="../../js/backend_appointments.js"></script>
	<script src="../../js/backend_provider_appointments.js"></script>
	<script src="../../js/notifications.js"></script>
</body>
</html>