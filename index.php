<?php
/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/2/6
 * Time: 10:02
 */
set_time_limit(0);//设置超时时间
@ini_set('implicit_flush',1);
ob_implicit_flush(1);
@ob_end_clean();
    require_once("./framework/core/Framework.class.php");
    Framework::run();


