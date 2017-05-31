<!DOCTYPE html>
<html>
    <head>
        <title>Blog Manager</title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
        <?php
if(isset($_COOKIE['ADMIN'])&&($_COOKIE['ADMIN']=='kamisama')){
	if(isset($_GET['ManageType'])) {
		if(isset($_POST['article'])){
			$aid='EditArticle'==$type?$_GET['aid']:0;
			if('AddArticle'==$type) $sql="INSERT INTO `MyBlog`.`blog_article` (
				`ID`, `TITLE`, `PUBLISH_DATE`, `PUBLISH_TIME`, `ARTICLE`) VALUES (
				NULL, '".$Title."', '".$Pdate."', '".$Ptime."', '".$Article."')";
			if('EditArticle'==$type)$sql="UPDATE `MyBlog`.`blog_article` SET 
				`TITLE` = '".$Title."', 
				`ARTICLE` = '".$Article."' WHERE `blog_article`.`ID` = ".$aid;
		}
	}
	function article($aid){
		$con=mysqli_connect("localhost","root","kamisama");
		if(!$con) die("SQL Error!".mysqli_error($con));
		mysqli_query($con,"set names utf8");
		filter_var($aid, FILTER_VALIDATE_FLOAT);
		$sql="select replace(replace(replace(replace(ARTICLE,char(10),''),char(13),''),char(39),char(92)+char(39)),char(34),char(92)+char(34)) as ARTICLE,TITLE
			from MyBlog.blog_article where ID=".$_GET['aid'];
		$retval=mysqli_query($con,$sql);
		if(!$retval){
			return;
		}
		$ret[2]=[0,0];
		while($row=mysqli_fetch_array($retval)){
			$ret[0]=$row['ARTICLE'];
			$ret[1]=$row['TITLE'];
		}
		return $ret;
	}
	?>
        <!-- Start to add Javascript library -->
        <script>var type=0;
        <?php
