<!DOCTYPE html>
<html>
	<head>
		<meta charset='UTF-8'>
		<title>Welcome<? echo $UserName?", ".$UserName:" to Chater" ?></title>
		<script src='marked.min.js'></script>
		<script src='jquery-3.2.0.min.js'></script>
		<script>
		const RefreshTime=100;
		const URL='./chatmsg.php';
		const UserProfile=[<? 
		//Connect to DB
		
		//get User from DB by Cookie(aid and pw)
		
		//echo user profile
		 ?>]
		var msg={
			post:function(message){
				$.post(URL,message);
			},
			get:function(type){
				var t=new Data();
				t=t.getTime();
				$.get(URL+'?t='+t+'&type='+type,function(){
					
				});
			}
		}
		class div {
			construct(classes,id,styles){
				this.class=classes;
				this.id=id;
				this.style=styles;
				this.html=["<div id='"+this.id+"' class='"+this.class.join(' ')+"' style='"+this.style.join(';')+"'>","</div>"];
			}
		}
		</script>
		<style>
		#register, #login {
			display:none;
		}
		#userlist, #myprofile, #messages, #send {
			float:left;
			position:relative;
		}
		#chatmain {
			height:95%;
			width:90%;
			position:fixed;
			margin:auto;left:0;right:0;top:0;bottom:0;
		}
		#userlist, #myprofile {
			width:25%;
		}
		#userlist {
			height:33%;
			background:blue;
		}
		#myprofile {
			height:65%;
			top:35%;
			left:-25%;
			background:red;
		}
		#messages, #send {
			width:70%;
		}
		#messages {
			height:68%;
			top:-65%;
			left:30%;
			background:green;
		}
		#send {
			height:30%;
			top:-63%;
			left:30%;
			background:yellow;
			
		}
		</style>
	</head>
	<body>
		<div id='register'>
			Name:<input type='text'>
			Password:<input type='password'>
			Repeat Password:<input type='password'>
			<input type='submit'>
		</div>
		<div id='login'>
			Name:<input type='text'>
			Password:<input type='password'>
			<input type='submit'>
		</div>
		<div id='chatmain'>
			<div id='userlist'></div>
			<div id='myprofile'>
				<ul>
					<li id='username'></li>
					<li id='userID'></li>
					<li id='myfriends'>
						<table></table>
					</li>
					<li id='mygroup'>
						<table></table>
					</li>
				</ul>
			</div>
			<div id='messages'></div>
			<div id='send'>
				<div id='editor'></div>
				<div id='textarea' contenteditable="true">123456465153</div>
				<input type='submit' value='Send'>
			</div>
		</div>
	</body>
</html>
