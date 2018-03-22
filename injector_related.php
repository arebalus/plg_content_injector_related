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

require_once dirname(__FILE__) . '/helpers/helper.php';

class plgContentInjector_related extends JPlugin
{

	protected $autoloadLanguage = true;
	protected $db;
	protected $app;

	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	public function onContentPrepare($context,&$article,&$params,$page) 
	{
		$tStart = microtime(true);
		if (!plgContentInjector_relatedHelper::shouldRun($context)) return true;
		$config = plgContentInjector_relatedHelper::getConfig($this->params,$article);
		$input	= JFactory::getApplication()->input;
		if (	$input->getCmd('option')=='com_content'
			&&	$input->getCmd('view')=='article'
			)
		{
			if ($config['enable'])
			{
				$inject = true;
				if ($config['filter'] == 'inc')
				{
					if (!in_array($article->catid,$config['categories'])) $inject = false;
				}
				else
				{
					if (in_array($article->catid,$config['categories'])) $inject = false;
				}
			}
			if ($inject)
			{
				plgContentInjector_relatedHelper::injectText($article,$config);
			}
		}
		return true;
	}
	
}
