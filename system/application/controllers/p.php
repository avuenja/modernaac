<?php 

	class p extends Controller {
		public function index() {
			$ide = new IDE;
			$ide->redirect(WEBSITE);
		}
		
		public function v($page) {
			$ide = new IDE;
			if(empty($page)) $ide->redirect(WEBSITE);
			if(file_exists("system/pages/".$page.".php"))
				include("system/pages/".$page.".php");
			else
				$ide->goPrevious();
		}
	}

?>