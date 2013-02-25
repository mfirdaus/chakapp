<?php
require_once("header.php");

function set_nick($nick){
	$existing=R::findOne("user","nick = ?",array($nick));
	if(@$existing->id) return false;
	$existing=R::findOne("user","session = ?",array(session_id()));
	if(@$existing){
		$existing->nick=$nick;
		R::store($existing);
	} else {
		$user=R::dispense("user");
		$user->nick=htmlentities($nick);
		$user->ip=$_SERVER["REMOTE_ADDR"];
		$user->session=session_id();
		$user->lastUpdate=R::isoDateTime();
		R::store($user);
	}
	if(isset($_SESSION["nick"])){
		save_post($_SESSION["nick"]." is now known as ".$nick.".",1);
	}
	$_SESSION["nick"]=$nick;
	return true;
}


function save_post($text,$id){
		$post=R::dispense("post");
		$post->time=R::isoDateTime();
		$post->text=htmlentities($text);
		$post->user=$id;
		$post->ip=$_SERVER['REMOTE_ADDR']; //should I hash this?
		R::store($post);
		
		$posts=R::getAll("select nick,text,user from post,user where post.user=user.session order by post.id desc limit 10");
		file_put_contents("posts.json",json_encode($posts),LOCK_EX); //I assume this would be faster than calling list everytime. Need moar testing.

}

if(!isset($_SESSION["nick"]))
	while(!set_nick("Anon".rand(1,99999)));  //randomly create a nick if no nick is set

if(isset($_POST["text"])){
	if(strpos($_POST["text"],"/nick ")===0){
		$nick=explode(" ",$_POST["text"]);	
		if($nick[1]!="")
			set_nick($nick[1]);
	} else {
		save_post($_POST["text"],session_id());
	}
}

?>