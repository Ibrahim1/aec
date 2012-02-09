<?php
HTML_frontend::aec_styling($option);

if ( !$suppressactions ) {
	$actions =	JText::_('CHECKOUT_ERROR_OPENINVOICE')
	. ' <a href="'
	.  AECToolbox::deadsureURL( 'index.php?option=' . $option . '&amp;task=repeatPayment&amp;invoice='
	. $invoice . '&amp;userid=' . $objUser->id ) . '&'. JUtility::getToken() .'=1" title="' . JText::_('GOTO_CHECKOUT') . '">'
	. JText::_('GOTO_CHECKOUT')
	. '</a>'
	. ', ' . JText::_('GOTO_CHECKOUT_CANCEL') . ' '
	. '<a href="'
	. AECToolbox::deadsureURL( 'index.php?option=' . $option . '&amp;task=cancelPayment&amp;invoice='
	. $invoice . '&amp;userid=' . $objUser->id . '&amp;pending=1' )
	. '" title="' . JText::_('HISTORY_ACTION_CANCEL') . '">'
	. JText::_('HISTORY_ACTION_CANCEL')
	. '</a>'
	;
} else {
	$actions = '';
}
?>

<div class="componentheading"><?php echo JText::_('CHECKOUT_ERROR_TITLE'); ?></div>
<div id="box_pending">
	<p><?php echo JText::_('CHECKOUT_ERROR_EXPLANATION') . ( $error ? ( ': ' . $error ) : '' ); ?></p>
	<p><?php echo $actions; ?></p>
</div>
