<?php
	session_start();
	include "backend_sidebar.php";
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
	<script src='../../js/jquery.min.js'></script>
	<script src='../../js/moment.min.js'></script>
	<script src="../../js/tinymce/tinymce.min.js"></script>
	<title>Providers Manager | Backend</title>
	<script>
        tinymce.init({
            selector: '#modal-bio',
			height : "50"
        });
    </script>
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
								<div><h3>Providers Manager</h3></div>
							</div>
							<hr>
							
							<div class="backend-body-mid-menu">
								<button class="backend-body-add-btn modal-btn">&#10133; Add new provider</button>
								<form action="download_sched.php" method="post">
									<button name="dl-pdf" type="submit" style="float: right; color: white; font-size: 1.2rem; padding: 10px; background: black; border: 0" class="modal-btn">Download all provider schedules</button>
								</form>
							</div>
							
							<div class="backend-body-content"></div>
						</div>
					</div>
				</div>
			';
		}
	?>

	<?php include '../../footer.php'; ?>
	<?php include '../../loader_popup.php'; ?>
	
	<div class="modal-popup">
		<div class="modal-content">
			<div class="modal-header">
				<span class="modal-header-text"></span>
				<div class="modal-close-btn"><span>&times;</span></div>
			</div>
			
			<div class="modal-body">
				<div class="tab-container">
					<button>Personal Information</button>
					<button>Services</button>
					<button>Working Days</button>
				</div>
				
				<div class="tab-panel" id="panel-1">
					<form class="modal-form" id="providers-modal-form" method="post" enctype="multipart/form-data">
						<p class="modal-instructions">Fields with an asterisk (<span class="required-asterisk">*</span>) are required.</p>
						<div>
							<label class="modal-label" id="first-modal-element">ID: <span class="modal-selected-id"></span></label>
							
							<label class="modal-label">Email: <span class="required-asterisk">*</span></label>
							<input class="modal-input" type="email" id="modal-email" disabled required/>
							
							
							<div class="modal-halved">
								<div>
									<label class="modal-label">Last name: <span class="required-asterisk">*</span></label>
									<input class="modal-input" type="text" id="modal-lname" disabled/>
								</div>
							
								<div>
									<label class="modal-label">First name: <span class="required-asterisk">*</span></label>
									<input class="modal-input" type="text" id="modal-fname" disabled/>
								</div>
							</div>
							
							<div class="modal-halved">
								<div>
									<label class="modal-label">Middle name: <span class="required-asterisk">*</span></label>
									<input class="modal-input" type="text" id="modal-mname" disabled/>
								</div>
								
								<div>
									<label class="modal-label" style="font-size: 0.9rem;">Name extension</label>
									<input class="modal-input" type="text" id="modal-name-ext" disabled/>
								</div>
							</div>
							
							<div class="modal-thirded">
								<div>
									<label class="modal-label">Month: <span class="required-asterisk">*</span></label>
									<select class="modal-input" id="month-box" disabled>
										<option selected disabled>Month*</option>
										<option>January</option>
										<option>February</option>
										<option>March</option>
										<option>April</option>
										<option>May</option>
										<option>June</option>
										<option>July</option>
										<option>August</option>
										<option>September</option>
										<option>October</option>
										<option>November</option>
										<option>December</option>
									</select>
								</div>
								
								<div>
									<label class="modal-label">Day: <span class="required-asterisk">*</span></label>
									<select class="modal-input" id="day-box" disabled></select>
								</div>
								
								<div>
									<label class="modal-label">Year: <span class="required-asterisk">*</span></label>
									<select class="modal-input" id="year-box" disabled>
										<option selected disabled>Year*</option>
									</select>
								</div>
							</div>
							
							<div class="modal-halved">
								<div>
									<label class="modal-label">Gender: <span class="required-asterisk">*</span></label>
									<select class="modal-input" id="modal-gender" disabled>
										<option selected disabled>---Select gender---</option>
										<option>Male</option>
										<option>Female</option>
									</select>
								</div>
									
								<div>
									<label class="modal-label">Position: <span class="required-asterisk">*</span></label>
									<select class="modal-input" id="modal-position">
										<option selected disabled>---Select position---</option>
										<option>Doctor</option>
										<option>Therapist</option>
									</select>	
								</div>
							</div>
						</div>
						
						<div>
							<div id="modal-img-container">
								<img src="../../img/empty_imgs.png" class="modal-preview-img"/>
								<input type="file" name="image" id="modal-img" accept="image/*"/>
								<div id="modal-img-options">
									<label for="modal-img" class="modal-img-btn">Choose a photo</label><br>
									<button class="modal-clear-img" id="img-clear-btn-from-backend-providers" type="button">Clear image</button>
								</div>
							</div>
						</div>
						
						<div id="modal-bio-container">
							<label class="modal-label">Bio / Qualifications / Awards won: <span class="required-asterisk">*</span></label>
							<textarea class="modal-input" id="modal-bio" rows="7"></textarea>
						</div>
					</form>
				</div>
				<div class="tab-panel" id="panel-2">
					<h2>Services</h2>
					<p>Lists all the services the specific provider performs.</p>
					<table id="individual-category-table">
						<thead>
							<th>Service Category</th>
							<th>Service Name</th>
							<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
						</thead>
						<tbody id="individual-category-table-body"></tbody>
					</table>
					<button type="button" class="modal-btn" id="add-provider-service">Add service</button>
				</div>
				<div class="tab-panel" id="panel-3">
					<h2>Working Days and Hours</h2>
					<p>Check the boxes below to display the days and hours that the provider is present at the clinic. Patients will not be able to book appointments in non-working periods.</p>
					<table id="working-days-table">
						<tr>
							<th>Day</th>
							<th>Start</th>
							<th>End</th>
						</tr>
						<tr>
							<td>
								<div class="checkbox">
									<label><input type="checkbox" class="checkDay" id="sunday" name="sunday">Sunday</label>
								</div>
							</td>
							<td>
								<input type="time" disabled id="sunday-time-start" name="sunday-time-start" class="sunday-time" required>
							</td>
							<td>
								<input type="time" disabled id="sunday-time-end" name="sunday-time-end" class="sunday-time" required>
							</td>
						</tr>
						<tr>
							<td>
								<div class="checkbox">
									<label><input type="checkbox" class="checkDay" id="monday" name="monday">Monday</label>
								</div>
							</td>
							<td>
								<input type="time" disabled id="monday-time-start" class="monday-time" name="monday-time-start" required>
							</td>
							<td>
								<input type="time" disabled id="monday-time-end" class="monday-time" name="monday-time-end" required>
							</td>
						</tr>
						<tr>
							<td>
								<div class="checkbox">
									<label><input type="checkbox" class="checkDay" id="tuesday" name="tuesday">Tuesday</label>
								</div>
							</td>
							<td>
								<input type="time" disabled id="tuesday-time-start" class="tuesday-time" name="tuesday-time-start" required>
							</td>
							<td>
								<input type="time" disabled id="tuesday-time-end" class="tuesday-time" name="tuesday-time-end" required>
							</td>
						</tr>
						<tr>
							<td>
								<div class="checkbox">
									<label><input type="checkbox" class="checkDay" id="wednesday" name="wednesday">Wednesday</label>
								</div>
							</td>
							<td>
								<input type="time" disabled id="wednesday-time-start" class="wednesday-time" name="wednesday-time-start" required>
							</td>
							<td>
								<input type="time" disabled id="wednesday-time-end" class="wednesday-time" name="wednesday-time-end" required>
							</td>
						</tr>
						<tr>
							<td>
								<div class="checkbox">
									<label><input type="checkbox" class="checkDay" id="thursday" name="thursday">Thursday</label>
								</div>
							</td>
							<td>
								<input type="time" disabled id="thursday-time-start" class="thursday-time" name="thursday-time-start" required>
							</td>
							<td>
								<input type="time" disabled id="thursday-time-end" class="thursday-time" name="thursday-time-end" required>
							</td>
						</tr>
						<tr>
							<td>
								<div class="checkbox">
									<label><input type="checkbox" class="checkDay" id="friday" name="friday">Friday</label>
								</div>
							</td>
							<td>
								<input type="time" disabled id="friday-time-start" class="friday-time" name="friday-time-start" required>
							</td>
							<td>
								<input type="time" disabled id="friday-time-end" class="friday-time" name="friday-time-end" required>
							</td>
						</tr>
						<tr>
							<td>
								<div class="checkbox">
									<label><input type="checkbox" class="checkDay" id="saturday" name="saturday">Saturday</label>
								</div>
							</td>
							<td>
								<input type="time" disabled id="saturday-time-start" class="saturday-time" name="saturday-time-start" required>
							</td>
							<td>
								<input type="time" disabled id="saturday-time-end" class="saturday-time" name="saturday-time-end" required>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<hr id="wew">
			<div class="modal-footer">
				<button type="submit" form="providers-modal-form" class="add-modal-btn modal-btn">Add provider</button>
				<button type="submit" form="providers-modal-form" class="update-modal-btn modal-btn">Update provider info</button>
				<form style="margin: 0; display: inline;" action="download_sched.php" method="post">
					<input type="text" style="display: none" name="provider_id" id="prov_input_id"/>
					<button name="dl-pdf" type="submit" style="color: white; padding: 5px; background: black; border: 0" class="modal-btn">Download schedule</button>
				</form>
			</div>
		</div>
	</div>
	
	<select id="shit" style="display: none;"></select>
	
	<script>
		var linkViewer = "";
		const userID = '<?php echo $_SESSION['client_id']; ?>';
		document.querySelector("#dashboard-providers-link").style.fontWeight = "bold";
		document.querySelector("#dashboard-providers-link").style.color = "green";
	</script>
	<script src="../../js/loaderPopupControl.js"></script>
	<script src="../../js/shared_backend_js.js"></script>
	<script src="../../js/loadData.js"></script>
	<script src="../../js/getImgurDict.js"></script>
	<script src="../../js/modalControl.js"></script>
	<script src="../../js/get_provider_services.js"></script>
	<script src="../../js/backend_providers_beta.js"></script>
	<script src="../../js/days_worked.js"></script>
	<script src="../../js/notifications.js"></script>
</body>
</html>