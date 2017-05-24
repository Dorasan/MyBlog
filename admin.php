<!DOCTYPE html>
<html>
    <head>
        <title>Blog Manager</title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
        <script>var type=0;
        <?php
        if(isset($_GET['ManageType'])) {
			$type=$_GET['ManageType'];
			echo 'type="'.$type.'";</script>';
			if('AddArticle'==$type||'EditArticle'==$type){
				?>
        <script type="text/javascript" charset="utf-8" src="ueditor/ueditor.config.js"></script>
        <!--<script type="text/javascript" charset="utf-8" src="ueditor/ueditor.all.min.js"></script>-->
        <<script type="text/javascript" charset="utf-8" src="ueditor/ueditor.all.js"></script>
        <script type="text/javascript" charset="utf-8" src="ueditor/lang/zh-cn/zh-cn.js"></script>
				<?
			} else if ('ManageArticle'==$type) {}
			//else if ('EditArticle'==$type) {}
		}else echo "</script>";
			?>
		<script  type="text/javascript" charset="utf-8" src="jquery-3.2.0.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
				if(type){
					if('ManageArticle'==type) $("#ManagmentOption #mnga").addClass("selected");
					else if('AddArticle'==type) $("#ManagmentOption #adda").addClass("selected");
					else if('EditArticle'==type) $("#ManagmentOption #edia").addClass("selected");
				}
			});
        </script>
        <style>
            
        </style>
    </head>
    <body>
        <div id='ManagmentOption' style='position:fixed;float:left;width:20%;background:pink;height:98%;font-size:2em;margin:auto;top:0;bottom:0;padding-top:0;padding-bottom:0;'>
			<ul>
				<li id='mnga'><a href='admin.php?ManageType=ManageArticle'>ManageArticle</a></li>
				<li id='adda'><a href='admin.php?ManageType=AddArticle'>AddArticle</a></li>
				<li id='edia'><a href='admin.php?ManageType=EditArticle'>EditArticle</a></li>
			</ul>
        </div>
        <div id='ManageWindow' style='width:70%;height:98%;top:0;bottom:0;margin:auto;float:right;right:5%;'>
			<?php
				if(isset($_GET['ManageType'])) {
					$type=$_GET['ManageType'];
					if('AddArticle'==$type||'EditArticle'==$type){
						?>
            <script id="editor" type="text/plain" style="width:95%;height:80%;margin:auto;"></script>
            <button id='sub'>Submit</button>
            <div id='postmsg' style='display:none;'>Succeed!</div>
			<script type="text/javascript" charset="utf-8">
				var ue = UE.getEditor('editor',{
					autoHeight: false,
					maximumWords: 10000000,
					enableAutoSave: false
				});
						<?php
						if ('EditArticle'==$type) {
							echo "var aid=".$_GET['aid'].";";
							?>
				$.get("page.php?aid="+aid,function(data){
					//eval(data)[]
					UE.getEditor('editor').setContent(eval(data)['article'], 1);
				})
							<?php
						}
							?>
				$("#sub").click(function(){
					$.post("pass.php?method=addA",UE.getEditor('editor').getAllHtml(),function(){
						$("postmsg").fadeToggle(200,function(){
							setTimeout(function(){$("postmsg").fadeToggle(200);},1000);
						});
					})
					
				})
			</script>
						<?
					} else if ('ManageArticle'==$type) {}
					else if ('EditArticle'==$type) {}
				}
				?>
        </div>
     </body>
</html>
