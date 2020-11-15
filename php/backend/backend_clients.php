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
	<link rel="stylesheet" type="text/css" href="../../css/backend_messages.css"/>
	<script src='../../js/jquery.min.js'></script>
	<script src='../../js/moment.min.js'></script>
	<title>Accounts Manager | Backend</title>
	<style>
		#change-user-settings, #create-admin, #submit-admin-btn{
			border: 0;
			color: white;
			padding: 10px;
			background: green;
		}
		
		#submit-admin-btn{
			margin-top: 10px;
		}
		
		#change-user-settings, #create-admin{
			float: right;
		}
		
		#panel-1 .modal-instructions{
			padding-top: 30px;
		}
		
		#email-instructions{
			grid-column: span 3;
		}
		
		#emailForm{
			display: grid;
			grid-template-columns: 1fr 0.5fr;
			grid-gap: 1rem;
		}
	</style>
</head>

<body>
	<?php
		if(!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 'admin'){
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
								<div><h3>Accounts Manager</h3></div>
							</div>
							<hr>
							
							<button id="create-admin" type="button" class="modal-btn">Create administrator</button>
							
							<div id="client-status-container">					
								<select id="client-status" name="client-status" class="padded-select">
									<option selected>Regular users</option>
									<option>Administrators</option>
								</select>
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
					<button>Eligible Services</button>
					<button>Appointment History</button>
				</div>
				
				<div class="tab-panel" id="panel-1">
					<form class="modal-form" id="providers-modal-form" method="post" enctype="multipart/form-data">
						<p class="modal-instructions">View information regarding a patient/client.</p>
																
						<div>
							<div id="modal-img-container">
								<img src="../../img/empty_imgs.png" class="modal-preview-img"/>
							</div>
							<button type="button" id="change-user-settings" class="modal-btn">Change user level</button>
						</div>
						
						<label class="modal-label" id="first-modal-element">ID: <span class="modal-selected-id"></span></label>
						
						<div class="modal-halved">
							<div>
								
								<label class="modal-label">Last name:</label>
								<input class="modal-input" type="text" id="modal-lname" disabled/>
								
								<label class="modal-label">First name:</label>
								<input class="modal-input" type="text" id="modal-fname" disabled/>
								
								<label class="modal-label">Middle name:</label>
								<input class="modal-input" type="text" id="modal-mname" disabled/>
								
								<label class="modal-label">Name extention (ex. Jr., III.):</label>
								<input class="modal-input" type="text" id="modal-name-ext" disabled/>
								
							</div>	
							
							<div>
								<label class="modal-label">Date of birth:</label>
								<input class="modal-input" type="text" id="modal-dob" disabled/>	

								<label class="modal-label">Age:</label>
								<input class="modal-input" type="text" id="modal-age" disabled/>								
								
								<label class="modal-label">Email:</label>
								<input class="modal-input" type="email" id="modal-email" disabled/>
								
								<label class="modal-label">Contact number:</label>
								<input class="modal-input" type="number" id="modal-contact" disabled/>
								
								<select style="display: none;" class="modal-input" id="modal-user-level">
									<option selected disabled>---Select user level---</option>
									<option>Admin</option>
									<option>Client</option>
								</select>		
							</div>

						</div>
						
						<div id="modal-bio-container">
						    <div class="modal-halved">
						        <div>
        							<label class="modal-label">Address:</label>
        							<textarea class="modal-input" id="modal-address" rows="7" disabled></textarea>
						        </div>
						        <div id="conditions-container">
						            <p>Health conditions</p>
						            
						            <ul>
						                <li id="heart-bullet">
						                    Suffers from heart condition.
						                </li>
						                <li id="skin-bullet">
						                    Suffers from skin condition.
						                </li>
						                <li id="allergy-bullet">
						                    Suffers from allergies.
						                </li>
						            </ul>
						        </div>
							</div>
						</div>
					</form>
				</div>
				
				<div class="tab-panel" id="panel-2">
					<h2>Eligible Services</h2>
					<p>Lists all the services requiring approval that the patient/client can avail.</p>
					<table id="individual-category-table">
						<thead>
							<th>Service Category</th>
							<th>Service Name</th>
						</thead>
						<tbody id="individual-category-table-body"></tbody>
					</table>
				</div>
				
				<div class="tab-panel" id="panel-3">
					<h2>Appointment History</h2>
					<select id="user-appointment-type">
						<option>Upcoming Appointments</option>
						<option>Past Appointments</option>
					</select>
					<div id="individual-appointment-table"></div>
				</div>
			</div>

		</div>
	</div>
	
	<div id="emailPopup">
		<div id="reply-modal-content">
			<div class="modal-header">
				<span class="popupHeaderText">Create administrator</span>
				<div class="popupCloseBtn"><span>&times;</span></div>
			</div>
			<div class="popupForm">
				<form class="modal-form" id="emailForm" method="post" enctype="multipart/form-data">
					<p id="email-instructions" class="modal-instructions">Fields with an asterisk (<span class="required-asterisk">*</span>) are required.</p>
					<div>
						<div>
							<label class="modal-label">Email: <span class="required-asterisk">*</span></label>
							<input class="modal-input" type="email" id="admin-email" required/>
							
							<div class="modal-halved">
								<div>
									<label class="modal-label">Last name: <span class="required-asterisk">*</span></label>
									<input class="modal-input" type="text" id="admin-lname"/>
								</div>
							
								<div>
									<label class="modal-label">First name: <span class="required-asterisk">*</span></label>
									<input class="modal-input" type="text" id="admin-fname"/>
								</div>
							</div>
							
							<div class="modal-halved">
								<div>
									<label class="modal-label">Middle name: <span class="required-asterisk">*</span></label>
									<input class="modal-input" type="text" id="admin-mname"/>
								</div>
								
								<div>
									<label class="modal-label" style="font-size: 0.9rem;">Name extension</label>
									<input class="modal-input" type="text" id="admin-name-ext"/>
								</div>
							</div>
							
							<div class="modal-thirded">
								<div>
									<label class="modal-label">Month: <span class="required-asterisk">*</span></label>
									<select class="modal-input" id="admin-month-box">
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
									<select class="modal-input" id="admin-day-box"></select>
								</div>
								
								<div>
									<label class="modal-label">Year: <span class="required-asterisk">*</span></label>
									<select class="modal-input" id="admin-year-box">
										<option selected disabled>Year*</option>
									</select>
								</div>
							</div>
							
						</div>
						<button id="submit-admin-btn" type="submit" form="emailForm" class="modal-btn">Create administrator</button>
					</div>
					
					<div>
					
						<div>
							<div id="admin-img-container">
								<img src="../../img/empty_imgs.png" class="admin-preview-img"/>
								<input type="file" name="image" id="admin-img" accept="image/*"/>
								<div id="admin-img-options">
									<label for="admin-img" class="admin-img-btn" style="background-color: green;">Choose a photo</label><br>
									<button class="admin-clear-img" id="admin-img-clear-btn-from-backend-providers" type="button">Clear image</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<select id="shit" style="display: none;"></select>
	
	<script>
		var linkViewer = "";
		const userID = '<?php echo $_SESSION['client_id']; ?>';
		const user_id = userID;
		document.querySelector("#dashboard-users-link").style.fontWeight = "bold";
		document.querySelector("#dashboard-users-link").style.color = "green";
	</script>
	<script src="../../js/loaderPopupControl.js"></script>
	<script src="../../js/getImgurDict.js"></script>
	<script src="../../js/shared_backend_js.js"></script>
	<script src="../../js/loadData.js"></script>
	<script src="../../js/modalControl.js"></script>
	<script src="../../js/backend_clients.js"></script>
	<script src="../../js/notifications.js"></script>
</body>
</html>