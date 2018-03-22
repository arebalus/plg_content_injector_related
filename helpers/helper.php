<?php 
/**
 * @Project   Content - Injector Related 1.2
 * @author    Magnus Arebalus
 * @email     arebalus.NO.SPAM@gmail.com
 * @website   github.com/arebalus
 * @licence   GNU / GPL
 * @copyright Copyright (C) 2018 Magnus Arebalus Marcus. All rights reserved.
 */

// No direct access 
defined( '_JEXEC' ) or die();

class plgContentInjector_relatedHelper
{
	public static function shouldRun($scope)
    {
		$input	= JFactory::getApplication()->input;
		if (	   $scope != 'com_content.article' 
				&& $scope != 'com_content.featured' 
				&& $scope != 'com_content.category' 
			)
		{
			return false;
		}
		return true;
    }
    
	public static function getConfig(&$params,&$article)
	{
		static $globalConfig = null;
		static $config = array();
		
		if (is_null($globalConfig))
		{
			$globalConfig	= self::_getGlobalConfig($params);
		}
		if (!isset($config[$article->id]))
		{
			$tempConfig		= $globalConfig;
			$textConfig		= self::_getInlineConfig($article);
			$inlineConfig	= self::_validateConfig($textConfig['params']);
			if (is_array($inlineConfig) && count($inlineConfig))
			{
				foreach ($inlineConfig as $k => $v)
				{
					$tempConfig[$k] = $v;
				}
			}
			$config[$article->id] = $tempConfig;
		}
		return $config[$article->id];
	}
	
	public static function injectText(&$article,$config)
	{
		$rows = self::_getRelatedList($article,$config,1,$config['number']);
		if (count($rows))
		{
			$layout = isset($config['layout']) ? $config['layout'] : 'default';
			$path = JPluginHelper::getLayoutPath('content', 'injector_related',$layout);
			ob_start();
			include $path;
			$text = ob_get_clean();
			self::_injector($article,$config,$text);
		}
	}
	
