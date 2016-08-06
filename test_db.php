<?php
$server = 'localhost';
$user = 'root';
$password = '';
$dblink = mysql_connect($server, $user, $password);
if($dblink)
echo 'Соединение установлено.';
else
die('Ошибка подключения к серверу баз данных.');
$database = 'howdytest';
$selected = mysql_select_db($database, $dblink);
if($selected)
echo ' Подключение к базе данных прошло успешно.';
else
die(' База данных не найдена или отсутствует доступ.');
?>
