<?php
session_start();
$error="";
if(array_key_exists("logout",$_GET)){
	unset($_SESSION);
	setcookie('id',"",time()-60*60);
	
	
}
else if(array_key_exists("id",$_SESSION) OR array_key_exists("id",$_COOKIE)){
	header("location:loggedInPage.php");
}




if(array_key_exists("submit",$_POST)){
	$link=mysqli_connect("localhost","secretDiary","SEH1510@tu","testdb");
	if(mysqli_connect_error()){
		die("not connected");
	}
	
	
if(!$_POST['email']){
	$error.="email address is required<br>";
}
	if(!$_POST['password']){
	$error.="password is required<br>";
}
	if($error!=""){
		$error="there were error(s):<br>".$error;
	}
	else{
		if($_POST['signUp']==1){
		$query="SELECT 'id' FROM anmol WHERE email='".mysqli_real_escape_string($link,$_POST['email'])."'";
		$result=mysqli_query($link,$query);
		if(mysqli_num_rows($result)>0){
			$error="email already taken";
		}
		
		else{
		$query="INSERT INTO anmol(email,password) VALUES('".mysqli_real_escape_string($link,$_POST['email'])."', '".mysqli_real_escape_string($link,$_POST['password'])."')";
			if(!mysqli_query($link,$query)){
     $error="could not sign in";
			}
			else{
				$query="UPDATE anmol SET password='".md5(md5(mysqli_insert_id($link)).($_POST['password']))."' WHERE id=".mysqli_insert_id($link)." LIMIT 1";
				mysqli_query($link,$query);
				$_SESSION['id']=mysqli_insert_id($link);
				if($_POST['stayLoggedIn']=='1'){
					setcookie("id",mysqli_insert_id($link),time()+60*60*24*365);
			}
				header("location:loggedInPage.php");
			}
		}
			
	}
	else{
		$query="SELECT*FROM anmol WHERE email='".mysqli_real_escape_string($link,$_POST['email'])."'";
	        $result=mysqli_query($link,$query);
	$row=mysqli_fetch_array($result);
	if(isSet($row)){
		$hashedPassword=md5(md5($row['id']).$_POST['password']);
		if($hashedPassword==$row['password']){
			$_SESSION['id']=$row['id'];
			if($_POST['stayLoggedIn']=='1'){
					setcookie("id",$row['id'],time()+60*60*24*365);
			}
				header("location:loggedInPage.php");
			}
			else{
			$error="that email/combination could not be found";
			}
	}
	else{
	$error="that email/combination could not be found";
	
	}
	
	
	}
	
}
}
?>

<?php include("header.php");?>

  <div class="container" id="homePageContainer">
  <h1>secret diary</h1>
  <p><strong>share your thoughts permanently and securely</strong></p>
  
  <div id="error"><?php echo $error; ?></div>
<form method="post" id="signUpForm">
<p>interested? sign up now</p>

<div class="form-group">
   <input type="text" class="form-control" name="email" placeholder="email address">
   </div>
   
   <div class="form-group">
      <input type="password" class="form-control" name="password" placeholder="password">
	  </div>
	  
	  <div class="form-group">
          <input type="checkbox"  name="stayLoggedIn" value=1>
		  stay logged in
			 </div>
			 
	 <div class="form-group">
<input type="hidden" name="signUp" value="1">
<input type="submit" class="btn btn-primary" name="submit" value="sign up">
</div>
<p><a class="toggleForms">Log In</a></p>
</form>

<form method="post" id="logInForm"> 

<div class="form-group" >
   <input type="text" class="form-control"  name="email" placeholder="email address">
</div>

<div class="form-group">
   <input type="password"  class="form-control" name="password" placeholder="password">
   </div>
   
   <div class="form-group">
     <input type="checkbox"  name="stayLoggedIn" value=1>
	 stay logged in
	 <input type="hidden" name="signUp" value="0">
</div>

<div class="form-group">
    <input type="submit" class="btn btn-primary" name="submit" value="log in">
</div>
<p><a class="toggleForms">sign up</a></p>
</form>
   </div>

   <?php include("footer.php"); ?>