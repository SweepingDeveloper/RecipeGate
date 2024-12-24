<?php
$db = mysqli_connect('localhost','root','','test') or die('Error connecting to MySQL server.');
$db_name = '`test`';
$non_admins = ['washington','jefferson','lincoln','jackson','grant','mckinley','cleveland','madison','wilson'];
$admins = ['hamilton','franklin','chase'];

?>