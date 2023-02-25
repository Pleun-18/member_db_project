<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>MEMBER REGISTRATION</title>
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
    require_once 'login.php';

    try {
        $pdo = new PDO($attr, $user, $pass, $opts);
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }

    //CHECKS FOR TEAMS AND ADD THEM
    if ( isset($_POST['team_name']) && isset($_POST['description']) ) {
        //GETS POST VALUES
        $team_name   = $pdo->quote($_POST['team_name']);
        $description   = $pdo->quote($_POST['description']);

        $query = "INSERT INTO teams (team_name, description) VALUES " . "($team_name, $description)";

        //EXECUTE QUERY (POST AND INSERT IN DATABASE)
        $pdo->query($query);

        //EMPTIES POST VALUES
        header("Refresh:0");
    }

    //CHECKS FOR TEAM MEMBERS AND ADDING TO TEAM
    if ( isset($_POST['team_name']) && isset($_POST['member_id']) ) {
        //GETS POST VALUES
        $team_name  = $pdo->quote($_POST['team_name']);
        $member_id   = $pdo->quote($_POST['member_id']);

        $query = "INSERT INTO team_member (team_name, member_id) VALUES " . "($team_name, $member_id)";

        //EXECUTE QUERY (POST AND INSERT IN DATABASE)
        $pdo->query($query);

        //EMPTIES POST VALUES
        header("Refresh:0");
    }

    //CHECKS FOR POST VALUES AND DELETES THE SELECTED
    if (isset($_POST['delete']) && isset($_POST['team_name'])) {
        $member_id  = $pdo->quote($_POST['team_name']);
        $query  = "DELETE FROM teams WHERE team_name=$team_name";
        $result = $pdo->query($query);

        //EMPTIES POST VALUES
        header("Refresh:0");
    }

    if (isset($_POST['delete']) && isset($_POST['team_member_id'])) {
        $team_member_id  = $pdo->quote($_POST['team_member_id']);
        $query  = "DELETE FROM team_member WHERE team_member_id=$team_member_id";
        $result = $pdo->query($query);

        //EMPTIES POST VALUES
        header("Refresh:0");
    }

    $query  = "SELECT member_id,name FROM members";
    $allMembers = $pdo->query($query);

    $query  = "SELECT team_name FROM teams";
    $allTeamNames = $pdo->query($query);

    $query  = "SELECT * FROM teams";
    $allTeams = $pdo->query($query);

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
            <a href="leden.php">V Overview leden</a>
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

    <!-- ADD A TEAM-->
    <div class="add-block">
        <h2 style="font-weight: 900;">Add your team:</h2>
        <form action="teams.php" method="post">
            <label for="team_name">Add team</label>
            <input type="text" name="team_name">

            <label for="description">Team description</label>
            <input type="text" name="description">

            <div class="input-buttons">
                <div class="add-team">
                    <i class="glyphicon glyphicon-plus" style="float: left;"></i>
                    <input type="submit" value="Add member" style="border: none; background: transparent;">
                </div>
            </div>
        </form>
    </div>

    <!-- ADD A TEAM MEMBER -->
    <div class="add-block">
        <h2 style="font-weight: 900;">Add team member</h2>
        <form action="teams.php" method="post">
            <label for="team_name">Team to join</label>
            <select name="team_name">
                <option value="" disabled selected>Select your team</option>
                <?php
                foreach ($allTeamNames as $team_name) : ?>
                    <option value="<?= ($team_name['team_name']) ?>">
                        <?= ($team_name['team_name']) ?>
                    </option>
                <?php endforeach;
                ?>
            </select>

            <label for="member_id">Member too add (id):</label>
            <select name="member_id">
                <option value="" disabled selected>Select your name</option>
                <?php
                foreach ($allMembers as $member) : ?>
                    <option value="<?= ($member['member_id']) ?>">
                        <?= ($member['name']) ?>
                    </option>
                <?php endforeach;
                ?>
            </select>
            
            <div class="input-buttons">
                <div class="add-member">
                    <i class="glyphicon glyphicon-plus" style="float: left;"></i>
                    <input type="submit" value="Add team member" style="border: none; background: transparent;">
                </div>
            </div>
        </form>
    </div>


    <!--team_member(team_name) === teams(team_name) -->
    <div class="teams-overview">
    <?php
    foreach ($allTeams as $team) : ?>


        <div class="team-overview-info">
            Team =  <?= $team['team_name'] ?><br>
            Description =  <?= $team['description'] ?> <br>
            Team members = 
            <?php
            $query  = "SELECT * FROM team_member WHERE team_name='" . $team['team_name'] . "'";
            $team_members = $pdo->query($query);

            foreach($team_members as $team_member){
                $query  = "SELECT * FROM members WHERE member_id=" . $team_member['member_id'];
                $members = $pdo->query($query)->fetch();

                ?> 
                <div class="member-erase">
                    <ul style="float: left;">
                        <li><?php  echo $members['name']; ?></li>
                    </ul>
                    <form action='teams.php' method='post'>
                        <input type='hidden' name='delete' value='yes'>
                        <input type='hidden' name='team_member_id' value='<?= $team_member['team_member_id'] ?>'>
                        <div class="delete-member">
                            <i class="glyphicon glyphicon-trash" style="float: left;"></i>
                            <input type='submit' value='Erase' style="margin: -5px 0px 0px 0px; border: none; background: transparent; float: left;">
                        </div>
                    </form>
                </div>

                <?php 
            }
            ?>

        <form action='teams.php' method='post'>
            <input type='hidden' name='delete' value='yes'>
            <input type='hidden' name='team_name' value='<?= $teams['team_name']?>'>
            <div class="input-buttons delete-all">
                <div class="input-container">
                    <i class="glyphicon glyphicon-trash" style="float: left;"></i>
                    <input type='submit' value='Delete team' style="margin: -5px 0px 0px 0px; border: none; background: transparent; float: left;">
                </div>
                <div class="input-container">
                    
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

        .add-member, .add-team {
            background: var(--yellow);
            padding: 5px;
            border: none;
            color: white;
        }

        .input-container:first-child {
            background: white;
            padding: 2px;
            border: none;
            color: var(--red);
            width: 120px;
            display: flex;
            flex-direction: row;
            justify-content: center;
            height: 20px;
        }

        .teams-overview {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
            justify-content: space-evenly;
        }

        .team-overview-info {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background-color: lightgray;
            padding: 10px;
            text-align: left;
            margin: auto;
            min-height: 220px;
            box-shadow: 2px 2px 4px gray;
        }

        .delete-member {
            background: var(--red);
            padding-top: 4px;
            border: none;
            color: white;
            width: 100px;
            display: flex;
            flex-direction: row;
            justify-content: center;
        }

        .add-block {
            background: gray;
            padding: 50px;
            border: none;
            display: flex;
            flex-direction: row;
            justify-content: center;
            margin: 20px;
        }

        h2 {
            padding-right: 20px;
            margin: 0;
        }

        .add-member-form, .add-team-form {
            padding: 20px;
            width: 25%;
        }

        .input-container:nth-child(2) {
            border: none;
            margin-left: 15px;
        }

        pre {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border: 2px solid black;
            padding: 0;
        }

        .input-buttons {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 10px 5px;
            background-color: gray;
        }

        form {
            display: flex;
            flex-direction: column;
            color: black;
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

         /* Popup container */
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