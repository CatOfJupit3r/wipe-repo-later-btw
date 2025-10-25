<?php
$group = "Група: СП-41";
$developer = "Розробник: Бармак Роман Миколайович";
$created = "Дата створення: 25.10.2025";
$now = date("d.m.Y H:i");

$D = [
	[0.2, -7.1, -3.5, 4.1],
	[2.4, -1.7, 3.1, 2.2],
	[-8.3, 4.1, -2.2, -1.7]
];

$maxNegGtMinus10 = null;
foreach ($D as $row) {
	foreach ($row as $v) {
		if ($v < 0 && $v > -10) {
			if ($maxNegGtMinus10 === null || $v > $maxNegGtMinus10) {
				$maxNegGtMinus10 = $v;
			}
		}
	}
}

$transformed = [];
if ($maxNegGtMinus10 !== null) {
	foreach ($D as $rIdx => $row) {
		$transformed[$rIdx] = [];
		foreach ($row as $cIdx => $v) {
			$transformed[$rIdx][$cIdx] = $v * $v; // square
		}
	}
	$transformMode = 'square';
} else {
	foreach ($D as $rIdx => $row) {
		$transformed[$rIdx] = [];
		foreach ($row as $cIdx => $v) {
			// cube root with sign preservation
			$transformed[$rIdx][$cIdx] = ($v >= 0) ? pow($v, 1.0/3.0) : -pow(abs($v), 1.0/3.0);
		}
	}
	$transformMode = 'cuberoot';
}

// 3) Compute products of elements of the first and second rows (rows 0 and 1)
$prodRow0 = array_product($transformed[0]);
$prodRow1 = array_product($transformed[1]);

// 4) Compute sums of elements of the first and second columns (columns 0 and 1)
$sumCol0 = 0.0;
$sumCol1 = 0.0;
foreach ($transformed as $row) {
	$sumCol0 += $row[0];
	$sumCol1 += $row[1];
}

// 5) Determine the smallest among these four values and raise it to the 5th power
$values = [
	'prod_row_0' => $prodRow0,
	'prod_row_1' => $prodRow1,
	'sum_col_0' => $sumCol0,
	'sum_col_1' => $sumCol1
];
$minKey = array_keys($values, min($values))[0];
$minValue = $values[$minKey];
$minPow5 = pow($minValue, 5);

// Helper to render a matrix as HTML table
function render_matrix_table($matrix)
{
	$html = "<table class=\"matrix\">";
	foreach ($matrix as $row) {
		$html .= '<tr>';
		foreach ($row as $v) {
			$html .= '<td>' . htmlspecialchars(round($v, 4)) . '</td>';
		}
		$html .= '</tr>';
	}
	$html .= '</table>';
	return $html;
}
?>
<!doctype html>
<html lang="uk">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Завдання 04 — Обробка матриць</title>
	<style>
		body{font-family:Segoe UI,Arial;margin:20px}
		.matrix{border-collapse:collapse;margin:10px 0}
		.matrix td{border:1px solid #999;padding:6px 10px;text-align:center}
		.blocks{display:flex;gap:12px;flex-wrap:wrap}
		.block{border:1px solid #ccc;padding:10px;border-radius:6px;min-width:220px}
		.figure{margin-top:12px}
		.legend{font-size:0.9em;color:#555}
	</style>
</head>
<body>

	<h1>Завдання 04 — Обробка матриць</h1>

	<div class="block">
		<?php
		echo "<p>$group</p>";
		echo "<p>$developer</p>";
		echo "<p>$created</p>";
		echo "<p>Поточна дата: $now</p>";
		?>
	</div>

	<h2>Вхідна матриця D</h2>
	<?= render_matrix_table($D) ?>

	<h2>Перетворення</h2>
	<div class="blocks">
		<div class="block">
			<strong>Застосоване правило:</strong> <?= ($transformMode === 'square') ? 'квадрат кожного елемента (знайдено макс. від\'ємне > -10: ' . $maxNegGtMinus10 . ')' : 'кубічний корінь кожного елемента (макс. від\'ємних > -10 не знайдено)' ?>
		</div>
		<div class="block">
			<strong>Перетворена матриця</strong>
			<?= render_matrix_table($transformed) ?>
		</div>
	</div>

	<h2>Обчислені результати</h2>
	<div class="blocks">
		<div class="block">
			<strong>Добуток елементів першого рядка (рядок 1):</strong>
			<div><?= htmlspecialchars($prodRow0) ?></div>
		</div>
		<div class="block">
			<strong>Добуток елементів другого рядка (рядок 2):</strong>
			<div><?= htmlspecialchars($prodRow1) ?></div>
		</div>
		<div class="block">
			<strong>Сума елементів першого стовпчика (стовпчик 1):</strong>
			<div><?= htmlspecialchars($sumCol0) ?></div>
		</div>
		<div class="block">
			<strong>Сума елементів другого стовпчика (стовпчик 2):</strong>
			<div><?= htmlspecialchars($sumCol1) ?></div>
		</div>
		<div class="block">
			<strong>Мінімальне серед чотирьох значень:</strong>
			<div><?= htmlspecialchars($minKey . ' = ' . $minValue) ?></div>
			<strong>Мінімальне значення в 5 ступені:</strong>
			<div><?= htmlspecialchars($minPow5) ?></div>
		</div>
	</div>

	<h2>Візуалізація (проста)</h2>
	<p class="legend">Перетворена матриця відображена як кольорова сітка (червоний = від\'ємне, зелений = додатне)</p>
	<div class="figure">
		<table class="matrix">
			<?php foreach ($transformed as $row): ?>
				<tr>
					<?php foreach ($row as $v):
						$color = ($v < 0) ? '#f8d7da' : '#d4edda';
						$text = round($v, 4);
					?>
					<td style="background:<?= $color ?>"><?= htmlspecialchars($text) ?></td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>

</body>
</html>
