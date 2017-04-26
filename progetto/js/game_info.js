var PLAYLOG_CHECKBOXES_CHANGED=false;

window.onload = function() {

	if($("state_buttons")!=null){
		init_buttons();

		var checks = $("platforms_list").children;
		for(var i=0; i < checks.length; i++)
			checks[i].observe("change",new_pending_check);
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

function new_pending_check(){
	this.addClassName("pending_check");
	PLAYLOG_CHECKBOXES_CHANGED=true;
}

function init_buttons(){
	var game_id = $("game_id").value;
	new Ajax.Request("lib/request_handlers/ownership_handler.php",
	{
		method: "post", 
		parameters: { function: "get_ownership", gameid: game_id },
		onSuccess: show_buttons,
		onFailure: handle_failure
	}
	);
}

function show_buttons(ajax) {
	var res = JSON.parse(ajax.responseText);
	if(res==null || res.length==0) //something went wrong server side, so we do show nothing
		return;
	if(res["success"]!=null && !res["success"])
		return;
	
	if(res["state"]==null)
		return;
	else
		var state=res["state"];

	clear_buttons();
	show_button("owned",(state=="owned"));
	show_button("playing",(state=="playing"));
	show_button("finished",(state=="finished"));
	show_button("dropped",(state=="dropped"));
	show_button("wishlist",(state=="wishlist"));
	if(state!="none" && state!="remove")
		show_button("remove",false);
	
	//check the platforms boxes
	if(res["platforms"]!=null){
		var platform_checks=document.getElementsByClassName("platform_checkbox");
		var plats_to_check=res["platforms"].split(",");
		for(var i=0; i<platform_checks.length ; i++){
			if(plats_to_check.indexOf(platform_checks[i].value)>=0){
				platform_checks[i].checked = true;
			}
		}
	}

	var checks = $("platforms_list").children;
	for(var i=0; i < checks.length; i++)
		checks[i].removeClassName("pending_check");

	PLAYLOG_CHECKBOXES_CHANGED=false;

}

function clear_buttons(){
	while ($("state_buttons").hasChildNodes()) {
		$("state_buttons").removeChild($("state_buttons").firstChild);
	}
}

function handle_button_click() {
	var game_id = $("game_id").value;
	var gametitle = $("gametitle").innerHTML;
	var platforms = [];
	var platform_checks=document.getElementsByClassName("platform_checkbox");
	var alert_p;
	var state = this.title;
	var at_least_one = false || (state == "remove"); //if the user selected "remove" we don't need a platform

	for(var i=0; i<platform_checks.length; i++){
		if(platform_checks[i].checked){
			platforms.push(platform_checks[i].value);
			at_least_one=true;
		}
	}
	if(!at_least_one){
		alert_p = $("alert_p");
		if(alert_p == null){
			alert_p = document.createElement("p");
			alert_p.id="alert_p";
			alert_p.innerHTML="Please select at least one platform before adding to a list";
			$("right_coloumn").appendChild(alert_p);
		}
		return;
	}
	else{
		alert_p = $("alert_p");
		if(alert_p != null){
			$("right_coloumn").removeChild(alert_p);
		}
	}

	//check if at least one change (game list/platforms) was made. If not, we do nothing
	if(this.hasClassName("state_selected") && !PLAYLOG_CHECKBOXES_CHANGED)
		return;
	

	var platforms_str=platforms.join(",");
	var cover_url = $("cover_url").value;

	new Ajax.Request("lib/request_handlers/ownership_handler.php",
	{
		method: "post", 
		parameters: { function: "update_ownership",
					  gameid: game_id, 
					  gametitle: gametitle,
					  platforms: platforms_str,
					  state: state,
					  coverurl: cover_url },
		onSuccess:  show_buttons,
		onFailure: handle_failure
	}
	);
}


function show_button(title,selected){
	var buttons_div = $("state_buttons");
	var new_button = document.createElement("button");
	new_button.title=title;
	new_button.addClassName("state_button");
	new_button.addClassName("animated");
	new_button.addClassName("bounceIn");
	if(selected)
		new_button.addClassName("state_selected");
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
	new_button.observe("click",handle_button_click);
	buttons_div.appendChild(new_button);
}


function handle_failure(ajax) {
	alert("Error making Ajax request:" + "\n\nServer status:\n" +
	ajax.status + " " + ajax.statusText +
	"\n\nServer response text:\n" + ajax.responseText);
}