<?php 
/*Controlling methods used by Ajax within Modern AAC*/
/*All methods should stop the output system*/

	class Ajax_cs extends Controller {
		
		public function index() {
			global $ide;
			$ide->system_stop();
		}
		
		public function adminRequest() {
			global $ide;
			require("commands/commands.php");
			if(!$ide->isAdmin()) exit;
			try {$output = callCommand($_POST['input'])."<br/>"; echo $output; addAction($output);} catch(Exception $e) { printc($e->getMessage());}
			printc($_POST['input']);
			addAction($_POST['input']);
			$ide->system_stop();
		}
		
	
	}
?>