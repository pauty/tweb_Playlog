window.onload = function() {

		//add observers for group click
	var groups = document.getElementsByClassName("group_button");
	for(var i=0; i<groups.length; i++){
		groups[i].observe("click",group_clik);
	}

	//add observers for tab click
	var tabs = document.getElementsByClassName("tab_button");
	for(var i=0; i<tabs.length; i++){
		tabs[i].observe("click",tab_clik);
	}

	var follow_btn = $("follow_button");
	if(follow_btn != null)
		follow_btn.observe("click",follow_click);

	//show the default list (playing)
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

function group_clik(){
	//if this isn't already the active group
	if(!this.hasClassName("group_selected")){
		//make this the active tab, removing the 'group_selected' class from another group
		var groups = document.getElementsByClassName("group_button");
		for(var i=0; i<groups.length; i++){
			if(groups[i].hasClassName("group_selected"))
				groups[i].removeClassName("group_selected");
		}
		this.addClassName("group_selected");
	}
}

function tab_clik(){
	//if this isn't already the active tab
	if(!this.hasClassName("tab_selected")){
		//make this the active tab, removing the 'tab_selected' class from another tab
		var tabs = document.getElementsByClassName("tab_button");
		for(var i=0; i<tabs.length; i++){
			if(tabs[i].hasClassName("tab_selected"))
				tabs[i].removeClassName("tab_selected");
		}
		this.addClassName("tab_selected");
		//show the games list wich name is the same as the selected tab 
		if(this.title=="followers" || this.title =="followed"){
			get_users_list(this.title);
		}
		else{
			get_games_list(this.title);
		}
	}
}

function get_games_list(listname){
	//clear old list first
	clear_list();

	var profile_id = $("profile_id").value;
	if(listname == null || profile_id == null)
		return;

    //make an ajax request to get the list specified by listname, owned by the user specified by profile_id
	new Ajax.Request("lib/request_handlers/profile_handler.php",
		{
			method: "post", 
			parameters: { function: "get_games_list",
			              listname: listname,
			              profileid: profile_id, 
			            },
			onSuccess: show_games_list,
			onFailure: handle_failure
		}
	);
}

function get_users_list(listname){
	//clear old list first
	clear_list();

	var profile_id = $("profile_id").value;
	if(listname == null || profile_id == null)
		return;

	new Ajax.Request("lib/request_handlers/profile_handler.php",
		{
			method: "post", 
			parameters: { function: "get_users_list",
			              listname: listname,
			              profileid: profile_id, 
			            },
			onSuccess: show_users_list,
			onFailure: handle_failure
		}
	);
}

function show_users_list(ajax){
	window.scrollTo(0,0);
	var res = JSON.parse(ajax.responseText);
	if(res == null){ //server side error
		show_alert("Sorry, an error occured. Please try again later.");
	}
	else if(res.length==2){ //empty result
		show_alert("No users found in this list");
	}
	else{ 
		//get list name and can_edit flag from ajax response
		var listname = res[res.length-2].listname;
		var can_edit = (res[res.length-1].edit) && listname == "followed"; //user can choose to unfollow 
		//show the list 
		for(var i=0; i<res.length-2; i++)
			show_user(res[i],can_edit);
	}
}

function show_user(user,can_edit){
	var user_div;
	var name_a;
	var img;
	var id;

	user_div = document.createElement("div");
	user_div.id="user_"+user.id;
	user_div.addClassName("user_div");
	user_div.addClassName("animated");
	user_div.addClassName("bounceInUp");
	$("game_list").appendChild(user_div);

	img = document.createElement("img");
	img.src="img/default/default_user.png";
	img.alt = "user pic";
	user_div.appendChild(img);

	name_a = document.createElement("a");
	name_a.href="user_profile.php?id="+user.id;
	name_a.innerHTML=user.username;
	user_div.appendChild(name_a);

	if(can_edit){
		var unfollow_button = document.createElement("button");
		unfollow_button.innerHTML = "Unfollow";
		unfollow_button.value = user.id;
		unfollow_button.observe("click",quick_unfollow_click);
		user_div.appendChild(unfollow_button);
	}
}

function show_games_list(ajax){
	window.scrollTo(0,0);
	var res = JSON.parse(ajax.responseText);
	if(res == null){ //server side error
		show_alert("Sorry, an error occured. Please try again later.");
	}
	else if(res.length==2){ //empty result
		show_alert("No games found in this list");
	}
	else{ 
		//get list name and can_edit flag from ajax response
		var listname = res[res.length-2].listname;
		var can_edit = res[res.length-1].edit;
		//show the list 
		for(var i=0; i<res.length-2; i++)
			show_game(res[i],can_edit,listname);
	}
}

/*create a div for a single game in the list*/
function show_game(game,can_edit,listname){

	var game_div;
	var title_a;
	var plat_p;
	var img;

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

	/*only the user can edit his own lists, and therefore see buttons.
	  server sets the can_edit flag to true only if the session id and profile id are the same*/
	if(can_edit){
		var buttons_div = document.createElement("div");
		buttons_div.addClassName("state_buttons");
		game_div.appendChild(buttons_div);

		if(game.state!="owned")
			add_state_button(buttons_div,"owned",game.id);
		if(game.state!="playing")
			add_state_button(buttons_div,"playing",game.id);
		if(game.state!="finished")
			add_state_button(buttons_div,"finished",game.id);
		if(game.state!="dropped")
			add_state_button(buttons_div,"dropped",game.id);
		if(game.state!="wishlist")
			add_state_button(buttons_div,"wishlist",game.id);
		add_state_button(buttons_div,"remove",game.id);
	}
}

//add a state button to a game div (parent)
function add_state_button(parent,title,value){
	var new_button = document.createElement("button");
	new_button.addClassName("state_button");
	new_button.title=title;
	new_button.value=value;
	//set button icon according to button title
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
	new_button.observe("click",state_button_click);
	parent.appendChild(new_button);
}

function show_alert(message){
	var alert_p = document.createElement("p");
	alert_p.innerHTML = message;
	alert_p.addClassName("alert_p");
	$("game_list").appendChild(alert_p);
}


function follow_click(){
	var profile_id = $("profile_id").value;
	if(profile_id == null)
		return;

	new Ajax.Request("lib/request_handlers/followers_handler.php",
		{
			method: "post", 
			parameters: { function: this.value, //will either call the 'follow' or the 'unfollow' function
			              profileid: profile_id, 
			            },
			onSuccess: switch_follow_button,
			onFailure: handle_failure
		}
	);
}

function switch_follow_button(ajax){
	var res = JSON.parse(ajax.responseText);
	if(res != null && res["success"] >= 0){
		var button = $("follow_button");
		if(button.value == "follow"){
			button.value = "unfollow";
			button.className = "unfollow";
			button.innerHTML = '<i class="fa fa-check" aria-hidden="true"></i>';
			button.title = "Unfollow";
		}
		else{
			button.value = "follow";
			button.className = "follow";
			button.innerHTML = '<i class="fa fa-plus" aria-hidden="true"></i>';
			button.title = "Follow";
		}
	}
	else{
		//window.alert("not successfully followed");
		//if not successful do nothing, failed to update server side
	}
}

function quick_unfollow_click(){
	new Ajax.Request("lib/request_handlers/followers_handler.php",
		{
			method: "post", 
			parameters: { function: "unfollow",
			              profileid: this.value, 
			            },
			onSuccess: handle_quick_unfollow_success,
			onFailure: handle_failure
		}
	);
}

function state_button_click() {
	//make ajax request to update ownership in local database
	new Ajax.Request("lib/request_handlers/ownership_handler.php",
	{
		method: "post", 
		parameters: { function: "quick_update_ownership",
					  gameid: this.value, 
					  state: this.title },
		onSuccess:  handle_ownership_success,
		onFailure: handle_failure
	}
	);
}

function handle_quick_unfollow_success(ajax){
	var res = JSON.parse(ajax.responseText);
	if(res != null && res["success"] >= 0){
		/*the unfollow update was successful. user div can be removed*/
		$("game_list").removeChild( $("user_"+res["success"]) ); //ajax response returns an user id on success
	}
	else{
		//if not successful do nothing, failed to update server side
		window.alert(res["success"]); //for debug
	}
}

function handle_ownership_success(ajax){
	var res = JSON.parse(ajax.responseText);
	if(res != null && res["success"] >= 0){
		/*the update was successful. Game div can be removed, as
		surely the game is no longer in the currently selected list*/
		$("game_list").removeChild( $("game_"+res["success"]) ); //ajax response returns a game id on success
	}
	else{
		//if not successful do nothing, failed to update server side
		window.alert(res["success"]); //for debug
	}
}


function handle_failure(ajax){
	//do nothing, failed to send the get_list/update request to server
	alert("Error making Ajax request:" + "\n\nServer status:\n" +
	ajax.status + " " + ajax.statusText +
	"\n\nServer response text:\n" + ajax.responseText);
}