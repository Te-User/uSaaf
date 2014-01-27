<?php
	class Template 
	{
		public 	$design 	= 	'default';
		private $config		= 	array();
		private $dB;
		private $src_build 	= 	'';
		
		// Appel de la classe & verification de l'existance du design.
		public function __construct ( $set, $dB )
		{
			$this->config = $set;
			$this->dB = $dB;
			$design = $set['DESIGN'];
			if(file_exists('design/'.$design.'/style.css'))
			{
				$this->design = $design;
			}
			else 
			{
				exit('Le thème <b>'.$design.'</b> n\'existe pas.');
			}
		}
		
		// Création de la page.
		public function build ( $objPage )
		{
			$getTplDesign = $this->getTpl('index');
			if($getTplDesign != false)
			{
				$getTplDesign = $this->parseElem($getTplDesign);
				
				ob_start();
				include('contents/'.$objPage->pageNav.'.php');
				$pageContent = ob_get_contents();
				ob_end_clean();
			
				$getTplDesign = str_replace('{cms::page::contents}', $pageContent, $getTplDesign);
				$this->show($getTplDesign);
			}
			else 
			{
				exit('Impossible de récupérer le template :<b>tpl_index</b>.');
			}
		}
		
		// Parsing des elements dans la source.
		private function parseElem ( $src )
		{
			$parseElem = explode('{cms::', $src);
			foreach ($parseElem as $id=>$element)
			{
				if($id != 0)
				{
					$delimElem 		= 	explode('}', $element);
					$delimVal 		= 	explode('::', $delimElem[0]);
					$getType		= 	$delimVal[0];
					$getValue		= 	$delimVal[1];
					$src 			= 	$this->rplElement($getType, $getValue, $src);
				}
			}
			return $src;
		}
		
		// Affichage de la page.
		private function show ( $src )
		{
			echo( $src );
		}
		
		// Récupération du template.
		private function getTpl	( $name, $isPhp = false )
		{
			if($isPhp) 
			{
				$tpl_dest = 'design/'.$this->design.'/tpl/tpl_'.$name.'.php';
			} 
			else 
			{
				$tpl_dest = 'design/'.$this->design.'/tpl/tpl_'.$name.'.html';
			}
			if(file_exists($tpl_dest))
			{
				if($isPhp) 
				{
					ob_start();
					include($tpl_dest);
					$content = ob_get_contents();
					ob_end_clean();
					return $content;
				}
				else
				{
					return file_get_contents($tpl_dest);
				}
			}
			else 
			{
				return false;
			}
		}
	
		// Remplacement des élements du template.
		private function rplElement ( $type, $value, $src )
		{
			switch($type)
			{
				case "settings":
					$rpl = str_replace('{cms::settings::'.$value.'}', $this->config[$value], $src);
					return $rpl;
				case "tpl":
					$getTpl = $this->getTpl( $value );
					if($getTpl != false)
					{
						$rpl = str_replace('{cms::tpl::'.$value.'}', $this->parseElem($getTpl), $src);
						return $rpl;
					}
					else 
					{
						echo('Impossible de récupérer le template : <b>tpl_'.$value.'</b>.<br/>');
					}
					break;
				case "php":
					$getTpl = $this->getTpl( $value, true );
					if($getTpl != false)
					{
						$rpl = str_replace('{cms::php::'.$value.'}', $this->parseElem($getTpl), $src);
						return $rpl;
					}
					else 
					{
						echo('Impossible de récupérer le template (PHP) : <b>tpl_'.$value.'</b>.<br/>');
					}
					break;
			}
			if(!isset($rpl)) return $src;
		}
	}
?>