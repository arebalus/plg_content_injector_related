<?php 
/**
 * @Project   Content - Injector Related 1.0
 * @author    Magnus Arebalus
 * @email     arebalus.NO.SPAM@gmail.com
 * @website   github.com/arebalus
 * @licence   GNU / GPL
 * @copyright Copyright (C) 2018 Magnus Arebalus Marcus. All rights reserved.
 */

// No direct access 
defined( '_JEXEC' ) or die();


# **************************************************
#
# VERY IMPORTANT: A wraper DIV element is MANDATORY.
#
# **************************************************
?>
<div style="margin: 50px 0;">
	<h3><?php echo JText::_('PLG_CONTENT_INJECTOR_RELATED_TITLE'); ?></h3>
	<ul>
	<?php foreach($rows as $row):?>
		 <li>
	 		<a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$row->id.'&catid='.$row->catid);?>"><?php echo $row->title; ?></a>
		 </li>
	<?php endforeach; ?>
	</ul>
</div>