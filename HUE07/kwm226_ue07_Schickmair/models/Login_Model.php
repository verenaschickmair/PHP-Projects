<?php


class Login_Model extends CI_Model
{
	function login($username, $password)
	{
		$this->db->where('username', $username);
		$query = $this->db->get('user');
		//ÜBERPRÜFUNG ÜBEREINSTIMMUNG USERNAME
		if($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$store_password = md5($password);
				//ÜBERPRÜFUNG ÜBEREINSTIMMUNG PASSWORT
				if ($row->password == $store_password) {
					$this->session->set_userdata('id', $row->userid);
				} else {
					return 'Wrong Password!';
				}
			}
		}
		else
			return 'Wrong Username!';
	}
}
