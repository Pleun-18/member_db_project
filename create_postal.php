<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>MEMBER POSTAL CREATION</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

    <?php
    require_once 'db_creds.php';

    session_start();

    try {
        $pdo = new PDO($attr, $user, $pass, $opts);
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }

    if (
        isset($_POST['postal'])   &&
        isset($_POST['adress'])    &&
        isset($_POST['residence'])
    ) {
        $postal   = get_post($pdo, 'postal');
        $adress   = get_post($pdo, 'adress');
        $residence = get_post($pdo, 'residence');

        //PREPARED STATEMENT
        $stmt = $pdo->prepare("INSERT INTO postals(postal, adress, residence) VALUES (:postal, :adress, :residence)");
        $stmt->bindParam(':postal', $postal, PDO::PARAM_STR);
        $stmt->bindParam(':adress', $adress, PDO::PARAM_STR);
        $stmt->bindParam(':residence', $residence, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt) {
            echo "<script type='text/javascript'>alert('Upload succesfull');</script>";
        }else {
            echo "<script type='text/javascript'>alert('Something went wrong');</script>";
        }
    }

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

    <div class="add-block">
        <h2 style="font-weight: 900;">Add your adress: </h2>
            <form action="create_postal.php" method="post">
               Postal <input type="text" name="postal">
               Adress <input type="text" name="adress">
               Residence <input type="text" name="residence">
               
               <input type="submit" value="Add postal"><br>
            </form>
    </div>

    <div class="postal-overview">

            <?php
            $query  = "SELECT * FROM postals";
            $result = $pdo->query($query);
        
            while ($row = $result->fetch()) {
                $r0 = htmlspecialchars($row['postal']);
                $r1 = htmlspecialchars($row['adress']);
                $r2 = htmlspecialchars($row['residence']);
            ?> <div class="overview-singles">   <?php
                echo <<<_END
           <pre>
               Postal =  $r0
               Adress =  $r1
               Residence  =  $r2
            </pre>
           _END;
           ?> </div>   <?php    
            }
            function get_post($pdo, $var)
            {
                return $pdo->quote($_POST[$var]);
            }
            ?>
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

        .postal-overview {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
            justify-content: space-evenly;
        }

        .overview-singles {
            background-color: lightgray;
            box-shadow: 2px 2px 4px gray;
            margin: 20px;
            padding: 10px;
        }

        .input-buttons {
            display: flex;
            align-items: center;
            padding: 10px;
        }

        input:nth-child(4) {
            background: var(--yellow);
            padding: 5px;
            border: none;
            color: white;
        }

        form {
            display: flex;
            flex-direction: column;
            padding: 20px;
        }
        .main-menu {
            display: flex; 
            flex-direction: row; 
            justify-content: space-evenly;
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