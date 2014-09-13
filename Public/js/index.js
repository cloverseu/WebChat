jQuery(document).ready(function($) {

	$(window).load(function() {
		$.post('index.php/Home/Index/checkSession',
			function(data) {
				window.location.replace('index.php/Home/Chat/index');
			}, 'json');
	});

	$('#register').click(function() {
		var username = $('#username').val();
		var password = $('#password').val();
		if (username == '') {
			alert("用户名不能为空");
			username.focus();
			return;
		}
		if (password == '') {
			alert("密码不能为空");
			password.focus();
			return;
		}

		$.post('index.php/Home/Index/register', {
				'username': username,
				'password': password
			},
			function(data) {
				if (data.status) {
					var id = data.id;
					alert("注册成功,您的ID为" + id);
					$('#registerModal').modal('hide');

				} else {
					alert('注册失败');
					$('#registerModal').modal('hide');
				}
			}, 'json');
	});


	$('#username').change(function() {
		var username = $('#username').val();
		$.post('index.php/Home/Index/check', {
				'username': username
			},
			function(data) {
				if (data.status == "0") {
					alert('该用户已经被注册');
					$('#username').focus();
					return;
				}
			}, 'json');
	});

	$('#login').click(function() {
		var username = $('#user').val();
		var password = $('#pwd').val();
		if (username == '') {
			alert("用户名不能为空");
			$('#user').focus();
			return;
		}
		if (password == '') {
			alert("密码不能为空");
			$('#password').focus();
			return;
		}

		$.post('index.php/Home/Index/login', {
				'username': username,
				'password': password
			},
			function(data) {
				if (data.status == "1") {
					window.location.replace('index.php/Home/Chat/index');
				} else {
					alert("密码或者用户名错误");
				}
			}, 'json');
	});

	$('#verify').change(function() {
		var verify = $('#verify').val();
		if (verify == '') {
			alert("验证码不能为空");
			$('#verify').focus();
			return;
		}
		$.post('index.php/Home/Index/check_verify', {
				'verify': verify
			},
			function(data) {
				if (data.status != "1") {
					alert("验证码错误");
				}
			}, 'json');
	});

	$('#verifyImg').click(function() {
		var timenow = new Date().getTime();
		document.getElementById('verifyImg').src = 'index.php/Home/Index/Verify/' + timenow;
	})

});