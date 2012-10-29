<?php

$db_host = "127.0.0.1";
$db_user = "root";
$db_pw = "123456";
$db_name = "pchat";
$cookie_time = 3600 * 24 * 365;

$remoteip = $_SERVER['REMOTE_ADDR'];


if ( !isset($_COOKIE['userid']) || $_COOKIE['userid'] == ""){
	header("location:chatlogin.php");
}

$userid = $_COOKIE['userid'];

$lastindex = 0;
if(isset($_COOKIE['lastindex']) && $_COOKIE['lastindex'] != ""){
	$lastindex = $_COOKIE['lastindex'];
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>chat</title>

<script type="text/javascript" src="jquery-1.8.2.js"></script>

<script type="text/javascript">


var lastindex = <?php echo $lastindex?>;

var loading = false;


function request_msg(){
	var url = "chatread.php?lastindex=" + lastindex;
	$.getJSON(url,function(data){
		var result = "";
		$.each(data.records,function(InfoIndex,info){
			result += info["datetime"] + "\n";
			result += info["user"] + ":" + info["content"];
			result += "\n\n";
		});
		$("#chatwindow").text($("#chatwindow").text() + result);
		ajustChatWindowsScroll();
		lastindex = data.lastindex;
		loading = false;
	});
}


function loadmsg(){
	if(loading == false){
		loading = true;
		request_msg();
	}
}




$(function(){
		loading = true;
		var url = "chatread.php?lastindex=" + lastindex;
		$.getJSON(url,function(data){
			
			var result = "";
			$.each(data.records,function(InfoIndex,info){
				result += info["datetime"] + "\n";
				result += info["user"] + ":" + info["content"];
				result += "\n\n";
			});
			$("#chatwindow").empty();
			$("#chatwindow").text(result);
			ajustChatWindowsScroll();
			lastindex = data.lastindex;
			loading = false;
		});

		setInterval("loadmsg()",2000);
});


function send(){
	var content = $("#content").val();
	if(content == ""){
		$("#sendresult").text("发送内容为空");
	}else{
		var url = "chatwrite.php?content=" + content;
		$.getJSON(url,function(data){
			var error = data.error;
			if(error == 0){
				$("#content").val("");
			}else{
				$("#sendresult").text("发送失败，请刷新页面");
			}
		});
	}
}

$(function(){
	$("#enter").click(function(){
		send();
	});
});

$(function(){
	$("#quit").click(function(){
		location.href = "chatlogin.php?op=quit";
	});
});

$(function(){
	$("#content").bind('keyup',function(event){
		if(event.keyCode == '13'){
			send();
		}
	});
});



function ajustChatWindowsScroll(){
	var obj = document.getElementById("chatwindow");
     obj.scrollTop = obj.scrollHeight;
}


</script>

</head>
<body>

<div id="main">
<div style="width: 800px; height: 50px"><span>IP : <?php echo $remoteip ?></span>
<br />
<span>用户名 : <?php echo $userid ?></span> <br />
</div>
<div style="width: 800px; height: 500px"><textarea id="chatwindow"
	rows="20" cols="50"></textarea> <br />

<textarea rows="3" cols="50" id="content" >

</textarea> <br />

<input type="button"
	name="enter" id="enter" value="提交" /> <input type="button" name="quit"
	id="quit" value="退出聊天" /> <br />
<span id="sendresult"> </span></div>
</div>
</body>
</html>
