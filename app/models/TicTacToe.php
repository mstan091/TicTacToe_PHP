<?php
//session_start();
class TicTacToe
{
  // Properties
  public $board;
  public $winningArray;

  function __construct()
  {
    // define the board array
    $this->board = array(
      "0" => "",
      "1" => "",
      "2" => "",
      "3" => "",
      "4" => "",
      "5" => "",
      "6" => "",
      "7" => "",
      "8" => ""
    );
  }

  // Methods
  function setMove($boardId, $move)
  {
    $this->board[$boardId] = $move;
    $result = $this->calculateScore();
    return $result;
  }

  function calculateScore()
  {
    $count = 0;
    $isDraw = true; // Flag to check if the game is a draw

    // List all the winning combinations
    $winComb = [
      [0, 1, 2],
      [3, 4, 5],
      [6, 7, 8],
      [0, 3, 6],
      [1, 4, 7],
      [2, 5, 8],
      [0, 4, 8],
      [2, 4, 6],
    ];

    // Check for circle wins
    foreach ($winComb as $indexArray) {
      $count = 0;
      foreach ($indexArray as $cell) {
        $currentValue = $this->board[$cell];
        if (str_contains($currentValue, "circle")) {
          $count++;
        }
        ;
      }
      ;
      if ($count === 3) {
        $this->winningArray = $indexArray;
        // Update the session and increase the score
        // score is reset in the API
        $_SESSION["player1"] = $_SESSION["player1"] + 1;

        // Reset values after 10 games 
        if (($_SESSION["player1"] + $_SESSION["player2"] + $_SESSION["draws"]) > 10) {
          $_SESSION["player1"] = 1;
          $_SESSION["player2"] = 0;
          $_SESSION["draws"] = 0;
        }
        ;
        return ("circleWon");
      }
      ;
    }
    ;

    // Check for cross wins
    foreach ($winComb as $indexArray) {
      $count = 0;
      foreach ($indexArray as $cell) {
        $currentValue = $this->board[$cell];
        if (str_contains($currentValue, "cross")) {
          $count++;
        }
        ;
      }
      ;
      if ($count === 3) {
        $this->winningArray = $indexArray;
        $_SESSION["player2"] = $_SESSION["player2"] + 1;
        if (($_SESSION["player1"] + $_SESSION["player2"] + $_SESSION["draws"]) > 10) {
          $_SESSION["player1"] = 0;
          $_SESSION["player2"] = 1;
          $_SESSION["draws"] = 0;
        }
        ;
        return ("crossWon");
      }
      ;
    }
    ;

    // Check for a draw
    if ($isDraw) {
      // Check if all squares are filled:
      $countFilled = 0;
      foreach ($this->board as $k => $v) {
        if (strlen($v) !== 0) {
          $countFilled++;
        } else {
          return ("keepPlaying");
        }
        ;
      }
      ;
      if ($countFilled === 9) {
        $_SESSION["draws"] = $_SESSION["draws"] + 1;
        if (($_SESSION["player1"] + $_SESSION["player2"] + $_SESSION["draws"]) > 10) {
          $_SESSION["player1"] = 0;
          $_SESSION["player2"] = 0;
          $_SESSION["draws"] = 1;
        }
        ;
        return ("isDraw");
      }
      ;
    }
    ;
  }
}

