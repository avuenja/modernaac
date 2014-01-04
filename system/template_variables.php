<?php 
$smarty->assign('head', $head);
$smarty->assign('path', $website);
$smarty->assign('template_path', $website.'/templates/'.$config['layout']);
$smarty->assign('logged', $logged);
$smarty->assign('main', $contents);
$smarty->assign('server_name', $config['server_name']);
$smarty->assign('layout', $config['layout']);
$smarty->assign('worlds', $config['worlds']);
$smarty->assign('UItheme', $config['UItheme']);
$smarty->assign('charSET', $config['engine']['charSET']);
$smarty->assign('website', WEBSITE);
$smarty->assign('current', CURRENT);
require ('status.php');
$smarty->assign('serverOnline', $serverOnline);
$smarty->assign('serverMax', $serverMax);
$smarty->assign('serverPeak', $serverPeak);
$smarty->assign('serverUptime', $serverUptime);
$smarty->assign('serverClient', $serverClient);
$smarty->assign('serverMotd', $serverMotd);
$smarty->assign('serverNPCs', $serverNPCs);
$smarty->assign('serverMonsters', $serverMonsters);
$smarty->assign('serverPlayers', $serverPlayers);
?>
