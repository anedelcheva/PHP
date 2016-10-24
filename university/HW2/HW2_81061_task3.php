<html>
<body>
<?php
class Request
{
	protected $server;
	function __construct($server)
	{
		$this->server = $server;
	}
	
	function getMethod()
	{
		return strtolower($this->server['REQUEST_METHOD']);
	}
	
	function getPath()
	{
		return $this->server['PHP_SELF'];
	}
	
	function getURL()
	{
		return "http://".$this->server["HTTP_HOST"].$this->server["PHP_SELF"];
	}
	
	function getUserAgent()
	{
		return $this->server["HTTP_USER_AGENT"];
	}
}
	
class GetRequest extends Request
{
	function getData()
	{
		$query_string = $this->server["QUERY_STRING"];
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

$request = new GetRequest($_SERVER);
echo $request->getMethod()."<br>";
echo $request->getPath()."<br>";
echo $request->getURL()."<br>";
echo $request->getUserAgent()."<br>";
echo $request->getData()."<br>";
?>
</body>
</html>