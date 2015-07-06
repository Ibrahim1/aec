<?php
/**
 * @version $Id: admin.acctexp.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Backend
 * @copyright 2006-2015 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

global $aecConfig;

$app = JFactory::getApplication();

require_once( JPATH_SITE . '/components/com_acctexp/acctexp.class.php' );
require_once( JPATH_SITE . '/administrator/components/com_acctexp/admin.acctexp.class.php' );
require_once( JPATH_SITE . '/administrator/components/com_acctexp/admin.acctexp.html.php' );

$langlist = array(
	'com_acctexp' => JPATH_ADMINISTRATOR,
	'com_acctexp.iso4217' => JPATH_ADMINISTRATOR
);

xJLanguageHandler::loadList( $langlist );

JLoader::register('JPaneTabs',  JPATH_LIBRARIES.'/joomla/html/pane.php');

xJACLhandler::adminBlock( $aecConfig->cfg['adminaccess'], $aecConfig->cfg['manageraccess'] );

$entity = trim( aecGetParam( 'entity', 'central' ) );
$task = trim( aecGetParam( 'task', 'index' ) );

$id = aecGetParam( 'id', null );

if ( !is_null($id) && !is_array($id) ) $id = array($id);

$db = JFactory::getDBO();

// Auto Heartbeat renew every one hour to make sure that the admin gets a view as recent as possible
$heartbeat = new aecHeartbeat();
$heartbeat->backendping();

if ( empty( $option ) ) {
	$option = aecGetParam( 'option', '0' );
}

switch( strtolower( $task ) ) {
	case 'heartbeat':
	case 'beat':
		// Manual Heartbeat
		$heartbeat = new aecHeartbeat();
		$heartbeat->beat();
		echo "wolves teeth";
		break;

	case 'copycoupon':
		$db = JFactory::getDBO();

		foreach ( $id as $pid ) {
			$c = explode( '.', $pid );

			$row = new Coupon( $c[0] );
			$row->load( $c[1] );
			$row->copy();
		}

		aecRedirect( 'index.php?option='. $option . '&task=showCoupons' );
		break;

	case 'hacks':
		$undohack	= aecGetParam( 'undohack', 0 );
		$filename	= aecGetParam( 'filename', 0 );
		$check_hack	= $filename ? 0 : 1;

		hackcorefile( $filename, $check_hack, $undohack );

		HTML_AcctExp::hacks( hackcorefile( 0, 1, 0 ) );
		break;

	case 'quicklookup':
		$return = quicklookup( $option );

		if ( is_array( $return ) ) {
			aecCentral( $return['return'], $return['search'] );
		} elseif ( strpos( $return, '</a>' ) || strpos( $return, '</div>' ) ) {
			aecCentral( $return );
		} elseif ( !empty( $return ) ) {
			aecRedirect( 'index.php?option=com_acctexp&task=edit&amp;entity=Membership&userid=' . $return, JText::_('AEC_QUICKSEARCH_THANKS') );
		} else {
			aecRedirect( 'index.php?option=com_acctexp&task=showcentral', JText::_('AEC_QUICKSEARCH_NOTFOUND') );
		}
		break;

	case 'quicksearch':
		$search = quicklookup( $option );

		if ( empty($search) ) {
			echo JText::_('AEC_QUICKSEARCH_NOTFOUND');
		} else {
			echo $search;
		}

		exit;
		break;

	case 'noticesmodal': getNotices();exit; break;

	case 'readnoticeajax': readNotice($id[0]); exit; break;

	case 'readnoticesajax':
		$db = JFactory::getDBO();

		$query = 'UPDATE #__acctexp_eventlog'
			. ' SET `notify` = \'0\''
			. ' WHERE `notify` = \'1\''
		;
		$db->setQuery( $query	);
		$db->query();
		exit;
		break;

	case 'getnotice': echo getNotice();exit; break;

	case 'readallnotices':
		$db = JFactory::getDBO();

		$query = 'UPDATE #__acctexp_eventlog'
			. ' SET `notify` = \'0\''
			. ' WHERE `notify` = \'1\''
		;
		$db->setQuery( $query	);
		$db->query();

		aecCentral( $option );
		break;

	case 'toggleajax': toggleProperty( aecGetParam('type'), aecGetParam('id'), aecGetParam('property') ); exit; break;

	case 'addgroupajax': addGroup( aecGetParam('type'), aecGetParam('id'), aecGetParam('group') ); exit; break;

	case 'removegroupajax': removeGroup( aecGetParam('type'), aecGetParam('id'), aecGetParam('group') ); exit; break;

	case 'recallinstall':
		include_once( JPATH_SITE . '/administrator/components/com_acctexp/install.acctexp.php' );
		com_install();
		break;

	case 'initsettings':
		$aecConfig = new aecConfig();
		$aecConfig->initParams();

		echo 'SPLINES RETICULATED.';
		break;

	case 'parsertest':
		$top = new templateOverrideParser();
		break;

	case 'lessen':
		include_once( JPATH_SITE . '/components/com_acctexp/lib/lessphp/lessc.inc.php' );
		$less = new lessc();
		$less->setImportDir( array(JPATH_SITE . '/media/com_acctexp/less/') );
		//$less->setFormatter("compressed");
		$less->setPreserveComments(true);

		$v = new JVersion();

		if ( $v->isCompatible('3.0') ) {
			$less->compileFile( JPATH_SITE . "/media/com_acctexp/less/admin-j3.less", JPATH_SITE . '/media/com_acctexp/css/admin.css' );
		} else {
			$less->compileFile( JPATH_SITE . "/media/com_acctexp/less/admin.less", JPATH_SITE . '/media/com_acctexp/css/admin.css' );
		}
		break;

	default:
		$class = 'aecAdmin' . ucfirst($entity);

		/** @var aecAdminEntity $class */
		$class = new $class($id, $entity);

		$class->call($task);
		break;
}

function addGroup( $type, $id, $groupid )
{
	if ( ItemGroupHandler::setChildren( $groupid, array( $id ), $type ) ) {
		$group = new ItemGroup();
		$group->load( $groupid );

		$g = array();
		$g['id']	= $group->id;
		$g['name']	= $group->getProperty('name');
		$g['color']	= $group->params['color'];

		$g['group']	= '<strong>' . $group->id . '</strong>';

		HTML_AcctExp::groupRow( $type, $g );
	}
}

function removeGroup( $type, $id, $groupid )
{
	ItemGroupHandler::removeChildren( $id, array( $groupid ), $type );

	echo 1;
}

class aecAdminEntity
{
	/**
	 * @var JApplication
	 */
	public $app;

	/**
	 * @var JLanguage
	 */
	public $lang;

	/**
	 * @var JDatabase|JDatabaseDriver
	 */
	public $db;

	/**
	 * @var array
	 */
	public $id;

	/**
	 * @var string
	 */
	public $entity;

	/**
	 * @var string
	 */
	public $table;

	/**
	 * @var string
	 */
	public $constraints;

	/**
	 * @var aecAdminState
	 */
	public $state = array();

	/**
	 * @var array
	 */
	public $init = array();

	/**
	 * @var string[]
	 */
	public $searchable = array();

	public $redirect;

	public $params = array();

	public $message = null;

	public function __construct( $id, $entity=null )
	{
		$this->setID($id);

		$this->app = JFactory::getApplication();

		$this->lang = JFactory::getLanguage();

		$this->db = JFactory::getDBO();

		$this->getState();

		$this->addSearchConstraints();

		if ( !empty($entity) ) {
			$this->entity = $entity;
		}
	}

	public function setID( $id )
	{
		if ( empty($id) ) {
			$this->id = array();
		} elseif ( is_array($id) ) {
			$this->id = array($id);
		} elseif ( strpos(',', $id) || strpos('-', $id) ) {
			$array = explode(',', $id);

			$ids = array();
			foreach ( $array as $v ) {
				if ( strpos('-', $v) ) {
					$p = explode('-', $v);

					$ids = array_merge( $ids, range($p[0], $p[1]) );
				} else {
					$ids[] = $v;
				}
			}

			$this->id = $ids;
		} else {
			$this->id = array($id);
		}
	}

	public function call( $task='index' )
	{
		if ($task == 'new') $task = 'edit';

		if ($task == 'apply') {
			$task = 'save';

			$this->redirect = 'edit';
		}

		$r = new ReflectionMethod( get_class($this), $task );

		$params = $r->getParameters();

		$this->params = array();
		foreach ( $params as $k ) {
			$this->params[$k->getName()] = aecGetParam($k->getName());
		}

		call_user_func_array(array($this, $task), $this->params);

		if ($task != 'save') return;

		if ( $this->redirect ) {
			$r = new ReflectionMethod( get_class($this), $this->redirect );

			$params = $r->getParameters();

			$this->params = array();
			foreach ( $params as $k ) {
				$this->params[$k->getName()] = aecGetParam($k->getName());
			}

			$this->redirect($this->redirect, null, $this->params);
		} elseif ( $task != 'index' ) {
			$this->redirect();
		}
	}

	public function redirect( $task='index', $entity=null, $inject=null )
	{
		if ( empty($entity) ) $entity = $this->entity;

		$params = array(
			'option' => 'com_acctexp',
			'task' => $task,
			'entity' => $entity
		);

		if( !empty($inject) )  {
			foreach ( $inject as $k => $v ) {
				$params[$k] = $v;
			}
		}

		aecRedirect( 'index.php?' . http_build_query($params), $this->message );
	}

	public function setMessage( $message=null )
	{
		$this->message = $message;
	}

	public function cancel()
	{
		$nexttask = aecGetParam( 'nexttask', 'config' ) ;

		$this->redirect( 'index.php?option=com_acctexp&task=' . $nexttask, JText::_('CANCELED') );
	}

	public function order( $up )
	{
		foreach( $this->id as $id ) {
			$row = new $this->entity;
			$row->load( $id );
			$row->move( $up ? -1 : 1 );
		}

		$this->redirect();
	}

	public function toggle( $id, $property )
	{
		$this->db->setQuery(
			'SELECT `'.$property.'` FROM #__acctexp_' . $this->table
			. ' WHERE `id` = ' . $id
		);

		$newstate = $this->db->loadResult() ? 0 : 1;

		if ( $property == 'default' ) {
			if ( !$newstate ) {
				echo !$newstate;

				return;
			}

			// Reset all other items
			$this->db->setQuery(
				'UPDATE #__acctexp_' . $this->table
				. ' SET `'.$property.'` = '.($newstate ? 0 : 1)
				. ' WHERE `id` != ' . $id
			);

			$this->db->query();
		}

		$this->db->setQuery(
			'UPDATE #__acctexp_' . $this->table
			. ' SET `'.$property.'` = '.$newstate
			. ' WHERE `id` = ' . $id
		);

		$this->db->query();

		echo $newstate;
	}

	public function copy( $type, $id, $customreturn=null )
	{
		foreach ( $id as $pid ) {
			$row = new $this->entity();
			$row->load( $pid );
			$row->copy();
		}

		$this->redirect();
	}

	public function log( $short, $tags, $event, $level=32, $params=array() )
	{
		$eventlog = new eventLog();
		$eventlog->issue( $short, $tags, $event, $level, $params );
	}

	public function getState()
	{
		if ( empty($this->state) ) {
			$this->state = new aecAdminState($this->entity, $this->init);
		}

		return $this->state;
	}

	public function addSearchConstraints()
	{
		if ( empty($this->searchable) || empty($this->state->search) ) return;

		if ( empty($this->state->search) ) return;

		foreach( $this->searchable as $field ) {
			$this->addConstraint(
				'LOWER(`' . $field . '`) LIKE \'%'
				. xJ::escape( $this->db, trim( strtolower($this->state->search) ) )
				. '%\''
			);
		}
	}

	public function addConstraint( $constraint )
	{
		$this->constraints[] = $constraint;
	}

	public function getConstraints()
	{
		if ( empty($this->constraints) ) return '';

		return ' WHERE (' . implode(') AND (', $this->constraints) . ')';
	}

	public function getOrdering()
	{
		return ' ORDER BY `' . str_replace(' ', '` ', $this->state->sort);
	}

	public function getLimit()
	{
		$nav = $this->getPagination();

		return ' LIMIT ' . $nav->limitstart . ',' . $nav->limit;
	}

	public function getPagination( $total=null )
	{
		static $cache;

		if ( empty($total) && empty($cache) ) {
			$this->db->setQuery(
				'SELECT count(*)'
				. ' FROM #__acctexp_' . $this->table
				. $this->getConstraints()
			);

			$total = $this->db->loadResult();

			$cache = $total;
		} elseif ( !empty($cache) ) {
			$total = $cache;
		}

		// TODO: Optimize by cache
		return new bsPagination( $total, $this->state->limitstart, $this->state->limit );
	}

	public function getRows( $class=null )
	{
		// get the subset (based on limits) of records
		$query = 'SELECT *'
			. ' FROM #__acctexp_' . $this->table
			. $this->getConstraints()
			. $this->getOrdering()
			. $this->getLimit()
		;
		$this->db->setQuery( $query );

		$rows = $this->db->loadObjectList();

		if ( $this->db->getErrorNum() ) {
			echo $this->db->stderr();

			return false;
		}

		if ( $class ) {
			foreach ( $rows as $k => $obj ) {
				$rows[$k] = new $class();
				$rows[$k]->load($obj->id);
			}
		}

		return $rows;
	}

	public function getRanges( $nums )
	{
		sort($nums);

		$ranges = array();

		for ( $i = 0, $len = count($nums); $i < $len; $i++ ) {
			$rStart = $nums[$i];
			$rEnd = $rStart;

			while ( isset($nums[$i+1]) && $nums[$i+1]-$nums[$i] == 1 ){
				$rEnd = $nums[++$i];
			}

			$ranges[] = $rStart == $rEnd ? $rStart : $rStart.'-'.$rEnd;
		}

		return $ranges;
	}
}

class aecAdminState
{
	/**
	 * @var JApplication
	 */
	public $app;

	/**
	 * @var JDatabase|JDatabaseDriver
	 */
	public $db;

	/**
	 * @var bool
	 */
	public $filtered;

	/**
	 * @var int
	 */
	public $limit;

	/**
	 * @var int
	 */
	public $limitstart;

	/**
	 * @var string
	 */
	public $search;

	/**
	 * @var object
	 */
	public $filter;

	/**
	 * @var array
	 */
	public $sort;

	/**
	 * @param string $entity
	 */
	public function __construct( $entity, $init )
	{
		$this->app = JFactory::getApplication();

		$this->db = JFactory::getDBO();

		$option = 'com_acctexp';

		$this->filtered = false;

		$this->limit = $this->app->getUserStateFromRequest(
			"viewlistlimit",
			'limit',
			$this->app->getCfg('list_limit')
		);

		$this->limitstart = $this->app->getUserStateFromRequest(
			"viewconf{$option}limitstart",
			'limitstart',
			0
		);

		$this->search = xJ::escape(
			$this->db,
			trim(
				strtolower( $this->app->getUserStateFromRequest(
					"search{$option}_subscr",
					'search',
					''
				) )
			)
		);

		if ( !empty($this->search) ) {
			$this->filtered = true;
		}

		$this->filter = new stdClass();

		if ( !empty($init['filter']) ) {
			foreach ( $init['filter'] as $key => $default ) {
				$value = $this->app->getUserStateFromRequest(
					'aec_' . $entity . '_' . $key,
					$entity . '_' . $key,
					$default
				);

				if ( !empty($_REQUEST[$key]) ) {
					$value = $_REQUEST[$key];
				}

				if ( is_array($default) && !is_array($value) ) {
					$value = array( $value );
				}

				$this->filter->{$key} = $value;

				if ( $this->filter->{$key} != $default ) {
					$this->filtered = true;
				}
			}
		}

		if ( !empty($init['sort']) ) {
			$this->{$key} = $this->app->getUserStateFromRequest(
				'aec_' . $entity . '_sort',
				$entity . '_sort',
				$init['sort']
			);
		}

		if ( empty($this->sort) ) $this->sort = 'id ASC';
	}
}

class aecAdminCentral extends aecAdminEntity
{
	public function index()
	{
		HTML_AcctExp::central();
	}

	public function getNotices()
	{
		$this->db->setQuery(
			'SELECT COUNT(*)'
			. ' FROM #__acctexp_eventlog'
			. ' WHERE `notify` = \'1\''
		);

		$furthernotices = $this->db->loadResult() - 5;

		$this->db->setQuery(
			'SELECT *'
			. ' FROM #__acctexp_eventlog'
			. ' WHERE `notify` = \'1\''
			. ' ORDER BY `datetime` DESC'
			. ' LIMIT 0, 5'
		);

		$notices = $this->db->loadObjectList();

		HTML_AcctExp::eventlogModal( $notices, $furthernotices );
	}

	public function readNotice( $id )
	{
		$query = 'UPDATE #__acctexp_eventlog'
			. ' SET `notify` = \'0\''
			. ' WHERE `id` = \'' . $id . '\''
		;
		$this->db->setQuery($query);

		$this->db->query();

		echo 1;exit;
	}

	public function getNotice()
	{
		$this->db->setQuery(
			'SELECT *'
			. ' FROM #__acctexp_eventlog'
			. ' WHERE `notify` = \'1\''
			. ' ORDER BY `datetime` DESC'
			. ' LIMIT 5, 1'
		);

		$notice = $this->db->loadObject();

		if ( empty( $notice->id ) ) {
			return '';
		}

		$noticex = array( 2 => 'success', 8 => 'info', 32 => 'warning', 128 => 'error' );

		return '<div class="alert alert-' . $noticex[$notice->level] . '" id="alert-' . $notice->id . '">
			<a class="close" href="#' . $notice->id . '" onclick="readNotice(' . $notice->id . ')">&times;</a>
			<h5><strong>' . JText::_( "AEC_NOTICE_NUMBER_" . $notice->level ) . ': ' . $notice->short . '</strong></h5>
			<p>' . substr( htmlentities( stripslashes( $notice->event ) ), 0, 256 ) . '</p>
			<span class="help-block">' . $notice->datetime . '</span>
		</div>';
	}

	function quicklookup()
	{
		$searcc	= trim( aecGetParam( 'search', 0 ) );

		if ( empty( $searcc ) ) {
			return false;
		}

		$search = xJ::escape( JFactory::getDBO(), strtolower( $searcc ) );

		$s = AECToolbox::searchUser( $search );

		if ( empty( $s ) || !is_array( $s ) ) {
			return false;
		}

		$return = array();
		foreach ( $s as $user ) {
			$JTableUser = new cmsUser();
			$JTableUser->load( $user );

			$userlink = '<div class="lookupresult">';
			$userlink .= '<a href="';
			$userlink .= JURI::base() . 'index.php?option=com_acctexp&amp;task=edit&amp;entity=Membership&amp;userid=' . $JTableUser->id;
			$userlink .= '">';
			$userlink .= str_replace( $search, '<span class="search-match">' . $search . '</span>', $JTableUser->name ) . ' (' . str_replace( $search, '<span class="search-match">' . $search . '</span>', $JTableUser->username ) . ')';
			$userlink .= '</a>';
			$userlink .= '</div>';

			$return[] = $userlink;
		}

		return '<div class="lookupresults">' . implode( $return ) . '</div>';
	}
}

class aecAdminSettings extends aecAdminEntity
{
	public function edit()
	{
		global $aecConfig;

		// See whether we have a duplication
		if ( $aecConfig->RowDuplicationCheck() ) {
			// Clean out duplication and reload settings
			$aecConfig->CleanDuplicatedRows();
			$aecConfig = new aecConfig();
		}

		$lists = array();

		$currency_code_list	= AECToolbox::aecCurrencyField( true, true, true );
		$lists['currency_code_general'] = JHTML::_('select.genericlist', $currency_code_list, ( 'currency_code_general' ), 'size="10"', 'value', 'text', ( !empty( $aecConfig->cfg['currency_code_general'] ) ? $aecConfig->cfg['currency_code_general'] : '' ) );

		$available_plans = SubscriptionPlanHandler::getActivePlanList( true, false );

		if ( !isset( $aecConfig->cfg['entry_plan'] ) ) {
			$aecConfig->cfg['entry_plan'] = 0;
		}

		$lists['entry_plan'] = JHTML::_('select.genericlist', $available_plans, 'entry_plan', 'size="' . min( 10, count( $available_plans ) + 2 ) . '"', 'value', 'text', $aecConfig->cfg['entry_plan'] );

		$gtree = xJACLhandler::getGroupTree( array( 28, 29, 30 ) );

		if ( !isset( $aecConfig->cfg['checkout_as_gift_access'] ) ) {
			$aecConfig->cfg['checkout_as_gift_access'] = 0;
		}

		// Create GID related Lists
		$lists['checkout_as_gift_access'] 		= JHTML::_('select.genericlist', $gtree, 'checkout_as_gift_access', 'size="6"', 'value', 'text', $aecConfig->cfg['checkout_as_gift_access'] );

		$tab_data = array();

		$params = array();
		$params[] = array( 'page-head', JText::_('General Configuration') );
		$params[] = array( 'section', 'access' );
		$params[] = array( 'section-head', JText::_('CFG_GENERAL_SUB_ACCESS') );
		$params['require_subscription']			= array( 'toggle', 0 );
		$params['adminaccess']					= array( 'toggle', 0 );
		$params['manageraccess']				= array( 'toggle', 0 );
		$params[] = array( 'section-head', JText::_('CFG_GENERAL_SUB_PROCESSORS') );
		$params['gwlist']						= array( 'list', 0 );
		$params['standard_currency']			= array( 'list_currency', 0 );
		$params[] = array( 'section-end' );

		$params[] = array( 'page-head', JText::_('Registration Flow') );
		$params[] = array( 'section', 'plans' );
		$params['plans_first']					= array( 'toggle', 0 );
		$params['integrate_registration']		= array( 'toggle', 0 );
		$params['skip_confirmation']			= array( 'toggle', 0 );
		$params[] = array( 'section-end' );
		$params[] = array( 'section', 'plans' );
		$params[] = array( 'section-head', JText::_('Plan List') );
		$params['root_group']					= array( 'list', 0 );
		$params[] = array( 'section-end' );
		$params[] = array( 'section', 'cart' );
		$params[] = array( 'section-head', 'Shopping Cart' );
		$params['enable_shoppingcart']			= array( 'toggle', '' );
		$params['additem_stayonpage']			= array( 'toggle', '' );
		$params[] = array( 'section-end' );
		$params[] = array( 'section', 'checkout' );
		$params[] = array( 'section-head', JText::_('CFG_GENERAL_SUB_CHECKOUT') );
		$params['checkout_coupons']				= array( 'toggle', 0 );
		$params['user_checkout_prefill']		= array( 'inputD', 0 );

		$rewriteswitches						= array( 'cms', 'user', 'expiration', 'subscription' );
		$params									= AECToolbox::rewriteEngineInfo( $rewriteswitches, $params );

		$params[] = array( 'section-end' );

		$params[] = array( 'page-head', JText::_('Inner workings') );
		$params[] = array( 'section', 'heartbeat' );
		$params[] = array( 'section-head', JText::_('CFG_GENERAL_SUB_SYSTEM') );
		$params['heartbeat_cycle']					= array( 'inputA', 0 );
		$params[] = array( 'section-head', JText::_('CFG_GENERAL_SUB_EMAIL') );
		$params['noemails']							= array( 'toggle', 0 );
		$params['noemails_adminoverride']			= array( 'toggle', 0 );
		$params['nojoomlaregemails']				= array( 'toggle', 0 );
		$params[] = array( 'section-head', JText::_('CFG_GENERAL_SUB_DEBUG') );
		$params['curl_default']						= array( 'toggle', 0 );
		$params['simpleurls']						= array( 'toggle', 0 );
		$params['debug_processor_notifications']	= array( 'toggle', 0 );
		$params['error_notification_level']			= array( 'list', 0 );
		$params['email_notification_level']			= array( 'list', 0 );
		$params[] = array( 'section-end' );

		@end( $params );
		$tab_data[] = array( JText::_('CFG_TAB1_TITLE'), key( $params ), '<h2>' . JText::_('CFG_TAB1_SUBTITLE') . '</h2>' );

		$params[] = array( 'page-head', JText::_('CFG_TAB_CUSTOMIZATION_TITLE') );
		$params[] = array( 'section', 'customredirect' );
		$params[] = array( 'section-head', JText::_('CFG_CUSTOMIZATION_SUB_CREDIRECT') );
		$params['customintro']						= array( 'inputC', '' );
		$params['customintro_userid']				= array( 'toggle', '' );
		$params['customintro_always']				= array( 'toggle', '' );
		$params[] = array( 'section-end' );
		$params[] = array( 'section', 'invoice-number' );
		$params[] = array( 'section-head', JText::_('CFG_CUSTOMIZATION_SUB_FORMAT_INUM') );
		$params['invoicenum_doformat']				= array( 'toggle', '' );
		$params['invoicenum_formatting']			= array( 'inputD', '' );

		$rewriteswitches							= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$params										= AECToolbox::rewriteEngineInfo( $rewriteswitches, $params );

		$params[] = array( 'section-end' );
		$params[] = array( 'section', 'captcha' );
		$params[] = array( 'section-head', JText::_('CFG_CUSTOMIZATION_SUB_CAPTCHA') );
		$params['use_recaptcha']					= array( 'toggle', '' );
		$params['recaptcha_privatekey']				= array( 'inputC', '' );
		$params['recaptcha_publickey']				= array( 'inputC', '' );
		$params[] = array( 'section-end' );
		$params[] = array( 'section', 'proxy' );
		$params[] = array( 'section-head', JText::_('CFG_CUSTOMIZATION_SUB_PROXY') );
		$params['use_proxy']						= array( 'toggle', '' );
		$params['proxy']							= array( 'inputC', '' );
		$params['proxy_port']						= array( 'inputC', '' );
		$params['proxy_username']					= array( 'inputC', '' );
		$params['proxy_password']					= array( 'inputC', '' );
		$params['gethostbyaddr']					= array( 'toggle', '' );
		$params[] = array( 'section-end' );

		$params[] = array( 'section', 'date' );
		$params[] = array( 'section-head', JText::_('CFG_CUSTOMIZATION_SUB_FORMAT_DATE') );
		$params['display_date_backend']				= array( 'inputC', '%a, %d %b %Y %T %Z' );
		$params['display_date_frontend']			= array( 'inputC', '%a, %d %b %Y %T %Z' );
		$params['setlocale_date']					= array( 'inputD', '' );
		$params[] = array( 'section-head', JText::_('CFG_CUSTOMIZATION_SUB_FORMAT_PRICE') );
		$params['amount_currency_symbol']			= array( 'toggle', 0 );
		$params['amount_currency_symbolfirst']		= array( 'toggle', 0 );
		$params['amount_use_comma']					= array( 'toggle', 0 );
		$params[] = array( 'section-end' );
		$params[] = array( 'section', 'itemid' );
		$params[] = array( 'section-head', JText::_('CFG_CUSTOMIZATION_SUB_ITEMID') );

		$itemidlist = array(
			'cart' => array( 'view' => 'cart', 'params' => false ),
			'checkout' => array( 'view' => 'checkout', 'params' => false ),
			'confirmation' => array( 'view' => 'confirmation', 'params' => false ),
			'subscribe' => array( 'view' => 'subscribe', 'params' => false ),
			'exception' => array( 'view' => 'exception', 'params' => false ),
			'thanks' => array( 'view' => 'thanks', 'params' => false ),
			'expired' => array( 'view' => 'expired', 'params' => false ),
			'hold' => array( 'view' => 'hold', 'params' => false ),
			'notallowed' => array( 'view' => 'notallowed', 'params' => false ),
			'pending' => array( 'view' => 'pending', 'params' => false ),
			'subscriptiondetails' => array( 'view' => 'subscriptiondetails', 'params' => false ),
			'subscriptiondetails_invoices' => array( 'view' => 'subscriptiondetails', 'params' => 'sub=invoices' ),
			'subscriptiondetails_details' => array( 'view' => 'subscriptiondetails', 'params' => 'sub=details' )
		);

		foreach ( $itemidlist as $param => $xparams ) {
			$params['itemid_'.$param]				= array( 'inputA', '' );
		}

		$params['itemid_cb']						= array( 'inputA', '' );
		$params['itemid_joomlauser']				= array( 'inputA', '' );

		$params[] = array( 'section-end' );

		@end( $params );
		$tab_data[] = array( JText::_('CFG_TAB_CUSTOMIZATION_TITLE'), key( $params ), '<h2>' . JText::_('CFG_TAB_CUSTOMIZATION_SUBTITLE') . '</h2>' );

		$params[] = array( 'page-head', JText::_('CFG_TAB_EXPERT_SUBTITLE') );
		$params[] = array( 'section', 'system' );
		$params[] = array( 'section-head', JText::_('CFG_GENERAL_SUB_SYSTEM') );
		$params['alertlevel2']					= array( 'inputA', 0 );
		$params['alertlevel1']					= array( 'inputA', 0 );
		$params['expiration_cushion']			= array( 'inputA', 0 );
		$params['invoice_cushion']				= array( 'inputA', 0 );
		$params['invoice_spawn_new']			= array( 'toggle', 0 );
		$params['heartbeat_cycle_backend']		= array( 'inputA', 0 );
		$params['allow_frontend_heartbeat']		= array( 'toggle', 0 );
		$params['disable_regular_heartbeat']	= array( 'toggle', 0 );
		$params['custom_heartbeat_securehash']	= array( 'inputC', '' );
		$params['countries_available']			= array( 'list_country_full', 0 );
		$params['countries_top']				= array( 'list_country_full', 0 );
		$params[] = array( 'section-end' );
		$params[] = array( 'section', 'api' );
		$params[] = array( 'section-head', JText::_('CFG_GENERAL_SUB_API') );
		$params['apiapplist']					= array( 'inputD', '' );
		$params[] = array( 'section-end' );

		$params[] = array( 'section', 'registration' );
		$params[] = array( 'section-head', JText::_('CFG_GENERAL_SUB_REGFLOW') );
		$params['show_fixeddecision']			= array( 'toggle', 0 );
		$params['temp_auth_exp']				= array( 'inputC', '' );
		$params['intro_expired']				= array( 'toggle', 0 );
		$params['skip_registration']			= array( 'toggle', 0 );
		$params[] = array( 'section-head', JText::_('CFG_GENERAL_SUB_CONFIRMATION') );
		$params['confirmation_coupons']			= array( 'toggle', 0 );
		$params[] = array( 'section-head', JText::_('CFG_GENERAL_SUB_CHECKOUT') );
		$params['checkoutform_jsvalidation']	= array( 'toggle', '' );
		$params['checkout_coupons']				= array( 'toggle', 1 );
		$params['checkout_as_gift']				= array( 'toggle', '' );
		$params['checkout_as_gift_access']		= array( 'list', ( defined( 'JPATH_MANIFESTS' ) ? 2 : 18 ) );
		$params['confirm_as_gift']				= array( 'toggle', '' );
		$params[] = array( 'section-head', JText::_('CFG_GENERAL_SUB_PLANS') );
		$params['root_group_rw']				= array( 'inputD', 0 );
		$params['entry_plan']					= array( 'list', 0 );
		$params['per_plan_mis']					= array( 'toggle', 0 );
		$params[] = array( 'section-end' );

		$params[] = array( 'section', 'security' );
		$params[] = array( 'section-head', JText::_('CFG_GENERAL_SUB_SECURITY') );
		$params['ssl_signup']					= array( 'toggle', 0 );
		$params['ssl_profile']					= array( 'toggle', 0 );
		$params['override_reqssl']				= array( 'toggle', 0 );
		$params['altsslurl']					= array( 'inputC', '' );
		$params['ssl_verifypeer']				= array( 'toggle', 0 );
		$params['ssl_verifyhost']				= array( 'inputC', '' );
		$params['ssl_cainfo']					= array( 'inputC', '' );
		$params['ssl_capath']					= array( 'inputC', '' );
		$params['allow_invoice_unpublished_item']				= array( 'toggle', 0 );
		$params[] = array( 'section-end' );
		$params[] = array( 'section', 'debug' );
		$params[] = array( 'section-head', JText::_('CFG_GENERAL_SUB_DEBUG') );
		$params['bypassintegration']			= array( 'inputC', '' );
		$params['breakon_mi_error']				= array( 'toggle', 0 );
		$params['email_default_admins']			= array( 'toggle', 1 );
		$params['email_extra_admins']			= array( 'inputD', '' );
		$params[] = array( 'section-end' );
		$params[] = array( 'section', 'uninstall' );
		$params[] = array( 'section-head', JText::_('CFG_GENERAL_SUB_UNINSTALL') );
		$params['delete_tables']				= array( 'toggle', 0 );
		$params['delete_tables_sure']			= array( 'toggle', 0 );
		$params[] = array( 'section-end' );

		@end( $params );
		$tab_data[] = array( JText::_('CFG_TAB_EXPERT_TITLE'), key( $params ), '<h2>' . JText::_('CFG_TAB_EXPERT_SUBTITLE') . '</h2>' );

		$error_reporting_notices[] = JHTML::_('select.option', 512, JText::_('AEC_NOTICE_NUMBER_512') );
		$error_reporting_notices[] = JHTML::_('select.option', 128, JText::_('AEC_NOTICE_NUMBER_128') );
		$error_reporting_notices[] = JHTML::_('select.option', 32, JText::_('AEC_NOTICE_NUMBER_32') );
		$error_reporting_notices[] = JHTML::_('select.option', 8, JText::_('AEC_NOTICE_NUMBER_8') );
		$error_reporting_notices[] = JHTML::_('select.option', 2, JText::_('AEC_NOTICE_NUMBER_2') );
		$lists['error_notification_level']			= JHTML::_('select.genericlist', $error_reporting_notices, 'error_notification_level', 'size="5"', 'value', 'text', $aecConfig->cfg['error_notification_level'] );
		$lists['email_notification_level']			= JHTML::_('select.genericlist', $error_reporting_notices, 'email_notification_level', 'size="5"', 'value', 'text', $aecConfig->cfg['email_notification_level'] );

		// Display Processor descriptions?
		if ( !empty( $aecConfig->cfg['gwlist'] ) ) {
			$desc_list = $aecConfig->cfg['gwlist'];
		} else {
			$desc_list = array();
		}

		$lists['gwlist'] = PaymentProcessorHandler::getProcessorSelectList( true, $desc_list );

		$grouplist = ItemGroupHandler::getTree();

		$glist = array();

		foreach ( $grouplist as $glisti ) {
			if ( defined( 'JPATH_MANIFESTS' ) ) {
				$glist[] = JHTML::_('select.option', $glisti[0], str_replace( '&nbsp;', ' ', $glisti[1] ) );
			} else {
				$glist[] = JHTML::_('select.option', $glisti[0], $glisti[1] );
			}
		}

		$lists['root_group'] 		= JHTML::_('select.genericlist', $glist, 'root_group', 'size="' . min(6,count($glist)+1) . '"', 'value', 'text', $aecConfig->cfg['root_group'] );

		foreach ( $itemidlist as $idk => $idkp ) {
			if ( empty( $aecConfig->cfg['itemid_' . $idk] ) ) {
				$query = 'SELECT `id`'
					. ' FROM #__menu'
					. ' WHERE ( LOWER( `link` ) = \'index.php?option=com_acctexp&view=' . $idkp['view'] . '\''
					. ' OR LOWER( `link` ) LIKE \'%' . 'layout='. $idkp['view'] . '%\' )'
					. ' AND published = \'1\''
				;
				$this->db->setQuery( $query );

				$mid = 0;
				if ( empty( $idkp['params'] ) ) {
					$mid = $this->db->loadResult();
				} else {
					$mids = xJ::getDBArray( $this->db );

					if ( !empty( $mids ) ) {
						$query = 'SELECT `id`'
							. ' FROM #__menu'
							. ' WHERE `id` IN (' . implode( ',', $mids ) . ')'
							. ' AND `params` LIKE \'%' . $idkp['params'] . '%\''
							. ' AND published = \'1\''
						;
						$this->db->setQuery( $query );

						$mid = $this->db->loadResult();
					}
				}

				if ( $mid ) {
					$aecConfig->cfg['itemid_' . $idk] = $mid;
				}
			}
		}

		if ( !empty( $aecConfig->cfg['apiapplist'] ) ) {
			$string = "";

			foreach ( $aecConfig->cfg['apiapplist'] as $app => $key ) {
				$string .= $app . "=" . $key . "\n";
			}

			$aecConfig->cfg['apiapplist'] = $string;
		} else {
			$aecConfig->cfg['apiapplist'] = "";
		}

		$settings = new aecSettings( 'cfg', 'general' );
		$settings->fullSettingsArray( $params, $aecConfig->cfg, $lists ) ;

		// Call HTML Class
		$aecHTML = new aecHTML( $settings->settings, $settings->lists );
		if ( !empty( $customparamsarray ) ) {
			$aecHTML->customparams = $customparamsarray;
		}

		HTML_AcctExp::Settings( $aecHTML, $params, $tab_data );
	}

