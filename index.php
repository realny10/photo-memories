<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Photo memories</title>

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="style.css"/>
</head>
<body>
<form action="img.php" method="post" enctype="multipart/form-data">
	<dl>
		<dt>
			Dzień
		</dt>
		<dd>
			<input name="day" type="number" value="1" min="1"/>
		</dd>

		<dt>
			Zdjęcie
		</dt>
		<dd>
			<input name="file" type="file" />
		</dd>

		<dt>
			Trasa
		</dt>
		<dd>
			<textarea name="route" class="input-route"></textarea>
		</dd>

		<dt>
			Opis
		</dt>
		<dd>
			<textarea name="description"></textarea>
		</dd>

		<dt>
			Lokalizacja - <a href="#" class="jQ_getLocalization">Pobierz</a>
		</dt>
		<dd>
			<input type="text" name="coords" readonly />
		</dd>

		<dd>
			<button type="submit">Zapisz</button>
		</dd>
	</dl>
</form>
</body>
</html>