# Implementation design
* The HTML code consists only of two elements: one `<div id="tttBoard"></div>` and one paragraph `<p id="message"></p>`.
* The `<div id="tttBoard"></div>` element is used for the game board.
* The paragraph is used to display the information messages.
* **The whole HTML code for the game is generated in the JavaScript code and styled in CSS**

```   
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tic-Tac-Toe</title>
    <link rel="stylesheet" href="stylesTicTacToe.css" />
  </head>
  <body>
    <div id="tttBoard"></div>
    <p id="message"></p>
    <script src="appTicTacToe.js"></script>
  </body>
</html>
```
---
# The game board
The game board is generated based on an array 3X3: for each element of the array: 
* dynamically generate a `<div></div>` having the class .square
* each `<div></div>` has the id=index of the array
* at the same time we addEventListener(“click”, SquareClicked) for each div
* each `<div></div>` is appended as a child for the game board
```
const boardCells = [
  "","","",
  "","","",
  "","",""
];
function createGame() {
    boardCells.forEach((_cell,index) => {
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
    })
}
```
---
# Starting the game
The game is controlled by the variable ‘move’ with an initial value = ‘Circle’
* When a square is clicked, the function associated to the event listener will create another div having the class either “circle” or “cross”. 
* This new div is a child of the square which was just clicked
    * Based on its class=”circle/cross” we are styling the div accordingly
* After setting the ‘move’ variable to it’s complementary value (if it was circle it becomes cross and if it was cross it becomes circle) we are removing the event listener to avoid double-clicking on the same square. 
* Before exiting the function we are checking the score
```
function squareClicked(e) {
  // console.log(e.target); // contains the square which was pressed
  // Create the div and give it the class "circle" or "cross"
  const divDisplay = document.createElement('div');
  divDisplay.classList.add(move);
  e.target.append(divDisplay);
  move = move === "circle" ? "cross" : "circle" // change from circle to cross
  // console.log(move);
  message.innerHTML = "Next is " + move + "'s turn";
  // Need to remove the eventListener so that we don;t click twice on the same square.
  e.target.removeEventListener("click", squareClicked); // remove event listener, you cannot click two times on the same div
  verifyScore();
}
```
---
# Verifying the score
* The main idea behind checking the score is to list all the winning combinations in an array and subsequently to check if, for each array index of  winning combination we have a div having the exact same class. 
* If we count 3 `<div></div>` having the same class, then the game is won by the respective class (circle or cross).
```
function verifyScore() {
  // Get all the squares from the board 
  const allSquares = document.querySelectorAll(".square");
  let count=0;
  // list all the winning combinations
  const winComb = [
    [0,1,2], [3,4,5], [6,7,8],
    [0,3,6], [1,4,7], [2,5,8],
    [0,4,8], [2,4,6]
  ]

  winComb.forEach(array => {
    // Verify for each index of the array if it contains *only* circles
    count=0;
    array.forEach(cell => {
      // For each cell, verify allSquares[cell] is if has a child and if the child is circle
      squareChild=allSquares[cell].firstChild;
      if (squareChild) {
        if (squareChild.classList.contains("circle")){
          count=count+1;
        }
      } 
    })
    if (count == 3) {
      message.innerHTML = "CIRCLE IS THE WINNER!";
      allSquares.forEach(square => square.replaceWith(square.cloneNode(true)));
    }
  })

  winComb.forEach(array => {
    // Verify for each index of the array if it contains *only* circles
    count=0;
    array.forEach(cell => {
      // For each cell, verify allSquares[cell] is if has a child and if the child is circle
      squareChild=allSquares[cell].firstChild;
      if (squareChild) {
        if (squareChild.classList.contains("cross")){
          count=count+1;
        }
      } 
    })
    if (count == 3) {
      message.innerHTML = "CROSS IS THE WINNER!";
      allSquares.forEach(square => square.replaceWith(square.cloneNode(true)));
    }
  })
}
```
---
# Styling the circle & cross

The width and the height of a square is 100px. 
We decided that the width and the height of the circle/cross is 60px. 
---
## CIRCLE
We used box-sizing: border-box to include the padding and the border in the width and the height. 
```
.circle {
  height: 60px;
  width: 60px;
  border-radius: 50%;
  border: 5px solid red;
  background-color:red;
  box-sizing: border-box;
}
```

---
## CROSS
* For the cross we are using a position:relative and the same width and height as the circle. 
* ***To draw the cross*** we use: transform: rotate(45deg) which results in a rotation 45 degrees and will in fact represent a cross as an “X”. 
* With ***cross:before*** we are drawing the vertical element of the cross: 
```
.cross:before {
  left: 50%;
  width: 30%;
  height: 100%;
  margin-left: -15%;
}
```
![Cross Before](/docs/design_system/cross_before.jpg)
* With ***cross: after*** we are drawing the horizontal element of the cross: 
```
.cross:after {
  top:50%;
  margin-top: -15%;
  width: 100%;
  height: 30%;
}
```
![Cross After](/docs/design_system/cross_after.jpg)

* Putting it all together and rotating it: 

```
.cross {
  height: 60px;
  width: 60px;
  position:relative;
  transform: rotate(45deg);
}

.cross:before, .cross:after {
  content: "";
  position:absolute;
  background-color: black;
}
```
![Cross](/docs/design_system/cross.jpg)