	public function save()
	{
		$user = JFactory::getUser();

		global $aecConfig;

		$general_settings = AECToolbox::cleanPOST( $_POST, false );

		if ( !empty( $general_settings['apiapplist'] ) ) {
			$list = explode( "\n", $general_settings['apiapplist'] );

			$array = array();
			foreach ( $list as $item ) {
				$li = explode( "=", $item, 2 );

				$k = $li[0];

				if ( !empty( $k ) ) {
					if ( !empty( $li[1] ) ) {
						$v = $li[1];
					} else {
						$v = AECToolbox::randomstring( 32, true, true );
					}

					$array[$k] = $v;
				}
			}

			$general_settings['apiapplist'] = $array;
		} else {
			$general_settings['apiapplist'] = array();
		}

		$diff = $aecConfig->diffParams( $general_settings, 'settings' );

		if ( is_array( $diff ) ) {
			$newdiff = array();
			foreach ( $diff as $value => $change ) {
				$newdiff[] = $value . '(' . implode( ' -> ', $change ) . ')';
			}
			$difference = implode( ',', $newdiff );
		} else {
			$difference = 'none';
		}

		if ( defined( 'JPATH_MANIFESTS' ) ) {
			if ( $aecConfig->cfg['manageraccess'] !== $general_settings['manageraccess'] ) {
				if ( $general_settings['manageraccess'] ) {
					$set = '{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}';
				} else {
					$set = '{}';
				}

				$query = 'UPDATE #__assets'
					. ' SET `rules` = \'' . xJ::escape( $this->db, $set ) . '\''
					. ' WHERE `name` = \'com_acctexp\''
				;
				$this->db->setQuery( $query );
				$this->db->query();
			}
		}

		$aecConfig->cfg = $general_settings;
		$aecConfig->saveSettings();

		$ip = AECToolbox::aecIP();

		$short	= JText::_('AEC_LOG_SH_SETT_SAVED');
		$event	= JText::_('AEC_LOG_LO_SETT_SAVED') . ' ' . $difference;
		$tags	= 'settings,system';
		$params = array(
			'userid' => $user->id,
			'ip' => $ip['ip'],
			'isp' => $ip['isp']
		);

		$eventlog = new eventLog();
		$eventlog->issue( $short, $tags, $event, 2, $params );

		if ( !empty( $aecConfig->cfg['entry_plan'] ) ) {
			$plan = new SubscriptionPlan();
			$plan->load( $aecConfig->cfg['entry_plan'] );

			$terms = $plan->getTerms();

			if ( !$terms->checkFree() ) {
				$short	= "Settings Warning";
				$event	= "You have selected a non-free plan as Entry Plan."
					. " Please keep in mind that this means that users"
					. " will be getting it for free when they log in"
					. " without having any membership";
				$tags	= 'settings,system';
				$params = array(
					'userid' => $user->id,
					'ip' => $ip['ip'],
					'isp' => $ip['isp']
				);

				$eventlog = new eventLog();
				$eventlog->issue( $short, $tags, $event, 32, $params );
			}
		}

		$this->setMessage( JText::_('AEC_CONFIG_SAVED') );
	}
}

class aecAdminMembership extends aecAdminEntity
{
	public $init = array(
		'sort' => 'name ASC',
		'filter' => array(
			'status' => array('active'),
			'group' => array(),
			'plan' => array()
		)
	);

