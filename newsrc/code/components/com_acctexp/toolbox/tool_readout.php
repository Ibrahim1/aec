<?php
/**
 * @version $Id: tool_readout.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Toolbox - Readout
 * @copyright 2011-2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class tool_readout
{
	function Info()
	{
		$info = array();
		$info['name'] = "Readout";
		$info['desc'] = "Gives you an overview of all the settings in the system.";

		return $info;
	}

	function options()
	{
		return array(
							'show_settings' => 0,
							'show_extsettings' => 0,
							'show_processors' => 0,
							'show_plans' => 1,
							'show_mi_relations' => 1,
							'show_mis' => 1,
							'truncation_length' => 42,
							'noformat_newlines' => 0,
							'use_ordering' => 0,
							'column_headers' => 20,
							'export_csv' => 0,
							'store_settings' => 1
						);
	}

	function Settings()
	{
		$optionlist = $this->options();

		$user = &JFactory::getUser();

		$metaUser = new metaUser( $user->id );
		if ( isset( $metaUser->meta->custom_params['aecadmin_readout'] ) ) {
			$prefs = $metaUser->meta->custom_params['aecadmin_readout'];
		} else {
			$prefs = array();
		}

		$settings = array();
		foreach ( $optionlist as $opt => $optdefault ) {
			if ( isset( $prefs[$opt] ) ) {
				$optval = $prefs[$opt];
			} else {
				$optval = $optdefault;
			}

			if ( ( $optdefault == 1 ) || ( $optdefault == 0 ) ) {
				$settings[$opt] = array( 'toggle', $optval );
			} else {
				$settings[$opt] = array( 'inputB', $optval );
			}
		}

		return $settings;
	}

	function Action()
	{
		$optionlist = $this->options();

		if ( !empty( $_POST['export_csv'] ) ) {
			$method = "csv";
		} else {
			$method = "html";
		}

		$r = array();
		$readout = new aecReadout( $optionlist, $method );

		foreach ( $optionlist as $opt => $odefault ) {
			if ( !$_POST[$opt] ) {
				continue;
			}

			switch ( $opt ) {
				case 'show_settings':
					$s = $readout->readSettings();
					break;
				case 'show_processors':
					$s = $readout->readProcessors();
					break;
				case 'show_plans':
					$s = $readout->readPlans();
					break;
				case 'show_mi_relations':
					$s = $readout->readPlanMIrel();
					break;
				case 'show_mis':
					$s = $readout->readMIs();
					break;
				case 'store_settings':
					$user = &JFactory::getUser();

					$settings = array();
					foreach ( $optionlist as $opt => $optdefault ) {
						if ( !empty( $_POST[$opt] ) ) {
							$settings[$opt] = $_POST[$opt];
						} else {
							$settings[$opt] = 0;
						}
					}

					$metaUser = new metaUser( $user->id );
					$metaUser->meta->addCustomParams( array( 'aecadmin_readout' => $settings ) );
					$metaUser->meta->storeload();
					continue 2;
					break;
				default:
					continue 2;
					break;
			}

			if ( isset( $s['def'] ) ) {
				$r[] = $s;
			} elseif ( is_array( $s ) ) {
				foreach ( $s as $i => $x ) {
					$r[] = $x;
				}
			}
		}

		if ( !empty( $_POST['export_csv'] ) ) {
			HTML_AcctExp::readoutCSV( $r );
		} else {
			HTML_AcctExp::readout( $r );
		}

		return $return;
	}

	function readout(  $readout )
	{
		HTML_myCommon::addReadoutCSS();
		HTML_myCommon::startCommon( 'aec_wrap_over' );

		if ( isset( $_POST['column_headers'] ) ) {
			$ch = $_POST['column_headers'];
		} else {
			$ch = 20;
		}

		?>
		<table class="aec_bg aecadminform"><tr><td>
			<?php foreach ( $readout as $part ) { ?>
				<?php
				echo '<div class="aec_userinfobox_sub">';

				if ( !empty( $part['head'] ) ) {
					if ( !empty( $part['sub'] ) ) {
						echo "<h2>" . $part['head'] . "</h2>";
					} else {
						echo "<h1>" . $part['head'] . "</h1>";
					}
				}

				if ( !empty( $part['type'] ) ) {
				switch ( $part['type'] ) {
					case 'table':
						echo "<table class=\"aec_readout_bit\">";

						$i = 0; $j = 0;
						foreach ( $part['set'] as $entry ) {
							if ( $j%$ch == 0 ) {
								echo "<tr>";
								$k = 0;
								foreach ( $part['def'] as $def => $dc ) {
									if ( is_array( $dc[0] ) ) {
										$dn = $dc[0][0].'_'.$dc[0][1];
									} else {
										$dn = $dc[0];
									}

									echo "<th class=\"col".$k." ".$dn."\">" . $def . "</th>";
									$k = $k ? 0 : 1;
								}
								echo "</tr>";
							}

							echo "<tr class=\"row".$i."\">";

							foreach ( $part['def'] as $def => $dc ) {
								if ( is_array( $dc[0] ) ) {
									$dn = $dc[0][0].'_'.$dc[0][1];
								} else {
									$dn = $dc[0];
								}

								$tdclass = $dn;

								if ( isset( $entry[$dn] ) ) {
									$dcc = $entry[$dn];
								} else {
									$dcc = "";
								}

								if ( isset( $dc[1] ) ) {
									$types = explode( ' ', $dc[1] );

									foreach ( $types as $tt ) {
										switch ( $tt ) {
											case 'bool';
												$dcc = $dcc ? 'Yes' : 'No';
												$tdclass .= " bool".$dcc;
												break;
										}
									}
								} else {
									if ( is_array( $dcc ) ) {
										$dcc = implode( ', ', $dcc );
									}
								}

								echo "<td class=\"".$tdclass."\">" . $dcc . "</td>";
							}

							echo "</tr>";

							$i = $i ? 0 : 1;
							$j++;
						}

						echo "</table>";
						break;
				}
				} ?>
				</div>
			<?php } ?>
		</td></tr></table>
		<?php

 		HTML_myCommon::endCommon();
	}

	function readoutCSV( $readout )
	{
		// Send download header
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");

		header("Content-Type: application/download");
		header('Content-Disposition: inline; filename="aec_readout.csv"');

		// Load Exporting Class
		$filename = JPATH_SITE . '/components/com_acctexp/lib/export/csv.php';
		$classname = 'AECexport_csv';

		include_once( $filename );

		$exphandler = new $classname();


		if ( isset( $_POST['column_headers'] ) ) {
			$ch = $_POST['column_headers'];
		} else {
			$ch = 20;
		}

		foreach ( $readout as $part ) {
			if ( !empty( $part['head'] ) ) {
				echo $exphandler->export_line( array( $part['head'] ) );
			}

			switch ( $part['type'] ) {
				case 'table':

					$i = 0; $j = 0;
					foreach ( $part['set'] as $entry ) {
						if ( $j%$ch == 0 ) {
							$array = array();
							foreach ( $part['def'] as $k => $v ) {
								$array[] = $k;
							}
							echo $exphandler->export_line( $array );
						}

						$array = array();
						foreach ( $part['def'] as $def => $dc ) {
							if ( is_array( $dc[0] ) ) {
								$dn = $dc[0][0].'_'.$dc[0][1];
							} else {
								$dn = $dc[0];
							}

							$dcc = $entry[$dn];

							if ( isset( $dc[1] ) ) {
								$types = explode( ' ', $dc[1] );

								foreach ( $types as $tt ) {
									switch ( $tt ) {
										case 'bool';
											$dcc = $dcc ? 'Yes' : 'No';
											break;
									}
								}
							}

							if ( is_array( $dcc ) ) {
								$dcc = implode( ', ', $dcc );
							}

							$array[] = $dcc;
						}

						echo $exphandler->export_line( $array );

						$j++;
					}

					echo "\n\n";
					break;
			}
		}
 		exit;
	}

}
?>
