<?php
require_once'qIconnection.php';
session_start();
?> 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <link rel="stylesheet" href="css/w3.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <title>Gadgets People | Login</title>
        <link rel="stylesheet" type="text/css" href="css/login.css">
        <link rel="stylesheet" href="css/font-awesome.min.css" >
		<style>
			@font-face{
				font-family: Droid Sans;
				src: url("../fonts/DroidSans-webfont.woff");
			}
			*{
				margin: 0;
				padding: 0;
			}
			body {
				font-family: 'Droid Sans', sans-serif;
			}
		</style>
    </head>
    <body>
<?php
		
$fname_err = $lname_err = $email_err = $ans_err = "";

        // Register user
        if (isset($_POST["sign-up"]))
        {
            $fname = $lname = "";
			$quest = $_POST['question'];
            $ans = $_REQUEST["answer"];
			$psk = $_POST["pass"];
			//$crypto_pwd = password_hash($_POST["pass"], PASSWORD_DEFAULT);
			
            // Validate firstname
            $input_name = trim($_POST["firstname"]);
            if(empty($input_name)){
                $name_err = "Please enter a name.";
            } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
                $name_err = "Please enter a valid name.";
            } else{
                $fname = $input_name;
            }

            // Validate Lastname
            $input_name = trim($_POST["lastname"]);
            if(empty($input_name)){
                $name_err = "Please enter a name.";
            } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
                $name_err = "Please enter a valid name.";
            } else{
                $lname = $input_name;
            }
    
            // Validate email
            $input_email = trim($_POST["email"]);
            if(empty($input_email)){
                $email_err = "Please enter your email.";
            } elseif(!filter_var($input_email, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9.@_-]+$/")))){
                $email_err = "Please enter a valid email.";
            } else{
                $email = $input_email;
            }
			$reg_date = date("d-M-Y");
                // Check for error before inserting into database
                if(empty($fname_err) && empty($lname_err) && empty($email_err) && empty($ans_err)){
    
                    $sql = "INSERT INTO RegUsers (FirstName, LastName, Usrname, PWord, SecQuest, Ans, DateReg)
                    VALUES ('{$fname}','{$lname}','{$email}','{$psk}','{$quest}','{$ans}','{$reg_date}')";
    
                    $result = mysqli_query($conn, $sql);
    
                    if ($result == TRUE)
                    {
                        print("<script>alert('Thank You! {$fname} {$lname}, Your Registration was Successful')</script>");
                    }
                    else
                    {
                        print("<script>alert('This Email ({$email}) already exists')</script>");
                    }
                    
                }
                else {
                    print("<script>alert('Input Error')</script>");
                }
    
        }
		
		
if(isset($_POST["sign-in"])){
	$username = $_POST["user-name"];
	$password = $_POST["pass-word"];
	$log_sql = "SELECT * FROM RegUsers WHERE Usrname='".$username."' AND PWord='".$password."'";
	$retval = mysqli_query($conn, $log_sql);
	if (mysqli_num_rows($retval) > 0){
			while($row = mysqli_fetch_assoc($retval)){
			$_SESSION['fname'] = $row['FirstName'];
			$_SESSION['lname'] = $row['LastName'];
				
			}
				print("<script>location.href='dashboard.php'</script>");
	}
	else{
		 print("<script>alert('Invalid username or password')</script>");
		}
}

#Change Password
if (isset($_REQUEST["change-pwd"]))
{
	$user_id = $_REQUEST["user-id"];
	$new_password = $_REQUEST["new-password"];
	$re_password = $_REQUEST["confirm-password"];
	$_user = $_REQUEST["user-name"];
	
	if ($new_password == $re_password)
	{
		$query = "UPDATE RegUsers SET PWord='".$new_password."' WHERE Id=".$user_id;
		$result = mysqli_query($conn, $query);
		if ($result == TRUE)
		{
			printf("<script>alert('Password Updated for %s')</script>", $_user);
		}
	}
	else
		print("<script>alert('Passwords does not match');</script>");
}

