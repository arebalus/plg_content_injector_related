/*
* Backend JavaScript for Injector Reltated 1.3
*/

jQuery(document).ready(function($){
	$('.injector-range').each(function(){
		var id	= $(this).attr('id');
		var min	= $(this).attr('min');
		var max = $(this).attr('max');
		var val	= parseInt($(this).val());
		var cls = 'label-info';
		if (val > 0)
		{
			cls = 'label-success';
		}
		if (val < 0)
		{
			cls = 'label-important';
		}
		$( '<span><small>'+min+' </small></span>' ).insertBefore( this );
		$( '<span><small> '+max+'</small></span>'+' <span class="label '+cls+'" id="'+id+'-value">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+val+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>' 
		).insertAfter( this );
		$(this).attr('oninput', 'injectorSliderChange(\''+id+'\');');
	});
	
});

function injectorSliderChange(e)
{
	var v = parseInt(jQuery('#'+e).val());
	var l = '#'+e+'-value';
	var cls = 'label-info';
	if (v > 0)
	{
		cls = 'label-success';
	}
	if (v < 0)
	{
		cls = 'label-important';
	}
	jQuery(l).html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+v+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
	jQuery(l).removeClass('label-info');
	jQuery(l).removeClass('label-success');
	jQuery(l).removeClass('label-important');
	jQuery(l).addClass(cls);
};