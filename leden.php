<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>MEMBER OVERVIEW</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script>
        // When the user clicks on <div>, open the popup
        function myCookies() {
            var popup = document.getElementById("cookiePopup");
            popup.classList.toggle("show");
        }
    </script>
</head>

<body>

    <?php
    require_once 'db_creds.php';

    try {
        $pdo = new PDO($attr, $user, $pass, $opts);
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
    if (
        isset($_POST['postal'])   &&
        isset($_POST['name'])    &&
        isset($_POST['house_number'])
    ) {
        //GETS POST VALUES
        $postal   = $pdo->quote($_POST['postal']);
        $name   = $pdo->quote($_POST['name']);
        $house_number = $pdo->quote($_POST['house_number']);

        $query = "INSERT INTO members (postal, name, house_number) VALUES " . "($postal, $name, $house_number)";

        //EXECUTE QUERY (POST AND INSERT IN DATABASE)
        $pdo->query($query);

        //EMPTIES POST VALUES
        header("Refresh:0");
    }

    if (isset($_POST['delete']) && isset($_POST['member_id'])) {
        $member_id  = $pdo->quote($_POST['member_id']);
        $query  = "DELETE FROM members WHERE member_id=$member_id";
        $result = $pdo->query($query);

        //EMPTIES POST VALUES
        header("Refresh:0");
    }

    //CHECKS FOR POST VALUES AND DELETES THE SELECTED
    if (isset($_POST['delete']) && isset($_POST['phone_number'])) {
        $phone_number  = $pdo->quote($_POST['phone_number']);
        $query  = "DELETE FROM phone_numbers WHERE phone_number=$phone_number";
        $result = $pdo->query($query);

        //EMPTIES POST VALUES
        header("Refresh:0");
    }

    //CHECKS FOR POST VALUES AND DELETES THE SELECTED
    if (isset($_POST['delete']) && isset($_POST['email_adress'])) {
        $email_adress  = $pdo->quote($_POST['email_adress']);
        $query  = "DELETE FROM email_adresses WHERE email_adress=$email_adress";
        $result = $pdo->query($query);

        //EMPTIES POST VALUES
        header("Refresh:0");
    }

    $query  = "SELECT * FROM postals";
    $allPostals = $pdo->query($query);

    $query  = "SELECT * FROM members";
    $allMembers = $pdo->query($query);

    $query  = "SELECT * FROM phone_numbers";
    $allPhoneNumbers = $pdo->query($query);
    
    $query  = "SELECT * FROM email_adresses";
    $allEmails = $pdo->query($query);

    ?>

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
    <nav class="main-menu">
        <div class="menu menu-1">
            <a href="logout.php">< logout</a>
        </div>
        <div class="menu menu-2">
            <a href="teams.php">V Overview teams</a>
        </div>
        <div class="menu menu-3 popup" onclick="myCookies()">^ Show my cookies
            <span class="popuptext" id="cookiePopup">
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
        <h2 style="font-weight: 900;">Add member: </h2>
        <form action="leden.php" method="post">
            <label for="postal">Postals</label>
            <select type="text" name="postal">
                <?php
                foreach ($allPostals as $postal) : ?>
                    <option value="<?= ($postal['postal']) ?>">
                        <?= ($postal['postal']) ?>
                    </option>
                <?php endforeach;
                ?>
            </select>

            <label for="name">Name</label>
            <input type="text" name="name">

            <label for="house_number">House number</label>
            <input type="text" name="house_number">
            <div class="input-buttons" style="height: 50px;">
                <input class="add-member" type="submit" value="Add member">
                <div class="input-container">
                    <a href="create_postal.php" style="float: left;">
                        <i class="glyphicon glyphicon-plus" style="float: left;"></i>
                        <p>Add postal</p>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="member-overview">
    <?php
    foreach ($allMembers as $member) : ?>
        <div class="member-overview-info">
            Postal =  <?= $member['postal'] ?><br>
            Name =  <?= $member['name'] ?><br>
            House number  =  <?= $member['house_number'] ?><br>
            
            <b>Contact info =</b>
           <?php
            $query  = "SELECT * FROM phone_numbers WHERE member_id='" . $member['member_id'] . "'";
            $phone_numbers = $pdo->query($query);

            foreach ($phone_numbers as $row) {
                ?> 
                <div class="number-erase">
                    <ul style="float: left;">
                        <li><?php  echo $row["phone_number"]; ?></li>
                    </ul>
                    <form action='leden.php' method='post'>
                        <input type='hidden' name='delete' value='yes'>
                        <input type='hidden' name='phone_number' value='<?= $row['phone_number'] ?>'>
                        <div class="delete-member">
                            <i class="glyphicon glyphicon-trash" style="float: left;"></i>
                            <input type='submit' value='Erase' style="margin: -5px 0px 0px 0px; border: none; background: transparent; float: left;">
                        </div>
                    </form>
                </div>
                <?php 
            }
            ?>

            <?php
            $query  = "SELECT * FROM email_adresses WHERE member_id='" . $member['member_id'] . "'";
            $email_adresses = $pdo->query($query);

            foreach ($email_adresses as $row) {
                ?> 
                <div class="number-erase">
                    <ul style="float: left;">
                        <li><?php  echo $row["email_adress"]; ?></li>
                    </ul>
                    <form action='leden.php' method='post'>
                        <input type='hidden' name='delete' value='yes'>
                        <input type='hidden' name='email_adress' value='<?= $row['email_adress'] ?>'>
                        <div class="delete-member">
                            <i class="glyphicon glyphicon-trash" style="float: left;"></i>
                            <input type='submit' value='Erase' style="margin: -5px 0px 0px 0px; border: none; background: transparent; float: left;">
                        </div>
                    </form>
                </div>
                <?php 
            }
            ?>

            <form action='leden.php' method='post' clsas="change-delete-member">
                <input type='hidden' name='delete' value='yes'>
                <input type='hidden' name='member_id' value='<?= $member['member_id'] ?>'>
                <div class="input-buttons">
                    <div class="input-container">
                        <i class="glyphicon glyphicon-trash" style="float: left;"></i>
                        <input type='submit' value='Delete' style="margin: -5px 0px 0px 0px; border: none; background: transparent; float: left;">
                    </div>
                    <div class="input-container">
                        <a type="button" href="update_member.php?member_id=<?= $member['member_id'] ?> &postal=<?= $member['postal'] ?> &name=<?= $member['name'] ?> &house_number=<?= $member['house_number'] ?>">
                            <i class="glyphicon glyphicon-pencil"></i>
                            <p>Change/add info</p>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    <?php endforeach;
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

        .delete-member {
            background: var(--red);
            padding: 2px;
            border: none;
            color: white;
            width: 100px;
            display: flex;
            flex-direction: row;
            justify-content: space-evenly;
        }

        .number-erase {
            display: flex;
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

        .add-member {
            background: var(--yellow);
            padding: 5px;
            border: none;
            color: white;
        }

        .member-overview {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
            justify-content: space-evenly;
            margin: 10px;
            padding: 5px;
        }

        .member-overview-info {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background-color: lightgray;
            padding: 10px;
            text-align: left;
            margin: auto;
            min-height: 260px;
            box-shadow: 2px 2px 4px gray;
        }

        .input-container:first-child {
            background: white;
            padding: 2px;
            border: none;
            color: var(--red);
            width: 100px;
            display: flex;
            flex-direction: row;
            justify-content: center;
            height: 20px;
        }

        .input-container:nth-child(2) {
            padding-top: 10px;
            border: none;
            margin-left: 15px;
        }

        .input-container a {
            display: flex;
            text-decoration: none;
            color: white;
        }

        .input-buttons {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0 10px !important;
            margin: 0;
            background-color: gray;
        }

        option, select {
            color: black;
        }

        pre form, pre ul {
            height: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
        }
        /* Popup container */
        .popup {
            position: relative;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            font-size: 14px;
            cursor: pointer;
        }

        /* The actual popup (appears on top) */
        .popup .popuptext {
            visibility: hidden;
            width: 150%;
            background-color: #555;
            font-size: 10px;
            color: #fff;
            text-align: center;
            padding: 10px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            margin-left: -80px;
            font-weight: 100;
        }

        /* Popup arrow */
        .popup .popuptext::after {
            content: "";
            position: absolute;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #555 transparent transparent transparent;
        }

        /* Toggle this class when clicking on the popup container (hide and show the popup) */
        .popup .show {
            visibility: visible;
            -webkit-animation: fadeIn 1s;
            animation: fadeIn 1s
        }

        /* Add animation (fade in the popup) */
        @-webkit-keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }

        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity:1 ;}
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
            background-color: var(--blue);
        }
    </style>

</body>

</html>