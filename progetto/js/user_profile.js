window.onload = function() {

	var tabs = document.getElementsByClassName("tab_button");
	for(var i=0; i<tabs.length; i++){
		tabs[i].observe("click",tab_clik);
	}
	get_games_list("playing");

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

function clear_list(){
	//clear old list
	while ($("game_list").firstChild) {
		$("game_list").removeChild($("game_list").firstChild);
	}
}

function tab_clik(){
	var tabs = document.getElementsByClassName("tab_button");
	for(var i=0; i<tabs.length; i++){
		if(tabs[i].hasClassName("tab_selected"))
			tabs[i].removeClassName("tab_selected");
	}
	this.addClassName("tab_selected");
	get_games_list(this.title);
}

function get_games_list(listname){
	//first, clear old list
	clear_list();
	var profile_id = $("profile_id").value;
	if(listname==null)
    	listname = this.title;
	new Ajax.Request("lib/request_handlers/profile_handler.php",
		{
			method: "post", 
			parameters: { function: "get_user_games_list",
			              listname: listname,
			              profileid: profile_id, 
			            },
			onSuccess: show_games_list,
			onFailure: handle_failure
		}
	);
}

function show_games_list(ajax){
	var res = JSON.parse(ajax.responseText);
	if(res==null){ //server side error
		show_alert("Sorry, an error occured. Please try again later.");
	}
	else if(res.length==2){ //empty result
		show_alert("No games found in this list");
	}
	else{ //show result list
		var listname = res[res.length-2].listname;
		var can_edit = res[res.length-1].edit;
		for(var i=0; i<res.length-2; i++)
			show_game(res[i],can_edit,listname);
	}
}

function show_alert(message){
	var alert_p = document.createElement("p");
	alert_p.innerHTML = message;
	alert_p.addClassName("alert_p");
	$("game_list").appendChild(alert_p);
}

function show_game(game,can_edit,listname){

	var game_div;
	var title_a;
	var plat_p;
	var img;
	var id;

	game_div = document.createElement("div");
	game_div.id="game_"+game.id;
	game_div.addClassName("game_div");
	game_div.addClassName("animated");
	game_div.addClassName("bounceInUp");
	$("game_list").appendChild(game_div);

	img = document.createElement("img");
	if(game.cover_url!=null)
		img.src = "https://images.igdb.com/igdb/image/upload/t_thumb/"+(game.cover_url);
	else
		img.src="img/default/cover_not_found.jpg";
		img.alt = "game cover";
	game_div.appendChild(img);

	title_a = document.createElement("a");
	title_a.href="game_info.php?id="+game.id;
	title_a.innerHTML=game.title;
	game_div.appendChild(title_a);

	
	plat_p= document.createElement("p");
	plat_p.innerHTML="<strong>Platforms: </strong>"+(game.platforms).replace(/,/g,", ");
	game_div.appendChild(plat_p);

	if(can_edit){
		var buttons_div = document.createElement("div");
		buttons_div.addClassName("state_buttons");
		game_div.appendChild(buttons_div);

		if(game.state!="owned")
			add_button(buttons_div,"owned",game.id);
		if(game.state!="playing")
			add_button(buttons_div,"playing",game.id);
		if(game.state!="finished")
			add_button(buttons_div,"finished",game.id);
		if(game.state!="dropped")
			add_button(buttons_div,"dropped",game.id);
		if(game.state!="wishlist")
			add_button(buttons_div,"wishlist",game.id);
		add_button(buttons_div,"remove",game.id);
	}

	/*$("game_list").appendChild(document.createElement("hr"));*/
}

function add_button(parent,title,value){
	var new_button = document.createElement("button");
	new_button.addClassName("state_button");
	new_button.title=title;
	new_button.value=value;
	switch(title){
		case "all":
			new_button.innerHTML='<i class="fa fa-list" aria-hidden="true"></i>';
			break;
		case "owned":
			new_button.innerHTML='<i class="fa fa-check-square-o" aria-hidden="true"></i>';
			break;
		case "playing":
			new_button.innerHTML='<i class="fa fa-gamepad" aria-hidden="true"></i>';
			break;
		case "finished":
			new_button.innerHTML='<i class="fa fa-trophy" aria-hidden="true"></i>';
			break;
		case "dropped":
			new_button.innerHTML='<i class="fa fa-thumbs-o-down" aria-hidden="true"></i>';
			break;
		case "wishlist":
			new_button.innerHTML='<i class="fa fa-star-o" aria-hidden="true"></i>';
			break;
		case "remove":
			new_button.innerHTML='<i class="fa fa-times" aria-hidden="true"></i>';
			break;
	}
	new_button.observe("click",button_click);
	parent.appendChild(new_button);
}

function button_click() {
	new Ajax.Request("lib/request_handlers/ownership_handler.php",
	{
		method: "post", 
		parameters: { function: "update_ownership_small",
					  gameid: this.value, 
					  state: this.title },
		onSuccess:  handle_success,
		onFailure: handle_failure
	}
	);
}

function handle_success(ajax){
	var res = JSON.parse(ajax.responseText);
	if(res["success"]>=0)
		$("game_list").removeChild( $("game_"+res["success"]) );
	else window.alert(res["success"]);
	//if not successful do nothing, failed to update server side
}


function handle_failure(ajax){
	//do nothing, failed to send the get_list/update request to server
}