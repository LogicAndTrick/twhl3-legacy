<?php 
//include 'captchaform.php';
?>
<img src="captchaimage.php?time=<?=str_replace(" ","",str_replace(".","",microtime()))?>" alt="captcha" />
