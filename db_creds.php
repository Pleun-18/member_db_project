<?php // login.php
  $host = 'localhost';    // Change as necessary
  $data = 'member_db'; // Change as necessary
  $user = 'root';         // Change as necessary
  $pass = '';        // Change as necessary
  $chrs = 'utf8mb4';
  $attr = "mysql:host=$host;dbname=$data;charset=$chrs";
  $opts =
  [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ];
    
  if (isset($_COOKIE['userForename']))
  {
      $forename = htmlspecialchars($_COOKIE['userForename']);
      $surname  = htmlspecialchars($_COOKIE['userSurname']);
      
      setcookie("userForename", $forename, time() + 60 * 60 * 24 * 7);
      setcookie("userSurname", $surname, time() + 60 * 60 * 24 * 7);
      setcookie("currentDate", date("l jS \of F Y h:i:s A"), time() + 60 * 60 * 24 * 7);
  }
?>