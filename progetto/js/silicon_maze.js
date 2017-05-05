var NUM_ROWS = 8;
var NUM_COLOUMNS = 12;
var CHECK_DELAY = 3500; 
var START_DELAY = 20000;
var LEVEL = 0;
var SCORE = 0;
var PLAYING = false;
var TIMER = null;
var MAX_DELAY = Number.MAX_VALUE;
var NEXT_ROW;
var NEXT_COLOUMN;
var EXPECTED_DIR;
var RECORD = 0;

window.onload = function(){
	$("start_game_button").observe("click",start_game);
	$("ff_button").observe("click", function() { if(!PLAYING) return; MAX_DELAY=25; clearTimeout(TIMER); check_path(); } );

	if (typeof(window.localStorage) !== "undefined" && window.localStorage.playlog_record != null) {
    		RECORD = parseInt(window.localStorage.playlog_record);
	} 

	/*set the event observer for game/user search*/
	$("header_search_gametitle").observe("keydown", function(e){ if(e.keyCode == 13)submit_search_game(); });
    $("header_search_username").observe("keydown", function(e){ if(e.keyCode == 13)submit_search_user(); });
	$("header_search_game_button").observe("click",submit_search_game);
	$("header_search_user_button").observe("click",submit_search_user);
}

function submit_search_game(){
	if($("header_search_gametitle").value!='')
		location.href="search.php?gametitle="+($("header_search_gametitle").value);
}

function submit_search_user(){
	if($("header_search_username").value!='')
		location.href="search.php?username="+($("header_search_username").value);
}

function clear_panel(){
	while ($("game_panel").firstChild) {
		$("game_panel").removeChild($("game_panel").firstChild);
	}
}

function clear_next(){
	while ($("next_squares").firstChild) {
		$("next_squares").removeChild($("next_squares").firstChild);
	}
}

function start_game(){
	clear_panel();
	clear_next();

	init_panel();
	init_start_end();
	init_broken();
	init_next();
	$("level").innerHTML = "<strong>Level: </strong>"+(LEVEL+1);
	$("timer").innerHTML = "<strong>Setup Time: </strong>";
	$("score").innerHTML = "<strong>Score: </strong>"+SCORE;
	TIMER = setTimeout(update_timer,100);

	PLAYING=true;
}

function init_panel(){
	var panel_div = $("game_panel");
	var square;
	for(var r=0; r<NUM_ROWS; r++){
		for(var c=0; c<NUM_COLOUMNS; c++){
			square=document.createElement("div");
			square.id=r+"_"+c;
			square.addClassName("square_div");
			square.addClassName("c"+Math.floor(Math.random()*22));
			square.observe("click",square_click);
			panel_div.appendChild(square);
		}
	}
}

function new_rand_square(){
	var new_square = document.createElement("div");
	new_square.addClassName("square_div");
	switch(Math.floor(Math.random()*7)){
		case 0:
			new_square.addClassName("down");
			new_square.addClassName("right");
			break;
		case 1:
			new_square.addClassName("down");
			new_square.addClassName("left");
			break;
		case 2:
			new_square.addClassName("up");
			new_square.addClassName("right");
			break;
		case 3:
			new_square.addClassName("up");
			new_square.addClassName("left");
			break;
		case 4:
			new_square.addClassName("left");
			new_square.addClassName("right");
			break;
		case 5:
			new_square.addClassName("up");
			new_square.addClassName("down");
			break;
		case 6:
			new_square.addClassName("up");
			new_square.addClassName("down");
			new_square.addClassName("left");
			new_square.addClassName("right");
			break;
	}
	return new_square;
}

function init_broken(){
	var broken_count=0;
	var limit=0;
	var row;
	var coloumn;
	var square;
	var max_broken=4+LEVEL;

	if(max_broken>12)
		max_broken=12;

	while(broken_count<max_broken && limit<100){
		row=Math.floor(Math.random()*NUM_ROWS);
		coloumn=Math.floor(Math.random()*NUM_COLOUMNS);
		square=$(row+"_"+coloumn);
		if(!square.hasClassName("start") && !square.hasClassName("end") && check_broken_position(row,coloumn)){
			square.addClassName("broken");
			broken_count++;
		}
		limit++;
	}
}

/*return true if the specified position is valid for a broken tile,
  false otherwise*/
function check_broken_position(row,coloumn){
	if($((row-1)+"_"+coloumn)!=null && $((row-1)+"_"+coloumn).hasClassName("down"))
		return false;
	if($((row+1)+"_"+coloumn)!=null && $((row+1)+"_"+coloumn).hasClassName("up"))
		return false;
	if($(row+"_"+(coloumn-1))!=null && $(row+"_"+(coloumn-1)).hasClassName("right"))
		return false;
	if($(row+"_"+(coloumn+1))!=null && $(row+"_"+(coloumn+1)).hasClassName("left"))
		return false;
	return true;
}

