<?php
require_once("header.php");

if(!isset($_SESSION["nick"]))
	while(!set_nick("Anon".rand(1,99999)));  //randomly create a nick if no nick is set

?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>INSERT TITLE HERE</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">


<!--replace to min in production-->
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-responsive.css" rel="stylesheet">
<script src="js/jquery-1.9.1.js"></script>
<script src="js/bootstrap.min.js"></script>
<style>
#chatInput {position:absolute;top:0px;}
body {margin-top:30px}
</style>
<script>
$(function(){

	var current_id=0;
	function send_chat(e){
		$.post("api.php",{text:$("#chatInput").val()},function(data){})
		$("#chatInput").val("");
		e.preventDefault();
		return false;	
	}
	$("form").submit(send_chat)
	$("#chatInput").keypress(function(e){
		if(e.keyCode==13){
			send_chat(e);
		}
	})	
	function get_text(){
		$.getJSON("posts.json",function(data){
			for(i=0;i<data.length;i++){
				if(parseInt(data[i].id,10)>current_id){
					current_id=parseInt(data[i].id,10)
					if(data[i].user=="1")
						$("#chat").prepend($("<p />").append($("<strong	 />").text(data[i].text)))
					else
						$("#chat").prepend($("<p />").text(data[i].nick+": "+data[i].text))
				}
				
			}
		})
	}
	window.setInterval(get_text,500)
	
})
</script>

<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="default" />
<meta name="apple-touch-fullscreen" content="yes" />

</head>
<body>
<textarea rows="1" id="chatInput" type="text" class="input-block-level" name="name" placeholder="type text here and type 'enter' to send"/></textarea>
<div id="chat"></div>
</body>
</html>