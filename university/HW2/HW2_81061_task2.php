<html>
<head>
	<title>Electives</title>
</head>
<body>
<?php
require('HW2_81061_task1.php');
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

function show_nav($data, $pageId)
{
	$result = "<nav>";
	foreach($data as $subjectId => $subjectInfo)
	{
		$link = '<a href="?page='.$subjectId.'">'.$subjectInfo["title"].'</a>';
		if($subjectId == $pageId)
		{
			$link = '<a href="?page='.$subjectId.'" class="selected">'.$subjectInfo["title"].'</a>';
		}
		$result .= $link."<br>";
	}
	$result .= "</nav>";
	echo $result;
}
$pageId = (isset($_GET["page"])) ? $_GET["page"] : '';
echo show_nav($data, $pageId);
echo show_pages($pageId, $data);
?>
</body>
</html>