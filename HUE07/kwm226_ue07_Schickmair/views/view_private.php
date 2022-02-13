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

<h1 class="welcomeUser">Welcome! You are now logged in</h1>

<a href="<?php echo base_url()?>index.php/private_area/logout" class="logout_button">Logout</a>

</body>
</html>
