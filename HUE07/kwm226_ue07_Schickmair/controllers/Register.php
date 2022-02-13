<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		//IST EIN USER IN DIESER SESSION EINGELOGGT -> PRIVATER BEREICH
		if ($this->session->userdata('id')) {
			redirect('private_area');
		}
		$this->load->helper(["form", "url"]);
		$this->load->library('form_validation');
		$this->load->model('register_model');
	}

	public function index()
	{
		$this->load->view("view_register");
	}

	public function validation()
	{
		//VALIDIERUNGSREGELN ANLEGEN
		$this->form_validation->set_rules("usernamereg", "Username", ['required']);
		$this->form_validation->set_rules("firstname", "First Name", ['required']);
		$this->form_validation->set_rules("lastname", "Last Name", ['required']);
		$this->form_validation->set_rules("password1reg", "Password", ['required', 'min_length[8]']);
		$this->form_validation->set_rules("password2reg", "Repeat", ['required', 'matches[password1reg]']);

		//VALIDIERUNGSREGELN WERDEN ERFÜLLT?
		if ($this->form_validation->run() != FALSE) {
			//ÜBERPRÜFUNG: GIBT ES USERNAMEN BEREITS?
			if (empty($this->register_model->exists($this->input->post('usernamereg')))) {
				//PASSWORT VERSCHLÜSSELN
				$encrypted_password = md5($this->input->post("password1reg"));
				$aData = [
					'username' => $this->input->post('usernamereg'),
					'firstname' => $this->input->post('firstname'),
					'lastname' => $this->input->post('lastname'),
					'password' => $encrypted_password
				];
				//EINFÜGEN IN DATENBANK
				$this->register_model->insert($aData);
				echo "<p class='notify green'>Registration successful!</p>";
			}
			else
				echo "<p class='notify red'>Username already exists!</p>";
			$this->index();
		}
		else {
			//FEHLERFALL: MITGABE EINGEGEBENER DATEN
			$aData = $aData = [
				'username' => $this->input->post('usernamereg'),
				'firstname' => $this->input->post('firstname'),
				'lastname' => $this->input->post('lastname'),
			];
			$this->load->view("view_register", $aData);
		}
	}
}
