<table align="center" style="background:#E0B949; height:30px; width:400px; font-family:Arial; color:white;"><tr><td valign="middle"><center><b>Copy Files</b></center></td></tr></table>

<table align="center" style="background:lightgray; width:400px; font-family:Arial;"><tr><td><center>

<br>
<b>
<?php
echo $_GET['path'];
echo $_GET['file'];
?>

<br>
<form method=post action=files/copy.php>
<input type=hidden name=path value=<?php echo $_GET['path']; ?>>
<input type=hidden name=from value=<?php echo $_GET['file']; ?>>
</b><br> will be copied to<br>
<input type=text name=dest value=<?php echo $_GET['path']; ?> style="width:90%; text-align:center;">

<br><br>

<input type=submit value="Copy File">

</center></td></tr></table>