<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		//IST EIN USER IN DIESER SESSION EINGELOGGT -> PRIVATER BEREICH
		if($this->session->userdata('id'))
		{
			redirect('private_area');
		}
		$this->load->library('form_validation');
		$this->load->model('login_model');
	}
	public function index()
	{
		$this->load->view('view_login');
	}

	public function validation()
	{
		//VALIDIERUNGSREGELN ANLEGEN
		$this->form_validation->set_rules("username", "Username",['required']);
		$this->form_validation->set_rules("password", "Password",['required']);

		//VALIDIERUNGSREGELN WERDEN ERFÜLLT?
		if($this->form_validation->run())
		{
			//ÜBERPRÜFUNG EINGABEDATEN MIT DB DATEN
			$result = $this->login_model->login($this->input->post('username'), $this->input->post('password'));
			if($result == '')
			{
				//KORREKTE DATEN
				redirect('private_area');
			}
			else
			{
				//INKORREKTE DATEN
				echo "<p class='notify red'>".$result."</p>";
				$this->index();
			}
		}
		//FEHLERFALL: MITGABE EINGEGEBENER DATEN
		else {
			$aData = $aData = [
				'username' => $this->input->post('username'),
			];
			$this->load->view("view_login", $aData);
		}
	}
}
