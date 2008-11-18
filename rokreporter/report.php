<?php
/**
 * @version		$Id: report.php 3 2008-02-09 12:01:48Z Shiny Black Shoe $
 * @package		RokReporter
 * @copyright	(C) 2007, Shiny Black Shoe. Modified from RokReporter inbuilt reports
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */
 //updated for AEC releases > 0.12.4.11 which no longer have #__acctexp table

// ensure this file is being included by a parent file
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct access not allowed' );

/**
 * Reporting class
 * @package		RokReporter
 */
class AEC_subscribers_Report extends Report
{
	/** @var string The report id (folder name); */
	var $id = 'AEC_subscribers';

	function ExportToCSV( $data )
	{
		$CRLF = "\n";
		$nRows = count( $data );
		if ($nRows > 0) {
			$nCols = count( $data[0] );
			$lines = array();
			$labels = array();
			$header = array();

				foreach ($data[0] as $key => $value)
				{
					$labels[$key]='"'.$key.'"';
				}
				$header[] = implode(',',$labels);

			for ($row = 0; $row < $nRows; $row++) {
				foreach ($data[$row] as $key => $value) {
					$cell = $data[$row][$key];
				//	echo $key.":".$value;
					$cell = str_replace( "\r\n", "\r", $cell );
					$cell = str_replace( "\r", "\n", $cell );
					$cell = str_replace( "\n", ";", $cell );
					$cell = str_replace( '"', "'", $cell );
					$cell = '"'.$cell.'"';
				//	echo $cell;
					$data[$row][$key]=$cell;
				}
				$lines[] = implode( ',', $data[$row] );
				//echo "<br />";
			}
			$lines=array_merge($header,$lines);
			return implode( $CRLF, $lines );

		}
		return '';
	}

	/**
	 * Runs the report
	 * @param array An array of system variables
	 */
	function run( &$vars )
	{
		// set the default ordering
		if (empty( $vars['orderCol'] )) {
			$vars['orderCol'] = 'search_term';
		}

		global $mosConfig_absolute_path;
		$database = &$this->getDBO();
		$query = "";
		$query = "SELECT DISTINCT(#__users.id), #__users.*, ";

		// Check if CB installed...
		if (file_exists( $mosConfig_absolute_path . "/components/com_comprofiler/comprofiler.php"))  {
			$query .= "#__comprofiler.*, ";
		}

		$query .= "#__acctexp_subscr.* ";
		$query .= "FROM #__users ";
		// $query .= "LEFT JOIN #__acctexp ON #__users.id = #__acctexp.userid ";
		$query .= "LEFT JOIN #__acctexp_subscr ON #__users.id = #__acctexp_subscr.userid ";

		// Check if CB installed...
		if (file_exists( $mosConfig_absolute_path . "/components/com_comprofiler/comprofiler.php")) {
			$query .= "LEFT JOIN #__comprofiler ON #__users.id = #__comprofiler.user_id ";
		}

		$query .= "GROUP BY #__users.id";

		$database->setQuery( $query );
		$rows = $database->loadAssocList();  //this is exporting data with column heading
		$buffer = trim( $this->ExportToCSV( $rows ) );

		$filename = "AEC_subscribers";

                header("Content-type: application/force-download");
		        header( "Content-type: application/octet-stream" );
                header  (  "Content-Type:  application/download"  );
                header  (  "Content-Type:  text/csv"  );
		        header( "Content-Length: ".strlen( $buffer ) );
		        header( "Content-disposition: attachment; filename=$filename-".date("Y-m-d").".csv" );
		        header( "Pragma: no-cache" );
		        header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		        header( "Expires: 0" );
		       echo $buffer;
		        exit( 0 );
		}
}

?>
