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
		$settings['min_payments']	= array( 'inputB', 'Min Payments per User', 'Every user has at least this many payments', 1 );
		$settings['max_payments']	= array( 'inputB', 'Max Payments per User', 'Every user has at most this many payments', 10 );
		
		$settings['start']			= array( 'inputB', 'Start', 'Start of business', date('Y')-10 );

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
		$userlist = $this->createUsers( $grouplist, $_POST['users'] );

		// Create Payments
		$paymentlist = $this->createPayments( $grouplist, $_POST['min_payments'], $_POST['max_payments'] );

		// Store some data so we can delete fake entries later on
		$data = array();

		$bucket = new aecBucket( $db );
		$bucket->stuff( 'tool_pretend', $data );
	}

	function createPlans( $plans )
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

	function getRandomNames( $amount )
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
						"Miller","Davis","García","Rodríguez","Wilson",
						"Martínez","Anderson","Taylor","Thomas","Hernández",
						"Moore","Martin","Jackson","Thompson","White",
						"López","Lee","González","Harris","Clark",
						"Lewis","Robinson","Walker","Pérez","Hall",
						"Young","Allen","Sánchez","Wright","King",
						"Scott","Green","Baker","Adams","Nelson",
						"Hill","Ramírez","Campbell","Mitchell","Roberts",
						"Carter","Phillips","Evans","Turner","Torres",
						"Parker","Collins","Edwards","Stewart","Flores",
						"Morris","Nguyen","Murphy","Rivera","Cook",
						"Rogers","Morgan","Peterson","Cooper","Reed",
						"Bailey","Bell","Gómez","Kelly","Howard",
						"Ward","Cox","Díaz","Richardson","Wood",
						"Watson","Brooks","Bennett","Gray","James",
						"Reyes","Cruz","Hughes","Price","Myers",
						"Long","Foster","Sanders","Ross","Morales",
						"Powell","Sullivan","Russell","Ortiz","Jenkins",
						"Gutiérrez","Perry","Butler","Barnes","Fisher");

		$namelist = array();
		for ( $i=0; $i<$amount; $i++ ) {
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

			$namelist[] = implode( " ", $name );
		}

		return $namelist;
	}

}
?>
