<?php
require_once ('_config.php');
session_start();

header("Content-Type: application/json");

switch ($_GET["action"]) {
  case "createGame":
    if (isset($_SESSION["initialize"])) {
      $ttt = new TicTacToe();
      $_SESSION["ttt"] = $ttt;
      echo json_encode($ttt->board);
    }
    ;
    break;
  case "registerMove":
    // $move is circle or cross
    $move = $_GET["register"];
    // $boardId is the id of the clicked square
    $boardId = $_GET["boardId"];
    // Get the TicTacToe object
    $ttt = $_SESSION["ttt"];
    //setMove will update the board and check the score
    $result = $ttt->setMove($boardId, $move);

    // Prepare the response data
    $data = array();
    $data["board"] = $ttt->board;
    $data["gameOver"] = $result;
    if (isset($ttt->winningArray)) {
      $data["winningArray"] = $ttt->winningArray;
    }
    ;
    $data["player1"] = $_SESSION["player1"];
    $data["player2"] = $_SESSION["player2"];
    $data["draws"] = $_SESSION["draws"];
    echo json_encode($data);

    break;
}
?>