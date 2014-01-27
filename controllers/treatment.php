<?php
	class Treatment 
	{
		public $pageNav			= 	'home';
		public $pageWant		=	null;
		public $Configuration	= 	array();
		public function __construct ( $config )
		{
			$this->Configuration = $config;
			if(isset($_GET['p']))
			{
				$this->pageWant	= $_GET['p'];
				if(!file_exists('contents/'.$_GET['p'].'.php'))
				{
					$this->pageNav = '404';
				} 
				else {
					$this->pageNav = $_GET['p'];
				}
			}
		}
	}
	
	$dB 	= 	new Database( $CMS_SET_DB );
	$Tpl 	= 	new Template( $CMS_ALL_SET, $dB );
	$Page 	= 	new Treatment( $CMS_ALL_SET );
	$Tpl->build($Page);
	
?>