#Forget passoword
if (isset($_REQUEST["validate"]))
{
	$username = $_REQUEST["usrname"];
	$question = $_REQUEST["question"];
	$answer = $_REQUEST["answer"];
	
	$query = "SELECT * FROM RegUsers WHERE Usrname='{$username}' AND SecQuest='{$question}' AND Ans='{$answer}'";
	$result = $conn->query($query);
	if (mysqli_num_rows($result) > 0)
	{
		while($data = mysqli_fetch_assoc($result))
		{
			print("
		<div class='w3-card-4 w3-animate-bottom w3-modal-content w3-margin-top w3-center' id='reset'>
			<span class='w3-closebtn w3-xlarge w3-text-white' id='close'>&times;</span>
			<header class='w3-teal w3-padding-16 w3-center w3-xlarge text-uppercase' style='letter-spacing: 5px'>
				<i class='fa fa-mobile'></i> Gadgets People
			</header>
			<h4 class='w3-center'><b>Change Password</b></h4>
			<div class='w3-container'>
				<form action='index.php' method='post' role='form'>
					<div class='form-group input-group'>
						<span class='input-group-addon'><i class='fa fa-key'></i></span>
						<input type='password' name='new-password' class='form-control' placeholder='New Password' required />
					</div>
					<div class='form-group input-group'>
						<span class='input-group-addon'><i class='fa fa-key'></i></span>
						<input type='password' name='confirm-password' class='form-control' placeholder='Confirm Password' required />
					</div>
					<div class='w3-margin'>
						<input type='submit' name='change-pwd' class='w3-btn w3-center w3-green w3-large' value='Change Password' />
						<input type='hidden' name='user-id' value='".$data["Id"]."' />
						<input type='hidden' name='user-name' value='".$data["FirstName"]." ".$data["LastName"]."' />
					</div>
				</form>
			</div>
			<footer class='w3-small w3-center w3-black w3-padding-8'>
				&copy; 2019, Gadgets People
			</footer
		</div>
		<script>
			{
				document.getElementById('close').addEventListener('click',function(){
					document.getElementById('reset').style.display='none';
					location.href='index.php';
				});
				
			}
		</script>
		");exit();
		}
		
	}
	else
	{
		print("<script>alert('Validation failed')</script>");
	}
}
?>
        <div class="login w3-card-4">
            <header class="w3-teal w3-center w3-padding-32"><i class="fa fa-mobile"></i> Gadgets People</header>
            <form action="index.php" method="post">
                <div class="w3-margin">
                    <div class="form-group input-group">
                        <span class="input-group-addon w3-large"><i class="fa fa-user"></i></span>
                        <input type="text" name="user-name" class="form-control" placeholder="Username" required />
                    </div>
                    <div class="form-group input-group">
                        <span class="input-group-addon w3-large"><i class="fa fa-key"></i></span>
                        <input type="password" name="pass-word" class="form-control" placeholder="******" required >
                    </div>
                    <div class="form-group">
                        <input type="submit" name="sign-in" value="LOGIN" class="w3-green w3-large w3-btn-block" >
                    </div>
                </div><div class="clearfix"></div>
            </form>
			<div class="checkbox">
				<label>
					<a href="javascript:void(0)" onclick="document.getElementById('sign-up').style.display = 'block';"><i class="fa fa-user-plus"></i> New User</a>
				</label>
				<label class="w3-right">
					<a href="javascript:void(0)" onclick="document.getElementById('forgot').style.display = 'block';"><i class="fa fa-lock"></i> Forgot password</a>
				</label>
			</div>
			
        <!-- Add new user Admin -->
        <div class="w3-modal" id="sign-up">
            <div class="w3-modal-content w3-animate-fading w3-card-8 w3-animate-bottom">
                    <span class="w3-closebtn w3-xlarge" onclick="document.getElementById('sign-up').style.display = 'none';">&times</span>
                        <header class="w3-teal w3-large w3-padding-8 w3-center">New User</header>
                <div class="w3-container">
                    <div class="panel-body">
                    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" role="form">
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label class="w3-label">First Name: </label>
                                <input type="text" name="firstname" class="form-control text-capitalize" placeholder="John" />
                                <span class="w3-text-red"><?= $fname_err ?></span> 
                            </div>
                            <div class="col-xs-6">
                                <label class="w3-label">Last Name: </label>
                                <input type="text" name="lastname" class="form-control text-capitalize" placeholder="Doe" />
                                <span class="w3-text-red"><?= $lname_err ?></span> 
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-xs-6">
                                <label class="w3-label">Username: </label>
                                <input type="text" name="email" class="form-control" placeholder="johndoe43" required />
                                <span class="w3-text-red"><?= $email_err ?></span> 
                            </div>
                            <div class="col-xs-6">
                                <label>Password: </label>
                                <input type="password" name="pass" class="form-control" placeholder="******" required />
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-xs-6">
                                <label class="w3-label">Security Question:</label>
                                <select name="question" class="form-control">
                                    <option selected disabled>[Select Question]</option>
                                    <option>Where did you meet your spouce?</option>
                                    <option>What is the name of your favourite Uncle?</option>
                                    <option>What is your favourite color?</option>
                                    <option>What is your Mothers Maiden name?</option>
                                    <option>At What year did you buy your first car?</option>
                                    <option>Who is your favourite music Artist?</option>
                                </select>
                            </div>
                            <div class="col-xs-6">
                                <label class="w3-label">Answer</label>
                                <input type="text" name="answer" class="form-control" required />
                            </div>
                            <div class="clearfix"></div>
                            <button name="sign-up" type="submit" class="w3-btn-block w3-green w3-large w3-round">Sign Up</button>
                        
                            </form>
                            </div>
                    </div>
                </div>
                    <footer class='w3-small w3-center w3-teal w3-padding-8'>
                        &copy; 2019, Gadgets People
                    </footer>
            </div>
        </div>
        <!-- //new user admin -->
		
		
        <!-- Forget password -->
        <div class="w3-modal" id="forgot">
            <div class="w3-modal-content w3-animate-fading w3-card-8 w3-animate-bottom">
                    <span class="w3-closebtn w3-xlarge" onclick="document.getElementById('forgot').style.display = 'none';">&times</span>
                        <header class="w3-teal w3-large w3-padding-8 w3-center">Reset Password</header>
                <div class="w3-container">
                <form method="post" action="<?=htmlspecialchars($_SERVER["PHP_SELF"]) ?>" role="form">
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label class="w3-label">Username: </label>
                                <input type="text" name="usrname" class="form-control" placeholder="johndoe43" required />
                            </div>
                            <div class="form-group col-xs-6">
                                <label class="w3-label">Security Question:</label>
                                <select name="question" class="form-control">
                                    <option selected disabled>[Select Question]</option>
                                    <option>Where did you meet your spouce?</option>
                                    <option>What is the name of your favourite Uncle?</option>
                                    <option>What is your favourite color?</option>
                                    <option>What is your Mothers Maiden name?</option>
                                    <option>At What year did you buy your first car?</option>
                                    <option>Who is your favourite music Artist?</option>
                                </select>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-xs-12">
                                <label class="w3-label">Answer:</label>
                                <input type="text" name="answer" class="form-control">
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label></label> <button name="validate" type="submit" class="w3-btn-block w3-large w3-blue w3-round">Validate</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                    <footer class='w3-small w3-center w3-black w3-padding-8'>
                        &copy; 2019, Gadgets People
                    </footer>
            </div>
        </div>
        <!-- //forget password -->
        <footer class="text-center copy w3-black w3-padding-8">
            <small>&copy; 2019 All rights Reserved</small>
        </footer>
        </div>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>