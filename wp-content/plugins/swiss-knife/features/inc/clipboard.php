<div
    class="oxygen-toolbar-button swk-topbar-icons button-paste"
    style="display:none" >

    <img src="<?php echo SWK_URL . 'features/img/clipboard/paste-icon.svg'; ?>">

</div>


<div
    class="oxygen-toolbar-button swk-topbar-icons button-copy"
    ng-click="console.log(item);"
    style="display:none">

    <img src="<?php echo SWK_URL . 'features/img/clipboard/copy-icon.svg'; ?>">

</div>


<div
    class="oxygen-toolbar-button swk-topbar-icons button-cut"
    ng-click="console.log(item);"
    style="display:none">

    <img src="<?php echo SWK_URL . 'features/img/clipboard/cut-icon.svg'; ?>">

</div>


<script>

jQuery( document ).ready(function($) {

    $( '.button-paste' ).insertAfter( '.oxygen-toolbar-panels' );
    $( '.button-copy' ).insertAfter( '.oxygen-toolbar-panels' );
    $( '.button-cut' ).insertAfter( '.oxygen-toolbar-panels' );

    $( '.button-cut' ).addClass( 'swk-button-loaded' );
    $( '.button-copy' ).addClass( 'swk-button-loaded' );
    $( '.button-paste' ).addClass( 'swk-button-loaded' );

});

</script>

<script>
    if (typeof swkUseSystemClipboard === 'undefined') {
        var swkUseSystemClipboard = "<?php echo get_option('swiss_system_clipboard');?>" === "yes";
    }

    tippy('.button-paste', {
  		content: 'Paste',
		animation: 'shift-toward',
	});

    tippy('.button-copy', {
  		content: 'Copy',
		animation: 'shift-toward',
	});

    tippy('.button-cut', {
  		content: 'Cut',
		animation: 'shift-toward',
	});

</script>

<style>
    .swk-button-loaded{
        display: flex !important;
    }

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

    .swk-topbar-icons img {
        height: 20px;
    }
</style>
