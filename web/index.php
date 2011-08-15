<?php
require 'header.php';
?>

<!-- Updates and Renewal -->
<iframe name='cpupdate' src='http://www.heliohost.org/scripts/renew.php?fromcpanel=1&username=<?php echo $_SESSION['username']; ?>' width='1' height='1' frameborder='0'></iframe>
<!-- End of the IFRAMES. -->

<table class=homecontent cellpadding=0><tr>

<td width=450 valign=top>
<table width=430 height=80 cellpadding=12><tr>
<td valign=top width=85%><div class="homeheader"><b>Welcome, <?php echo $username; ?>!</b></div></td>
<td valign=top width=15%><div class="homeheader"><a href=login.php?r=logout><font color=black>Logout</font></a></div></td>
</tr></table>

<center>
<a href=files.php?path=<?php echo $homedir.'/'; ?>><img src="images/filemanagerbutton.png"></a>
<a href=sqlbuddy/><img src="images/sqlbuddybutton.png"></a>
<a href="http://heliopanel.heliohost.org/support.php"><img src="images/helpbutton.png"></a>
</center>
</td>

<td><script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: 4,
  interval: 6000,
  width: 250,
  height: 300,
  theme: {
    shell: {
      background: '#333333',
      color: '#ffffff'
    },
    tweets: {
      background: '#000000',
      color: '#ffffff',
      links: '#4aed05'
    }
  },
  features: {
    scrollbar: false,
    loop: false,
    live: false,
    hashtags: true,
    timestamp: true,
    avatars: false,
    behavior: 'all'
  }
}).render().setUser('HelioHost').start();
</script></td>

</tr></table>

<?php
require 'footer.php';
?>