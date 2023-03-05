<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>MEMBER AUTHENTICATION</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
    </head>
    <body>

    <h1 style="text-align: center;"> Welcome to the LOI Member start page:  </h1>
    <h3 style="text-align: center;">Login with your credentials: </h3>

    
    <?php //authenticate.php
      require_once 'db_creds.php';
      try
      {
        $pdo = new PDO($attr, $user, $pass, $opts);
      }
      catch (\PDOException $e)
      {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
      }

      // UNSET COOKIES BY LOADING THIS PAGE
      setcookie("currentDate", '', time() - 2592000);
      setcookie("userForename", '', time() - 2592000);
      setcookie("userSurname", '', time() - 2592000);
      setcookie("PHPSESSID", '', time() - 2592000);


      if(isset($_COOKIE['userForename']) &&  isset($_COOKIE['userSurname'])) {
        session_start();
      } else {
        ?>

        <form method="post" action="" name="signup-form">
          <div class="form-element">
            <label>Username</label>
            <input type="text" name="userLogin" pattern="[a-zA-Z0-9]+" required />
          </div>
          <div class="form-element">
            <label>Password</label>
            <input type="password" name="passwordLogin" required />
          </div>
            <button type="submit" name="register" value="login">Login</button>
        </form>

        <?php
        if (isset($_POST['userLogin']) &&
          isset($_POST['passwordLogin']))
        {
          
          $un_temp = sanitise($pdo, $_POST['userLogin']);
          $pw_temp = sanitise($pdo, $_POST['passwordLogin']);
          $query   = "SELECT * FROM users WHERE username=$un_temp";
          $result  = $pdo->query($query)->fetch();

          // ini_set('session.use_only_cookies', 1);
          if (!$result){
            die("User not found");  
          } 

          $fn  = $result['forename'];
          $sn  = $result['surname'];
          $un  = $result['username'];
          $pw  = $result['password'];

          setcookie("userForename", $fn, time() + 60 * 60 * 24 * 7);
          setcookie("userSurname", $sn, time() + 60 * 60 * 24 * 7);
          setcookie("currentDate", date("l jS \of F Y h:i:s A"), time() + 60 * 60 * 24 * 7);

          // setcookie()

          if (password_verify(str_replace("'", "", $pw_temp), $pw)) {
            session_start();

            $_SESSION['forename'] = $fn;
            $_SESSION['surname']  = $sn;

            echo "<div class='welcome-logged-in'>";
            echo htmlspecialchars("$fn $sn : Hi $fn,
              you are now logged in as '$un'");
            echo ("<p><a href='members.php'>Go to member overview ></a><br><br>
            <a href='logout.php'>Click here to logout</a></p>");
            echo "</div>";
          }
          else {
            echo ("<p class='invalid-output'>Invalid username/password combination</p>");
            // header("Refresh:0");
          }
        }
      }

      function sanitise($pdo, $str)
      {
        $str = htmlentities($str);
        return $pdo->quote($str);
      }
    ?>

    <style>
      form {
        padding: 20px;
        text-align: center;
        background-color: lightgray;
        box-shadow: 2px 2px 8px gray;
        font-family:'Roboto';
        font-weight: 900;
        width: 50%;
        margin: auto;
      }

      input {
        font-family:'Roboto';
        border: none;
        box-shadow: inset 2px 2px 4px gray;
        padding: 3px;
      }

      button {
        font-family:'Roboto';
        border: none;
        box-shadow: 2px 2px 4px gray;
        padding: 5px 20px;
      }

      .form-element {
        margin: 20px;
      }

      .welcome-logged-in {
        font-family: 'Roboto';
        text-align: center;
        margin: auto;
        padding: 20px;
      }

      a:nth-child(1) {
        text-decoration: none;
        background-color: #fb8b24;;
        padding: 5px 20px; 
        color: white;
        box-shadow: 2px 2px 4px gray;
      }

      .invalid-output {
        text-align: center;
      }

    </style>


    </body>
</html>