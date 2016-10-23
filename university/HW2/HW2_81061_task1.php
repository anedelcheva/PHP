<html>
<head>
	<title>Electives</title>
</head>
<body>
<?php 
$data = array(
	'calculus' => array(
		'title' => 'Диференциално и интегрално смятане',
		'lecturer' => 'доц. Георги Александров',
		'description' => 'Смятане на интеграли, производни и други забавни неща'
	),
	'fp' => array(
		'title' => 'Функционално програмиране',
		'lecturer' => 'проф. Мария Нишева',
		'description' => 'Програмиране с Haskell и Scheme'
	),
);

function show_pages($pageId, $data)
{
	if($pageId == '')
		return '';
	$subject = $data[$pageId];
	$result = "<h1>".$subject["title"]."</h1>";
	$result .= "<h2>".$subject["lecturer"]."</h2>";
	$result .= "<p>".$subject["description"]."</p>";
	return $result;
}
//echo show_pages("calculus", $data);
?>
</body>
</html>