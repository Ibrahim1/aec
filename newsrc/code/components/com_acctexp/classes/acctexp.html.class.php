<?php
/**
 * @version $Id: acctexp.html.class.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Core Class
 * @copyright 2006-2015 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

class aecHTML
{
	public function aecHTML( $rows, $lists=null, $js=array() )
	{
		$this->rows		= $rows;
		$this->lists	= $lists;
		$this->js		= $js;
	}

	public function createSettingsParticle( $name, $notooltip=false, $insertlabel=null, $insertctrl=null )
	{
		if ( !isset( $this->rows[$name] ) ) {
			return null;
		}

		$row	= $this->rows[$name];
		$type	= $row[0];

		$return = '';

		$xtitle = $xlabel = '';

		if ( isset( $row[2] ) ) {
			if ( isset( $row[3] ) ) {
				$value = $row[3];
			} else {
				$value = '';
			}

			$return = '<div id="wrap_' . $name . '" class="form-group">';

			if ( !empty( $row[1] ) && !empty( $row[2] ) && !$notooltip ) {
				if ( strnatcmp( phpversion(),'5.2.3' ) >= 0 ) {
					$xtitle = htmlentities( $row[2], ENT_QUOTES, "UTF-8", false );
					$xlabel = htmlentities( $row[1], ENT_QUOTES, "UTF-8", false );
				} else {
					$xtitle = htmlentities( $row[2], ENT_QUOTES, "UTF-8" );
					$xlabel = htmlentities( $row[1], ENT_QUOTES, "UTF-8" );
				}

				$return .= '<label class="col-sm-4 control-label bstooltip" for="' . $name . '" rel="tooltip" data-original-title="' . $xtitle . '">';

				$return .= $xlabel;

				$return .= $insertlabel;

				$return .= '</label>';
			}
		} else {
			if ( isset( $row[1] ) ) {
				$value = $row[1];
			} else {
				$value = '';
			}
		}

		switch ( $type ) {
			case 'inputA':
				$return .= '<div class="col-sm-8">';
				$return .= '<input id="' . $name . '" class="form-control col-sm-1" name="' . $name . '" type="text" value="' . $value . '" />';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'inputB':
				$return .= '<div class="col-sm-8">';
				$return .= '<input id="' . $name . '" class="form-control col-sm-2" type="text" name="' . $name . '" value="' . $value . '" />';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'inputC':
				$return .= '<div class="col-sm-8">';
				$return .= '<input id="' . $name . '" class="form-control col-sm-3" type="text" name="' . $name . '" value="' . $value . '" />';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'inputD':
				$return .= '<div class="col-sm-8">';
				$return .= '<textarea id="' . $name . '" class="form-control col-sm-4" rows="5" name="' . $name . '" >' . $value . '</textarea>';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'inputE':
				$return .= '<div class="col-sm-8">';
				$return .= '<textarea id="' . $name . '" class="form-control col-sm-4" cols="450" rows="1" name="' . $name . '" >' . $value . '</textarea>';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'password':
				$return .= '<div class="col-sm-8">';
				$return .= '<input id="' . $name . '" class="form-control col-sm-3" type="password" name="' . $name . '" value="' . $value . '" />';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'p':
				$return = ( !empty( $value ) ? '<p>' . $value . '</p>' : '' );
				break;
			case 'hr':
				$return = '<hr />';
				break;
			case 'checkbox':
				$id = $name;

				if ( !empty( $row[5] ) ) {
					$attrname = $row[5];

					$name = str_replace( '[]', '', $attrname );
				} else {
					$attrname = $name;
				}

				if ( !empty( $value ) && !empty( $row[4] ) ) {
					$enabled = $value == $row[4];
				} elseif ( !empty( $value ) ) {
					$enabled = $value;
				} else {
					$value = 1;

					$enabled = false;
				}

				$return = '<div class="form-group">';
				$return .= '<label for="' . $id . '">';

				if ( !empty( $xlabel ) ) {
					$return .= $xlabel;
				} elseif ( !empty( $xtitle ) ) {
					$return .= $xtitle;
				} elseif ( !empty( $row[1] ) ) {
					if ( strnatcmp( phpversion(),'5.2.3' ) >= 0 ) {
						$return .= htmlentities( $row[1], ENT_QUOTES, "UTF-8", false );
					} else {
						$return .= htmlentities( $row[1], ENT_QUOTES, "UTF-8" );
					}
				} elseif ( !empty( $row[2] ) ) {
					if ( strnatcmp( phpversion(),'5.2.3' ) >= 0 ) {
						$return .= htmlentities( $row[2], ENT_QUOTES, "UTF-8", false );
					} else {
						$return .= htmlentities( $row[2], ENT_QUOTES, "UTF-8" );
					}
				}

				$return .= '</label>';

				$return .= '<div class="form-control">';
				$return .= '<input type="hidden" name="' . $name . '" value="0"/>';
				$return .= '<input id="' . $id . '" type="checkbox" name="' . $attrname . '" ' . ( $enabled ? 'checked="checked" ' : '' ) . ' value="' . $value . '"/>';

				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'checkbox_nofallback':
				$id = $name;

				if ( !empty( $row[5] ) ) {
					$attrname = $row[5];

					$name = str_replace( '[]', '', $attrname );
				} else {
					$attrname = $name;
				}

				if ( !empty( $value ) && !empty( $row[4] ) ) {
					$enabled = $value == $row[4];
				} elseif ( !empty( $value ) ) {
					$enabled = $value;
				} else {
					$value = 1;

					$enabled = false;
				}

				$return = '<div class="form-group">';
				$return .= '<label for="' . $id . '">';

				if ( !empty( $xlabel ) ) {
					$return .= $xlabel;
				} elseif ( !empty( $xtitle ) ) {
					$return .= $xtitle;
				} elseif ( !empty( $row[1] ) ) {
					if ( strnatcmp( phpversion(),'5.2.3' ) >= 0 ) {
						$return .= htmlentities( $row[1], ENT_QUOTES, "UTF-8", false );
					} else {
						$return .= htmlentities( $row[1], ENT_QUOTES, "UTF-8" );
					}
				} elseif ( !empty( $row[2] ) ) {
					if ( strnatcmp( phpversion(),'5.2.3' ) >= 0 ) {
						$return .= htmlentities( $row[2], ENT_QUOTES, "UTF-8", false );
					} else {
						$return .= htmlentities( $row[2], ENT_QUOTES, "UTF-8" );
					}
				}

				$return .= '</label>';

				$return .= '<div class="form-control">';
				$return .= '<input id="' . $id . '" type="checkbox" name="' . $attrname . '" ' . ( $enabled ? 'checked="checked" ' : '' ) . ' value="' . $value . '"/>';

				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'toggle':
				$return .= '<input type="hidden" name="' . $name . '" value="0"/>';

				$return .= '<div class="col-sm-8">';
				$return .= '<input id="' . $name . '" class="bootstrap-toggle" type="checkbox" name="' . $name . '"' . ( $value ? ' checked="checked" ' : '' ) . ' value="1"/>';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'toggle_disabled':
				$return .= '<input type="hidden" name="' . $name . '" value="' . $value . '"/>';

				$return .= '<div class="col-sm-8">';
				$return .= '<input id="' . $name . '" class="bootstrap-toggle" type="checkbox" name="' . $name . '"' . ( $value ? ' checked="checked" ' : '' ) . ' disabled="disabled" value="1"/>';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'editor':
				$editor = JFactory::getEditor();

				$return .= '<div>' . $editor->display( $name,  $value , '', '250', '50', '20' ) . '</div>';
				$return .= $insertctrl;
				$return .= '</div>';
				break;
			case 'textarea':
				$return .= '<textarea style="width:90%" cols="450" rows="10" name="' . $name . '" id="' . $name . '" >' . $value . '</textarea></div>';
				break;
			case 'list':
				$return .= '<div class="col-sm-8">';
				if ( isset($this->lists[$name]) && strpos( $this->lists[$name], '[]"' ) ) {
					$return .= '<input type="hidden" name="' . $name . '" value="0" />';
					$return .= str_replace( '<select', '<select class="select2-bootstrap"', $this->lists[$name] );
				} else {
					$return .= str_replace( '<select', '<select class="form-control"', $this->lists[$name] );
				}

				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'radio':
				$return = '<div class="form-group">';
				$return .= '<label for="' . $name . '">';
				$return .= '<input type="radio" id="' . $name . '" name="' . $row[1] . '"' . ( ( $row[3] == $row[2] ) ? ' checked="checked"' : '' ) . ' value="' . $row[2] . '"/>';
				$return .= '</label>';
				$return .= $row[4];
				$return .= $insertctrl;
				$return .= '</div>';
				break;
			case 'file':
				$return .= '<input id="' . $name . '" name="' . $name . '" type="file" />';
				$return .= $insertctrl;
				$return .= '</div>';
				break;
			case 'accordion_start':
				if ( !isset( $this->accordions ) ) {
					$this->accordionitems = 1;
					$this->accordions = 1;
				} else {
					$this->accordions++;
				}

				$return = '<div id="accordion' . $this->accordions . '" class="panel-group' . ( !empty( $value ) ? ' ' . $value : '' ) . '" role="tablist" aria-multiselectable="true">';
				break;
			case 'accordion_itemstart':
				$item_target = $this->accordions . '-' . $this->accordionitems;

				$return = '<div class="panel panel-default">';
				$return .= '<div class="panel-heading" role="tab" id="heading' . $item_target . '"><a href="#collapse' . $item_target . '" data-parent="#accordion' . $this->accordions . '" data-toggle="collapse" class="accordion-toggle" aria-expanded="true" aria-controls="#collapse' . $item_target . '">' . $value . '</a></div>';
				$return .= '<div class="panel-collapse collapse' . ($this->accordionitems<2 ? ' in' : '') . '" id="collapse' . $item_target . '" role="tabpanel" aria-labelledby="heading' . $item_target . '"><div class="panel-body">';
				break;
			case 'accordion_itemend':
				$this->accordionitems++;

				$return = '</div></div></div>';
				break;
			case 'div_end':
				$return = '</div>';
				break;
			case '2div_end':
				$return = '</div></div>';
				break;
			case 'tabberstart':
				$return = '';
				break;
			case 'tabregisterstart':
				$return = '<ul class="nav nav-tabs">';
				break;
			case 'tabregister':
				$return = '<li' . ($row[3] ? ' class="active"': '') . '><a href="#' . $row[1] . '" data-toggle="tab">' . $row[2] . '</a></li> ';
				break;
			case 'tabregisterend':
				$return = '</ul><div class="tab-content">';
				break;
			case 'tabstart':
				$act = false;
				if ( isset( $row[2] ) ) {
					$act = $row[2];
				}
				$return = '<div id="' . $row[1] . '" class="tab-pane' . ($act ? ' active': '') . '">';
				break;
			case 'tabend':
				$return = '</div>';
				break;
			case 'tabberend':
				$return = '</div>';
				break;
			case 'userinfobox':
				$return = '<div class="col-sm-' . $value . '"><div class="userinfobox">';
				break;
			case 'userinfobox_sub':
				$return = '<div class="aec-settings-container">' . ( !empty( $value ) ? '<h4>' . $value . '</h4>' : '' );
				break;
			case 'userinfobox_sub_stacked':
				$return = '<div class="aec-settings-container">' . ( !empty( $value ) ? '<h4>' . $value . '</h4>' : '' );
				break;
			case 'section_paper':
				$return = '<section class="paper">' . ( !empty( $value ) ? '<h4>' . $value . '</h4>' : '' );
				break;
			case 'section_end':
				$return = '</section>';
				break;
			case 'fieldset':
				$return = '<div class="form-control">' . "\n"
				. '<fieldset><legend>' . $row[1] . '</legend>' . "\n"
				. $row[2] . "\n"
				. '</fieldset>' . "\n"
				. '</div>'
				;
				break;
			case 'page-head':
				$return = '<div id="' . str_replace(" ", "_", strtolower($value) ) . '"><h1>' . $value . '</h1></div>';
				break;
			case 'section':
				$return = '<section class="paper"' . ( !empty( $value ) ? ' id="' . $value . '"' : '' ) . '>';
				break;
			case 'section-head':
				$return = '<h2>' . $value . '</h2>';
				break;
			case 'section-end':
				$return = '</section>';
				break;
			case 'hidden':
				$return = '';
				if ( is_array( $value ) ) {
					foreach ( $value as $v ) {
						$return .= '<input id="' . $name . '" type="hidden" name="' . $name . '[]" value="' . $v . '" />';
					}
				} else {
					$return .= '<input id="' . $name . '" type="hidden" name="' . $name . '" value="' . $value . '" />';
				}
				break;
			case 'passthrough':
				$return .= '<div class="form-control">';
				$return .= $value;
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			default:
				$return = $value;
				break;
		}
		return $return;
	}

	public function loadJS( $return=null )
	{
		if ( !empty( $this->js ) || !empty( $return ) ) {
			$js = "\n" . '<script type="text/javascript">';

			if ( !empty( $this->js ) ) {
				foreach ( $this->js as $scriptblock ) {
					$js .= "\n";
					$js .= $scriptblock;
				}
			}

			$js .= $return;
			$js .= "\n" . '</script>';

			$return = $js;
		}

		return $return;
	}

	public function returnFull( $notooltip=false, $table=false )
	{
		$return = '';
		foreach ( $this->rows as $rowname => $rowcontent ) {
			$return .= $this->createSettingsParticle( $rowname, $notooltip );
		}

		return $return;
	}

	public function printFull( $notooltip=false )
	{
		echo $this->returnFull( $notooltip );
	}

	/**
	 * @param string $addin
	 */
	static function Icon( $icon='fire', $addin=null )
	{
		return '<i class="glyphicon glyphicon-'. $icon . $addin .'"></i>';
	}

	static function Button( $icon='fire', $text='', $style='', $link='', $js='' )
	{
		$style = ' btn-' . $style;

		if ( empty( $link ) ) {
			$link = '#';
		}

		if ( !empty( $js ) ) {
			$js = 'onclick="javascript: submitbutton(\''.$js.'\')"';
		}

		return '<a data-original-title="'.JText::_($text).'" rel="tooltip" href="'.$link.'"'.$js.' class="btn'.$style.'">'.aecHTML::Icon( $icon ).'</a>';
	}

}
