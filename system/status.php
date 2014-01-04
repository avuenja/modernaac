<?php
// A class contributed from the VAPus PHP project
$serverOnline = array();
$serverPlayers = array();
$serverUptime = array();
$serverMax = array();
$serverPeak = array();
$serverMotd = array();
$serverClient = array();
$serverNPCs = array();
$serverMonsters = array();

class OTConnect {
	var $errno = 0;
	var $errstr = '';
	var $data = '';
	var $xml = NULL;
	function connect($address, $port) {
		$this->sock = @fsockopen($address, $port, $this->errno, $this->errstr, 1);
	
		if($this->sock) {
			fwrite($this->sock, chr(6).chr(0).chr(255).chr(255).'info');
         while (!feof($this->sock))
            $this->data .= fgets($this->sock, 1024);
         fclose($this->sock);
			$this->xml = simplexml_load_string($this->data);
		}
	}
	function getUptime() {
		return (int)$this->xml->serverinfo->attributes()->uptime;	
	}
	function getClientVersion() {
		return (float)str_replace('x', '', $this->xml->serverinfo->attributes()->client);	
	}
	function getPlayers() {
		return @(int)$this->xml->players->attributes()->online;	
	}
	function getMotd() {
		return $this->xml->motd;
	}
	function getPeak() {
		return (int)$this->xml->players->attributes()->peak;	
	}
	function getMax() {
		return (int)$this->xml->players->attributes()->max;	
	}
	function getServer() {
		return $this->xml->serverinfo->attributes()->server;	
	}
	function getServerVersion() {
		return $this->xml->serverinfo->attributes()->version;	
	}
	function getMonsters() {
		return (int)$this->xml->monsters->attributes()->total;	
	}
	function getNPCs() {
		return (int)@$this->xml->npcs->attributes()->uptime;	
	}
	function getMapHeight() {
		return (int)$this->xml->map->attributes()->height;	
	}
	function getMapWidth() {
		return (int)$this->xml->map->attributes()->width;	
	}
	function getMapName() {
		return $this->xml->map->attributes()->name;	
	}
	function getMapAuthor() {
		return $this->xml->map->attributes()->author;	
	}
	function getName() {
		return $this->xml->serverinfo->attributes()->name;
	}
	function getLocation() {
		return $this->xml->serverinfo->attributes()->location;
	}
	function getURL() {
		return $this->xml->serverinfo->attributes()->url;
	}
	function getOwner() {
		return $this->xml->owner->attributes()->owner;
	}
}
$xml = array();
require_once('config.php');
global $config;
if(@filemtime(FCPATH.'/cache/status') < time() - $config['statusTimeout']) {
	foreach($config['servers'] as $worldID=>$server) {
		// Make a connection in order to see if it's on
		$serv = new OTConnect();
		$serv->connect($server['address'], $server['port']);
		$xml[$worldID] = $serv->data;
	}
	file_put_contents(FCPATH.'/cache/status', json_encode($xml));
} else {

	$xml = json_decode(file_get_contents(FCPATH.'/cache/status'), true);	
}
$serv = new OTConnect();
foreach($xml as $worldID=>$code) {	
	if($code) {
		$serv->xml = simplexml_load_string($code);
		$serverPlayers[$worldID] = $serv->getPlayers();
		$serverUptime[$worldID] = uptimeParse($serv->getUptime());
		$serverMax[$worldID] = $serv->getMax();
		$serverPeak[$worldID] = $serv->getPeak();
		$serverMotd[$worldID] = $serv->getMotd();
		$serverClient[$worldID] = $serv->getClientVersion();
		$serverNPCs[$worldID] = $serv->getNPCs();
		$serverMonsters[$worldID] = $serv->getMonsters();
		$serverOnline[$worldID] = true;
	} else {
		$serverOnline[$worldID] = false;
		$serverPlayers[$worldID] = 0;
		$serverUptime[$worldID] = 0;
		$serverMax[$worldID] = 0;
		$serverPeak[$worldID] = 0;
		$serverMotd[$worldID] = 0;
		$serverClient[$worldID] = 0;
		$serverNPCs[$worldID] = 0;
		$serverMonsters[$worldID] = 0;
	}
}
?>
