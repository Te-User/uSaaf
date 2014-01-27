<?php
	class Database
	{
		public $dB;
		public function __construct($type)
		{
			try
			{
				$this->dB = new PDO('mysql:host='.$type['DB_HOST'].';dbname='.$type['DB_NAME'].'', $type['DB_USER'], $type['DB_PASS']);
			}
			catch (Exception $e)
			{
				exit($e->getMessage());
			}
		}
		
		public function query ($line = '')
		{	
			$myQuery = $this->dB->query($line);
			$Values = $myQuery->fetchAll();
			$myQuery->closeCursor();
			return $Values;
		}
	}
	
?>