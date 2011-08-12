<?php
session_start();

// Make sure the user is logged in
if (!isset($_SESSION['username'])) {
	header("location:../login.php");
}

// Include the configuration
require '../config.php';

// Get the user's home directory
if (file_exists('/home/'.$username)) {
    $homedir = '/home/'.$username;
} elseif (file_exists('/home1/'.$username)) {
    $homedir = '/home1/'.$username;
} else {
	die ('Fatal Error: Cannot find home directory!');
}

// If the form hasn't been submitted
if (!isset($_POST['content'])) {

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <link href="../ace/build/textarea/style.css" rel="stylesheet" type="text/css">
  <link href="../style.css" type="text/css" rel="stylesheet">
  <title>File Editor</title>
</head>
<body style="font-family:Arial; background:transparent;">

<form method=post action=editcode.php>
<input type=hidden name=file value="<?php echo $_GET['file']; ?>">

<table class="filestoolbar"><tr>
<td width=40 valign=middle><img border=0 src="../images/files/back-icon.png" style="cursor:pointer; opacity:0.5;filter:alpha(opacity=50)" onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50" onClick="top.window.location='../files.php?path=<?php echo $homedir; ?>/'"></td>
<td valign=middle><input type=text name=file value="<?php echo $_GET['file']; ?>" style="width:98%;" disabled></td>
<td width=200 valign=middle><center><font face=arial size=2><b>Last Saved:</b> <?php echo date('h:i:s A'); ?> (PST)</font></center></td>
<td width=40 valign=middle><input type=image border=0 src="../images/files/save.png" style="cursor:pointer; opacity:0.5;filter:alpha(opacity=50)" onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50"></td>
</tr></table>

<textarea name="content" id="textarea" style="width:100%; height:400px">
<?php
echo htmlspecialchars($fileRepository->get($_GET['file']));
?>
</textarea>

</form>

<script>
function inject() {
    var baseUrl = "../ace/build/textarea/src/";
    function load(path, module, callback) {
        path = baseUrl + path;
        if (!load.scripts[path]) {
            load.scripts[path] = {
                loaded: false,
                callbacks: [ callback ]
            };

            var head = document.getElementsByTagName('head')[0];
            var s = document.createElement('script');

            function c() {
                if (window.__ace_shadowed__ && window.__ace_shadowed__.define.modules[module]) {
                    load.scripts[path].loaded = true;
                    load.scripts[path].callbacks.forEach(function(callback) {
                        callback();
                    });
                } else {
                    setTimeout(c, 50);
                }
            };
            s.src = path;
            head.appendChild(s);

            c();
        } else if (load.scripts[path].loaded) {
            callback();
        } else {
            load.scripts[path].callbacks.push(callback);
        }
    };

    load.scripts = {};
    window.__ace_shadowed_load__ = load;

    load('ace.js', 'text!ace/css/editor.css', function() {
        var ace = window.__ace_shadowed__;
        ace.options.mode = "javascript";
        var Event = ace.require('pilot/event');
        var areas = document.getElementsByTagName("textarea");
        for (var i = 0; i < areas.length; i++) {
            Event.addListener(areas[i], "click", function(e) {
                if (e.detail == 3) {
                    ace.transformTextarea(e.target);
                }
            });
        }
    });
}

// Call the inject function to load the ace files.
inject();

var textAce;
function initAce() {
    var ace = window.__ace_shadowed__;
    // Check if the ace.js file was loaded already, otherwise check back later.
    if (ace && ace.transformTextarea) {
        var t = document.querySelector("textarea");
        textAce = ace.transformTextarea(t);
        textAce.setDisplaySettings(true);
    } else {
        setTimeout(initAce, 100);
    }
}

// Transform the textarea on the page into an ace editor.
initAce();

document.getElementById("buBuild").onclick = function() {
    var injectSrc = inject.toString().split("\n").join("");
    injectSrc = injectSrc.replace('baseUrl = "../ace/build/textarea/src/"', 'baseUrl="' + document.getElementById("srcURL").value + '"');

    var aceOptions = textAce.getOptions();
    var opt = [];
    for (var option in aceOptions) {
        opt.push(option + ":'" + aceOptions[option] + "'");
    }
    injectSrc = injectSrc.replace('ace.options.mode = "javascript"', 'ace.options = { ' + opt.join(",") + ' }');
    injectSrc = injectSrc.replace(/\s+/g, " ");

    var a = document.querySelector("a");
    a.href = "javascript:(" + injectSrc + ")()";
    a.innerHTML = "Ace Bookmarklet Link";
}

</script>

</body>
</html>

<?php
}else{

	$fileRepository->save($_POST['file'], $_POST['content']);
	header("location:editcode.php?file=".$_POST['file']);

}?>