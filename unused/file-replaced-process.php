<?php
 $root_file = plugin_dir_path(__FILE__);
 $root_dir_file = dirname(plugin_dir_path(__DIR__)).'/ultimate-member';
$file_link =  $root_file . 'um-actions-account-file.php';
 $umactionfile = fopen($file_link, "r") or die("Unable to open file!");
$filetomove =  fread($umactionfile,filesize($file_link));
fclose($umactionfile);
 
$link =  $root_dir_file . '/includes/core/um-actions-account.php';
$newumactionfile = fopen($link, "w") or die("Unable to open file!");
fwrite($newumactionfile, $filetomove);
fclose($newumactionfile);

$misc_file_link = $root_file. 'um-actions-misc-file.php';
$umactionmiscfile = fopen($misc_file_link, "r") or die("Unable to open file!");
$filetomove =  fread($umactionmiscfile,filesize($misc_file_link));
fclose($umactionmiscfile);
// echo $filetomove ;
// $link = plugin_dir_url(__DIR__).'unused/newfile.txt';
$link =  $root_dir_file . '/includes/core/um-actions-misc.php';
$newumactionmiscfile = fopen($link, "w") or die("Unable to open file!");
fwrite($newumactionmiscfile, $filetomove);
fclose($newumactionmiscfile);


$account_file_link = $root_file .'account-template.php';
$umactionmiscfile = fopen($account_file_link, "r") or die("Unable to open file!");
$filetomove =  fread($umactionmiscfile,filesize($account_file_link));
fclose($umactionmiscfile);
// echo $filetomove ;
// $link = plugin_dir_url(__DIR__).'unused/newfile.txt';
$link =  $root_dir_file . '/templates/account.php';
$newaccountfile = fopen($link, "w") or die("Unable to open file!");
fwrite($newaccountfile, $filetomove);
fclose($newaccountfile);
 