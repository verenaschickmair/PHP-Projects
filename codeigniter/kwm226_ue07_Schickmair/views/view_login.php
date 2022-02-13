<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if($this->session->userdata('id'))
	redirect('private_area');
else{
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>HÜ 7 - CodeIgniter</title>
	<link rel="stylesheet" type="text/css" href="<?=base_url();?>css/style.css"/>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>


<div class="login_form">
	<div>
		<h3>Login:</h3>
		<?php
		//SERVERSEITIGE FORMULARVALIDIERUNG
		echo validation_errors();

		//LOGIN-FORM
		echo form_open(base_url().'index.php/login/validation');

		//Username
		echo form_label("Username:", "username");
		$usernameInput = array("value" => set_value("username"),
				"name" => "username",
				"id" => "username");
		echo (form_input($usernameInput)."<br>");

		//Password
		echo form_label("Password:", "password");
		$passwordInput = array(
				"name" => "password",
				"id" => "password");
		echo (form_password($passwordInput)."<br>");

		//Submit-Button
		$submitInput = array("value" => "Login",
				"class" => "login_button");
		echo form_submit($submitInput);

		echo form_close();
		?>

		<a href="<?php echo base_url();?>index.php/register">Want to Register?</a>
	</div>
</div>
</body>
</html>

<?php
}
?>
