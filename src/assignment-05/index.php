
<?php
$group = "Група: СП-41";
$developer = "Розробник: Бармак Роман Миколайович";
$created = "Дата створення: 20.10.2025";
$now = date("d.m.Y H:i");
$result = [];
$errors = [];

function safe($str) {
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Text
	$text = isset($_POST['text']) ? trim($_POST['text']) : '';
	if (!preg_match('/^[a-zA-Zа-яА-ЯёЁіІїЇєЄ0-9\s]{3,20}$/u', $text)) {
		$errors['text'] = 'Текст: 3-20 букв/цифр';
	} else {
		$result['text'] = $text;
	}
	// Password
	$password = isset($_POST['password']) ? $_POST['password'] : '';
	if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $password)) {
		$errors['password'] = 'Пароль: мінімум 8 символів, велика, мала літера, цифра';
	} else {
		$result['password'] = str_repeat('*', strlen($password));
	}
	// Email
	$email = isset($_POST['email']) ? trim($_POST['email']) : '';
	if (!preg_match('/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/', $email)) {
		$errors['email'] = 'Email некоректний';
	} else {
		$result['email'] = $email;
	}
	// Radio
	$radio = isset($_POST['radio']) ? $_POST['radio'] : '';
	if (!in_array($radio, ['one','two','three'])) {
		$errors['radio'] = 'Оберіть radio';
	} else {
		$result['radio'] = $radio;
	}
	// Checkbox (array)
	$checkbox = isset($_POST['checkbox']) ? $_POST['checkbox'] : [];
	if (!is_array($checkbox) || count($checkbox) < 1) {
		$errors['checkbox'] = 'Оберіть хоча б один checkbox';
	} else {
		$result['checkbox'] = implode(', ', $checkbox);
	}
	// Select
	$select = isset($_POST['select']) ? $_POST['select'] : '';
	if (!in_array($select, ['a','b','c'])) {
		$errors['select'] = 'Оберіть select';
	} else {
		$result['select'] = $select;
	}
	// Hidden
	$hidden = isset($_POST['hidden']) ? $_POST['hidden'] : '';
	if ($hidden !== 'hiddenvalue') {
		$errors['hidden'] = 'Hidden некоректний';
	} else {
		$result['hidden'] = $hidden;
	}
	// File (just name)
	if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
		$filename = $_FILES['file']['name'];
		if (!preg_match('/^[\w,\s-]+\.[A-Za-z]{3,4}$/', $filename)) {
			$errors['file'] = 'Файл: некоректна назва';
		} else {
			$result['file'] = $filename;
		}
	} else {
		$errors['file'] = 'Файл не вибрано';
	}
	// Textarea
	$textarea = isset($_POST['textarea']) ? trim($_POST['textarea']) : '';
	if (strlen($textarea) < 5) {
		$errors['textarea'] = 'Textarea: мінімум 5 символів';
	} else {
		$result['textarea'] = $textarea;
	}
	// Number
	$number = isset($_POST['number']) ? $_POST['number'] : '';
	if (!preg_match('/^(100|[1-9]?[0-9])$/', $number)) {
		$errors['number'] = 'Число від 0 до 100';
	} else {
		$result['number'] = $number;
	}
	// Phone
	$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
	if (!preg_match('/^\+\d{1,3}\.\d{4,14}(?: x.+)?$/', $phone)) {
		$errors['phone'] = 'Телефон у форматі +CCC.NNNNNN';
	} else {
		$result['phone'] = $phone;
	}
	// URL
	$url = isset($_POST['url']) ? $_POST['url'] : '';
	if (!preg_match('/^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})([\/\w .-]*)*\/?$/', $url)) {
		$errors['url'] = 'URL некоректний';
	} else {
		$result['url'] = $url;
	}
	// IP
	$ip = isset($_POST['ip']) ? $_POST['ip'] : '';
	if (!preg_match('/^((25[0-5]|2[0-4]\d|1\d{2}|\d{1,2})\.){3}(25[0-5]|2[0-4]\d|1\d{2}|\d{1,2})$/', $ip)) {
		$errors['ip'] = 'IP некоректний';
	} else {
		$result['ip'] = $ip;
	}
	// Date
	$date = isset($_POST['date']) ? $_POST['date'] : '';
	if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
		$errors['date'] = 'Дата у форматі YYYY-MM-DD';
	} else {
		$result['date'] = $date;
	}
	// Color
	$color = isset($_POST['color']) ? $_POST['color'] : '';
	if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
		$errors['color'] = 'Колір у форматі #RRGGBB';
	} else {
		$result['color'] = $color;
	}
}

?>
<html>
<head>
	<meta charset="utf-8">
	<title>Assignment 05</title>
