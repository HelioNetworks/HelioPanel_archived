<?php

// Include the header file
require 'header.php';

// If the home page was requested
if (!isset($_GET['r'])) {

if($_GET['path'] == '/') {
	$path = $homedir.'/';
}else{
	$path = $_GET['path'];
}
?>

<table class="filestoolbar"><tr>
<td width=40 valign=middle><img border=0 src="images/files/addfile.png" style="cursor:pointer; opacity:0.5;filter:alpha(opacity=50)" onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50" onClick="javascript:popUp('files/newfile.php?path=<?php echo $path; ?>')"></td>
<td width=40 valign=middle><img border=0 src="images/files/addfolder.png" style="cursor:pointer; opacity:0.5;filter:alpha(opacity=50)" onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50" onClick="javascript:popUp('files/newdirectory.php?path=<?php echo $path; ?>')"></td>
<td width=40 valign=middle><img border=0 src="images/files/upload.png" style="cursor:pointer; opacity:0.5;filter:alpha(opacity=50)" onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50" onClick="javascript:popUp('files/uploadfile.php?path=<?php echo $path; ?>')"></td>
<td valign=middle><form method=get action=files.php><font size=1><br></font><input type=text name=path value="<?php echo $_GET['path']; ?>"><input type=submit></form></td>
</tr></table>

<table width="100%">

<?php


if ($files = $fileRepository->ls($path)) {

   foreach ($files as $file) {

          if ($file != "." && $file != "..") {

	  		$type = $file['type'];
	  		$file = $file['file'];

         	$thelist .= '

         		<tr>
         		<td align=left width=40><img src=images/files/'.$type.'.png></td>
         		<td align=left><a href=files.php?path='.$path.''.$file.'/><font face=Verdana color=black>'.$file.'</font></a></td>
         		<td align=left width=40><a href=files.php?r=editcode&file='.$path.''.$file.'><img style="opacity:0.5;filter:alpha(opacity=50)" src=images/files/edit'.$type.'.png onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50"></a></td>
         		<td align=left width=40><a href=files.php?r=wysiwyg&file='.$path.''.$file.'><img style="opacity:0.5;filter:alpha(opacity=50)" src=images/files/wysiwyg'.$type.'.png onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50"></a></td>
				<td align=left width=40><img onClick="javascript:popUp(\'files/rename.php?path='.$path.'&file='.$file.'\')" style="cursor:pointer; opacity:0.5;filter:alpha(opacity=50)" src=images/files/rename.png onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50"></a></td>
				<td align=left width=40><img onClick="javascript:popUp(\'files/copy.php?path='.$path.'&file='.$file.'\')" style="cursor:pointer; opacity:0.5;filter:alpha(opacity=50)" src=images/files/copy.png onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50"></a></td>
         		<td align=left width=40><img onClick="javascript:popUp(\'files/move.php?path='.$path.'&file='.$file.'\')" style="cursor:pointer; opacity:0.5;filter:alpha(opacity=50)" src=images/files/move.png onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50"></a></td>
         		<td align=left width=40><img onClick="javascript:popUp(\'files/delete.php?file='.$path.''.$file.'\')" style="cursor:pointer; opacity:0.5;filter:alpha(opacity=50)" src=images/files/deletefile.png onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50"></a></td>
         		<td align=left width=40><img onClick="javascript:popUp(\'files/extract.php?path='.$path.'&file='.$file.'\')" style="cursor:pointer; opacity:0.5;filter:alpha(opacity=50)" src=images/files/extract.png onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50"></a></td>
         		</tr>
         	';
          }
       }
  closedir($handle);
}else{
	echo "<font face=tahoma size=3><b><center>Sorry, the directory you are looking for doesn't exist.</center></b></font>";
}

echo $thelist;
echo "</table>";

}elseif ($_GET['r'] == 'editcode') { ?>

<IFRAME src="files/editcode.php?file=<?php echo $_GET['file']; ?>" width=100% height=450 frameborder=0 border=0 scrolling=no>Browser incompatible.</IFRAME>

<?php
}elseif ($_GET['r'] == 'wysiwyg') { ?>

<IFRAME src="files/wysiwyg.php?file=<?php echo $_GET['file']; ?>" width=100% height=450 frameborder=0 border=0 scrolling=no>Browser incompatible.</IFRAME>

<?php
}

// Include the footer file
require 'footer.php';