	private static function _getRelatedList(&$article,$config,$relation=1,$number=3,$already=array())
	{
		$return	= array();
		$rows	= array();
		
		if (!$article->id) return $return;
		
		if ($relation == 1 || (isset($config['relation'.$relation]) && $config['relation'.$relation] != 'non'))
		{
			$doQuery= true;
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			switch($config['relation'.$relation])
			{
				
				case 'tag':
					$query	->select($db->quoteName('tag_id'))
							->from($db->quoteName('#__contentitem_tag_map'))
							->where($db->quoteName('content_item_id').'='.$article->id)
							->where($db->quoteName('type_alias').'='.$db->quote('com_content.article'))
							;
					$db		->setQuery($query);
					$tags	= $db->loadColumn();
					
					$query	->clear();
					if (is_array($tags) && count($tags))
					{
						$query	->join('', 
									$db->quoteName('#__contentitem_tag_map','tm')
									.'ON '
									.$db->quoteName('tm.content_item_id')
									.' = '
									.$db->quoteName('c.id')
									)
								->where($db->quoteName('tm.tag_id').' IN ('.implode(',',$tags).')')
								->group($db->quoteName('c.id'));
								;
					}
					else
					{
						$doQuery = false;
					}
					break;
				case 'cat':
					$query	->where($db->quoteName('c.catid').'='.$article->catid);
					break;
				case 'aut':
					$query	->where($db->quoteName('c.created_by').'='.$article->created_by);
					break;
				case 'key':
					$kwds	= explode(',',$article->metakey);
					for($i=0,$n=count($kwds);$i<$n;$i++)
					{
						$kwds[$i] = trim($kwds[$i]);
						if (strlen($kwds[$i]))
						{
							$kwds[$i] = $db->quoteName('c.metakey').' LIKE '.$db->quote('%'.$kwds[$i].'%'); 
						}
						else
						{
							unset($kwds[$i]);
						}
					}
					if (count($kwds))
					{
						$query->where('('.implode(' OR ',$kwds).')');
					}
					else
					{
						$doQuery = false;
					}
				default:
					break;
			}
			if ($doQuery)
			{
				$now	= $query->currentTimestamp();
				$null	= $query->nullDate();
				$query	->select(
									 $db->quoteName('c.id','id')
								.','.$db->quoteName('c.title','title')
								.','.$db->quoteName('c.introtext','introtext')
								.','.$db->quoteName('c.catid','catid')
								.','.$db->quoteName('c.images','images')
								.','.$db->quoteName('c.introtext','introtext')
								.','.$db->quoteName('c.fulltext','fulltext')
								)
						->from ($db->quoteName('#__content','c'))
						->where($db->quoteName('c.id').'<>'.$article->id)
						->where($db->quoteName('c.state').'=1')
						->where('('.$db->quoteName('c.publish_up').' < '.$now.' OR '.$db->quoteName('c.publish_up').'='.$null.')' )
						->where('('.$db->quoteName('c.publish_down').' > '.$now.' OR '.$db->quoteName('c.publish_down').'='.$null.')' )
						->setLimit(intval($number))
						;
				if (is_array($already) && count ($already))
				{
					$query ->where($db->quoteName('c.id').' NOT IN ('.implode(',',$already).')');
				}
				# To-Do: Add more sorting options
				$query	->order($db->quoteName('c.publish_up').' DESC');
				$db->setQuery($query);
				$rows = $db->loadObjectList();
			}		
			
			$selIds = array();
			
			//die (JPATH_ROOT);
			if (is_array($rows))
			{
				for ($i=0,$n=count($rows);$i<$n;$i++)
				{
					$selIds[]	= $rows[$i]->id;
					$images 	= (array)json_decode($rows[$i]->images);
					if (
							isset($images['image_intro']) 
						&& 	strlen($images['image_intro']) 
						//&&	file_exists(JPATH_ROOT.'/'.$images['image_intro'])
						)
					{
						$rows[$i]->image = $images['image_intro'];
					}
					elseif (
							isset($images['image_fulltext']) 
						&& 	strlen($images['image_fulltext']) 
						//&&	file_exists(JPATH_ROOT.'/'.$images['image_fulltext'])
						)
					{
						$rows[$i]->image = $images['image_fulltext'];
					}
					else
					{
						$txt	= $rows[$i]->introtext.$rows[$i]->fulltext;
						$ptrn	= '/<img\s.*?src=(?:\'|")([^\'">]+)(?:\'|")/si';
						if (preg_match($ptrn,$txt,$imgMatch))
						{
							$rows[$i]->image = html_entity_decode($imgMatch[1]);
						}
						else
						{
							$rows[$i]->image = $config['default-image'];
						} 
						
					}				
				}
				$already = array_merge($already,$selIds);
				if (count($rows) < $number && $relation < 4)
				{
					$rows = array_merge($rows,self::_getRelatedList($article,$config,$relation+1,$number-count($rows),$already));
				}
				$return = $rows;
			}
			
		}
		return $return;
	}
	
