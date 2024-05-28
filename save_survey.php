<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "survey_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$full_names = $_POST['full_names'];
$email = $_POST['email'];
$date_of_birth = $_POST['date_of_birth'];
$contact_number = $_POST['contact_number'];
$favorite_food = implode(", ", $_POST['favorite_food']);
$watch_movies = $_POST['watch_movies'];
$listen_radio = $_POST['listen_radio'];
$eat_out = $_POST['eat_out'];
$watch_tv = $_POST['watch_tv'];

$sql = "INSERT INTO surveys (full_names, email, date_of_birth, contact_number, favorite_food, watch_movies, listen_radio, eat_out, watch_tv) VALUES ('$full_names', '$email', '$date_of_birth', '$contact_number', '$favorite_food', '$watch_movies', '$listen_radio', '$eat_out', '$watch_tv')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

header('Location: view_results.php');
exit;
?>