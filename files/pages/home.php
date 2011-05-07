
	<table><tr>
		        <td align=left width=40><a href=../home.php><img style="opacity:0.5;filter:alpha(opacity=50)" src=files/images/back-icon.png onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50"></a></td>
	         	<td align=left width=40><a href=addfile.php?path=<?php echo $_GET['path']; ?>><img style="opacity:0.5;filter:alpha(opacity=50)" src=files/images/addfile.png onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50"></a></td>
         		<td align=left width=40><a href=addfolder.php?path=<?php echo $_GET['path']; ?>><img style="opacity:0.5;filter:alpha(opacity=50)" src=files/images/addfolder.png onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50"></a></td>
         		<td align=left width=40><a href=upload.php?path=<?php echo $_GET['path']; ?>><img style="opacity:0.5;filter:alpha(opacity=50)" src=files/images/upload.png onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50"></a></td>
				
				<td align=left valign="bottom">
				<font size=1><br></font>
				<form method=post action="files/changedirectory.php">
				<font face=Verdana size=2>Current Directory:</font>
				<input type=text name=directory style="height:20px;" value="<?php echo $_GET['path']; ?>"><input type=submit>
				</form>
				</td>
	</tr></table>

	<font size=1><br></font><div align="center">
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	
	
	<?php
	
	$path = $_GET['path'];
	
 if ($handle = opendir($path)) {
   while (false !== ($file = readdir($handle)))
      {
          if ($file != "." && $file != "..")
	  {
	  
	  if($_GET['path'] == '/') {
	  	$slash = '';
	  }else{
	  	$slash = '/';
	  }
	  
	  if(is_file($path.''.$file.'')) {
	  $type = 'file';
	  }else{
	  $type = 'folder';
	  }
         	$thelist .= '
         	         		      	
         		<tr>
         		<td align=left width=40><img src=files/images/'.$type.'.png></td>
         		<td align=left><a href=files.php?path='.$path.''.$file.'/><font face=Verdana color=black>'.$file.'</font></a></td>
         		<td align=left width=40><a href=editcode.php?file='.$path.''.$file.'><img style="opacity:0.5;filter:alpha(opacity=50)" src=files/images/edit'.$type.'.png onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50"></a></td>
         		<td align=left width=40><a href=wysiwyg.php?file='.$path.''.$file.'><img style="opacity:0.5;filter:alpha(opacity=50)" src=files/images/wysiwyg'.$type.'.png onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50"></a></td>
				<td align=left width=40><a href=files.php?act=rename&path='.$path.'&file='.$file.'><img style="opacity:0.5;filter:alpha(opacity=50)" src=files/images/rename.png onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50"></a></td>
				<td align=left width=40><a href=files.php?act=copy&path='.$path.'&file='.$file.'><img style="opacity:0.5;filter:alpha(opacity=50)" src=files/images/copy.png onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50"></a></td>
         		<td align=left width=40><a href=files.php?act=move&path='.$path.'&file='.$file.'><img style="opacity:0.5;filter:alpha(opacity=50)" src=files/images/move.png onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50"></a></td>
         		<td align=left width=40><a href=delete.php?file='.$path.''.$file.'><img style="opacity:0.5;filter:alpha(opacity=50)" src=files/images/deletefile.png onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50"></a></td>
         		<td align=left width=40><a href=extract.php?file='.$path.''.$file.'><img style="opacity:0.5;filter:alpha(opacity=50)" src=files/images/extract.png onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50"></a></td>
         		<td align=left width=40><a href='.$path.''.$file.' target=_blank><img style="opacity:0.5;filter:alpha(opacity=50)" src=files/images/view.png onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50"></a></td>
         		</tr>
         	';
          }
       }
  closedir($handle);
  } else { echo "<font face=tahoma size=3><b>Sorry, the directory you are looking for doesn't exist.</b></font>"; }
?>

<?=$thelist?>
	
	</table>
</div>

<p align="center">&nbsp;
