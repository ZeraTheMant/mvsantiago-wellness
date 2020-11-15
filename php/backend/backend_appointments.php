<?php
	session_start();
	include 'backend_sidebar.php';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel='stylesheet' href='../../css/fullcalendar.min.css'/>
	<link rel="stylesheet" type="text/css" href="../../css/loader_display.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/index_stylesheet.css"/>
	<link rel='stylesheet' href='../../css/icons.css'/>
	<link rel="stylesheet" type="text/css" href="../../css/all_icons.css"/>
	<link rel='stylesheet' href='../../css/real_backend_css.css'/>
	<link rel="stylesheet" type="text/css" href="../../css/shared_backend_css.css"/>
	<script src='../../js/jquery.min.js'></script>
	<script src='../../js/moment.min.js'></script>
	<script src='../../js/fullcalendar.min.js'></script>
	<script src='../../js/popper.min.js'></script>
	<script src='../../js/scheduler.min.js'></script>
	<script>
		const table_link = "get_appointments_table_admin.php";
		const provider_id = '<?php if(!isset($_SESSION['provider_id'])){echo "";}else{echo $_SESSION['provider_id'];} ?>';
		const user_id = '<?php if(!isset($_SESSION['client_id'])){echo "";}else{echo $_SESSION['client_id'];} ?>';
	</script>
	<title>Appointments Manager | Backend</title>
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
								<div><h3>Appointments Manager</h3></div>
							</div>
							
							<div class="backend-body-content">
								<hr>
								<select id="appointment-view">
									<option selected>Upcoming appointments</option>
									<option>Finished appointments</option>
								</select><br>
								<select id="providers-select"></select>
								<div class="body-content-container">
									<div id="backend-main-calendar-container"><div id="calendar"></div></div>
									<div id="appointment-instructions">
										<h1>Viewing Appointment Details</h1>
										<ol>
											<img src="../../img/guide.png"/>
											<li>
												Select an appointment by left-clicking it.
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
		</div>
	</div>
	
	<?php include '../../footer.php'; ?>
	<?php include '../../loader_popup.php'; ?>
	
	<script src="../../js/loaderPopupControl.js"></script>
	<script src="../../js/modalControl.js"></script>
	<script src="../../js/backend_appointments.js"></script>
	<script>	
		function getAllProvidersParam(){
			$('#providers-select').html("");
			loaderPopupControl(true);
			$.ajax({
				url: 'get_all_providers.php',
				method: 'GET',
				success: function(result){
					providers_dict = JSON.parse(result);
					
					var all_providers = document.createElement('option');
					all_providers.selected = true;
					all_providers.textContent = "All providers";
					document.querySelector('#providers-select').appendChild(all_providers);
					
					for(var i=0; i<providers_dict.length; i++){
						var provider_level = ''; 
						if(providers_dict[i].provider_level.toLowerCase() === 'doctor' || providers_dict[i].provider_level.toLowerCase() === 'dentist'){
							provider_level = 'Dr.'; 
						}
						
						var provider = document.createElement('option');
						provider.textContent = provider_level + ' ' + providers_dict[i].fname + ' ' + providers_dict[i].mname[0].toUpperCase() + '. ' + providers_dict[i].lname + ' ' + providers_dict[i].name_ext;
						provider.setAttribute("id", providers_dict[i].provider_id);
						document.querySelector('#providers-select').appendChild(provider);
					}

					getClinicHours(getEventSourceDict("", 0, 0), getProvidersColumns(""));
					
					loaderPopupControl(false);
				}
			});
		}
		
		function loadClientServices(id){
			return null;
		}
		
		function returnAppointmentView(){
			if(document.querySelector("#appointment-view").value === "Upcoming appointments"){
				return 0;
			}
			else{
				return 1;
			}
		}
		
		function changeAppointmentViewAdmin(e){
			getClinicHours(getEventSourceDict(e.target.options[e.target.selectedIndex].id, returnAppointmentView(), 0), getProvidersColumns(e.target.options[e.target.selectedIndex].id));
			selected_provider_id = e.target.options[e.target.selectedIndex].id;
			getAppointmentTable(selected_provider_id, returnAppointmentView(), 0);
		}

		
		linkViewer = "";
		getAllProviders(getAllProvidersParam);
		
		document.querySelector("#dashboard-appointments-link").style.fontWeight = "bold";
		document.querySelector("#dashboard-appointments-link").style.color = "green";
		document.querySelector('#appointment-view').addEventListener('change', changeAppointmentViewAdmin);
		
		getAppointmentTable("", 0, 0);
	</script>
	<script src="../../js/notifications.js"></script>

</body>
</html>