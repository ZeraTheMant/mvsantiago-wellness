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
	<title>Testimonials Manager | Backend</title>
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
								<div><h3>Testimonials Manager</h3></div>
							</div>
							<hr>
							
							<div id="testimonial-status-container">					
								<select id="testimonial-status" name="testimonial-status" class="padded-select">
									<option selected>Approved testimonials</option>
									<option>Pending testimonials</option>
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
					<button>Testimonial Information</button>
				</div>
				
				<div class="tab-panel" id="panel-1">
					<form class="modal-form" id="providers-modal-form" method="post" enctype="multipart/form-data">
						
						<div id="modal-img-container">
							<img src="../../img/question_mark.png" class="modal-preview-img"/>
						</div>
						
						<div>
							<label class="modal-label" id="first-modal-element">ID: <span class="modal-selected-id"></span></label>
							
							<label class="modal-label">Sender name:</label>
							<input class="modal-input" type="text" id="modal-sender-name" disabled/>

							<label class="modal-label">Sender address:</label>
							<input class="modal-input" type="text" id="modal-sender-address" disabled/>
							
							<label class="modal-label">Sender age:</label>
							<input class="modal-input" type="number" id="modal-sender-age" disabled/>
							
							<label class="modal-label">Testimonial:</label>
							<textarea class="modal-input" id="modal-testimonial" rows="7" disabled></textarea>	
						</div>
					</form>
				</div>
			</div>
			<hr id="wew">
			<div class="modal-footer">
				<button type="button" form="providers-modal-form" class="approve-testi-btn modal-btn">Approve</button>
				<button type="button" form="providers-modal-form" class="hide-testi-btn modal-btn">Return to pending</button>
				<button type="button" class="delete-testi-btn modal-btn">Delete</button>
			</div>
		</div>
	</div>
	
	<select id="shit" style="display: none;"></select>
	
	<script>
		var linkViewer = "";
		const userID = '<?php echo $_SESSION['client_id']; ?>';
		document.querySelector("#dashboard-testimonials-link").style.fontWeight = "bold";
		document.querySelector("#dashboard-testimonials-link").style.color = "green";
	</script>
	<script src="../../js/loaderPopupControl.js"></script>
	<script src="../../js/loadData.js"></script>
	<script src="../../js/modalControl.js"></script>
	<script src="../../js/backend_testimonials.js"></script>
	<script src="../../js/notifications.js"></script>
</body>
</html>