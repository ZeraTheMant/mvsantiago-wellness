<?php
	session_start();
	include 'get_clinic_hours_backend.php';
	include 'backend_sidebar.php';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" type="text/css" href="../../css/loader_display.css"/>
	<link rel='stylesheet' href='../../css/icons.css'/>
	<link rel="stylesheet" type="text/css" href="../../css/all_icons.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/index_stylesheet.css"/>
	<link rel='stylesheet' href='../../css/real_backend_css.css'/>
	<link rel="stylesheet" type="text/css" href="../../css/shared_backend_css.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/clinic_hours.css"/>
	<script src='../../js/jquery.min.js'></script>
	<script src='../../js/moment.min.js'></script>
	<title>Clinic Hours | Backend</title>
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
								<div><h3>Clinic Hours Manager</h3></div>
							</div>
							<hr>
						
							<div class="backend-body-content">
								<div id="working-days-table">
									<h2>Working Days</h2>
									<p>
										Select options for each day on whether or not the clinic will accept appointments for that day.
									</p>

									<table>
										<thead>
											<tr>
												<th>Day</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Sunday</td>
												<td>
													<select class="working-day" id="sunday-status">
														<option selected>Open</option>
														<option>Closed</option>
													</select>
												</td>
											</tr>
											
											<tr>
												<td>Monday</td>
												<td>
													<select class="working-day" id="monday-status">
														<option selected>Open</option>
														<option>Closed</option>
													</select>
												</td>
											</tr>
											
											<tr>
												<td>Tuesday</td>
												<td>
													<select class="working-day" id="tuesday-status">
														<option selected>Open</option>
														<option>Closed</option>
													</select>
												</td>
											</tr>
											
											<tr>
												<td>Wednesday</td>
												<td>
													<select class="working-day" id="wednesday-status">
														<option selected>Open</option>
														<option>Closed</option>
													</select>
												</td>
											</tr>
											
											<tr>
												<td>Thursday</td>
												<td>
													<select class="working-day" id="thursday-status">
														<option selected>Open</option>
														<option>Closed</option>
													</select>
												</td>
											</tr>
											
											<tr>
												<td>Friday</td>
												<td>
													<select class="working-day" id="friday-status">
														<option selected>Open</option>
														<option>Closed</option>
													</select>
												</td>
											</tr>
											
											<tr>
												<td>Saturday</td>
												<td>
													<select class="working-day" id="saturday-status">
														<option selected>Open</option>
														<option>Closed</option>
													</select>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								
								<div>
									<div id="specific-closed-days-table">
										<h2>Specified Closed Days</h2>
										<p>
											Include exact days during which the clinic is closed. May include holidays and other emergency cases.
										</p>
										<table>
											<thead>
												<th>Month</th>
												<th>Day</th>
												<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
											</thead>
											<tbody>
											
											</tbody>
										</table>
										<button type="button" id="add-closed-day" class="modal-btn">Add closed day</button>
									</div>
									<div>
										<h2>Max Online Appointments Per Day</h2>
										<label>Enter here:</label>&nbsp;&nbsp;<input value="' . $plan['max_num_of_online_appointments_per_day'] . '" type="number" style="width: 100px; padding: 5px;" id="max-num" min="1"/>
									</div>
								</div>
							</div>
							<button type="button" id="save-clinic-hours" class="modal-btn">Save Settings</button>
						</div>
					</div>
				</div>
			';
		}
	?>

	<?php include '../../footer.php'; ?>
	<?php include '../../loader_popup.php'; ?>
	
	<script>
		var linkViewer = "";
		const userID = '<?php echo $_SESSION['client_id']; ?>';
		var closed_days = '<?php echo $plan['specified_closed_days']; ?>';
		var working_schedule = <?php echo $plan['days_open']; ?>;
		
		document.querySelector("#dashboard-hours-link").style.fontWeight = "bold";
		document.querySelector("#dashboard-hours-link").style.color = "green";
	</script>
	
	<script src="../../js/loaderPopupControl.js"></script>
	<script src="../../js/backend_clinic_hours.js"></script>
	<script src="../../js/notifications.js"></script>
</body>
</html>