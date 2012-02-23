<?php
/**
 * @version $Id: paramsform.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>

<table width="100%" id="paramsbox">
	<tr>
		<td class="append_button">
			<form action="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=InvoiceAddParams', $tmpl->cfg['ssl_signup'] ) ?>" method="post">
				<?php echo $params ?>
				<input type="hidden" name="option" value="<?php echo $option ?>" />
				<input type="hidden" name="task" value="InvoiceAddParams" />
				<input type="hidden" name="invoice" value="<?php echo $InvoiceFactory->invoice->invoice_number ?>" />
				<input type="submit" class="button" value="<?php echo JText::_('BUTTON_APPEND') ?>" />
				<?php echo JHTML::_( 'form.token' ) ?>
			</form>
		</td>
	</tr>
</table>
