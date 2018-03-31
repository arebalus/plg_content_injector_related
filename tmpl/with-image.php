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

$image_height		= intval($config['image-height']) >= 0 ? intval($config['image-height']) : 120;
$text_lenght		= intval($config['text-length']) >= 0 ? intval($config['text-length']) : 0;
$title_uppercase	= intval($config['uppercase-title']) == 1 ? 'text-transform:uppercase; ' : '' ;

$width = floor(100 / count($rows));

$style = ".injector-related-images{margin:50px 0;} "
		.".injector-related-item {float:left;width:{$width}%;padding:10px;}"
		.".injector-related-images:after,.injector-related-images:before {visibility: hidden;display: block;font-size: 0;content: \" \";clear: both;height: 0;}"
		.".injector-related-image{width:100%;background-position:center center;background-size: cover; }"
		.".injector-related-image img, .injector-related-image a img{width:100%; height:{$image_height}px !important;border:0;} "
		.".injector-related-text{font-size:{$config['text-font-size']}px;line-height:1.1em;text-align:justify;color:{$config['text-color']};}"
		.""
		;
if (strlen($config['color-title']))
{
	$style .= ".injector-related-title{color:{$config['color-title']};}"
			.".injector-related-title a{color:{$config['color-title']};}"
	;
	
}
if ($config['single-line-title'] == '1')
{
	$style .= ".injector-related-title{{$title_uppercase}font-size:{$config['font-size-title']}px;line-height:1.2em; font-weight:bold; overflow: hidden; position: relative; text-overflow: ellipsis; white-space: nowrap;margin:{$config['margin-up-title']}px 0 {$config['margin-down-title']}px 0;} "; 
}
else
{
	$style .= ".injector-related-title{{$title_uppercase}font-size:{$config['font-size-title']}px; font-weight:bold; line-height:1.2em;margin:{$config['margin-up-title']}px 0 {$config['margin-down-title']}px 0;} ";
}

JFactory::getDocument()->addStyleDeclaration($style);

# **************************************************
#
# VERY IMPORTANT: A wraper DIV element is MANDATORY.
#
# **************************************************
?>
<div class="injector-related-images">
	<h2><?php echo JText::_('PLG_CONTENT_INJECTOR_RELATED_TITLE');?></h2>
	<?php foreach($rows as $row): ?>
	<div class="injector-related-item">
		<?php if ($image_height > 0): ?>
		<div class="injector-related-image" style="background-image: url('<?php echo $row->image;?>');">
			<?php if ($config['linked-image']) :?>
			<a 
				href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$row->id.'&catid='.$row->catid);?>"
				title="<?php echo $row->title; ?>"
				><img 
					src="<?php echo JUri::base(false);?>/media/plg_content_injector_related/placeholder.png"
					alt="<?php echo $row->title; ?>" 
					title="<?php echo $row->title; ?>"
				/></a>
			<?php else :?>
				<img 
					src="<?php echo JUri::base(false);?>/media/plg_content_injector_related/placeholder.png"
					alt="<?php echo $row->title; ?>" 
					title="<?php echo $row->title; ?>"
				/>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<div class="injector-related-title">
			<?php if ($config['linked-title']) : ?>
			<span><a 
					href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$row->id.'&catid='.$row->catid);?>"
					title="<?php echo $row->title; ?>"
					><?php echo $row->title; ?></a></span>
			<?php else : ?>
			<span><?php echo $row->title; ?></span>
			<?php endif; ?>
		</div>
		<?php if ($text_lenght): ?>
		<div class="injector-related-text">
			<?php echo plgContentInjector_relatedHelper::cleanText($row->introtext.$row->fulltext,$text_lenght); ?>
		</div>
		<?php endif; ?>
	</div>
	<?php endforeach; ?>
</div>