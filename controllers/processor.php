<?php
	class Processor
	{
		private $dB;
		private $config	 = array();
		
		public function __construct ( $database, $config )
		{
			$this->dB = $database;
			$this->config = $config;
		}
		
		
		public function showNews ( $debut = 0, $fin = 10)
		{
			$getNewsTemplate = file_get_contents('design/'.$this->config['DESIGN'].'/tpl/tpl_news.html');
			$getNews = $this->dB->query('SELECT * FROM `news`');
			foreach($getNews as $new)
			{
				printf($getNewsTemplate, $new['title'], $new['author'], $new['content']);
			}
		}
		
		public function showError ( $text )
		{
			$getErrorTemplate = file_get_contents('design/'.$this->config['DESIGN'].'/tpl/tpl_error.html');
			printf($getErrorTemplate, $text);
		}
	}

?>