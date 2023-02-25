<?php // logout.php = version 2
  session_start();

  if (isset($_SESSION['forename']))
  {
    $forename = $_SESSION['forename'];
    $surname  = $_SESSION['surname'];

    echo "<div class='logout-block'>";
    echo htmlspecialchars("You're now logged out");
		echo "<br>";
    echo "Please <a href='authenticate.php'>click here</a> to log in.";
    echo "</div>";

    destroy_session_and_data();
  }

  function destroy_session_and_data()
  {
    $_SESSION = array();
    setcookie("currentDate", '', time() - 2592000);
    setcookie("userForename", '', time() - 2592000);
    setcookie("userSurname", '', time() - 2592000);
    setcookie("PHPSESSID", '', time() - 2592000);
  }
?>

<style>
  .logout-block {
    font-family:'Roboto';
    font-size: 16px;
  }
</style>