/*
randomly create a start and an end point
*/
function init_start_end(){
	var p1,p2;
	var row_left=Math.floor(Math.random()*NUM_ROWS);
	var row_right=Math.floor(Math.random()*NUM_ROWS);
	var coloumn_left=Math.floor(Math.random()*3);
	var coloumn_right=NUM_COLOUMNS-Math.floor(Math.random()*3+1);

	if(Math.floor(Math.random()*2)%2 == 0){
		p1 = "start";
		p2 = "end";
		NEXT_ROW = row_left;
		NEXT_COLOUMN = coloumn_left;
	}
	else{
		p1 = "end";
		p2 = "start";
		NEXT_ROW = row_right;
		NEXT_COLOUMN = coloumn_right;
	}
	$(row_left+"_"+coloumn_left).addClassName(p1);
	$(row_left+"_"+coloumn_left).addClassName(get_direction(row_left,coloumn_left));
	$(row_right+"_"+coloumn_right).addClassName(p2);
	$(row_right+"_"+coloumn_right).addClassName(get_direction(row_right,coloumn_right));
}

/*
get a random initial direction for start/end point
wich is compatible with their positions on the grid
*/
function get_direction(row,coloumn){
	var dir;
	switch(Math.floor(Math.random()*4)){
		case 0:
			dir="up";
			break;
		case 1:
			dir="right";
			break;
		case 2:
			dir="down";
			break;
		case 3:
			dir="left";
			break;
	}
	if(row==0 && dir=="up")
		dir="down";
	else if(row==NUM_ROWS-1 && dir=="down")
		dir="up";
	if(coloumn==0 && dir=="left")
		dir="right";
	else if(coloumn==NUM_COLOUMNS-1 && dir=="right")
		dir="left";
	return dir;
}

/*
ititialize the next square area, creating 4 new pieces
*/
function init_next(){
	var next_div = $("next_squares");
	for(var i = 0; i < 4; i++){
		next_div.appendChild(new_rand_square());
	}
}

function update_next_squares(){
	var next_div = $("next_squares");
	next_div.removeChild(next_div.lastChild);
	var new_square = new_rand_square();
	new_square.addClassName("animated");
	new_square.addClassName("slideInDown");
	next_div.insertBefore(new_square,next_div.firstChild);
}

function square_click(){
	
	if(!PLAYING || this.hasClassName("start")||this.hasClassName("end")||this.hasClassName("broken")||this.hasClassName("active") ||this.hasClassName("dead_end"))
		return;

	var old_el= this;
	var new_el = old_el.cloneNode(true);
	old_el.parentNode.replaceChild(new_el, old_el);
	new_el.className = $("next_squares").lastChild.className;
	new_el.removeClassName("slideInDown");
	new_el.addClassName("zoomIn");
	new_el.addClassName("fast_animate");
	new_el.observe("click",square_click);
	update_next_squares();
}

function check_path(){
	var row = NEXT_ROW;
	var coloumn = NEXT_COLOUMN;
	var expected_dir = EXPECTED_DIR;
	var current_square=$(row+"_"+coloumn);
	var next_dir;

	if(current_square.hasClassName("start")){
			if(current_square.hasClassName("up")){
			TIMER = setTimeout(check_path,Math.min(CHECK_DELAY,MAX_DELAY));
			NEXT_ROW = row-1;
			NEXT_COLOUMN = coloumn;
			EXPECTED_DIR = "down";
		}
		else if(current_square.hasClassName("down")){
			TIMER = setTimeout(check_path,Math.min(CHECK_DELAY,MAX_DELAY));
			NEXT_ROW = row+1;
			NEXT_COLOUMN = coloumn;
			EXPECTED_DIR = "up";
		}
		else if(current_square.hasClassName("left")){
			TIMER = setTimeout(check_path,Math.min(CHECK_DELAY,MAX_DELAY));
			NEXT_ROW = row;
			NEXT_COLOUMN = coloumn-1;
			EXPECTED_DIR = "right";
		}
		else if(current_square.hasClassName("right")){
			TIMER = setTimeout(check_path,Math.min(CHECK_DELAY,MAX_DELAY));
			NEXT_ROW = row;
			NEXT_COLOUMN = coloumn+1;
			EXPECTED_DIR = "left";
		}

		activate_square(current_square);
	}
	else{ 
		if(current_square.hasClassName(expected_dir)){
			if(current_square.hasClassName("end")){
				PLAYING=false;
				activate_square(current_square);
				setTimeout(level_finished,2000);
			}
			else{
				next_dir=get_next_dir(current_square,expected_dir);
				if((row==0 && next_dir=="down") || (row==NUM_ROWS-1 && next_dir=="up") 
					|| (coloumn==0 && next_dir=="right") || (coloumn==NUM_COLOUMNS-1 && next_dir=="left")){
					PLAYING=false;
					current_square.addClassName("dead_end");
					current_square.innerHTML='<p><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <br>DEAD END</p>';
					setTimeout(game_over,4000);	
				}
				else{
					activate_square(current_square,next_dir);
					switch(next_dir){
						case "up":
							TIMER = setTimeout(check_path,Math.min(CHECK_DELAY,MAX_DELAY));
							NEXT_ROW = row+1;
							NEXT_COLOUMN = coloumn;
							break;
						case "down":
							TIMER = setTimeout(check_path,Math.min(CHECK_DELAY,MAX_DELAY));
							NEXT_ROW = row-1;
							NEXT_COLOUMN = coloumn;
							break;
						case "left":
							TIMER = setTimeout(check_path,Math.min(CHECK_DELAY,MAX_DELAY));
							NEXT_ROW = row;
							NEXT_COLOUMN = coloumn+1;
							break;
						case "right":
							TIMER = setTimeout(check_path,Math.min(CHECK_DELAY,MAX_DELAY));
							NEXT_ROW = row;
							NEXT_COLOUMN = coloumn-1;
							break;
					}
					EXPECTED_DIR = next_dir;
				}
			}
		}
		else{
			PLAYING=false;
			current_square.addClassName("dead_end");
			current_square.innerHTML='<p><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <br>DEAD END</p>';
			setTimeout(game_over,4000);	
		}
	}
}