	private static function _injector(&$article,$config,$text)
	{
		switch($config['location'])
		{
			case 'top':
				$article->text = $text.$article->text;
				break;
			case 'bot':
				$article->text = $article->text.$text;
				break;
			case 'mid':
			default:
				$dom	= new DOMDocument;
				$dom->formatOutput = true;
				if ($dom->loadHTML('<?xml encoding="utf-8" ?><div>'.$article->text.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD))
				{
					$new	= new DOMDocument;
					if ($new->loadHTML('<?xml encoding="utf-8" ?>'.$text))
					{
						$to		= $new->getElementsByTagName('div')->item(0);
						$to		= $dom->importNode($to, true);
						$tags	= $dom->getElementsByTagName($config['mode']);
						$real	= array();
						$clsLen	= strlen(trim($config['class']));
						$chkCls	= $clsLen > 0;
						for ($i=0,$j=$tags->length;$i<$j;$i++)
						{
							if ($config['only-root'] == 1)
							{
								$parent = $tags->item($i)->parentNode;
								$child	= $dom->getElementsByTagName('div')->item(0);
								if ($parent !== $child)
								{
									continue;
								}
							}
							if ($chkCls)
							{
								$tag	= $tags->item($i);
								$tCls	= trim($tag->getAttribute('class'));
								if (
										   substr($tCls,0,$clsLen) == trim($config['class'])
										|| substr($tCls,-$clsLen) == trim($config['class'])
										|| strpos($tCls,' '.trim($config['class']).' ') !== false
									)
								{
									$real[] = $i;
								}
							}
							else
							{
								$real[]	= $i;
							}
							
						}
						if (count($real) > 0)
						{
							if ($config['position'] > 0)
							{
								$key	= ($config['position'] > count($real)) ? count($real) : $config['position'];
								if ($key < count($real))
								{
									$p		= $tags->item($real[$key-1])->nextSibling;
									$p		->parentNode->insertBefore($to, $p);  
								}
								else
								{
									$p		= $tags->item($real[count($real) - 1]);
									$p		->parentNode->appendChild($to);
								}
								
							}
							elseif ($config['position'] < 0)
							{
								$key	= (count($real)+$config['position'] < 0) ? 0 : count($real)+$config['position'];
								$p		= $tags->item($real[$key]);
								$p		->parentNode->insertBefore($to, $p);  
							}
							else
							{
								$key = intval(count($real) / 2);
								$p		= $tags->item($real[$key]);
								$p		->parentNode->insertBefore($to, $p);  
							}
							$temp	= $dom->saveHTML();
							if (substr($temp,0,25)== '<?xml encoding="utf-8" ?>')
							{
								$temp = substr($temp,25);
							}
							$temp = trim($temp,"\r\n ");
							if (substr($temp,0,5)=='<div>' && substr($temp,-6)=='</div>')
							{
								$temp = substr($temp,5,strlen($temp)-11);
							}
							$temp	= "\r\n\r\n".$temp."\r\n\r\n";
							$article->text = $temp;
						}
						else
						{
							if ($config['position']<0)
							{
								$article->text = $text.$article->text;
							}
							else
							{
								$article->text = $article->text.$text;
							}
						}
					}
				}
				break;
		}
	}
	
	
    private static function _validateConfig($config)
    {
    	$valid = array();
		foreach($config as $k=>$v)
    	{
    		switch ($k)
    		{
    			case 'enable':
    			case 'only-root':
    			case 'single-line-title':
    				$valid[$k] = ($v == '0') ? '0' : '1';
					break;
				case 'number':
					$v = intval($v);
					$valid['number'] = ($v >= 1 && $v <= 15) ? $v : 3;
					break;
				case 'relation1':
					$valid['relation1'] = in_array($v,array('key','tag','cat','aut')) ? $v : 'key';
					break;
				case 'relation2':
				case 'relation3':
				case 'relation4':
					$valid[$k] = in_array($v,array('non','key','tag','cat','aut')) ? $v : 'non';
					break;
				case 'filter':
					$valid['filter'] = in_array($v,array('inc','exc')) ? $v : 'exc';
					break;
				case 'mode':
					$valid['mode'] = in_array($v,array('p','div')) ? $v : 'p';
					break;
				case 'location':
					$valid['location'] = in_array($v,array('top','bot','mid')) ? $v : 'mid';
					break;
				case 'position':
					$valid['position'] = in_array($v,array(	'-10','-9','-8','-7','-6','-5','-4','-3','-2','-1',
															'0','1','2','3','4','5','6','7','8','9','10','center'
															)
													) ? $v : 'center';
					break;
				case 'categories':
					$v = is_array($v) ? $v : explode(',',$v);
					for ($i=0,$n=count($v);$i<$n;$i++)
					{
						if ($v[$i] != intval($v[$i]))
						{
							unset($v[$i]);
						}
					}
					$valid['categories'] = $v;
					break;
				case 'class':
					$valid['class'] = $v;
					break;
				case 'layout':
					
					if (
							!file_exists(JPATH_SITE.'/plugins/content/injector_related/tmpl/'.$v)
						&&	!file_exists(JPATH_SITE.'/templates/'.JFactory::getApplication()->getTemplate().'/html/plg_content_injector_related/'.$v)
						)
					{
						$v = 'default';
					}
					if (substr($v,-4)=='.php')
					{
						$v=substr($v,0,strlen($v)-4);
					}
					$valid['layout'] = $v;
					break;
				case 'max-items-in-slide':
					$v = intval($v);
					$valid['max-items-in-slide'] = ($v >= 1 && $v <= 10) ? $v : 4;
					break;
				case 'default-image':
					if (
						 	strlen($v) 
						&&	file_exists(JPATH_ROOT.'/'.$v)
						)
					{
						$valid['default-image'] = JUri::base(false).'/'.$v;
					}
					else
					{
						$valid['default-image'] = JUri::base(false).'/media/plg_content_injector_related/default.jpg';
					}
					break;
				case 'image-height':
					$v = intval($v);
					$valid[$k] = $v > 0 ? $v : 120;
					break;
				case 'text-length':
					$v = intval($v);
					$valid[$k] = $v >= 0 ? $v : 200;
					break;
				default:
					# None other parameter is accepted.
					break;
    		}
    	}
    	return $valid;
    }
    
    
    private static function _getInlineConfig(&$article)
    {
    	// TO-DO: Make parser stronger by now must avoid spaces between pairs valuss.
    	// key=val Will work
    	// key = val Will NOT work
		$config = array('string'=>'','params'=>array());
		
		# strpos is faster than preg_match, so we'll avoid unless it's necessary
		if (strpos($article->text,'{injector_related ') !== false)
    	{
    		
			if (preg_match('/\{injector_related([^\}]{1,})\}/si',$article->text,$MATCHES))
    		{
				$config['string'] = $MATCHES[0];
	    		$pairs	= explode(' ',$MATCHES[1]);
    			$params	= array(); 
    			foreach($pairs as $pair)
    			{
    				if (strpos($pair,'='))
    				{
    					list($key,$val) = explode('=',$pair);
    					$key	= trim($key);
    					$val	= trim($val);
    					if (strlen($key) && strlen($val))
    					{
    						$params[$key] = $val;
    					}
    				}
    			}
    			if (count($params))
    			{
	    			$params['filter'] = 'inc';
    				$params['categories'] = $article->catid;
    				if (!isset($params['enable']))
    				{
    					$params['enable']='1';
    				}
    				$config['params']	= $params;
    			}
		    	$article->text = str_replace($MATCHES[0],'',$article->text);
		    	$article->introtext = str_replace($MATCHES[0],'',$article->introtext);
		    	$article->fulltext = str_replace($MATCHES[0],'',$article->fulltext);
		    	
    		}
    	}
    	return $config;
    }
    
