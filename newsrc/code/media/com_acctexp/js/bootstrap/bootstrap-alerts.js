/* ==========================================================
 * bootstrap-alerts.js v1.4.0
 * http://twitter.github.com/bootstrap/javascript.html#alerts
 * ==========================================================
 * Copyright 2011 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */


!function( jQuery ){

  "use strict"

  /* CSS TRANSITION SUPPORT (https://gist.github.com/373874)
   * ======================================================= */

   var transitionEnd

   jQuery(document).ready(function () {

     jQuery.support.transition = (function () {
       var thisBody = document.body || document.documentElement
         , thisStyle = thisBody.style
         , support = thisStyle.transition !== undefined || thisStyle.WebkitTransition !== undefined || thisStyle.MozTransition !== undefined || thisStyle.MsTransition !== undefined || thisStyle.OTransition !== undefined
       return support
     })()

     // set CSS transition event type
     if ( jQuery.support.transition ) {
       transitionEnd = "TransitionEnd"
       if ( jQuery.browser.webkit ) {
        transitionEnd = "webkitTransitionEnd"
       } else if ( jQuery.browser.mozilla ) {
        transitionEnd = "transitionend"
       } else if ( jQuery.browser.opera ) {
        transitionEnd = "oTransitionEnd"
       }
     }

   })

 /* ALERT CLASS DEFINITION
  * ====================== */

  var Alert = function ( content, options ) {
    this.settings = jQuery.extend({}, jQuery.fn.alert.defaults, options)
    this.$element = jQuery(content)
      .delegate(this.settings.selector, 'click', this.close)
  }

  Alert.prototype = {

    close: function (e) {
      var $element = jQuery(this).parent('.alert-message')

      e && e.preventDefault()
      $element.removeClass('in')

      function removeElement () {
        $element.remove()
      }

      jQuery.support.transition && $element.hasClass('fade') ?
        $element.bind(transitionEnd, removeElement) :
        removeElement()
    }

  }


 /* ALERT PLUGIN DEFINITION
  * ======================= */

  jQuery.fn.alert = function ( options ) {

    if ( options === true ) {
      return this.data('alert')
    }

    return this.each(function () {
      var $this = jQuery(this)

      if ( typeof options == 'string' ) {
        return $this.data('alert')[options]()
      }

      jQuery(this).data('alert', new Alert( this, options ))

    })
  }

  jQuery.fn.alert.defaults = {
    selector: '.close'
  }

  jQuery(document).ready(function () {
    new Alert(jQuery('body'), {
      selector: '.alert-message[data-alert] .close'
    })
  })

}( window.jQuery || window.ender );