window.WP_Grid_Builder && WP_Grid_Builder.on( 'init', onInit );

function onInit( wpgb ) {
    wpgb.facets && wpgb.facets.on( 'appended', onAppended );
}

function onAppended( content ) {
    
    /* Lightbox */
    if (typeof doExtrasLightbox == 'function' && jQuery(content).has('.oxy-lightbox')) {
    	doExtrasLightbox(jQuery(content));
    }
  
    /* Read More / Less */
    if (typeof doExtrasReadmore == 'function' && jQuery(content).has('.oxy-read-more-less')) {
    	doExtrasReadmore(jQuery(content));
    }
	
	 /* Tabs */
    if (typeof doExtrasTabs == 'function' && jQuery(content).has('.oxy-dynamic-tabs')) {
    	doExtrasTabs(jQuery(content));
    }
	
	/* Accordion */
    if (typeof doExtrasAccordion == 'function' && jQuery(content).has('.oxy-pro-accordion')) {
    	doExtrasAccordion(jQuery(content));
    }
	
	/* Carousel */
    if (typeof doExtrasCarousel == 'function' && jQuery(content).has('.oxy-carousel-builder')) {
    	    doExtrasCarousel(jQuery(content));
    }

    /* Popover */
    if (typeof doExtrasPopover == 'function' && jQuery(content).has('.oxy-popover')) {
        doExtrasPopover(jQuery(content));
    }

} 