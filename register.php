<?php include('dbConfig.php') ?>
<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	<link rel="stylesheet" type="text/css" href="css/style1.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<div class="container">
		<div class="img">
		</div>
		<div class="login-content">

		<form method="post" action="register.php">
			<?php include('errors.php'); ?>
				<img src="img/avatar.svg">
				<h2 class="title">Welcome</h2>
           		<div class="input-div one">
           		   <div class="i">
           		   		<i class="fas fa-user"></i>
           		   </div>
           		   <div class="div">
           		   		<h5>Username</h5>
           		   		<input type="text" name="username" class="input" value="<?php echo $username; ?>">
           		   </div>
           		</div>
				<div class="input-div email">
					<div class="i">
					   <i class="fas fa-envelope"></i>
					</div>
					<div class="div">
					   <h5>Email</h5>
					   <input type="email" name="email" class="input" value="<?php echo $email; ?>">
					</div>
				</div>
           		<div class="input-div pass">
           		   <div class="i"> 
           		    	<i class="fas fa-lock"></i>
           		   </div>
           		   <div class="div">
           		    	<h5>Password</h5>
           		    	<input type="password" name="password_1" class="input">
            	   </div>
            	</div>
				<div class="input-div pass">
           		   <div class="i"> 
           		    	<i class="fas fa-lock"></i>
           		   </div>
           		   <div class="div">
           		    	<h5>Confirm password</h5>
           		    	<input type="password" name="password_2" class="input">
            	   </div>
            	</div>
				<p>
  					<a href="login.php">Sign in</a>
  				</p>
                <button type="submit" class="btn" name="reg_user">Register</button>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="js/main.js"></script>
</body>
</html>
