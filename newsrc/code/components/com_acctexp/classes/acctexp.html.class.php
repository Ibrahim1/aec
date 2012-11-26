<?php
/**
 * @version $Id: acctexp.html.class.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Core Class
 * @copyright 2006-2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class aecHTML
{

	function aecHTML( $rows, $lists=null, $js=array() )
	{
		//$this->area = $area;
		//$this->fallback = $fallback;

		$this->rows		= $rows;
		$this->lists	= $lists;
		$this->js		= $js;
	}

	function createSettingsParticle( $name, $notooltip=false, $insertlabel=null, $insertctrl=null )
	{
		if ( !isset( $this->rows[$name] ) ) {
			return;
		}

		$row	= $this->rows[$name];
		$type	= $row[0];

		$return = '';

		if ( isset( $row[2] ) ) {
			if ( isset( $row[3] ) ) {
				$value = $row[3];
			} else {
				$value = '';
			}

			if ( !empty( $row[1] ) && !empty( $row[2] ) && !$notooltip ) {
				$return = '<div class="control-group">';

				if ( strnatcmp( phpversion(),'5.2.3' ) >= 0 ) {
					$xtitle = htmlentities( $row[2], ENT_QUOTES, "UTF-8", false );
					$xlabel = htmlentities( $row[1], ENT_QUOTES, "UTF-8", false );
				} else {
					$xtitle = htmlentities( $row[2], ENT_QUOTES, "UTF-8" );
					$xlabel = htmlentities( $row[1], ENT_QUOTES, "UTF-8" );
				}

				$return .= '<label class="control-label bstooltip" for="' . $name . '" rel="tooltip" class="bstooltip" data-original-title="' . $xtitle . '">';

				$return .= $xlabel;

				$return .= $insertlabel;

				$return .= '</label>';
			} else {
				$return = '<div class="control-group">';
				$return .= '<label class="control-label" for="' . $name . '"><div class="controls"></div></label>';
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
				$return .= '<div class="controls">';
				$return .= '<input id="' . $name . '" class="span1" name="' . $name . '" type="text" value="' . $value . '" />';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'inputB':
				$return .= '<div class="controls">';
				$return .= '<input id="' . $name . '" class="span2" type="text" name="' . $name . '" value="' . $value . '" />';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'inputC':
				$return .= '<div class="controls">';
				$return .= '<input id="' . $name . '" class="span3" type="text" name="' . $name . '" value="' . $value . '" />';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'inputD':
				$return .= '<div class="controls">';
				$return .= '<textarea id="' . $name . '" class="span4" rows="5" name="' . $name . '" >' . $value . '</textarea>';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'inputE':
				$return .= '<div class="controls">';
				$return .= '<textarea id="' . $name . '" class="span4" cols="450" rows="1" name="' . $name . '" >' . $value . '</textarea>';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'password':
				$return .= '<div class="controls">';
				$return .= '<input id="' . $name . '" class="span3" type="password" name="' . $name . '" value="' . $value . '" />';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'p':
				$return = ( !empty( $value ) ? '<p>' . $value . '</p>' : '' );
				break;
			case 'checkbox':
				$return = '<div class="control-group">';
				$return .= '<label class="control-label" for="' . $name . '"></label>';
				$return .= '<div class="controls">';
				$return .= '<input type="hidden" name="' . $name . '" value="0"/>';
				$return .= '<input id="' . $name . '" type="checkbox" name="' . $name . '" ' . ( $value ? 'checked="checked" ' : '' ) . ' value="1"/>';

				$return .= $xlabel;

				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'toggle':
				$return .= '<input type="hidden" name="' . $name . '" value="0"/>';
				$return .= '<div class="controls">';
				$return .= '<div class="toggleswitch">';
				$return .= '<label class="toggleswitch" onclick="">';
				$return .= '<input id="' . $name . '" type="checkbox" name="' . $name . '"' . ( $value ? ' checked="checked" ' : '' ) . ' value="1"/>';
				$return .= '<span class="toggleswitch-inner">';
				$return .= '<span class="toggleswitch-on">' . JText::_( 'yes' ) . '</span>';
				$return .= '<span class="toggleswitch-off">' . JText::_( 'no' ) . '</span>';
				$return .= '<span class="toggleswitch-handle"></span>';
				$return .= '</span>';
				$return .= '</label>';
				$return .= '</div>';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'toggle_disabled':
				$return .= '<input type="hidden" name="' . $name . '" value="' . $value . '"/>';
				$return .= '<div class="controls">';
				$return .= '<div class="toggleswitch">';
				$return .= '<label class="toggleswitch" onclick="">';
				$return .= '<input id="' . $name . '" type="checkbox" name="' . $name . '"' . ( $value ? ' checked="checked" ' : '' ) . ' disabled="disabled" value="1"/>';
				$return .= '<span class="toggleswitch-inner">';
				$return .= '<span class="toggleswitch-on">' . JText::_( 'yes' ) . '</span>';
				$return .= '<span class="toggleswitch-off">' . JText::_( 'no' ) . '</span>';
				$return .= '<span class="toggleswitch-handle"></span>';
				$return .= '</span>';
				$return .= '</label>';
				$return .= '</div>';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'editor':
				$return .= '<div class="controls">';

				$editor = &JFactory::getEditor();

				$return .= '<div>' . $editor->display( $name,  $value , '', '250', '50', '20' ) . '</div>';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'textarea':
				$return .= '<textarea style="width:90%" cols="450" rows="10" name="' . $name . '" id="' . $name . '" >' . $value . '</textarea></div>';
				break;
			case 'list':
				$return .= '<div class="controls">';

				if ( strpos( $this->lists[$name], '[]"' ) ) {
					$return .= '<input type="hidden" name="' . $name . '" value="0" />';
					$return .= str_replace( '<select', '<select class="jqui-multiselect"', $this->lists[$name] );
				} else {
					$return .= str_replace( '<select', '<select class="span3"', $this->lists[$name] );
				}
				
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'radio':
				$return = '<div class="control-group">';
				$return .= '<label class="control-label" for="' . $name . '">';
				$return .= '<input type="radio" id="' . $name . '" name="' . $row[1] . '"' . ( ( $row[3] == $row[2] ) ? ' checked="checked"' : '' ) . ' value="' . $row[2] . '"/>';
				$return .= '</label>';
				$return .= '<div class="controls">';
				$return .= $row[4];
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'file':
				$return .= '<div class="controls">';
				$return .= '<input id="' . $name . '" name="' . $name . '" type="file" />';
				$return .= $insertctrl;
				$return .= '</div></div>';
				break;
			case 'accordion_start':
				if ( !isset( $this->accordions ) ) {
					$this->accordionitems = 1;
					$this->accordions = 1;
				} else {
					$this->accordions++;
				}

				$return = '<div id="accordion' . $this->accordions . '" class="accordion' . ( !empty( $value ) ? ' ' . $value : '' ) . '"' . '>';
				break;
			case 'accordion_itemstart':
				$return = '<div class="accordion-group">';
				$return .= '<div class="accordion-heading"><a href="#collapse' . ($this->accordions+$this->accordionitems) . '" data-parent="#accordion' . $this->accordions . '" data-toggle="collapse" class="accordion-toggle">' . $value . '</a></div>';
				$return .= '<div class="accordion-body collapse" id="collapse' . ($this->accordions+$this->accordionitems) . '"><div class="accordion-inner">';
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
				$return = '<div style="position:relative;float:left;width:' . $value . '%;"><div class="userinfobox">';
				break;
			case 'userinfobox_sub':
				$return = '<div class="aec_userinfobox_sub">' . ( !empty( $value ) ? '<h4>' . $value . '</h4>' : '' );
				break;
			case 'userinfobox_sub_stacked':
				$return = '<div class="aec_userinfobox_sub form-stacked">' . ( !empty( $value ) ? '<h4>' . $value . '</h4>' : '' );
				break;
			case 'fieldset':
				$return = '<div class="controls">' . "\n"
				. '<fieldset><legend>' . $row[1] . '</legend>' . "\n"
				. $row[2] . "\n"
				. '</fieldset>' . "\n"
				. '</div>'
				;
				break;
			case 'page-head':
				$return = '<div class="page-header" id="' . str_replace(" ", "_", strtolower($value) ) . '"><h1>' . $value . '</h1></div>';
				break;
			case 'section':
				$return = '<section' . ( !empty( $value ) ? ' id="' . $value . '"' : '' ) . '>';
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
				$return .= '<div class="controls">';
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

	function loadJS( $return=null )
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

	function returnFull( $notooltip=false, $table=false )
	{
		$return = '';
		foreach ( $this->rows as $rowname => $rowcontent ) {
			$return .= $this->createSettingsParticle( $rowname, $notooltip );
		}

		return $return;
	}

	function printFull( $notooltip=false )
	{
		echo $this->returnFull( $notooltip );
	}

	function createFormParticle( $name, $row=null, $lists=array(), $table=0 )
	{
		// Currently abandoning this function, leaving a couple of things for further reference as I move
		// stuff to createSettingsParticle
		if ( is_null( $row ) && !empty( $this->rows ) ) {
			if ( isset( $this->rows[$name] ) ) {
				$row = $this->rows[$name];
			} else {
				return '';
			}
		}

		if ( empty( $lists ) && !empty( $this->lists ) ) {
			$lists = $this->lists;
		}

		global $aecConfig;

		$return = '';
		if ( isset( $row[3] ) ) {
			$value = $row[3];
		} else {
			$value = '';
		}

		if ( !empty( $row[2] ) ) {
			$return .= $table ? '<tr class="aec_formrow"><td class="cleft">' : '<p>';
		} else {
			$return .= $table ? '<tr class="aec_formrow"><td class="cleft">' : '<p>';

			$row[2] = "";
		}

		$return .= '<label id="' . $name . 'msg" for="' . $name . '">';

		$sx = "";
		$sxx = false;
		if ( !empty( $row[1] ) ) {
			if ( strpos( $row[1], '*' ) ) {
				$sx = '<span class="aec_required">*</span>';
				$sxx = true;

				$row[1] = substr( $row[1], 0, -1 );
			}

			$return .= '<strong>' . $row[1] . ( ( strpos( $row[1], '?' ) == ( strlen( $row[1] ) ) - 1 ) ? '' : ':' ) . '</strong>';
		}

		$return .= '</label>';

		$return .= $table ? '</td><td class="cright">' : ' ';

		$noappend = false;
		switch ( $row[0] ) {
			case 'submit':
				$return .= '<input type="submit" class="button btn aec_formfield' . ( $aecConfig->cfg['checkoutform_jsvalidation'] ? ' validate-'.$name : '' ) . ( $sxx ? " required" : "" ) . '" id="' . $name . '" name="' . $name . '" value="' . $value . '" title="' . $row[2] . '" />' . "\n";
				break;
			case "inputA":
				$return .= '<input type="text" class="inputbox aec_formfield' . ( $aecConfig->cfg['checkoutform_jsvalidation'] ? ' validate-'.$name : '' ) . ( $sxx ? " required" : "" ) . '" id="' . $name . '" name="' . $name . '" size="4" maxlength="5" value="' . $value . '" title="' . $row[2] . '" />' . $sx;
				break;
			case "inputB":
				$return .= '<input type="text" class="inputbox aec_formfield' . ( $aecConfig->cfg['checkoutform_jsvalidation'] ? ' validate-'.$name : '' ) . ( $sxx ? " required" : "" ) . '" id="' . $name . '" name="' . $name . '" size="2" maxlength="10" value="' . $value . '" title="' . $row[2] . '" />' . $sx;
				break;
			case "inputC":
				$return .= '<input type="text" class="inputbox aec_formfield' . ( $aecConfig->cfg['checkoutform_jsvalidation'] ? ' validate-'.$name : '' ) . ( $sxx ? " required" : "" ) . '" id="' . $name . '" name="' . $name . '" size="30" value="' . $value . '" title="' . $row[2] . '"/>' . $sx;
				break;
			case "inputD":
				$return .= '<textarea align="left" cols="60" rows="5" id="' . $name . '" name="' . $name . '" title="' . $row[2] . '" class="aec_formfield' . ( $aecConfig->cfg['checkoutform_jsvalidation'] ? ' validate-'.$name : '' ) . '' . ( $aecConfig->cfg['checkoutform_jsvalidation'] ? ' form-validate' : '' ) . ( $sxx ? " required" : "" ) . '"/>' . $value . '</textarea>' . $sx;
				break;
			case "password":
				$return .= '<input type="password" class="inputbox aec_formfield' . ( $aecConfig->cfg['checkoutform_jsvalidation'] ? ' validate-'.$name : '' ) . ( $sxx ? " required" : "" ) . '" id="' . $name . '" name="' . $name . '" size="30" value="' . $value . '" title="' . $row[2] . '"/>' . $sx;
				break;
			case 'radio':
				$return = '<tr><td class="cleft">';
				$return .= '<input type="radio" id="' . $name . '" name="' . $row[1] . '"' . ( ( $row[3] == $row[2] ) ? ' checked="checked"' : '' ) . ' value="' . $row[2] . '" title="' . /*$row[2] .*/ '" class="aec_formfield' . ( $aecConfig->cfg['checkoutform_jsvalidation'] ? ' validate-'.$name : '' ) . ( $sxx ? " required" : "" ) . '"/>';
				$return .= '</td><td class="cright">' . $row[4];
				break;
			case 'checkbox':
				$return = '<tr><td class="cleft">';
				$return .= '<input type="checkbox" id="' . $name . '" name="' . $row[1] . '"' . ( ( $row[3] == $row[2] ) ? ' checked="checked"' : '' ) . ' value="' . $row[2] . '" class="aec_formfield' . ( $aecConfig->cfg['checkoutform_jsvalidation'] ? ' validate-'.$name : '' ) . ( $sxx ? " required" : "" ) . '"/>' . $sx;
				$return .= '</td><td class="cright">' . $row[4];
				break;
			case "list":
				if ( $aecConfig->cfg['checkoutform_jsvalidation'] ) {
					$title = "";
					if ( isset( $row[2] ) ) {
						$title = $row[2];
					} elseif ( isset( $row[1] ) ) {
						$title = $row[1];
					}
					
					$search = 'class="';
					$replace = 'title="' . $title . '" class="' . ( $aecConfig->cfg['checkoutform_jsvalidation'] ? 'validate-'.$name.' ' : '' ) . ( $sxx ? 'required ' : '' );

					$return .= str_replace( $search, $replace, $lists[$value ? $value : $name] ) . $sx;
				} else {
					$return .= $lists[$value ? $value : $name] . $sx;
				}
				break;
			case 'tabberstart':
				$return = '<div class="checkout-tabs">';
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
				$return = '<div id="' . $row[1] . '" class="tab-pane' . ($act ? ' active': '') . '"><table>';
				break;
			case 'tabend':
				$return = '</table></div>';
				break;
			case 'tabberend':
				$return = '</div></div>';
				break;
			case 'divstart':
				if ( isset( $row[4] ) ) {
					$return = '<div id="' . $row[4] . '">';
				} else {
					$return = '<div class="' . $row[3] . '">';
				}
				break;
			case 'divend':
				$return = '</div>';
				break;
			case 'hidden':
				if ( !empty( $row[2] ) ) {
					$name = $row[2];
				}

				$return = '';
				if ( is_array( $value ) ) {
					foreach ( $value as $v ) {
						$return .= '<input type="hidden" id="' . $name . '" name="' . $name . '[]" value="' . $v . '" />';
					}
				} else {
					$return .= '<input type="hidden" id="' . $name . '" name="' . $name . '" value="' . $value . '" />';
				}
				break;
			default:
				if ( !empty( $row[0] ) ) {
					if ( empty( $row[1] ) ) {
						if ( isset( $row[4] ) ) {
							$return = '<tr><td class="cboth" colspan="2"><' . $row[0] . $row[4] . '>' . $row[2] . $value . '</' . $row[0] . '></td></tr>';
						} else {
							$return = '<tr><td class="cboth" colspan="2"><' . $row[0] . '>' . $row[2] . $value . '</' . $row[0] . '></td></tr>';
						}
					} else {
						if ( isset( $row[4] ) ) {
							$return = '<' . $row[0] . $row[4] . '>' . $row[2] . $value . '</' . $row[0] . '>';
						} else {
							if ( empty( $row[2] ) ) {
								$return = '<' . $row[0] . '>' . $row[1] . $value . '</' . $row[0] . '>';
							} else {
								$return = '<' . $row[0] . '>' . $row[2] . $value . '</' . $row[0] . '>';
							}
						}
					}
				} elseif ( empty( $row[0] ) && empty( $row[2] ) ) {
					$return .= '<' . $row[1] . $value . ' />';
				} else {
					$return .= $row[2] . $value;
				}
				break;
		}

		if ( strpos( $return, ($table ? '<tr class="aec_formrow"><td class="cleft">' : '<p>') ) !== false ) {
			$return .= $table ? '</td></tr>' : '</p>';
		}

		return $return;
	}

	/**
	* Utility function to provide ToolTips
	* @param string ToolTip text
	* @param string Box title
	* @returns HTML code for ToolTip
	*/
	function ToolTip( $tooltip, $title='', $width='', $image='help.png', $text='', $href='#', $link=1 )
	{
		if ( $width ) {
			$width = ', WIDTH, \''.$width .'\'';
		}
		if ( $title ) {
			$title = ', CAPTION, \''.$title .'\'';
		}
		if ( !$text ) {
			$image 	= JURI::root() . 'media/com_acctexp/images/admin/icons/'. $image;
			$text 	= '<img src="'. $image .'" border="0" alt=""/>';
		}
		$style = 'style="text-decoration: none; color: #586C79;"';
		if ( $href ) {
			$style = '';
		} else{
			$href = '#';
		}

		if ( strnatcmp( phpversion(),'5.2.3' ) >= 0 ) {
			$mousover = 'return overlib(\''. htmlentities( $tooltip, ENT_QUOTES, "UTF-8", false ) .'\''. $title .', BELOW, RIGHT'. $width .');';
		} else {
			$mousover = 'return overlib(\''. htmlentities( $tooltip, ENT_QUOTES, "UTF-8" ) .'\''. $title .', BELOW, RIGHT'. $width .');';
		}

		$tip = '';
		if ( $link ) {
			$tip .= '<a href="'. $href .'" onmouseover="'. $mousover .'" onmouseout="return nd();" '. $style .'>'. $text .'</a>';
		} else {
			$tip .= '<span onmouseover="'. $mousover .'" onmouseout="return nd();" '. $style .'>'. $text .'</span>';
		}

		return $tip . '&nbsp;';
	}

	function Icon( $icon='fire', $white=false, $addin=null )
	{
		$v = new JVersion();

		if ( $v->isCompatible('3.0') ) {
			return '<i class="icon-'. $icon . ( $white ? ' icon-white' : '' ) . $addin .'"></i>';
		} else {
			return '<i class="bsicon-'. $icon . ( $white ? ' bsicon-white' : '' ) . $addin .'"></i>';
		}
	}

	function Button( $icon='fire', $text='', $style='', $link='', $js='' )
	{
		$white = true;

		if ( empty( $style ) ) {
			$white = false;
		} else {
			$style = ' btn-'.$style;
		}

		if ( empty( $link ) ) {
			$link = '#';
		}

		if ( !empty( $js ) ) {
			$js = 'onclick="javascript: submitbutton(\''.$js.'\')"';
		}

		return '<a data-original-title="'.JText::_($text).'" rel="tooltip" href="'.$link.'"'.$js.' class="btn'.$style.'">'.aecHTML::Icon( $icon, $white ).'</a>';
	}

}

?>
