<?php
	session_start();
	include "backend_sidebar_provider.php";
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
	<title>Users Manager | Backend</title>
	<script>
		const provider_id = '<?php if(!isset($_SESSION['provider_id'])){echo "";}else{echo $_SESSION['provider_id'];} ?>';
	</script>
</head>
<body>
	<?php
		if(!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 'provider'){
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
								<div><h3>My Clients</h3></div>
							</div>
							<hr>
							
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
								
								<label class="modal-label">Gender:</label>
								<select class="modal-input" id="modal-gender" disabled>
									<option selected disabled>---Select gender---</option>
									<option>Male</option>
									<option>Female</option>
								</select>
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
								
								<label class="modal-label">User level:</label>
								<select class="modal-input" id="modal-user-level" disabled>
									<option selected disabled>---Select user level---</option>
									<option>Admin</option>
									<option>Client</option>
									<option>Provider</option>
								</select>		
							</div>
						</div>
						
						<div id="modal-bio-container">
							<label class="modal-label">Address:</label>
							<textarea class="modal-input" id="modal-address" rows="7" disabled></textarea>
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
							<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
						</thead>
						<tbody id="individual-category-table-body"></tbody>
					</table>
					<button type="button" class="modal-btn" id="add-provider-service">Add service</button>
					
					<div class="centered-all">
						<button type="button" id="save-client-services-settings" class="modal-btn">Save settings</button>
					</div>
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
	
	<select id="shit" style="display: none;"></select>
	
	<script>
		var linkViewer = "";
		const userID = '<?php echo $_SESSION['client_id']; ?>';
		document.querySelector("#dashboard-users-link").style.fontWeight = "bold";
		document.querySelector("#dashboard-users-link").style.color = "green";
	</script>
	<script src="../../js/loaderPopupControl.js"></script>
	<script src="../../js/shared_backend_js.js"></script>
	<script src="../../js/loadData.js"></script>
	<script src="../../js/get_inividual_provider_services.js"></script>
	<script src="../../js/modalControl.js"></script>
	<script src="../../js/backend_provider_clients.js"></script>
	<script src="../../js/notifications.js"></script>
</body>
</html>