<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title>AutoTab</title>
<!--
<link rel="stylesheet" href="css/bootstrap/css/bootstrap.min.css">
-->
<style>
*{
margin:0;
padding:0;
}
body{
}
#container{
	width:100%;
	height:100%;	
}
/*
#navbar{
	color:#ffffff;
	background:#000000;	
	height:40px;
	line-height:40px;
}
*/
#main{
	/*background:blue;*/
	width:100%;
}
#tabs{
	text-align:center;
	width:100%;
	position:absolute;
	top:0;
}
#tabs .file {
	width:100%;
}
#tabs .file img{
	width:60%;
}
#bottombar{
	width:100%;
	color:#ffffff;
	background:#000000;	
	height:40px;
	line-height:40px;
	position:fixed;
	bottom:0;
	opacity:0.3;
	filter:alpha(opacity=30); /* 针对 IE8 以及更早的版本 */
}
</style>
</head>
<body>
<div id="container">
<!--
    <nav id="navbar" class="">
	autotab.cc
    </nav>
-->
    <div id="main" class="">
	<div id="tabs">
	</div>
    </div>
    <nav id="bottombar" class="">
	<button id="btn_play">Play</button>
	<button id="btn_pause">Pause</button>
	<button id="btn_reset">Reset</button>
    </nav>
</div>
</body>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript">
var tabs = [
	{
		id:1,
		name:'贝加尔湖畔',
		speed:0.75,
		files:[
			'./uploads/tabs/1/20150924/1.jpg',
			'./uploads/tabs/1/20150924/2.jpg',
		],
		skips:[
			[0,0.1],
			[0.9,1],
			[1.0,1.1],
			[1.9,2]
		],
		repeats:[
			[0.6,0.3],
			[1.7,0.7]
		]
	},
	{
		id:2,
		name:'贝加尔湖畔2',
		speed:1,
		files:[
			'/uploads/tabs/1/20150924/1.jpg',
			'/uploads/tabs/1/20150924/2.jpg',
		],
		skips:[
		],
		repeats:[
		]
	},
];
var timer;
var current_tab = 0;
var current_position = 0;
function tab_scroll() {
	$('#tabs').css('top', - current_position);
}
function tab_reset() {
	current_position = 0;
	tab_scroll();
}
function tab_play() {

	timer = setInterval(function(){
		current_position += tabs[current_tab].speed;
		tab_scroll();
	},100);
}
function tab_pause() {
	clearInterval(timer);
}
function tab_init(tab) {
	html = '';
	for (i in tab.files) {
		html += '<div class="file">';
		html += '<img src="'+tab.files[i]+'" />';
		html += '</div>';
	}
	$('#tabs').html(html);
}
function bind_event() {
	$('#btn_play').click(function(){
		tab_play();
	});

	$('#btn_pause').click(function(){
		tab_pause();
	});

	$('#btn_reset').click(function(){
		tab_reset();
	});
}
$(function(){
	if (tabs.length > 0) {
		last_play = 0;
		tab_init(tabs[last_play]);
	} else {
		tab_empty();
	}
	bind_event();
});
</script>
</html>
