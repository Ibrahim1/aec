<?php
/**
 * @version	$Id: aecrewrite.php $
 * @package AEC - Account Control Expiration - Joomla 1.5 Plugins
 * @subpackage Rewrite
 * @copyright 2006-2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

$app =& JFactory::getApplication();
$app->registerEvent( 'onPrepareContent', 'plgContentAECRestriction' );

class plgContentAECRestriction extends JPlugin
{
	function onPrepareContent( &$article, &$params, $limitstart )
	{
		return $this->onContentPrepare( "", $article, $params, $limitstart );
	}

	/**
	 * @param	string	The context of the content being passed to the plugin.
	 * @param	object	The article object.  Note $article->text is also available
	 * @param	object	The article params
	 * @param	int		The 'page' number
	 */
	public function onContentPrepare( $context, &$article, &$params, $page=0 )
	{
		// See whether there is anything to replace
		if (JString::strpos($article->text, '{aecrestriction') === false) {
			return true;
		}

		if ( false ) {
			// component check
		}

		if ( file_exists( JPATH_ROOT."/components/com_acctexp/acctexp.class.php" ) ) {
			$regex = "#{aecrestriction(.*?)}(.*?){/aecrestriction}#s";

			$article->text = preg_replace_callback( $regex, array(&$this, '_replace'), $article->text );
		}

		return true;
	}

	/**
	 * Replaces the matched tags.
	 *
	 * @param	array	An array of matches (see preg_match_all)
	 * @return	string
	 */
	protected function _replace( &$matches )
	{
		$permission = false;

		jimport('joomla.utilities.utility');

		include_once( JPATH_ROOT."/components/com_acctexp/acctexp.class.php" );

		$user = &JFactory::getUser();

		$r = new stdClass();
		$r->action			= 'actionRestrictionCheck';
		$r->user			= new stdClass();
		$r->user->userid	= $user->id;

		$aecAPI = new aecAPI();

		$aecAPI->load( $r );

		if ( empty( $aecAPI->error ) ) {
			$api->resolve();

			$permission = $api->response->result;
		}

		if ( $permission ) {
			return $matches[1];
		} else {
			return "";
		}
	}
}
