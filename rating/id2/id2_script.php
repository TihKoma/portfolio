	<script type="text/javascript">
	
		var ID2_SITE_USER_ID = <?php echo $uid; ?>;
		
		var aktion = aktion || [];
			(function(){
				var s = document.createElement('script');
				s.type = 'text/javascript';
				s.async = true;
				s.src = '//m.action-media.ru/js/all.2.js';
				var es = document.getElementsByTagName('script')[0];
				es.parentNode.insertBefore(s, es);
			})();
			window.AsyncInit = function () {
				aktionid.init({
					appid: <?php echo $appid; ?>,
					emid: <?php echo $emid; ?>,
					rater: true,
					clientcallback: 'clientcallback',
					loginblock: 'panelcontainer',
					style: 'default',
					supportlink: 'https://id2.action-media.ru/Feedback',
					reglink: '<?php echo $reglink; ?>'
				});
					
					
				aktionid.subscribe("status.auth", "statusAuthCallback");
				aktionid.subscribe("token.change", "tokenChangeCallback");
				aktionid.subscribe("status.noauth", "statusNoauthCallback");
				aktionid.subscribe("user.custom", "userCustomCallback");
				
			

			};
			
			function statusAuthCallback(o) {
				
				if (ID2_SITE_USER_ID == 0 || (ID2_SITE_USER_ID > 0 && ID2_SITE_USER_ID != o.user.id)) {
					//alert (o.user.id);
					$.ajax({
						type: 'POST',
						url: 'id2/login.php',
						data: 'uid=' + o.user.id + '&token=' + o.status.token,
						success: function (msg) {
							if (msg == "1") {
								//alert('statusAuthCallback');
								location.reload();
							}
						}
					});
				}
			}
			
			function tokenChangeCallback(o) {
				$.ajax({
					type: 'POST',
					url: 'id2/login.php',
					data: 'uid=' + o.user.id + '&token=' + o.status.token,
					success: function (msg) {
						if(msg=="1") {
							//alert('tokenChangeCallback');
							location.reload();
						}
					}
				});
			}


	//		function statusNoauthCallback(o) {
	//			alert('statusNoauthCallback');
	//		}
	//		function userCustomCallback(o) {
	//		}

	</script>