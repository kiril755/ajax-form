<?php

require_once "../Model/Ajax.php";

use Model\Ajax;

$ajax = new Ajax();



// якщо потрібно очистити сессію
//$ajax->clearSession();



echo $ajax->request($_POST);