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
