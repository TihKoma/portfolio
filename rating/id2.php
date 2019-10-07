<?php
	require_once 'id2/config.php';
	require_once 'id2/id2class.php';
	$id2 = new Id2action($appid, $pass);
	$reglink = $id2->getRegistrationLink($callbackurl);

	
	$profile = $id2->GetProfile($token);
	$id2uid = $profile['Data']['Id'];
	$fname = $profile['Data']['FirstName'];
	$sname = $profile['Data']['LastName'];
	$uemail = $profile['Data']['Email'];
	
	$uname = "$FirstName $LastName";
	
	$utcode = md5($uid);


?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>

        <title>тест id2</title>

        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="author" content="">
        
        <!-- viewport settings -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		
		<?php require_once 'id2/id2_script.php'; ?>
		
		
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->



</head>

	<body>
	
		<div id="panelcontainer" style="padding: 13px;"></div>
		
		
		<?php
		
		// if ($token) {
		
		// 	echo "BitrixID: $uid
		// 	<br>Имя: $fname
		// 	<br>Фамилия: $sname
		// 	<br>Email: $uemail
		// 	<br>Идентификатор: $utcode";
			
		// } else {
			
		// 	echo "<button type=\"button\" class=\"btn btn-primary\" data-toggle=\"modal\" data-target=\"#myModal\">Участвовать в рейтинге</button>";
			
		// }
		
		?>
		
		
		



<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Авторизация/регистрация</h4>
      </div>
      <div class="modal-body">
        Для участия в рейтинга вам необходимо <span class="id2-rx-user-informer-button id2-rx-noauth-informer-button">авторизоваться или зарегистрироваться</span>
      </div>
      
    </div>
  </div>
</div>



	

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>

	</body>
	
	
</html>