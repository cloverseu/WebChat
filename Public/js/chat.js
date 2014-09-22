jQuery(document).ready(function($) {

	$(window).load(function() {
		$.post('receiveNews',
			function(data) {
				for (var p in data) {
					$('#' + data[p] + 'news').html("news");
				}
			}, 'json');

		$.post('receiveNotices',
			function(data) {
				$('#noticeBadge').html(data);
			}, 'json');

	});

	$('#add').click(function() {
		var add_id = $('#add_id').val();
		$.post('add', {
				'add_id': add_id
			},
			function(data) {
				$('#add_id').val("");
				if (data.status == "1") {
					alert("信息已经发送，等待对方回应");
				} else {
					alert("该用户不存在或者该用户已是您好友");
				}
			}, 'json');
	});

	$('.media').click(function() {
		$('#close_chat').show();
		var id = $(this).attr('getterId');
		var getter = $(this).attr('getter');
		var fetchMessage = setInterval(function() {
			receive(getter)
		}, 2000);
		$('#' + id + 'news').html("");
		$.post('../List/index', {
				'getter': getter
			},
			function(data) {
				if (data) {
					$('#sendMessage').html(data.content);
				}
			}, 'json');
	});

	$('.btn.btn-default.send').click(function() {
		var content = $('#content').val();
		var getter = $(this).attr('getter');
		$.post('../Message/index', {
				'getter': getter,
				'content': content
			},
			function(data) {
				if (data) {
					$('#content').val("");
					$('#chatRoom').append(data.content);
				}
			}, 'json');
	});

	function receive(sender) {
		$.post('../ReceiveMsg/index', {
				'sender': sender
			},
			function(data) {
				if (data) {
					$('#chatRoom').append(data.chat);
				}
			}, 'json');
	}

	$('#close').click(function() {
		$('#close_chat').hide();
	});

	$('#out').click(function() {
		$.post('out', '', function(data) {
			if (data) {
				window.location.replace('../../../');
			} else {
				alert("网络错误");
				window.close();
			}
		}, 'json');
	});

	$("img").click(function() {
		var exp = $(this).attr('value');
		var str = $('#content').val() + exp;
		$('#content').val(str);
	});

	$("#notice").click(function(){
		$('#noticeBadge').hide();
	});

	$('.btn.btn-primary.deal').click(function() {
		var dealId = $(this).attr('dealId');
		var type = $(this).attr('idType');
		$.post('dealId', {
				'id':dealId,
				'type':type
			}, function(data){
				$('#'+dealId+'deal').hide();
			},'json');
	});

	$('#weather').click(function(){
		var city = $('#city').val();
		var type = "weather";
		$.post('basicFunction',{
			'city':city,
			'type':type
		},function(data){
				var weatherResult = "<br><span>日期: "+data.date+"</span><br><br>白天:&nbsp;<img src="+data.dayPictureUrl+">&nbsp;&nbsp;晚上:&nbsp;<img src="+data.nightPictureUrl+"><br><br>天气:&nbsp;<span>"+data.weather+"</span>&nbsp;&nbsp;风力:&nbsp;"+data.wind+"</span>";
					$('#showWeather').html(weatherResult);
		},'json');
	});

	$('#movie').click(function(){
		var city = "南京";
		var type = "movie";
		$.post('basicFunction',{
			'city':city,
			'type':type
		},function(data){
				 var movieResult = "<br><img src="+data[0]['movie_picture']+"><br><br>电影:&nbsp;"+data[0]['movie_name']+"<br><br>演员:&nbsp;"+data[0]['movie_starring']+"<br><br>简介:&nbsp;"+data[0]['movie_message'];
				 $('#showMovie').html(movieResult);
		},'json');
	});

	$('#cet').click(function(){
		var cetName = $('#cet_name').val();
		var cetId = $('#cet_id').val();
		$.post('cet',{
			'cetName':cetName,
			'cetId':cetId
		},function(data){
			var cetResult ="<br>姓名:&nbsp;"+data[0][0]+"<br><br>学校:&nbsp;"+data[0][1]+"<br><br>准考证号:&nbsp"+data[0][2]+"<br><br>听力:"+data[0][3]+"&nbsp;&nbsp;&nbsp;阅读:&nbsp"+data[0][4]+"<br><br>写作:&nbsp;"+data[0][5]+"&nbsp;&nbsp;总分:&nbsp"+data[0][6];	 	 
				 $('#showCet').html(cetResult);
		},'json');
	});
});