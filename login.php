<?php
session_start();
include("Header.php");

if (isset($_POST['signin'])) {
  $con = connectdb();
  $email = strtolower(test_input($con,$_POST['email']));
  $password = test_input($con,$_POST['password']);
  
  $query = 'SELECT * FROM users WHERE user_email = "'.$email.'"';
  $result = mysqli_query($con, $query);
  $user = mysqli_fetch_array($result);

  if (!$user) {
    echo'login failed';
    exit;
  }

  if (!password_verify($password,$user['user_pass'])) {
    header('location: login.php'); 
    exit;
  } else {
    $_SESSION['id'] = $user['user_id'];       // Storing the value in session
    //! Session data can be hijacked. Never store personal data such as password, security pin, credit card numbers other important data in $_SESSION
    header('location: dashboard.php?id=' . $user['user_id']);
  }
}

?>
   <section class="login-container">
        <div class="form_container login_form">
            <h1 class="loginTitle"> Login</h1>
                <form id="login" method="post">
                    <div class="from_input">
                        <input type="text" name="email" id="email" placeholder="email"  autocomplete="off" required>
                        <input type="password" name="password" id="password" placeholder="WachtWoord"  autocomplete="off" required>
                    </div>
                    <div class="submit_btn">
                        <button type="submit" name="signin" id='signin'>Signin</button>
                    </div>
                </form >
                <!-- <p><a href="#">Vergeet Wachtwoord </a> </p> -->
                <p>Heb je geen account dan ? <a href="./contact.php">Contact ons</a> </p>
        </div>
    </section>
