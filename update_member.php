<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>MEMBER REGISTRATION</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body>

    <?php
    require_once 'db_creds.php';

    try {
        $pdo = new PDO($attr, $user, $pass, $opts);
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }

    //CHECKS FOR POST VALUES
    if (
        isset($_POST['postal'])   &&
        isset($_POST['name'])    &&
        isset($_POST['house_number'])
        
    ) {
        //GETS POST VALUES
        $postal   = $pdo->quote($_POST['postal']);
        $name   = $pdo->quote($_POST['name']);
        $house_number = $pdo->quote($_POST['house_number']);

        $query = "UPDATE members SET name=$name, house_number=$house_number WHERE postal=$postal";

        //EXECUTE QUERY (POST AND INSERT IN DATABASE)
        $pdo->query($query);
    }

    //CHECKS FOR POST VALUES
    if (
        isset($_POST['email_adress']) &&
        isset($_POST['member_id'])
    ) {
        //GETS POST VALUES
        $email_adress   = $pdo->quote($_POST['email_adress']);
        $member_id   = $pdo->quote($_POST['member_id']);
        $query = "INSERT INTO email_adresses (email_adress, member_id) VALUES " . "($email_adress, $member_id)";

        //EXECUTE QUERY (POST AND INSERT IN DATABASE)
        $pdo->query($query);

    }

    //CHECKS FOR POST VALUES
    if (
        isset($_POST['phone_number']) &&
        isset($_POST['member_id'])
    ) {
        //GETS POST VALUES
        $phone_number   = $pdo->quote($_POST['phone_number']);
        $member_id   = $pdo->quote($_POST['member_id']);

        //PREPARED STATEMENT
        $stmt = $pdo->prepare("INSERT INTO phone_numbers(phone_number, member_id) VALUES (:phone_number, :member_id)");
        $stmt->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
        $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $stmt->execute();
    }

    if (isset($_POST['delete']) && isset($_POST['member_id'])) {
        $member_id  = $pdo->quote($_POST['member_id']);
        $query  = "DELETE FROM members WHERE member_id=$member_id";
        $pdo->query($query);
    }

    $query  = "SELECT * FROM postals";
    $allPostals = $pdo->query($query);

    $query  = "SELECT * FROM members";
    $allMembers = $pdo->query($query);

    //ADD THE MEMBER_ID TO INSERT MEMBER INFO AS VALUE
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $url = "https://";
    } else {
        $url = "http://";
    }

    // Append the host(domain name, ip) to the URL.   
    $url .= $_SERVER['HTTP_HOST'];

    // Append the requested resource location to the URL   
    $url .= $_SERVER['REQUEST_URI'];

    // echo $url;  
    ?>

    <?php // START SESSION
        session_start();

        if (isset($_COOKIE['userForename']))
        {
            $forename = htmlspecialchars($_COOKIE['userForename']);
            $surname  = htmlspecialchars($_COOKIE['userSurname']);

            echo "<div class='welcome-message'><p>Welcome back $forename.<br>
                  Your full name is $forename $surname.</p><br></div>";

        } elseif (!isset($_SESSION['forename'])) {
            header("Location: authenticate.php");
            exit();
        }
    ?>

    <nav class="main-menu">
        <div class="menu menu-1">
            <a href="logout.php">< logout</a>
        </div>
        <div class="menu menu-2">
            <a href="members.php">V Overview Members</a>
        </div>
        <div class="menu menu-3" onclick="myCookies()">^ Show my cookies
            <span class="tooltiptext">
                <?php 
                    if(isset($_COOKIE["userForename"]) && isset($_COOKIE["userSurname"]) && isset($_COOKIE["currentDate"])) {
                        echo "<b>Cookie:</b><br> User is " . $_COOKIE["userForename"] . " " .  $_COOKIE["userSurname"] . 
                        ". <br> The date and time are: <br>" . $_COOKIE["currentDate"];
                        } else {
                        echo "Cookie '" . $cookie_name . "' is set!<br>";
                        echo "Value is: " . $_COOKIE[$cookie_name];
                    }
                ?>
            </span>
        </div>
    </nav>

    <?php 

    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    
    $url_components = parse_url($url);
    // echo $url_comsponents;
    parse_str($url_components["query"], $params);

    ?>

    <div class="add-block">
        <h2 style="font-weight: 900;">Change selected member: </h2>
        <form action="<?php $url_components ?>" method="post">
                <label for="postal">Postals</label>
                <input type="text" name="postal" value="<?= $params['postal'] ?>" readonly>

                <label for="name">Member id</label>
                <input type="text" name="member_id" value='<?= $params['member_id'] ?>' readonly>

                <label for="name">Name</label>
                <input type="text" name="name" placeholder="<?= $params['name'] ?>">

                <label for="house_number">House number</label>
                <input type="text" name="house_number" value="">
                
                <input class="change-member" type="submit" value="Save changes"><br><br>
        </form>
    </div>

    <div class="add-block">
        <div class="block block-1">
            <h2 style="font-weight: 900;">Add e-mail: </h2>
            <form action="<?php $url_components ?>" method="post">
                    <label for="member_id">Selected member id</label>
                    <input type="text" name="member_id" value="<?= $params['member_id'] ?>" readonly>

                    <label for="email_adress">E-mail adress</label>
                    <input type="text" name="email_adress" value="">
                    
                    <input class="add-email" type="submit" value="Add e-mail"><br><br>
            </form>
        </div>
        <div class="block block-2">
            <h2 style="font-weight: 900;">Add phone number: </h2>
            <form action="<?php $url_components ?>" method="post">

                    <label for="member_id">Selected member id</label>
                    <input type="text" name="member_id" value="<?= $params['member_id'] ?>" readonly>

                    <label for="phone_number">Phone number</label>
                    <input type="text" name="phone_number" value="">
                    
                    <input class="add-number" type="submit" value="Add number"><br><br>
            </form>
        </div>
    </div>


    <style>
        /* Set up */
        :root {
            --purple: #5f0f40;
            --red: #9a031e;
            --yellow: #fb8b24;
            --orange: #e36414;
            --blue: #0f4c5c;
            --font: "Montserrat";
            --title: "Roboto";
        }

        body {
            font-family: 'Roboto';
        }

        .welcome-message {
            width: 100%;
            text-align: center;
            background-color: var(--blue);
            color: white;
            font-size: 24px;
            padding-top: 20px;
            box-shadow: 2px 2px 15px grey;
        }
        .add-block {
            background: gray;
            padding: 50px;
            border: none;
            display: flex;
            flex-direction: row;
            justify-content: space-evenly;
            margin: 20px;
        }

        .change-member, .add-email, .add-number {
            background: var(--yellow);
            padding: 5px;
            border: none;
            color: white;
        }

        .block {
            margin: 10px;
        }

        pre form, pre ul {
            height: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input {
            cursor: pointer;
        }

        .main-menu {
            display: flex; 
            flex-direction: row; 
            justify-content: space-evenly;
            font-family: 'Roboto';
        }

        .menu {
            padding: 5px 10px; 
            text-decoration: none; 
            color: white;
            font-weight: 900;
        }
        .menu a {
            color: white;
        }
        .menu-1 {
            background-color: var(--red); 
        }
        .menu-2 {
            background-color: var(--yellow);
        }
        .menu-3 {
            position: relative;
            display: inline-block;
            border-bottom: 1px dotted black;
            background-color: var(--blue);
        }

        .menu-3 .tooltiptext {
            visibility: hidden;
            margin-top: 30px;
            width: 220px;
            background-color: lightgray;
            color: #fff;
            text-align: center;
            padding: 5px 0;
            left: 0;
            /* Position the tooltip */
            position: absolute;
            z-index: 1;
        }

        .menu-3:hover .tooltiptext {
            visibility: visible;
        }
    </style>

</body>

</html>