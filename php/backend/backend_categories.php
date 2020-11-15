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
    </script>
	<title>Categories Manager | Backend</title>
	<script>
        tinymce.init({
            selector: '#modal-description',
			height : "150"
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
								<div><h3>Categories Manager</h3></div>
							</div>
							<hr>
							
							<div class="backend-body-mid-menu">
								<button class="backend-body-add-btn modal-btn">&#10133; Add new category</button>
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
							<th></th>
						</thead>
						<tbody id="category-services-table-body"></tbody>
					</table>
					<button type="button" class="modal-btn" id="add-category-service">Add service</button>
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
		var linkViewer = "";
		const userID = '<?php echo $_SESSION['client_id']; ?>';
		document.querySelector("#dashboard-categories-link").style.fontWeight = "bold";
		document.querySelector("#dashboard-categories-link").style.color = "green";
	</script>
	<script src="../../js/loaderPopupControl.js"></script>
	<script src="../../js/getImgurDict.js"></script>
	<script src="../../js/shared_backend_js.js"></script>
	<script src="../../js/loadData.js"></script>
	<script src="../../js/modalControl.js"></script>
	<script src="../../js/backend_categories.js"></script>
	<script src="../../js/notifications.js"></script>
</body>
</html>