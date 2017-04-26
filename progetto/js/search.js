window.onload = function() {

	if($("header_search_gametitle").value!='')
		search_games();
	else if($("header_search_username").value!='null')
		search_users();

	/*set the event observer for game/user search*/
	$("header_search_gametitle").observe("keydown", function(e){ if(e.keyCode == 13)search_games(); });
    $("header_search_username").observe("keydown", function(e){ if(e.keyCode == 13)search_users(); });
	$("header_search_game_button").observe("click",search_games);
	$("header_search_user_button").observe("click",search_users);

}

function search_games () {
	var gametitle = $("header_search_gametitle").value;
	if(gametitle!=''){
		new Ajax.Request("lib/request_handlers/search_handler.php",
			{
				method: "post", 
				parameters: { function: "search_games_by_title" , gametitle: gametitle },
				onSuccess: show_games_list,
				onFailure: handle_failure
			}
		);
	}
}

function search_users () {
	var username = $("header_search_username").value;
	if(username!=''){
		new Ajax.Request("lib/request_handlers/search_handler.php",
			{
				method: "post", 
				parameters: { function: "search_users_by_name" , username: username },
				onSuccess: show_users_list,
				onFailure: handle_failure
			}
		);
	}
}

function clear_list(){
	//clear old list
	while ($("result_list").firstChild) {
		$("result_list").removeChild($("result_list").firstChild);
	}
}

function show_games_list(ajax){
	clear_list();
	var games = JSON.parse(ajax.responseText);
	var result_list = $("result_list");
	var game_div;
	var title_a;
	var rating_p;
	var img;
	var success_code = games[games.length-1].success;

	if(success_code==1){
		for(var i=0; i<games.length-1; i++){  //the last index is reserved for ajax response success code
			game_div = document.createElement("div");
			game_div.addClassName("game_div");
			game_div.addClassName("animated");
			game_div.addClassName("bounceInUp");
			result_list.appendChild(game_div);

			img = document.createElement("img");
			game_div.appendChild(img);
			if(games[i].cover!=null)
				img.src = "https:"+games[i].cover.url;
			else
				img.src="img/default/cover_not_found.jpg";
			img.alt = "game cover";

			title_a = document.createElement("a");
			game_div.appendChild(title_a);
			title_a.href="game_info.php?id="+games[i].id;
			title_a.innerHTML=games[i].name;

			rating_p = document.createElement("p");
			game_div.appendChild(rating_p);
			if(games[i].rating){
				rating_p.innerHTML="Rated: <span>"+Math.floor(games[i].rating)+"</span>/100";
				if(games[i].rating >= 75)
					rating_p.addClassName("high_rating");
				else if(games[i].rating >= 55)
					rating_p.addClassName("mid_rating");
				else
					rating_p.addClassName("low_rating");
			}
			else{
				rating_p.innerHTML="Not rated";
			}
		}
	}
	else{
		var alert_p = document.createElement("p");
		if(success_code==0)
			alert_p.innerHTML="No result for the searched game";
		else
			alert_p.innerHTML="Sorry, an error has occurred. Please try again later";
		alert_p.addClassName("alert_p");
		result_list.appendChild(alert_p);
	}
}

function show_users_list(ajax){
	clear_list();
	var users = JSON.parse(ajax.responseText);
	var result_list = $("result_list");
	var user_div;
	var user_img;
	var name_a;
	var success_code = users[users.length-1].success;
	
	if(success_code==1){
		for(var i=0; i<users.length-1; i++){ //the last index is reserved for ajax response success code
			user_div = document.createElement("div");
			user_div.addClassName("user_div");
			user_div.addClassName("animated");
			user_div.addClassName("bounceInUp");
			result_list.appendChild(user_div);

			user_img = document.createElement("img");
			user_img.src="img/default/default_user.png";
			user_img.alt="profile_pic";
			user_div.appendChild(user_img);

			name_a = document.createElement("a");
			name_a.href="user_profile.php?id="+users[i].id;
			name_a.innerHTML=users[i].username;
			user_div.appendChild(name_a);
		}
	}
	else{
		var alert_p = document.createElement("p");
		if(success_code==0)
			alert_p.innerHTML="No result for the searched user";
		else
			alert_p.innerHTML="Sorry, an error has occurred. Please try again later";
		alert_p.addClassName("alert_p");
		result_list.appendChild(alert_p);
	}
}

function handle_failure(ajax) {
	alert("Error making Ajax request:" + "\n\nServer status:\n" +
	ajax.status + " " + ajax.statusText +
	"\n\nServer response text:\n" + ajax.responseText);
}