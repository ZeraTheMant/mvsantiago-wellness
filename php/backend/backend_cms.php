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
	<title>Content Management System | Backend</title>
	<script>
        tinymce.init({
            selector: '#home-part',
			height : "150",
            theme: 'modern',
            plugins: 'textcolor',
            toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            toolbar2: 'print preview media | forecolor backcolor'
        });
    </script>
	<script>
        tinymce.init({
            selector: '#showcase-card-desc',
			height : "250"
        });
    </script>
	<script>
        tinymce.init({
            selector: '#facilities-card-desc',
			height : "250"
        });
    </script>
	<script>
        tinymce.init({
            selector: '#about-textarea',
			height : "250"
        });
    </script>
    <script>
        tinymce.init({
            selector: '#about-mission',
			height : "150"
        });
    </script>
    <script>
        tinymce.init({
            selector: '#about-vision',
			height : "150"
        });
    </script>
</head>
<body>
	<?php
		if(!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 'admin'){
			header("Location: ../../index.php?accessDenied");
		}
	?>
		<div class="backend-container">
		<?php echo $sidebar; ?> 
	
		<div class="backend-page-body">
			<?php echo $logo_holder; ?>
			
			<div class="backend-page-body-container">
				<div class="backend-body-header">
					<div><h3>Content Management System</h3></div>
				</div>
				<hr>
				
				<div id="cms-status-container" class="modal-input">					
					<select id="cms-status" name="cms-status">
						<option selected>Homepage</option>
						<option>About us page</option>
						<option>Facilities</option>
						<option>Contact Info</option>
						<option>Logo</option>
					</select>
				</div>
				
				<div id="cms-container">
					<div id="contact-section">
						<form id="contact-info">
							<label class="modal-label">Contact numbers:</label>
							<input class="modal-input" type="text" id="company-nums" required/><br>
							
							<label class="modal-label">Email address:</label>
							<input class="modal-input" type="email" id="company-email" required/><br>
							
							<label class="modal-label">Address:</label>
							<textarea class="modal-input" id="company-location" rows="7"></textarea>
							
							<label class="modal-label">Hours open:</label>
							<textarea class="modal-input" id="company-hours" rows="7"></textarea>
							
							<button type="submit" class="modal-btn" id="submit-company-form">Submit</button>
						</form>
					</div>
				
					<div id="logo-section">
						<form id="logo-form" method="post" enctype="multipart/form-data">
							<img src="../../img/question_mark.png" id="logo-img-holder"/>
							<input type="file" name="image" id="logo-img" accept="image/*"/>
							
							<div id="logo-btn-container">
								<label for="logo-img" class="logo-img-btn">Choose a photo</label><br>					
								<button type="submit" class="modal-btn" id="submit-logo-form">Submit</button>
								
							</div>
						</form>
					</div>
				
					<div id="showcase-section">
						<div id="showcase-form-container">
							<form id="showcase-form" method="post" enctype="multipart/form-data">
								<div id="img-container">
									<img src="../../img/question_mark.png" class="modal-preview-img"/>
									<input type="file" name="image" id="modal-img" accept="image/*"/>
									<div id="modal-img-options">
										<label for="modal-img" class="modal-img-btn">Choose a photo</label><br>
										<button class="modal-clear-img" id="img-clear-btn-from-backend-categories" type="button">Clear image</button>
									</div>
								</div>
								<label class="modal-label">Display order (1 is the first image displayed):</label>
								<input class="modal-input" type="number" id="showcase-card-display-order" min="1" required/><br>
								
								<label class="modal-label">Header text:</label>
								<input class="modal-input" type="text" id="showcase-card-header-text" required/><br>
								
								<label class="modal-label">Subheader text:</label>
								<input class="modal-input" type="text" id="showcase-card-subheader-text" required/><br>
								
								<label class="modal-label">Links to:</label>
								<input class="modal-input" type="text" id="showcase-card-link" required/><br>
								
								<label class="modal-label">Description:</label>						
								<textarea class="modal-input" id="showcase-card-desc" rows="20"></textarea>
								
								<button type="submit" class="modal-btn" id="submit-showcase-form">Submit image</button>
								<button type="button" class="modal-btn" id="delete-showcase-img" style="background: red; border: 0; color: white; padding: 10px; font-size: 1.1rem;">Delete image</button>
							</form>
						</div>
						<div>
							<div id="showcase-thumbs"></div>
							<div style="margin-top: 50px;">
							    <h2>Max Number of Sliders</h2>
							    <div class="modal-halved" style="width: 50%;">
									<div>
										<p>Enter slider limit:</p>
									</div>
									<div style="margin-top: 10px;">
										<input type="number" min="1" class="modal-input" id="max-slides"/>
									</div>
								</div>
								<h2>Homepage Info</h2>
								<div>
									<textarea class="modal-input" id="home-part" rows="10"></textarea>
								</div>
								<div class="modal-halved" style="margin-top: 10px; width: 50%;">
									<div>
										<p>Facebook link:</p>
										<p>Instagram link:</p>
									</div>
									<div style="margin-top: 10px;">
										<input type="text" class="modal-input" id="fb-link"/ style="margin-bottom: 5px;">
										<input type="text" class="modal-input" id="ig-link"/>
									</div>
								</div>
								<button type="button" class="modal-btn" id="update-home-info">Update homepage info</button>
							</div>
						</div>
					</div>
					
					<div id="about-section">
						<div>							
							<label class="modal-label">About section content:</label>
							<textarea class="modal-input" id="about-textarea" rows="20"></textarea><br>
							
							<label class="modal-label">Mission section content:</label>
							<textarea class="modal-input" id="about-mission" rows="10"></textarea><br>
							
							<label class="modal-label">Vision section content:</label>
							<textarea class="modal-input" id="about-vision" rows="10"></textarea><br>
							
							<div id="about-img-section">
								<h2>Image</h2>
								<form id="about-img-form" method="post" enctype="multipart/form-data">
									<img src="../../img/question_mark.png" id="about-img-holder"/>
									<input type="file" name="image" id="about-img" accept="image/*"/>
									
									<div id="about-img-btn-container">
										<label for="about-img" class="about-img-btn">Choose a photo</label><br>					
										<button type="submit" class="modal-btn" id="submit-about-img-form">Submit</button>
										
									</div>
								</form>
							</div>
							
							<button type="button" class="modal-btn" id="submit-about-page-info">Submit</button>
						</div>
					</div>
					
					<div id="facilities-section">
						<div id="showcase-form-container">
							<form id="facilities-form" method="post" enctype="multipart/form-data">
								<div id="img-container2">
									<img src="../../img/question_mark.png" class="modal-preview-img2"/>
									<input type="file" name="image" id="modal-img2" accept="image/*"/>
									<div id="modal-img-options">
										<label for="modal-img2" class="modal-img-btn2">Choose a photo</label><br>
										<button class="modal-clear-img2" id="img-clear-btn-from-backend-categories2" type="button">Clear image</button>
									</div>
								</div>
								
								<label class="modal-label">Display order (1 is the first image displayed):</label>
								<input class="modal-input" type="number" id="facilities-card-display-order" min="1" required/><br>
								
								<label class="modal-label">Header text:</label>
								<input class="modal-input" type="text" id="facilities-card-header-text" required/><br>
																
								<label class="modal-label">Description:</label>						
								<textarea class="modal-input" id="facilities-card-desc" rows="10" required></textarea>
								
								<button type="submit" class="modal-btn" id="submit-facilities-form">Submit</button>
								
								<button type="button" class="modal-btn" id="delete-facilities-img" style="background: red; border: 0; color: white; padding: 10px; font-size: 1.1rem;">Delete image</button>
							</form>
						</div>
						<div>
					    	<div id="facilities-thumbs"></div>
					    	<div style="margin-top: 50px;">
							    <h2>Max Number of Sliders</h2>
							    <div class="modal-halved" style="width: 50%;">
									<div>
										<p>Enter slider limit:</p>
									</div>
									<div style="margin-top: 10px;">
										<input type="number" min="1" class="modal-input" id="max-slides-facilities"/>
										<br>
										<button type="button" class="modal-btn" id="facilities-limit-btn" style="background: green; border: 0; color: white; padding: 5px">Save limit</button>
									</div>
								</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	
	<?php include '../../footer.php'; ?>
	<?php include '../../loader_popup.php'; ?>
	
	<div class="modal-popup">
		<div class="modal-content">
			<div class="modal-header">
				<span class="modal-header-text"></span>
				<div class="modal-close-btn"><span>&times;</span></div>
			</div>
			
			<div class="modal-body">

				

			</div>
	
		</div>
	</div>
	
	<select id="shit" style="display: none;"></select>
	
	<script>
		var linkViewer = "";
		const userID = '<?php echo $_SESSION['client_id']; ?>';
		document.querySelector("#dashboard-cms-link").style.fontWeight = "bold";
		document.querySelector("#dashboard-cms-link").style.color = "green";
		
	</script>
	<script src="../../js/loaderPopupControl.js"></script>
	<script src="../../js/getImgurDict.js"></script>
	<script src="../../js/shared_backend_js.js"></script>
	<script src="../../js/loadData.js"></script>
	<script src="../../js/modalControl.js"></script>
	<script src="../../js/notifications.js"></script>
	<script src="../../js/backend_cms.js"></script>
</body>
</html>