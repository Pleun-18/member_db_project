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

    //CHECKS FOR TEAMS AND ADD THEM
    if ( isset($_POST['team_name']) && isset($_POST['description']) ) {
        //GETS POST VALUES
        $team_name   = $pdo->quote($_POST['team_name']);
        $description   = $pdo->quote($_POST['description']);

        //PREPARED STATEMENT
        $stmt = $pdo->prepare("INSERT INTO teams(team_name, description) VALUES (:team_name, :description)");
        $stmt->bindParam(':team_name', $team_name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->execute();
    }

    //CHECKS FOR TEAM MEMBERS AND ADDING TO TEAM
    if ( isset($_POST['team_name']) && isset($_POST['member_id']) ) {
        //GETS POST VALUES
        $team_name  = $_POST['team_name'];
        $member_id   = $_POST['member_id'];

        //PREPARED STATEMENT
        $stmt = $pdo->prepare("INSERT INTO team_member(team_name, member_id) VALUES (:team_name, :member_id)");
        $stmt->bindParam(':team_name', $team_name, PDO::PARAM_STR);
        $stmt->bindParam(':member_id', $member_id, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt) {
            echo "<script type='text/javascript'>alert('Upload succesfull');</script>";
        }else {
            echo "<script type='text/javascript'>alert('Something went wrong');</script>";
        }
    }

    if (isset($_POST['delete']) && isset($_POST['team_member_id'])) {
        $team_member_id  = $pdo->quote($_POST['team_member_id']);
        $query  = "DELETE FROM team_member WHERE team_member_id=$team_member_id";
        $result = $pdo->query($query);
        //GUARD CLAUSE
        if (!$result) {
            die ('Error after deleting team member id');
        } else {
            echo "<script type='text/javascript'>alert('Deleted successfully');</script>";
        }
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
        <div class="menu menu-3 popup">^ Show my cookies
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
            align-items: center;z
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
            margin-left: -150px;
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