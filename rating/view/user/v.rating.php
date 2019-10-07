<!DOCTYPE html>
<html>
<head>
	<title>Рейтинг</title>
</head>
<body>

	<?php
		$cnt = count($ratingList);

		for ($i = 0; $i < $cnt; $i++) {
	?>
			<p>
				<?php echo $ratingList[$i]['surname'].' '.$ratingList[$i]['name'].' '.$ratingList[$i]['patronymic'].' '.$ratingList[$i]['score']; ?>
			</p>
	<?php
		};
	?>

</body>
</html>