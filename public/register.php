<?php
// database connection
$servername = "localhost";
$username = "abcshop_db_user";
$password = "abcshop_db";
$dbname = "abcshop_mydb";

// database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// check connection
if ($conn->connect_error) {
    die("connection fail " . $conn->connect_error);
}

// POST data
$data = json_decode(file_get_contents("php://input"), true);

$uid = $data['uid'];
$username = $data['username'];
$email = $data['email'];
$password = md5($data['password']);

// insert data
$sql = "INSERT INTO users (uid, username, email) VALUES ('$uid', '$username', '$email')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $uid, $username, $email);

$response = array();

if ($conn->query($sql) === TRUE) {
    $response['success'] = true;
    $response['message'] = "New record created successfully";
} else {
    $response['success'] = false;
    $response['message'] = "Error: " . $sql . "<br>" . $conn->error;
}

// close connection
$conn->close();
$conn->close();

// response
header('Content-Type: application/json');
echo json_encode($response);
?>