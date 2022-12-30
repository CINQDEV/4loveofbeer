<div class="sidebar--advanced-settings" style="display: none">

	<div class="sidebar-advanced-icon" data-section="background">
			<img src="<?php echo SWK_URL . 'features/img/advanced-settings/background.svg'; ?>">
			<span style="display:none;">Background</span>					
	</div>
			
	<div class="sidebar-advanced-icon" data-section="position">
			<img src="<?php echo SWK_URL . 'features/img/advanced-settings/size_spacing.svg'; ?>">
			<span style="display:none;">Size &amp; Spacing</span>
	</div>
	
	<div class="sidebar-advanced-icon" data-section="layout">
			<img src="<?php echo SWK_URL . 'features/img/advanced-settings/layout.svg'; ?>">
			<span style="display:none;">Layout</span>
	</div>
	
	<div class="sidebar-advanced-icon" data-section="typography">
			<img src="<?php echo SWK_URL . 'features/img/advanced-settings/typography.svg'; ?>">
			<span style="display:none;">Typography</span>
	</div>
	
	<div class="sidebar-advanced-icon" data-section="borders">
			<img src="<?php echo SWK_URL . 'features/img/advanced-settings/borders.svg'; ?>">
			<span style="display:none;">Borders</span>
	</div>
	
	<div class="sidebar-advanced-icon" data-section="effects">
			<img src="<?php echo SWK_URL . 'features/img/advanced-settings/effects.svg'; ?>">
			<span style="display:none;">Effects</span>
	</div>
	
	<div class="sidebar-advanced-icon code-php" data-section="code-php">
			<img src="<?php echo SWK_URL . 'features/img/advanced-settings/borders.svg'; ?>">
			<span style="display:none;">PHP</span>
	</div>

	<div class="sidebar-advanced-icon" data-section="code-css">
			<img src="<?php echo SWK_URL . 'features/img/advanced-settings/css.svg'; ?>">
			<span style="display:none;">CSS</span>
	</div>
	
	<div class="sidebar-advanced-icon" data-section="code-js">
			<img src="<?php echo SWK_URL . 'features/img/advanced-settings/js.svg'; ?>">
			<span style="display:none;">JavaScript</span>
	</div>
	
	<!-- 
		<div class="sidebar-advanced-icon" data-section="custom-css">
				<img src="<?php echo SWK_URL . 'features/img/advanced-settings/css.svg'; ?>">
				<span style="display:none;">Custom CSS</span>
		</div>

		<div class="sidebar-advanced-icon" data-section="custom-js">
				<img src="<?php echo SWK_URL . 'features/img/advanced-settings/js.svg'; ?>">
				<span style="display:none;">JavaScript</span>
		</div>
	-->
	
	<div class="sidebar-advanced-icon" data-section="custom-attributes">
			<img src="<?php echo SWK_URL . 'features/img/advanced-settings/layout.svg'; ?>">
			<span style="display:none;">Attributes</span>
	</div>


</div>

<script>
    jQuery(document).ready(function($){
        
		$(".sidebar--advanced-settings").clone().appendTo(".oxygen-sidebar-control-panel-basic-styles").addClass( "show" );		        	        
        $(".sidebar--advanced-settings").clone().appendTo(".oxygen-sidebar-advanced-home").addClass( "show" );	

    });     

	tippy('[data-section="background"]', { content: 'Backround', animation: 'shift-toward', placement: 'right',  });
	tippy('[data-section="position"]', { content: 'Position', animation: 'shift-toward', placement: 'right',  });
	tippy('[data-section="layout"]', { content: 'Layout', animation: 'shift-toward', placement: 'right',  });
	tippy('[data-section="typography"]', { content: 'Typography', animation: 'shift-toward', placement: 'right',  });
	tippy('[data-section="borders"]', { content: 'Borders', animation: 'shift-toward', placement: 'right',  });
	tippy('[data-section="effects"]', { content: 'Effects', animation: 'shift-toward', placement: 'right',  });
	tippy('[data-section="code-php"]', { content: 'PHP Code Editor', animation: 'shift-toward', placement: 'right',  });
	tippy('[data-section="code-css"]', { content: 'CSS Code Editor', animation: 'shift-toward', placement: 'right',  });
	tippy('[data-section="code-js"]', { content: 'JS Code Editor', animation: 'shift-toward', placement: 'right',  });

</script>

<style>
	.show{ display: block !important; }

	.oxygen-sidebar-advanced-home,
	.oxygen-sidebar-control-panel,
	#oxygen-sidebar-control-panel-basic-styles
	{
		position: relative;
		padding-left: 60px;
	}

	.sidebar--advanced-settings{
		background: var(--oxy__bg-2) !important;
		height: 100%;
		position: absolute;
		left: 0;
		top: 0;
	}

	.sidebar-advanced-icon{
		color: var(--oxy__text);
		background: var(--oxy__bg-3);
		border: 2px solid var(--oxy__input-border);
		width: 40px;
		height: 50px;
		display: flex;
		align-items: center;
		justify-content: center;
		margin: 5px;
	}

	.sidebar-advanced-icon:hover{
		border-color: var(--oxy__action);
	}

	.sidebar-advanced-icon.active{
		background: var(--oxy__bg-3);
		border-color: var(--oxy__bg-3);
		position: relative;
	}

	.sidebar-advanced-icon.active:after{
		content: '';
		display: block;
		height: calc(100% + 4px);
		width: 10px;
		position: absolute;
		left: 100%;
		top: -2px;
		background: var(--oxy__bg-3);
	}

</style>