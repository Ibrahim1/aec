<?php
/**
 * @version $Id: invoices.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>
<table>
	<tr>
		<th><?php echo JText::_('HISTORY_COL1_TITLE');?></th>
		<th><?php echo JText::_('HISTORY_COL2_TITLE');?></th>
		<th><?php echo JText::_('HISTORY_COL3_TITLE');?></th>
		<th><?php echo JText::_('HISTORY_COL4_TITLE');?></th>
		<th><?php echo JText::_('HISTORY_COL5_TITLE');?></th>
	</tr>
	<?php
	foreach ( $invoices as $invoice ) { ?>
		<tr<?php echo $invoice['rowstyle'] ?>>
			<td><?php echo $invoice['invoice_number']; ?></td>
			<td><?php echo $invoice['amount'] . '&nbsp;' . $invoice['currency_code']; ?></td>
			<td><?php echo $invoice['transactiondate']; ?></td>
			<td><?php echo $invoice['processor']; ?></td>
			<td><?php echo $invoice['actions']; ?></td>
		</tr>
		<?php
	} ?>
</table>
<?php
if ( $properties['invoice_pages'] > 1 ) {
	echo '<div class="aec-invoices-pagination"><p>';
	$plist = array();
	for ( $i=0; $i<$properties['invoice_pages']; $i++ ) {
		if ( $i == $properties['invoice_page'] ) {
			$plist[] = ( $i + 1 );
		} else {
			$plist[] = '<a href="' . AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=subscriptiondetails&sub=invoices&page=' . $i, !empty( $tmpl->cfg['ssl_profile'] ) ) . '">' . ( $i + 1 ) . '</a>';
		}
	}
	echo implode( '&nbsp;&middot;&nbsp;', $plist ) . '</p></div>';
}
