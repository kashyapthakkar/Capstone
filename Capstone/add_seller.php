<?php
include 'database.php';

$seller_name = $_POST["seller_name"];
$email = $_POST["email"];
$password = $_POST["password"];
$password2 = $_POST["confirm_password"];
$contact = $_POST["contact_number"];
$errors = array();


$stmt = $conn->prepare("SELECT * FROM sellers WHERE Email='$email';");
$stmt->execute();
$user = $stmt->fetch();

if($user){
    array_push($errors, "Email address already exist");
}

 //if email is invalid then show an error
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    array_push($errors, "Invalid Email Address");
}

if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

if (count($_SESSION['errors']) == 0) {
    $password = md5($password);//encrypt the password before saving in the database
    $stmt = $conn->prepare("INSERT INTO sellers " .
                             "(username,email,password, phone_number) VALUES " .
                             "(?, ?, ?, ?)");
    $stmt->execute([$seller_name, $email, $password, $contact]);
    //header("Location: signin.php");                                           //redirect to sign-in page if registration is successful
}else{
    header("Location: sellers_register.php");                                             //redirect to registration page if anything wrong with registration
}
