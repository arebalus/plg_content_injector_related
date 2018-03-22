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


$image_height	= intval($config['image-height']) > 0 ? intval($config['image-height']) : 120;
$text_lenght	= intval($config['text-length']) > 0 ? intval($config['text-length']) : 0;
$slides			= (count($rows)) >= $config['max-items-in-slide'] ? $config['max-items-in-slide'] : count($rows) ;
$slides_1024	= ($slides - 1) > 0 ? $slides - 1 : 1 ;
$slides_600		= ($slides_1024 - 1) > 0 ? $slides_1024 - 1 : 1 ; 
$slides_480		= ($slides_600 - 1) > 0 ? $slides_600 - 1 : 1 ;

JHtml::_('jquery.framework');
$doc	= JFactory::getDocument();
$base	= rtrim(JUri::base(false),'/');
$doc	->addStyleSheet($base.'/media/plg_content_injector_related/slick/slick.css');
$doc	->addStyleSheet($base.'/media/plg_content_injector_related/slick/slick-theme.css');
$doc	->addScript($base.'/media/plg_content_injector_related/slick/slick.min.js');
$doc	->addScriptDeclaration(""
			."jQuery(document).ready(function(){"
			.	"jQuery('.injector-related-slick').slick({"
			.		"dots: true,"
			.		"infinite: false,"
			.		"speed: 300,"
			.		"slidesToShow: {$slides},"
			.		"slidesToScroll: {$slides},"
			.		"responsive: ["
			.		"{"
			.			"breakpoint: 1024,"
			.			"settings: {"
			.				"slidesToShow: {$slides_1024},"
			.				"slidesToScroll: {$slides_1024}"
			.			"}"
			.		"},"
			.		"{"
			.			"breakpoint: 600,"
			.			"settings: {"
			.				"slidesToShow: {$slides_600},"
			.				"slidesToScroll: {$slides_600}"
			.			"}"
			.		"},"
			.		"{"
			.			"breakpoint: 480,"
			.			"settings: {"
			.				"slidesToShow: {$slides_480},"
			.				"slidesToScroll: {$slides_480}"
			.			"}"
			.		"}"
			.		"]"
			.	"});"
			."});"
		);

$style = ".injector-related-slider{margin:50px 0;clear:both;"
		."-moz-user-select: none;box-sizing: border-box;display: block;position: relative;touch-action: pan-y;"
		."} "
		.".injector-related-slider *{height:auto; min-width: 0;min-height: 0;}"
		.".injector-related-slider:after,{visibility: hidden;display: block;font-size: 0;content: \" \";clear: both;height: 0;} "
		.".injector-related-image{width:100%;background-position:center center;background-size: cover; } "
		.".injector-related-image a img{width:100%; height:{$image_height}px !important;} "
		.".injector-related-slider-parent{  padding: 20px 30px;}"
		.".injector-related-slider-item{padding:0 5px;}"
		.".injector-related-text{font-size:0.8em;line-height:1.1em;text-align:justify;color:#777;}"
		;
if ($config['single-line-title'] == '1')
{
	$style .= ".injector-related-title{text-transform:uppercase; font-size:0.8em; font-weight:bold; overflow: hidden; position: relative; text-overflow: ellipsis; white-space: nowrap;margin:20px 0 10px 0;} "; 
}
else
{
	$style .= ".injector-related-title{text-transform:uppercase; font-size:0.8em; font-weight:bold; line-height:1.2em;margin:20px 0 10px 0;} ";
}
JFactory::getDocument()->addStyleDeclaration($style);


# **************************************************
#
# VERY IMPORTANT: A wraper DIV element is MANDATORY.
#
# **************************************************
?>
<div class="injector-related-slider">
	<h2><?php echo JText::_('PLG_CONTENT_INJECTOR_RELATED_TITLE'); ?></h2>
	<div style="" class="injector-related-slider-parent">
		<div class="injector-related-slick">
		<?php foreach($rows as $row):?>
			<div class="injector-related-slider-item">
		<div class="injector-related-image" style="background-image: url('<?php echo $row->image;?>');">
			<a 
				href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$row->id.'&catid='.$row->catid);?>"
				title="<?php echo $row->title; ?>"
				><img 
					src="<?php echo JUri::base(false);?>/media/plg_content_injector_related/placeholder.png"
					alt="<?php echo $row->title; ?>" 
					title="<?php echo $row->title; ?>"
				/></a>		
		</div>
		<div class="injector-related-title">
			<span><a 
					href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$row->id.'&catid='.$row->catid);?>"
					title="<?php echo $row->title; ?>"
					><?php echo $row->title; ?></a></span>
		</div>
		<?php if ($text_lenght): ?>
		<div class="injector-related-text">
			<?php echo plgContentInjector_relatedHelper::cleanText($row->introtext.$row->fulltext,$text_lenght); ?>
		</div>
		<?php endif; ?>
		<div>
			
		</div>
		 	</div>
		<?php endforeach; ?>
		</div>
	</div>
</div>
<!--
<script src="<?php echo $base.'/media/plg_content_injector_related/slick/slick.min.js'; ?>" type="text/javascript"></script>
-->
<script type="text/javascript">
</script>