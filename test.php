<?php
require_once("header.php");


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
#chatInput {position:absolute;bottom:0px;}
</style>
<script>
$(function(){
	function send_chat(e){
		$.post("api.php",{text:$("#chatInput").val()},function(data){
			//alert(data);
		})
		$("#chatInput").val("");
		$(document).scrollTop($(document).height());
		e.preventDefault();
		return false;	
	}
	$("form").submit(send_chat)
	$("#chatInput").keypress(function(e){
		if(e.keyCode==13){
			send_chat(e);
		}
	})
	$("#chatInput").focus();
	
	function get_text(){
		$.getJSON("api.php",function(data){
			$("#chat").html("");
			for(i=0;i<data.length;i++){
				$("#chat").append($("<p />").text(data[i].time+":"+data[i].ip+":"+data[i].text))
			}
			window.setTimeout(get_text,1000)
		})
	}
	
	//get_text()
	function test(){
		$.post("api.php",{text:"lol_test"},function(data){
			//alert(data);
			window.setTimeout(test,200)
		})
	}
	
	test();
	
	
})
</script>

<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="default" />
<meta name="apple-touch-fullscreen" content="yes" />

</head>
<body>
<div id="chat"></div>
<textarea rows="1" id="chatInput" type="text" class="input-block-level" name="name" placeholder="type text here and type 'enter' to send"/></textarea>
</div>

</body>
</html>