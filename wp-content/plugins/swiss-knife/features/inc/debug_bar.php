<div id="debug_btn" class="oxygen-toolbar-button swk-topbar-icons" style="display: none">
	<img src="<?php echo SWK_URL . 'features/img/debug/debug-icon.svg'; ?>">
</div>


<script>
	tippy('#debug_btn', {
  		content: 'Debug',
		animation: 'shift-toward',
	});
</script>

<style>
	.swk-topbar-icons{
		background: var(--oxy-dark);
		display: flex;
		align-items: center;
		justify-content: center;
		text-align: center;
		padding: 0 12px;
		cursor: pointer;
	}
	.swk-topbar-icons:hover{
		background-color: var(--oxy-hover);
	}	
</style>	