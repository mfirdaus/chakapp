<?php
$unique_ip=true;
function set_nick($nick){
	if($unique_ip){
		$existing=R::findOne("user","ip = ?",array($_SERVER["REMOTE_ADDR"]));
	} else {
		$existing=R::findOne("user","session = ?",array(session_id()));
	}
	if(@$existing->id){
		$other=R::findOne("user","nick = ? and id <> ?",array($nick,$existing->id));
		if(@$other->id) return false;
		if(isset($_SESSION["nick"])){
			$existing->nick=$nick;
			R::store($existing);
		} else {
			$nick=$existing->nick;
		}
	} else {
	$existing=R::findOne("user","nick = ?",array($nick));
	if(@$existing->id) return false;
	 else {
		$user=R::dispense("user");
		$user->nick=htmlentities($nick);
		$user->ip=$_SERVER["REMOTE_ADDR"];
		$user->session=session_id();
		$user->lastUpdate=R::isoDateTime();
		R::store($user);
	}}
	if(isset($_SESSION["nick"])){
		save_post($_SESSION["nick"]." is now known as ".$nick.".",1);
	} else {
		save_post($nick." has joined the room.",1);
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
		
		$posts=R::getAll("select * from (select post.id,nick,text,user from post,user where post.user=user.session order by post.id desc limit 10) a order by id asc");
		file_put_contents("posts.json",json_encode($posts),LOCK_EX); //I assume this would be faster than calling list everytime. Need moar testing.

}
?>