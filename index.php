<?php
error_reporting(0); if(!file_exists('urls.json')) { file_put_contents('urls.json', json_encode(array())); }
if(!empty($_SERVER['QUERY_STRING'])) {
	$string = $_SERVER['QUERY_STRING'];
	$urls = json_decode(@file_get_contents('urls.json'), true);
	if(isset($urls[$string])) {
		$url = $urls[$string];
		if(preg_match('/?/', $url)) {
			$url .= '&utm_source='.$_SERVER['SERVER_NAME'].'&utm_medium=short_url&utm_campaign=short_url';
		} else {
			$url .= '?utm_source='.$_SERVER['SERVER_NAME'].'&utm_medium=short_url&utm_campaign=short_url';
		}
		header('Location: '.$url); exit;
	} else {
		header('Content-type: text/html; charset=UTF-8');
		die("<b>Error</b>: The requested short URL doesn't exist. ");
	}
} else {
$short = false;
if(isset($_POST['url'])) {
	$urls = json_decode(@file_get_contents('urls.json'), true);
	$rurls = array_flip($urls);
	if(!isset($rurls[$_POST['url']])) {
		$lenght = 1;
		if(count($urls) > 33) { $lenght = 2; }
		if(count($urls) > 1089) { $lenght = 3; }
		if(count($urls) > 35937) { $lenght = 4; }
		if(count($urls) > 1185921) { $lenght = 5; }
		$chars = array_merge(range('a', 'z'), range(0, 9));
		shuffle($chars);
		$randomURL = implode(array_slice($chars, 0, $lenght));
		$urls[$randomURL] = $_POST['url'];
		file_put_contents('urls.json', json_encode($urls));
	} else {
		$randomURL = $rurls[$_POST['url']];
	}
	$short = $randomURL;
}
header('Content-type: text/html; charset=UTF-8');
?>
<html>
   <style>
      body {
         background-color: black;
      }
      b{
         color: 39ff14;
         font-family: "Lucida Console", "Courier New", monospace;
      }
      font{
         color: 39ff14;
         font-family: "Lucida Console", "Courier New", monospace;
      }
     center {
     padding: 350px 0;
     text-align: center;
     }
   </style>
<head>
<title>luka's url shortner</title>
</head>
<body>
  <center>
	<?php if($short): ?>
	<big><b>Your URL has been shortened! </b></big><br/>
	<font size="16px">http://s.luka.bar<?=str_replace(basename(__FILE__),'',$_SERVER['PHP_SELF'])?>?<?=$short?></font><br/><br/>
	<?php endif; ?>
	<br/>
  <p/><img src="https://i.ibb.co/0DTvm29/lurl.png"></p>
  <b>Please enter an URL: </b><br/>
	<form name="url" action="<?=$_SERVER['PHP_SELF']?>" method="post">
		<input name="url" type="text" /> <button type="submit">Shorten</button>
	</form>
</body>
</html>
<?php
}