<?php
session_start();
$_SESSION["registrationComplete"] = "false";

$email = $password = "";

$HOST = "127.0.0.1";
$DATABASE = "mysql";
$USERNAME = "root";
$PASSWORD = "";

$dbh = new PDO('mysql:host=' . "127.0.0.1" . ';dbname=' . "minicurs", "root", "", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$response = array("message" => "", "redirect" => "");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$email = input($_POST["email"]);
	$password = input($_POST["password"]);
}

function input($data)
{
	$data = trim($data);
	$data = htmlspecialchars($data);
	return $data;
}

function get_user_by_email($email)
{
	global $dbh;
	$sth = $dbh->query('SELECT * FROM users WHERE email="' . $email . '"');
	$user = $sth->fetch(PDO::FETCH_ASSOC);

	return $user;
}

if (get_user_by_email($email)) {
	$response["message"] = "User already created!";
	echo json_encode($response);
} else {
	if (!$email == "" || !$password == "") {
		$user_id = add_user($email, $password);
		$response["message"] = "User created!";
		$response["redirect"] = "page_login.html";

		$_SESSION["registrationComplete"] = "true";

		echo json_encode($response);
	} else {
		$response["message"] = "something is wrong";
		echo json_encode($response);
	}
}

function add_user($email, $password)
{
	global $dbh;
	$sth = $dbh->prepare('INSERT INTO users(password,email) VALUES(:password, :email)');
	$sth->bindParam(':password', $password);
	$sth->bindParam(':email', $email);
	$sth->execute();

	$sth = $dbh->query('SELECT * FROM users WHERE email="' . $email . '"');
	$user = $sth->fetch(PDO::FETCH_ASSOC);

	return $user["id"];
}
