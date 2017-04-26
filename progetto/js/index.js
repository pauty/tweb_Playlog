PLAYLOG_BACKGROUNDS_NUM = 3;
PLAYLOG_CURRENT_BACKGROUND = 0;

window.onload = function() {

	var login_button = $("login_button");
	var signup_button = $("signup_button");
	
	if(login_button!=null)
		login_button.observe("click",show_login_form);
	if(signup_button!=null)
		signup_button.observe("click",show_signup_form);

	setInterval(change_background,10000);

	if($("show_login")!=null)
		show_login_form();
	else if($("show_signup")!=null)
		show_signup_form();

}

function change_background(){
	var bkg = $("dynamic_background");

	bkg.removeClassName("bkg_"+PLAYLOG_CURRENT_BACKGROUND);
	PLAYLOG_CURRENT_BACKGROUND = (PLAYLOG_CURRENT_BACKGROUND+1)%PLAYLOG_BACKGROUNDS_NUM;
	bkg.addClassName("bkg_"+PLAYLOG_CURRENT_BACKGROUND);
}


function clear_welcome_area() {

	var welcome_div = $("welcome_area");

	while (welcome_div.firstChild) {
		welcome_div.removeChild(welcome_div.firstChild);
	}

	$("left_coloumn").removeChild(welcome_div);

	welcome_div=document.createElement("div");
	$("left_coloumn").appendChild(welcome_div);
	welcome_div.id="welcome_area";
	welcome_div.addClassName("animated");
	welcome_div.addClassName("fadeInUp");

}

function show_login_form(){
	var welcome_div;
	var intro_p;
	var signup_a;
	var login_form;
	var email_input;
	var password_input;
	var submit;

	clear_welcome_area();

	welcome_div = $("welcome_area");

	intro_p=document.createElement("h2");
	intro_p.innerHTML="Not a member yet? ";
	welcome_div.appendChild(intro_p);

	signup_a=document.createElement("a");
	signup_a.innerHTML=" Sign Up</br></br>";
	signup_a.observe("click",show_signup_form);
	intro_p.appendChild(signup_a);

	login_form=document.createElement("form");
	login_form.action="javascript:void(0);";
	login_form.observe("submit",validate_login);
	welcome_div.appendChild(login_form);

	email_input=document.createElement("input");
	email_input.id="email";
	email_input.name="email";
	email_input.type="text";
	email_input.placeholder="email";
	email_input.required="required";
	login_form.appendChild(email_input);

	password_input=document.createElement("input");
	password_input.id="password";
	password_input.name="password";
	password_input.type="password";
	password_input.placeholder="password";
	password_input.required="required";
	login_form.appendChild(password_input);

	submit=document.createElement("button");
	submit.type="submit";
	submit.innerHTML="Log In";
	login_form.appendChild(submit);

}

function show_signup_form() {
	var welcome_div;
	var intro_p;
	var login_a;
	var signup_form;
	var email_input;
	var username_input;
	var password_input;
	var submit;

	clear_welcome_area();

	welcome_div = $("welcome_area");

	intro_p=document.createElement("h2");
	intro_p.innerHTML="Already a member? ";
	welcome_div.appendChild(intro_p);

	login_a=document.createElement("a");
	login_a.innerHTML=" Log In";
	login_a.observe("click",show_login_form);
	intro_p.appendChild(login_a);

	intro_p=document.createElement("h3");
	intro_p.innerHTML="</br><strong>Note: </strong>Please choose an user name between 6 and 20 characters. Password must be at least 6 characters long and contain one letter and one digit";
	welcome_div.appendChild(intro_p);

	signup_form=document.createElement("form");
	signup_form.action="javascript:void(0);";
	signup_form.observe("submit",validate_signup);
	welcome_div.appendChild(signup_form);

	email_input=document.createElement("input");
	email_input.id="email";
	email_input.type="text";
	email_input.placeholder="email";
	email_input.required="required";
	email_input.maxLength="30";
	email_input.pattern="^[a-zA-Z0-9_\-]+@(([a-zA-Z0-9_\-])+\.)+[a-zA-Z]{2,4}$";
	email_input.title="Please insert a valid email address"
	signup_form.appendChild(email_input);

	username_input=document.createElement("input");
	username_input.id="user_name";
	username_input.type="text";
	username_input.placeholder="user name";
	username_input.required="required";
	username_input.maxLength="20";
	username_input.pattern="^[a-zA-Z0-9][a-zA-Z0-9_\-]{4,18}[a-zA-Z0-9]$";
	username_input.title="User name must be from 6 to 20 characters long and can contain letters, digits, underscores and hyphens (latter two not at the end/begin)"
	signup_form.appendChild(username_input);
 
	password_input=document.createElement("input");
	password_input.id="password";
	password_input.type="password";
	password_input.placeholder="password";
	password_input.required="required";
	password_input.maxLength="20";
	password_input.title="Password must be from 6 to 20 characters long and contain at least one letter and one digit";
	password_input.observe("change",validate_passwords);
	signup_form.appendChild(password_input);

	password_input=document.createElement("input");
	password_input.id="repeat_password";
	password_input.type="password";
	password_input.placeholder="confirm password";
	password_input.maxLength="20";
	password_input.required="required";
	password_input.observe("keyup",validate_passwords);
	signup_form.appendChild(password_input);

	submit=document.createElement("button");
	submit.type="submit";
	submit.innerHTML="Sign Up";
	signup_form.appendChild(submit);
	
}

