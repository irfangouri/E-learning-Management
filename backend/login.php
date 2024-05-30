<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $user_type = $_POST['user_type'];

  $email = htmlspecialchars($email);
  $password = htmlspecialchars($password);
  $user_type = htmlspecialchars($user_type);

  $conn = get_db_connection();

  if ($user_type === 'student') {
    $stmt = $conn->prepare("SELECT student_id, email, password FROM student WHERE email = ?");
  } else if ($user_type === 'teacher') {
    $stmt = $conn->prepare("SELECT teacher_id, email, password FROM teacher WHERE email = ?");
  } else {
    echo json_encode(array("error" => "Invalid user type"));
    exit;
  }

  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    if ($user_type === 'student') {
      $stmt->bind_result($user_id, $db_email, $db_password);
    } else if ($user_type === 'teacher') {
      $stmt->bind_result($user_id, $db_email, $db_password);
    }

    $stmt->fetch();

    if ($password === $db_password) {
      $user_data = array(
        "id" => $user_id,
        "email" => $db_email,
        "user_type" => $user_type
      );
      echo json_encode($user_data);
    } else {
      echo json_encode(array("error" => "Invalid password"));
    }
  } else {
    echo json_encode(array("error" => "Email does not exist"));
  }

  $stmt->close();
  $conn->close();
} else {
  http_response_code(405);
  echo json_encode(array("error" => "Method Not Allowed"));
}
?>
