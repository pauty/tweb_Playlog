FREE_SQUARE_ROW=3;
FREE_SQUARE_COLOUMN=3;
NUM_MOVES=0;

window.onload = function() {

$("shufflebutton").observe("click",shuffle);
init_squares();
shuffle();

}

//performs all the initial setup of the grid
function init_squares() {

var squares = $("puzzlearea").getElementsByTagName("div");
for(var i=0; i<squares.length; i++){
		squares[i].id = "square_"+parseInt(i/4)+"_"+parseInt(i%4);
		squares[i].style.top = parseInt(i/4)*100+"px";
		squares[i].style.left = parseInt(i%4)*100+"px";
		squares[i].style.backgroundPosition = "-"+(parseInt(i%4)*100)+"px"+" -"+(parseInt(i/4)*100)+"px";
		squares[i].observe("mouseover",highlight);
		squares[i].observe("mouseout",undo_highlight);
		squares[i].observe("click",try_move)
	}

}

function highlight() {

if(can_move(this))
	this.addClassName("highlighted");

}

function undo_highlight() {

this.removeClassName("highlighted");

}

function try_move() {

if(can_move(this)){
	move(this);
	NUM_MOVES++;
	if(check_victory())
		show_victory();
	return 1;
}
return 0;

}

/*any result > 0 means square can move:
  1=UP, 2=RIGHT, 3=DOWN, 4=LEFT (unused info)*/
function can_move(elem) {

var r = get_row(elem);
var c = get_coloumn(elem);

if(r==FREE_SQUARE_ROW+1 && c==FREE_SQUARE_COLOUMN)
	return 1; //UP
if(r==FREE_SQUARE_ROW && c==FREE_SQUARE_COLOUMN-1)
	return 2; //RIGHT
if(r==FREE_SQUARE_ROW-1 && c==FREE_SQUARE_COLOUMN)
	return 3; //DOWN
if(r==FREE_SQUARE_ROW && c==FREE_SQUARE_COLOUMN+1)
	return 4; //LEFT
return 0;

}

function get_row(elem){

return parseInt(elem.getStyle("top"))/100;

}

function get_coloumn(elem){

return parseInt(elem.getStyle("left"))/100;

}

function move(elem){

	//calculate row and coloumn number of moving square
	var new_free_r = parseInt(elem.getStyle("top"))/100; 
	var new_free_c = parseInt(elem.getStyle("left"))/100;
	//update moving square position with values of free square
	elem.style.top = FREE_SQUARE_ROW*100+"px";
	elem.style.left = FREE_SQUARE_COLOUMN*100+"px";
	//set the new id of the moving square
	elem.id = "square_"+FREE_SQUARE_ROW+"_"+FREE_SQUARE_COLOUMN;
	//update row and column of free square
	FREE_SQUARE_ROW = new_free_r;
	FREE_SQUARE_COLOUMN = new_free_c;

}

function shuffle(){

	var max = 90+parseInt(Math.random()*30);
	for(var i=0; i<max; i++)
		rand_move();
	//reset moves counter
	NUM_MOVES = 0;
	hide_victory();

}

function rand_move(){

	var r_inc;
	var c_inc;
	var r = FREE_SQUARE_ROW;
	var c = FREE_SQUARE_COLOUMN;

	/*first we randomly choose to fix row or coloumn,
	  then move a random square near the free square */
	if(parseInt(Math.random()*2)==1) {  //fix coloumn
		if(parseInt(Math.random()*2)==1)  //choose between left/right move
			r_inc = 1;
		else 
			r_inc = -1;
		if(FREE_SQUARE_ROW+r_inc<0 || FREE_SQUARE_ROW+r_inc>3) //check for valid move
			r_inc = -r_inc;
		r = FREE_SQUARE_ROW+r_inc;
	}
	else { //fix row
		if(parseInt(Math.random()*2)==1) //choose between left/right move
			c_inc = 1;
		else 
			c_inc = -1;
		if(FREE_SQUARE_COLOUMN+c_inc<0 || FREE_SQUARE_COLOUMN+c_inc>3) //check for valid move
			c_inc = -c_inc;
		c = FREE_SQUARE_COLOUMN+c_inc;
	}
	move($("square_"+r+"_"+c));
	
}

//functions to show/hide the victory banner
function show_victory(){
	var elem = $("victory_banner");
	elem.removeClassName("hidden_elem");
	elem.innerHTML = "Congratulations! You completed the puzzle in "+NUM_MOVES+" moves.";
}

function hide_victory(){
	var elem = $("victory_banner");
	if(!elem.hasClassName("hidden_elem"))
		elem.addClassName("hidden_elem");
}

//returns true if puzzle is complete; false otherwise
function check_victory(){
	var square;
	var ok = true;
	//check for every square if it is in the correct position
	for(var i=0; i<15 && ok; i++){
		square = $("square_"+parseInt(i/4)+"_"+parseInt(i%4));
		if(square == null) //check for free square
			ok = false;
		else
			ok = (parseInt(square.innerHTML) == (i+1));
	}
	return ok;
}