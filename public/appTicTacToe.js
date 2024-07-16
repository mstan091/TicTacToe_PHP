const tttBoard = document.querySelector("#tttBoard");
console.log(tttBoard);
const message = document.querySelector("#message");
console.log(message);
const tallyDisplay = document.querySelector("#tally");
const summaryDisplay = document.querySelector("#summary");
const boardCells = ["", "", "", "", "", "", "", "", ""];

let move = "circle";
let circleWins = 0;
let crossWins = 0;
let draws = 0;

// Set initial message
message.innerHTML = "Circle starts";

function createGame() {
  boardCells.forEach((_cell, index) => {
    // for each cell element create a div
    const boardElement = document.createElement("div");
    // for each div add a class "square"
    boardElement.classList.add("square");
    // for each div set the id=index
    boardElement.id = index;
    // for *each* div add event listener "click" which calls addGo()
    boardElement.addEventListener("click", squareClicked);
    // append
    tttBoard.appendChild(boardElement);
  });
  $.ajax({
    type: "GET",
    url: "ttt_api.php?action=createGame",
    // data: { register: move, boardId: e.target.id},
    success: function (data) {
      console.log(data);
    },
    error: function (jqXHR, textstatus, error) {
      console.log(error);
      console.log(textstatus);
      console.log(jqXHR);
    },
  });
}

function squareClicked(e) {
  // console.log(e.target); // contains the square which was pressed
  // Create the div and give it the class "circle" or "cross"
  const divDisplay = document.createElement("div");
  divDisplay.classList.add(move);
  e.target.append(divDisplay);

  const allSquares = document.querySelectorAll(".square");

  // Ajax call to register the move
  //AJAX call to store the results
  $.ajax({
    type: "GET",
    url: "ttt_api.php?action=registerMove",
    data: { register: move, boardId: e.target.id },
    datatype: 'html',
    success: function (data) {
      // console.log("DATA from squareCliekd ", $.parseJSON(data));
      console.log("BOARD: ", data.board);
      console.log("GameOVER: ", data.gameOver);
      console.log("WinningArray: ", data.winningArray);
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

  move = move === "circle" ? "cross" : "circle"; // change from circle to cross
  // console.log(move);
  message.innerHTML = "Next is " + move + "'s turn";
  // Need to remove the eventListener so that we don't click twice on the same square.
  e.target.removeEventListener("click", squareClicked); // remove event listener, you cannot click two times on the same div

  // verifyScore();
}

function showPlayAgainButton() {
  // Remove any existing play again button before creating a new one
  const existingButton = document.querySelector("#playAgainButton");
  if (!existingButton) {
    const playAgainButton = document.createElement("button");
    playAgainButton.id = "playAgainButton";
    playAgainButton.innerHTML = "Play again?";
    playAgainButton.addEventListener("click", resetGame);
    document.body.appendChild(playAgainButton);
  }
}

function resetGame() {
  // Clear the board and reset the message
  tttBoard.innerHTML = "";
  message.innerHTML = "Circle starts";
  move = "circle";
  circleWins = 0;
  crossWins = 0;
  draws = 0;
  updateTally();

  // Remove the Play Again button
  const playAgainButton = document.querySelector("#playAgainButton");
  if (playAgainButton) {
    playAgainButton.remove();
  }

  // Recreate the game board
  createGame();
}

function updateTally() {
  tallyDisplay.innerHTML = `Current game: Circle: ${circleWins} - Cross: ${crossWins} - Draws: ${draws}`;
}

function updateSummary(player1, player2, draws) {
  summaryDisplay.innerHTML = `<b>Cumulative:</b> Circle: ${player1} - Cross: ${player2} - Draws: ${draws}`;
}

function highlightWinningSquares(winningArray) {
  winningArray.forEach((index) => {
    const square = document.getElementById(index);
    square.classList.add("confetti"); // Add confetti class to the winning squares
  });
}

createGame();
