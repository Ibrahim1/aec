<?= $tmpl->lnk( array(	'task' => 'repeatPayment',
							'invoice' => $invoice,
							'userid' => $objUser->id
							), JText::_('GOTO_CHECKOUT') )
	. ', ' . JText::_('GOTO_CHECKOUT_CANCEL') . ' '
	.  $tmpl->lnk( array(	'task' => 'cancelPayment',
							'invoice' => $invoice,
							'userid' => $objUser->id,
							'pending' => 1
							), JText::_('HISTORY_ACTION_CANCEL') ) ?>
