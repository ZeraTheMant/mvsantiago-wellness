<?php
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" type="text/css" href="../../css/loader_display.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/shared_backend_css.css"/>
	<script src='../../js/jquery.min.js'></script>
	<script src='../../js/moment.min.js'></script>
	<title>Providers Manager | Backend</title>
</head>
<body>
	<?php
		if(!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 'admin'){
			header("Location: ../../index.php?accessDenied");
		}
		else{
			echo '
				<div class="backend-page-body">
					<div class="backend-body-header">
						<div><h3>Providers Manager</h3></div>
					</div>
					
					<div class="backend-body-mid-menu">
						<button class="backend-body-add-btn modal-btn">&#10133; Add new provider</button>
					</div>
					
					<div class="backend-body-content"></div>
				</div>
			';
		}
	?>

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
							
							<label class="modal-label">Last name: <span class="required-asterisk">*</span></label>
							<input class="modal-input" type="text" id="modal-lname"/>
							
							<label class="modal-label">First name: <span class="required-asterisk">*</span></label>
							<input class="modal-input" type="text" id="modal-fname"/>
							
							<label class="modal-label">Middle name: <span class="required-asterisk">*</span></label>
							<input class="modal-input" type="text" id="modal-mname"/>
							
							<label class="modal-label">Name extention (ex. Jr., III.):</label>
							<input class="modal-input" type="text" id="modal-name-ext"/>
							
							<label class="modal-label">Gender: <span class="required-asterisk">*</span></label>
							<select class="modal-input" id="modal-gender">
								<option selected disabled>---Select gender---</option>
								<option>Male</option>
								<option>Female</option>
							</select>
							
							<label class="modal-label">Position: <span class="required-asterisk">*</span></label>
							<select class="modal-input" id="modal-position">
								<option selected disabled>---Select position---</option>
								<option>Doctor</option>
								<option>Therapist</option>
							</select>			
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
							<label class="modal-label">Bio: <span class="required-asterisk">*</span></label>
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
					<p>Check the boxes below to display the days and hours that the provider is present at the clinic. Patients will not be able to book appointments by themselves in non-working periods.</p>
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
				<button type="submit" form="providers-modal-form" class="add-modal-btn modal-btn">Add new provider</button>
				<button type="submit" form="providers-modal-form" class="update-modal-btn modal-btn">Update provider info</button>
				<button type="button" class="delete-modal-btn modal-btn">Delete provider</button>
			</div>
		</div>
	</div>
	
	<select id="shit" style="display: none;"></select>
	
	<script src="../../js/loaderPopupControl.js"></script>
	<script src="../../js/getImgurDict.js"></script>
	<script src="../../js/shared_backend_js.js"></script>
	<script src="../../js/loadData.js"></script>
	<script src="../../js/modalControl.js"></script>
	<script src="../../js/get_provider_services.js"></script>
	<script src="../../js/backend_providers.js"></script>
	<script src="../../js/days_worked.js"></script>
</body>
</html>