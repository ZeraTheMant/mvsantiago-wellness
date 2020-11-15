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
		<link rel="stylesheet" type="text/css" href="../../css/backend_messages.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/loader_display.css"/>
	<link rel='stylesheet' href='../../css/icons.css'/>
	<link rel="stylesheet" type="text/css" href="../../css/all_icons.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/index_stylesheet.css"/>
	<link rel='stylesheet' href='../../css/real_backend_css.css'/>
	<link rel="stylesheet" type="text/css" href="../../css/shared_backend_css.css"/>
	<script src='../../js/jquery.min.js'></script>
	<script src='../../js/moment.min.js'></script>
	<title>Messages Manager | Backend</title>
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
								<div><h3>Message Inbox</h3></div>
							</div>
							
							<hr>
							
							<div id="inbox-status-container">					
								<select id="inbox-status" name="inbox-status" class="padded-select">
									<option selected>Unread messages</option>
									<option>Read messages</option>
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
	
	<div id="emailPopup">
		<div id="reply-modal-content">
			<div class="modal-header">
				<span class="popupHeaderText">Reply to message</span>
				<div class="popupCloseBtn"><span>&times;</span></div>
			</div>
			<div class="popupForm">
				<form id="emailForm">
					<label class="block-label">To:</label>
					<input type="email" class="block-input" id="msg-to" readonly/>
					<label class="block-label">Message ID:</label>
					<input type="text" class="block-input" id="msg-id" readonly/>
					<label class="block-label">Message:</label>
					<textarea class="block-input" rows="7" id="msg-msg"></textarea>
					<button type="submit" id="submit-reply-btn">Send reply</button>
				</form>
			</div>
		</div>
	</div>
	
	<div class="modal-popup">
		<div class="modal-content">
			<div class="modal-header">
				<span class="modal-header-text"></span>
				<div class="modal-close-btn"><span>&times;</span></div>
			</div>
			
			<div class="modal-body">
				
				<div id="backend-messages-popup-body">
					<div>
						<form class="modal-form">
							<fieldset>
								<legend>Sender info</legend>
								<p>
									<label class="modal-label" id="first-modal-element">ID: <span class="modal-selected-id"></span></label>
								</p>
								<div class="modal-halved">
									<div>
										<p>
											<span class="modal-label">Sender:</span> <span id="message-sender"></span>
										</p>
										<p>
											<span class="modal-label">Email:</span> <span id="message-email"></span>
										</p>
									</div>
									<div>
										<p>
											<span class="modal-label">Date sent:</span> <span id="date-sent"></span>
										</p>
										<p>
											<span class="modal-label">Contact:</span> <span id="message-contact"></span>
										</p>
									</div>
								</div>
							</fieldset>
								
							<fieldset>
								<legend>Contents</legend>
								<textarea class="modal-input" rows="5" id="message" readonly></textarea>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			<hr id="wew">
			<div class="modal-footer">
				<button type="button" form="providers-modal-form" class="mark-as-read-btn modal-btn">Mark as read</button>
				<button type="button" form="providers-modal-form" class="reply-btn modal-btn">Reply</button>
				<button type="button" class="delete-msg-btn modal-btn">Delete</button>
			</div>
		</div>
	</div>
	
	<select id="shit" style="display: none;"></select>
	
	<script>
		var linkViewer = "";
		const userID = '<?php echo $_SESSION['client_id']; ?>';
		document.querySelector("#dashboard-messages-link").style.fontWeight = "bold";
		document.querySelector("#dashboard-messages-link").style.color = "green";
	</script>
	<script src="../../js/loaderPopupControl.js"></script>
	<script src="../../js/loadData.js"></script>
	<script src="../../js/modalControl.js"></script>
	<script src="../../js/backend_messages.js"></script>
	<script src="../../js/notifications.js"></script>
</body>
</html>