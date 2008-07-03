<?php
// Update database fields to JSONized fields
if ( $jsonupdate ) {
	$updates = array(	'displayPipeline'	=> array( 'displaypipeline', array( 'params' => array('displayedto') ) ),
						'eventLog'			=> array( 'eventlog', array( 'info' => array('actions') ) ),
						'processor'			=> array( 'config_processors', array() ),
						'SubscriptionPlan'	=> array( 'plans', array( 'params' => array('similarplans','equalplans','processors'), 'micro_integrations' => array('_self'), 'restrictions' => array('previousplan_req','currentplan_req','overallplan_req','previousplan_req_excluded','currentplan_req_excluded','overallplan_req_excluded') ) ),
						'Invoice'			=> array( 'Invoice', array( 'coupons' => array('_self'), 'micro_integrations' => array('_self') ) ),
						'Subscription'		=> array( 'subscr', array() ),
						'microIntegration'	=> array( 'microintegrations', array() ),
						'coupon'			=> array( 'coupons', array( 'restrictions' => array('bad_combinations','usage_plans') ) ),
						'coupon'			=> array( 'coupons_static', array( 'restrictions' => array('bad_combinations','usage_plans') ) )
						);

	$miupdate = array(	'mi_acl' => array( 'sub_gid_del', 'sub_gid', 'sub_gid_exp_del', 'sub_gid_exp', 'sub_gid_pre_exp_del', 'sub_gid_pre_exp' ),
						'mi_docman' => array( 'group', 'group_exp' ),
						'mi_g2' => array( 'groups', 'groups_sel_scope' ),
						'mi_juga' => array( 'enroll_group', 'enroll_group_exp' ),
						'mi_remository' => array( 'group', 'group_exp' )
						);

	foreach ( $updates as $classname => $ucontent ) {
		$dbtable = $ucontent[0];

		$jsondeclare = call_user_func( array( $classname, 'declareJSONfields' ) );

		$unsetdec = array();
		if ( $dbtable == 'subscr' ) {
			$unsetdec[] = 'userid';
			$unsetdec[] = 'plan';
			$unsetdec[] = 'used_plans';
			$unsetdec[] = 'previous_plan';
		} elseif ( $dbtable == 'microintegrations' ) {
			$unsetdec[] = 'class_name';
		}

		$jsondeclare = array_merge( $jsondeclare, $unsetdec );

		$query = 'SELECT `id`'
				. ' FROM #__acctexp_' . $dbtable
				;
		$database->setQuery( $query );
		$entries = $database->loadResultArray();

		if ( empty( $entries ) ) {
			continue;
		}

		foreach ( $entries as $id ) {
			$query = 'SELECT `' . implode( '`, `', $jsondeclare ) . '` FROM #__acctexp_' . $dbtable
			. ' WHERE `id` = \'' . $id . '\''
			;
			$database->setQuery( $query );
			$database->loadObject( $object );

			$dec = $jsondeclare;
			foreach ( $jsondeclare as $fieldname ) {
				if ( in_array( $fieldname, $unsetdec ) ) {
					unset( $dec[array_search( $fieldname, $dec )] );
					continue;
				}

				// No need to update what is empty
				if ( empty( $object->$fieldname ) || ( strpos( $object->$fieldname, '{' ) === 0 ) || ( strpos( $object->$fieldname, '[' ) === 0 ) ) {
					unset( $dec[array_search( $fieldname, $dec )] );
				}
			}

			if ( ( $dbtable == 'subscr' ) && empty( $object->params ) ) {
				$dec[] = 'params';
			}

			if ( count( $dec ) < 1 ) {
				continue;
			}

			$sets = array();
			foreach ( $dec as $fieldname ) {
				// Decode from newline separated variables
				$temp = parameterHandler::decode( $object->$fieldname );

				if ( !empty( $ucontent[1] ) ) {
					if ( isset( $ucontent[1][$fieldname] ) ) {
						if ( in_array( '_self', $ucontent[1][$fieldname] ) ) {
							$temp = explode( ';', $object->$fieldname );
							array_walk( $temp, 'trim' );
						} else {
							foreach ( $temp as $key => $value ) {
								if ( in_array( $key, $ucontent[1][$fieldname] ) ) {
									$temp[$key] = explode( ';', $value );
									array_walk( $temp[$key], 'trim' );
								}
							}
						}
					}
				}

				// Make sure to capture exceptions
				if ( ( $dbtable == 'subscr' ) && ( $fieldname == 'params' ) ) {
					$metaUserDB = new metaUserDB( $database );
					$metaUserDB->loadUserid( $object->userid );

					if ( !empty( $temp ) ) {
						$vs		= new stdClass();
						$vsmi	= new stdClass();

						foreach ( $temp as $key => $value ) {
							if ( strpos( $key, 'MI_FLAG' ) !== false ) {
								$ks = explode( '_', $key );

								$vname = array();

								foreach ( $ks as $n => $k ) {
									if ( in_array( $n, array( 0,1,2,4 ) ) ) {
										// And nothing of value was lost
									} elseif( $n == 3 ) {
										// Set usage
										$usage = $k;
									} elseif( $n == 5 ) {
										// Set MI
										$mi = $k;
									} elseif( $n > 5 ) {
										// Set MI Variable name
										$vname[] = $k;
									}
								}

								// Well, the cool stuff doesnt happen without some lameness
								if ( !isset( $vs->$usage ) ) {
									$vs->$usage = new stdClass();
								}

								if ( !isset( $vs->$usage->$mi ) ) {
									$vs->$usage->$mi = array();
								}

								if ( !isset( $vsmi->$mi ) ) {
									$vsmi->$mi = array();
								}

								$vnam = implode( '_', $vname );
								$var = array( $vnam => $value );

								$vs->$usage->$mi = array_merge( $vs->$usage->$mi, $var );
								$vsmi->$mi = array_merge( $vsmi->$mi, $var );
								unset( $temp[$key] );
							}
						}

						if ( !empty( $vs ) || !empty( $vsmi ) ) {
							$metaUserDB->addPreparedMIParams( $vs, $vsmi );
						}
					}

					$plans = array();

					if ( !empty( $object->used_plans ) ) {
						$used_plans = explode( ";", $object->used_plans );

						foreach ( $used_plans as $plan ) {
							$p = explode( ',', $plan );

							if ( empty( $p[0] ) ) {
								continue;
							}

							if ( $p[0] == $object->plan ) {
								$end = $p;
							} elseif ( $p[0] == $object->previous_plan ) {
								$bend = $p;
							} else {
								if ( !empty( $p[1] ) ) {
									$plans[$p[0]] = $p[1];
								} else {
									$plans[$p[0]] = 1;
								}
							}
						}

						// Preserve previous plan with this
						if ( isset( $bend ) ) {
							if ( !empty( $bend[1] ) ) {
								$plans[$bend[0]] = $bend[1];
							} else {
								$plans[$bend[0]] = 1;
							}
						}

						// Preserve current plan with this
						if ( isset( $end ) ) {
							if ( !empty( $end[1] ) ) {
								$plans[$end[0]] = $end[1];
							} else {
								$plans[$end[0]] = 1;
							}
						}

						$history = array();
						foreach ( $plans as $pid => $poc ) {
							for( $i=0; $i<$poc; $i++ ) {
								$history[] = $pid;
							}
						}

						$up = new stdClass();
						$up->plan_history	= $history;
						$up->used_plans		= $plans;

						$metaUserDB->addParams( $up, 'plan_history' );
						$metaUserDB->storeload();
					}
				} else {
					if ( ( $dbtable == 'plans' ) && ( $fieldname == 'custom_params' ) ) {
						$newtemp = array();
						foreach ( $temp as $locator => $content ) {
							$p = explode( '_', $locator, 2 );

							if ( isset( $p[1] ) ) {
								$newtemp[$p[0]][$p[1]] = $content;
							} else {
								$newtemp[$locator] = $content;
							}
						}

						$temp = $newtemp;
					} elseif ( ( $dbtable == 'microintegrations' ) && ( $fieldname == 'params' ) ) {
						if ( isset( $miupdate[$object->class_name] ) ) {
							$newtemp = array();
							foreach ( $temp as $locator => $content ) {
								if ( in_array( $locator, $miupdate[$object->class_name] ) ) {
									$newtemp[$locator] = explode( ';', $content );
									array_walk( $newtemp[$locator], 'trim' );
								}
							}

							$temp = $newtemp;
						}
					}

					// ...To JSOON based notation
					$sets[] = '`' . $fieldname . '` = \'' . $database->getEscaped( jsoonHandler::encode( $temp ) ) . '\'';
				}
			}

			unset( $object );

			if ( !empty( $sets ) ) {
				$query = 'UPDATE #__acctexp_' . $dbtable
				. ' SET ' . implode( ', ', $sets ) . ''
				. ' WHERE `id` = \'' . $id . '\''
				;
				$database->setQuery( $query );
				if ( !$database->query() ) {
			    	$errors[] = array( $database->getErrorMsg(), $query );
				}
			}
		}
	}
}

$eucaInstalldb->dropColifExists( 'used_plans', 'subscr' );
$eucaInstalldb->dropColifExists( 'previous_plan', 'subscr' );
?>