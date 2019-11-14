<?php

session_start();																																//starting a session

//if someone tries to access this page directly without log-in, they will be redirected on sign-in page
if(!isset($_SESSION['user_id'])){
	header("Location: login.php");
}

session_unset();																																//unset the session

session_destroy();																															//destroy the session

header("Location: index.php");																									//redirect user to signin page

?>
