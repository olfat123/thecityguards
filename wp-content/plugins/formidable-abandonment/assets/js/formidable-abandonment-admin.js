/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/@wordpress/dom-ready/build-module/index.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@wordpress/dom-ready/build-module/index.js ***!
  \*****************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": function() { return /* binding */ domReady; }\n/* harmony export */ });\n/**\n * @typedef {() => void} Callback\n *\n * TODO: Remove this typedef and inline `() => void` type.\n *\n * This typedef is used so that a descriptive type is provided in our\n * automatically generated documentation.\n *\n * An in-line type `() => void` would be preferable, but the generated\n * documentation is `null` in that case.\n *\n * @see https://github.com/WordPress/gutenberg/issues/18045\n */\n\n/**\n * Specify a function to execute when the DOM is fully loaded.\n *\n * @param {Callback} callback A function to execute after the DOM is ready.\n *\n * @example\n * ```js\n * import domReady from '@wordpress/dom-ready';\n *\n * domReady( function() {\n * \t//do something after DOM loads.\n * } );\n * ```\n *\n * @return {void}\n */\nfunction domReady(callback) {\n  if (typeof document === 'undefined') {\n    return;\n  }\n  if (document.readyState === 'complete' ||\n  // DOMContentLoaded + Images/Styles/etc loaded, so we call directly.\n  document.readyState === 'interactive' // DOMContentLoaded fires at this point, so we call directly.\n  ) {\n    return void callback();\n  }\n\n  // DOMContentLoaded has not fired yet, delay callback until then.\n  document.addEventListener('DOMContentLoaded', callback);\n}\n//# sourceMappingURL=index.js.map\n\n//# sourceURL=webpack://formidable-abandonment/./node_modules/@wordpress/dom-ready/build-module/index.js?");

/***/ }),

