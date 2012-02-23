<?php echo $tmpl->lnk( array(	'task' => 'repeatPayment',
							'invoice' => $invoice,
							'userid' => $metaUser->userid
							), JText::_('GOTO_CHECKOUT') )
	. ', ' . JText::_('GOTO_CHECKOUT_CANCEL') . ' '
	.  $tmpl->lnk( array(	'task' => 'cancelPayment',
							'invoice' => $invoice,
							'userid' => $metaUser->userid,
							'pending' => 1
							), JText::_('HISTORY_ACTION_CANCEL') ) ?>
