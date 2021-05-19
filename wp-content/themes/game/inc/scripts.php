
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.lavalamp.1.3.3-min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.cycle.all.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/superfish.js" type="text/javascript" charset="utf-8"></script>   
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.easing.1.3.js" type="text/javascript" charset="utf-8"></script>  
    
<script type="text/javascript">
//<![CDATA[
jQuery(function(){

		jQuery.noConflict();
	
		jQuery('ul.catmenu').superfish({
			delay:       200,                            // one second delay on mouseout 
			animation:   {'marginLeft':'0px',opacity:'show',height:'show'},  // fade-in and slide-down animation 
			speed:       'fast',                          // faster animation speed 
			autoArrows:  true,                           // disable generation of arrow mark-up 
			onBeforeShow:      function(){ this.css('marginLeft','20px'); },
			dropShadows: false                            // disable drop shadows 
		});
		
		jQuery('ul.catmenu ul > li').addClass('noLava');
		jQuery('ul.catmenu > li').addClass('top-level');
		
		jQuery('ul.catmenu > li > a.sf-with-ul').parent('li').addClass('sf-ul');
		
		jQuery("ul.catmenu > li > ul").prev("a").attr("href","#");
			
		jQuery('ul.catmenu li ul').append('<li class="bottom_bg noLava"></li>');
		
		var active_subpage = jQuery('ul.catmenu ul li.current-cat, ul.catmenu ul li.current_page_item').parents('li.top-level').prevAll().length;
		var isHome = <?php if (is_front_page() || is_home()) echo('1'); else echo('0'); ?>; 
		
		if (active_subpage) jQuery('ul.catmenu').lavaLamp({ startItem: active_subpage });
		else if (isHome === 1) jQuery('ul.catmenu').lavaLamp({ startItem: 0 });
		else jQuery('ul.catmenu').lavaLamp();

});
//]]>
</script>