/***/ "./assets/src/admin/action.js":
/*!************************************!*\
  !*** ./assets/src/admin/action.js ***!
  \************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* global frmGlobal, frmDom, ajaxurl, jQuery */\n\n/**\n * Class for creating and managing a form action.\n *\n * @since 1.0\n */\nconst createAction = {\n  /**\n   * Last action ID in dom uses as a count.\n   *\n   * @since 1.0\n   */\n  lastActionId: 0,\n  /**\n   * Create email action button.\n   *\n   * @since 1.0\n   */\n  abandonmentEmailAction: document.getElementById('abandonment-email-action'),\n  /**\n   * Creates a form action\n   *\n   * @since 1.0\n   */\n  init() {\n    if (this.abandonmentEmailAction) {\n      this.abandonmentEmailAction.addEventListener('click', () => this.createAction());\n    }\n  },\n  /**\n   * Creates a form action\n   *\n   * @since 1.0\n   */\n  async createAction() {\n    const {\n      div\n    } = frmDom;\n    const actionId = this.getNewActionId();\n    const formId = document.getElementById('form_id').value;\n    const formData = new FormData();\n    formData.append('action', 'frm_add_form_action');\n    formData.append('type', 'email');\n    formData.append('list_id', actionId);\n    formData.append('form_id', formId);\n    formData.append('abandonment_form_action', 'true');\n    formData.append('nonce', frmGlobal.nonce);\n    let response = '';\n    try {\n      response = await fetch(ajaxurl, {\n        method: 'POST',\n        body: formData\n      });\n    } catch (err) {\n      return;\n    }\n    const html = await response.text();\n    document.querySelector(`.frm-form-setting-tabs li a[href=\"#email_settings\"]`).dispatchEvent(new Event('click'));\n    document.querySelectorAll('.frm_form_action_settings.open').forEach(setting => setting.classList.remove('open'));\n    const newActionContainer = div();\n    newActionContainer.innerHTML = html;\n    const widgetTop = newActionContainer.querySelector('.widget-top');\n    const actionsList = document.getElementById('frm_notification_settings');\n    Array.from(newActionContainer.children).forEach(child => actionsList.appendChild(child));\n    const newAction = document.getElementById(`frm_form_action_${actionId}`);\n    newAction.classList.add('open');\n    document.getElementById('post-body-content').scroll({\n      top: newAction.offsetTop + 10,\n      left: 0,\n      behavior: 'smooth'\n    });\n\n    // Check if icon should be active\n    document.querySelectorAll('.frm_email_action').forEach(trigger => {\n      if (trigger.querySelector('.frm_show_upgrade')) {\n        // Prevent disabled action becoming active.\n        return;\n      }\n      trigger.classList.remove('frm_inactive_action', 'frm_already_used');\n      trigger.classList.add('frm_active_action');\n    });\n    this.showInputIcon(`#frm_form_action_${actionId}`);\n    jQuery('#frm_form_action_' + actionId + ' .frm_multiselect').hide().each(frmDom.bootstrap.multiselect.init);\n    frmDom.autocomplete.initAutocomplete('page', newAction);\n    if (widgetTop) {\n      jQuery(widgetTop).trigger('frm-action-loaded');\n    }\n  },\n  /**\n   * Returns a new action ID.\n   *\n   * @since 1.0\n   *\n   * @return {number} The new action ID.\n   */\n  getNewActionId() {\n    const actionSettings = document.querySelectorAll('.frm_form_action_settings');\n    let len = this.getNewRowId(actionSettings, 'frm_form_action_');\n    if (document.getElementById(`frm_form_action_${len}`)) {\n      len += 100;\n    }\n    if (this.lastActionId >= len) {\n      len = this.lastActionId + 1;\n    }\n    this.lastActionId = len;\n    return len;\n  },\n  /**\n   * Returns a new row ID.\n   *\n   * @since 1.0\n   *\n   * @param {Array}  rows             - The rows.\n   * @param {string} replace          - The string to replace.\n   * @param {any}    [defaultValue=0] - The default value if rows are empty.\n   * @return {number} The new row ID.\n   */\n  getNewRowId(rows, replace) {\n    let defaultValue = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 0;\n    if (!rows.length) {\n      return defaultValue;\n    }\n    return parseInt(rows[rows.length - 1].id.replace(replace, ''), 10) + 1;\n  },\n  /**\n   * Displays the input icon.\n   *\n   * @since 1.0\n   *\n   * @param {string} parentClass The parent class.\n   */\n  showInputIcon() {\n    let parentClass = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';\n    this.maybeAddFieldSelection(parentClass);\n    const selectors = document.querySelectorAll(`${parentClass} .frm_has_shortcodes:not(.frm-with-right-icon) input,${parentClass} .frm_has_shortcodes:not(.frm-with-right-icon) textarea`);\n    selectors.forEach(selector => {\n      const span = document.createElement('span');\n      span.classList.add('frm-with-right-icon');\n      selector.parentNode.insertBefore(span, selector);\n      span.appendChild(selector);\n      span.insertAdjacentHTML('afterbegin', '<svg class=\"frmsvg frm-show-box\"><use xlink:href=\"#frm_more_horiz_solid_icon\"/></svg>');\n    });\n  },\n  /**\n   * Checks for fields that were using the old sidebar and adds class if necessary.\n   *\n   * @since 1.0\n   *\n   * @param {string} parentClass The parent class.\n   */\n  maybeAddFieldSelection(parentClass) {\n    const missingClassSelectors = document.querySelectorAll(`${parentClass} :not(.frm_has_shortcodes) .frm_not_email_message, ${parentClass} :not(.frm_has_shortcodes) .frm_not_email_to, ${parentClass} :not(.frm_has_shortcodes) .frm_not_email_subject`);\n    missingClassSelectors.forEach(selector => {\n      selector.parentNode.classList.add('frm_has_shortcodes');\n    });\n  }\n};\n/* harmony default export */ __webpack_exports__[\"default\"] = (createAction);\n\n//# sourceURL=webpack://formidable-abandonment/./assets/src/admin/action.js?");

/***/ }),

/***/ "./assets/src/admin/index.js":
/*!***********************************!*\
  !*** ./assets/src/admin/index.js ***!
  \***********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/dom-ready */ \"./node_modules/@wordpress/dom-ready/build-module/index.js\");\n/* harmony import */ var _action__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./action */ \"./assets/src/admin/action.js\");\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\n\n/**\n * Load admin side js on dom ready.\n */\n(0,_wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(() => {\n  _action__WEBPACK_IMPORTED_MODULE_0__[\"default\"].init();\n});\n\n//# sourceURL=webpack://formidable-abandonment/./assets/src/admin/index.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./assets/src/admin/index.js");
/******/ 	
/******/ })()
;