<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $user_type = $_POST['user_type'];

  $name = htmlspecialchars($name);
  $email = htmlspecialchars($email);
  $password = htmlspecialchars($password);
  $user_type = htmlspecialchars($user_type);

  $conn = get_db_connection();

  if ($user_type === 'student') {
    $stmt = $conn->prepare("SELECT student_id FROM student WHERE name = ?");
  } else if ($user_type === 'teacher') {
    $stmt = $conn->prepare("SELECT teacher_id FROM teacher WHERE name = ?");
  } else {
    echo json_encode(array("error" => "Invalid user type"));
    exit;
  }

  $stmt->bind_param("s", $name);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    echo json_encode(array("error" => "Name already taken"));
  } else {
    $stmt->close();

    if ($user_type === 'student') {
      $stmt = $conn->prepare("INSERT INTO student (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
    } else if ($user_type === 'teacher') {
      $stmt = $conn->prepare("INSERT INTO teacher (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
    }

    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
      $user_id = $stmt->insert_id;
      $user_data = array(
        "id" => $user_id,
        "name" => $name,
        "email" => $email,
        "user_type" => $user_type
      );
      echo json_encode($user_data);
    } else {
      echo json_encode(array("error" => "Registration failed: " . $stmt->error));
    }
  }

  $stmt->close();
  $conn->close();
} else {
  http_response_code(405);
  echo json_encode(array("error" => "Method Not Allowed"));
}
?>
