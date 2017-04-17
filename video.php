
<html>
<!--<frameset rows="0%,100%" cols="*">-->
<!--<frameset  frameborder="no" border="0" framespacing="1">-->
<!--<frame src="a.html"/>-->
<!--</frameset>-->

<!--<frameset  frameborder="yes" framespacing="1">-->
<!--<frame src="http://www.baidu.com"/>-->
<!--</frameset>-->
<!--</frameset>-->
<frameset cols="0%,*">
    <frame name="hello" src="up2u.html">
    <frame name="hi" src="<?php echo base64_decode($_GET['key']) ?>">
</frameset>
</html>
