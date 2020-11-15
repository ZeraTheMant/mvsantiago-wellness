<div class="modal-popup">
	<div class="modal-content">
		<div class="modal-header">
			<span class="modal-header-text"></span>
			<div class="modal-close-btn"><span>&times;</span></div>
		</div>
		
		<div class="modal-body">
			<div id="login-body">
				<div class="relative-login-box">
					<div class="relative-inner-box"><span>&#9993;</span></div>
					<input class="login-inner-box" placeholder="Email" type="email" id="login-email" required/>
				</div>
				
				<div class="relative-login-box">
					<div class="relative-inner-box"><span class="icon-lock1"></span></div>
					<input class="login-inner-box" placeholder="Password" type="password" id="login-password" required/>
				</div>
				
				<label id="login-btn"><span class="icon-arrow-right"></span>&nbsp;Login</label>
				<form class="modal-form" style="display:none;"></form>
				
				<div class="switch-views">
					<span class="view-switch-msg">Don't have an account yet? <span id="to-register">Register</span> now to be able to book appointments and more.</span>
				</div>
			</div>
			
			<div id="register-body">
				
				<div class="halved-in-three">
					<div class="relative-login-box">
						<div class="relative-inner-box"><span class="icon-user"></span></div>
						<input class="login-inner-box" placeholder="First name*" type="text" id="register-fname" required/>
					</div>
					
					<div class="relative-login-box">
						<div class="relative-inner-box"><span class="icon-user"></span></div>
						<input class="login-inner-box" placeholder="Middle name*" type="text" id="register-mi" required/>
					</div>
					
					<div class="relative-login-box">
						<div class="relative-inner-box"><span class="icon-user"></span></div>
						<input class="login-inner-box" placeholder="Last name*" type="text" id="register-lname" required/>
					</div>
				</div>
				
				<div id="birth-date">
					<div class="relative-login-box wew">
						<div class="relative-inner-box"><span class="icon-calendar"></span></div>
						<select id="month-box">
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
					
					<div class="relative-login-box wew">
						<div class="relative-inner-box"><span class="icon-calendar"></span></div>
						<select class="other-boxes" id="day-box">
						</select>
					</div>
					
					<div class="relative-login-box wew">
						<div class="relative-inner-box"><span class="icon-calendar"></span></div>
						<select class="other-boxes" id="year-box">
							<option selected disabled>Year*</option>
						</select>
					</div>
				</div>
				
				<div class="halved-in-three">
					<div class="relative-login-box wew">
						<div class="relative-inner-box"><span class="icon-intersex"></span></div>
						<select class="other-boxes" id="gender-box">
							<option selected disabled>Gender*</option>
							<option>Male</option>
							<option>Female</option>
						</select>
					</div>
					
					<div class="relative-login-box">
						<div class="relative-inner-box"><span>&#9993;</span></div>
						<input class="login-inner-box" placeholder="Email address*" type="email" id="register-email" required/>
					</div>
					
					<div class="relative-login-box">
						<div class="relative-inner-box"><span>&#9742;</span></div>
						<input class="login-inner-box" placeholder="Contact no.*" type="text" pattern="\d+$" id="register-contact" required/>
					</div>
				</div>
				
				<div class="relative-login-box">
					<div class="relative-inner-box"><span class="icon-map"></span></div>
					<input class="login-inner-box" placeholder="Address.*" type="text" id="register-address" required/>
				</div>
				
				<div id="health-conditions-container">
					<p>Please check if you have any of the following:</p>
					<br>
					<p><input type="checkbox" id="heart-cond" name="conditions" value="Heart condition"/>Heart condition</p>
					<p><input type="checkbox" id="skin-cond" name="conditions" value="Heart condition"/>Skin condition</p>
					<p><input type="checkbox" id="allergy" name="conditions" value="Heart condition"/>Allergies</p>
				</div>
				
				<label id="register-btn"><span class="icon-register"></span>&nbsp;Register</label>
				
				<div class="switch-views">
					<span class="view-switch-msg">Already have an account? <span id="to-login">Sign-in</span>.</span>
				</div>
			</div>
		</div>
	</div>
</div>