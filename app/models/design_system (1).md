# Implementation design
* The TicTacToe was implemented in PHP with AJAX calls
* To run the application you will need the following folder structure - folder TicTacToe_PHP must be present under the htdocs folder

![Cross Before](/docs/design_system/folder_struct_1.jpg)  

* Under the TicTacToe_PHP folder there are the folder/files required for the application

![Cross Before](/docs/design_system/folder_struct_2.jpg)

# _config.php
* The file loading mechanism is updated accordingly: 

```
function resolve_path($name)
{
    if ($name == ".")
    {
        $publicRoot = $_SERVER["DOCUMENT_ROOT"] . "/..";
        $appRoot = $_SERVER["DOCUMENT_ROOT"];
    }
    else if ($_SERVER["DOCUMENT_ROOT"] != "")
    {
        $publicRoot = $_SERVER["DOCUMENT_ROOT"] . "/TicTacToe_PHP/../$name";
        $appRoot = $_SERVER["DOCUMENT_ROOT"] . "/TicTacToe_PHP/$name";
    }
    else
    {
        return "../{$name}";
    }

    return file_exists($publicRoot) ? realpath($publicRoot) : realpath($appRoot);
}
```
# index.php
* In the ```<head>``` section we are loading the jquery script
* The file index.php gets executed and calls the appTicTacToe.js file
  
# TicTacToe API
* The game is implemented by the PHP api named ```ttt_api.php```. 
* The API provides two actions: ```createGame``` and ```registerMove```. 
* The API actions return data in JSON format
* The ```registerMove``` action is called every time the user clicks on one square. The call to the API is made using an ```jquery``` call from the Javascript : 
  
``` 
$.ajax({
    type: "GET",
    url: "ttt_api.php?action=registerMove",
    data: { register: move, boardId: e.target.id },
    datatype: 'html',
    success: function (data) {
      // console.log("DATA from squareCliekd ", $.parseJSON(data));
      // console.log("BOARD: ", data.board);
      // console.log("GameOVER: ", data.gameOver);
      // console.log("WinningArray: ", data.winningArray);
      if (data.gameOver === "circleWon") {
        message.innerHTML = "CIRCLE IS THE WINNER!";
        highlightWinningSquares(data.winningArray);
        allSquares.forEach((square) =>
          square.removeEventListener("click", squareClicked)
        );
        //circleWins++; // Update circle wins tally
        circleWins = 1;
        updateTally(); // Update tally display
        updateSummary(data.player1, data.player2, data.draws);
        showPlayAgainButton();
      }
      if (data.gameOver === "crossWon") {
        message.innerHTML = "CROSS IS THE WINNER!";
        highlightWinningSquares(data.winningArray);
        allSquares.forEach((square) =>
          square.removeEventListener("click", squareClicked)
        );
        //circleWins++; // Update circle wins tally
        crossWins = 1;
        updateTally(); // Update tally display
        updateSummary(data.player1, data.player2, data.draws);
        showPlayAgainButton();
      }
      if (data.gameOver === "isDraw") {
        draws = 1;
        updateTally(); // Update tally display
        updateSummary(data.player1, data.player2, data.draws);
        showPlayAgainButton();
      }
    },
    error: function (jqXHR, textstatus, error) {
      console.log(error);
      console.log(textstatus);
      console.log(jqXHR);
    },
  });
  ```

* All the game functionality is implemented in PHP through the PHP api which calls the PHP class ```TicTacToe.php```

# PHP Class TicTacToe.php
* The class TicTacToe.php implements the methods ```setMove``` and ```calculateScore```
* The constructor builds the initial game board
``` 
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
```
# $_SESSION global variables
* To pass variables between PHP components and to be able to reset the game after 10 sessions we are using ```$_SESSION[]``` global variable:

```
// Reset values after 10 games 
        if (($_SESSION["player1"] + $_SESSION["player2"] + $_SESSION["draws"]) > 10) {
          $_SESSION["player1"] = 1;
          $_SESSION["player2"] = 0;
          $_SESSION["draws"] = 0;
        }
```



