<?php

function redirect_to($url = '')
{
	header('Location: ' . $url);
	exit();
}

function cek_login($role = array())
{
	if (isset($_SESSION['id_user']) && isset($_SESSION['role']) && in_array($_SESSION['role'], $role)) {
		// do nothing
	} else {
		redirect_to("login.php");
	}
}

function get_role()
{
	if (isset($_SESSION['id_user']) && isset($_SESSION['role'])) {
		if ($_SESSION['role'] == '1') {
			return 'admin';
		} elseif ($_SESSION['role'] == '2') {
			return 'customer';
		} elseif ($_SESSION['role'] == '3') {
			return 'supplier';
		} else {
			return false; // Role tidak dikenali
		}
	} else {
		return false; // Tidak ada sesi login
	}
}
?>