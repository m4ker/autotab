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
#main{
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
	/*width:60%;*/
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
#btn_pause {
	display:none;
}
#btn_reset {
	display:none;
}
#btn_play, #btn_pause, #btn_reset{
	margin-left:5px;
	padding: 5px;
}
</style>
</head>
<body>
<div id="container">
    <div id="main" class="">
	<div id="tabs">
	</div>
    </div>
    <nav id="bottombar" class="">
	<button id="btn_play">Play</button><button id="btn_pause">Pause</button><button id="btn_reset">Reset</button>
    </nav>
</div>
</body>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript">
var tabs = [
	{
		id:1,
		name:'贝加尔湖畔',
		speed:200, // 这个数值代表多少秒滚动一张曲谱
		scale:0.6,
		files:[
			'./uploads/tabs/1/20150924/1.jpg',
			'./uploads/tabs/1/20150924/2.jpg',
			'./uploads/tabs/1/20150926/1.png',
			'./uploads/tabs/1/20150926/2.png',
			'./uploads/tabs/1/20150926/3.png'
		],
		size:[
			[2480,3508],
			[2480,3508]
		],
		skips:[
			[0,0.1], // 第一页页头
			[0.85,1], // 第一页页尾
			[0.85,1] // 第一页页尾 重复一次
		],
		repeats:[
			//[0.6,0.3],
			[1.7,0.7]
		]
	},
	{
		id:2,
		name:'贝加尔湖畔2',
		speed:1,
		scale:0.8,
		files:[
			'/uploads/tabs/1/20150924/1.jpg',
			'/uploads/tabs/1/20150924/2.jpg',
		],
		size:[
			[2480,3508],
			[2480,3508]
		],
		skips:[
		],
		repeats:[
		]
	},
];
var timer;

// 窗口尺寸
var screen_width  = 0;
var screen_height = 0;

// 曲谱缩放后尺寸
var tab_width  = 0;
var tab_height = 0;

// 曲谱每0.1秒滚动距离
var step = 0;

var current_tab_index = 0;
var current_tab;

// 当前滚动距离
var current_scroll = 0;
// 当前滚动事件
var current_time = 0;
function clone(obj){
	var o;
	if(typeof obj == "object"){
		if(obj === null){
			o = null;
		}else{
			if(obj instanceof Array){
				o = [];
				for(var i = 0, len = obj.length; i < len; i++){
					o.push(clone(obj[i]));
				}
			}else{
				o = {};
				for(var k in obj){
					o[k] = clone(obj[k]);
				}
			}
		}
	}else{
		o = obj;
	}
	return o;
}
function tab_scroll(offset) {
	//current_scroll += offset;
	//console.log(current_scroll);
	//window.scrollTo(0, current_scroll);
	position = get_real_position_by_time();

	scroll = position * tab_height - (tab_height * 0.3);
	if (scroll < 0)
		scroll = 0;
	//console.log(position);
	window.scrollTo(0, scroll);
}
function tab_reset() {
	//current_position = $(window).scrollTop();
	//tab_scroll(-current_position);
}
function tab_play() {
	timer = setInterval(function(){
		tab_scroll(step);
		current_time += 0.1;
	},100);
}
function tab_pause() {
	clearInterval(timer);
}
function tab_init(tab) {

	current_tab = tab;

	html = '';
	for (i in tab.files) {
		html += '<div class="file">';
		html += '<img src="'+tab.files[i]+'" />';
		html += '</div>';
	}
	$('#tabs').html(html);

	$('#tabs .file img').css('width', (tab.scale * 100) + '%');

	// todo: why doesn't work
	//window.scrollTo(0, 0);	
}
// 取得最优缩放比例
function get_scale(screen_width, screen_height, tab_width,  tab_height) {
	if (screen_width > screen_height)
		return (screen_height/0.6) / tab_height;
	else
		return tab_width / screen_width;
}

function get_position_by_time() {
	position = current_time / current_tab.speed;
	return position;
}

function get_real_position_by_time() {
	ct = clone(current_tab);

	position = current_time / current_tab.speed;

	real_position = position;

	// 跳过无效区域
	for (i in ct.skips) {
		if ((ct.skips[i][0] < real_position) && ((typeof ct.skips[i][2]) == 'undefined')) {
			real_position += ct.skips[i][1] - ct.skips[i][0];
			ct.skips[i][2] = 1;
		}
	}

	// 计算重复区域
	for (i in ct.repeats) {
		if ((ct.repeats[i][0] < real_position) && ((typeof ct.repeats[i][2]) == 'undefined')) {
			real_position -= ct.repeats[i][0] - ct.repeats[i][1];
			ct.repeats[i][2] = 1;
		}
	}

	console.log(position + " " + real_position);

	return real_position;
}

function bind_event() {
	$('#btn_play').click(function(){
		tab_play();
		$('#btn_pause').show();
		$('#btn_play').hide();
	});

	$('#btn_pause').click(function(){
		tab_pause();
		$('#btn_play').show();
		$('#btn_pause').hide();
	});

	$('#btn_reset').click(function(){
		tab_reset();
	});

	$(window).scroll(function(){
		if (timer) {
			current_position = $(window).scrollTop();
			if (current_position < (current_scroll - 1)) {
				tab_pause();
				$('#btn_play').show();
				$('#btn_pause').hide();
			}
		}
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

	// 窗口尺寸
	screen_width = $(window).width();
	screen_height = $(window).height();

	// 曲谱缩放后尺寸
	scale = (screen_width * 0.6) / current_tab.size[0][0];

	tab_width = current_tab.size[0][0] * scale;
	tab_height = current_tab.size[0][1] * scale; // 还是有偏差 1147/1159怀疑与滚动条有关

	//console.log(tab_height);

	// 曲谱每0.1秒滚动距离
	step = tab_height / (current_tab.speed * 10);

	//console.log(step);
});
</script>
</html>
