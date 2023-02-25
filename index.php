<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>MEMBER START</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body>
    <h1> Welcome to the LOI Member start page</h1>
    <h3>You've been logged in as: </h3>

    <?php // START SESSION
        session_start();
        
        if (isset($_COOKIE['userForename']))
        {
            $forename = htmlspecialchars($_COOKIE['userForename']);
            $surname  = htmlspecialchars($_COOKIE['userSurname']);
            
            setcookie("userForename", $forename, time() + 60 * 60 * 24 * 7);
            setcookie("userSurname", $surname, time() + 60 * 60 * 24 * 7);
            setcookie("currentDate", date("l jS \of F Y h:i:s A"), time() + 60 * 60 * 24 * 7);

            echo "<div class='welcome-message'><p>Welcome back $forename.<br>
                    Your full name is $forename $surname.</p><br></div>";

        } 
        elseif (!isset($_SESSION['forename'])) {
            header("Location: authenticate.php");
            exit();
        }
    ?>

    <a href="members.php">I wish to proceed to my overview ></a>


</body>

</html>
