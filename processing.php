<?php # processing.php
require 'classes/PrettyJSON.php';

$error = '';
$rHtml = '';

if (!isset($_POST['json'])) {
	$error = 'In _POST not found param JSON';
} else {
	$json   = (string)$_POST['json'];
	$engine = new PrettyJSON();
	$engine->setJsonText($json);

	if ($engine->isError()) {
		$error = $engine->getError();
	} else {
		$rHtml = $engine->getHtml();
	}
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Processing JSON convert result</title>
</head>
<body>
<?php if (strlen($error) > 0) : ?>
	<div>Error: <?php echo($error); ?></div>
<?php else: ?>
	<div><?php echo($rHtml); ?></div>
<?php endif; ?>
</body>
</html>
