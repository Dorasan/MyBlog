<?php
define("endl","\n");
define ("MAX_ARTICLE_PER_PAGE",10);
/*create table blog_article (
ID int not null auto_increment,
TITLE text not null,
PUBLISH_DATE int not null,
PUBLISH_TIME int not null,
VIEW_TIME int not null default 0,
ARTICLE text not null,
primary key(ID)
)ENGINE=InnoDB default charset=utf8;
*/
$con=mysqli_connect("localhost","root","kamisama");
if(!$con) die("SQL Error!".mysqli_error($con));
mysqli_query($con,"set names utf8");
if(isset($_GET['page'])){
	filter_var($_GET['page'], FILTER_VALIDATE_FLOAT);
	$sql="select ID,TITLE,PUBLISH_DATE,PUBLISH_TIME 
		from MyBlog.blog_article order by ID desc
		limit ".(($_GET['page']-1)*MAX_ARTICLE_PER_PAGE).",".
		MAX_ARTICLE_PER_PAGE.";";
	$retval=mysqli_query($con,$sql);
	if(!$retval)
		die('NO DATA: '.mysqli_error($con));
	$count=mysqli_query($con,"select count(ID) as nums from MyBlog.blog_article");
	echo "[";
	while($row=mysqli_fetch_array($count)){
		echo $row['nums'];
	}
	while($row=mysqli_fetch_array($retval)){
		echo ",{\n\t'id':".$row['ID'].",\n".
			"\t'title':'".$row['TITLE']."',\n".
			"\t'pubdate':".$row['PUBLISH_DATE'].",\n".
			"\t'pubtime':".$row['PUBLISH_TIME']."\n".
			"}";
	}
	echo "]";
}
else if(isset($_GET['preaid'])){
	filter_var($_GET['preaid'], FILTER_VALIDATE_FLOAT);
	$sql="select ID,VIEW_TIME,
		replace(replace(REPLACE(REPLACE(SUBSTRING_INDEX(ARTICLE,'<!--MORE-->',1), CHAR(10),''),char(39),char(92)+char(39)),char(34),char(92)+char(34)), CHAR(13),'') as ARTICLE
		from MyBlog.blog_article where ID=".$_GET['preaid'];
	$retval=mysqli_query($con,$sql);
	if(!$retval)
		die('NO DATA: '.mysqli_error($con));
	while($row=mysqli_fetch_array($retval)){
		echo "[{\n\t'id':".$row['ID'].",\n".
			"\t'viewtimes':".$row['VIEW_TIME'].",\n".
			"\t'article':'".$row['ARTICLE']."'\n".
			"}]";
	}
}
else if(isset($_GET['aid'])){
	filter_var($_GET['aid'], FILTER_VALIDATE_FLOAT);
	$sql="select ID,VIEW_TIME,replace(replace(replace(replace(ARTICLE,char(10),''),char(13),''),char(39),char(92)+char(39)),char(34),char(92)+char(34)) as ARTICLE,TITLE,PUBLISH_DATE,PUBLISH_TIME
		from MyBlog.blog_article where ID=".$_GET['aid'];
	$retval=mysqli_query($con,$sql);
	if(!$retval)
		die('NO DATA: '.mysqli_error($con));
	while($row=mysqli_fetch_array($retval)){
		echo "[{\n\t'id':".$row['ID'].",\n".
			"\t'viewtimes':".$row['VIEW_TIME'].",\n".
			"\t'article':'".$row['ARTICLE']."',\n".
			"\t'title':'".$row['TITLE']."',\n".
			"\t'pubdate':".$row['PUBLISH_DATE'].",\n".
			"\t'pubtime':".$row['PUBLISH_TIME']."\n".
			"}]";
	}
}
?>
