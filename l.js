function AllCenter(e){
    e.css("left",0).css('right',0).css('top',0).css('bottom',0)
        .css('margin','auto')
        .css('position','absolute')
}
function randColor(str,alpha=1){
	var r=Math.round(Math.random()*1000%255);
	var g=Math.round(Math.random()*1000%255);
	var b=Math.round(Math.random()*1000%255);
	return '<span style="color:rgba('+r+','+g+','+b+','+alpha+')">'+str+'</span>';
}
function randPlace(str,size){
	var top=Math.round(Math.random()*1000);
	var left=Math.round(Math.random()*1000);
	return '<span style="position:absolute;top:'+top+';left:'+left+';font-size:'+size+';">'+str+'</span>'
}
(function($) {
    $.fn.typewriter = function(timer) {
        this.each(function() {
            var $ele = $(this), str = $ele.html(), progress = 0, str0=str;                        //Set html and progress
            $ele.html('');																//Clean the html
            var t=0;
            var timer = setInterval(function() {										//Repeat every 75ms
                var current = str.substr(progress, 1);									//get current char
                if (current == '<') {													//Skip html lables
                    progress = str.indexOf('>', progress) + 1;
                } else {
                    progress++;
                }
                $ele.html(str.substring(0, progress) + (progress & 1 ? '_' : ''));		//Underline blink
                if (progress >= str.length) {
                    //clearInterval(timer);												//Stop while at the end of the html
					progress-=1;
					str+='<br/>'+randColor(str0);
                }
            }, 100);
        });
        return this;
    };
})(jQuery);
$(document).ready(function(){
    var body=$('body');
    var div=['<div id="','"></div>'];
    body.fadeToggle(200,function(){
        body.append(div[0]+"HB"+div[1]);
        var HB=$('#HB');
        var strings="Happy Birthday!";
        HB.append(strings);
        HB.fadeToggle(0);
        HB.typewriter();
    });
    AllCenter(body);
    var to = setTimeout(function(){
		clearInterval(timer);
		$('#HB').fadeToggle(2000);
	}, 10000);
});
