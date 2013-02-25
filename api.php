<?php
require_once("header.php");


if(!isset($_SESSION["nick"]))
	while(!set_nick("Anon".rand(1,99999)));  //randomly create a nick if no nick is set

if(isset($_POST["text"])&&$_POST["text"]!=""){
	if(strpos($_POST["text"],"/nick ")===0){
		$nick=explode(" ",$_POST["text"]);	
		if($nick[1]!="")
			set_nick($nick[1]);
	} else {
		save_post($_POST["text"],session_id());
	}
}

?>