<?php
session_start();
$_SESSION=[];
// reloading page will set the followinh session variables
$_SESSION["initialize"] = 1;
$_SESSION["player1"] = 0;
$_SESSION["player2"] = 0;
$_SESSION["draws"] = 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tic-Tac-Toe</title>
  <link rel="stylesheet" href="stylesTicTacToe.css" />
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
</head>

<body>
  <h1>TIC TAC TOE GAME</h1>
  <div id="tttBoard"></div>
  <p id="message"></p>
  <p id="tally">Current game: Circle: 0 - Cross: 0 - Draws: 0</p> <!-- Added tally display -->
  <p id="summary"><b>Cumulative:</b> Circle: 0 - Cross: 0 - Draws: 0</p> <!-- Added summary display -->
  <script src="appTicTacToe.js"></script>
</body>

</html>