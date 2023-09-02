<?php
include("../conn.php");

// Get form data
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$gender = $_POST['gender'];
$course = $_POST['course'];
$year_level = $_POST['year_level'];
$password = $_POST['password'];

$selExamineeFullname = $conn->query("SELECT * FROM examinee_tbl WHERE exmne_fullname='$fullname' ");
$selExamineeEmail = $conn->query("SELECT * FROM examinee_tbl WHERE exmne_email='$email' ");

if ($gender == "0") {
  $res = array("res" => "noGender");
} else if ($course == "0") {
  $res = array("res" => "noCourse");
} else if ($year_level == "0") {
  $res = array("res" => "noLevel");
} else if ($selExamineeFullname->rowCount() > 0) {
  $res = array("res" => "fullnameExist", "msg" => $fullname);
} else if ($selExamineeEmail->rowCount() > 0) {
  $res = array("res" => "emailExist", "msg" => $email);
} else {
  $insData = $conn->query("INSERT INTO examinee_tbl(exmne_fullname,exmne_course,exmne_gender,exmne_email,exmne_password) VALUES('$fullname','$course','$gender','$email','$password')");
  if ($insData) {
    $res = array("res" => "success", "msg" => $email);
  } else {
    $res = array("res" => "failed");
  }
}

echo json_encode($res);
?>


<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <h2>User Registration</h2>
  <form id="registrationForm" action="register.php" method="POST">
    <label for="fullname">Full Name:</label>
    <input type="text" id="fullname" name="fullname" required><br><br>
    
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>
    
    <label for="gender">Gender:</label>
    <select id="gender" name="gender" required>
      <option value="0">Select Gender</option>
      <option value="Male">Male</option>
      <option value="Female">Female</option>
    </select><br><br>
    
    <label for="course">Course:</label>
    <input type="text" id="course" name="course" required><br><br>
    
    <label for="year_level">Year Level:</label>
    <input type="text" id="year_level" name="year_level" required><br><br>
    
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    
    <input type="submit" value="Register">
  </form>

  <script>
    $(document).ready(function() {
      $('#registrationForm').submit(function(event) {
        event.preventDefault(); // Prevent the form from submitting normally
        
        // Get form data
        var formData = $(this).serialize();
        
        // Send the registration request
        $.ajax({
          type: 'POST',
          url: 'register.php',
          data: formData,
          dataType: 'json',
          success: function(response) {
            // Handle the response from the server
            if (response.res === 'success') {
              alert('Registration successful! Email: ' + response.msg);
              // Optionally, redirect the user to a success page
              // window.location.href = 'success.html';
            } else {
              alert('Registration failed!');
            }
          },
          error: function() {
            alert('An error occurred while processing the registration.');
          }
        });
      });
    });
  </script>
</body>
</html>