<?php

if (!empty($_REQUEST['url'])) {
	require_once(__DIR__.'/vendor/autoload.php');
	require_once(__DIR__.'/config_auth.php');
	require_once(__DIR__.'/KinoPoiskFilm.php');

	try {
		$kp = new KinoPoiskFilm($config_auth, $_REQUEST['url']);
		$name = $kp->getFilmName();
	} catch(\Exception $e) {
		$error = 'Ошибка получения данных';
	}
}
?>

<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Тест здание - Парсинг Кинопоиска</title>
	</head>

	<body>
		<h2>Тест здание - Парсинг Кинопоиска</h2>

		<form action="">
			<label for="field_url">Получить название</label>
			<input id="field_url" type="text" name="url" value="https://www.kinopoisk.ru/film/mafiya-2012-586568/">
			<button type="submit">Получить данные</button>
		</form>
		<?php if (!empty($error)) {
			echo 'Ошибка: '.$error;
		}
		if (!empty($name)) {
			echo '<h4> Название фильма: ' . $name . '</h4>';
		}

		?>
	</body>

</html>

