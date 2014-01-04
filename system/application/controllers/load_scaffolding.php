<?php 

class Load_Scaffolding extends Controller {
	
	function Load_Scaffolding() {
		$ide = new IDE;
		$ide->requireAdmin();
		parent::Controller();
		if(empty($_SESSION['scaffolding']))
			exit("Could not load scaffolding.");
		else
			$this->load->scaffolding($_SESSION['scaffolding']);
		$ide->system_stop();
	}
}

?>