    public static function cleanText($text,$lenght)
    {
    	# Remove plugins syntax
		if (preg_match_all('/\{[^\}]{1,}\}/si',$text,$MATCHES))
    	{
    		if (isset($MATCHES[0]) && is_array($MATCHES[0]) && count($MATCHES[0]))
    		{
    			foreach($MATCHES[0] as $match)
    			{
    				$text = str_replace($match,'',$text);
    			}
    		}
    	}
    	
		# Remove HTML tags
    	$text = strip_tags($text);
    	
    	# Convert HTML Entities
    	$text = html_entity_decode($text,ENT_NOQUOTES,'UTF-8');
    	
    	# Remove format characters
		$text = str_replace(array("\r","\n","\t",'&nbsp;'),' ',$text);
    	
    	# Remove doble spaces
		while (strpos($text,'  ') !== false)
    	{
    		$text = str_replace('  ',' ',$text);
    	}
    	
    	# Build the return without truncate words
		$words = explode(' ',$text);
    	$return = '';
    	if (is_array($words) && count($words))
    	{
    		$return = $words[0];
			$i=0;
    		while(strlen($return.' '.$words[$i+1])<$lenght)
    		{
    			$return .= ' '.$words[$i+1];
    			$i++;
    		}
    		if ($i < count($words))
    		{
    			$return .= '&nbsp;&hellip;';
    		}
    	}
    	return $return;
    }
    
    private static function _getGlobalConfig(&$params)
    {
    	static $config = null;
    	
    	if (is_null($config))
    	{
    		$config = array();
    		
    		# Basic params
    		$config['enable'] = $params->get('enable','1');
    		$config['number'] = $params->get('number','3');
    		$config['relation1'] = $params->get('relation1','key');
    		$config['relation2'] = $params->get('relation2','non');
    		$config['relation3'] = $params->get('relation3','non');
    		$config['relation4'] = $params->get('relation4','non');
    		
    		# Where params
    		$config['filter'] = $params->get('filter','exc');
    		$config['categories'] = $params->get('categories','');
    		$config['mode'] = $params->get('mode','p');
    		$config['class'] = $params->get('class','');
    		$config['only-root'] = $params->get('only-root',1);
    		$config['location'] = $params->get('location','mid');
    		$config['position'] = $params->get('position','center');
    		
    		# Layout params
    		$config['layout'] = $params->get('layout','default.php');
    		$config['max-items-in-slide'] = $params->get('max-items-in-slide','4');
    		$config['default-image'] = $params->get('default-image','media/plg_content_injector_related/default.jpg');
    		$config['image-height'] = $params->get('image-height','120');
    		$config['single-line-title'] = $params->get('single-line-title','1');
    		$config['text-length'] = $params->get('text-length','200');
    		
    		$config = self::_validateConfig($config);
    	}
    	return $config;
    }
    
}

