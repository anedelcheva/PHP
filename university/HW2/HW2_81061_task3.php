<html>
<body>
<?php
class Request
{
	//function __construct($_SERVER)
	//{
		
	//}
	
	function getMethod()
	{
		return strtolower($_SERVER['REQUEST_METHOD']);
	}
	
	function getPath()
	{
		return $_SERVER['PHP_SELF'];
	}
	
	function getURL()
	{
		return "http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"];
	}
	
	function getUserAgent()
	{
		return $_SERVER["HTTP_USER_AGENT"];
	}
}
	
class GetRequest extends Request
{
	function getData()
	{
		$query_string = $_SERVER["QUERY_STRING"];
		//$query_string = "a=1&b=2&c=3";
		$query_string = str_replace("=", ":", $query_string);
		$query_string = str_replace("&", ",", $query_string);
		$result = '';
		for($i = 0; $i < strlen($query_string); $i++)
		{
			if($query_string[$i] == ":")
				$result .= '":"';
			elseif($query_string[$i] == ',')
				$result .= '","';
			else
				$result .= $query_string[$i];
		}
		$result = '{"'.$result.'"}';
		return $result;
	}
}

$request = new GetRequest();
echo $request->getMethod()."<br>";
echo $request->getPath()."<br>";
echo $request->getURL()."<br>";
echo $request->getUserAgent()."<br>";
echo $request->getData()."<br>";
?>
</body>
</html>