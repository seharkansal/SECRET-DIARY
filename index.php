<?php
session_start();

if(array_key_exists('email',$_POST) OR array_key_exists('password',$_POST)){
	
	$link=mysqli_connect("localhost","sehar","users12345","testdb");
if(mysqli_connect_error()){
	die("not connected");
}

	if($_POST['email']==''){
		echo "email address is required";
	}
	else if($_POST['password']==''){
		echo "password is required";
	}
	else{
		$query="SELECT 'id' FROM users WHERE email='".mysqli_real_escape_string($link,$_POST['email'])."'";
		$result=mysqli_query($link,$query);
		if(mysqli_num_rows($result)>0){
			echo "email already taken";
		}
		else{
			$query="INSERT INTO users(email,password) VALUES('".mysqli_real_escape_string($link,$_POST['email'])."', '".mysqli_real_escape_string($link,$_POST['password'])."')";
			if(mysqli_query($link,$query)){
      $_SESSION['email']=$_POST['email'];
	  header("location:session.php");
			}
			else{
				echo "not registred";
			}
	}
}
}


		
		


?>

<form method="post">
<input type="text" name="email" placeholder="email address">
<input type="password" name="password" placeholder="password">
<input type="submit" value="sign up">
</form>