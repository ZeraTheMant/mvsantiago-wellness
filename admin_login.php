
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta http-equiv="X-UA-Compatible" content="ie=edge"/>
	<link rel="stylesheet" type="text/css" href="css/loader_display.css"/>
	<script src='js/jquery.min.js'></script>
	<title>Admin Login</title>
	<style>
		body{
			background-color: #98fb98;
			margin: 0;
			display: flex;
			align-items: center;
			width: 100%;
			height: 100vh;
			justify-content: center;
			font-family: arial;
		}
		
		#login-form-container{
			background: white;
			padding: 40px;
			position: relative;
		}
		
		label, input, button{
			display: block;
			width: 100%;
			margin: auto;
			box-sizing: border-box;
			margin-bottom: 5px;
		}
		
		input{
			border: 0;
			outline: 0;
			background: transparent;
			border-bottom: 1px solid black;
			padding: 10px 0;
		}
		
		h2{
			text-align: center;
		}
		
		label{
			font-weight: bolder;
		}
		
		#login-btn{
			background-color: green;
			border: 0;
			color: white;
			margin-bottom: 0;
			margin-top: 30px;
			font-size: 1.1rem;
			padding: 10px 20px;
			border-radius: 30px;
			cursor: pointer;
		}
		
		#img-div{
			height: 100px;
			width: 90px;
			border-radius: 60%;
			position: absolute;
			top: -50px;
			background-color: green;
		}
		
		img{
			height: 100px;
			width: 90px;
			border-radius: 60%;
			background-color: green;
		}
	</style>
</head>
<body>
	<div id="login-form-container">
		<form id="login-form">
			<br>
			<h2>M.V. Santiago Wellness Center</h2>
			<h2>Admin Panel</h2>
			<br>
			<label>E-mail</label>
			<input type="email" id="admin-email" placeholder="Enter E-mail" required/>
			<br>
			<label>Password</label>
			<input type="password" id="admin-pword" placeholder="Enter Password" required/>
			
			<button type="submit" id="login-btn">Sign in</button>
		</form>
		<div id="img-div"><img src="img/admin.png"/></div>
	</div>
	
	<?php include 'loader_popup.php'; ?>
	
	<script src="js/loaderPopupControl.js"></script>
	<script>
		function loginFormFilledUp(){
			return document.querySelector("#admin-pword").value !== "" && document.querySelector("#admin-email").value !== "";
		}
		
		function loginAdmin(){
			loaderPopupControl(true);
			$.ajax({
				url: "php/backend/login_official.php",
				method: "POST",
				data: {
					email: document.querySelector("#admin-email").value, 
					password: document.querySelector("#admin-pword").value,
					initial_query: "SELECT * FROM clients WHERE email = :email AND user_level = 'admin'"
				},
				success: function(result){
					if(result === 'credentials'){
						alert("Login failed. Invalid credentials given. Please try again.");
						loaderPopupControl(false);
					}
					else if(result === 'email'){
						loaderPopupControl(false);
						alert("Your email address is not yet verified. Please verify it first before you can log-in.");
					}
					else{
						alert("Login success. Welcome, " + result + '.');
						window.location = 'php/backend/backend_admin_dashboard.php';
					}
				},
				error: function(result){
					alert("Login failed. Invalid credentials given. Please try again.");
					loaderPopupControl(false);
				}
			});
		}
	
		function logInAdminPrep(e){
			e.preventDefault();
			if(loginFormFilledUp()){
				loginAdmin();
			}
			else{
				alert("Please fill up the login form.");
			}
		}
	
		var container = window.getComputedStyle(document.querySelector('#login-form-container'));
		var imgDiv = document.querySelector('#img-div');
		var containerWidth = parseInt(container.getPropertyValue("width").slice(0, -2) / 2);

		imgDiv.style.left = containerWidth.toString() + "px";
		
		document.querySelector('#login-form').addEventListener('submit', logInAdminPrep);
	</script>
</body>
</html>