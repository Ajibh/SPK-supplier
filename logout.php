<?php
require_once('includes/init.php');
session_start();
session_destroy();
redirect_to("landing-page.php");
?>