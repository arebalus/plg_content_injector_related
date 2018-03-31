<?php 
/**
 * @Project   Content - Injector Related 1.3
 * @author    Magnus Arebalus
 * @email     arebalus.NO.SPAM@gmail.com
 * @website   github.com/arebalus
 * @licence   GNU / GPL
 * @copyright Copyright (C) 2018 Magnus Arebalus Marcus. All rights reserved.
 */

// No direct access 
defined( '_JEXEC' ) or die();

require_once dirname(__FILE__) . '/helpers/helper.php';

class plgContentInjector_related extends JPlugin
{

	protected $autoloadLanguage = true;
	protected $db;
	protected $app;

	public function __construct(&$subject,$config)
	{
		parent::__construct($subject, $config);
	}

	public function onContentPrepare($context,&$article,&$params,$page) 
	{
		if (plgContentInjector_relatedHelper::shouldRun($context))
		{
			$config = plgContentInjector_relatedHelper::getConfig($this->params,$article);
			$input	= JFactory::getApplication()->input;
			if (	$input->getCmd('option')=='com_content'
				&&	$input->getCmd('view')=='article'
				)
			{
				$inject = true;
				if ($config['enable'])
				{
					if ($config['filter'] == 'inc')
					{
						if (!in_array($article->catid,$config['categories']))
						{
							$inject = false;
						}
					}
					else
					{
						if (in_array($article->catid,$config['categories']))
						{
							$inject = false;
						}
					}
				}
				else
				{
					$inject = false;
				}
				if ($inject)
				{
					plgContentInjector_relatedHelper::injectText($article,$config);
				}
			}
		}
		return true;
	}
	
	public function onBeforeRender()
	{
		if (
				$this->app->isAdmin() 
			&&	$this->app->input->getCmd('option')=='com_plugins'
			&&	$this->app->input->getCmd('view')=='plugin'
			&&	$this->app->input->getCmd('layout')=='edit'
			)
		{
			$id		= $this->app->input->getCmd('extension_id');
			if (!empty($id))
			{
				$query	= $this->db->getQuery(true);
				$query	->select('*')
						->select($this->db->quoteName('folder'))
						->from($this->db->quoteName('#__extensions'))
						->where($this->db->quoteName('extension_id').'='.$id)
						->where($this->db->quoteName('folder').'='.$this->db->quote($this->_type))
						->where($this->db->quoteName('element').'='.$this->db->quote($this->_name))
						;
				$this	->db->setQuery($query);
				$rows	= $this->db->loadObjectList();
				if (count($rows))
				{
					$doc = JFactory::getDocument();
					$doc ->addScript(rtrim(JUri::root(true),'/').'/media/plg_content_injector_related/js/backend.js');
					$doc ->addStyleSheet(rtrim(JUri::root(true),'/').'/media/plg_content_injector_related/css/backend.css');
				}
			}
		}
	}
	
}
