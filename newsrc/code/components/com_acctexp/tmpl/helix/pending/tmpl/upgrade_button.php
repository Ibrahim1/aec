<div id="upgrade_button">
<?= $tmpl->btn( array(	'task' => 'renewSubscription',
								'userid' => $objUser->id
								), JText::_('PENDING_NOINVOICE_BUTTON') )?>
</div>
