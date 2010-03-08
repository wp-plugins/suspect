jQuery(document).ready(function($){
	$('body').prepend($('#suspect'));
	
	$('#suspect_nav li a').not($('#close a')).click(function(){
		// Toggle close
		if($(this).hasClass('open')){
			$(this).css({background: ''});
			$('#suspect #details').hide();
			$($(this).attr('href')).hide();
			$(this).removeClass('open');
			return false;
		}
		
		// Close any previously opened item
		$(this).parent().parent().find('a').not($(this)).each(function(){
			if($(this).hasClass('open')){
				$(this).css({background: ''});
				$($(this).attr('href')).hide();
				$(this).removeClass('open');
			}
		});

		// Open the item
		$(this).css({background: '#fff'}).addClass('open');
		$('#suspect #details').show();
		$($(this).attr('href')).show();

		return false;
	});
	
	// Hover table rows
	$('#suspect #details table tbody tr').hover(
		function(){
			$(this).css({background: '#e8e9e8'});
		},
		function(){
			$(this).css({background: ''});
		}
	);
	
	// Close
	$('#close a').toggle(
		function(){
			$('#suspect').css({width: 'auto'});
			$('#suspect_nav li').not('#close').hide();
			$('#suspect #details').hide();
			$('#suspect_nav li a').css({background: ''}).removeClass('open');
			$.cookie('suspect-settings', '0', {expires: 7, path: '/'});
			$(this).html('&gt;');

			return false;
		},
		function(){
			$('#suspect').css({width: ''});
			$('#suspect_nav li').show();
			$.cookie('suspect-settings', '1', {expires: 7, path: '/'});
			$(this).html('X');

			return false;
		}
	);
	
	// Is the toolbar open or closed?
	if($.cookie('suspect-settings') == '0'){
		$('#close a').trigger('click');
	}
});