function validate_passwords() {
	var password = $("password").value;
	var r_password = $("repeat_password").value;

	if(/^(?=.*[a-zA-Z])(?=.*\d).{6,20}$/.test(password))
		$("password").setCustomValidity("");
	else
		$("password").setCustomValidity("Password must be from 6 to 20 characters long and contain at least one letter and one digit");

	if(password===r_password)
		$("repeat_password").setCustomValidity("");
	else
		$("repeat_password").setCustomValidity("Passwords don't match");
}

function validate_login(){
	var email_value;
	var password_value;

	email_value = $("email").value;
	password_value = $("password").value;

	if(email_value!=null && password_value!=null){
		new Ajax.Request("lib/request_handlers/autentication_handler.php",
			{
				method: "post", 
				parameters: { function: "user_login" ,
							  email: email_value,
							  password: password_value
							},
				onSuccess: handle_login,
				onFailure: handle_failure
			}
		);
	}

}

function validate_signup() {
	var email_ok;
	var email_value;
	var username_ok;
	var username_value;
	var password_ok;
	var password_value;
	var r_password_value; 

	email_value = $("email").value;
	var email_ok = /^[a-zA-Z0-9_\-]+@(([a-zA-Z0-9_\-\d])+\.)+[a-zA-Z]{2,4}$/.test(email_value);
	username_value = $("user_name").value;
	var username_ok = /^[a-zA-Z0-9][a-zA-Z0-9_\-]{4,18}[a-zA-Z0-9]$/.test(username_value);
	var password_value = $("password").value;
	password_ok = /^(?=.*[a-zA-Z])(?=.*\d).{6,20}$/.test(password_value);
	r_password_value = $("repeat_password").value;
	if( email_ok && username_ok && password_ok && password_value === r_password_value){
		new Ajax.Request("lib/request_handlers/autentication_handler.php",
			{
				method: "post", 
				parameters: { function: "user_signup" ,
							  email: email_value,
							  username: username_value,
							  password: password_value
							},
				onSuccess: handle_signup,
				onFailure: handle_failure
			}
		);
	}

}

function handle_login(ajax){
	var res = JSON.parse(ajax.responseText);
	if(res["success"]==1)
		location.href="index.php";
	else{
		var p = $("error_p");
		if(p!=null)
			$("welcome_area").removeChild(p);
		p = document.createElement("p");
		if(res["success"]==0)
			p.innerHTML="Incorrect email and/or password";
		else
			p.innerHTML="Sorry, an error occured. Please try again later";
		p.id="error_p";
		$("welcome_area").insertBefore( p, $("welcome_area").childNodes[0] );
	}

}

function handle_signup(ajax){
	var res = JSON.parse(ajax.responseText);
	var p = null;
	var b = null;
	if(res["success"]==1){
		clear_welcome_area();
		p = document.createElement("h1");
		p.innerHTML="Thank you for joining us!";
		$("welcome_area").appendChild(p);

		b = document.createElement("button");
		b.innerHTML = "Log In";
		b.observe("click",show_login_form);
		$("welcome_area").appendChild(b);
	}
	else{
		p = $("error_p");
		if(p!=null)
			$("welcome_area").removeChild(p);
		p = document.createElement("p");
		if(res["success"]==0)
			p.innerHTML="An account is already using the specified email address. Please insert a new one";
		else
			p.innerHTML="Sorry, an error occured. Please try again later";
		p.id="error_p";
		$("welcome_area").insertBefore( p, $("welcome_area").childNodes[0] );
	}
}

function handle_failure(){
	var p = $("error_p");
	if(p!=null)
		$("welcome_area").removeChild(p);
	p = document.createElement("p");
	p.innerHTML="Sorry, an error occured. Please try again later";
	p.id="error_p";
	$("welcome_area").insertBefore( p, $("welcome_area").childNodes[0] );
}