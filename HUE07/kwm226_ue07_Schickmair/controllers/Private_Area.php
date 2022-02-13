<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Private_Area extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('id'))
		{
			redirect('login');
		}
	}

	function index(){
		$this->load->view("view_private");
	}

	function logout(){
		//BEI LOGOUT WERDEN ALLE DATEN IN DER SESSION GELÖSCHT
		$data = $this->session->all_userdata();
		foreach($data as $row => $rows_value)
		{
			$this->session->unset_userdata($row);
		}
		redirect('login');
	}
}