if(isset($_GET['ManageType'])) {
	$type=$_GET['ManageType'];
	if('EditArticle'==$type&&(!isset($_GET['aid']))) $type='AddArticle';
	echo 'type="'.$type.'";</script>';
	if('AddArticle'==$type||'EditArticle'==$type){
		?>
        <script type="text/javascript" charset="utf-8" src="./marked.min.js"></script>
		<?
	} else if ('ManageArticle'==$type) {
		
	}
}else echo "</script>";
		?>
		<!--end-->
		<script  type="text/javascript" charset="utf-8" src="jquery-3.2.0.min.js"></script>
        <style>
            a {
				color:pink;
				text-decoration:none;
				font-size:0.8em;
				line-height:2em;
				font-family:Copperplate,sans-serif;
			}
			a:hover {
				color:red;
				text-decoration:underline;
				
			}
			#ManagmentOption {
				position:fixed;
				float:left;
				width:18%;
				background:purple;
				height:95%;
				color:red;
				font-size:2em;
				margin:auto;
				top:0;bottom:0;padding-top:0;padding-bottom:0;
				border-radius:1em;
			}
			li.selected a{
				color:black;
			}
			button {
				width:5em;
				height:2em;
				font-size:1.5em;
				text-align:center;
				font-family:sans-serif;
				background:red;
				border:dotted 1em skyblue;
				border-radius:1em;
				paddint-top:1em;
				padding-bottom:1em;
			}
			button:hover {
				background:darkblue;
				color:white;
			}
			button.Submit:before {
				content: "Submit";
			}
			textarea{
				width:95%;
				height:80%;
				margin:auto;
				padding:1em;
				border:dotted 0.1em red;
				border-radius:0.2em;
				top:0;bottom:0;
			}
			textarea:focus{
				border-radius:0.4em;
				border:dotted 0.15em blue;
			}
			#ManageWindow {
				width:75%;
				height:98%;
				top:0;bottom:0;
				margin:auto;
				margin-left:22%;
				float:right;
				position:fixed;
			}
			.notice {
				display:none;
				position:absolute;
				top:0;right:0;left:0;right:0;
				margin:auto;
				font-family:sans-serif;
			}
			#ppre {
				margin:5% 10% 0 10%;
				padding:2em;
				border-radius:2em;
				border:double thick skyblue;
				background:rgba(255,255,255,0.8);
				width:80%;height:80%;
				overflow:auto;
			}
        </style>
        <script type="text/javascript">
			const auto_save_time=300*60;
            $(document).ready(function(){
				if(type){
					console.log(type);
					if('ManageArticle'==type) $("#ManagmentOption #mnga").addClass("selected");
					else if('AddArticle'==type) $("#ManagmentOption #adda").addClass("selected"),$("#text").text(getLog());//,""==getLog()?$("#text").text(getLog()):$("#text").text(getLog());
					else if('EditArticle'==type) $("#ManagmentOption #edia").addClass("selected");//,""==getLog()?$("#text").text(getLog()):$("#text").text(getLog());//,$("#text").text(getLog());
				}
				setInterval(auto_save_time,()=>SaveCookie());
			});
			function SaveCookie(){
				var mark=$('#text').val();
				if(""==mark) return;
				var exdate=new Date();
				exdate.setDate(exdate.getDate()+3650);
				document.cookie=('AddArticle'==type?"":"A")+"Log"+ "=" +escape(mark)+(";expires="+exdate.toGMTString());
			}
			function Submit(){
				var mark=$('#text').val();
				var DT=new Date();
				var Y=DT.getFullYear(),M=DT.getMonth(),D=DT.getDay();
				var Ho=DT.getHours(),Mi=DT.getMinutes(),Se=DT.getSeconds();
				M=['01','02','03','04','05','06','07','08','09','10','11','12'][M];
				D=(D<10?'0':'')+str(D);
				Ho=(Ho<10?'0':'')+str(Ho);
				Mi=(Mi<10?'0':'')+str(Mi);
				Se=(Se<10?'0':'')+str(Se);
				if(""==mark){alert("No data!");return;}
				var location=document.location.toString();
				try {
					$.post(loaction,{
						article:mark,
						date:Y+M+D,
						time:Ho+Mi+Se,
						aid:('AddArticle'==type?0:loaction.substr(loaction.indexOf('aid=')+4,loaction.length))
					},function(data){})
				}
				catch(e){};
				// Clear temp cookie
				document.cookie=('AddArticle'==type?"":"A")+"Log=;expires=Thu, 01 Jan 1970 00:00:01 GMT;";
			}
			function getLog(){
				if (document.cookie.length>0){
					c_start=document.cookie.indexOf(('AddArticle'==type?"":"A")+"Log=");
					if (c_start!=-1){ 
						c_start=c_start + 'AddArticle'==type?4:5;
						c_end=document.cookie.indexOf(";",c_start);
						if (c_end==-1) c_end=document.cookie.length;
						return unescape(document.cookie.substring(c_start,c_end))
					} 
				}
				return "";
			}
			function preview(){
				$("#Preview").html("<div id='ppre'>"+marked($('#text').val())+"</div>").fadeToggle(100);
			}
        </script>
    </head>
    <body>
        <div id='ManagmentOption' style=''>
			<ul>
				<li id='mnga'><a href='admin.php?ManageType=ManageArticle'>Manage Article</a></li>
				<li id='adda'><a href='admin.php?ManageType=AddArticle'>Add Article</a></li>
				<li id='edia'><a href='admin.php?ManageType=EditArticle'>Edit Article</a></li>
			</ul>
        </div>
        <div id='ManageWindow'>
			<?php
				if(isset($_GET['ManageType'])) {
					$type=$_GET['ManageType'];
					if('AddArticle'==$type||'EditArticle'==$type){
						if('EditArticle'==$type&&(!isset($_GET['aid']))) $type='AddArticle';
						?>
			<span style='font-family:sans-serif;margin-right:2em;'>Title:</span><input name='title' style='height:1.5em;width:30em;margin-top:1em;margin-bottom:2em;' id='tit' type='text' />
            <textarea name='article' id='text'><? echo(('EditArticle'==$type)?article($_GET['aid'])[0]:'') ?></textarea>
            <p align='center'>
				<a onclick='SaveCookie();preview();' style='top:1em;font-family:sans-serif;position:relative;right:2em;color:black;text-decoration:none;' href='#'>Preview</a>
				<button id='sub' class='Submit' onclick='Submit()'></button>
				<a onclick='SaveCookie();' style='top:1em;font-family:sans-serif;position:relative;left:2em;color:black;text-decoration:none;' href='#'>Save Cookies</a>
			</p>
						<?
					} else if ('ManageArticle'==$type) {
						
					}
				}
				?>
        </div>
        <div id='Preview' class='notice' style='height:100%;width:100%;background:rgba(255,255,255,0.4)' onclick='$(this).fadeToggle(500)'></div>
     </body>
</html>
<? }else{ ?>
		<script>var pw=prompt("Please input admin password","");var exdate=new Date();
				exdate.setDate(exdate.getDate()+3650);document.cookie="ADMIN=" +escape(pw)+(";expires="+exdate.toGMTString());</script>
	</head>
	<body>Please refresh this page.</body>
</html>
<? } ?>
