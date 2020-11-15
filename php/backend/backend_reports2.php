<?php
	session_start();
	
	if($_SESSION['user_level'] == 'client'){
		include "backend_sidebar_client.php";
	}
	else{
		include "backend_sidebar_provider.php";
	}
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
	<title>Reports | Backend</title>
	<style>
		.backend-body-content .actual-row td{
			padding: 5px;
		}
	</style>
</head>
<body>
	<?php
		if(!isset($_SESSION['user_level']) || ($_SESSION['user_level'] != 'client' && $_SESSION['user_level'] != 'provider')){
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
								<div><h3>Reports Manager</h3></div>
							</div>
							<hr>
						
							<div>
								<select id="reports-filter">
									<option selected>All reports</option>
									<option>Pending appointments</option>
									<option>Confirmed appointments</option>
									<option>Finished appointments</option>
									<option>Cancelled appointments</option>
								</select>
								
								<label>From:</label>
								<input type="date" id="from-date"/>

								<label>To:</label>
								<input type="date" id="to-date"/>
								
								<button type="button" class="modal-btn" id="search-btn">Search</button>
							</div>
							
							<div class="backend-body-content"></div>
							
							<div id="reports-btn">
								<button type="button" id="prev-reports" class="modal-btn">&#x23ea; Previous 10</button>
								<button type="button" id="next-reports" class="modal-btn">Next 10 &#x23e9;</button>
							</div>
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
					<button>Category Information</button>
					<button>Services</button>
				</div>
				
				<div class="tab-panel" id="panel-1">
					<form class="modal-form" id="providers-modal-form" method="post" enctype="multipart/form-data">
						<p class="modal-instructions">Fields with an asterisk (<span class="required-asterisk">*</span>) are required.</p>
						
						<div id="modal-img-container">
							<img src="../../img/question_mark.png" class="modal-preview-img"/>
							<input type="file" name="image" id="modal-img" accept="image/*"/>
							<div id="modal-img-options">
								<label for="modal-img" class="modal-img-btn">Choose a photo</label><br>
								<button class="modal-clear-img" id="img-clear-btn-from-backend-categories" type="button">Clear image</button>
							</div>
						</div>
						
						<div>
							<label class="modal-label" id="first-modal-element">ID: <span class="modal-selected-id"></span></label>
							
							<label class="modal-label">Name: <span class="required-asterisk">*</span></label>
							<input class="modal-input" type="text" id="modal-category-name"/>

							<label class="modal-label">Description: <span class="required-asterisk">*</span></label>
							<textarea class="modal-input" id="modal-description" rows="7"></textarea>	
						</div>
					</form>
				</div>
				
				<div class="tab-panel" id="panel-2">
					<h2>Services</h2>
					<p>Lists all the services under this category.</p>
					<table id="category-services-table">
						<thead>
							<th>Service Name</th>
						</thead>
						<tbody id="category-services-table-body"></tbody>
					</table>

				</div>
			</div>
			<hr id="wew">
			<div class="modal-footer">
				<button type="submit" form="providers-modal-form" class="add-modal-btn modal-btn">Add new category</button>
				<button type="submit" form="providers-modal-form" class="update-modal-btn modal-btn">Update category info</button>
				<button type="button" class="delete-modal-btn modal-btn">Delete category</button>
			</div>
		</div>
	</div>
	
	<select id="shit" style="display: none;"></select>
	
	<script>
		var user_level = '<?php if(!isset($_SESSION['user_level'])){echo "";}else{echo $_SESSION['user_level'];} ?>';
		var provID = '<?php if(!isset($_SESSION['provider_id'])){echo "";}else{echo $_SESSION['provider_id'];} ?>';
		var linkViewer = "";
		const userID = '<?php echo $_SESSION['client_id']; ?>';
		document.querySelector("#dashboard-reports-link").style.fontWeight = "bold";
		document.querySelector("#dashboard-reports-link").style.color = "green";

	</script>
	<script src="../../js/loaderPopupControl.js"></script>
	<script src="../../js/getImgurDict.js"></script>
	<script src="../../js/shared_backend_js.js"></script>
	<script src="../../js/loadData.js"></script>
	<script src="../../js/modalControl.js"></script>
	<script src="../../js/backend_reports.js"></script>
	<script src="../../js/notifications.js"></script>
</body>
</html>