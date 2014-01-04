<?PHP

	// Reject any unwanted access.
	if ( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	$player = $GLOBALS['player'];
	$config = $GLOBALS['config'];
	$SQL = POT::getInstance()->getDBHandle();
	
	$characters = $SQL->query( 'SELECT `c`.`name`, `c`.`vocation`, `c`.`world_id` FROM `players` AS `c` LEFT JOIN `players` AS `p` ON `p`.`account_id` = `c`.`account_id` AND c.deleted = 0 AND c.hide_char = 0 AND p.hide_char = 0 WHERE `p`.`id` = '.$player->getId( ).' AND `c`.`id` != '.$player->getId( ).';' )->fetchAll();
	if(!empty($characters))
	echo '<div class="bar">Characters</div>';
	foreach( $characters as $character )
	{
		?>
		<table style="width: 100%;">
			<tr>
				<td style="width: 40%;"><?PHP echo $character['name']; ?></td>
				<td style="width: 25%;"><?PHP echo $config['server_vocations'][$character['vocation']]; ?></td>
				<td style="width: 25%;"><?PHP echo $config['worlds'][$character['world_id']]; ?></td>
				<td style="width: 10%;"><a href="<?PHP echo WEBSITE; ?>/index.php/character/view/<?PHP echo $character['name']; ?>"><strong>View</strong></a></td>
			</tr>
		</table>
		<?PHP
	}
?>