<?php
if (!isset($_GET['do'])) {
?>

<html>

<head>
<title>The HelioPanel Project</title>
</head>

<body style="margin: 0px 0px 0px 0px;">

<center><img src="http://heliopanel.heliohost.org/images/logo.png"></center>

<br>

<table align=center cellpadding=10 style="background-image:url('http://heliopanel.heliohost.org/images/installbg2.png'); background-repeat:no-repeat; width:500px; height:500px; font-family:Trebuchet MS; text-align:center;"><tr><td valign=top>
<p><img src="http://heliopanel.heliohost.org/images/step3.png"><br>
<b><u>Installing HelioPanel</u></b> <a target="_blank" href="http://heliopanel.kayako.com/Knowledgebase/Article/View/1/0/installing-heliopanel"><img border=0 src="http://heliopanel.heliohost.org/images/helpicon.png"></a></p>
<p>Thank you for installing HelioPanel. You're nearly there now; just one more thing.</p>
<p>We need the username from your home directory's path; it is usually the same as your cPanel username. For example:</p>
<p><i>/home1/<b>jje</b>/</i></p>
<p>We also need a password. This password can be whatever you like and it doesn't have to be the same as your cPanel password, as long as it doesn't have any special characters.</p>
<form method=post action="install.php?do=install">
<textarea name="content" style="width:90%; height:80px;">
<?php
echo htmlspecialchars(file_get_contents('config.php'));
?>
</textarea>
<input type=submit value="Finish">
</form>
</td></tr></table>

<?php
}elseif ($_GET['do'] == 'install') {

$fh = fopen('config.php', 'w') or die("<b>Error, couldn't write config.php</b>");
fwrite($fh, $_POST['content']);
fclose($fh);

$zip = new ZipArchive;
if ($zip->open('editors.zip') === TRUE) {
    $zip->extractTo('./');
    $zip->close();
} else {
    echo 'Error, couldnt install editors.';
}

unlink('editors.zip');

header("location:install.php?do=finish");

}elseif ($_GET['do'] == 'finish') {
?>

<html>

<head>
<title>The HelioPanel Project</title>
</head>

<body style="margin: 0px 0px 0px 0px;">

<center><img src="http://heliopanel.heliohost.org/images/logo.png"></center>

<br>

<table align=center cellpadding=10 style="background-image:url('http://heliopanel.heliohost.org/images/installbg.png'); background-repeat:no-repeat; width:500px; height:400px; font-family:Trebuchet MS; text-align:center;"><tr><td valign=top>
<p><img src="http://heliopanel.heliohost.org/images/step3.png"><br>
<b><u>Installing HelioPanel</u></b> <a target="_blank" href="http://heliopanel.kayako.com/Knowledgebase/Article/View/1/0/installing-heliopanel"><img border=0 src="http://heliopanel.heliohost.org/images/helpicon.png"></a></p>
<p>Congratulations; HelioPanel has been installed!</p>
<p>Please delete the install.php file from your HelioPanel installation as it poses a security risk.</p>
<p>You are now free to <a href="./">login</a> to your HelioPanel and take advantage of the software.
If you have any questions please visit <a href="http://heliopanel.heliohost.org">The HelioPanel Project</a> website.</p>
</td></tr></table>

<p><strong>Removing install.php...</strong></p>
<?php
unlink(__FILE__);
?>
<p><strong>install.php removed</strong></p>

<?php
}
?>