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
	<script>
        tinymce.init({
            selector: '#modal-description',
			height : "150"
        });
    </script>
	<title>Services Manager | Backend</title>
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
								<div><h3>Services Manager</h3></div>
							</div>
							<hr>
							
							<div class="backend-body-mid-menu">
								<button class="backend-body-add-btn modal-btn">&#10133; Add new service</button>
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
					<button>Service Information</button>
					<button>Images</button>
				</div>
				
				<div class="tab-panel" id="panel-1">
					<form class="modal-form" id="providers-modal-form" method="post" enctype="multipart/form-data">
						<p class="modal-instructions">Fields with an asterisk (<span class="required-asterisk">*</span>) are required.</p>
												
						<label class="modal-label" id="first-modal-element">ID: <span class="modal-selected-id"></span></label>
						
						<label class="modal-label">Category: <span class="required-asterisk">*</span></label>
						<select class="modal-input" id="modal-category">
							<option selected disabled>---Select category---</option>
						</select>
						
						<label class="modal-label">Name: <span class="required-asterisk">*</span></label>
						<input class="modal-input" type="text" id="modal-service-name"/>
											
						<div class="modal-halved">
							<div>
								<label class="modal-label">Duration hours: <span class="required-asterisk">*</span></label>
								<input class="modal-input" type="number" id="duration-hours" name="duration-hours" min="0" required/>
							</div>
							
							<div>
								<label class="modal-label">Duration minutes: <span class="required-asterisk">*</span></label>
								<select class="modal-input" id="duration-mins" name="duration-mins">
									<option>0</option>
									<option>15</option>
									<option>30</option>
									<option>45</option>
								</select>
							</div>
						</div>
						
						<div style="display: grid; grid-gap: 5px; grid-template-columns: 0.5fr 1fr;">
							<div>
								<label class="modal-label">Price: <span class="required-asterisk">*</span></label>
								<input class="modal-input" type="text" id="modal-service-price" name="modal-service-price" min="0" required/>

							</div>
							
							<div>
								<label class="modal-label">Available for first-time clients?: <span class="required-asterisk">*</span></label>	
								<select class="modal-input" id="is-service-available-for-first-time-patients">
									<option name="1">Yes</option>
									<option name="2">No</option>
								</select>
							</div>
						</div>
												
						<div id="modal-bio-container">
							<label class="modal-label">Description: <span class="required-asterisk">*</span></label>
							<textarea class="modal-input" id="modal-description" rows="7"></textarea>	
						</div>
					</form>
				</div>
				
				<div class="tab-panel" id="panel-2">
					<h3>Showcase Images</h3>
					<p id="instruction">Double-click the thumbnail of the image that you wish to edit.</p>
					<div id="modal-img-container">
									
						<div>
							<img src="../../img/question_mark.png" class="modal-preview-img"/>
							<input type="file" name="image" id="modal-img" accept="image/*"/>
							<div id="modal-img-options">
								<label for="modal-img" class="modal-img-btn">Choose a photo</label><br>
								<button class="modal-clear-img" id="img-clear-btn-from-backend-categories" type="button">Clear image</button>
							</div>
							
							<div id="display-order-div">
								<label class="modal-label">Display order (1 is the first image displayed) <span class="required-asterisk">*</span>:</label>
								<input type="number" class="modal-input" id="img-order" min="1" required/>
								<input type="text" id="si-id" style="display: none;"/>
								<div style="margin-top: 10px">
									<button type="button" class="modal-btn" id="upload-img">Save image</button>
									<button type="button" class="modal-btn" id="delete-img">Delete image</button>
								</div>
							</div>
						</div>
						
						<div id="services-thumbs">
							<table id="service-imgs-tbl">
								<thead>
									<tr>
										<th>Display Order</th>
										<th>Thumbnail</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<hr id="wew">
			<div class="modal-footer">
				<button type="submit" form="providers-modal-form" class="add-modal-btn modal-btn">Add new service</button>
				<button type="submit" form="providers-modal-form" class="update-modal-btn modal-btn">Update service info</button>
				<button type="button" class="delete-modal-btn modal-btn">Delete service</button>
			</div>
		</div>
	</div>
	
	<select id="shit" style="display: none;"></select>
	
	<script>
		var linkViewer = "";
		const userID = '<?php echo $_SESSION['client_id']; ?>';
		const user_id = '<?php echo $_SESSION['client_id']; ?>';
		document.querySelector("#dashboard-services-link").style.fontWeight = "bold";
		document.querySelector("#dashboard-services-link").style.color = "green";
	</script>
	<script src="../../js/loaderPopupControl.js"></script>
	<script src="../../js/getImgurDict.js"></script>
	<script src="../../js/shared_backend_js.js"></script>
	<script src="../../js/loadData.js"></script>
	<script src="../../js/modalControl.js"></script>
	<script src="../../js/get_provider_services.js"></script>
	<script src="../../js/backend_services.js"></script>
	<script src="../../js/notifications.js"></script>
</body>
</html>