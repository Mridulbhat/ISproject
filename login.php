<?php
session_start();

// Connect to the database
$db = mysqli_connect('localhost', 'root', '', 'project');

$errors = array();

if (isset($_POST['login_user'])) {
  $username = $_POST['username']; // Not escaped
  $password = $_POST['password']; // Not hashed or escaped

  if (empty($username)) {
    array_push($errors, "Username is required");
  }
  if (empty($password)) {
    array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
    // VULNERABLE QUERY: susceptible to SQL Injection
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $results = mysqli_query($db, $query);

    if (mysqli_num_rows($results) == 1) {
      $_SESSION['username'] = $username;
      $_SESSION['success'] = "You are now logged in";
      header('location: index.php');
    } else {
      array_push($errors, "Wrong username/password combination");
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Registration system PHP and MySQL</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div class="header">
    <h2>Login</h2>
  </div>

  <form method="post" action="login.php">
    <?php if (count($errors) > 0): ?>
      <div class="error">
        <?php foreach ($errors as $error): ?>
          <p><?php echo $error; ?></p>
        <?php endforeach ?>
      </div>
    <?php endif ?>
    
    <div class="input-group">
      <label>Username</label>
      <input type="text" name="username">
    </div>
    <div class="input-group">
      <label>Password</label>
      <input type="password" name="password">
    </div>
    <div class="input-group">
      <button type="submit" class="btn" name="login_user">Login</button>
    </div>
    <p>
      Not yet a member? <a href="register.php">Sign up</a>
    </p>
  </form>
</body>
</html>