	public function index( $subscriptionid, $userid=array() )
	{
		$groups = $this->state->filter->status;
		if ( is_array($this->state->filter->status) && count( $this->state->filter->status ) === 1 ) {
			if ( $this->state->filter->status[0] == 'all' ) {
				$groups = array('active', 'excluded', 'expired', 'pending', 'cancelled', 'hold', 'closed');
			}
		}

		if ( array_search( 'notconfig', $this->state->filter->status ) ) {
			$set_group	= 'notconfig';
		} else {
			$set_group	= strtolower($this->state->filter->status[0]);
		}

		if ( !empty( $orderby ) ) {
			if ( $set_group == 'notconfig' ) {
				$forder = array(
					'name ASC', 'name DESC', 'lastname ASC', 'lastname DESC', 'username ASC', 'username DESC',
					'signup_date ASC', 'signup_date DESC', 'lastpay_date ASC', 'lastpay_date DESC',
				);
			} else {
				$forder = array(
					'expiration ASC', 'expiration DESC', 'lastpay_date ASC', 'lastpay_date DESC',
					'name ASC', 'name DESC', 'lastname ASC', 'lastname DESC', 'username ASC', 'username DESC',
					'signup_date ASC', 'signup_date DESC', 'lastpay_date ASC', 'lastpay_date DESC',
					'plan_name ASC', 'plan_name DESC', 'status ASC', 'status DESC', 'type ASC', 'type DESC'
				);
			}

			if ( !in_array( $orderby, $forder ) ) {
				$this->state->sort = 'name ASC';
			}
		}

		// define displaying at html
		$action = array();
		switch( $set_group ){
			case 'active':
				$action[0]	= 'active';
				$action[1]	= JText::_('AEC_HEAD_ACTIVE_SUBS');
				break;

			case 'excluded':
				$action[0]	= 'excluded';
				$action[1]	= JText::_('AEC_HEAD_EXCLUDED_SUBS');
				break;

			case 'expired':
				$action[0]	= 'expired';
				$action[1]	= JText::_('AEC_HEAD_EXPIRED_SUBS');
				break;

			case 'pending':
				$action[0]	= 'pending';
				$action[1]	= JText::_('AEC_HEAD_PENDING_SUBS');
				break;

			case 'cancelled':
				$action[0]	= 'cancelled';
				$action[1]	= JText::_('AEC_HEAD_CANCELLED_SUBS');
				break;

			case 'hold':
				$action[0]	= 'hold';
				$action[1]	= JText::_('AEC_HEAD_HOLD_SUBS');
				break;

			case 'closed':
				$action[0]	= 'closed';
				$action[1]	= JText::_('AEC_HEAD_CLOSED_SUBS');
				break;

			case 'manual':
			case 'notconfig':
				$action[0]	= 'manual';
				$action[1]	= JText::_('AEC_HEAD_MANUAL_SUBS');
				break;
		}

		$where		= array();
		$where_or	= array();
		$notconfig	= false;

		$planid = trim( aecGetParam( 'assign_planid', null ) );

		$users_selected = ( ( is_array( $subscriptionid ) && count( $subscriptionid ) ) || ( is_array( $userid ) && count( $userid ) ) );

		if ( !empty( $planid ) && $users_selected ) {
			$plan = new SubscriptionPlan();
			$plan->load( $planid );

			if ( !empty( $subscriptionid ) ) {
				foreach ( $subscriptionid as $sid ) {
					$metaUser = new metaUser( false, $sid );

					$metaUser->establishFocus( $plan );

					$metaUser->focusSubscription->applyUsage( $planid, 'none', 1 );
				}
			}

			if ( !empty( $userid ) ) {
				foreach ( $userid as $uid ) {
					$metaUser = new metaUser( $uid );

					$metaUser->establishFocus( $plan );

					$metaUser->focusSubscription->applyUsage( $planid, 'none', 1 );

					$subscriptionid[] = $metaUser->focusSubscription->id;
				}
			}

			// Also show active users now
			if ( !in_array( 'active', $groups ) ) {
				$groups[] = 'active';
			}
		}

		$set_status = trim( aecGetParam( 'set_status', null ) );
		$add_or_set_expiration = trim( aecGetParam( 'add_or_set_expiration', null ) );
		$set_time = trim( aecGetParam( 'set_time', null ) );
		$set_time_unit = trim( aecGetParam( 'set_time_unit', null ) );

		if (
			( !empty( $set_status ) || !empty( $add_or_set_expiration ) )
			&& is_array( $subscriptionid )
			&& count( $subscriptionid ) > 0
		) {
			foreach ( $subscriptionid as $k ) {
				$subscriptionHandler = new Subscription();

				if ( !empty( $k ) ) {
					$subscriptionHandler->load( $k );
				} else {
					$subscriptionHandler->createNew( $k, '', 1 );
				}

				if ( strcmp( $set_status, 'now' ) === 0) {
					$subscriptionHandler->expire();

					if ( !in_array( 'expired', $groups ) ) {
						$groups[] = 'expired';
					}
				} elseif ( strcmp( $set_status, 'exclude' ) === 0 ) {
					$subscriptionHandler->setStatus( 'Excluded' );

					if ( !in_array( 'excluded', $groups ) ) {
						$groups[] = 'excluded';
					}
				} elseif ( strcmp( $set_status, 'close' ) === 0 ) {
					$subscriptionHandler->setStatus( 'Closed' );

					if ( !in_array( 'closed', $groups ) ) {
						$groups[] = 'closed';
					}
				} elseif ( strcmp( $set_status, 'hold' ) === 0 ) {
					$subscriptionHandler->setStatus( 'Hold' );

					if ( !in_array( 'hold', $groups ) ) {
						$groups[] = 'hold';
					}
				} elseif ( strcmp( $set_status, 'include' ) === 0 ) {
					$subscriptionHandler->setStatus( 'Active' );

					if ( !in_array( 'active', $groups ) ) {
						$groups[] = 'active';
					}
				} elseif ( strcmp( $set_status, 'lifetime' ) === 0 ) {
					if ( !$subscriptionHandler->isLifetime() ) {
						$subscriptionHandler->expiration = '9999-12-31 00:00:00';
						$subscriptionHandler->lifetime = 1;
					}

					$subscriptionHandler->setStatus( 'Active' );

					if ( !in_array( 'active', $groups ) ) {
						$groups[] = 'active';
					}
				}

				if (
					$set_status !== 'lifetime'
					&& !empty($add_or_set_expiration)
					&& !empty($set_time)
					&& !empty($set_time_unit)
				) {
					if ( $add_or_set_expiration == 'set' ) {
						$subscriptionHandler->setExpiration( $set_time_unit, $set_time, 0 );

						$subscriptionHandler->lifetime = 0;

						$subscriptionHandler->storeload();

						if ( !in_array( 'active', $groups ) ) {
							$groups[] = 'active';
						}
					} elseif ( $add_or_set_expiration == 'add' ) {
						if ( $subscriptionHandler->lifetime) {
							$subscriptionHandler->setExpiration( $set_time_unit, $set_time, 0 );
						} else {
							$subscriptionHandler->setExpiration( $set_time_unit, $set_time, 1 );
						}

						$subscriptionHandler->lifetime = 0;

						$subscriptionHandler->storeload();

						if ( !in_array( 'active', $groups ) ) {
							$groups[] = 'active';
						}
					}
				}
			}
		}

		if ( is_array( $groups ) ) {
			if ( in_array( 'notconfig', $groups ) ) {
				$notconfig = true;
				$groups = array( 'notconfig' );
			} else {
				if ( in_array( 'excluded', $groups ) ) {
					$where_or[] = "a.status = 'Excluded'";
				}
				if ( in_array( 'expired', $groups ) ) {
					$where_or[] = "a.status = 'Expired'";
				}
				if ( in_array( 'active', $groups ) ) {
					$where_or[] = "(a.status = 'Active' || a.status = 'Trial')";
				}
				if ( in_array( 'pending', $groups ) ) {
					$where_or[] = "a.status = 'Pending'";
				}
				if ( in_array( 'cancelled', $groups ) ) {
					$where_or[] = "a.status = 'Cancelled'";
				}
				if ( in_array( 'hold', $groups ) ) {
					$where_or[] = "a.status = 'Hold'";
				}
				if ( in_array( 'closed', $groups ) ) {
					$where_or[] = "a.status = 'Closed'";
				}
			}
		}

		if ( isset( $search ) && $search!= '' ) {
			if ( $notconfig ) {
				$where[] = "(username LIKE '%$search%' OR name LIKE '%$search%')";
			} else {
				$where[] = "(b.username LIKE '%$search%' OR b.name LIKE '%$search%')";
			}
		}

		$group_plans = ItemGroupHandler::getChildren( $this->state->filter->group, 'item' );

		if ( !empty( $this->state->filter->plan ) || !empty( $group_plans ) ) {
			$plan_selection = array();

			if ( !empty( $this->state->filter->plan ) ) {
				$plan_selection = $this->state->filter->plan;
			}

			if ( !empty( $group_plans ) ) {
				$plan_selection = array_merge( $plan_selection, $group_plans );
			}

			if ( empty( $plan_selection[0] ) ) {
				unset( $plan_selection[0] );
			}

			$plan_selection = array_unique( $plan_selection );

			if ( !$notconfig && !empty( $plan_selection ) ) {
				$where[] = "a.plan IN (" . implode( ',', $plan_selection ) . ")";
			}
		}

		// get the total number of records
		if ( $notconfig ) {
			$where[] = 'b.status is null';

			$query = 'SELECT count(*)'
				. ' FROM #__users AS a'
				. ' LEFT JOIN #__acctexp_subscr AS b ON a.id = b.userid'
				. (count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
			;
		} else {
			$query = 'SELECT count(*)'
				. ' FROM #__acctexp_subscr AS a'
				. ' INNER JOIN #__users AS b ON a.userid = b.id'
			;

			if ( count( $where_or ) ) {
				$where[] = ( count( $where_or ) ? '(' . implode( ' OR ', $where_or ) . ')' : '' );
			}

			$query .= (count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		}

		$this->db->setQuery( $query );

		$nav = $this->getPagination(
			$this->db->loadResult()
		);

		$orderby = $this->state->sort;

		// get the subset (based on limits) of required records
		if ( $notconfig ) {
			$forder = array(
				'name ASC', 'name DESC',
				'lastname ASC', 'lastname DESC',
				'username ASC', 'username DESC',
				'signup_date ASC', 'signup_date DESC'
			);

			if ( !in_array( $this->state->sort, $forder ) ) {
				$orderby = 'name ASC';
			}

			if ( strpos( $orderby, 'lastname' ) !== false ) {
				$orderby = str_replace( 'lastname', 'SUBSTRING_INDEX(name, \' \', -1)', $orderby );
			}

			$query = 'SELECT a.id, a.name, a.username, a.registerDate as signup_date'
				. ' FROM #__users AS a'
				. ' LEFT JOIN #__acctexp_subscr AS b ON a.id = b.userid'
				. (count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
				. ' ORDER BY ' . str_replace( 'signup_date', 'registerDate', $orderby )
				. ' LIMIT ' . $nav->limitstart . ',' . $nav->limit
			;

			if ( strpos( $orderby, 'SUBSTRING_INDEX' ) !== false ) {
				$orderby = str_replace( 'SUBSTRING_INDEX(name, \' \', -1)', 'lastname', $orderby );
			}
		} else {
			if ( strpos( $orderby, 'lastname' ) !== false ) {
				$orderby = str_replace( 'lastname', 'SUBSTRING_INDEX(b.name, \' \', -1)', $orderby );
			}

			$query = 'SELECT a.*, b.name, b.username, b.email, c.name AS plan_name'
				. ' FROM #__acctexp_subscr AS a'
				. ' INNER JOIN #__users AS b ON a.userid = b.id'
				. ' LEFT JOIN #__acctexp_plans AS c ON a.plan = c.id'
				. ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' )
				. ' ORDER BY ' . $orderby
				. ' LIMIT ' . $nav->limitstart . ',' . $nav->limit
			;

			if ( strpos( $orderby, 'SUBSTRING_INDEX' ) !== false ) {
				$orderby = str_replace( 'SUBSTRING_INDEX(b.name, \' \', -1)', 'lastname', $orderby );
			}
		}

		$this->state->sort = $orderby;

		$this->db->setQuery( 'SET SQL_BIG_SELECTS=1');
		$this->db->query();

		$this->db->setQuery( $query );
		$rows = $this->db->loadObjectList();

		if ( $this->db->getErrorNum() ) {
			echo $this->db->stderr();
			return false;
		}

		$this->db->setQuery( 'SET SQL_BIG_SELECTS=0');
		$this->db->query();

		$processors = PaymentProcessorHandler::getObjectList(
			PaymentProcessorHandler::getProcessorList()
		);

		$procs = array(
			'free' => 'Free',
			'none' => 'None'
		);

		foreach ( $processors as $processor ) {
			$procs[$processor->processor_name] = $processor->processor->info['longname'];
		}

		foreach ( $rows as $k => $row ) {
			if ( !isset($rows[$k]->type) ) continue;

			$rows[$k]->type = $procs[$rows[$k]->type];
		}

		// Get list of plans for filter
		$query = 'SELECT `id`, `name`'
			. ' FROM #__acctexp_plans'
			. ' ORDER BY `ordering`'
		;
		$this->db->setQuery( $query );
		$db_plans = $this->db->loadObjectList();

		$plans2[] = JHTML::_('select.option', '0', JText::_('BIND_USER'), 'id', 'name' );

		if ( is_array( $db_plans ) ) {
			$plans2 = array_merge( $plans2, $db_plans );
		}

		$lists['set_plan']	= JHTML::_('select.genericlist', $plans2, 'assign_planid', 'class="form-control inputbox" size="1"', 'id', 'name', 0 );

		$lists['filter_plan'] = '<select id="plan-filter-select" name="filter_plan[]" multiple="multiple" size="5">';
		foreach ( $db_plans as $plan ) {
			$lists['filter_plan'] .= '<option value="' . $plan->id . '"' . ( in_array( $plan->id, $this->state->filter->plan ) ? ' selected="selected"' : '' ) . '>' . $plan->name . '</option>';
		}
		$lists['filter_plan'] .= '</select>';

		$grouplist = ItemGroupHandler::getTree();

		$lists['filter_group'] = '<select id="group-filter-select" name="filter_group[]" multiple="multiple" size="5">';
		foreach ( $grouplist as $glisti ) {
			if ( defined( 'JPATH_MANIFESTS' ) ) {
				$lists['filter_group'] .= '<option value="' . $glisti[0] . '"' . ( in_array( $glisti[0], $this->state->filter->group ) ? ' selected="selected"' : '' ) . '>' . str_replace( '&nbsp;', ' ', $glisti[1] ) . '</option>';
			} else {
				$lists['filter_group'] .= '<option value="' . $glisti[0] . '"' . ( in_array( $glisti[0], $this->state->filter->group ) ? ' selected="selected"' : '' ) . '>' . $glisti[1] . '</option>';
			}
		}
		$lists['filter_group'] .= '</select>';

		$status = array(
			'excluded'	=> JText::_('AEC_SEL_EXCLUDED'),
			'pending'	=> JText::_('AEC_SEL_PENDING'),
			'active'	=> JText::_('AEC_SEL_ACTIVE'),
			'expired'	=> JText::_('AEC_SEL_EXPIRED'),
			'closed'	=> JText::_('AEC_SEL_CLOSED'),
			'cancelled'	=> JText::_('AEC_SEL_CANCELLED'),
			'hold'		=> JText::_('AEC_SEL_HOLD'),
			'notconfig'	=> JText::_('AEC_SEL_NOT_CONFIGURED')
		);

		$lists['filter_status'] = '<select id="status-group-select" name="filter_status[]" multiple="multiple" size="5">';
		foreach ( $status as $id => $txt ) {
			$lists['filter_status'] .= '<option value="' . $id . '"' . ( in_array( $id, $groups ) ? ' selected="selected"' : '' ) . '>' . $txt . '</option>';
		}
		$lists['filter_status'] .= '</select>';

		$group_selection = array();
		$group_selection[] = JHTML::_('select.option', '',			JText::_('Set Status') );
		$group_selection[] = JHTML::_('select.option', 'now',		JText::_('EXPIRE_NOW') );
		$group_selection[] = JHTML::_('select.option', 'exclude',	JText::_('EXPIRE_EXCLUDE') );
		$group_selection[] = JHTML::_('select.option', 'lifetime',	JText::_('AEC_CMN_LIFETIME') );
		$group_selection[] = JHTML::_('select.option', 'include',	JText::_('EXPIRE_INCLUDE') );
		$group_selection[] = JHTML::_('select.option', 'close',		JText::_('EXPIRE_CLOSE') );
		$group_selection[] = JHTML::_('select.option', 'hold',		JText::_('EXPIRE_HOLD') );

		$lists['set_status'] = JHTML::_('select.genericlist', $group_selection, 'set_status', 'class="form-control inputbox" size="1"', 'value', 'text', "");

		$group_selection = array();
		$group_selection[] = JHTML::_('select.option', 'add',		JText::_('Add') );
		$group_selection[] = JHTML::_('select.option', 'set',		JText::_('Set') );

		$lists['add_or_set_expiration'] = JHTML::_('select.genericlist', $group_selection, 'add_or_set_expiration', 'class="form-control inputbox" size="1"', 'value', 'text', "");

		// make the select list for first trial period units
		$perunit[] = JHTML::_('select.option', 'D', JText::_('PAYPLAN_PERUNIT1') );
		$perunit[] = JHTML::_('select.option', 'W', JText::_('PAYPLAN_PERUNIT2') );
		$perunit[] = JHTML::_('select.option', 'M', JText::_('PAYPLAN_PERUNIT3') );
		$perunit[] = JHTML::_('select.option', 'Y', JText::_('PAYPLAN_PERUNIT4') );

		$lists['set_time_unit'] = JHTML::_('select.genericlist', $perunit, 'set_time_unit', 'class="form-control inputbox" size="1"', 'value', 'text');

		HTML_AcctExp::listSubscriptions( $rows, $nav, $this->state, $lists, $subscriptionid, $action );
	}

	public function edit( $userid, $subscriptionid, $task, $page=0 )
	{
		if ( !empty( $subscriptionid ) ) {
			$userid = aecUserHelper::UserIDfromSubscriptionID( $subscriptionid );
		}

		if ( !empty( $subscriptionid ) ) {
			$sid = $subscriptionid;
		} else {
			$sid = 0;
		}

		$lists = array();

		$metaUser = new metaUser( $userid );

		if ( !empty( $sid ) ) {
			$metaUser->moveFocus( $sid );
		} else {
			if ( $metaUser->hasSubscription ) {
				$sid = $metaUser->focusSubscription->id;
			}
		}

		if ( $metaUser->loadSubscriptions() && !empty( $sid ) ) {
			foreach ( $metaUser->allSubscriptions as $s_id => $s_c ) {
				if ( $s_c->id == $sid ) {
					$metaUser->allSubscriptions[$s_id]->current_focus = true;
					continue;
				}
			}
		}

		$invoices_limit = 15;

		$invoice_ids = aecInvoiceHelper::InvoiceIdList( $metaUser->userid, $page*$invoices_limit, $invoices_limit );

		$group_selection = array();
		$group_selection[] = JHTML::_('select.option', '',			JText::_('EXPIRE_SET') );
		$group_selection[] = JHTML::_('select.option', 'expired',	JText::_('EXPIRE_NOW') );
		$group_selection[] = JHTML::_('select.option', 'excluded',	JText::_('EXPIRE_EXCLUDE') );
		$group_selection[] = JHTML::_('select.option', 'active',	JText::_('EXPIRE_INCLUDE') );
		$group_selection[] = JHTML::_('select.option', 'closed',	JText::_('EXPIRE_CLOSE') );
		$group_selection[] = JHTML::_('select.option', 'cancelled',	JText::_('EXPIRE_CANCEL') );
		$group_selection[] = JHTML::_('select.option', 'hold',		JText::_('EXPIRE_HOLD') );

		$lists['set_status'] = JHTML::_('select.genericlist', $group_selection, 'set_status', 'class="inputbox" size="1"', 'value', 'text', '' );

		$invoices = array();
		$couponsh = array();
		$invoice_counter = 0;

		$processors = PaymentProcessorHandler::getObjectList(
			PaymentProcessorHandler::getProcessorList()
		);

		$procs = array(
			'free' => 'Free',
			'none' => 'None'
		);

		foreach ( $processors as $processor ) {
			$procs[$processor->processor_name] = $processor->processor->info['longname'];
		}

		foreach ( $invoice_ids as $inv_id ) {
			$invoice = new Invoice();
			$invoice->load ($inv_id );

			if ( !empty( $invoice->coupons ) ) {
				foreach( $invoice->coupons as $coupon_code ) {
					if ( !isset( $couponsh[$coupon_code] ) ) {
						$couponsh[$coupon_code] = couponHandler::idFromCode( $coupon_code );
					}

					$couponsh[$coupon_code]['invoices'][] = $invoice->invoice_number;
				}
			}

			if ( $invoice_counter >= $invoices_limit && ( strcmp( $invoice->transaction_date, '0000-00-00 00:00:00' ) !== 0 ) ) {
				continue;
			} else {
				$invoice_counter++;
			}

			$status = aecHTML::Icon( 'plus' ) . HTML_AcctExp::DisplayDateInLocalTime( $invoice->created_date ) . '<br />';

			if ( isset( $invoice->params['deactivated'] ) ) {
				$status .= aecHTML::Icon( 'remove-circle' ) . 'deactivated';
			} elseif ( strcmp( $invoice->transaction_date, '0000-00-00 00:00:00' ) === 0 ) {
				if ( isset( $invoice->params['pending_reason'] ) ) {
					if ( $this->lang->hasKey( 'PAYMENT_PENDING_REASON_' . strtoupper( $invoice->params['pending_reason'] ) ) ) {
						$status .= aecHTML::Icon( 'warning-sign' ) . JText::_( 'PAYMENT_PENDING_REASON_' . strtoupper($invoice->params['pending_reason'] ) );
					} else {
						$status .= aecHTML::Icon( 'warning-sign' ) . $invoice->params['pending_reason'];
					}
				} else {
					$status .= aecHTML::Icon( 'time' ) . 'uncleared';
				}
			}

			$actions	= array();
			$rowstyle	= '';

			if ( strcmp( $invoice->transaction_date, '0000-00-00 00:00:00' ) === 0 ) {
				$checkoutlink = AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=repeatPayment&amp;invoice=' . $invoice->invoice_number );

				$actions = array(
					array( 'repeat', 'arrow-right', 'USERINVOICE_ACTION_REPEAT', 'info', '', $checkoutlink ),
					array( 'cancel', 'remove', 'USERINVOICE_ACTION_CANCEL', 'danger' ),
					array( 'clear', 'ok', 'USERINVOICE_ACTION_CLEAR_APPLY', 'success', '&applyplan=1' ),
					array( 'clear', 'check', 'USERINVOICE_ACTION_CLEAR', 'warning' ),
				);

				$rowstyle = ' style="background-color:#fee;"';
			} else {
				$status .= aecHTML::Icon( 'shopping-cart' ) . HTML_AcctExp::DisplayDateInLocalTime( $invoice->transaction_date );
			}

			$actions[] = array( 'print', 'print', 'HISTORY_ACTION_PRINT', '', '&tmpl=component" target="_blank' );
			$actions[] = array( 'pdf', 'file', 'PDF', '', '' );

			$actionlist = '<div class="btn-group">';
			foreach ( $actions as $action ) {
				if ( !empty( $action[5] ) ) {
					$alink = $action[5];
				} else {
					$alink = 'index.php?option=com_acctexp&task='.$action[0].'Invoice&invoice='. $invoice->invoice_number . '&returntask=edit&amp;entity=Membership&userid=' . $metaUser->userid;

					if ( !empty( $action[4] ) ) {
						$alink .= $action[4];
					}
				}

				$actionlist .= aecHTML::Button( $action[1], $action[2], $action[3], $alink );
			}
			$actionlist .= '</div>';

			$non_formatted = $invoice->invoice_number;
			$invoice->formatInvoiceNumber();
			$is_formatted = $invoice->invoice_number;

			if ( $non_formatted != $is_formatted ) {
				$is_formatted = $non_formatted . "\n" . '(' . $is_formatted . ')';
			}

			$invoices[$inv_id] = array();
			$invoices[$inv_id]['rowstyle']			= $rowstyle;
			$invoices[$inv_id]['invoice_number']	= $is_formatted;
			$invoices[$inv_id]['amount']			= $invoice->amount . '&nbsp;' . $invoice->currency;
			$invoices[$inv_id]['status']			= $status;

			if ( $procs[$invoice->method] ) {
				$invoices[$inv_id]['processor']		= $invoice->method;
			} else {
				$invoices[$inv_id]['processor']		= $procs[$invoice->method];
			}

			$invoices[$inv_id]['usage']				= $invoice->usage;
			$invoices[$inv_id]['actions']			= $actionlist;
		}

		$coupons = array();

		$coupon_counter = 0;
		foreach ( $couponsh as $coupon_code => $coupon ) {
			if ( $coupon_counter >= 10 ) {
				continue;
			} else {
				$coupon_counter++;
			}

			$cc = array();
			$cc['coupon_code']	= '<a href="index.php?option=com_acctexp&amp;task=edit&amp;entity=Coupon&id=' . $coupon['type'].'.'.$coupon['id'] . '">' . $coupon_code . '</a>';
			$cc['invoices']		= implode( ", ", $coupon['invoices'] );

			$coupons[] = $cc;
		}

		// get available plans
		$available_plans	= SubscriptionPlanHandler::getActivePlanList(false);

		$lists['assignto_plan'] = JHTML::_('select.genericlist', $available_plans, 'assignto_plan[]', 'size="1" multiple="multiple" class="select2-bootstrap"', 'value', 'text', 0 );

		$userMIs = $metaUser->getUserMIs();

		$mi					= array();
		$mi['profile']		= array();
		$mi['admin']		= array();
		$mi['profile_form']	= array();
		$mi['admin_form']	= array();

		$params = array();

		foreach ( $userMIs as $m ) {
			$pref = 'mi_'.$m->id.'_';

			$ui = $m->profile_info( $metaUser );
			if ( !empty( $ui ) ) {
				$mi['profile'][] = array( 'name' => $m->info['name'] . ' - ' . $m->name, 'info' => $ui );
			}

			$uf = $m->profile_form( $metaUser, true );
			if ( !empty( $uf ) ) {
				foreach ( $uf as $k => $v ) {
					$mi['profile_form'][] = $pref.$k;
					$params[$pref.$k] = $v;
				}
			}

			$ai = $m->admin_info( $metaUser );
			if ( !empty( $ai ) ) {
				$mi['admin'][] = array( 'name' => $m->info['name'] . ' - ' . $m->name, 'info' => $ai );
			}

			$af = $m->admin_form( $metaUser );
			if ( !empty( $af ) ) {
				foreach ( $af as $k => $v ) {
					$mi['admin_form'][] = $pref.$k;
					$params[$pref.$k] = $v;
				}
			}
		}

		if ( !empty( $params ) ) {
			$settings = new aecSettings ( 'userForm', 'mi' );
			$settings->fullSettingsArray( $params, array(), $lists ) ;

			// Call HTML Class
			$aecHTML = new aecHTML( $settings->settings, $settings->lists );
		} else {
			$aecHTML = new stdClass();
		}

		$aecHTML->invoice_pages	= (int) ( aecInvoiceHelper::InvoiceCountbyUserID( $metaUser->userid ) / $invoices_limit );
		$aecHTML->invoice_page	= $page;
		$aecHTML->sid			= $sid;

		HTML_AcctExp::userForm( $metaUser, $invoices, $coupons, $mi, $lists, $task, $aecHTML );
	}

	public function save($userid, $subscriptionid, $assignto_plan)
	{
		$post = $_POST;

		if ( !empty($assignto_plan) ) {
			if ( $assignto_plan[0] == 0 ) {
				unset( $assignto_plan[0] );
			}
		}

		$metaUser = new metaUser($userid);

		if ( $metaUser->hasSubscription && !empty($subscriptionid) ) {
			$metaUser->moveFocus($subscriptionid);
		}

		$ck_primary = aecGetParam( 'ck_primary' );

		if ( $ck_primary && !$metaUser->focusSubscription->primary ) {
			$metaUser->focusSubscription->makePrimary();
		}

		if ( !empty($assignto_plan) && is_array($assignto_plan) ) {
			foreach ( $assignto_plan as $assign_planid ) {
				$plan = new SubscriptionPlan();
				$plan->load( $assign_planid );

				$metaUser->establishFocus( $plan );

				$metaUser->focusSubscription->applyUsage( $assign_planid, 'none', 1 );

				// We have to reload the metaUser object because of the changes
				$metaUser = new metaUser($userid);
			}
		}

		$ck_lifetime = aecGetParam( 'ck_lifetime' );

		$set_status = trim( aecGetParam( 'set_status', null ) );

		if ( !$metaUser->hasSubscription ) {
			if ( $set_status == 'excluded' ) {
				$metaUser->focusSubscription = new Subscription();
				$metaUser->focusSubscription->createNew( $metaUser->userid, 'none', 0 );

				$metaUser->hasSubscription = true;
			} else {
				echo "<script> alert('".JText::_('AEC_ERR_NO_SUBSCRIPTION')."'); window.history.go(-1); </script>\n";
				exit();
			}
		}

		if ( empty( $assignto_plan ) ) {
			if ( $ck_lifetime ) {
				$metaUser->focusSubscription->expiration	= '9999-12-31 00:00:00';
				$metaUser->focusSubscription->status		= 'Active';
				$metaUser->focusSubscription->lifetime	= 1;
			} elseif ( !empty( $post['expiration'] ) ) {
				if ( $post['expiration'] != $post['expiration_check'] ) {
					if ( strpos( $post['expiration'], ':' ) === false ) {
						$metaUser->focusSubscription->expiration = $post['expiration'] . ' 00:00:00';
					} else {
						$metaUser->focusSubscription->expiration = $post['expiration'];
					}

					if ( $metaUser->focusSubscription->status == 'Trial' ) {
						$metaUser->focusSubscription->status = 'Trial';
					} else {
						$metaUser->focusSubscription->status = 'Active';
					}

					$metaUser->focusSubscription->lifetime = 0;
				}
			}
		}

		if ( !empty( $set_status ) ) {
			switch ( $set_status ) {
				case 'expired':
					$metaUser->focusSubscription->expire();
					break;
				case 'cancelled':
					$metaUser->focusSubscription->cancel();
					break;
				default:
					$metaUser->focusSubscription->setStatus( ucfirst( $set_status ) );
					break;
			}
		}

		if ( !empty( $post['notes'] ) ) {
			$metaUser->focusSubscription->customparams['notes'] = $post['notes'];

			unset( $post['notes'] );
		}

		if ( $metaUser->hasSubscription ) {
			$metaUser->focusSubscription->storeload();
		}

		$userMIs = $metaUser->getUserMIs();

		if ( !empty( $userMIs ) ) {
			foreach ( $userMIs as $m ) {
				$params = array();

				$pref = 'mi_'.$m->id.'_';

				$uf = $m->profile_form( $metaUser );
				if ( !empty( $uf ) ) {
					foreach ( $uf as $k => $v ) {
						if ( isset( $post[$pref.$k] ) ) {
							$params[$k] = $post[$pref.$k];
						}
					}

					$m->profile_form_save( $metaUser, $params );
				}

				$admin_params = array();

				$af = $m->admin_form( $metaUser );
				if ( !empty( $af ) ) {
					foreach ( $af as $k => $v ) {
						if ( isset( $post[$pref.$k] ) ) {
							$admin_params[$k] = $post[$pref.$k];
						}
					}

					$m->admin_form_save( $metaUser, $admin_params );
				}

				if ( empty( $params ) ) {
					continue;
				}

				$metaUser->meta->setMIParams( $m->id, null, $params, true );
			}

			$metaUser->meta->storeload();
		}
	}
}

class aecAdminTemplate extends aecAdminEntity
{
	public function index()
	{
		$list = xJUtility::getFileArray( JPATH_SITE . '/components/com_acctexp/tmpl', '[*]', true );

		foreach ( $list as $id => $name ) {
			if ( ( $name == 'default' ) || ( $name == 'classic' ) ) {
				unset( $list[$id] );
			}
		}

		$nav = $this->getPagination( count($list) );

		$names = array_slice( $list, $this->state->limitstart, $this->state->limit );

		$rows = array();
		foreach ( $names as $name ) {
			$t = new configTemplate();
			$t->loadName( $name );

			if ( !$t->id ){
				$t->default = 0;

				if ( $name == 'helix' ) {
					continue;
				}
			}

			$rows[] = $t;
		}

		HTML_AcctExp::listTemplates( $rows, $nav );
	}

	public function edit( $name )
	{
		$temp = new configTemplate();
		$temp->loadName( $name );

		$tempsettings = $temp->template->settings();
		$temp->settings['default'] = $temp->default;

		$lists = array();

		$settings = new aecSettings ( 'cfg', 'general' );
		$settings->fullSettingsArray( $tempsettings['params'], $temp->settings, $lists ) ;

		// Call HTML Class
		$aecHTML = new aecHTML( $settings->settings, $settings->lists );

		$aecHTML->tempname	= $name;
		$aecHTML->name		= $temp->info['longname'];

		HTML_AcctExp::editTemplate( $aecHTML, $tempsettings['tab_data'] );
	}

	public function save( $name )
	{
		$temp = new configTemplate();
		$temp->loadName( $name );

		if ( $_POST['default'] ) {
			if ( $temp->id ) {
				if ( !$temp->default ) {
					// Reset all other items
					$this->db->setQuery(
						'UPDATE #__acctexp_config_templates'
						. ' SET `default` = 0'
						. ' WHERE `id` > 0'
					);
					$this->db->query();
				}
			} else {
				// Reset all other items
				$this->db->setQuery(
					'UPDATE #__acctexp_config_templates'
					. ' SET `default` = 0'
					. ' WHERE `id` > 0'
				);
				$this->db->query();
			}

			$temp->default = 1;
		} else {
			$temp->default = 0;
		}

		unset( $_POST['id'] );
		unset( $_POST['task'] );
		unset( $_POST['option'] );
		unset( $_POST['name'] );
		unset( $_POST['default'] );

		$temp->template->cfg = $temp->settings;

		$temp->settings = $_POST;

		$temp->storeload();
	}
}

class aecAdminProcessor extends aecAdminEntity
{
	public $table = 'config_processors';

	public function index()
	{
		$rows = $this->getRows();
		foreach ( $rows as $k => $id ) {
			$pp = new PaymentProcessor();
			$pp->loadId($id);

			if ( $pp->fullInit() ) {
				$rows[$k] = $pp;
			}
		}

		HTML_AcctExp::listProcessors($rows, $this->getPagination());
	}

	public function edit( $id )
	{
		if ( $id ) {
			$pp = new PaymentProcessor();

			if ( !$pp->loadId( $id ) ) {
				return false;
			}

			// Init Info and Settings
			$pp->fullInit(true);

			$lists = array();

			// Get Backend Settings
			$settings_array		= $pp->getBackendSettings();
			$original_settings	= $pp->processor->settings();

			if ( isset( $settings_array['lists'] ) ) {
				foreach ( $settings_array['lists'] as $lname => $lvalue ) {
					$list_name = $pp->processor_name . '_' . $lname;

					$lists[$list_name] = str_replace( 'name="' . $lname . '"', 'name="' . $list_name . '"', $lvalue );
				}

				unset( $settings_array['lists'] );
			}

			$available_plans = SubscriptionPlanHandler::getActivePlanList();

			// Iterate through settings form assigning the db settings
			foreach ( $settings_array as $name => $values ) {
				$setting_name = $pp->processor_name . '_' . $name;

				switch( $settings_array[$name][0] ) {
					case 'list_currency':
						// Get currency list
						if ( is_array( $pp->info['currencies'] ) ) {
							$currency_array	= $pp->info['currencies'];
						} else {
							$currency_array	= explode( ',', $pp->info['currencies'] );
						}

						// Transform currencies into OptionArray
						$currency_code_list = array();
						foreach ( $currency_array as $currency ) {
							if ( $this->lang->hasKey( 'CURRENCY_' . $currency )) {
								$currency_code_list[] = JHTML::_('select.option', $currency, $currency . ' - ' . JText::_( 'CURRENCY_' . $currency ) );
							}
						}

						$size = min( count($currency_array), 10 );

						// Create list
						$lists[$setting_name] = JHTML::_('select.genericlist', $currency_code_list, $setting_name, 'size="' . $size . '"', 'value', 'text', $pp->settings[$name] );

						$settings_array[$name][0] = 'list';
						break;
					case 'list_language':
						// Get language list
						if ( is_array( $pp->info['languages'] ) ) {
							$this->language_array	= $pp->info['languages'];
						} else {
							$this->language_array	= explode( ',', $pp->info['languages'] );
						}

						// Transform languages into OptionArray
						$this->language_code_list = array();
						foreach ( $this->language_array as $this->language ) {
							$this->language_code_list[] = JHTML::_('select.option', $this->language, JText::_( 'LANGUAGECODE_' . strtoupper( $this->language ) ) );
						}
						// Create list
						$lists[$setting_name] = JHTML::_('select.genericlist', $this->language_code_list, $setting_name, 'size="10"', 'value', 'text', $pp->settings[$name] );
						$settings_array[$name][0] = 'list';
						break;
					case 'list_plan':
						// Create list
						$lists[$setting_name] = JHTML::_('select.genericlist', $available_plans, $setting_name, 'size="10"', 'value', 'text', $pp->settings[$name] );
						$settings_array[$name][0] = 'list';
						break;
					default:
						break;
				}

				if ( !isset( $settings_array[$name][1] ) ) {
					$settings_array[$name][1] = $pp->getParamLang( $name . '_NAME' );
					$settings_array[$name][2] = $pp->getParamLang( $name . '_DESC' );
				}

				// It might be that the processor has got some new properties, so we need to double check here
				if ( isset( $pp->settings[$name] ) ) {
					$content = $pp->settings[$name];
				} elseif ( isset( $original_settings[$name] ) ) {
					$content = $original_settings[$name];
				} else {
					$content = null;
				}

				// Set the settings value
				$settings_array[$setting_name] = array_merge( (array) $settings_array[$name], array( $content ) );

				// unload the original value
				unset( $settings_array[$name] );
			}

			$longname = $pp->processor_name . '_info_longname';
			$description = $pp->processor_name . '_info_description';

			$settingsparams = $pp->settings;

			$params = array();
			$params[$pp->processor_name.'_active'] = array( 'toggle', JText::_('PP_GENERAL_ACTIVE_NAME'), JText::_('PP_GENERAL_ACTIVE_DESC'), $pp->processor->active);

			if ( is_array( $settings_array ) && !empty( $settings_array ) ) {
				$params = array_merge( $params, $settings_array );
			}

			$params[$longname] = array( 'inputC', JText::_('CFG_PROCESSOR_NAME_NAME'), JText::_('CFG_PROCESSOR_NAME_DESC'), $pp->info['longname'], $longname);
			$params[$description] = array( 'editor', JText::_('CFG_PROCESSOR_DESC_NAME'), JText::_('CFG_PROCESSOR_DESC_DESC'), $pp->info['description'], $description);
		} else {
			$lists['processor']		= PaymentProcessorHandler::getSelectList();

			$params['processor']	= array( 'list' );

			$settingsparams = array();

			$pp = null;
		}

		$settings = new aecSettings ( 'pp', 'general' );
		$settings->fullSettingsArray( $params, $settingsparams, $lists ) ;

		// Call HTML Class
		$aecHTML = new aecHTML( $settings->settings, $settings->lists );
		if ( !empty( $customparamsarray ) ) {
			$aecHTML->customparams = $customparamsarray;
		}

		$aecHTML->pp = $pp;

		HTML_AcctExp::editProcessor( $aecHTML );
	}

	public function change( $cid=null, $state=0, $type )
	{
		if ( count( $cid ) < 1 ) {
			echo "<script> alert('" . JText::_('AEC_ALERT_SELECT_FIRST') . "'); window.history.go(-1);</script>\n";
			exit;
		}

		$total	= count( $cid );
		$cids	= implode( ',', $cid );

		$this->db->setQuery(
			'UPDATE #__acctexp_config_processors'
			. ' SET `' . $type . '` = \'' . $state . '\''
			. ' WHERE `id` IN (' . $cids . ')'
		);

		if ( !$this->db->query() ) {
			echo "<script> alert('".$this->db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		if ( $state ) {
			$msg = ( ( strcmp( $type, 'active' ) === 0 ) ? JText::_('AEC_CMN_PUBLISHED') : JText::_('AEC_CMN_MADE_VISIBLE') );
		} else {
			$msg = ( ( strcmp( $type, 'active' ) === 0 ) ? JText::_('AEC_CMN_NOT_PUBLISHED') : JText::_('AEC_CMN_MADE_INVISIBLE') );
		}

		$msg = sprintf( JText::_('AEC_MSG_ITEMS_SUCESSFULLY'), $total ) . ' ' . $msg;

		$this->setMessage($msg);
	}

	public function save( $return=0 )
	{
		$pp = new PaymentProcessor();

		if ( !empty( $_POST['id'] ) ) {
			$pp->loadId( $_POST['id'] );

			if ( empty( $pp->id ) ) {
				$this->cancel();
			}

			$procname = $pp->processor_name;
		} elseif ( isset( $_POST['processor'] ) ) {
			$pp->loadName( $_POST['processor'] );

			$procname = $_POST['processor'];
		}

		$pp->fullInit(true);

		$active			= $procname . '_active';
		$longname		= $procname . '_info_longname';
		$description	= $procname . '_info_description';

		if ( isset( $_POST[$longname] ) ) {
			$pp->info['longname'] = $_POST[$longname];
			unset( $_POST[$longname] );
		}

		if ( isset( $_POST[$description] ) ) {
			$pp->info['description'] = $_POST[$description];
			unset( $_POST[$description] );
		}

		if ( isset( $_POST[$active] ) ) {
			$pp->processor->active = $_POST[$active];
			unset( $_POST[$active] );
		}

		$settings = $pp->getBackendSettings();

		if ( is_int( $pp->is_recurring() ) ) {
			$settings['recurring'] = 2;
		}

		foreach ( $settings as $name => $value ) {
			if ( $name == 'lists' ) {
				continue;
			}

			$postname = $procname  . '_' . $name;

			if ( isset( $_POST[$postname] ) ) {
				$val = $_POST[$postname];

				if ( empty( $val ) ) {
					switch( $name ) {
						case 'currency':
							$val = 'USD';
							break;
						default:
							break;
					}
				}

				$pp->settings[$name] = $val;
			}
		}

		$pp->storeload();

		$this->setMessage( JText::_('AEC_CONFIG_SAVED') );
	}
}

class aecAdminSubscriptionPlan extends aecAdminEntity
{
	public $table = 'plans';

	public $init = array(
		'sort' => 'name ASC',
		'filter' => array(
			'group' => array()
		)
	);

	public function getList()
	{
		$rows = SubscriptionPlanHandler::getFullPlanList();

		$totals = array();
		$query = 'SELECT count(*)'
			. ' FROM #__acctexp_subscr'
			. ' WHERE (status = \'Active\' OR status = \'Trial\')'
			. ( empty( $subselect ) ? '' : ' AND plan IN (' . implode( ',', $subselect ) . ')' )
		;
		$this->db->setQuery( $query );

		$totals['active'] = $this->db->loadResult();
		if ( $this->db->getErrorNum() ) {
			echo $this->db->stderr();
			return false;
		}

		$query = 'SELECT count(*)'
			. ' FROM #__acctexp_subscr'
			. ' WHERE (status = \'Expired\')'
			. ( empty( $subselect ) ? '' : ' AND plan IN (' . implode( ',', $subselect ) . ')' )
		;
		$this->db->setQuery( $query );

		$totals['expired'] = $this->db->loadResult();
		if ( $this->db->getErrorNum() ) {
			echo $this->db->stderr();
			return false;
		}

		$gcolors = array();

		foreach ( $rows as $n => $row ) {
			$query = 'SELECT count(*)'
				. ' FROM #__acctexp_subscr'
				. ' WHERE plan = ' . $row->id
				. ' AND (status = \'Active\' OR status = \'Trial\')'
			;
			$this->db->setQuery( $query );

			$rows[$n]->usercount = $this->db->loadResult();
			if ( $this->db->getErrorNum() ) {
				echo $this->db->stderr();
				return false;
			}

			$query = 'SELECT count(*)'
				. ' FROM #__acctexp_subscr'
				. ' WHERE plan = ' . $row->id
				. ' AND (status = \'Expired\')'
			;
			$this->db->setQuery( $query );

			$rows[$n]->expiredcount = $this->db->loadResult();
			if ( $this->db->getErrorNum() ) {
				echo $this->db->stderr();
				return false;
			}

			$query = 'SELECT group_id'
				. ' FROM #__acctexp_itemxgroup'
				. ' WHERE type = \'item\''
				. ' AND item_id = \'' . $rows[$n]->id . '\''
			;
			$this->db->setQuery( $query	);
			$g = (int) $this->db->loadResult();

			$group = empty( $g ) ? 0 : $g;

			if ( !isset( $gcolors[$group] ) ) {
				$gcolors[$group] = array();
				$gcolors[$group]['color'] = ItemGroupHandler::groupColor( $group );
			}

			$rows[$n]->group = $group;
			$rows[$n]->color = $gcolors[$group]['color'];

			$rows[$n]->link = 'index.php?option=com_acctexp&amp;task=showSubscriptions&amp;plan='.$row->id.'&amp;groups[]=all';
			$rows[$n]->link_active = 'index.php?option=com_acctexp&amp;task=showSubscriptions&amp;plan='.$row->id.'&amp;groups[]=active';
			$rows[$n]->link_expired = 'index.php?option=com_acctexp&amp;task=showSubscriptions&amp;plan='.$row->id.'&amp;groups[]=expired';

			if ( $totals['expired'] ) {
				$rows[$n]->expired_percentage = $row->expiredcount / ( $totals['expired'] / 100 );
			} else {
				$rows[$n]->expired_percentage = 0;
			}

			$rows[$n]->expired_inner = false;
			if ( $rows[$n]->expired_percentage > 45 ) {
				$rows[$n]->expired_inner = true;
			}

			$row->activecount = $row->usercount;

			if ( $totals['active'] ) {
				$rows[$n]->active_percentage = $row->usercount / ( $totals['active'] / 100 );
			} else {
				$rows[$n]->active_percentage = 0;
			}

			$rows[$n]->active_inner = false;
			if ( $rows[$n]->active_percentage > 45 ) {
				$rows[$n]->active_inner = true;
			}

			$row->totalcount = $row->expiredcount+$row->usercount;

			if ( $totals['active']+$totals['expired'] ) {
				$rows[$n]->total_percentage = ($row->expiredcount+$row->usercount) / ( ($totals['active']+$totals['expired']) / 100 );
			} else {
				$rows[$n]->total_percentage = 0;
			}

			$rows[$n]->total_inner = false;
			if ( $rows[$n]->total_percentage > 20 ) {
				$rows[$n]->total_inner = true;
			}

			if ( !empty( $row->desc ) ) {
				$rows[$n]->desc = stripslashes( strip_tags( $row->desc ) );
				if ( strlen( $rows[$n]->desc ) > 50 ) {
					$rows[$n]->desc = substr( $rows[$n]->desc, 0, 50) . ' ...';
				}
			}
		}

		$ret = new stdClass();
		$ret->aaData = $rows;

		echo json_encode( $ret );exit;
	}

	public function index()
	{
		if ( !empty( $this->state->filter->group ) ) {
			$subselect = ItemGroupHandler::getChildren( $this->state->filter->group, 'item' );

			$this->addConstraint(
				'id IN (' . implode( ',', $subselect ) . ')'
			);
		} else {
			$subselect = array();
		}

		$nav = $this->getPagination();

		// get the subset (based on limits) of records
		$rows = SubscriptionPlanHandler::getFullPlanList(
			$nav->limitstart,
			$nav->limit,
			$subselect,
			$this->state->sort,
			$this->state->search
		);

		$gcolors = array();

		foreach ( $rows as $n => $row ) {
			$query = 'SELECT count(*)'
				. ' FROM #__acctexp_subscr'
				. ' WHERE plan = ' . $row->id
				. ' AND (status = \'Active\' OR status = \'Trial\')'
			;
			$this->db->setQuery( $query );

			$rows[$n]->usercount = $this->db->loadResult();
			if ( $this->db->getErrorNum() ) {
				echo $this->db->stderr();
				return false;
			}

			$query = 'SELECT count(*)'
				. ' FROM #__acctexp_subscr'
				. ' WHERE plan = ' . $row->id
				. ' AND (status = \'Expired\')'
			;
			$this->db->setQuery( $query );

			$rows[$n]->expiredcount = $this->db->loadResult();
			if ( $this->db->getErrorNum() ) {
				echo $this->db->stderr();
				return false;
			}

			$query = 'SELECT group_id'
				. ' FROM #__acctexp_itemxgroup'
				. ' WHERE type = \'item\''
				. ' AND item_id = \'' . $rows[$n]->id . '\''
			;
			$this->db->setQuery( $query );

			$groups = xJ::getDBArray( $this->db );

			$rows[$n]->groups = array();
			foreach ( $groups as $group ) {
				if ( empty($group) ) continue;

				if ( !isset( $gcolors[$group] ) ) {
					$gcolors[$group] = array();
					$gcolors[$group]['color'] = ItemGroupHandler::groupColor( $group );
				}

				$rows[$n]->groups[] = (object) array(
					'id' => $group,
					'color' => $gcolors[$group]['color']
				);
			}
		}

		$grouplist = ItemGroupHandler::getTree();

		$glist		= array();
		$sel_groups	= array();

		$glist[] = JHTML::_('select.option', 0, '- - - - - -' );

		if ( empty( $this->state->filter->group ) ) {
			$sel_groups[] = JHTML::_('select.option', 0, '- - - - - -' );
		}

		foreach ( $grouplist as $id => $glisti ) {
			if ( defined( 'JPATH_MANIFESTS' ) ) {
				$glist[] = JHTML::_('select.option', $glisti[0], str_replace( '&nbsp;', ' ', $glisti[1] ) );
			} else {
				$glist[] = JHTML::_('select.option', $glisti[0], $glisti[1] );
			}

			if ( !empty( $this->state->filter->group ) ) {
				if ( in_array( $glisti[0], $this->state->filter->group ) ) {
					$sel_groups[] = JHTML::_('select.option', $glisti[0], $glisti[1] );
				}
			}
		}

		$lists['filter_group'] = JHTML::_('select.genericlist', $glist, 'filter_group[]', 'size="4" multiple="multiple"', 'value', 'text', $sel_groups );

		$totals = array();
		$query = 'SELECT count(*)'
			. ' FROM #__acctexp_subscr'
			. ' WHERE (status = \'Active\' OR status = \'Trial\')'
			. ( empty( $subselect ) ? '' : ' AND plan IN (' . implode( ',', $subselect ) . ')' )
		;
		$this->db->setQuery( $query );

		$totals['active'] = $this->db->loadResult();
		if ( $this->db->getErrorNum() ) {
			echo $this->db->stderr();
			return false;
		}

		$query = 'SELECT count(*)'
			. ' FROM #__acctexp_subscr'
			. ' WHERE (status = \'Expired\')'
			. ( empty( $subselect ) ? '' : ' AND plan IN (' . implode( ',', $subselect ) . ')' )
		;
		$this->db->setQuery( $query );

		$totals['expired'] = $this->db->loadResult();
		if ( $this->db->getErrorNum() ) {
			echo $this->db->stderr();
			return false;
		}

		foreach ( $rows as $rid => $row ) {
			$rows[$rid]->link = 'index.php?option=com_acctexp&amp;task=showSubscriptions&amp;plan='.$row->id.'&amp;groups[]=all';
			$rows[$rid]->link_active = 'index.php?option=com_acctexp&amp;task=showSubscriptions&amp;plan='.$row->id.'&amp;groups[]=active';
			$rows[$rid]->link_expired = 'index.php?option=com_acctexp&amp;task=showSubscriptions&amp;plan='.$row->id.'&amp;groups[]=expired';

			if ( $totals['expired'] ) {
				$rows[$rid]->expired_percentage = $row->expiredcount / ( $totals['expired'] / 100 );
			} else {
				$rows[$rid]->expired_percentage = 0;
			}

			$rows[$rid]->expired_inner = false;
			if ( $rows[$rid]->expired_percentage > 45 ) {
				$rows[$rid]->expired_inner = true;
			}

			if ( $totals['active'] ) {
				$rows[$rid]->active_percentage = $row->usercount / ( $totals['active'] / 100 );
			} else {
				$rows[$rid]->active_percentage = 0;
			}

			$rows[$rid]->active_inner = false;
			if ( $rows[$rid]->active_percentage > 45 ) {
				$rows[$rid]->active_inner = true;
			}

			if ( $totals['active']+$totals['expired'] ) {
				$rows[$rid]->total_percentage = ($row->expiredcount+$row->usercount) / ( ($totals['active']+$totals['expired']) / 100 );
			} else {
				$rows[$rid]->total_percentage = 0;
			}

			$rows[$rid]->total_inner = false;
			if ( $rows[$rid]->total_percentage > 20 ) {
				$rows[$rid]->total_inner = true;
			}

			if ( !empty( $row->desc ) ) {
				$rows[$rid]->desc = stripslashes( strip_tags( $row->desc ) );
				if ( strlen( $rows[$rid]->desc ) > 50 ) {
					$rows[$rid]->desc = substr( $rows[$rid]->desc, 0, 50) . ' ...';
				}
			}
		}

		HTML_AcctExp::listSubscriptionPlans( $rows, $this->state, $lists, $nav );
	}

	public function edit( $id )
	{
		global $aecConfig;

		$lists = array();
		$params_values = array();
		$restrictions_values = array();
		$customparams_values = array();

		$customparamsarray = new stdClass();

		$row = new SubscriptionPlan();
		$row->load( $id );

		$restrictionHelper = new aecRestrictionHelper();

		if ( !$row->id ) {
			$row->ordering	= 9999;
			$hasrecusers	= false;

			$params_values['active'] = 1;
			$params_values['visible'] = 0;
			$params_values['processors'] = 0;

			$restrictions_values['gid_enabled']	= 1;
			if ( defined( 'JPATH_MANIFESTS' ) ) {
				$restrictions_values['gid'] = 2;
			} else {
				$restrictions_values['gid'] = 18;
			}
		} else {
			$params_values = $row->params;
			$restrictions_values = $row->restrictions;

			if ( empty( $restrictions_values ) ) {
				$restrictions_values = array();
			}

			// Clean up custom params
			if ( !empty( $row->customparams ) ) {
				foreach ( $row->customparams as $n => $v ) {
					if ( isset( $params_values[$n] ) || isset( $restrictions_values[$n] ) ) {
						unset( $row->customparams[$n] );
					}
				}
			}

			$customparams_values = $row->custom_params;

			// We need to convert the values that are set as object properties
			$params_values['active']				= $row->active;
			$params_values['visible']				= $row->visible;
			$params_values['email_desc']			= $row->getProperty( 'email_desc' );
			$params_values['name']					= $row->getProperty( 'name' );
			$params_values['desc']					= $row->getProperty( 'desc' );
			$params_values['micro_integrations']	= $row->micro_integrations;
			$params_values['processors']			= $row->params['processors'];

			// Checking if there is already a user, which disables certain actions
			$query  = 'SELECT count(*)'
				. ' FROM #__users AS a'
				. ' LEFT JOIN #__acctexp_subscr AS b ON a.id = b.userid'
				. ' WHERE b.plan = ' . $row->id
				. ' AND (b.status = \'Active\' OR b.status = \'Trial\')'
				. ' AND b.recurring =\'1\''
			;
			$this->db->setQuery( $query );
			$hasrecusers = ( $this->db->loadResult() > 0 ) ? true : false;
		}

		$stdformat = '{aecjson}{"cmd":"condition","vars":[{"cmd":"data","vars":"payment.freetrial"},'
			.'{"cmd":"concat","vars":[{"cmd":"jtext","vars":"CONFIRM_FREETRIAL"},"&nbsp;",{"cmd":"data","vars":"payment.method_name"}]},'
			.'{"cmd":"concat","vars":[{"cmd":"data","vars":"payment.amount"},{"cmd":"data","vars":"payment.currency_symbol"},"&nbsp;",{"cmd":"data","vars":"payment.method_name"}]}'
			.']}{/aecjson}'
		;

		// params and their type values
		$params['active']					= array( 'toggle', 1 );
		$params['visible']					= array( 'toggle', 1 );

		$params['name']						= array( 'inputC', '' );
		$params['desc']						= array( 'editor', '' );
		$params['customamountformat']		= array( 'inputD', $stdformat );
		$params['customthanks']				= array( 'inputC', '' );
		$params['customtext_thanks_keeporiginal']	= array( 'toggle', 1 );
		$params['customtext_thanks']		= array( 'editor', '' );
		$params['email_desc']				= array( 'inputD', '' );
		$params['meta']						= array( 'inputD', '' );
		$params['micro_integrations_inherited']		= array( 'list', '' );
		$params['micro_integrations']		= array( 'list', '' );
		$params['micro_integrations_plan']	= array( 'list', '' );

		$params['params_remap']				= array( 'subarea_change', 'groups' );

		$groups = ItemGroupHandler::parentGroups( $row->id, 'item' );

		if ( !empty( $groups ) ) {
			$gs = array();
			foreach ( $groups as $groupid ) {
				$group = new ItemGroup();
				$group->load( $groupid );

				$g = array();
				$g['id']	= $group->id;
				$g['name']	= $group->getProperty('name');
				$g['color']	= $group->params['color'];

				$g['group']	= '<strong>' . $groupid . '</strong>';

				$gs[$groupid] = $g;
			}


			$customparamsarray->groups = $gs;
		} else {
			$customparamsarray->groups = null;
		}

		$grouplist = ItemGroupHandler::getTree();

		$glist = array();

		$glist[] = JHTML::_('select.option', 0, '- - - - - -' );
		$groupids = array();
		foreach ( $grouplist as $id => $glisti ) {
			if ( defined( 'JPATH_MANIFESTS' ) ) {
				$glist[] = JHTML::_('select.option', $glisti[0], str_replace( '&nbsp;', ' ', $glisti[1] ), 'value', 'text', in_array($glisti[0], $groups) );
			} else {
				$glist[] = JHTML::_('select.option', $glisti[0], $glisti[1], 'value', 'text', in_array($glisti[0], $groups) );
			}

			$groupids[$glisti[0]] = ItemGroupHandler::groupColor( $glisti[0] );
		}

		$lists['add_group'] 			= JHTML::_('select.genericlist', $glist, 'add_group', 'size="1"', 'value', 'text', ( ( $row->id ) ? 0 : 1 ) );

		$params['add_group']			= array( 'list', '', '', ( ( $row->id ) ? 0 : 1 ) );

		$params['params_remap']			= array( 'subarea_change', 'params' );

		$params['override_activation']	= array( 'toggle', 0 );
		$params['override_regmail']		= array( 'toggle', 0 );

		$params['full_free']			= array( 'toggle', '' );
		$params['full_amount']			= array( 'inputA', '' );
		$params['full_period']			= array( 'inputA', '' );
		$params['full_periodunit']		= array( 'list', 'D' );
		$params['trial_free']			= array( 'toggle', '' );
		$params['trial_amount']			= array( 'inputA', '' );
		$params['trial_period']			= array( 'inputA', '' );
		$params['trial_periodunit']		= array( 'list', 'D' );

		$params['gid_enabled']			= array( 'toggle', 1 );
		$params['gid']					= array( 'list', ( defined( 'JPATH_MANIFESTS' ) ? 2 : 18 ) );
		$params['lifetime']				= array( 'toggle', 0 );
		$params['processors']			= array( 'list', '' );
		$params['processor_selectmode']	= array( 'list', '' );
		$params['standard_parent']		= array( 'list', '' );
		$params['fallback']				= array( 'list', '' );
		$params['fallback_req_parent']	= array( 'toggle', 0 );
		$params['make_active']			= array( 'toggle', 1 );
		$params['make_primary']			= array( 'toggle', 1 );
		$params['update_existing']		= array( 'toggle', 1 );

		$params['similarplans']			= array( 'list', '' );
		$params['equalplans']			= array( 'list', '' );

		$params['notauth_redirect']		= array( 'inputC', '' );
		$params['fixed_redirect']		= array( 'inputC', '' );
		$params['hide_duration_checkout']	= array( 'toggle', 0 );
		$params['cart_behavior']		= array( 'list', 0 );
		$params['addtocart_redirect']	= array( 'inputC', '' );
		$params['addtocart_max']		= array( 'inputA', '' );
		$params['notes']				= array( 'textarea', '' );

		$params['restr_remap']			= array( 'subarea_change', 'restrictions' );

		$params['inventory_amount_enabled']	= array( 'toggle', 0 );
		$params['inventory_amount']			= array( 'inputB', 0 );
		$params['inventory_amount_used']	= array( 'inputB', 0 );

		$params = array_merge( $params, $restrictionHelper->getParams() );

		$rewriteswitches				= array( 'cms', 'user' );
		$params['rewriteInfo']			= array( 'fieldset', '', AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		// make the select list for first trial period units
		$perunit[] = JHTML::_('select.option', 'D', JText::_('PAYPLAN_PERUNIT1') );
		$perunit[] = JHTML::_('select.option', 'W', JText::_('PAYPLAN_PERUNIT2') );
		$perunit[] = JHTML::_('select.option', 'M', JText::_('PAYPLAN_PERUNIT3') );
		$perunit[] = JHTML::_('select.option', 'Y', JText::_('PAYPLAN_PERUNIT4') );

		$lists['trial_periodunit'] = JHTML::_('select.genericlist', $perunit, 'trial_periodunit', 'size="4"', 'value', 'text', arrayValueDefault($params_values, 'trial_periodunit', "D") );
		$lists['full_periodunit'] = JHTML::_('select.genericlist', $perunit, 'full_periodunit', 'size="4"', 'value', 'text', arrayValueDefault($params_values, 'full_periodunit', "D") );

		// make the select list for first trial period units
		$selectmode[] = JHTML::_('select.option', '0', JText::_('PAYPLAN_PROCESSOR_SELECTMODE_LIST') );
		$selectmode[] = JHTML::_('select.option', '1', JText::_('PAYPLAN_PROCESSOR_SELECTMODE_CONFIRMATION') );
		$selectmode[] = JHTML::_('select.option', '2', JText::_('PAYPLAN_PROCESSOR_SELECTMODE_BOTH') );

		$lists['processor_selectmode'] = JHTML::_('select.genericlist', $selectmode, 'processor_selectmode', 'size="3"', 'value', 'text', arrayValueDefault($params_values, 'processor_selectmode', "0") );

		$params['processors_remap'] = array("subarea_change", "plan_params");

		$pps = PaymentProcessorHandler::getInstalledObjectList( 1 );

		if ( empty( $params_values['processors'] ) ) {
			$plan_procs = array();
		} else {
			$plan_procs = $params_values['processors'];
		}

		if ( !empty($plan_procs) ) {
			foreach ( $plan_procs as $proc ) {
				foreach ( $pps as $pk => $ppo ) {
					if ( $ppo->id == $proc ) {
						$pps[] = $ppo;
						unset($pps[$pk]);
					}
				}
			}
		}

		$custompar = array();
		foreach ( $pps as $ppobj ) {
			if ( !$ppobj->active ) {
				continue;
			}

			$pp = new PaymentProcessor();

			if ( !$pp->loadName( $ppobj->name ) ) {
				continue;
			}

			$pp->init();
			$pp->getInfo();

			$custompar[$pp->id] = array();
			$custompar[$pp->id]['handle'] = $ppobj->name;
			$custompar[$pp->id]['name'] = $pp->info['longname'];
			$custompar[$pp->id]['params'] = array();

			$params['processor_' . $pp->id] = array( 'toggle', JText::_('PAYPLAN_PROCESSORS_ACTIVATE_NAME'), JText::_('PAYPLAN_PROCESSORS_ACTIVATE_DESC')  );
			$custompar[$pp->id]['params'][] = 'processor_' . $pp->id;

			$params[$pp->id . '_aec_overwrite_settings'] = array( 'toggle', JText::_('PAYPLAN_PROCESSORS_OVERWRITE_SETTINGS_NAME'), JText::_('PAYPLAN_PROCESSORS_OVERWRITE_SETTINGS_DESC') );
			$custompar[$pp->id]['params'][] = $pp->id . '_aec_overwrite_settings';

			$customparams = $pp->getCustomPlanParams();

			if ( is_array( $customparams ) ) {
				foreach ( $customparams as $customparam => $cpcontent ) {
					$naming = array( $pp->getParamLang( $customparam . '_NAME' ), $pp->getParamLang( $customparam . '_DESC' ) );

					$shortname = $pp->id . "_" . $customparam;
					$params[$shortname] = array_merge( $cpcontent, $naming );
					$custompar[$pp->id]['params'][] = $shortname;
				}
			}

			if ( empty( $plan_procs ) ) {
				continue;
			}

			if ( !in_array( $pp->id, $plan_procs ) ) {
				continue;
			}

			$params_values['processor_' . $pp->id] = 1;

			if ( isset( $customparams_values[$pp->id . '_aec_overwrite_settings'] ) ) {
				if ( !$customparams_values[$pp->id . '_aec_overwrite_settings'] ) {
					continue;
				}
			} else {
				continue;
			}

			$settings_array = $pp->getBackendSettings();

			if ( isset( $settings_array['lists'] ) ) {
				foreach ( $settings_array['lists'] as $listname => $listcontent ) {
					$lists[$pp->id . '_' . $listname] = $listcontent;
				}

				unset( $settings_array['lists'] );
			}

			// Iterate through settings form to...
			foreach ( $settings_array as $name => $values ) {
				$setting_name = $pp->id . '_' . $name;

				if ( isset( $params[$setting_name] ) ) {
					continue;
				}

				if ( isset( $customparams_values[$setting_name] ) ) {
					$value = $customparams_values[$setting_name];
				} elseif ( isset( $pp->settings[$name] ) ) {
					$value = $pp->settings[$name];
				} else {
					$value = '';
				}

				// ...assign new list fields
				switch( $settings_array[$name][0] ) {
					case 'list_yesno':
						$arr = array(
							JHTML::_('select.option', 0, JText::_( 'no' ) ),
							JHTML::_('select.option', 1, JText::_( 'yes' ) ),
						);

						$lists[$setting_name] = JHTML::_('select.genericlist', $arr, $setting_name, '', 'value', 'text', (int) $value );

						$settings_array[$name][0] = 'list';
						break;

					case 'list_currency':
						// Get currency list
						$currency_array	= explode( ',', $pp->info['currencies'] );

						// Transform currencies into OptionArray
						$currency_code_list = array();
						foreach ( $currency_array as $currency ) {
							if ( $this->lang->hasKey( 'CURRENCY_' . $currency )) {
								$currency_code_list[] = JHTML::_('select.option', $currency, JText::_( 'CURRENCY_' . $currency ) );
							}
						}

						// Create list
						$lists[$setting_name] = JHTML::_('select.genericlist', $currency_code_list, $setting_name, 'size="10"', 'value', 'text', $value );
						$settings_array[$name][0] = 'list';
						break;

					case 'list_language':
						// Get language list
						if ( !is_array( $pp->info['languages'] ) ) {
							$this->language_array	= explode( ',', $pp->info['languages'] );
						} else {
							$this->language_array	= $pp->info['languages'];
						}

						// Transform languages into OptionArray
						$this->language_code_list = array();
						foreach ( $this->language_array as $this->language ) {
							$this->language_code_list[] = JHTML::_('select.option', $this->language, ( $this->lang->hasKey( 'LANGUAGECODE_' . $this->language  ) ? JText::_( 'LANGUAGECODE_' . $this->language ) : $this->language ) );
						}
						// Create list
						$lists[$setting_name] = JHTML::_('select.genericlist', $this->language_code_list, $setting_name, 'size="10"', 'value', 'text', $value );
						$settings_array[$name][0] = 'list';
						break;

					case 'list_plan':
						unset( $settings_array[$name] );
						break;

					default:
						break;
				}

				// ...put in missing language fields
				if ( !isset( $settings_array[$name][1] ) ) {
					$settings_array[$name][1] = $pp->getParamLang( $name . '_NAME' );
					$settings_array[$name][2] = $pp->getParamLang( $name . '_DESC' );
				}

				$params[$setting_name] = $settings_array[$name];
				$custompar[$pp->id]['params'][] = $setting_name;
			}
		}

		$customparamsarray->pp = $custompar;

		// get available active plans
		$fallback_plans = array( JHTML::_('select.option', '0', JText::_('PAYPLAN_NOFALLBACKPLAN') ) );
		$parent_plans = array( JHTML::_('select.option', '0', JText::_('PAYPLAN_NOPARENTPLAN') ) );

		$query = 'SELECT `id` AS value, `name` AS text'
			. ' FROM #__acctexp_plans'
			. ' WHERE `active` = 1'
			. ' AND `id` != \'' . $row->id . '\'';
		;
		$this->db->setQuery( $query );
		$payment_plans = $this->db->loadObjectList();

		if ( is_array( $payment_plans ) ) {
			$fallback_plans	= array_merge( $fallback_plans, $payment_plans );
			$parent_plans	= array_merge( $parent_plans, $payment_plans );
		}

		$lists['fallback'] = JHTML::_('select.genericlist', $fallback_plans, 'fallback', 'size="1"', 'value', 'text', arrayValueDefault($params_values, 'fallback', 0));
		$lists['standard_parent'] = JHTML::_('select.genericlist', $parent_plans, 'standard_parent', 'size="1"', 'value', 'text', arrayValueDefault($params_values, 'standard_parent', 0));

		// get similar plans
		if ( !empty( $params_values['similarplans'] ) ) {
			$query = 'SELECT `id` AS value, `name` As text'
				. ' FROM #__acctexp_plans'
				. ' WHERE `id` IN (' . implode( ',', $params_values['similarplans'] ) .')'
			;
			$this->db->setQuery( $query );

			$sel_similar_plans = $this->db->loadObjectList();
		} else {
			$sel_similar_plans = 0;
		}

		$lists['similarplans'] = JHTML::_('select.genericlist', $payment_plans, 'similarplans[]', 'size="1" multiple="multiple"', 'value', 'text', $sel_similar_plans);

		// get equal plans
		if ( !empty( $params_values['equalplans'] ) ) {
			$query = 'SELECT `id` AS value, `name` AS text'
				. ' FROM #__acctexp_plans'
				. ' WHERE `id` IN (' . implode( ',', $params_values['equalplans'] ) .')'
			;
			$this->db->setQuery( $query );

			$sel_equal_plans = $this->db->loadObjectList();
		} else {
			$sel_equal_plans = 0;
		}

		$lists['equalplans'] = JHTML::_('select.genericlist', $payment_plans, 'equalplans[]', 'size="1" multiple="multiple"', 'value', 'text', $sel_equal_plans);

		$lists = array_merge( $lists, $restrictionHelper->getLists( $params_values, $restrictions_values ) );

		// make the select list for first trial period units
		$cartmode[] = JHTML::_('select.option', '0', JText::_('PAYPLAN_CARTMODE_INHERIT') );
		$cartmode[] = JHTML::_('select.option', '1', JText::_('PAYPLAN_CARTMODE_FORCE_CART') );
		$cartmode[] = JHTML::_('select.option', '2', JText::_('PAYPLAN_CARTMODE_FORCE_DIRECT') );

		$lists['cart_behavior'] = JHTML::_('select.genericlist', $cartmode, 'cart_behavior', 'size="1"', 'value', 'text', arrayValueDefault($params_values, 'cart_behavior', "0") );

		$mi_list = microIntegrationHandler::getDetailedList();

		$mi_settings = array( 'inherited' => array(), 'attached' => array(), 'custom' => array() );

		$attached_mis = $row->getMicroIntegrationsSeparate( true );

		foreach ( $mi_list as $mi_details ) {
			$mi_details->inherited = false;
			if ( !empty($attached_mis['inherited']) && in_array( $mi_details->id, $attached_mis['inherited'] ) ) {
				$mi_details->inherited = true;

				$mi_settings['inherited'][] = $mi_details;
			}

			$mi_details->attached = false;
			if ( !empty($attached_mis['plan']) && in_array( $mi_details->id, $attached_mis['plan'] ) ) {
				$mi_details->attached = true;
			}

			$mi_settings['attached'][] = $mi_details;
		}

		$mi_handler = new microIntegrationHandler();
		$mi_list = $mi_handler->getIntegrationList();

		$mi_htmllist = array();
		$mi_htmllist[]	= JHTML::_('select.option', '', JText::_('AEC_CMN_NONE_SELECTED') );

		foreach ( $mi_list as $name ) {
			$mi = new microIntegration();
			$mi->class_name = 'mi_'.$name;
			if ( $mi->callIntegration() ){
				$len = 30 - AECToolbox::visualstrlen( trim( $mi->name ) );
				$fullname = str_replace( '#', '&nbsp;', str_pad( $mi->name, $len, '#' ) ) . ' - ' . substr($mi->desc, 0, 120);
				$mi_htmllist[] = JHTML::_('select.option', $name, $fullname );
			}
		}

		if ( !empty( $row->micro_integrations ) && is_array( $row->micro_integrations ) ) {
			$query = 'SELECT `id`'
				. ' FROM #__acctexp_microintegrations'
				. ' WHERE `id` IN (' . implode( ',', $row->micro_integrations ) . ')'
				. ' AND `hidden` = \'1\''
			;
			$this->db->setQuery( $query );
			$hidden_mi = $this->db->loadObjectList();
		} else {
			$hidden_mi = array();
		}

		$customparamsarray->hasperplanmi = false;

		if ( !empty( $aecConfig->cfg['per_plan_mis'] ) || !empty( $hidden_mi ) ) {
			$customparamsarray->hasperplanmi = true;

			$lists['micro_integrations_plan'] = JHTML::_('select.genericlist', $mi_htmllist, 'micro_integrations_plan[]', 'size="' . min( ( count( $mi_list ) + 1 ), 25 ) . '" multiple="multiple"', 'value', 'text', array() );

			$custompar = array();

			$hidden_mi_list = array();
			if ( !empty( $hidden_mi ) ) {
				foreach ( $hidden_mi as $miobj ) {
					$hidden_mi_list[] = $miobj->id;
				}
			}

			$params['micro_integrations_hidden']		= array( 'hidden', '' );
			$params_values['micro_integrations_hidden']		= $hidden_mi_list;

			if ( !empty( $hidden_mi ) ) {
				foreach ( $hidden_mi as $miobj ) {
					$mi = new microIntegration();

					if ( !$mi->load( $miobj->id ) ) {
						continue;
					}

					if ( !$mi->callIntegration( 1 ) ) {
						continue;
					}

					$custompar[$mi->id] = array();
					$custompar[$mi->id]['name'] = $mi->name;
					$custompar[$mi->id]['params'] = array();

					$prefix = 'MI_' . $mi->id . '_';

					$params[] = array( 'area_change', 'MI' );
					$params[] = array( 'subarea_change', 'E' );
					$params[] = array( 'add_prefix', $prefix );
					$params[] = array( 'section_paper', JText::_('MI_E_TITLE') );

					$generalsettings = $mi->getGeneralSettings();

					foreach ( $generalsettings as $name => $value ) {
						$params[$prefix . $name] = $value;
						$custompar[$mi->id]['params'][] = $prefix . $name;

						if ( isset( $mi->$name ) ) {
							$params_values[$prefix.$name] = $mi->$name;
						} else {
							$params_values[$prefix.$name] = '';
						}
					}

					$params[]	= array( 'section_end', 0 );

					$misettings = $mi->getSettings();

					if ( isset( $misettings['lists'] ) ) {
						foreach ( $misettings['lists'] as $listname => $listcontent ) {
							$lists[$prefix . $listname] = str_replace( 'name="', 'name="'.$prefix, $listcontent );
						}

						unset( $misettings['lists'] );
					}

					$params[] = array( 'area_change', 'MI' );
					$params[] = array( 'subarea_change', $mi->class_name );
					$params[] = array( 'add_prefix', $prefix );
					$params[] = array( 'section_paper', JText::_('MI_E_SETTINGS') );

					foreach ( $misettings as $name => $value ) {
						$params[$prefix . $name] = $value;
						$custompar[$mi->id]['params'][] = $prefix . $name;
					}

					$params[]	= array( 'section_end', 0 );
				}
			}

			if ( !empty( $custompar ) ) {
				$mi_settings['custom'] = $custompar;
			}
		}

		$customparamsarray->mi = $mi_settings;

		$settings = new aecSettings( 'payplan', 'general' );

		if ( is_array( $customparams_values ) ) {
			$settingsparams = array_merge( $params_values, $customparams_values, $restrictions_values );
		} else {
			$settingsparams = array_merge( $params_values, $restrictions_values );
		}

		$settings->fullSettingsArray( $params, $settingsparams, $lists ) ;

		// Call HTML Class
		$aecHTML = new aecHTML( $settings->settings, $settings->lists );

		if ( !empty( $customparamsarray ) ) {
			$aecHTML->customparams = $customparamsarray;
		}

		HTML_AcctExp::editSubscriptionPlan( $aecHTML, $row, $hasrecusers );
	}

	public function save()
	{
		$row = new SubscriptionPlan();
		$row->load( $_POST['id'] );

		$post = AECToolbox::cleanPOST( $_POST, false );

		$row->savePOSTsettings( $post );

		$row->storeload();

		if ( $_POST['id'] ) {
			$id = $_POST['id'];
		} else {
			$id = $row->getMax();
		}

		if ( empty( $_POST['id'] ) ) {
			ItemGroupHandler::setChildren( 1, array( $id ), 'item' );
		}

		if ( !empty( $row->params['lifetime'] ) && !empty( $row->params['full_period'] ) ) {
			$this->log(
				"Plan Warning",
				"You have selected a regular period for a plan that"
				. " already has the 'lifetime' (i.e. 'non expiring') flag set."
				. " The period you have set will be overridden by"
				. " that setting.",
				'settings,plan'
			);
		}

		$terms = $row->getTerms();

		if ( !$terms->checkFree() && empty( $row->params['processors'] ) ) {
			$this->log(
				"Plan Warning",
				"You have set a plan to be non-free, yet did not select a payment processor."
				. " Without a processor assigned, the plan will not show up on the frontend.",
				'settings,plan'
			);
		}

		if ( !empty( $row->params['lifetime'] ) && !empty( $row->params['processors'] ) ) {
			$fcount	= 0;
			$found	= 0;

			foreach ( $row->params['processors'] as $procid ) {
				$fcount++;

				if ( isset( $row->custom_params[$procid.'_recurring'] ) ) {
					if ( ( 0 < $row->custom_params[$procid.'_recurring'] ) && ( $row->custom_params[$procid.'_recurring'] < 2 ) ) {
						$found++;
					} elseif ( $row->custom_params[$procid.'_recurring'] == 2 ) {
						$fcount++;
					}
				} else {
					$pp = new PaymentProcessor();
					if ( ( 0 < $pp->is_recurring() ) && ( $pp->is_recurring() < 2 ) ) {
						$found++;
					} elseif ( $pp->is_recurring() == 2 ) {
						$fcount++;
					}
				}
			}

			if ( $found ) {
				if ( ( $found < $fcount ) && ( $fcount > 1 ) ) {
					$event	= "You have selected one or more processors that only support recurring payments"
						. ", yet the plan is set to a lifetime period."
						. " This is not possible and the processors will not be displayed as options.";
				} else {
					$event	= "You have selected a processor that only supports recurring payments"
						. ", yet the plan is set to a lifetime period."
						. " This is not possible and the plan will not be displayed.";
				}

				$short	= "Plan Warning";
				$tags	= 'settings,plan';
				$params = array();

				$eventlog = new eventLog();
				$eventlog->issue( $short, $tags, $event, 32, $params );
			}
		}

		$this->setMessage( JText::_('AEC_MSG_SUCESSFULLY_SAVED') );
	}

	public function remove( $id )
	{
		$ids = implode( ',', $id );

		$query = 'SELECT count(*)'
			. ' FROM #__acctexp_plans'
			. ' WHERE `id` IN (' . $ids . ')'
		;
		$this->db->setQuery( $query );
		$total = $this->db->loadResult();

		if ( $total == 0 ) {
			echo "<script> alert('" . html_entity_decode( JText::_('AEC_MSG_NO_ITEMS_TO_DELETE') ) . "'); window.history.go(-1);</script>\n";
			exit;
		}

		foreach ( $id as $planid ) {
			if ( SubscriptionPlanHandler::getPlanUserCount( $planid ) > 0 ) {
				$msg = JText::_('AEC_MSG_NO_DEL_W_ACTIVE_SUBSCRIBER');

				aecRedirect( 'index.php?option=com_acctexp&task=showSubscriptionPlans', $msg );
			} else {
				$plan = new SubscriptionPlan();
				$plan->load( $planid );

				$plan->delete();
			}
		}

		$msg = $total . ' ' . JText::_('AEC_MSG_ITEMS_DELETED');

		aecRedirect( 'index.php?option=com_acctexp&task=showSubscriptionPlans', $msg );
	}

	public function change( $cid=null, $state=0, $type )
	{
		if ( count( $cid ) < 1 ) {
			echo "<script> alert('" . JText::_('AEC_ALERT_SELECT_FIRST') . "'); window.history.go(-1);</script>\n";
			exit;
		}

		$total	= count( $cid );
		$cids	= implode( ',', $cid );

		$query = 'UPDATE #__acctexp_plans'
			. ' SET `' . $type . '` = \'' . $state . '\''
			. ' WHERE `id` IN (' . $cids . ')'
		;
		$this->db->setQuery( $query );

		if ( !$this->db->query() ) {
			echo "<script> alert('".$this->db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		if ( $state ) {
			$msg = ( ( strcmp( $type, 'active' ) === 0 ) ? JText::_('AEC_CMN_PUBLISHED') : JText::_('AEC_CMN_MADE_VISIBLE') );
		} else {
			$msg = ( ( strcmp( $type, 'active' ) === 0 ) ? JText::_('AEC_CMN_NOT_PUBLISHED') : JText::_('AEC_CMN_MADE_INVISIBLE') );
		}

		$msg = sprintf( JText::_('AEC_MSG_ITEMS_SUCESSFULLY'), $total ) . ' ' . $msg;

		aecRedirect( 'index.php?option=com_acctexp&task=showSubscriptionPlans', $msg );
	}
}

class aecAdminItemGroup extends aecAdminEntity
{
	public $searchable = array('name');

	public $table = 'itemgroups';

	public function index()
	{
		$rows = $this->getRows('ItemGroup');

		$gcolors = array();

		foreach ( $rows as $rid => $row ) {
			$query = 'SELECT count(*)'
				. 'FROM #__users AS a'
				. ' LEFT JOIN #__acctexp_subscr AS b ON a.id = b.userid'
				. ' WHERE b.plan = ' . $row->id
				. ' AND (b.status = \'Active\' OR b.status = \'Trial\')'
			;
			$this->db->setQuery( $query );

			$rows[$rid]->usercount = $this->db->loadResult();
			if ( $this->db->getErrorNum() ) {
				echo $this->db->stderr();
				return false;
			}

			$query = 'SELECT count(*)'
				. ' FROM #__users AS a'
				. ' LEFT JOIN #__acctexp_subscr AS b ON a.id = b.userid'
				. ' WHERE b.plan = ' . $row->id
				. ' AND (b.status = \'Expired\')'
			;
			$this->db->setQuery( $query	);

			$rows[$rid]->expiredcount = $this->db->loadResult();
			if ( $this->db->getErrorNum() ) {
				echo $this->db->stderr();
				return false;
			}

			$gid = $rows[$rid]->id;

			if ( !isset( $gcolors[$gid] ) ) {
				$gcolors[$gid] = array();
				$gcolors[$gid]['color'] = ItemGroupHandler::groupColor( $gid );
			}

			$rows[$rid]->color = $gcolors[$gid]['color'];

			$parents = ItemGroupHandler::getParents( $gid, 'group' );

			$rows[$rid]->parent_groups = array();

			if ( !empty( $parents ) ) {
				foreach ( $parents as $parent ) {
					if ( !isset( $gcolors[$parent] ) ) {
						$gcolors[$parent] = array();
						$gcolors[$parent]['color'] = ItemGroupHandler::groupColor( $parent );
					}

					$rows[$rid]->parent_groups[] = (object) array(
						'id' => $parent,
						'color' => $gcolors[$parent]['color']
					);
				}
			} else {
				$rows[$rid]->parent_groups[] = (object) array(
					'id' => $gid,
					'color' => $gcolors[$gid]['color']
				);
			}

			if ( !empty( $row->desc ) ) {
				$rows[$rid]->desc = stripslashes( strip_tags( $row->desc ) );
				if ( strlen( $rows[$rid]->desc ) > 50 ) {
					$rows[$rid]->desc = substr( $rows[$rid]->desc, 0, 50) . ' ...';
				}
			}
		}

		HTML_AcctExp::listItemGroups( $rows, $this->getPagination(), $this->state );
	}

	public function edit( $id )
	{
		$lists = array();
		$params_values = array();
		$restrictions_values = array();
		$customparams_values = array();

		$row = new ItemGroup();
		$row->load( $id );

		$restrictionHelper = new aecRestrictionHelper();

		if ( !$row->id ) {
			$row->ordering	= 9999;

			$params_values['active']	= 1;
			$params_values['visible']	= 0;

			$restrictions_values['gid_enabled']	= 1;
			$restrictions_values['gid']			= 18;
		} else {
			$params_values = $row->params;
			$restrictions_values = $row->restrictions;
			$customparams_values = $row->custom_params;

			// We need to convert the values that are set as object properties
			$params_values['active']				= $row->active;
			$params_values['visible']				= $row->visible;
			$params_values['name']					= $row->getProperty( 'name' );
			$params_values['desc']					= $row->getProperty( 'desc' );
		}

		// params and their type values
		$params['active']					= array( 'toggle', 1 );
		$params['visible']					= array( 'toggle', 0 );

		$params['name']						= array( 'inputC', '' );
		$params['desc']						= array( 'editor', '' );

		$params['color']					= array( 'list', '' );

		$params['reveal_child_items']		= array( 'toggle', 0 );
		$params['symlink']					= array( 'inputC', '' );
		$params['symlink_userid']			= array( 'toggle', 0 );

		$params['notauth_redirect']			= array( 'inputD', '' );

		$params['micro_integrations']		= array( 'list', '' );
		$params['meta']						= array( 'inputD', '' );

		$params['params_remap']				= array( 'subarea_change', 'groups' );

		$groups = ItemGroupHandler::parentGroups( $row->id, 'group' );

		$customparamsarray = new stdClass();
		if ( !empty( $groups ) ) {
			$gs = array();
			foreach ( $groups as $groupid ) {
				$params['group_delete_'.$groupid] = array( 'checkbox' );

				$group = new ItemGroup();
				$group->load( $groupid );

				$g = array();
				$g['id']	= $group->id;
				$g['name']	= $group->getProperty('name');
				$g['color']	= $group->params['color'];

				$g['group']	= '<strong>' . $groupid . '</strong>';

				$gs[$groupid] = $g;
			}


			$customparamsarray->groups = $gs;
		} else {
			$customparamsarray->groups = null;
		}

		$grouplist = ItemGroupHandler::getTree();

		$glist = array();

		$glist[] = JHTML::_('select.option', 0, '- - - - - -' );
		$groupids = array();
		foreach ( $grouplist as $gid => $glisti ) {
			$children = ItemGroupHandler::getParents( $glisti[0], 'group' );

			$disabled = in_array( $id, $children );

			if ( $id ) {
				$self = ( $glisti[0] == $id );
				$existing = in_array( $glisti[0], $groups );

				$disabled = ( $disabled || $self || $existing );
			}

			if ( defined( 'JPATH_MANIFESTS' ) ) {
				$glist[] = JHTML::_('select.option', $glisti[0], str_replace( '&nbsp;', ' ', $glisti[1] ), 'value', 'text', $disabled );
			} else {
				$glist[] = JHTML::_('select.option', $glisti[0], $glisti[1], 'value', 'text', $disabled );
			}

			$groupids[$glisti[0]] = ItemGroupHandler::groupColor( $glisti[0] );
		}

		$lists['add_group'] 			= JHTML::_('select.genericlist', $glist, 'add_group', 'size="1"', 'value', 'text', ( ( $row->id ) ? 0 : 1 ) );

		foreach ( $groupids as $groupid => $groupcolor ) {
			$lists['add_group'] = str_replace( 'value="'.$groupid.'"', 'value="'.$groupid.'" style="background-color: #'.$groupcolor.' !important;"', $lists['add_group'] );
		}

		$params['add_group']	= array( 'list', '', '', ( ( $row->id ) ? 0 : 1 ) );

		$params['restr_remap']	= array( 'subarea_change', 'restrictions' );

		$params = array_merge( $params, $restrictionHelper->getParams() );

		$rewriteswitches		= array( 'cms', 'user' );
		$params['rewriteInfo']	= array( 'fieldset', '', AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		$colors = array(	'3182bd', '6baed6', '9ecae1', 'c6dbef', 'e6550d', 'fd8d3c', 'fdae6b', 'fdd0a2',
			'31a354', '74c476', 'a1d99b', 'c7e9c0', '756bb1', '9e9ac8', 'bcbddc', 'dadaeb',
			'636363', '969696', 'bdbdbd', 'd9d9d9',
			'1f77b4', 'aec7e8', 'ff7f0e', 'ffbb78', '2ca02c', '98df8a', 'd62728', 'ff9896',
			'9467bd', 'c5b0d5', '8c564b', 'c49c94', 'e377c2', 'f7b6d2', '7f7f7f', 'c7c7c7',
			'bcbd22', 'dbdb8d', '17becf', '9edae5', 'BBDDFF', '5F8BC4', 'A2BE72', 'DDFF99',
			'D07C30', 'C43C42', 'AA89BB', 'B7B7B7' );

		$colorlist = array();
		foreach ( $colors as $color ) {
			$obj = new stdClass;
			$obj->value = '#'.$color;
			$obj->text = $color;

			$colorlist[] = $obj;
		}

		$lists['color'] = JHTML::_('select.genericlist', $colorlist, 'color', 'size="1"', 'value', 'text', '#'.arrayValueDefault($params_values, 'color', 'BBDDFF'));

		$mi_list = microIntegrationHandler::getDetailedList();

		$mi_settings = array( 'inherited' => array(), 'attached' => array(), 'custom' => array() );

		$attached_mis = $row->getMicroIntegrationsSeparate( true );

		foreach ( $mi_list as $mi_details ) {
			$mi_details->inherited = false;
			if ( in_array( $mi_details->id, $attached_mis['inherited'] ) ) {
				$mi_details->inherited = true;

				$mi_settings['inherited'][] = $mi_details;
			}

			$mi_details->attached = false;
			if ( in_array( $mi_details->id, $attached_mis['group'] ) ) {
				$mi_details->attached = true;
			}

			$mi_settings['attached'][] = $mi_details;
		}

		$customparamsarray->mi = $mi_settings;

		$settings = new aecSettings ( 'itemgroup', 'general' );
		if ( is_array( $customparams_values ) ) {
			$settingsparams = array_merge( $params_values, $customparams_values, $restrictions_values );
		} elseif( is_array( $restrictions_values ) ){
			$settingsparams = array_merge( $params_values, $restrictions_values );
		}
		else {
			$settingsparams = $params_values;
		}

		$lists = array_merge( $lists, $restrictionHelper->getLists( $params_values, $restrictions_values ) );

		$settings->fullSettingsArray( $params, $settingsparams, $lists ) ;

		// Call HTML Class
		$aecHTML = new aecHTML( $settings->settings, $settings->lists );
		if ( !empty( $customparamsarray ) ) {
			$aecHTML->customparams = $customparamsarray;
		}

		HTML_AcctExp::editItemGroup( $aecHTML, $row );
	}

	public function save()
	{
		$row = new ItemGroup();
		$row->load( $_POST['id'] );

		$post = AECToolbox::cleanPOST( $_POST, false );

		$row->savePOSTsettings( $post );

		if ( !$row->check() ) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-2); </script>\n";
			exit();
		}
		if ( !$row->store() ) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-2); </script>\n";
			exit();
		}

		$row->reorder();

		if ( $_POST['id'] ) {
			$id = $_POST['id'];
		} else {
			$id = $row->getMax();
		}

		if ( empty( $_POST['id'] ) ) {
			ItemGroupHandler::setChildren( 1, array( $id ), 'group' );
		}

		$this->setMessage( JText::_('AEC_MSG_SUCESSFULLY_SAVED') );
	}

	public function remove( $id )
	{
		$ids = implode( ',', $id );

		$this->db->setQuery(
			'SELECT count(*)'
			. ' FROM #__acctexp_itemgroups'
			. ' WHERE `id` IN (' . $ids . ')'
		);
		$total = $this->db->loadResult();

		if ( $total == 0 ) {
			echo "<script> alert('" . html_entity_decode( JText::_('AEC_MSG_NO_ITEMS_TO_DELETE') ) . "'); window.history.go(-1);</script>\n";
			exit;
		}

		$total = 0;

		foreach ( $id as $i ) {
			$ig = new ItemGroup();
			$ig->load( $i );

			if ( $ig->delete() !== false ) {
				ItemGroupHandler::removeChildren( $i, false, 'group' );

				$total++;
			}
		}

		if ( $total == 0 ) {
			echo "<script> alert('" . html_entity_decode( JText::_('AEC_MSG_NO_ITEMS_TO_DELETE') ) . "'); window.history.go(-1);</script>\n";
			exit;
		} else {
			$msg = $total . ' ' . JText::_('AEC_MSG_ITEMS_DELETED');

			aecRedirect( 'index.php?option=com_acctexp&task=showItemGroups', $msg );
		}
	}

	public function change( $cid=null, $state=0, $type )
	{
		if ( count( $cid ) < 1 ) {
			echo "<script> alert('" . JText::_('AEC_ALERT_SELECT_FIRST') . "'); window.history.go(-1);</script>\n";
			exit;
		}

		$total	= count( $cid );
		$cids	= implode( ',', $cid );

		$query = 'UPDATE #__acctexp_itemgroups'
			. ' SET `' . $type . '` = \'' . $state . '\''
			. ' WHERE `id` IN (' . $cids . ')'
		;
		$this->db->setQuery( $query );

		if ( !$this->db->query() ) {
			echo "<script> alert('".$this->db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		if ( $state ) {
			$msg = ( ( strcmp( $type, 'active' ) === 0 ) ? JText::_('AEC_CMN_PUBLISHED') : JText::_('AEC_CMN_MADE_VISIBLE') );
		} else {
			$msg = ( ( strcmp( $type, 'active' ) === 0 ) ? JText::_('AEC_CMN_NOT_PUBLISHED') : JText::_('AEC_CMN_MADE_INVISIBLE') );
		}

		$msg = sprintf( JText::_('AEC_MSG_ITEMS_SUCESSFULLY'), $total ) . ' ' . $msg;

		aecRedirect( 'index.php?option=com_acctexp&task=showItemGroups', $msg );
	}

}

class aecAdminMicroIntegration extends aecAdminEntity
{
	public $table = 'microintegrations';

	public $searchable = array('name', 'desc', 'class_name');

	public $init = array(
		'filter' => array(
			'plan' => array()
		)
	);

	public function index()
	{
		if ( isset( $this->state->filter->plan ) && $this->state->filter->plan > 0 ) {
			$mis = microIntegrationHandler::getMIsbyPlan( $this->state->filter->plan );

			if ( !empty( $mis ) ) {
				$this->addConstraint(
					"id IN (" . implode( ',', $mis ) . ")"
				);
			} else {
				$this->state->filter->plan = "";
			}
		}

		$rows = $this->getRows();

		foreach ( $rows as $rid => $row ) {
			if ( !empty( $row->desc ) ) {
				$rows[$rid]->desc = stripslashes( strip_tags( $row->desc ) );
				if ( strlen( $rows[$rid]->desc ) > 50 ) {
					$rows[$rid]->desc = substr( $rows[$rid]->desc, 0, 50) . ' ...';
				}
			}
		}

		$sel = array();
		$sel[] = JHTML::_('select.option', 'ordering ASC',		JText::_('ORDERING_ASC') );
		$sel[] = JHTML::_('select.option', 'ordering DESC',		JText::_('ORDERING_DESC') );
		$sel[] = JHTML::_('select.option', 'id ASC',			JText::_('ID_ASC') );
		$sel[] = JHTML::_('select.option', 'id DESC',			JText::_('ID_DESC') );
		$sel[] = JHTML::_('select.option', 'name ASC',			JText::_('NAME_ASC') );
		$sel[] = JHTML::_('select.option', 'name DESC',			JText::_('NAME_DESC') );
		$sel[] = JHTML::_('select.option', 'class_name ASC',	JText::_('CLASSNAME_ASC') );
		$sel[] = JHTML::_('select.option', 'class_name DESC',	JText::_('CLASSNAME_DESC') );

		$lists['orderNav'] = JHTML::_('select.genericlist', $sel, 'orderby_mi', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $this->state->sort );

		// Get list of plans for filter
		$query = 'SELECT `id`, `name`'
			. ' FROM #__acctexp_plans'
			. ' ORDER BY `ordering`'
		;
		$this->db->setQuery( $query );
		$db_plans = $this->db->loadObjectList();

		$plans[] = JHTML::_('select.option', '0', JText::_('FILTER_PLAN'), 'id', 'name' );
		if ( is_array( $db_plans ) ) {
			$plans = array_merge( $plans, $db_plans );
		}
		$lists['filterplanid']	= JHTML::_('select.genericlist', $plans, 'filter_planid', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'id', 'name', $this->state->filter->plan );

		HTML_AcctExp::listMicroIntegrations( $rows, $this->state, $this->getPagination(), $lists );
	}

	public function edit( $id )
	{
		$lists	= array();
		$mi		= new microIntegration();
		$mi->load( $id );

		$aecHTML = null;
		$attached = array();

		$mi_gsettings = $mi->getGeneralSettings();

		if ( !$mi->id ) {
			// Create MI Selection List
			$mi_handler = new microIntegrationHandler();
			$mi_list = $mi_handler->getIntegrationList();

			$drilldown = array( 'all' => array() );

			$mi_gsettings['class_name']		= array( 'hidden' );
			$mi_gsettings['class_list']		= array( 'list' );

			if ( count( $mi_list ) > 0 ) {
				foreach ( $mi_list as $name ) {
					$mi_item = new microIntegration();

					if ( $mi_item->callDry( $name ) ) {
						$handle = str_replace( 'mi_', '', $mi_item->class_name );

						if ( isset( $mi_item->info['type'] ) ) {
							foreach ( $mi_item->info['type'] as $type ) {
								$drill = explode( '.', $type );

								$cursor =& $drilldown;

								$mi_item->name = str_replace( array(' AEC ', ' MI '), ' ', $mi_item->name );

								foreach ( $drill as $i => $k ) {
									if ( !isset( $cursor[$k] ) ) {
										$cursor[$k] = array();
									}

									if ( $i == count( $drill )-1 ) {
										$cursor[$k][] = '<a href="#' . $handle . '" class="mi-menu-mi"><span class="mi-menu-mi-name">' . $mi_item->name . '</span><span class="mi-menu-mi-desc">' . $mi_item->desc . '</span></a>';
									} else {
										$cursor =& $cursor[$k];
									}
								}
							}
						}

						$drilldown['all'][] = '<a href="#' . $handle . '" class="mi-menu-mi"><span class="mi-menu-mi-name">' . $mi_item->name . '</span><span class="mi-menu-mi-desc">' . $mi_item->desc . '</span></a>';
					}
				}

				deep_ksort( $drilldown );

				$lists['class_list'] = '<a tabindex="0" href="#mi-select-list" class="btn btn-primary" id="drilldown">Select an Integration</a>';

				$lists['class_list'] .= '<div id="mi-select-list" class="hidden"><ul>';
				foreach ( $drilldown as $lin => $li ) {
					if ( $this->lang->hasKey( 'AEC_MI_LIST_' . strtoupper( $lin ) ) ) {
						$kkey = JText::_('AEC_MI_LIST_' . strtoupper( $lin ) );
					} else {
						$kkey = ucwords( str_replace('_', ' ', $lin) );
					}

					$lists['class_list'] .= '<li><a href="#">' . $kkey . '</a><ul>';

					foreach ( $li as $lixn => $lix ) {
						if ( is_array( $lix ) ) {
							if ( $this->lang->hasKey( 'AEC_MI_LIST_' . strtoupper( $lixn ) ) ) {
								$xkey = JText::_('AEC_MI_LIST_' . strtoupper( $lixn ) );
							} else {
								$xkey = ucwords( str_replace('_', ' ', $lixn) );
							}

							$lists['class_list'] .= '<li><a href="#">' . $xkey . '</a><ul>';

							foreach ( $lix as $mix ) {
								$lists['class_list'] .= '<li>' . $mix . '</li>';
							}

							$lists['class_list'] .= '</ul></li>';
						} else {
							$lists['class_list'] .= '<li>' . $lix . '</li>';
						}
					}

					$lists['class_list'] .= '</ul></li>';
				}
				$lists['class_list'] .= '</ul></div>';

			} else {
				$lists['class_list'] = '';
			}
		}

		if ( $mi->id ) {
			// Call MI (override active check) and Settings
			if ( $mi->callIntegration( true ) ) {
				$attached['plans'] = microIntegrationHandler::getPlansbyMI( $mi->id, false, true );
				$attached['groups'] = microIntegrationHandler::getGroupsbyMI( $mi->id, false, true );

				$set = array();
				foreach ( $mi_gsettings as $n => $v ) {
					if ( !isset( $mi->$n ) ) {
						if (  isset( $mi->settings[$n] ) ) {
							$set[$n] = $mi->settings[$n];
						} else {
							$set[$n] = null;
						}
					} else {
						$set[$n] = $mi->$n;
					}
				}

				$restrictionHelper = new aecRestrictionHelper();

				$mi_gsettings['restr_remaps']	= array( 'subarea_change', 'restrictions' );

				$mi_gsettings = array_merge( $mi_gsettings, $restrictionHelper->getParams() );

				if ( empty( $mi->restrictions ) ) {
					$mi->restrictions = array();
				}

				$lists = array_merge( $lists, $restrictionHelper->getLists( $set, $mi->restrictions ) );

				$mi_gsettings[$mi->id.'remap']	= array( 'area_change', 'MI' );
				$mi_gsettings[$mi->id.'remaps']	= array( 'subarea_change', $mi->class_name );

				$mi_settings = $mi->getSettings();

				// Get lists supplied by the MI
				if ( !empty( $mi_settings['lists'] ) ) {
					$lists = array_merge( $lists, $mi_settings['lists'] );
					unset( $mi_settings['lists'] );
				}

				$available_plans = SubscriptionPlanHandler::getPlanList( false, false, true, null, true );

				$selected_plans = array();
				foreach ( $attached['plans'] as $p ) {
					$selected_plans[] = (object) array( 'value' => $p->id, 'text' => $p->name );
				}

				$lists['attach_to_plans'] = JHTML::_('select.genericlist', $available_plans, 'attach_to_plans[]', 'size="1" multiple="multiple" class="select2-bootstrap"', 'value', 'text', $selected_plans );

				$available_groups = ItemGroupHandler::getGroups( null, true );

				$selected_groups = array();
				foreach ( $attached['groups'] as $g ) {
					$selected_groups[] = (object) array( 'value' => $g->id, 'text' => $g->name );
				}

				$lists['attach_to_groups'] = JHTML::_('select.genericlist', $available_groups, 'attach_to_groups[]', 'size="1" multiple="multiple" class="select2-bootstrap"', 'value', 'text', $selected_groups );

				$gsettings = new aecSettings( 'MI', 'E' );
				$gsettings->fullSettingsArray( $mi_gsettings, array_merge( $set, $mi->restrictions ), $lists );

				$settings = new aecSettings( 'MI', $mi->class_name );
				$settings->fullSettingsArray( $mi_settings, $set, $lists );

				// Call HTML Class
				$aecHTML = new aecHTML( array_merge( $gsettings->settings, $settings->settings ), array_merge( $gsettings->lists, $settings->lists ) );

				$aecHTML->hasHacks = method_exists( $mi->mi_class, 'hacks' );

				$aecHTML->customparams = array();
				foreach ( $mi_settings as $n => $v ) {
					$aecHTML->customparams[] = $n;
				}

				$aecHTML->hasSettings = true;

				$aecHTML->hasRestrictions = !empty( $mi->settings['has_restrictions'] );
			} else {
				$short	= 'microIntegration loading failure';
				$event	= 'When trying to load microIntegration: ' . $mi->id . ', callIntegration failed';
				$tags	= 'microintegration,loading,error';
				$params = array();

				$eventlog = new eventLog();
				$eventlog->issue( $short, $tags, $event, 128, $params );
			}
		} else {
			$settings = new aecSettings( 'MI', 'E' );
			$settings->fullSettingsArray( $mi_gsettings, array(), $lists );

			// Call HTML Class
			$aecHTML = new aecHTML( $settings->settings, $settings->lists );

			$aecHTML->hasSettings = false;

			$aecHTML->hasRestrictions = false;

			$available_plans = SubscriptionPlanHandler::getPlanList( false, false, true, null, true );
			$lists['attach_to_plans'] = JHTML::_('select.genericlist', $available_plans, 'attach_to_plans[]', 'size="1" multiple="multiple" class="select2-bootstrap"', 'value', 'text', null );

			$available_groups = ItemGroupHandler::getGroups( null, true );
			$lists['attach_to_groups'] = JHTML::_('select.genericlist', $available_groups, 'attach_to_groups[]', 'size="1" multiple="multiple" class="select2-bootstrap"', 'value', 'text', null );
		}

		HTML_AcctExp::editMicroIntegration( $mi, $lists, $aecHTML, $attached );
	}

	public function save()
	{
		unset( $_POST['option'] );
		unset( $_POST['task'] );

		$id = $_POST['id'] ? $_POST['id'] : 0;

		$mi = new microIntegration();
		$mi->load( $id );

		if ( !empty( $_POST['class_name'] ) ) {
			$load = $mi->callDry( $_POST['class_name'] );
		} else {
			$load = $mi->callIntegration( 1 );
		}

		if ( $load ) {
			$save = array(
				'attach_to_plans' => array(),
				'attached_to_plans' => array(),
				'attach_to_groups' => array(),
				'attached_to_groups' => array()
			);

			foreach ( $save as $pid => $v ) {
				if ( isset( $_POST[$pid] ) ) {
					$save[$pid] = $_POST[$pid];

					unset( $_POST[$pid] );
				} else {
					$save[$pid] = array();
				}
			}

			$mi->savePostParams( $_POST );

			$mi->storeload();

			$all_groups = array_unique( array_merge( $save['attach_to_groups'], $save['attached_to_groups'] ) );

			if ( !empty( $all_groups ) ) {
				foreach ( $all_groups as $groupid ) {
					$group = new ItemGroup();
					$group->load( $groupid );

					if ( in_array( $groupid, $save['attach_to_groups'] ) && !in_array( $groupid, $save['attached_to_groups'] ) ) {
						$group->params['micro_integrations'][] = $mi->id;

						$group->storeload();
					} elseif ( !in_array( $groupid, $save['attach_to_groups'] ) && in_array( $groupid, $save['attached_to_groups'] ) ) {
						unset( $group->params['micro_integrations'][array_search( $mi->id, $group->params['micro_integrations'] )] );

						$group->storeload();
					}
				}
			}

			$all_plans = array_unique( array_merge( $save['attach_to_plans'], $save['attached_to_plans'] ) );

			if ( !empty( $all_plans ) ) {
				foreach ( $all_plans as $planid ) {
					$plan = new SubscriptionPlan();
					$plan->load( $planid );

					if ( in_array( $planid, $save['attach_to_plans'] ) && !in_array( $planid, $save['attached_to_plans'] ) ) {
						$plan->micro_integrations[] = $mi->id;

						$plan->storeload();
					} elseif ( !in_array( $planid, $save['attach_to_plans'] ) && in_array( $planid, $save['attached_to_plans'] ) ) {
						unset( $plan->micro_integrations[array_search( $mi->id, $plan->micro_integrations )] );

						$plan->storeload();
					}
				}
			}
		} else {
			$short	= 'microIntegration storing failure';
			if ( !empty( $_POST['class_name'] ) ) {
				$event	= 'When trying to store microIntegration: ' . $_POST['class_name'] . ', callIntegration failed';
			} else {
				$event	= 'When trying to store microIntegration: ' . $mi->id . ', callIntegration failed';
			}
			$tags	= 'microintegration,loading,error';
			$params = array();

			$eventlog = new eventLog();
			$eventlog->issue( $short, $tags, $event, 128, $params );
		}

		$mi->reorder();

		$this->setMessage( JText::_('AEC_MSG_SUCESSFULLY_SAVED') );
	}

	public function remove( $id )
	{
		$ids = implode( ',', $id );

		$query = 'SELECT count(*)'
			. ' FROM #__acctexp_microintegrations'
			. ' WHERE `id` IN (' . $ids . ')'
		;
		$this->db->setQuery( $query );
		$total = $this->db->loadResult();

		if ( $total==0 ) {
			echo "<script> alert('" . html_entity_decode( JText::_('AEC_MSG_NO_ITEMS_TO_DELETE') ) . "'); window.history.go(-1);</script>\n";
			exit;
		}

		// Call On-Deletion function
		foreach ( $id as $k ) {
			$mi = new microIntegration();
			$mi->load($k);
			if ( $mi->callIntegration() ) {
				$mi->delete();
			}
		}

		// Micro Integrations from table
		$query = 'DELETE FROM #__acctexp_microintegrations'
			. ' WHERE `id` IN (' . $ids . ')'
		;
		$this->db->setQuery( $query	);

		if ( !$this->db->query() ) {
			echo "<script> alert('".$this->db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		$this->setMessage( $total . ' ' . JText::_('AEC_MSG_ITEMS_DELETED') );
	}

	public function change( $cid=null, $state=0 )
	{
		if ( count( $cid ) < 1 ) {
			$action = $state == 1 ? JText::_('AEC_CMN_TOPUBLISH'): JText::_('AEC_CMN_TOUNPUBLISH');
			echo "<script> alert('" . sprintf( html_entity_decode( JText::_('AEC_ALERT_SELECT_FIRST_TO') ), $action ) . "'); window.history.go(-1);</script>\n";
			exit;
		}

		$total = count( $cid );
		$cids = implode( ',', $cid );

		$query = 'UPDATE #__acctexp_microintegrations'
			. ' SET `active` = \'' . $state . '\''
			. ' WHERE `id` IN (' . $cids . ')'
		;
		$this->db->setQuery( $query );
		if ( !$this->db->query() ) {
			echo "<script> alert('".$this->db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		if ( $state ) {
			$this->setMessage( $total . ' ' . JText::_('AEC_MSG_ITEMS_SUCC_PUBLISHED') );
		} else {
			$this->setMessage( $total . ' ' . JText::_('AEC_MSG_ITEMS_SUCC_UNPUBLISHED') );
		}
	}
}

class aecAdminCoupon extends aecAdminEntity
{
	public $table = 'coupons';

	public $searchable = array('name', 'coupon_code');

	public function index()
	{
		$total = 0;

		$this->db->setQuery(
			'SELECT count(*)'
			. ' FROM #__acctexp_' . $this->table
			. $this->getConstraints()
		);

		$total += $this->db->loadResult();

		$this->db->setQuery(
			'SELECT count(*)'
			. ' FROM #__acctexp_coupons_static'
			. $this->getConstraints()
		);

		$nav = $this->getPagination(
			$total + $this->db->loadResult()
		);

		// get the subset (based on limits) of required records
		$query = '(SELECT *, "0" as `type`'
			. ' FROM #__acctexp_coupons'
			. $this->getConstraints()
			. ')'
			. ' UNION '
			. '(SELECT *, "1" as `type`'
			. ' FROM #__acctexp_coupons_static'
			. $this->getConstraints()
			. ')'
			. $this->getOrdering()
			. $this->getLimit()
		;

		$this->db->setQuery( $query );

		$rows = $this->db->loadObjectList();
		if ( $this->db->getErrorNum() ) {
			echo $this->db->stderr();
			return false;
		}

		$query = 'SELECT SUM(usecount)'
			. ' FROM #__acctexp_coupons'
		;
		$this->db->setQuery( $query );

		$total_usecount = $this->db->loadResult();
		if ( $this->db->getErrorNum() ) {
			echo $this->db->stderr();
			return false;
		}

		$query = 'SELECT SUM(usecount)'
			. ' FROM #__acctexp_coupons_static'
		;
		$this->db->setQuery( $query );

		$total_usecount += $this->db->loadResult();
		if ( $this->db->getErrorNum() ) {
			echo $this->db->stderr();
			return false;
		}

		foreach ( $rows as $rid => $row ) {
			if ( $row->usecount ) {
				$rows[$rid]->percentage = $row->usecount / ( $total_usecount / 100 );
			} else {
				$rows[$rid]->percentage = 0;
			}

			$rows[$rid]->inner = false;
			if ( $rows[$rid]->percentage > 15 ) {
				$rows[$rid]->inner = true;
			}
		}

		HTML_AcctExp::listCoupons($rows, $this->state, $nav);
	}

	public function edit( $id )
	{
		$lists = array();

		$cph = new couponHandler();

		if ( empty($id) ) {
			$cph->coupon = new Coupon();
			$cph->coupon->createNew();

			$params_values			= array();
			$discount_values		= array();
			$restrictions_values	= array();
		} else {
			$idx = explode( ".", $id[0] );

			$cph->coupon = new Coupon( $idx[0] );
			$cph->coupon->load( $idx[1] );

			$params_values			= $cph->coupon->params;
			$discount_values		= $cph->coupon->discount;
			$restrictions_values	= $cph->coupon->restrictions;
		}

		// We need to convert the values that are set as object properties
		$params_values['active']				= $cph->coupon->active;
		$params_values['type']					= $cph->coupon->type;
		$params_values['name']					= $cph->coupon->name;
		$params_values['desc']					= $cph->coupon->desc;
		$params_values['coupon_code']			= $cph->coupon->coupon_code;
		$params_values['usecount']				= $cph->coupon->usecount;
		$params_values['micro_integrations']	= $cph->coupon->micro_integrations;

		// params and their type values
		$params['active']						= array( 'toggle',		1 );
		$params['type']							= array( 'toggle',		1 );
		$params['name']							= array( 'inputC',		'' );
		$params['desc']							= array( 'inputE',		'' );
		$params['coupon_code']					= array( 'inputC',		'' );
		$params['micro_integrations']			= array( 'list',		'' );

		$params['params_remap']					= array( 'subarea_change',	'params' );

		$params['amount_use']					= array( 'toggle',		'' );
		$params['amount']						= array( 'inputB',		'' );
		$params['amount_percent_use']			= array( 'toggle',		'' );
		$params['amount_percent']				= array( 'inputB',		'' );
		$params['percent_first']				= array( 'toggle',		'' );
		$params['useon_trial']					= array( 'toggle',		'' );
		$params['useon_full']					= array( 'toggle',		'1' );
		$params['useon_full_all']				= array( 'toggle',		'1' );

		$params['has_start_date']				= array( 'toggle',		1 );
		$params['start_date']					= array( 'list_date',	date( 'Y-m-d', ( (int) gmdate('U') ) ) );
		$params['has_expiration']				= array( 'toggle',		0);
		$params['expiration']					= array( 'list_date',	date( 'Y-m-d', ( (int) gmdate('U') ) ) );
		$params['has_max_reuse']				= array( 'toggle',		0 );
		$params['max_reuse']					= array( 'inputA',		1 );
		$params['has_max_peruser_reuse']		= array( 'toggle',		1 );
		$params['max_peruser_reuse']			= array( 'inputA',		1 );
		$params['usecount']						= array( 'inputA',		0 );

		$params['usage_plans_enabled']			= array( 'toggle',		0 );
		$params['usage_plans']					= array( 'list',		0 );

		$params['usage_cart_full']				= array( 'toggle',		0 );
		$params['cart_multiple_items']			= array( 'toggle',		0 );
		$params['cart_multiple_items_amount']	= array( 'inputB',		'' );

		$params['restr_remap']					= array( 'subarea_change',	'restrictions' );

		$params['depend_on_subscr_id']			= array( 'toggle',		0 );
		$params['subscr_id_dependency']			= array( 'inputB',		'' );
		$params['allow_trial_depend_subscr']	= array( 'toggle',		0 );

		$params['restrict_combination']			= array( 'toggle',		0 );
		$params['bad_combinations']				= array( 'list',		'' );

		$params['allow_combination']			= array( 'toggle',		0 );
		$params['good_combinations']			= array( 'list',		'' );

		$params['restrict_combination_cart']	= array( 'toggle',		0 );
		$params['bad_combinations_cart']		= array( 'list',		'' );

		$params['allow_combination_cart']		= array( 'toggle',		0 );
		$params['good_combinations_cart']		= array( 'list',		'' );

		$restrictionHelper = new aecRestrictionHelper();
		$params = array_merge( $params, $restrictionHelper->getParams() );

		// get available plans
		$available_plans = array();

		$this->db->setQuery(
			'SELECT `id` as value, `name` as text'
			. ' FROM #__acctexp_plans'
		);
		$plans = $this->db->loadObjectList();

		if ( is_array( $plans ) ) {
			$all_plans = array_merge( $available_plans, $plans );
		} else {
			$all_plans = $available_plans;
		}
		$total_all_plans = min( max( ( count( $all_plans ) + 1 ), 4 ), 20 );

		// get usages
		if ( !empty( $restrictions_values['usage_plans'] ) ) {
			$query = 'SELECT `id` AS value, `name` as text'
				. ' FROM #__acctexp_plans'
				. ' WHERE `id` IN (' . implode( ',', $restrictions_values['usage_plans'] ) . ')'
			;
			$this->db->setQuery( $query );

			$sel_usage_plans = $this->db->loadObjectList();
		} else {
			$sel_usage_plans = 0;
		}

		$lists['usage_plans']		= JHTML::_('select.genericlist', $all_plans, 'usage_plans[]', 'size="' . $total_all_plans . '" multiple="multiple"',
			'value', 'text', $sel_usage_plans);


		// get available micro integrations
		$available_mi = array();

		$query = 'SELECT `id` AS value, CONCAT(`name`, " - ", `desc`) AS text'
			. ' FROM #__acctexp_microintegrations'
			. ' WHERE `active` = 1'
			. ' ORDER BY `ordering`'
		;
		$this->db->setQuery( $query );
		$mi_list = $this->db->loadObjectList();

		$mis = array();
		if ( !empty( $mi_list ) && !empty( $params_values['micro_integrations'] ) ) {
			foreach ( $mi_list as $mi_item ) {
				if ( in_array( $mi_item->value, $params_values['micro_integrations'] ) ) {
					$mis[] = $mi_item->value;
				}
			}
		}

		if ( !empty( $mis ) ) {
			$query = 'SELECT `id` AS value, CONCAT(`name`, " - ", `desc`) AS text'
				. ' FROM #__acctexp_microintegrations'
				. ( !empty( $mis ) ? ' WHERE `id` IN (' . implode( ',', $mis ) . ')' : '' )
			;
			$this->db->setQuery( $query );
			$selected_mi = $this->db->loadObjectList();
		} else {
			$selected_mi = array();
		}

		$lists['micro_integrations'] = JHTML::_('select.genericlist', $mi_list, 'micro_integrations[]', 'size="' . min((count( $mi_list ) + 1), 25) . '" multiple="multiple"', 'value', 'text', $selected_mi );

		$query = 'SELECT count(*)'
			. ' FROM #__acctexp_coupons'
		;
		$this->db->setQuery( $query );
		$ccount = $this->db->loadResult();

		if ( $ccount > 50 ) {
			$coupons = array();
		} else {
			$query = 'SELECT `coupon_code` as value, `coupon_code` as text'
				. ' FROM #__acctexp_coupons'
				. ' WHERE `coupon_code` != \'' . $cph->coupon->coupon_code . '\''
			;
			$this->db->setQuery( $query );
			$coupons = $this->db->loadObjectList();
		}

		$query = 'SELECT `coupon_code` as value, `coupon_code` as text'
			. ' FROM #__acctexp_coupons_static'
			. ' WHERE `coupon_code` != \'' . $cph->coupon->coupon_code . '\''
		;
		$this->db->setQuery( $query );
		$coupons = array_merge( $this->db->loadObjectList(), $coupons );

		$cpl = array( 'bad_combinations', 'good_combinations', 'bad_combinations_cart', 'good_combinations_cart' );

		foreach ( $cpl as $cpn ) {
			$cur = array();

			if ( !empty( $restrictions_values[$cpn] ) ) {
				if ( $ccount > 50 ) {
					$cur = array();
				} else {
					$query = 'SELECT `coupon_code` as value, `coupon_code` as text'
						. ' FROM #__acctexp_coupons'
						. ' WHERE `coupon_code` IN (\'' . implode( '\',\'', $restrictions_values[$cpn] ) . '\')'
					;
					$this->db->setQuery( $query );
					$cur = $this->db->loadObjectList();
				}

				$query = 'SELECT `coupon_code` as value, `coupon_code` as text'
					. ' FROM #__acctexp_coupons_static'
					. ' WHERE `coupon_code` IN (\'' . implode( '\',\'', $restrictions_values[$cpn] ) . '\')'
				;
				$this->db->setQuery( $query );
				$nc = $this->db->loadObjectList();

				if ( !empty( $nc ) ) {
					$cur = array_merge( $nc, $cur );
				}
			}

			$lists[$cpn] = JHTML::_('select.genericlist', $coupons, $cpn.'[]', 'size="' . min((count( $coupons ) + 1), 25) . '" multiple="multiple"', 'value', 'text', $cur);
		}

		$lists = array_merge( $lists, $restrictionHelper->getLists( $params_values, $restrictions_values ) );

		$settings = new aecSettings( 'coupon', 'general' );

		if ( is_array( $discount_values ) && is_array( $restrictions_values ) ) {
			$settingsparams = array_merge( $params_values, $discount_values, $restrictions_values );
		} else {
			$settingsparams = $params_values;
		}

		$settings->fullSettingsArray( $params, $settingsparams, $lists );

		// Call HTML Class
		$aecHTML = new aecHTML( $settings->settings, $settings->lists );

		// Lets grab the data and fill it in.
		$this->db->setQuery(
			'SELECT id'
			. ' FROM #__acctexp_invoices'
			. ' WHERE `coupons` <> \'\''
			. ' ORDER BY `created_date` DESC'
		);
		$rows = $this->db->loadObjectList();

		if ( $this->db->getErrorNum() ) {
			echo $this->db->stderr();
			return false;
		}

		$aecHTML->invoices = array();
		foreach ( $rows as $row ) {
			$invoice = new Invoice();
			$invoice->load( $row->id );

			if ( !in_array( $cph->coupon->coupon_code, $invoice->coupons ) ) {
				continue;
			}

			$in_formatted = $invoice->formatInvoiceNumber();

			$invoice->invoice_number_formatted = $invoice->invoice_number . ( ($in_formatted != $invoice->invoice_number) ? "\n" . '(' . $in_formatted . ')' : '' );

			$invoice->usage = '<a href="index.php?option=com_acctexp&amp;task=edit&amp;entity=SubscriptionPlan&amp;id=' . $invoice->usage . '">' . $invoice->usage . '</a>';

			$query = 'SELECT username'
				. ' FROM #__users'
				. ' WHERE `id` = \'' . $invoice->userid . '\''
			;
			$this->db->setQuery( $query );
			$username = $this->db->loadResult();

			$invoice->username = '<a href="index.php?option=com_acctexp&amp;task=edit&amp;entity=Membership&userid=' . $invoice->userid . '">';

			if ( !empty( $username ) ) {
				$invoice->username .= $username . '</a>';
			} else {
				$invoice->username .= $invoice->userid;
			}

			$invoice->username .= '</a>';

			$aecHTML->invoices[] = $invoice;
		}

		HTML_AcctExp::editCoupon( $aecHTML, $cph->coupon );
	}

	public function save( $coupon_code )
	{
		$new = 0;
		$type = $_POST['type'];

		$_POST['coupon_code'] = aecGetParam( 'coupon_code', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );

		if ( $_POST['coupon_code'] == '' ) {
			$this->setMessage( JText::_('AEC_MSG_NO_COUPON_CODE') );

			return;
		}

		$cph = new couponHandler();

		if ( !empty( $_POST['id'] ) ) {
			$cph->coupon = new Coupon( $_POST['oldtype'] );
			$cph->coupon->load( $_POST['id'] );

			if ( $cph->coupon->id ) {
				$cph->status = true;
			}
		} else {
			$cph->load( $_POST['coupon_code'] );
		}

		if ( !$cph->status ) {
			$cph->coupon = new Coupon( $type );
			$cph->coupon->createNew( $_POST['coupon_code'] );
			$cph->status = true;
			$new = 1;
		}

		if ( $cph->status ) {
			if ( !$new ) {
				if ( $cph->coupon->type != $_POST['type'] ) {
					$cph->switchType();
				}
			}

			unset( $_POST['type'] );
			unset( $_POST['oldtype'] );
			unset( $_POST['id'] );

			$post = AECToolbox::cleanPOST( $_POST, false );

			$cph->coupon->savePOSTsettings( $post );

			$cph->coupon->storeload();
		} else {
			$short	= 'coupon store failure';
			$event	= 'When trying to store coupon';
			$tags	= 'coupon,loading,error';
			$params = array();

			$eventlog = new eventLog();
			$eventlog->issue( $short, $tags, $event, 128, $params );
		}

		$this->setMessage( JText::_('AEC_MSG_SUCESSFULLY_SAVED') );
	}

	public function remove( $id, $option, $returnTask )
	{
		$rids = $sids = array();
		foreach ( $id as $i ) {
			$ex = explode( '.', $i );

			if ( $ex[0] ) {
				$sids[] = $ex[1];
			} else {
				$rids[] = $ex[1];
			}
		}

		if ( !empty( $sids ) ) {
			$query = 'DELETE FROM #__acctexp_coupons_static'
				. ' WHERE `id` IN (' . implode( ',', $sids ) . ')'
			;
			$this->db->setQuery( $query );

			if ( !$this->db->query() ) {
				echo "<script> alert('".$this->db->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
		}

		if ( !empty( $rids ) ) {
			$query = 'DELETE FROM #__acctexp_coupons'
				. ' WHERE `id` IN (' . implode( ',', $rids ) . ')'
			;
			$this->db->setQuery( $query );

			if ( !$this->db->query() ) {
				echo "<script> alert('".$this->db->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
		}

		$this->setMessage( JText::_('AEC_MSG_ITEMS_DELETED') );
	}

	public function change( $id=null, $state=0, $option )
	{
		if ( count( $id ) < 1 ) {
			$action = $state == 1 ? JText::_('AEC_CMN_TOPUBLISH') : JText::_('AEC_CMN_TOUNPUBLISH');
			echo "<script> alert('" . sprintf( html_entity_decode( JText::_('AEC_ALERT_SELECT_FIRST_TO') ) ), $action . "'); window.history.go(-1);</script>\n";
			exit;
		}

		$idx	= explode( ',', $id );

		$rids = $sids = array();
		foreach ( $idx as $ctype => $cid ) {
			if ( $ctype ) {
				$sids[] = $cid;
			} else {
				$rids[] = $cid;
			}
		}

		if ( !empty( $sids ) ) {
			$this->db->setQuery(
				'UPDATE #__acctexp_coupons_static'
				. ' SET `active` = IF (`active` = 1, 0, 1)'
				. ' WHERE `id` IN (' . implode( ',', $sids ) . ')'
			);
			$this->db->query();
		}

		if ( !empty( $rids ) ) {
			$this->db->setQuery(
				'UPDATE #__acctexp_coupons'
				. ' SET `active` = IF (`active` = 1, 0, 1)'
				. ' WHERE `id` IN (' . implode( ',', $rids ) . ')'
			);
			$this->db->query();
		}

		$this->setMessage( count( $id ) . ' ' . JText::_('AEC_MSG_ITEMS_SUCC_UPDATED') );
	}

}

class aecAdminInvoice extends aecAdminEntity
{
	public $table = 'invoices';

	public $searchable = array('invoice_number', 'secondary_ident', 'id', 'invoice_number_format');

	public function index()
	{
		$ids = $this->getRows();

		$processors = PaymentProcessorHandler::getObjectList(
			PaymentProcessorHandler::getProcessorList()
		);

		$procs = array(
			'free' => 'Free',
			'none' => 'None'
		);

		foreach ( $processors as $processor ) {
			$procs[$processor->processor_name] = $processor->processor->info['longname'];
		}

		$invoices = array();
		$cclist = array();
		foreach ( $ids as $data ) {
			$invoice = new Invoice();
			$invoice->load( $data->id );

			$invoice->formatInvoiceNumber();

			if ( empty($invoice->invoice_number_formatted) ) {
				$invoice->invoice_number_formatted = $invoice->invoice_number;
			} else {
				$invoice->invoice_number_formatted = $invoice->invoice_number . ( ($invoice->invoice_number_formatted != $invoice->invoice_number) ? "\n" . '(' . $invoice->invoice_number_formatted . ')' : '' );
			}

			if ( !empty( $invoice->coupons ) ) {
				$coupons = $invoice->coupons;
			} else {
				$coupons = null;
			}

			if ( !empty( $coupons ) ) {
				$invoice->coupons = "";

				$couponslist = array();
				foreach ( $coupons as $coupon_code ) {
					if ( !isset( $cclist[$coupon_code] ) ) {
						$cclist[$coupon_code] = couponHandler::idFromCode( $coupon_code );
					}

					if ( !empty( $cclist[$coupon_code]['id'] ) ) {
						$couponslist[] = '<a href="index.php?option=com_acctexp&amp;task=' . ( $cclist[$coupon_code]['type'] ? 'editcouponstatic' : 'editcoupon' ) . '&amp;id=' . $cclist[$coupon_code]['id'] . '">' . $coupon_code . '</a>';
					}
				}

				$invoice->coupons = implode( ", ", $couponslist );
			} else {
				$invoice->coupons = null;
			}

			$invoice->usage = '<a href="index.php?option=com_acctexp&amp;task=edit&amp;entity=SubscriptionPlan&amp;id=' . $invoice->usage . '">' . $invoice->usage . '</a>';

			$query = 'SELECT username'
				. ' FROM #__users'
				. ' WHERE `id` = \'' . $invoice->userid . '\''
			;
			$this->db->setQuery( $query );
			$username = $this->db->loadResult();

			$invoice->username = '<a href="index.php?option=com_acctexp&amp;task=edit&amp;entity=Membership&userid=' . $invoice->userid . '">';

			if ( !empty( $username ) ) {
				$invoice->username .= $username . '</a>';
			} else {
				$invoice->username .= $invoice->userid;
			}

			$invoice->username .= '</a>';

			if ( isset($procs[$invoice->method]) ) {
				$invoice->processor = $procs[$invoice->method];
			} else {
				$invoice->processor = $invoice->method;
			}

			$invoices[$data->id] = $invoice;
		}

		HTML_AcctExp::viewInvoices( $invoices, $this->getPagination(), $this->state );
	}

	public function edit( $id, $userid )
	{
		$row = new Invoice();
		$row->load( $id );

		$params['fixed']				= array( 'toggle',		0 );
		$params['userid']				= array( 'hidden',		$userid );
		$params['active']				= array( 'toggle',		1 );
		$params['created_date']			= array( 'list_date',	date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) ) );
		$params['amount']				= array( 'inputB',		'' );
		$params['usage']				= array( 'list', 		0 );
		$params['method']				= array( 'list', 		'' );
		$params['coupons']				= array( 'list', 		0 );

		$available_plans = SubscriptionPlanHandler::getActivePlanList();

		$lists['usage'] = JHTML::_('select.genericlist', $available_plans, 'usage', 'size="1"', 'value', 'text', $row->usage );

		$lists['method']				= str_replace( 'processor', 'method', PaymentProcessorHandler::getSelectList( $row->method, true ) );

		$this->db->setQuery(
			'SELECT `id` as value, `coupon_code` as text'
			. ' FROM #__acctexp_coupons'
		);

		$coupons = $this->db->loadObjectList();

		$this->db->setQuery(
			'SELECT `id` as value, `coupon_code` as text'
			. ' FROM #__acctexp_coupons_static'
		);

		$coupons = array_merge( $this->db->loadObjectList(), $coupons );

		$coupons_active = array();

		if ( !empty($row->coupons) ) {
			foreach ( $row->coupons as $coupon_code ) {
				$coupon_id = couponHandler::idFromCode($coupon_code);

				$coupons_active[] = (int) $coupon_id['id'];
			}
		}

		$lists['coupons'] = JHTML::_('select.genericlist', $coupons, 'coupons[]', 'multiple="multiple"', 'value', 'text', $coupons_active);

		$params_values = array();
		$params_values['active']		= $row->active;
		$params_values['fixed']			= $row->fixed;
		$params_values['userid']		= $row->userid;
		$params_values['created_date']	= $row->created_date;

		$settings = new aecSettings ( 'invoice', 'general' );
		$settings->fullSettingsArray( $params, $params_values, $lists ) ;

		// Call HTML Class
		$aecHTML = new aecHTML( $settings->settings, $settings->lists );
		if ( !empty( $customparamsarray ) ) {
			$aecHTML->customparams = $customparamsarray;
		}

		$aecHTML->params = $row->params;

		HTML_AcctExp::editInvoice( $aecHTML, $id );
	}

	public function save()
	{
		$row = new Invoice();
		$row->load( $_POST['id'] );

		$returnTask = $_POST['returnTask'];

		unset( $_POST['id'] );
		unset( $_POST['returnTask'] );

		if ( empty($_POST['coupons']) ) {
			$_POST['coupons'] = array();
		}

		$previous = array();
		if ( !empty($row->coupons) ) {
			foreach ( $row->coupons as $coupon_code ) {
				$id = couponHandler::idFromCode($coupon_code);

				$previous[] = $id['id'];
			}
		}

		$added = array();
		foreach ( $_POST['coupons'] as $coupon_id ) {
			if ( !in_array($coupon_id, $previous) ) {
				$added[] = $coupon_id;
			} else {
				unset( $previous[array_search($coupon_id, $previous)] );
			}
		}

		if ( !empty($added) ) {
			foreach ( $added as $coupon_id ) {
				$row->addCoupon((int)$coupon_id, true);
			}
		}

		if ( !empty($previous) ) {
			foreach ( $previous as $coupon_id ) {
				$row->removeCoupon((int)$coupon_id, true);
			}
		}

		unset($_POST['coupons']);

		$row->savePOSTsettings( $_POST );

		$row->storeload();

		$this->setMessage( JText::_('AEC_CONFIG_SAVED') );
	}

	public function clear( $invoice_number, $applyplan, $task )
	{
		$invoiceid = aecInvoiceHelper::InvoiceIDfromNumber( $invoice_number, 0, true );

		$userid = '';
		if ( $invoiceid ) {
			$objInvoice = new Invoice();
			$objInvoice->load( $invoiceid );

			$pp = new stdClass();
			$pp->id = 0;
			$pp->processor_name = 'none';

			if ( $applyplan ) {
				$objInvoice->pay();
			} else {
				$objInvoice->setTransactionDate();
			}

			$history = new logHistory();
			$history->entryFromInvoice( $objInvoice, null, $pp );

			if ( strcmp( $task, 'editMembership' ) == 0) {
				$userid = '&userid=' . $objInvoice->userid;
			}
		}

		aecRedirect( 'index.php?option=com_acctexp&task=' . $task . $userid, JText::_('AEC_MSG_INVOICE_CLEARED') );
	}

	public function cancelEntity( $invoice_number, $task )
	{
		$invoiceid = aecInvoiceHelper::InvoiceIDfromNumber( $invoice_number, 0, true );

		$userid = '';
		if ( $invoiceid ) {
			$objInvoice = new Invoice();
			$objInvoice->load( $invoiceid );

			$objInvoice->delete();

			if ( strcmp( $task, 'editMembership' ) == 0 ) {
				$userid = '&userid=' . $objInvoice->userid;
			}
		}

		aecRedirect( 'index.php?option=com_acctexp&task=' . $task . $userid, JText::_('REMOVED') );
	}

	public function printout( $invoice_number, $standalone=true )
	{
		$invoice = new Invoice();
		$invoice->loadInvoiceNumber( $invoice_number );

		$iFactory = new InvoiceFactory( $invoice->userid, null, null, null, null, null, false, true );
		$iFactory->invoiceprint( $invoice->invoice_number, $standalone );
	}

	public function printoutPDF( $invoice_number )
	{
		require_once( JPATH_SITE . '/components/com_acctexp/lib/tcpdf/config/lang/eng.php' );
		require_once( JPATH_SITE . '/components/com_acctexp/lib/tcpdf/tcpdf.php' );

		ob_start();

		AdminInvoicePrintout( $invoice_number, false );

		$buffer = ob_get_contents();

		ob_end_clean();

		$document= JFactory::getDocument();
		$document->_type="html";
		$renderer = $document->loadRenderer("head");

		$content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'
			.'<html xmlns="http://www.w3.org/1999/xhtml">'
			.'<head>' . $renderer->render("head") . '</head><body>'.$buffer.'</body>'
			.'</html>';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$pdf->AddPage();
		$pdf->writeHTML($content, true, false, true, false, '');

		$pdf->Output( $invoice_number.'.pdf', 'I');exit;
	}

}

class aecAdminService extends aecAdminEntity
{
	public $table = 'services';

	public $searchable = array('name');

	public function index()
	{
		HTML_AcctExp::listServices(
			$this->getRows(),
			$this->state,
			$this->getPagination(),
			array()
		);
	}

	public function edit( $id )
	{
		$lists = array();
		$params_values = array();

		$row = aecService::getById($id);

		if ( empty($row->id) ) {
			$row = new aecService();
			$row->ordering	= 9999;

			$params_values['active']	= 1;
		} else {
			$params_values = $row->params;

			// We need to convert the values that are set as object properties
			$params_values['active']				= $row->active;
			$params_values['name']					= $row->name;
		}

		// params and their type values
		$params['active']					= array( 'toggle', 1 );
		$params['visible']					= array( 'toggle', 0 );

		$params['name']						= array( 'inputC', '' );
		$params['type']						= array( 'list', '' );

		$params['params_remap']				= array( 'subarea_change', 'services' );

		$servicelist = aecServiceList::getAvailableServiceClasses(true);

		$glist = array();

		$glist[] = JHTML::_('select.option', 0, '- - - - - -' );
		foreach ( $servicelist as $service ) {
			$info = $service->getInfo();

			$glist[] = JHTML::_('select.option', $info['slug'], $info['name'], 'value', 'text' );
		}

		$lists['type'] = JHTML::_('select.genericlist', $glist, 'type', 'size="1"', 'value', 'text', ( ( $row->id ) ? 0 : 1 ) );

		$settings = new aecSettings( 'service', 'general' );

		if ( $row->id ) {
			$service_params = $row->getSettings();

			$params = array_merge($params, $service_params);
		} else {
			$service_params = array();
		}

		$settings->fullSettingsArray( $params, $params_values, $lists ) ;

		// Call HTML Class
		$aecHTML = new aecHTML( $settings->settings, $settings->lists );

		if ( !empty($service_params) ) {
			foreach ( $service_params as $n => $v ) {
				$aecHTML->customparams[] = $n;
			}
		}

		$aecHTML->hasSettings = $id ? true : false;

		HTML_AcctExp::editService( $row, $aecHTML );
	}

	public function save($id)
	{
		$post = AECToolbox::cleanPOST( $_POST, false );

		if ($id) {
			$row = aecService::getById($id);
		} else {
			$row = aecService::getByType($post['type']);
		}

		$row->savePOSTsettings( $post );

		if ( !$row->check() ) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-2); </script>\n";
			exit();
		}
		if ( !$row->store() ) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-2); </script>\n";
			exit();
		}

		$row->reorder();

		if ( empty($id) ) {
			$this->params['id'] = $row->getMax();
		}
	}

	public function remove( $id, $option )
	{
		$ids = implode( ',', $id );

		$this->db->setQuery(
			'SELECT count(*)'
			. ' FROM #__acctexp_services'
			. ' WHERE `id` IN (' . $ids . ')'
		);

		$total = $this->db->loadResult();

		if ( $total == 0 ) {
			echo "<script> alert('" . html_entity_decode( JText::_('AEC_MSG_NO_ITEMS_TO_DELETE') ) . "'); window.history.go(-1);</script>\n";
			exit;
		}

		if ( $total == 0 ) {
			echo "<script> alert('" . html_entity_decode( JText::_('AEC_MSG_NO_ITEMS_TO_DELETE') ) . "'); window.history.go(-1);</script>\n";
			exit;
		} else {
			$this->db->setQuery(
				'DELETE FROM #__acctexp_services'
				. ' WHERE `id` IN (' . $ids . ')'
			);

			$this->db->query();

			$msg = $total . ' ' . JText::_('AEC_MSG_ITEMS_DELETED');

			aecRedirect( 'index.php?option=com_acctexp&task=showServices', $msg );
		}
	}

	public function change( $cid=null, $state=0, $type, $option )
	{
		$this->db = JFactory::getDBO();

		if ( count( $cid ) < 1 ) {
			echo "<script> alert('" . JText::_('AEC_ALERT_SELECT_FIRST') . "'); window.history.go(-1);</script>\n";
			exit;
		}

		$total	= count( $cid );
		$cids	= implode( ',', $cid );

		$query = 'UPDATE #__acctexp_services'
			. ' SET `' . $type . '` = \'' . $state . '\''
			. ' WHERE `id` IN (' . $cids . ')'
		;
		$this->db->setQuery( $query );

		if ( !$this->db->query() ) {
			echo "<script> alert('".$this->db->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}

		if ( $state ) {
			$msg = ( ( strcmp( $type, 'active' ) === 0 ) ? JText::_('AEC_CMN_PUBLISHED') : JText::_('AEC_CMN_MADE_VISIBLE') );
		} else {
			$msg = ( ( strcmp( $type, 'active' ) === 0 ) ? JText::_('AEC_CMN_NOT_PUBLISHED') : JText::_('AEC_CMN_MADE_INVISIBLE') );
		}

		$msg = sprintf( JText::_('AEC_MSG_ITEMS_SUCESSFULLY'), $total ) . ' ' . $msg;

		aecRedirect( 'index.php?option=com_acctexp&task=showServices', $msg );
	}
}

class aecAdminHistory extends aecAdminEntity
{
	public $table = 'log_history';

	public $searchable = array('user_name', 'invoice_number', 'proc_name');

	public function index( $option )
	{
		HTML_AcctExp::viewHistory(
			$this->getRows('logHistory'),
			$this->getPagination(),
			$this->state
		);
	}
}

class aecAdminEventlog extends aecAdminEntity
{
	public $table = 'eventlog';

	public $searchable = array('short', 'event', 'tags');

	public function index( $option )
	{
		$rows = $this->getRows();

		$events = array();
		foreach ( $rows as $id ) {
			$row = new EventLog();
			$row->load( $id );

			$events[$id] = new stdClass();
			$events[$id]->id		= $row->id;
			$events[$id]->datetime	= $row->datetime;
			$events[$id]->short		= $row->short;
			$events[$id]->tags		= implode( ', ', explode( ',', $row->tags ) );
			$events[$id]->event		= $row->event;
			$events[$id]->level		= $row->level;
			$events[$id]->notify	= $row->notify;

			$params = array();
			if ( !empty( $row->params ) && is_array( $row->params ) ) {
				foreach ( $row->params as $key => $value ) {
					switch ( $key ) {
						case 'userid':
							$content = '<a href="index.php?option=com_acctexp&amp;task=edit&amp;entity=Membership&userid=' . $value . '">' . $value . '</a>';
							break;
						case 'invoice_number':
							$content = '<a class="quicksearch" href="#">' . $value . '</a>';
							break;
						default:
							$content = $value;
							break;
					}
					$params[] = $key . '(' . $content . ')';
				}
			}
			$events[$id]->params = implode( ', ', $params );

			if ( strpos( $row->event, '<?xml' ) !== false ) {
				$events[$id]->event = '<p><strong>XML cell - decoded as:</strong></p><pre class="prettyprint">'.htmlentities($row->event).'</pre>';
			} else {
				$format = @json_decode( $row->event );

				if ( is_array( $format ) || is_object( $format ) ) {
					$events[$id]->event = '<p><strong>JSON cell - decoded as:</strong></p><pre class="prettyprint">'.print_r($format,true).'</pre>';
				} else {
					$events[$id]->event = htmlentities( stripslashes( $events[$id]->event ) );
				}
			}
		}

		HTML_AcctExp::eventlog($events, $this->getPagination(), $this->getState());
	}
}

class aecAdminStats extends aecAdminEntity
{
	public function index( $page )
	{
		if ( empty( $page ) ) {
			$page = 'overview';
		}

		$stats = array();

		$document= JFactory::getDocument();
		$document->addCustomTag( '<script type="text/javascript" src="' . JURI::root(true) . '/media/com_acctexp/js/d3/d3.min.js"></script>' );
		$document->addCustomTag( '<script type="text/javascript" src="' . JURI::root(true) . '/media/com_acctexp/js/d3/d3.time.min.js"></script>' );
		$document->addCustomTag( '<script type="text/javascript" src="' . JURI::root(true) . '/media/com_acctexp/js/d3/d3.layout.min.js"></script>' );
		$document->addCustomTag( '<script type="text/javascript" src="' . JURI::root(true) . '/media/com_acctexp/js/rickshaw/rickshaw.js"></script>' );
		$document->addCustomTag( '<link type="text/css" href="' . JURI::root(true) . '/media/com_acctexp/js/rickshaw/rickshaw.css" rel="stylesheet" />' );
		$document->addCustomTag( '<link type="text/css" href="' . JURI::root(true) . '/media/com_acctexp/js/colorbrewer/colorbrewer.css" rel="stylesheet" />' );

		$query = 'SELECT count(*)'
			. ' FROM #__acctexp_log_history'
		;
		$this->db->setQuery( $query );

		$stats['sale_count'] = $this->db->loadResult();

		$query = 'SELECT DISTINCT(date(transaction_date)) AS date, count( * ) AS count' .
			' FROM #__acctexp_log_history' .
			' GROUP BY date' .
			' ORDER BY count ASC';
		$this->db->setQuery( $query );
		$sales_count = $this->db->loadObjectList();
		$stats['min_sale_count'] = $sales_count[0]->count;
		$stats['max_sale_count'] = $sales_count[count($sales_count)-1]->count;
		$stats['avg_sale_count'] = $sales_count[((int) (count($sales_count)/2) )]->count;

		$query = 'SELECT amount'
			. ' FROM #__acctexp_log_history'
			. ' ORDER BY 0+`amount` DESC'
		;
		$this->db->setQuery( $query );

		$stats['max_sale_value'] = $this->db->loadResult();

		$query = 'SELECT MIN(amount)'
			. ' FROM #__acctexp_log_history'
			. ' WHERE amount > 0'
		;
		$this->db->setQuery( $query );

		$stats['min_sale_value'] = $this->db->loadResult();

		$query = 'SELECT SUM(amount)'
			. ' FROM #__acctexp_log_history'
		;
		$this->db->setQuery( $query );

		if ( $stats['sale_count'] ) {
			$stats['avg_sale_value'] = round( $this->db->loadResult() / $stats['sale_count'], 2 );
		} else {
			$stats['avg_sale_value'] = 0;
		}

		$stats['avg_sale'] = $stats['avg_sale_count']*$stats['avg_sale_value']*1.8;

		$query = 'SELECT MIN(transaction_date)'
			. ' FROM #__acctexp_log_history'
		;
		$this->db->setQuery( $query );

		$stats['first_sale'] = $this->db->loadResult();

		$query = 'SELECT id, name'
			. ' FROM #__acctexp_plans'
			. ' ORDER BY `id`'
		;

		$this->db->setQuery( $query );

		$rows = $this->db->loadObjectList();

		$mrow = count( $rows )-1;

		$i = 0;
		$stats['plan_names'] = array();
		for ( $i=0; $i<=$rows[$mrow]->id; $i++ ) {
			$stats['plan_names'][$i] = "";
			foreach ( $rows as $rid => $row ) {
				if ( $row->id == $i ) {
					$stats['plan_names'][$i] = $row->name;
				}
			}
		}

		$query = 'SELECT id, name'
			. ' FROM #__acctexp_itemgroups'
			. ' ORDER BY `id`'
		;

		$this->db->setQuery( $query );

		$rows = $this->db->loadObjectList();

		$mrow = count( $rows )-1;

		$i = 0;
		$stats['group_names'] = array();
		for ( $i=0; $i<=$rows[$mrow]->id; $i++ ) {
			$stats['group_names'][$i] = "";
			foreach ( $rows as $rid => $row ) {
				if ( $row->id == $i ) {
					$stats['group_names'][$i] = $row->name;
				}
			}
		}

		HTML_AcctExp::stats( $page, $stats );
	}

	public function request( $type, $start, $end )
	{
		$tree = new stdClass();

		switch ( $type ) {
			case 'sales':
				$tree = array();

				if ( empty( $end ) ) {
					$end = date( 'Y-m-d H:i:s', ( (int) gmdate('U') ) );
				}

				$query = 'SELECT `id`'
					. ' FROM #__acctexp_log_history'
					. ' WHERE transaction_date >= \'' . $start . '\''
					. ' AND transaction_date <= \'' . $end . '\''
					. ' ORDER BY transaction_date ASC'
				;
				$this->db->setQuery( $query );
				$entries = xJ::getDBArray( $this->db );

				if ( empty( $entries ) ) {
					echo json_encode( $tree );exit;
				}

				$historylist = array();
				$groups = array();
				foreach ( $entries as $id ) {
					$entry = new logHistory();
					$entry->load( $id );
					$entry->amount = AECToolbox::correctAmount( $entry->amount );

					$refund = false;

					if ( is_array( $entry->response ) && !empty( $entry->response ) ) {
						$filter = array( 'new_case', 'subscr_signup', 'paymentreview', 'subscr_eot', 'subscr_failed', 'subscr_cancel', 'Pending', 'Denied' );

						foreach ( $entry->response as $v ) {
							if ( in_array( $v, $filter ) ) {
								continue 2;
							} elseif ( ( $v == 'refund' ) || ( $v == 'Reversed' ) || ( $v == 'Refunded' ) ) {
								$refund = true;
							}
						}
					} else {
						continue;
					}

					$pgroups = ItemGroupHandler::parentGroups( $entry->plan_id );

					if ( empty( $pgroups[0] ) ) {
						$pgroups[0] = 0;
					}

					if ( !in_array( $pgroups[0], $groups ) ) {
						$groups[] = $pgroups[0];
					}

					$sale			= new stdClass();
					$sale->id		= $id;
					//$sale->invoice	= $entry->invoice_number;
					$sale->date		= $entry->transaction_date;
					//$sale->datejs	= date( 'F d, Y H:i:s', strtotime( $entry->transaction_date ) );
					$sale->plan		= $entry->plan_id;
					$sale->group	= $pgroups[0];
					$sale->amount	= $refund ? (-$entry->amount) : $entry->amount;

					$tree[] = $sale;
				}

				break;
		}

		echo json_encode( $tree );exit;
	}
}

class aecAdminImport extends aecAdminEntity
{
	public function import()
	{
		$show_form = false;
		$done = false;

		$temp_dir = JPATH_SITE . '/tmp';

		$file_list = xJUtility::getFileArray( $temp_dir, 'csv', false, true );

		$params = array();
		$lists = array();

		if ( !empty( $_FILES ) ) {
			if ( strpos( $_FILES['import_file']['name'], '.csv' ) === false ) {
				$last = strrpos( $_FILES['import_file']['name'], '.' );

				$filename = substr( $_FILES['import_file']['name'], 0, $last ) . '.csv';
			} else {
				$filename = $_FILES['import_file']['name'];
			}

			$destination = $temp_dir . '/' . $filename;

			if ( move_uploaded_file( $_FILES['import_file']['tmp_name'], $destination ) ) {
				$file_select = $filename;
			}
		}

		if ( empty( $file_select ) ) {
			$file_select = aecGetParam( 'file_select', '' );
		}

		if ( empty( $file_select ) ) {
			$show_form = true;

			$params['file_select']			= array( 'list', '' );
			$params['MAX_FILE_SIZE']		= array( 'hidden', '5120000' );
			$params['import_file']			= array( 'file', 'Upload', 'Upload a file and select it for importing', '' );

			$file_htmllist		= array();
			$file_htmllist[]	= JHTML::_('select.option', '', JText::_('AEC_CMN_NONE_SELECTED') );

			if ( !empty( $file_list ) ) {
				foreach ( $file_list as $name ) {
					$file_htmllist[] = JHTML::_('select.option', $name, $name );
				}
			}

			$lists['file_select'] = JHTML::_('select.genericlist', $file_htmllist, 'file_select', 'size="' . min( ( count( $file_htmllist ) + 1 ), 25 ) . '"', 'value', 'text', 0 );
		} else {
			$options = array();

			if ( !empty( $_POST['assign_plan'] ) ) {
				$options['assign_plan'] = $_POST['assign_plan'];
			}

			$import = new aecImport( $temp_dir . '/' . $file_select, $options );

			if ( !$import->read() ) {
				die( 'could not read file' );
			}

			$import->parse();

			if ( empty( $import->rows ) ) {
				die( 'could not find any entries in this file' );
			}

			$params['file_select'] = array( 'hidden', $file_select );

			if ( !isset( $_POST['convert_field_0'] ) ) {
				$fields = array(
					'id' => 'User ID',
					'name' => 'User Full Name',
					'username' => 'Username',
					'email' => 'User Email',
					'password' => 'Password',
					'plan_id' => 'Payment Plan ID',
					'invoice_number' => 'Invoice Number',
					'expiration' => 'Membership Expiration'
				);

				$mis = array_merge(
					microIntegrationHandler::getMIList(false, false, false, false, 'mi_aecuserdetails')
				);

				foreach( $mis as $entry ) {
					$mi = new microIntegration();
					$mi->load($entry->id);

					if ( $mi->callIntegration() ) {
						$fields = array_merge(
							$fields,
							$mi->mi_class->getCustomFields()
						);
					}
				}

				$field_htmllist		= array();
				$field_htmllist[]	= JHTML::_('select.option', 0, 'Ignore' );

				foreach ( $fields as $name => $longname ) {
					$field_htmllist[] = JHTML::_('select.option', $name, $longname );
				}

				$cols = count( $import->rows[0] );

				$columns = array();
				for ( $i=0; $i<$cols; $i++ ) {
					$columns[] = 'convert_field_'.$i;

					$params['convert_field_'.$i] = array( 'list', '', '', '' );

					$lists['convert_field_'.$i] = JHTML::_('select.genericlist', $field_htmllist, 'convert_field_'.$i, 'size="1" class="select2-bootstrap"', 'value', 'text', 0 );
				}

				$rows_count = count( $import->rows );

				$rowcount = min( $rows_count, 5 );

				$rows = array();
				for ( $i=0; $i<$rowcount; $i++ ) {
					$rows[] = $import->rows[$i];
				}

				$params['assign_plan'] = array( 'list', 'Assign Plan', 'Assign users to a specific payment plan. Is overridden if you provide an individual plan ID with the "Payment Plan ID" field assignment.' );

				$params['skip_first'] = array( 'toggle', 'Skip First Line', 'Do not import the first line (use this if you have column names in the first line).' );

				$available_plans	= SubscriptionPlanHandler::getActivePlanList();

				$lists['assign_plan'] = JHTML::_('select.genericlist', $available_plans, 'assign_plan', 'size="5"', 'value', 'text', 0 );
			} else {
				$import->getConversionList();

				$import->import(
					array(
						'skip_first' => $_POST['skip_first']
					)
				);

				$done = true;
			}
		}

		$settingsparams = array();

		$settings = new aecSettings ( 'import', 'general' );
		$settings->fullSettingsArray( $params, $settingsparams, $lists ) ;

		// Call HTML Class
		$aecHTML = new aecHTML( $settings->settings, $settings->lists );

		$aecHTML->form		= $show_form;
		$aecHTML->done		= $done;

		if ( !empty( $import->errors ) ) {
			$aecHTML->errors	= $import->errors;
		}

		if ( !$show_form && !$done ) {
			$aecHTML->user_rows = $rows;
			$aecHTML->user_rows_count = $rows_count;
			$aecHTML->columns = $columns;
		}

		HTML_AcctExp::import( $aecHTML );
	}
}

class aecAdminExport extends aecAdminEntity
{
	function export( $type, $cmd=null )
	{
		$db = JFactory::getDBO();

		$cmd_save = ( strcmp( 'save', $cmd ) === 0 );
		$cmd_apply = ( strcmp( 'apply', $cmd ) === 0 );
		$cmd_load = ( strcmp( 'load', $cmd ) === 0 );
		$cmd_export = ( strcmp( 'export', $cmd ) === 0 );
		$use_original = 0;

		$system_values = array();
		$filter_values = array();
		$options_values = array();
		$params_values = array();

		if ( $type == 'sales' ) {
			$getpost = array(	'system' => array( 'selected_export', 'delete', 'save', 'save_name' ),
								 'filter' => array( 'date_start', 'date_end', 'method', 'planid', 'groupid', 'status', 'orderby' ),
								 'options' => array( 'collate', 'breakdown', 'breakdown_custom' ),
								 'params' => array( 'export_method' )
			);

			$pf = 8;
		} else {
			$getpost = array(	'system' => array( 'selected_export', 'delete', 'save', 'save_name' ),
								 'filter' => array( 'planid', 'groupid', 'status', 'orderby' ),
								 'options' => array( 'rewrite_rule' ),
								 'params' => array( 'export_method' )
			);

			$pf = 5;
		}

		$postfields = 0;
		foreach( $getpost as $name => $array ) {
			$field = $name . '_values';

			foreach( $array as $vname ) {
				$vvalue = aecGetParam( $vname, '' );
				if ( !empty( $vvalue ) ) {
					${$field}[$vname] = $vvalue;

					$postfields++;
				}
			}
		}

		if ( !empty( $params_values['export_method'] ) ) {
			$is_test = $params_values['export_method'] == 'test';
		} else {
			$is_test = false;
		}

		$lists = array();

		$pname = "";

		if ( !empty( $system_values['selected_export'] ) || $cmd_save || $cmd_apply || $is_test ) {
			$row = new aecExport( ( $type == 'sales' ) );
			if ( isset( $system_values['selected_export'] ) ) {
				$row->load( $system_values['selected_export'] );

				$pname = $row->name;
			} else {
				$row->load(0);
			}

			if ( !empty( $system_values['delete'] ) ) {
				// User wants to delete the entry
				$row->delete();
			} elseif ( ( $cmd_save || $cmd_apply ) && ( !empty( $system_values['selected_export'] ) || !empty( $system_values['save_name'] ) ) ) {
				// User wants to save an entry
				if ( !empty( $system_values['save'] ) ) {
					// But as a copy of another entry
					$row->load( 0 );
				}

				$row->saveComplex( $system_values['save_name'], $filter_values, $options_values, $params_values );

				if ( !empty( $system_values['save'] ) ) {
					$system_values['selected_export'] = $row->getMax();
				}
			} elseif ( ( $cmd_save || $cmd_apply ) && ( empty( $system_values['selected_export'] ) && !empty( $system_values['save_name'] ) && $system_values['save'] ) && !$is_test ) {
				// User wants to save a new entry
				$row->saveComplex( $system_values['save_name'], $filter_values, $options_values, $params_values );
			}  elseif ( $cmd_load || ( count($postfields) && ( $postfields <= $pf ) && ( $cmd_export || $is_test ) )  ) {
				if ( $row->id ) {
					// User wants to load an entry
					$filter_values = $row->filter;
					$options_values = $row->options;
					$params_values = $row->params;
					$pname = $row->name;

					$use_original = 1;
				}
			}
		}

		// Always store the last ten calls, but only if something is happening
		if ( $cmd_save || $cmd_apply || $cmd_export ) {
			$autorow = new aecExport( ( $type == 'sales' ) );
			$autorow->load(0);
			$autorow->saveComplex( 'Autosave', $filter_values, $options_values, $params_values, true );

			if ( isset( $row ) ) {
				if ( ( $autorow->filter == $row->filter ) && ( $autorow->options == $row->options ) && ( $autorow->params == $row->params ) ) {
					$use_original = 1;
				}
			}
		}

		$filters = array( 'planid', 'groupid', 'status' );

		foreach ( $filters as $filter ) {
			if ( !isset( $filter_values[$filter] ) ) {
				$filter_values[$filter] = array();

				continue;
			}

			if ( !is_array( $filter_values[$filter] ) ) {
				if ( !empty( $filter_values[$filter] ) ) {
					$filter_values[$filter] = array( $filter_values[$filter] );
				} else {
					$filter_values[$filter] = array();
				}
			}
		}

		if ( $is_test ) {
			$row->params['export_method'] = 'test';
		}

		// Create Parameters

		$params[] = array( 'userinfobox', 5 );

		if ( $type == 'members' ) {
			$params[] = array( 'section_paper', 'Compose Export' );
			$params['params_remap']		= array( 'subarea_change', 'params' );
			$params[] = array( 'div', '<div class="alert alert-info">' );
			$params[] = array( 'p', '<p>Take users that fit these criteria:</p>' );
			$params['groupid']			= array( 'list', '' );
			$params['planid']			= array( 'list', '' );
			$params['status']			= array( 'list', '' );
			$params[] = array( 'div_end', '' );
			$params[] = array( 'div', '<div class="alert alert-warning">' );
			$params[] = array( 'p', '<p>Order them like this:</p>' );
			$params['orderby']			= array( 'list', '' );
			$params[] = array( 'div_end', '' );
			$params[] = array( 'div', '<div class="alert alert-success">' );
			$params[] = array( 'p', '<p>And use these details for each line of the export:</p>' );
			$params['rewrite_rule']	= array( 'inputD', '[[user_id]];[[user_username]];[[subscription_expiration_date]]' );
			$params[] = array( 'div_end', '' );
			$params[] = array( 'section_end', '' );
		} else {
			$monthago = ( (int) gmdate('U') ) - ( 60*60*24 * 31 );

			$params[] = array( 'section_paper', 'Compose Export' );
			$params['params_remap']		= array( 'subarea_change', 'params' );
			$params[] = array( 'div', '<div class="alert alert-info">' );
			$params[] = array( 'p', '<p>Collect Sales Data from this range:</p>' );
			$params['date_start']		= array( 'list_date', date( 'Y-m-d', $monthago ) );
			$params['date_end']			= array( 'list_date', date( 'Y-m-d' ) );
			$params['method']			= array( 'list', '' );
			$params['planid']			= array( 'list', '' );
			$params['groupid']			= array( 'list', '' );
			$params[] = array( 'div_end', '' );
			$params[] = array( 'div', '<div class="alert alert-warning">' );
			$params[] = array( 'p', '<p>Collate it like this:</p>' );
			$params['collate']			= array( 'list', 'day' );
			$params[] = array( 'div_end', '' );
			$params[] = array( 'div', '<div class="alert alert-success">' );
			$params[] = array( 'p', '<p>Break down the data in each line like so:</p>' );
			$params['breakdown']		= array( 'list', 'month' );
			$params['breakdown_custom']	= array( 'inputD', '' );
			$params[] = array( 'div_end', '' );
			$params[] = array( 'section_end', '' );
		}

		if ( $type == 'members' ) {
			$params[] = array( 'userinfobox', 5 );
			$params[] = array( 'section_paper' );
			$rewriteswitches			= array( 'cms', 'user', 'subscription', 'plan', 'invoice' );
			$params = AECToolbox::rewriteEngineInfo( $rewriteswitches, $params );
			$params[] = array( 'section_end', '' );
			$params[] = array( '2div_end', '' );
		}

		$params[] = array( '2div_end', '' );

		$params[] = array( 'userinfobox', 5 );
		$params[] = array( 'section_paper', 'Save or Load Export Presets' );
		$params[] = array( 'div', '<div class="form-wide">' );
		$params['selected_export']	= array( 'list', '' );
		$params['delete']			= array( 'checkbox' );
		$params['save']				= array( 'checkbox' );
		$params['save_name']		= array( 'inputC', $pname );
		$params[] = array( 'div_end', '' );
		$params[] = array( 'div', '<div class="right-btns">' );
		$params[] = array( 'p', '<a class="btn btn-primary" onclick="javascript: submitbutton(\'loadExport' . $type . '\')" href="#">' . aecHTML::Icon( 'upload' ) . '&nbsp;Load Preset</a>' );
		$params[] = array( 'p', '<a class="btn btn-success" onclick="javascript: submitbutton(\'applyExport' . $type . '\')" href="#">' . aecHTML::Icon( 'download' ) . '&nbsp;Save Preset</a>' );
		$params[] = array( 'p', '<a class="btn danger" onclick="javascript: submitbutton(\'saveExport' . $type . '\')" href="#">' . aecHTML::Icon( 'download-alt' ) . '&nbsp;Save Preset &amp; Exit</a>' );
		$params[] = array( 'div_end', '' );
		$params[] = array( 'section_end', '' );
		$params[] = array( '2div_end', '' );

		$params[] = array( 'userinfobox', 5 );
		$params[] = array( 'section_paper', 'Export' );
		$params['export_method']	= array( 'list', '' );
		$params[] = array( 'p', '<div class="right-btns"><div class="btn-group">' );
		$params[] = array( 'p', '<a class="btn btn-info" id="testexport" href="#export-result">' . aecHTML::Icon( 'eye-open' ) . '&nbsp;Test Export</a>' );
		$params[] = array( 'p', '<a class="btn btn-success" onclick="javascript: submitbutton(\'exportExport' . $type . '\')" href="#">' . aecHTML::Icon( 'file' ) . '&nbsp;Export Now</a>' );
		$params[] = array( '2div_end', '' );
		$params[] = array( 'section_end', '' );
		$params[] = array( '2div_end', '' );

		$params[] = array( 'userinfobox', 5 );
		$params[] = array( 'div', '<div class="aec-settings-container" id="export-result">' );
		$params[] = array( 'h4', '<h4>Preview</h4>' );
		$params[] = array( '2div_end', '' );

		// Create a list of export options
		// First, only the non-autosaved entries
		$query = 'SELECT `id`, `name`, `created_date`, `lastused_date`'
			. ' FROM #__acctexp_export' . ( ( $type == 'sales' ) ? '_sales' : '' )
			. ' WHERE `system` = \''
		;
		$db->setQuery( $query . '0\'' );
		$user_exports = $db->loadObjectList();

		// Then the autosaved entries
		$db->setQuery( $query . '1\'' );
		$system_exports = $db->loadObjectList();

		$entries = count( $user_exports ) + count( $system_exports );

		$m = 0;
		if ( $entries > 0 ) {
			$listitems = array();
			$listitems[] = JHTML::_('select.option', 0, " --- Your Exports --- " );

			$user = false;
			for ( $i=0; $i < $entries; $i++ ) {
				if ( ( $i >= count( $user_exports ) ) && ( $user === false ) ) {
					$user = $i;

					$listitems[] = JHTML::_('select.option', 0, " --- Autosaves --- " );
				}

				if ( $user === false ) {
					if ( !empty( $user_exports[$i]->name ) ) {
						$used_date = ( $user_exports[$i]->lastused_date == '0000-00-00 00:00:00' ) ? 'never' : $user_exports[$i]->lastused_date;
						$listitems[] = JHTML::_('select.option', $user_exports[$i]->id, substr( $user_exports[$i]->name, 0, 64 ) . ' - ' . 'last used: ' . $used_date . ', created: ' . $user_exports[$i]->created_date );
					} else {
						$m--;
					}
				} else {
					$ix = $i - $user;
					$used_date = ( $system_exports[$ix]->lastused_date == '0000-00-00 00:00:00' ) ? 'never' : $system_exports[$ix]->lastused_date;
					$listitems[] = JHTML::_('select.option', $system_exports[$ix]->id, substr( $system_exports[$ix]->name, 0, 64 ) . ' - ' . 'last used: ' . $used_date . ', created: ' . $system_exports[$ix]->created_date );
				}
			}
		} else {
			$listitems[] = JHTML::_('select.option', 0, " --- No saved Preset available --- " );
			$listitems[] = JHTML::_('select.option', 0, " --- Your Exports --- ", 'value', 'text', true );
			$listitems[] = JHTML::_('select.option', 0, " --- Autosaves --- ", 'value', 'text', true );
		}

		$lists['selected_export'] = JHTML::_('select.genericlist', $listitems, 'selected_export', 'size="' . max( 10, min( 20, $entries+$m+2 ) ) . '" class="col-sm-7"', 'value', 'text', arrayValueDefault($system_values, 'selected_export', '') );

		// Get list of plans for filter
		$query = 'SELECT `id`, `name`'
			. ' FROM #__acctexp_plans'
			. ' ORDER BY `ordering`'
		;
		$db->setQuery( $query );
		$db_plans = $db->loadObjectList();

		$lists['planid'] = '<select id="plan-filter-select" class="col-sm-3" name="planid[]" multiple="multiple" size="5">';
		foreach ( $db_plans as $plan ) {
			$lists['planid'] .= '<option value="' . $plan->id . '"' . ( in_array( $plan->id, $filter_values['planid'] ) ? ' selected="selected"' : '' ) . '>' . $plan->name . '</option>';
		}
		$lists['planid'] .= '</select>';

		$grouplist = ItemGroupHandler::getTree();

		$lists['groupid'] = '<select id="group-filter-select" class="col-sm-3" name="groupid[]" multiple="multiple" size="5">';
		foreach ( $grouplist as $glisti ) {
			if ( defined( 'JPATH_MANIFESTS' ) ) {
				$lists['groupid'] .= '<option value="' . $glisti[0] . '"' . ( in_array( $glisti[0], $filter_values['groupid'] ) ? ' selected="selected"' : '' ) . '>' . str_replace( '&nbsp;', ' ', $glisti[1] ) . '</option>';
			} else {
				$lists['groupid'] .= '<option value="' . $glisti[0] . '"' . ( in_array( $glisti[0], $filter_values['groupid'] ) ? ' selected="selected"' : '' ) . '>' . $glisti[1] . '</option>';
			}
		}
		$lists['groupid'] .= '</select>';

		if ( $type == 'members' ) {
			$status = array(	'excluded'	=> JText::_('AEC_SEL_EXCLUDED'),
								'pending'	=> JText::_('AEC_SEL_PENDING'),
								'active'	=> JText::_('AEC_SEL_ACTIVE'),
								'expired'	=> JText::_('AEC_SEL_EXPIRED'),
								'closed'	=> JText::_('AEC_SEL_CLOSED'),
								'cancelled'	=> JText::_('AEC_SEL_CANCELLED'),
								'hold'		=> JText::_('AEC_SEL_HOLD'),
								'notconfig'	=> JText::_('AEC_SEL_NOT_CONFIGURED')
			);

			$lists['status'] = '<select id="status-group-select" name="status[]" multiple="multiple" size="5">';
			foreach ( $status as $id => $txt ) {
				$lists['status'] .= '<option value="' . $id . '"' . ( in_array( $id, $filter_values['status'] ) ? ' selected="selected"' : '' ) . '>' . $txt . '</option>';
			}
			$lists['status'] .= '</select>';

			// Ordering
			$sel = array();
			$sel[] = JHTML::_('select.option', 'expiration ASC',	JText::_('EXP_ASC') );
			$sel[] = JHTML::_('select.option', 'expiration DESC',	JText::_('EXP_DESC') );
			$sel[] = JHTML::_('select.option', 'name ASC',			JText::_('NAME_ASC') );
			$sel[] = JHTML::_('select.option', 'name DESC',			JText::_('NAME_DESC') );
			$sel[] = JHTML::_('select.option', 'username ASC',		JText::_('LOGIN_ASC') );
			$sel[] = JHTML::_('select.option', 'username DESC',		JText::_('LOGIN_DESC') );
			$sel[] = JHTML::_('select.option', 'signup_date ASC',	JText::_('SIGNUP_ASC') );
			$sel[] = JHTML::_('select.option', 'signup_date DESC',	JText::_('SIGNUP_DESC') );
			$sel[] = JHTML::_('select.option', 'lastpay_date ASC',	JText::_('LASTPAY_ASC') );
			$sel[] = JHTML::_('select.option', 'lastpay_date DESC',	JText::_('LASTPAY_DESC') );
			$sel[] = JHTML::_('select.option', 'plan_name ASC',		JText::_('PLAN_ASC') );
			$sel[] = JHTML::_('select.option', 'plan_name DESC',	JText::_('PLAN_DESC') );
			$sel[] = JHTML::_('select.option', 'status ASC',		JText::_('STATUS_ASC') );
			$sel[] = JHTML::_('select.option', 'status DESC',		JText::_('STATUS_DESC') );
			$sel[] = JHTML::_('select.option', 'type ASC',			JText::_('TYPE_ASC') );
			$sel[] = JHTML::_('select.option', 'type DESC',			JText::_('TYPE_DESC') );

			$lists['orderby'] = JHTML::_('select.genericlist', $sel, 'orderby', 'class="inputbox" size="1"', 'value', 'text', arrayValueDefault($filter_values, 'orderby', '') );
		} else {
			$collate_selection = array();
			$collate_selection[] = JHTML::_('select.option', 'day',	JText::_('Day') );
			$collate_selection[] = JHTML::_('select.option', 'week',	JText::_('Week') );
			$collate_selection[] = JHTML::_('select.option', 'month',		JText::_('Month') );
			$collate_selection[] = JHTML::_('select.option', 'year',		JText::_('Year') );

			$selected_collate = 0;
			if ( !empty( $options_values['collate'] ) ) {
				$selected_collate = $options_values['collate'];
			} else {
				$selected_collate = 'day';
			}

			$lists['collate'] = JHTML::_('select.genericlist', $collate_selection, 'collate', 'size="1"', 'value', 'text', $selected_collate);

			$breakdown_selection = array();
			$breakdown_selection[] = JHTML::_('select.option', '0',	JText::_('None') );
			$breakdown_selection[] = JHTML::_('select.option', 'plan',	JText::_('Plan') );
			$breakdown_selection[] = JHTML::_('select.option', 'group',	JText::_('Group') );

			$selected_breakdown = 0;
			if ( !empty( $options_values['breakdown'] ) ) {
				$selected_breakdown = $options_values['breakdown'];
			}

			$lists['breakdown'] = JHTML::_('select.genericlist', $breakdown_selection, 'breakdown', 'size="1"', 'value', 'text', $selected_breakdown);

			$processors = PaymentProcessorHandler::getInstalledObjectList();

			$proc_list = array();
			$selected_proc = array();
			foreach ( $processors as $proc ) {
				$pp = new PaymentProcessor();
				$pp->loadName( $proc->name );
				$pp->getInfo();

				$proc_list[] = JHTML::_('select.option', $pp->id, $pp->info['longname'] );

				if ( !empty( $filter_values['method'] ) ) {
					foreach ( $filter_values['method'] as $id ) {
						if ( $id == $pp->id ) {
							$selected_proc[] = JHTML::_('select.option', $id, $pp->info['longname'] );
						}
					}
				}
			}

			$lists['method'] = JHTML::_('select.genericlist', $proc_list, 'method[]', 'size="8" multiple="multiple"', 'value', 'text', $selected_proc);
		}

		// Export Method
		$list = xJUtility::getFileArray( JPATH_SITE . '/components/com_acctexp/lib/export', 'php', false, true );

		$sel = array();
		foreach ( $list as $ltype ) {
			$ltype = str_replace( '.php', '', $ltype );
			if ( $ltype != 'test' ) {
				$sel[] = JHTML::_('select.option', $ltype, $ltype );
			}
		}

		if ( empty( $params_values['export_method'] ) ) {
			$params_values['export_method'] = 'csv';
		}

		$lists['export_method'] = JHTML::_('select.genericlist', $sel, 'export_method', 'class="inputbox" size="1"', 'value', 'text', $params_values['export_method'] );

		$settings = new aecSettings ( 'export', 'general' );

		// Repackage the objects as array
		foreach( $getpost as $name => $array ) {
			$field = $name . '_values';
			foreach( $array as $vname ) {
				if ( !empty( $$field->$name ) ) {
					$settingsparams[$name] = $$field->$name;
				} else {
					$settingsparams[$name] = "";
				}
			}
		}

		if ( empty( $params_values['rewrite_rule'] ) ) {
			//$params_values['rewrite_rule'] = '[[user_id]];[[user_username]];[[subscription_expiration_date]]';
		}

		$settingsparams = array_merge( $filter_values, $options_values, $params_values );

		$settings->fullSettingsArray( $params, $settingsparams, $lists ) ;

		// Call HTML Class
		$aecHTML = new aecHTML( $settings->settings, $settings->lists );

		if ( ( $cmd_export ) && !empty( $params_values['export_method'] ) ) {
			if ( $use_original ) {
				$row->useExport();
			} else {
				$autorow->useExport();
			}
		}

		if ( $cmd_save ) {
			aecRedirect( 'index.php?option=com_acctexp&task=showCentral' );
		} else {
			HTML_AcctExp::export( $type, $aecHTML );
		}
	}

}

class aecAdminHacks extends aecAdminEntity
{
	public function hack( $filename, $check_hack, $undohack, $checkonly=false )
	{
		$db = JFactory::getDBO();

		$app = JFactory::getApplication();

		$aec_hack_start				= "// AEC HACK %s START" . "\n";
		$aec_hack_end				= "// AEC HACK %s END" . "\n";

		$aec_condition_start		= 'if (file_exists( JPATH_ROOT."/components/com_acctexp/acctexp.class.php" )) {' . "\n";

		$aec_condition_end			= '}' . "\n";

		$aec_include_class			= 'include_once(JPATH_SITE . "/components/com_acctexp/acctexp.class.php");' . "\n";

		$aec_verification_check		= "AECToolBox::VerifyUsername( %s );" . "\n";
		$aec_userchange_clause		= '$mih = new microIntegrationHandler();' . "\n" . '$mih->userchange($row, $_POST, \'%s\');' . "\n";
		$aec_userchange_clauseCB12	= '$mih = new microIntegrationHandler();' . "\n" . '$mih->userchange($userComplete, $_POST, \'%s\');' . "\n";
		$aec_userchange_clause15	= '$mih = new microIntegrationHandler();' . "\n" . '$mih->userchange($userid, $post, \'%s\');' . "\n";
		$aec_userregchange_clause15	= '$mih = new microIntegrationHandler();' . "\n" . '$mih->userchange($user, $post, \'%s\');' . "\n";

		$aec_global_call			= "\n";

		$aec_redirect_notallowed	= 'aecRedirect( $mosConfig_live_site . "/index.php?option=com_acctexp&task=NotAllowed" );' . "\n";
		$aec_redirect_notallowed15	= '$app = JFactory::getApplication();' . "\n" . '$app->redirect( "index.php?option=com_acctexp&task=NotAllowed" );' . "\n";

		$aec_redirect_subscribe		= 'aecRedirect( JURI::root() . \'index.php?option=com_acctexp&task=subscribe\' );' . "\n";

		$aec_normal_hack = $aec_hack_start
			. $aec_global_call
			. $aec_condition_start
			. $aec_redirect_notallowed
			. $aec_condition_end
			. $aec_hack_end;

		$aec_jhack1 = $aec_hack_start
			. 'function mosNotAuth($override=false) {' . "\n"
			. $aec_global_call
			. $aec_condition_start
			. 'if (!$override) {' . "\n"
			. $aec_redirect_notallowed
			. $aec_condition_end
			. $aec_condition_end
			. $aec_hack_end;

		$aec_jhack2 = $aec_hack_start
			. $aec_global_call
			. $aec_condition_start
			. $aec_redirect_notallowed
			. $aec_condition_end
			. $aec_hack_end;

		$aec_jhack3 = $aec_hack_start
			. $aec_global_call
			. $aec_condition_start
			. $aec_include_class
			. sprintf( $aec_verification_check, '$credentials[\'username\']' )
			. $aec_condition_end
			. $aec_hack_end;

		$aec_cbmhack =	$aec_hack_start
			. "mosNotAuth(true);" . "\n"
			. $aec_hack_end;

		$aec_uchangehack =	$aec_hack_start
			. $aec_global_call
			. $aec_condition_start
			. $aec_include_class
			. $aec_userchange_clause
			. $aec_condition_end
			. $aec_hack_end;

		$aec_uchangehackCB12 = str_replace( '$row', '$userComplete', $aec_uchangehack );
		$aec_uchangehackCB12x = str_replace( '$row', '$this', $aec_uchangehack );

		$aec_uchangehackCB12 =	$aec_hack_start
			. $aec_global_call
			. $aec_condition_start
			. $aec_include_class
			. $aec_userchange_clauseCB12
			. $aec_condition_end
			. $aec_hack_end;

		$aec_uchangehack15 =	$aec_hack_start
			. $aec_global_call
			. $aec_condition_start
			. $aec_include_class
			. $aec_userregchange_clause15
			. $aec_condition_end
			. $aec_hack_end;

		$aec_uchangereghack15 =	$aec_hack_start
			. $aec_global_call
			. $aec_condition_start
			. $aec_include_class
			. $aec_userchange_clause15
			. $aec_condition_end
			. $aec_hack_end;

		$aec_rhackbefore =	$aec_hack_start
			. $aec_global_call
			. $aec_condition_start
			. 'if (!isset($_POST[\'planid\'])) {' . "\n"
			. $aec_include_class
			. 'aecRedirect(JURI::root() . "index.php?option=com_acctexp&amp;task=subscribe");' . "\n"
			. $aec_condition_end
			. $aec_condition_end
			. $aec_hack_end;

		$aec_rhackbefore_fix = str_replace("planid", "usage", $aec_rhackbefore);

		$aec_rhackbefore2 =	$aec_hack_start
			. $aec_global_call . '$app = JFactory::getApplication();' . "\n"
			. $aec_condition_start
			. 'if (!isset($_POST[\'usage\'])) {' . "\n"
			. $aec_include_class
			. 'aecRedirect(JURI::root() . "index.php?option=com_acctexp&amp;task=subscribe");' . "\n"
			. $aec_condition_end
			. $aec_condition_end
			. $aec_hack_end;

		$aec_optionhack =	$aec_hack_start
			. $aec_global_call
			. $aec_condition_start
			. '$option = "com_acctexp";' . "\n"
			. $aec_condition_end
			. $aec_hack_end;

		$aec_regvarshack =	'<?php' . "\n"
			. $aec_hack_start
			. $aec_global_call
			. $aec_condition_start
			. '?>' . "\n"
			. '<input type="hidden" name="planid" value="<?php echo $_POST[\'planid\'];?>" />' . "\n"
			. '<input type="hidden" name="processor" value="<?php echo $_POST[\'processor\'];?>" />' . "\n"
			. '<?php' . "\n"
			. 'if ( isset( $_POST[\'recurring\'] ) ) {'
			. '?>' . "\n"
			. '<input type="hidden" name="recurring" value="<?php echo $_POST[\'recurring\'];?>" />' . "\n"
			. '<?php' . "\n"
			. '}' . "\n"
			. $aec_condition_end
			. $aec_hack_end
			. '?>' . "\n";

		$aec_regvarshack_fix = str_replace( 'planid', 'usage', $aec_regvarshack);

		$aec_regvarshack_fixcb = $aec_hack_start
			. $aec_global_call
			. $aec_condition_start
			. 'if ( isset( $_POST[\'usage\'] ) ) {' . "\n"
			. '$regFormTag .= \'<input type="hidden" name="usage" value="\' . $_POST[\'usage\'] . \'" />\';' . "\n"
			. '}' . "\n"
			. 'if ( isset( $_POST[\'processor\'] ) ) {' . "\n"
			. '$regFormTag .= \'<input type="hidden" name="processor" value="\' . $_POST[\'processor\'] . \'" />\';' . "\n"
			. '}' . "\n"
			. 'if ( isset( $_POST[\'recurring\'] ) ) {' . "\n"
			. '$regFormTag .= \'<input type="hidden" name="recurring" value="\' . $_POST[\'recurring\'] . \'" />\';' . "\n"
			. '}' . "\n"
			. $aec_condition_end
			. $aec_hack_end
		;

		$aec_regredirect = $aec_hack_start
			. $aec_global_call
			. $aec_condition_start
			. $aec_redirect_subscribe
			. $aec_condition_end
			. $aec_hack_end;

		$juser_blind = $aec_hack_start
			. 'case \'blind\':'. "\n"
			. 'break;'. "\n"
			. $aec_hack_end;

		$aec_j15hack1 =  $aec_hack_start
			. 'if ( $error->message == JText::_("ALERTNOTAUTH") ) {'
			. $aec_condition_start
			. $aec_redirect_notallowed15
			. $aec_condition_end
			. $aec_condition_end
			. $aec_hack_end;

		$n = 'errorphp';
		$hacks[$n]['name']			=	'error.php ' . JText::_('AEC_HACK_HACK') . ' #1';
		$hacks[$n]['desc']			=	JText::_('AEC_HACKS_NOTAUTH');
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['filename']		=	JPATH_SITE . '/libraries/joomla/error/error.php';
		$hacks[$n]['read']			=	'// Initialize variables';
		$hacks[$n]['insert']		=	sprintf( $aec_j15hack1, $n, $n ) . "\n" . $hacks[$n]['read'];
		$hacks[$n]['legacy']		=	1;

		$n = 'joomlaphp4';
		$hacks[$n]['name']			=	'authentication.php';
		$hacks[$n]['desc']			=	JText::_('AEC_HACKS_LEGACY_PLUGIN');
		$hacks[$n]['uncondition']	=	'joomlaphp';
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['filename']		=	JPATH_SITE . '/libraries/joomla/user/authentication.php';
		$hacks[$n]['read'] 			=	'if(empty($response->username)) {';
		$hacks[$n]['insert']		=	sprintf($aec_jhack3, $n, $n) . "\n" . $hacks[$n]['read'];
		$hacks[$n]['legacy']		=	1;

		if ( aecComponentHelper::detect_component( 'UHP2' ) ) {
			$n = 'uhp2menuentry';
			$hacks[$n]['name']			=	JText::_('AEC_HACKS_UHP2');
			$hacks[$n]['desc']			=	JText::_('AEC_HACKS_UHP2_DESC');
			$hacks[$n]['uncondition']	=	'uhp2managephp';
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['filename']		=	JPATH_SITE . '/modules/mod_uhp2_manage.php';
			$hacks[$n]['read']			=	'<?php echo "$settings"; ?></a>';
			$hacks[$n]['insert']		=	sprintf( $hacks[$n]['read'] . "\n</li>\n<?php " . $aec_hack_start . '?>'
				. '<li class="latest<?php echo $moduleclass_sfx; ?>">'
				. '<a href="index.php?option=com_acctexp&task=subscriptionDetails" class="latest<?php echo $moduleclass_sfx; ?>">'
				. JText::_('AEC_SPEC_MENU_ENTRY') . '</a>'."\n<?php ".$aec_hack_end."?>", $n, $n );
		}

		if ( aecComponentHelper::detect_component( 'CB1.2' ) ) {
			$n = 'comprofilerphp2';
			$hacks[$n]['name']			=	'comprofiler.php ' . JText::_('AEC_HACK_HACK') . ' #2';
			$hacks[$n]['desc']			=	JText::_('AEC_HACKS_CB2');
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['filename']		=	JPATH_SITE . '/components/com_comprofiler/comprofiler.php';
			$hacks[$n]['read']			=	'function registerForm( $option, $emailpass, $regErrorMSG = null ) {';
			$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf($aec_optionhack, $n, $n);
			$hacks[$n]['legacy']		=	1;

			$n = 'comprofilerphp6';
			$hacks[$n]['name']			=	'comprofiler.php ' . JText::_('AEC_HACK_HACK') . ' #6';
			$hacks[$n]['desc']			=	JText::_('AEC_HACKS_CB6');
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['filename']		=	JPATH_SITE . '/components/com_comprofiler/comprofiler.php';
			$hacks[$n]['read']			=	'HTML_comprofiler::registerForm( $option, $emailpass, $userComplete, $regErrorMSG );';
			$hacks[$n]['insert']		=	sprintf($aec_rhackbefore_fix, $n, $n) . "\n" . $hacks[$n]['read'];
			$hacks[$n]['legacy']		=	1;

			$n = 'comprofilerhtml2';
			$hacks[$n]['name']			=	'comprofiler.html.php ' . JText::_('AEC_HACK_HACK') . ' #2';
			$hacks[$n]['desc']			=	JText::_('AEC_HACKS_CB_HTML2');
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['filename']		=	JPATH_SITE . '/components/com_comprofiler/comprofiler.html.php';
			$hacks[$n]['read']			=	'echo HTML_comprofiler::_cbTemplateRender( $user, \'RegisterForm\'';
			$hacks[$n]['insert']		=	sprintf($aec_regvarshack_fixcb, $n, $n) . "\n" . $hacks[$n]['read'];
			$hacks[$n]['desc']			=	JText::_('AEC_HACKS_LEGACY');
			$hacks[$n]['legacy']		=	1;

		} elseif ( aecComponentHelper::detect_component( 'CB' ) ) {
			$n = 'comprofilerphp2';
			$hacks[$n]['name']			=	'comprofiler.php ' . JText::_('AEC_HACK_HACK') . ' #2';
			$hacks[$n]['desc']			=	JText::_('AEC_HACKS_CB2');
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['filename']		=	JPATH_SITE . '/components/com_comprofiler/comprofiler.php';
			$hacks[$n]['read']			=	'if ($regErrorMSG===null) {';
			$hacks[$n]['insert']		=	sprintf($aec_optionhack, $n, $n) . "\n" . $hacks[$n]['read'];

			$n = 'comprofilerphp6';
			$hacks[$n]['name']			=	'comprofiler.php ' . JText::_('AEC_HACK_HACK') . ' #6';
			$hacks[$n]['desc']			=	JText::_('AEC_HACKS_CB6');
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['condition']		=	'comprofilerphp2';
			$hacks[$n]['uncondition']	=	'comprofilerphp3';
			$hacks[$n]['filename']		=	JPATH_SITE . '/components/com_comprofiler/comprofiler.php';
			$hacks[$n]['read']			=	'HTML_comprofiler::registerForm';
			$hacks[$n]['insert']		=	sprintf($aec_rhackbefore_fix, $n, $n) . "\n" . $hacks[$n]['read'];

			$n = 'comprofilerhtml2';
			$hacks[$n]['name']			=	'comprofiler.html.php ' . JText::_('AEC_HACK_HACK') . ' #2';
			$hacks[$n]['desc']			=	JText::_('AEC_HACKS_CB_HTML2');
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['uncondition']	=	'comprofilerhtml';
			$hacks[$n]['filename']		=	JPATH_SITE . '/components/com_comprofiler/comprofiler.html.php';
			$hacks[$n]['read']			=	'<input type="hidden" name="task" value="saveregisters" />';
			$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf($aec_regvarshack_fix, $n, $n);

		} elseif ( aecComponentHelper::detect_component( 'CBE' ) ) {
			$n = 'comprofilerphp2';
			$hacks[$n]['name']			=	'comprofiler.php ' . JText::_('AEC_HACK_HACK') . ' #2';
			$hacks[$n]['desc']			=	JText::_('AEC_HACKS_CB2');
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['filename']		=	JPATH_SITE . '/components/com_comprofiler/comprofiler.php';
			$hacks[$n]['read']			=	'$rowFieldValues=array();';
			$hacks[$n]['insert']		=	sprintf($aec_optionhack, $n, $n) . "\n" . $hacks[$n]['read'];

			$n = 'comprofilerphp6';
			$hacks[$n]['name']			=	'comprofiler.php ' . JText::_('AEC_HACK_HACK') . ' #6';
			$hacks[$n]['desc']			=	JText::_('AEC_HACKS_CB6');
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['condition']		=	'comprofilerphp2';
			$hacks[$n]['filename']		=	JPATH_SITE . '/components/com_comprofiler/comprofiler.php';
			$hacks[$n]['read']			=	'HTML_comprofiler::registerForm';
			$hacks[$n]['insert']		=	sprintf($aec_rhackbefore2, $n, $n) . "\n" . $hacks[$n]['read'];

			$n = 'comprofilerhtml2';
			$hacks[$n]['name']			=	'comprofiler.html.php ' . JText::_('AEC_HACK_HACK') . ' #2';
			$hacks[$n]['desc']			=	JText::_('AEC_HACKS_CB_HTML2');
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['uncondition']	=	'comprofilerhtml';
			$hacks[$n]['filename']		=	JPATH_SITE . '/components/com_comprofiler/comprofiler.html.php';
			$hacks[$n]['read']			=	'<input type="hidden" name="task" value="saveRegistration" />';
			$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf($aec_regvarshack_fix, $n, $n);
		} elseif ( aecComponentHelper::detect_component( 'JUSER' ) ) {
			$n = 'juserhtml1';
			$hacks[$n]['name']			=	'juser.html.php ' . JText::_('AEC_HACK_HACK') . ' #1';
			$hacks[$n]['desc']			=	JText::_('AEC_HACKS_JUSER_HTML1');
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['filename']		=	JPATH_SITE . '/components/com_juser/juser.html.php';
			$hacks[$n]['read']			=	'<input type="hidden" name="option" value="com_juser" />';
			$hacks[$n]['insert']		=	sprintf($aec_regvarshack_fix, $n, $n) . "\n" . '<input type="hidden" name="option" value="com_acctexp" />';

			$n = 'juserphp1';
			$hacks[$n]['name']			=	'juser.php ' . JText::_('AEC_HACK_HACK') . ' #1';
			$hacks[$n]['desc']			=	JText::_('AEC_HACKS_JUSER_PHP1');
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['filename']		=	JPATH_SITE . '/components/com_juser/juser.php';
			$hacks[$n]['read']			=	'HTML_JUser::userEdit( $row, $option, $params, $ext_row, \'saveUserRegistration\' );';
			$hacks[$n]['insert']		=	sprintf($aec_rhackbefore_fix, $n, $n) . "\n" . $hacks[$n]['read'];

			$n = 'juserphp2';
			$hacks[$n]['name']			=	'juser.php ' . JText::_('AEC_HACK_HACK') . ' #2';
			$hacks[$n]['desc']			=	JText::_('AEC_HACKS_JUSER_PHP2');
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['filename']		=	JPATH_SITE . '/components/com_juser/juser.php';
			$hacks[$n]['read']			=	'default:';
			$hacks[$n]['insert']		=	sprintf($juser_blind, $n, $n) . "\n" . $hacks[$n]['read'];
		} else {

			$n = 'registrationhtml2';
			$hacks[$n]['name']			=	'registration.html.php ' . JText::_('AEC_HACK_HACK') . ' #2';
			$hacks[$n]['desc']			=	JText::_('AEC_HACKS_LEGACY');
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['uncondition']	=	'registrationhtml';
			$hacks[$n]['condition']		=	'registrationphp2';
			$hacks[$n]['filename']		=	JPATH_SITE . '/components/com_user/views/register/tmpl/default.php';
			$hacks[$n]['read']			=	'<input type="hidden" name="task" value="register_save" />';
			$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf($aec_regvarshack_fix, $n, $n);
			$hacks[$n]['legacy']		=	1;

			$n = 'registrationphp6';
			$hacks[$n]['name']			=	'user.php';
			$hacks[$n]['desc']			=	JText::_('AEC_HACKS_REG5');
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['uncondition']	=	'registrationphp5';
			$hacks[$n]['filename']		=	JPATH_SITE . '/components/com_user/controller.php';
			$hacks[$n]['read']			=	'JRequest::setVar(\'view\', \'register\');';
			$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf($aec_regredirect, $n, $n);
			$hacks[$n]['legacy']		=	1;
		}

		if ( aecComponentHelper::detect_component( 'anyCB' ) ) {
			if ( aecComponentHelper::detect_component( 'CB1.2' ) ) {
				$n = 'comprofilerphp7';
				$hacks[$n]['name']			=	'comprofiler.php ' . JText::_('AEC_HACK_HACK') . ' #7';
				$hacks[$n]['desc']			=	JText::_('AEC_HACKS_MI1');
				$hacks[$n]['type']			=	'file';
				$hacks[$n]['filename']		=	JPATH_SITE . '/components/com_comprofiler/comprofiler.php';
				$hacks[$n]['read']			=	'$_PLUGINS->trigger( \'onAfterUserRegistrationMailsSent\',';
				$hacks[$n]['insert']		=	sprintf( $aec_uchangehackCB12, $n, 'registration', $n ) . "\n" . $hacks[$n]['read'];
				$hacks[$n]['legacy']		=	1;

				$n = 'comprofilerphp8';
				$hacks[$n]['name']			=	'comprofiler.php ' . JText::_('AEC_HACK_HACK') . ' #8';
				$hacks[$n]['desc']			=	JText::_('AEC_HACKS_MI1');
				$hacks[$n]['type']			=	'file';
				$hacks[$n]['filename']		=	JPATH_SITE . '/administrator/components/com_comprofiler/library/cb/cb.tables.php';
				$hacks[$n]['read']			=	'$_PLUGINS->trigger( \'onAfterUserUpdate\', array( &$this, &$this, true ) );';
				$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf( $aec_uchangehackCB12x, $n, 'user', $n );
				$hacks[$n]['legacy']		=	1;
			} else {
				$n = 'comprofilerphp4';
				$hacks[$n]['name']			=	'comprofiler.php ' . JText::_('AEC_HACK_HACK') . ' #4';
				$hacks[$n]['desc']			=	JText::_('AEC_HACKS_MI1');
				$hacks[$n]['type']			=	'file';
				$hacks[$n]['filename']		=	JPATH_SITE . "/components/com_comprofiler/comprofiler.php";
				$hacks[$n]['read']			=	'$_PLUGINS->trigger( \'onAfterUserRegistrationMailsSent\',';
				$hacks[$n]['insert']		=	sprintf($aec_uchangehack, $n, "user", $n) . "\n" . $hacks[$n]['read'];
				$hacks[$n]['legacy']		=	1;

				$n = 'comprofilerphp5';
				$hacks[$n]['name']			=	'comprofiler.php ' . JText::_('AEC_HACK_HACK') . ' #5';
				$hacks[$n]['desc']			=	JText::_('AEC_HACKS_MI2');
				$hacks[$n]['type']			=	'file';
				$hacks[$n]['filename']		=	JPATH_SITE . "/components/com_comprofiler/comprofiler.php";
				$hacks[$n]['read']			=	'$_PLUGINS->trigger( \'onAfterUserUpdate\', array($row, $rowExtras, true));';
				$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf($aec_uchangehack, $n, "registration",$n);
				$hacks[$n]['legacy']		=	1;

				$n = 'comprofilerphp7';
				$hacks[$n]['name']			=	'comprofiler.php ' . JText::_('AEC_HACK_HACK') . ' #7';
				$hacks[$n]['desc']			=	JText::_('AEC_HACKS_MI1');
				$hacks[$n]['type']			=	'file';
				$hacks[$n]['uncondition']	=	'comprofilerphp4';
				$hacks[$n]['filename']		=	JPATH_SITE . '/components/com_comprofiler/comprofiler.php';
				$hacks[$n]['read']			=	'$_PLUGINS->trigger( \'onAfterUserRegistrationMailsSent\',';
				$hacks[$n]['insert']		=	sprintf( $aec_uchangehack, $n, 'registration', $n ) . "\n" . $hacks[$n]['read'];

				$n = 'comprofilerphp8';
				$hacks[$n]['name']			=	'comprofiler.php ' . JText::_('AEC_HACK_HACK') . ' #8';
				$hacks[$n]['desc']			=	JText::_('AEC_HACKS_MI1');
				$hacks[$n]['type']			=	'file';
				$hacks[$n]['uncondition']	=	'comprofilerphp5';
				$hacks[$n]['filename']		=	JPATH_SITE . '/components/com_comprofiler/comprofiler.php';
				$hacks[$n]['read']			=	'$_PLUGINS->trigger( \'onAfterUserUpdate\', array($row, $rowExtras, true));';
				$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf( $aec_uchangehack, $n, 'user', $n );
			}
		} else {
			$n = 'userphp';
			$hacks[$n]['name']			=	'user.php';
			$hacks[$n]['desc']			=	JText::_('AEC_HACKS_LEGACY');
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['filename']		=	JPATH_SITE . '/components/com_user/controller.php';
			$hacks[$n]['read']			=	'if ($model->store($post)) {';
			$hacks[$n]['insert']		=	sprintf( $aec_uchangehack15, $n, "user", $n ) . "\n" . $hacks[$n]['read'];
			$hacks[$n]['legacy']		=	1;

			$n = 'registrationphp1';
			$hacks[$n]['name']			=	'registration.php ' . JText::_('AEC_HACK_HACK') . ' #1';
			$hacks[$n]['desc']			=	JText::_('AEC_HACKS_LEGACY');
			$hacks[$n]['type']			=	'file';
			$hacks[$n]['filename']		=	JPATH_SITE . '/components/com_user/controller.php';
			$hacks[$n]['read']			=	'UserController::_sendMail($user, $password);';
			$hacks[$n]['insert']		=	$hacks[$n]['read'] . "\n" . sprintf( $aec_uchangereghack15, $n, "registration", $n );
			$hacks[$n]['legacy']		=	1;
		}

		$n = 'adminuserphp';
		$hacks[$n]['name']			=	'admin.user.php';
		$hacks[$n]['desc']			=	JText::_('AEC_HACKS_LEGACY');
		$hacks[$n]['type']			=	'file';
		$hacks[$n]['filename']		=	JPATH_SITE . '/administrator/components/com_users/controller.php';
		$hacks[$n]['read']			=	'if (!$user->save())';
		$hacks[$n]['insert']		=	sprintf( $aec_uchangehack15, $n, 'adminuser', $n ) . "\n" . $hacks[$n]['read'];
		$hacks[$n]['legacy']	=	1;

		if ( aecComponentHelper::detect_component( 'CBM' ) ) {
			if ( !aecComponentHelper::detect_component( 'CB1.2' ) ) {
				$n = 'comprofilermoderator';
				$hacks[$n]['name']			=	'comprofilermoderator.php';
				$hacks[$n]['desc']			=	JText::_('AEC_HACKS_CBM');
				$hacks[$n]['type']			=	'file';
				$hacks[$n]['filename']		=	JPATH_SITE . '/modules/mod_comprofilermoderator.php';
				$hacks[$n]['read']			=	'mosNotAuth();';
				$hacks[$n]['insert']		=	sprintf( $aec_cbmhack, $n, $n );
			}
		}

		$mih = new microIntegrationHandler();
		$new_hacks = $mih->getHacks();

		if ( is_array( $new_hacks ) ) {
			$hacks = array_merge( $hacks, $new_hacks );
		}

		// Receive the status for the hacks
		foreach ( $hacks as $name => $hack ) {

			$hacks[$name]['status'] = 0;

			if ( !empty( $hack['filename'] ) ) {
				if ( !file_exists( $hack['filename'] ) ) {
					continue;
				}
			}

			if ( $hack['type'] ) {
				switch( $hack['type'] ) {
					case 'file':
						if ( $hack['filename'] != 'UNKNOWN' ) {
							$originalFileHandle = fopen( $hack['filename'], 'r' );
							$oldData			= fread( $originalFileHandle, filesize($hack['filename'] ) );
							fclose( $originalFileHandle );

							if ( strpos( $oldData, 'AEC HACK START' ) || strpos( $oldData, 'AEC CHANGE START' )) {
								$hacks[$name]['status'] = 'legacy';
							} else {
								if ( ( strpos( $oldData, 'AEC HACK ' . $name . ' START' ) > 0 ) || ( strpos( $oldData, 'AEC CHANGE ' . $name . ' START' ) > 0 )) {
									$hacks[$name]['status'] = 1;
								}
							}

							if ( function_exists( 'posix_getpwuid' ) ) {
								$hacks[$name]['fileinfo'] = posix_getpwuid( fileowner( $hack['filename'] ) );
							}
						}
						break;

					case 'menuentry':
						$query = 'SELECT COUNT(*)'
							. ' FROM #__menu'
							. ' WHERE `link` = \'' . JURI::root()  . '/index.php?option=com_acctexp&task=subscriptionDetails\''
						;
						$db->setQuery( $query );
						$count = $db->loadResult();

						if ( $count ) {
							$hacks[$name]['status'] = 1;
						}
						break;
				}
			}
		}

		if ( $checkonly ) {
			return $hacks[$filename]['status'];
		}

		// Commit the hacks
		if ( !$check_hack ) {

			switch( $hacks[$filename]['type'] ) {
				case 'file':
					// mic: fix if CMS is not Joomla or Mambo
					if ( $hack['filename'] != 'UNKNOWN' ) {
						$originalFileHandle = fopen( $hacks[$filename]['filename'], 'r' ) or die ("Cannot open ".$hacks[$filename]['filename']);
						// Transfer File into variable $oldData
						$oldData = fread( $originalFileHandle, filesize( $hacks[$filename]['filename'] ) );
						fclose( $originalFileHandle );

						if ( !$undohack ) { // hack
							$newData = str_replace( $hacks[$filename]['read'], $hacks[$filename]['insert'], $oldData );

							//make a backup
							if ( !$this->backupFile( $hacks[$filename]['filename'], $hacks[$filename]['filename'] . '.aec-backup' ) ) {
								// Echo error message
							}

						} else { // undo hack
							if ( strcmp( $hacks[$filename]['status'], 'legacy' ) === 0 ) {
								$newData = preg_replace( '/\/\/.AEC.(HACK|CHANGE).START\\n.*\/\/.AEC.(HACK|CHANGE).END\\n/s', $hacks[$filename]['read'], $oldData );
							} else {
								if ( strpos( $oldData, $hacks[$filename]['insert'] ) ) {
									if ( isset( $hacks[$filename]['oldread'] ) && isset( $hacks[$filename]['oldinsert'] ) ) {
										$newData = str_replace( $hacks[$filename]['oldinsert'], $hacks[$filename]['oldread'], $oldData );
									}

									$newData = str_replace( $hacks[$filename]['insert'], $hacks[$filename]['read'], $oldData );
								} else {
									$newData = preg_replace( '/\/\/.AEC.(HACK|CHANGE).' . $filename . '.START\\n.*\/\/.AEC.(HACK|CHANGE).' . $filename . '.END\\n/s', $hacks[$filename]['read'], $oldData );
								}
							}
						}

						$oldperms = fileperms( $hacks[$filename]['filename'] );
						chmod( $hacks[$filename]['filename'], $oldperms | 0222 );

						if ( $fp = fopen( $hacks[$filename]['filename'], 'wb' ) ) {
							fwrite( $fp, $newData, strlen( $newData ) );
							fclose( $fp );
							chmod( $hacks[$filename]['filename'], $oldperms );
						}
					}
					break;
			}
		}

		return $hacks;
	}

	function backupFile( $file, $file_new )
	{
		if ( !copy( $file, $file_new ) ) {
			return false;
		}
		return true;
	}
}


class aecAdminToolbox extends aecAdminEntity
{
	public function toolBoxTool( $cmd )
	{
		$path = JPATH_SITE . '/components/com_acctexp/toolbox';

		if ( empty( $cmd ) ) {
			$list = array();

			$files = xJUtility::getFileArray( $path, 'php', false, true );

			asort( $files );

			foreach ( $files as $n => $name ) {
				$file = $path . '/' . $name;

				include_once $file;

				$class = str_replace( '.php', '', $name );

				$tool = new $class();

				if ( !method_exists( $tool, 'Info' ) ) {
					continue;
				}

				$info = $tool->Info();

				$info['link'] = AECToolbox::deadsureURL( 'administrator/index.php?option=com_acctexp&task=toolbox&cmd=' . $class );

				$list[] = $info;
			}

			HTML_AcctExp::toolBox( '', $list );
		} else {
			$file = $path . '/' . $cmd . '.php';

			include_once $file;

			$tool = new $cmd();

			$info = $tool->Info();

			$return = '';
			if ( !method_exists( $tool, 'Action' ) ) {
				$return .= '<section class="paper">' . '<p>Tool doesn\'t have an action to carry out!</p>' . '</section>';
			} else {
				$response = '</section><section class="paper"><h4>' . JText::_('Response') . '</h4>' . $tool->Action() . '</section>';

				if ( method_exists( $tool, 'Settings' ) ) {
					$tb_settings = $tool->Settings();

					if ( !empty( $tb_settings ) ) {

						$lists = array();
						if ( isset( $tb_settings['lists'] ) ) {
							$lists = $tb_settings['lists'];

							unset( $tb_settings['lists'] );
						}

						// Get preset values from POST
						foreach ( $tb_settings as $n => $v ) {
							if ( isset( $_POST[$n] ) ) {
								$tb_settings[$n][3] = $_POST[$n];
							}
						}

						$settings = new aecSettings( 'TOOLBOX', 'E' );
						$settings->fullSettingsArray( $tb_settings, array(), $lists );

						// Call HTML Class
						$aecHTML = new aecHTML( $settings->settings, $settings->lists );

						foreach ( $tb_settings as $n => $v ) {
							$return .= $aecHTML->createSettingsParticle( $n );
						}

						$return .= '<input type="submit" class="btn btn-primary pull-right"/><br/><br/>';
					}
				}

				$return .= $response;
			}

			HTML_AcctExp::toolBox( $cmd, $return, $info['name'] );
		}
	}
}
