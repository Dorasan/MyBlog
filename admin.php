<? //定义常量
require 'config.php';
define("DEBUG",true);
?>
<? //函数定义
function article($aid){
	$con=mysqli_connect(DB_H,DB_U,DB_P);
	if(!$con) die(DEBUG?"SQL Error!".mysqli_error($con):000000);
	mysqli_query($con,"set names utf8");
	filter_var($aid, FILTER_VALIDATE_FLOAT);
	$sql="select replace(replace(ARTICLE,char(39),char(92)+char(39)),char(34),char(92)+char(34)) as ARTICLE,TITLE
		from MyBlog.blog_article where ID=".$_GET['aid'];
	$retval=mysqli_query($con,$sql);
	if(!$retval){
		return DEBUG?[false,mysqli_error($retval)]:1;
	}
	$ret[2]=[0,0];
	while($row=mysqli_fetch_array($retval)){
		$ret[0]=$row['ARTICLE'];
		$ret[1]=$row['TITLE'];
	}
	return $ret;
}
?>
<?php //主体
if(isset($_COOKIE['ADMIN'])&&($_COOKIE['ADMIN']==SUPASS)){ //验证成功
	$type='ManageArticle';
	if(isset($_GET['ManageType'])){ //检测管理界面类型
		$type=$_GET['ManageType'];
		if('EditArticle'==$type&&(!isset($_GET['aid']))) {
			header("location: ./admin.php?ManageType=AddArticle"); 
			exit; 
		}
	}
	if(isset($_POST['article'])){ //提交文章
		//Mysql connect start
		$con=mysqli_connect(DB_H,DB_U,DB_P);
		if(!$con) die(DEBUG?"SQL Error!".mysqli_error($con):000000);
		mysqli_query($con,"set names utf8");
		//Mysql connect finish
		$sql='';$Article="";$Title='';$Pdate=0;$Ptime=0;
		$aid='EditArticle'==$type?$_GET['aid']:0;
		$Pdate=$_POST['date'];$Ptime=$_POST['time'];
		filter_var($aid, FILTER_VALIDATE_FLOAT);
		filter_var($Pdate, FILTER_VALIDATE_FLOAT);
		filter_var($Ptime, FILTER_VALIDATE_FLOAT);
		$Article=str_ireplace('`','\`',str_ireplace('"','\"',str_ireplace('\'','\\\'',$_POST['article'])));
		$Title=str_ireplace('`','\`',str_ireplace('"','\"',str_ireplace('\'','\\\'',$_POST['title'])));
		//filter the article
		if('AddArticle'==$type){
			$sql="INSERT INTO `MyBlog`.`blog_article` (
			`ID`, `TITLE`, `PUBLISH_DATE`, `PUBLISH_TIME`, `ARTICLE`) VALUES (
			NULL, '".$Title."', '".$Pdate."', '".$Ptime."', '".$Article."')";
		}else if('EditArticle'==$type){
			$sql="UPDATE `MyBlog`.`blog_article` SET 
				`TITLE` = '".$Title."', 
				`ARTICLE` = '".$Article."' WHERE `blog_article`.`ID` = ".$aid;
		} else {return '000000';}
		$retval=mysqli_query($con,$sql);
		if(!$retval){
			return DEBUG?[false,mysqli_error($retval)]:1;
		}
	}else if(isset($_GET['nid'])){
		filter_var($_GET['nid'], FILTER_VALIDATE_FLOAT);
		filter_var($_GET['aid'], FILTER_VALIDATE_FLOAT);
		$sql="UPDATE `MyBlog`.`blog_article` SET `ID` = '".$_GET['nid']."' WHERE `blog_article`.`ID` = ".$_GET['aid'];
		//Mysql connect start
		$con=mysqli_connect(DB_H,DB_U,DB_P);
		if(!$con) die(DEBUG?"SQL Error!".mysqli_error($con):000000);
		mysqli_query($con,"set names utf8");
		//Mysql connect finish
		$retval=mysqli_query($con,$sql);
		if(!$retval){
			echo DEBUG?mysqli_error($retval):'false';
		}else echo 'true';
	}else if(isset($_GET['d'])){
		filter_var($_GET['aid'], FILTER_VALIDATE_FLOAT);
		if(1==$_GET['d'])
			$sql="DELETE FROM `MyBlog`.`blog_article` WHERE `blog_article`.`ID` = ".$_GET['aid'];
		else return;
		//Mysql connect start
		$con=mysqli_connect(DB_H,DB_U,DB_P);
		if(!$con) die(DEBUG?"SQL Error!".mysqli_error($con):000000);
		mysqli_query($con,"set names utf8");
		//Mysql connect finish
		$retval=mysqli_query($con,$sql);
		if(!$retval){
			echo DEBUG?mysqli_error($retval):'false';
		}else echo 'true';
	}else{
?>
<!DOCTYPE html>
<html>
	<head>
		<title>博客管理</title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
		<link rel='stylesheet' href='admin.css' >
		<script type="text/javascript" charset="utf-8" src="./marked.min.js"></script>
		<script  type="text/javascript" charset="utf-8" src="jquery-3.2.0.min.js"></script>
		<script type="text/javascript">
			const type='<? echo $type ?>';
			const auto_save_time=300*60;
			var AIDs=[];
			function con(t){ //控制台输入并返回
				console.log(t);
				return t;
			};
			
			var draft={ //设置草稿
				save:function(){
					var mark=document.getElementById('text').value;//$('#text').val();
					return localStorage.setItem(('AddArticle'==type?"":"A")+"Log",mark);
				},
				get:function(){
					return localStorage.getItem(('AddArticle'==type?"":"A")+"Log")||'';
				},
				remove:function(key){
					return localStorage.removeItem(key);
				}
			};
			function Submit(){ //提交文章
				function toDouble(num){
					return (num<10?'0':'')+D.toString()%100;
				};
				var mark=document.getElementById('text').value,DT=new Date();
				var Y=DT.getFullYear(),M=DT.getMonth(),D=DT.getDay();
				var Ho=DT.getHours(),Mi=DT.getMinutes(),Se=DT.getSeconds();
				if(""==mark) return alert("No data!");
				var location=document.location.toString();
				try {
					$.post(location,{
						article:mark,
						date:toDouble(Y)+toDouble(M+1)+toDouble(D),
						time:toDouble(Ho)+toDouble(Mi)+toDouble(Se),
						title:document.getElementById('tit').value,
						aid:('AddArticle'==type?0:location.substr(location.indexOf('aid=')+4,location.length))
					},()=>draft.remove(('AddArticle'==type?"":"A")+"Log"));
				}
				catch(e){alert("提交失败");return undefined;};
			};
			function preview(){
				$("#Preview").html("<div id='ppre'>"+marked(document.getElementById('text').value)+"</div>").fadeToggle(100);
			}
			function used(aid){
				for(var i=0;i<AIDs.length;i++){
					if(AIDs[i]==aid)return i+1;
				}
				return false;
			}
			var Manage= {
				Del:function(aid){
					if(!aid)return;
					var cfm=confirm("Are you sure to delete this article?");
					if(cfm)
						$.get("./admin.php?ManageType=ManageArticle&d=1&aid="+aid,function(data){
							if(!eval(data))return alert("Something wrong while deleting the article.");
							location.reload(1);
						});
					return;
				},
				ChangeAID:function(aid){
					var NewID=prompt("Please input new id:","Do not use used id");
					while(used(NewID)){
						NewID=prompt("DONOT use old ID:","");
					}
					if(!NewID)return;
					$.get("./admin.php?ManageType=EditArticle&aid="+aid+"&nid="+NewID,function(data){
						if(!eval(data))return alert("Something wrong while deleting the article.");
						location.reload(1);
					});
				}
			};
			$(document).ready(function(){
				console.log(type);
				if('ManageArticle'==type) document.getElementById("mnga").className = "selected";
				else if('AddArticle'==type) {
					document.getElementById("adda").className = "selected";
					document.getElementById('text').value=draft.get();
				}
				else if('EditArticle'==type) {
					document.getElementById("edia").className = "selected";
					if(''!=draft.get())
						if(confirm("There is a draft you edited, do you want to continue edit your draft?")) document.getElementById('text').value=draft.get();
						else draft.remove('ALog');
				}
				setInterval(auto_save_time,()=>draft.save());
			});
		</script>
		<style>
			a {
				color:black;
				text-decoration:none;
			}
			a:hover{
				text-decoration:uinderline;
				color:green;
			}
			a.del {
				color:red;
			}
			a.del:hover {
				color:green;
			}
			td {
				text-align:center;
				line-height:1.2em;
				padding-left:0.5em;
				padding-right:0.5em;
			}
			th {
				padding-left:0.5em;
				padding-right:0.5em;
				background:rgb(80,80,80);
				color:white;
			}
			td.tit {
				text-align:left;
			}
			tr.tr0 {
				background:rgba(255,255,255,0);
			}
			tr.tr1 {
				background:rgb(200,200,200);
			}
		</style>
	</head>
	<body>
		<div id='ManagmentOption'>
			<ul>
				<li id='mnga'><a href='./admin.php?ManageType=ManageArticle'>Manage Article</a></li>
				<li id='adda'><a href='./admin.php?ManageType=AddArticle'>Add Article</a></li>
				<li id='edia'><a href='./admin.php?ManageType=EditArticle'>Edit Article</a></li>
			</ul>
		</div>
		<div id='ManageWindow'>
			<?php
		if('AddArticle'==$type||'EditArticle'==$type){
			?>
			<span style='font-family:sans-serif;margin-right:2em;'>标题:</span><input name='title' style='height:1.5em;width:30em;margin-top:1em;margin-bottom:2em;' id='tit' type='text' value='<? echo(('EditArticle'==$type)?article($_GET['aid'])[1]:'') ?>' />
			<textarea name='article' id='text'><? echo(('EditArticle'==$type)?article($_GET['aid'])[0]:'') ?></textarea>
			<p align='center'>
				<a onclick='draft.save();preview();' style='top:1em;font-family:sans-serif;position:relative;right:2em;color:black;text-decoration:none;' href='#'>预览</a>
				<button id='sub' class='Submit' onclick='Submit()'></button>
				<a onclick='draft.save();' style='top:1em;font-family:sans-serif;position:relative;left:2em;color:black;text-decoration:none;' href='#'>保存草稿</a>
			</p>
		</div>
		<div id='Preview' class='notice' style='height:100%;width:100%;background:rgba(255,255,255,0.4)' onclick='$(this).fadeToggle(500)'></div>
			<?
		} else if ('ManageArticle'==$type) {
			//echo "21132165146";
			//Mysql connect start
			$con=mysqli_connect(DB_H,DB_U,DB_P);
			if(!$con) die(DEBUG?"SQL Error!".mysqli_error($con):000000);
			mysqli_query($con,"set names utf8");
			//Mysql connect finish
			$sql="select ID
				from MyBlog.blog_article order by ID desc;";
			$retval=mysqli_query($con,$sql);
			if(!$retval)
				die('NO DATA: '.DEBUG?(mysqli_error($con)):000000);
			echo "<script>AIDs=[";
			while($row=mysqli_fetch_array($retval)){
				echo $row['ID'].",";
			}
			echo "-1]</script>";
			$sql="select ID,TITLE
				from MyBlog.blog_article order by ID desc;";
				//limit ".(($_GET['page']-1)*MAX_ARTICLE_PER_PAGE).",".
				//MAX_ARTICLE_PER_PAGE.";";
			$retval=mysqli_query($con,$sql);
			if(!$retval)
				die('NO DATA: '.DEBUG?(mysqli_error($con)):000000);
			echo "<table style='font-size:1.2em;'><tr><th>ID</th><th>Title</th><th>Change ID</th><th>Edit</th><th>Delete</th></tr>";
			$t=0;
			while($row=mysqli_fetch_array($retval)){
			?>
			<tr class='tr<? echo $t%2; ?>'>
				<td style='text-align:center;'><? echo $row['ID'] ?></td>
				<td class='tit'><? echo $row['TITLE'] ?></td>
				<td><a href='#' onclick='Manage.ChangeAID(<? echo $row['ID'] ?>)' class='cge'>Change</a></td>
				<td><a href="./admin.php?ManageType=EditArticle&aid=<? echo $row['ID'] ?>" class='edt'>Edit</a></td>
				<td><a href='#' onclick='Manage.Del(<? echo $row['ID'] ?>)' class='del'>Delete</a></td>
			</tr>
			<?
				$t++;
			}
			echo "</table></div>";
		}
			?>
	 </body>
</html>
<? 
	}
} else {
?>
		<script>
			var pw=prompt("请输入管理员密码","");
			var exdate=new Date();
			exdate.setDate(exdate.getDate()+3650);
			document.cookie='ADMIN='+escape(pw)+';expires='+exdate.toGMTString();
		</script>
	</head>
	<body>请刷新页面.<br />Please refresh this page.</body>
</html>
<? 
}
?>
