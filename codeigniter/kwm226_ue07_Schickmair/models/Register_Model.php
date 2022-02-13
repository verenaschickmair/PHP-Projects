<?php


class Register_Model extends CI_Model
{
	function insert($aData)
	{
		$this->db->insert('user', $aData);
		return $this->db->insert_id();
	}

	function exists($sUsername)
	{
		$sQuery = "SELECT username FROM user WHERE username='".$sUsername."';";
		//durch Autoload der Library db Zugriff möglich
		$mResult = $this->db->query($sQuery);
		return $mResult->result();
	}
}
