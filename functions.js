function elems(elem){
	var retval="<"+(elem=elem.replace(/[^\w\.#]/g,'')).substr(0,elem.search(/\.|#/));
	if(!open) return retval+">";
	var classes,ids;
	return retval+((ids=elem.match(/#\w+/g))?" id='"+ids[0].substr(1)+"'":"")+((classes=elem.match(/\.\w+/g))?" class='"+classes.reduce((a,b,c)=>a+(c?' ':'')+b.replace(/[^\w]/g,''),"")+"'":"")+">";
};
(function($){
	$.fn.typewriter=function(timeout){
		this.each(function(){
			var $ele=$(this),str=$ele.html(),progress=0,str0=str;
			$ele.html('');
			var t=0;
			var timer=setInterval(function(){
				var current=str.substr(progress,1);
				if (current=='<') {
					progress=str.indexOf('>',progress)+1;
				}else{
					progress++;
				}
				$ele.html(str.substring(0,progress)+(progress&1?'_':''));
				if (progress>=str.length){
					clearInterval(timer);
				}
				},(timeout?timeout:100));
		});
		return this;
	};
})(jQuery);
function toDate(date_num){
	var d=date_num?date_num:19700101;
	return Math.floor(d/10000)+"年"+Math.floor((d/100)%100)+"月"+Math.floor(d%100)+"日";
}
function toTime(time_num){
	var t=time_num?time_num:060000;
	return (t/10000<10?'0':'')+Math.floor(t/10000)+":"+(((t/100)%100)<10?'0':'')+Math.floor((t/100)%100)+":"+((t%100)<10?"0":'')+Math.floor(t%100);
}
function rgb(){
	var r=Math.floor(Math.random()*256);
	var g=Math.floor(Math.random()*256);
	var b=Math.floor(Math.random()*256);
	return "rgb("+r+','+g+','+b+")";
}
