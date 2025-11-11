<?php
require('util/Connection.php');
require('util/SessionCheck.php');
require('Header.php');
?>
<script src="crypto-js/crypto-js.js"></script>
<script src="js/Encryption.js"></script>

<script>
	// -------------------------------
	// NEW FUNCTION: Password Policy
	// -------------------------------
	function passwordMeetsPolicy(password) {
		const minLength = 8;   // you can change as needed
		const maxLength = 128; // upper bound for security
		
		if (password.length < minLength) {
			echo("Password must be at least " + minLength + " characters long.");
			return false;
		}
		if (password.length > maxLength) {
			echo("Password must be at most " + maxLength + " characters long.");
			return false;
		}

		// Require mixed character sets: alpha, numeric, special, mixed case
		const hasLower = /[a-z]/.test(password);
		const hasUpper = /[A-Z]/.test(password);
		const hasNumber = /[0-9]/.test(password);
		const hasSpecial = /[!@#\$%\^&\*\(\)_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);

		if (!hasLower || !hasUpper || !hasNumber || !hasSpecial) {
			echo("Password must include uppercase, lowercase, number, and special character.");
			return false;
		}

		return true;
	}

	// Verify and encrypt (existing function, lightly updated)
	function verifyCaptcha() {
		var readableString = document.getElementById("password").value;
		
		// Apply password policy check first
		if (!passwordMeetsPolicy(readableString)) {
			return false;
		}

		var nonceValue = "nonce_value";
		let encryption = new Encryption();
		var encrypted = encryption.encrypt(readableString, nonceValue);
		document.getElementById("password").value = encrypted;
	}

</script>

<!-- START BREADCRUMB -->
<ul class="breadcrumb">
	<li><a href="#">Home</a></li>
	<li class="active">Login Data Add</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">

	<div class="row">
		<div class="col-md-12">

			<form action="api/LoginDataAdd.php" method="POST" class="form-horizontal" enctype="multipart/form-data">
				<div class="panel panel-default">
					<div class="panel-body">
						<p>Fill this form to add new user.</p>
					</div>

					<div class="panel-body">
						<div class="row">

							<div class="col-md-6">

								<div class="form-group">
									<label class="col-md-3 control-label">Username*</label>
									<div class="col-md-9">
										<div class="input-group">
											<span class="input-group-addon"><span class="fa fa-info"></span></span>
											<input type="text" class="form-control" id="newusername" name="newusername" required />
										</div>
										<span class="help-block">Unique Username</span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label">Role/District*</label>
									<div class="col-md-9">
										<div class="input-group">
											<span class="input-group-addon"><span class="fa fa-info"></span></span>
											<select class="form-control" id="district" name="district">
												<option value="admin">Admin</option>
											</select>
										</div>
										<span class="help-block">District Name/Admin Role</span>
									</div>
								</div>

							</div>

							<div class="col-md-6">

								<div class="form-group">
									<label class="col-md-3 control-label">Password*</label>
									<div class="col-md-9">
										<div class="input-group">
											<span class="input-group-addon"><span class="fa fa-info"></span></span>
											<input type="password" class="form-control" id="newpassword" name="newpassword" required />
										</div>
										<span class="help-block">Password</span>
									</div>
								</div>

							</div>

						</div>
					</div>

					<div class="panel-footer">
						<button class="btn btn-primary pull-right" onclick="showPopup()" type="button">Submit</button>
					</div>

					<div id="popup" class="popup">
						<a class="close" onclick="hidePopup()" style="font-size:25px">×</a>
						</br></br>

						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Username*</label>
								<div class="col-md-9">
									<div class="input-group">
										<span class="input-group-addon"><span class="fa fa-info"></span></span>
										<input type="text" class="form-control" id="username" name="username" required />
									</div>
									<span class="help-block">Username</span>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Password*</label>
								<div class="col-md-9">
									<div class="input-group">
										<span class="input-group-addon"><span class="fa fa-info"></span></span>
										<input type="password" class="form-control" id="password" name="password" required />
									</div>
									<span class="help-block">Password</span>
								</div>
							</div>
						</div>

						<center><button class="btn btn-primary" onclick="verifyCaptcha()">Verify</button></center>
					</div>
				</div>
			</form>

		</div>
	</div>
</div>

<!-- END PAGE CONTENT WRAPPER -->

<script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
<script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
<script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
<script type="text/javascript" src="js/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/tableexport/tableExport.js"></script>
<script type="text/javascript" src="js/plugins/tableexport/jquery.base64.js"></script>
<script type="text/javascript" src="js/plugins/tableexport/html2canvas.js"></script>
<script type="text/javascript" src="js/plugins/tableexport/jspdf/libs/sprintf.js"></script>
<script type="text/javascript" src="js/plugins/tableexport/jspdf/jspdf.js"></script>
<script type="text/javascript" src="js/plugins/tableexport/jspdf/libs/base64.js"></script>
<script type="text/javascript" src="js/plugins.js"></script>
<script type="text/javascript" src="js/actions.js"></script>
<script type="text/javascript" src="js/jquery.autocomplete.min.js"></script>
<?php require('DistrictAutocomplete.php'); ?>

<script>
	function showPopup() {
		var username = document.getElementById('newusername').value;
		var password = document.getElementById('newpassword').value;
		var district = document.getElementById('district').value;

		if (username === '' || password === '' || district === '') {
			alert('Please enter all fields');
			return false;
		}

		// Apply password policy before showing popup
		if (!passwordMeetsPolicy(password)) {
			return false;
		}

		document.getElementById('popup').style.display = 'block';
	}

	function hidePopup() {
		document.getElementById('popup').style.display = 'none';
	}
</script>

</body>
</html>