function activate_square(square, next_dir = null){
	if(square.hasClassName("up") && square.hasClassName("down") && square.hasClassName("left")){
		if(square.hasClassName("active")){
			square.removeClassName("horizontal");
			square.removeClassName("vertical");
			square.addClassName("both");
			update_score(40);
		}
		else{
			square.addClassName("active");
			if(next_dir=="up" || next_dir=="down")
				square.addClassName("vertical");
			else
				square.addClassName("horizontal");
		}
	}
	else{
		square.addClassName("active");
	}
	if(!square.hasClassName("start"))
		update_score(20);
}

function get_next_dir(square,source){
	var next_dir;
	switch(source){
		case "up":
			if(square.hasClassName("down"))
				next_dir="up";
			else if(square.hasClassName("right"))
				next_dir="left";
			else 
				next_dir="right";
			break;
		case "down":
			if(square.hasClassName("up"))
				next_dir="down";
			else if(square.hasClassName("right"))
				next_dir="left";
			else 
				next_dir="right";
			break;
		case "left":
			if(square.hasClassName("right"))
				next_dir="left";
			else if(square.hasClassName("up"))
				next_dir="down";
			else 
				next_dir="up";
			break;
		case "right":
			if(square.hasClassName("left"))
				next_dir="right";
			else if(square.hasClassName("up"))
				next_dir="down";
			else 
				next_dir="up";
			break;
	}
	return next_dir;
}

function update_score(points){
	SCORE+=points;
	$("score").innerHTML="<strong>Score: </strong>"+SCORE;
}

function update_timer(){
	START_DELAY-=100;
	$("timer").innerHTML="<strong>Setup Time: </strong>"+(START_DELAY/1000);
	if(START_DELAY == 0 || MAX_DELAY == 0){
		check_path();
	}
	else
		TIMER=setTimeout(update_timer,100);
}

function game_over(){

	var record_text="";

	if(RECORD<SCORE){
		record_text = '<h2 class="yellow">New Record!</h2>';
		RECORD = SCORE;
		if(typeof(window.localStorage) !== "undefined")
			window.localStorage.playlog_record = SCORE;
	}

	clear_panel();

	var game_div=$("game_panel");

	var text_div=document.createElement("div");
	text_div.id="text_panel";
	text_div.innerHTML="<h1>GAME OVER</h1><h2>Your score: "+SCORE+"</h2><h2>Record: "+RECORD+"</h2>"+record_text;
	text_div.addClassName("animated");
	text_div.addClassName("bounceInUp");
	game_div.appendChild(text_div);
	
	var start_button=document.createElement("button");
	start_button.innerHTML="PLAY AGAIN";
	start_button.observe("click",start_game);
	text_div.appendChild(start_button);


	LEVEL=0;
	SCORE=0;
	update_score(0);
	START_DELAY=20000;
	CHECK_DELAY=3500;
	MAX_DELAY = Number.MAX_VALUE;

}

function level_finished(){
	clear_panel();

	var game_div=$("game_panel");

	var text_div=document.createElement("div");
	text_div.id="text_panel";
	text_div.innerHTML="<h1>Congratulations!</h1><h2>Level "+(LEVEL+1)+" complete</h2>";
	text_div.addClassName("animated");
	text_div.addClassName("bounceInUp");
	game_div.appendChild(text_div);
	
	var start_button=document.createElement("button");
	start_button.innerHTML="NEXT LEVEL";
	start_button.observe("click",start_game);
	text_div.appendChild(start_button);


	LEVEL++;
	MAX_DELAY = Number.MAX_VALUE;
	START_DELAY=20000-(1000*LEVEL);
	if(START_DELAY<2000)
		START_DELAY=2000;
	CHECK_DELAY-=100;
	if(CHECK_DELAY<500)
		CHECK_DELAY=500;
}