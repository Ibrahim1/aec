<?php
/**
 * @version $Id: tool_pretend.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Toolbox - Pretend
 * @copyright 2011-2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class tool_pretend
{
	function Info()
	{
		$info = array();
		$info['name'] = "Pretend";
		$info['desc'] = "Create artificial membership and sales data.";

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['plans']			= array( 'inputB', 'Plans', 'Number of Membership Plans', 10 );
		$settings['groups']			= array( 'inputB', 'Groups', 'Number of Membership Plan Groups', 3 );
		$settings['users']			= array( 'inputB', 'Users', 'Number of Users', 25000 );
		
		$settings['start']			= array( 'inputB', 'Start', 'Start of business', date('Y')-6 );

		return $settings;
	}

	function Action()
	{
		$db = &JFactory::getDBO();

		// Create a number of groups
		$grouplist = $this->createGroups( $_POST['groups'] );
		
		// Create a number of plans
		$planlist = $this->createPlans( $grouplist, $_POST['plans'] );

		// Create Users
		$userlist = $this->createUsers( $_POST['users'] );

		// Create Payments
		$paymentlist = $this->createPayments( $grouplist );

		// Store some data so we can delete fake entries later on, if needed
		$data = array(
						'range_group' => array( $this->range['groups']['start'], $this->range['groups']['end'] ),
						'range_plans' => array( $this->range['plans']['start'], $this->range['plans']['end'] ),
						'range_users' => array( $this->range['users']['start'], $this->range['users']['end'] ),
						'range_payments' => array( $this->range['payments']['start'], $this->range['payments']['end'] )
		);

		$bucket = new aecBucket( $db );
		$bucket->stuff( 'tool_pretend', $data );
	}

	function createPayments( $planrange, $userrange )
	{
		
	}

	function createPlans( $grouplist, $plans )
	{
		$class = array( 'copper', 'silver', 'titanium', 'gold', 'platinum', 'diamond' );
		$color = array( 'azure', 'scarlet', 'jade', 'lavender', 'mustard' );

		$offset = ( rand(0, (count($class)+count($color))) );

		for ( $i=0; $i<$plans; $i++ ) {
			if ( $plans > count($class) ) {
				
			}

			$name = "Membership";

			$offset++;
		}
	}

	function createGroups( $amount )
	{
		$db = &JFactory::getDBO();

		$grouplist = array();
		for ( $i=0; $i<=$amount; $i++ ) {
			$row = new ItemGroup( $db );

			$post = array(
							'name' => 'Group '.($i+1),
							'desc' => 'Group '.($i+1)
			);

			$row->savePOSTsettings( $post );
			$row->storeload();

			ItemGroupHandler::setChildren( 1, array($row->id), $type='item' );

			if ( $i == 0 ) {
				$this->range['groups']['start'] = $row->id;
			} elseif ( $i == $amount ) {
				$this->range['groups']['end'] = $row->id;
			}

			$grouplist[] = $row->id;
		}

		return $grouplist;
	}

	function createUsers( $amount )
	{
		$db = &JFactory::getDBO();

		$userlist = array();
		for ( $i=0; $i<=$amount; $i++ ) {
			$rname = $this->getRandomName();

			$var = array(	'username' => $this->generateUsername( $rname ),
							'password' => 'password',
							'password2' => 'password',
							'email' => $this->generateEmail( $rname ),
							'name' => $rname,
							);


			$userid = AECToolbox::saveUserRegistration( 'com_acctexp', $var, true, true, true, true );

			$userlist[] = $userid;

			$row = new JTableUser( $db );

			$post = array(
							'name' => 'Group '.($i+1),
							'desc' => 'Group '.($i+1)
			);

			$row->savePOSTsettings( $post );
			$row->storeload();

			ItemGroupHandler::setChildren( 1, array($row->id), $type='item' );

			if ( $i == 0 ) {
				$this->range['groups']['start'] = $row->id;
			} elseif ( $i == $amount ) {
				$this->range['groups']['end'] = $row->id;
			}
		}
	}

	function getRandomName()
	{
		$male = array(	"John","William","James","George","Charles",
						"Frank","Joseph","Henry","Robert","Thomas",
						"Edward","Harry","Walter","Arthur","Fred",
						"Albert","Samuel","Clarence","Louis","David",
						"Joe","Charlie","Richard","Ernest","Roy",
						"Will","Andrew","Jesse","Oscar","Willie",
						"Daniel","Benjamin","Carl","Sam","Alfred",
						"Earl","Peter","Elmer","Frederick","Howard",
						"Lewis","Ralph","Herbert","Paul","Lee",
						"Tom","Herman","Martin","Jacob","Michael",
						"Jim","Claude","Ben","Eugene","Francis",
						"Grover","Raymond","Harvey","Clyde","Edwin",
						"Edgar","Ed","Lawrence","Bert","Chester",
						"Jack","Otto","Luther","Charley","Guy",
						"Floyd","Ira","Ray","Hugh","Isaac",
						"Oliver","Patrick","Homer","Theodore","Leonard",
						"Leo","Alexander","August","Harold","Allen",
						"Jessie","Archie","Philip","Stephen","Horace",
						"Marion","Bernard","Anthony","Julius","Warren",
						"Leroy","Clifford","Eddie","Sidney","Milton");

		$female = array("Mary","Anna","Emma","Elizabeth","Margaret",
						"Minnie","Ida","Bertha","Clara","Alice",
						"Annie","Florence","Bessie","Grace","Ethel",
						"Sarah","Ella","Martha","Nellie","Mabel",
						"Laura","Carrie","Cora","Helen","Maude",
						"Lillian","Gertrude","Rose","Edna","Pearl",
						"Edith","Jennie","Hattie","Mattie","Eva",
						"Julia","Myrtle","Louise","Lillie","Jessie",
						"Frances","Catherine","Lula","Lena","Marie",
						"Ada","Josephine","Fannie","Lucy","Dora",
						"Agnes","Maggie","Blanche","Katherine","Elsie",
						"Nora","Mamie","Rosa","Stella","Daisy",
						"May","Effie","Mae","Ellen","Nettie",
						"Ruth","Alma","Della","Lizzie","Sadie",
						"Sallie","Nancy","Susie","Maud","Flora",
						"Irene","Etta","Katie","Lydia","Lottie",
						"Viola","Caroline","Addie","Hazel","Georgia",
						"Esther","Mollie","Olive","Willie","Harriet",
						"Emily","Charlotte","Amanda","Kathryn","Lulu",
						"Susan","Kate","Nannie","Jane","Amelia");

		$surnames = array("Smith","Johnson","Williams","Brown","Jones",
						"Miller","Davis","Garcia","Rodriguez","Wilson",
						"Martinez","Anderson","Taylor","Thomas","Hernandez",
						"Moore","Martin","Jackson","Thompson","White",
						"Lopez","Lee","Gonzalez","Harris","Clark",
						"Lewis","Robinson","Walker","Perez","Hall",
						"Young","Allen","Sanchez","Wright","King",
						"Scott","Green","Baker","Adams","Nelson",
						"Hill","Ramirez","Campbell","Mitchell","Roberts",
						"Carter","Phillips","Evans","Turner","Torres",
						"Parker","Collins","Edwards","Stewart","Flores",
						"Morris","Nguyen","Murphy","Rivera","Cook",
						"Rogers","Morgan","Peterson","Cooper","Reed",
						"Bailey","Bell","Gomez","Kelly","Howard",
						"Ward","Cox","Diaz","Richardson","Wood",
						"Watson","Brooks","Bennett","Gray","James",
						"Reyes","Cruz","Hughes","Price","Myers",
						"Long","Foster","Sanders","Ross","Morales",
						"Powell","Sullivan","Russell","Ortiz","Jenkins",
						"Gutierrez","Perry","Butler","Barnes","Fisher");

		// Feminist code is feminist
		$gender = rand(0, 1);

		$sno = rand(1, 4);

		$name = array();
		for ( $j=0; $j<$sno; $j++ ) {
			$rname = rand(0, 99);

			if ( $gender ) {
				$name[] = $female[$rname];
			} else {
				$name[] = $male[$rname];
			}
		}

		$rname = rand(0, 99);

		$name[] = $surnames[$rname];

		return implode( " ", $name );
	}

	function generateUsername( $seed )
	{
		$db = &JFactory::getDBO();

		$numberofrows	= 1;
		while ( $numberofrows ) {
			$username = strtolower( str_replace( " ", "", $seed )  );

			$query = 'SELECT count(*)'
					. ' FROM #__users'
					. ' WHERE LOWER( `username` ) = \'%' . $username . '%\''
					;
			$db->setQuery( $query );
			$numberofrows = $db->loadResult();
		}

		return $username;
	}

	function generateEmail( $seed )
	{
		$db = &JFactory::getDBO();

		$domains = array(	'yahoo.com','hotmail.com','aol.com','gmail.com','msn.com',
							'comcast.net','hotmail.co.uk','sbcglobal.net','yahoo.co.uk','yahoo.co.in',
							'bellsouth.net','verizon.net','earthlink.net','cox.net','rediffmail.com',
							'yahoo.ca','btinternet.com','charter.net','shaw.ca','ntlworld.com' );

		$numberofrows	= 1;
		while ( $numberofrows ) {
			$email = strtolower( str_replace( " ", rand(11111, 99999), $seed )  ) . rand(11, 999) . '@' . $domains[rand(0, 19)];

			$query = 'SELECT count(*)'
					. ' FROM #__users'
					. ' WHERE LOWER( `email` ) = \'%' . $email . '%\''
					;
			$db->setQuery( $query );
			$numberofrows = $db->loadResult();
		}

		return $email;
	}

	/* Inspired by d3 examples, in turn inspired by Lee Byron's test data generator. */
	function stream_layers( $layers, $samples, $step )
	{
		foreach ( $layers as $lid ) {
			$a = array();

			for ( $i=0; $i<$samples; $i++ ) {
				$a[$i] = $step + $step * rand();
			}

			for ( $i=0; $i<5; $i++) {
				$a = $this->bump( $a, $samples );
			}

			return a.map(stream_index);
		}

		return $layers;
	}

	function bump( $array, $samples )
	{
		$x = 1 / (.1 + rand() );
		$y = 2 * rand() - 0.5;
		$z = 10 / (0.1 + rand());

		for ( $i=0; $i<$samples; $i++ ) {
			$w = ($i / $samples - $y) * $z;
			$array[$i] += $x * exp(-$w * $w);
		}

		return $array;
	}
}
?>
