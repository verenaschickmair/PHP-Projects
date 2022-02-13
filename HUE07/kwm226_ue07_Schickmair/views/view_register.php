<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>HÜ 7 - CodeIgniter</title>
	<link rel="stylesheet" type="text/css" href="<?=base_url();?>css/style.css"/>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>

<div class="registration_form">
	<div>
		<h3>Registration:</h3>
		<?php

		//SERVERSEITIGE FORMULARVALIDIERUNG
		echo validation_errors();

		//LOGIN-FORM
		echo form_open(base_url().'index.php/register/validation');

		//Username
		echo form_label("Username:", "usernamereg");
		$usernameInput = array("value" => set_value('usernamereg'),
			"name" => "usernamereg",
			"id" => "usernamereg");
		echo (form_input($usernameInput)."<br>");

		//Vorname
		echo form_label("First Name:", "firstname");
		$firstnameInput = array("value" => set_value('firstname'),
			"name" => "firstname",
			"id" => "firstname");
		echo (form_input($firstnameInput)."<br>");

		//Nachname
		echo form_label("Last Name:", "lastname");
		$lastnameInput = array("value" => set_value('lastname'),
			"name" => "lastname",
			"id" => "lastname");
		echo (form_input($lastnameInput)."<br>");

		//Password 1
		echo form_label("Password:", "password1reg");
		$password1Input = array(
			"name" => "password1reg",
			"id" => "password1reg");
		echo (form_password($password1Input)."<br>");

		//Password 2
		echo form_label("Repeat:", "password2reg");
		$password2Input = array(
			"name" => "password2reg",
			"id" => "password2reg");
		echo (form_password($password2Input)."<br>");

		//Submit-Button
		$submitInput = array("value" => "Register",
			"class" => "reg_button");
		echo form_submit($submitInput);

		echo form_close();
		?>

		<a href="<?php echo base_url();?>index.php/login">Want to Login?</a>
	</div>
</div>

</body>
</html>
