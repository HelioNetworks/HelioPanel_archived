<?php
// PHP iUnTAR Version 3.0
// license: Revised BSD license
function untar($tarfile,$outdir,$chmod=null) {
$TarSize = filesize($tarfile);
$TarSizeEnd = $TarSize - 1024;
if($outdir!=""&&!file_exists($outdir)) {
                mkdir($outdir,0777); }
$thandle = fopen($tarfile, "r");
while (ftell($thandle)<$TarSizeEnd) {
                $FileName = $outdir.trim(fread($thandle,100));
                $FileMode = trim(fread($thandle,8));
                if($chmod===null) {
                                $FileCHMOD = octdec("0".substr($FileMode,-3)); }
                if($chmod!==null) {
                                $FileCHMOD = $chmod; }
                $OwnerID = trim(fread($thandle,8));
                $GroupID = trim(fread($thandle,8));
                $FileSize = octdec(trim(fread($thandle,12)));
                $LastEdit = trim(fread($thandle,12));
                $Checksum = trim(fread($thandle,8));
                $FileType = trim(fread($thandle,1));
                $LinkedFile = trim(fread($thandle,100));
                fseek($thandle,255,SEEK_CUR);
                if($FileType=="0") {
                                $FileContent = fread($thandle,$FileSize); }
                if($FileType=="1") {
                                $FileContent = null; }
                if($FileType=="2") {
                                $FileContent = null; }
                if($FileType=="5") {
                                $FileContent = null; }
                if($FileType=="0") {
                                $subhandle = fopen($FileName, "a+");
                                fwrite($subhandle,$FileContent,$FileSize);
                                fclose($subhandle);
                                chmod($FileName,$FileCHMOD); }
                if($FileType=="1") {
                                link($FileName,$LinkedFile); }
                if($FileType=="2") {
                                symlink($LinkedFile,$FileName); }
                if($FileType=="5") {
                                mkdir($FileName,$FileCHMOD); }
                //touch($FileName,$LastEdit);
                if($FileType=="0") {
                                $CheckSize = 512;
                                while ($CheckSize<$FileSize) {
                                                if($CheckSize<$FileSize) {
                                                $CheckSize = $CheckSize + 512; } }
                                $SeekSize = $CheckSize - $FileSize;
                                fseek($thandle,$SeekSize,SEEK_CUR); } }
                fclose($thandle);
                return true;
}
?>