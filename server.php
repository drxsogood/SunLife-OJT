<?php
session_start();


// initializing variables
$username = "";
$email    = "";
$errors = array(); 

// connect to the database

$db = mysqli_connect('localhost', 'root', '', 'project');

// REGISTER USER

if (isset($_POST['reg_user'])) {

  // receive all input values from the form
  // escapes special characters in a string for use in an SQL query, 
  //taking into account the current character set of the connection
  // Which is used to collect form data after submitting Post
  
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
 
  // form validation: ensure that the form is correctly filled ...
  // by adding JavaScript pop-up alerts for each error

  if (empty($username)) { 
    echo "<script>alert('Username is required');</script>";
  } elseif (empty($email)) { 
    echo "<script>alert('Email is required');</script>";
  } elseif (empty($password_1)) { 
    echo "<script>alert('Password is required');</script>";
  } elseif ($password_1 != $password_2) {
    echo "<script>alert('The two passwords do not match');</script>";
  } else {

    // Check if the username or email already exist in the database
    $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) { // if user exists
      if ($user['username'] === $username) {
        echo "<script>alert('Username already exists');</script>";
      }
      if ($user['email'] === $email) {
        echo "<script>alert('Email already exists');</script>";
      }
    } else {

      // If the username and email are unique, proceed with registration
      // Hash the password
      $password = md5($password_1);

      // Insert the user data into the database
      $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
      
      if (mysqli_query($db, $query)) {
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now registered and logged in";
        header('location: index.php');
      } else {
        echo "<script>alert('Database error: Registration failed');</script>";
      }
    }
  }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  // validation: display JavaScript pop-up alerts for errors
  if (empty($username)) {
    echo "<script>alert('Username is required');</script>";
  } elseif (empty($password)) {
    echo "<script>alert('Password is required');</script>";
  } else {
    // Check if there are no errors
    if (empty($errors)) {
      $password = md5($password);

      $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
      $results = mysqli_query($db, $query);

      if (mysqli_num_rows($results) == 1) {
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in";
        header('location: index.php');
      } else {
        echo "<script>alert('Wrong username/password combination');</script>";
      }
    }
  }
}


?>
