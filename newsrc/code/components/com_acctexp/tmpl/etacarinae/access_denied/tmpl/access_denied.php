<?php
/**
 * @version $Id: access_denied.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' ) ?>
<div id="aec">
	<div id="aec-access-denied">
		<div class="componentheading"><?php echo JText::_('NOT_ALLOWED_HEADLINE') ?></div>
		<div class="alert alert-danger">
			<p><?php echo $loginlink ?></p>
		</div>
		<?php @include( $tmpl->tmpl( 'plans.processor_list' ) ); ?>
	</div>
</div>
