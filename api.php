<?php
require_once("header.php");

if(isset($_POST["text"])){
	$post=R::dispense("post");
	$post->time=R::isoDateTime();
	$post->text=htmlentities($_POST["text"]);
	$post->user=session_id();
	$post->ip=$_SERVER['REMOTE_ADDR']; //should I hash this?
	R::store($post);
}

$posts=R::getAll("select * from post order by id desc limit 20");
echo json_encode($posts);
?>