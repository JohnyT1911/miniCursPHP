<?php
session_start();
$response = array("message" => "", "redirect" => "");


if ($_SESSION["registrationComplete"] == "true") {
	$response["message"] = "ok";
	echo json_encode($response);
} else {
	$response["message"] = $_SESSION["registrationComplete"];
	echo json_encode($response);
}

$_SESSION["registrationComplete"] = "false";
