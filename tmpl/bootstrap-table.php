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

JHtml::_('bootstrap.framework');
JHtmlBootstrap::loadCss();


# **************************************************
#
# VERY IMPORTANT: A wraper DIV element is MANDATORY.
#
# **************************************************
?>
<div class="row" style="margin: 50px 0;">
	<table class="table table-striped">
		<thead>
			<tr>
				<th><?php echo JText::_('PLG_CONTENT_INJECTOR_RELATED_TITLE'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($rows as $row):?>
			 <tr>
			 	<td>
		 			<a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$row->id.'&catid='.$row->catid);?>"><?php echo $row->title; ?></a>
			 	</td>
			 </tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>