</head>
<body>
<div class="block">
	<?php
	echo "<p>" . safe($group) . "</p>";
	echo "<p>" . safe($developer) . "</p>";
	echo "<p>" . safe($created) . "</p>";
	echo "<p>Поточна дата: " . safe($now) . "</p>";
	?>
</div>
<a href="../index.php">← Назад</a>
<h2>Форма для перевірки регулярних виразів (15 полів)</h2>
<form method="post" enctype="multipart/form-data">
	<div><label>Текст: <input type="text" name="text" value="<?php if(isset($_POST['text'])) echo safe($_POST['text']); ?>" maxlength="20"></label></div>
	<div><label>Пароль: <input type="password" name="password" maxlength="20"></label></div>
	<div><label>Email: <input type="text" name="email" value="<?php if(isset($_POST['email'])) echo safe($_POST['email']); ?>"></label></div>
	<div>Radio:
		<label><input type="radio" name="radio" value="one" <?php if(isset($_POST['radio']) && $_POST['radio']==='one') echo 'checked'; ?>>Один</label>
		<label><input type="radio" name="radio" value="two" <?php if(isset($_POST['radio']) && $_POST['radio']==='two') echo 'checked'; ?>>Два</label>
		<label><input type="radio" name="radio" value="three" <?php if(isset($_POST['radio']) && $_POST['radio']==='three') echo 'checked'; ?>>Три</label>
	</div>
	<div>Checkbox:
		<label><input type="checkbox" name="checkbox[]" value="red" <?php if(isset($_POST['checkbox']) && in_array('red',$_POST['checkbox'])) echo 'checked'; ?>>Червоний</label>
		<label><input type="checkbox" name="checkbox[]" value="blue" <?php if(isset($_POST['checkbox']) && in_array('blue',$_POST['checkbox'])) echo 'checked'; ?>>Синій</label>
		<label><input type="checkbox" name="checkbox[]" value="green" <?php if(isset($_POST['checkbox']) && in_array('green',$_POST['checkbox'])) echo 'checked'; ?>>Зелений</label>
	</div>
	<div><label>Select:
		<select name="select">
			<option value="">Оберіть...</option>
			<option value="a" <?php if(isset($_POST['select']) && $_POST['select']==='a') echo 'selected'; ?>>A</option>
			<option value="b" <?php if(isset($_POST['select']) && $_POST['select']==='b') echo 'selected'; ?>>B</option>
			<option value="c" <?php if(isset($_POST['select']) && $_POST['select']==='c') echo 'selected'; ?>>C</option>
		</select>
	</label></div>
	<input type="hidden" name="hidden" value="hiddenvalue">
	<div><label>Файл: <input type="file" name="file"></label></div>
	<div><label>Textarea: <textarea name="textarea"><?php if(isset($_POST['textarea'])) echo safe($_POST['textarea']); ?></textarea></label></div>
	<div><label>Число (0-100): <input type="text" name="number" value="<?php if(isset($_POST['number'])) echo safe($_POST['number']); ?>"></label></div>
	<div><label>Телефон (+CCC.NNNNNN): <input type="text" name="phone" value="<?php if(isset($_POST['phone'])) echo safe($_POST['phone']); ?>"></label></div>
	<div><label>URL: <input type="text" name="url" value="<?php if(isset($_POST['url'])) echo safe($_POST['url']); ?>"></label></div>
	<div><label>IP: <input type="text" name="ip" value="<?php if(isset($_POST['ip'])) echo safe($_POST['ip']); ?>"></label></div>
	<div><label>Дата: <input type="text" name="date" value="<?php if(isset($_POST['date'])) echo safe($_POST['date']); ?>" placeholder="YYYY-MM-DD"></label></div>
	<div><label>Колір: <input type="text" name="color" value="<?php if(isset($_POST['color'])) echo safe($_POST['color']); ?>" placeholder="#RRGGBB"></label></div>
	<div>
		<input type="submit" value="Відправити">
		<input type="reset" value="Очистити">
		<button type="button" onclick="window.location.reload()">Оновити</button>
	</div>
</form>
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') { ?>
	<div style="margin-top:20px;">
		<h3>Результати:</h3>
		<?php if ($errors) { ?>
			<div style="color:red;">
				<?php foreach ($errors as $k=>$v) echo safe($v)."<br>"; ?>
			</div>
		<?php } else { ?>
			<?php foreach ($result as $k=>$v) { ?>
				<div style="border:1px solid #ccc; margin:5px; padding:5px;"> <?php echo safe($k).": ".safe($v); ?> </div>
			<?php } ?>
		<?php } ?>
	</div>
<?php } ?>
</body>
</html>
