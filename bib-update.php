<?php
// config
$password = 'Bibi.Blocksberg.2022';
$repoURL = 'file:///var/www/html/bibtest/';
$workURL = '/var/www/html/bibtest';

// init
$log = (string) '';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de">
<head>
  <title>bibtest - Update to head revision</title>
    <style type="text/css">
	* { font-family: sans-serif }
	body { font-size: 10pt }
    </style>
</head>
	
<body>
  <h1>bibtest - Update to head revision</h1>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST' and $_POST['password'] == $password){
?>
  <h2>System says</h2>
  <pre>
<?php
	exec('git pull '.$repoURL.' ./vip/', $log);
	foreach($log as $no => $row)
		echo '<strong>'.$no.'</strong>: '.$row."\r\n";
}else{
	exec('cd '.$workURL.'; git log -u', $log);
	echo '<strong>'.$log[0].'</strong>';
}
?>
  </pre>

  <h2>Trigger update</h2>
  <form action="<?php echo $_SERVER['REQUEST_URI']?>" method="post">
    <div>
	    <label for="password" style="display: block">Passwort</label>
	    <input type="password" size="20" id="password" name="password" />
    </div>
    <div>
	    <input type="submit" value="checkout" />
    </div>
  </form>
</body>

</html>
