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

/***/ "./node_modules/@wordpress/hooks/build-module/createAddHook.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createAddHook.js ***!
  \*********************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _validateNamespace_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./validateNamespace.js */ \"./node_modules/@wordpress/hooks/build-module/validateNamespace.js\");\n/* harmony import */ var _validateHookName_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./validateHookName.js */ \"./node_modules/@wordpress/hooks/build-module/validateHookName.js\");\n/**\n * Internal dependencies\n */\n\n\n\n/**\n * @callback AddHook\n *\n * Adds the hook to the appropriate hooks container.\n *\n * @param {string}               hookName      Name of hook to add\n * @param {string}               namespace     The unique namespace identifying the callback in the form `vendor/plugin/function`.\n * @param {import('.').Callback} callback      Function to call when the hook is run\n * @param {number}               [priority=10] Priority of this hook\n */\n\n/**\n * Returns a function which, when invoked, will add a hook.\n *\n * @param {import('.').Hooks}    hooks    Hooks instance.\n * @param {import('.').StoreKey} storeKey\n *\n * @return {AddHook} Function that adds a new hook.\n */\nfunction createAddHook(hooks, storeKey) {\n  return function addHook(hookName, namespace, callback, priority = 10) {\n    const hooksStore = hooks[storeKey];\n    if (!(0,_validateHookName_js__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(hookName)) {\n      return;\n    }\n    if (!(0,_validateNamespace_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(namespace)) {\n      return;\n    }\n    if ('function' !== typeof callback) {\n      // eslint-disable-next-line no-console\n      console.error('The hook callback must be a function.');\n      return;\n    }\n\n    // Validate numeric priority\n    if ('number' !== typeof priority) {\n      // eslint-disable-next-line no-console\n      console.error('If specified, the hook priority must be a number.');\n      return;\n    }\n    const handler = {\n      callback,\n      priority,\n      namespace\n    };\n    if (hooksStore[hookName]) {\n      // Find the correct insert index of the new hook.\n      const handlers = hooksStore[hookName].handlers;\n\n      /** @type {number} */\n      let i;\n      for (i = handlers.length; i > 0; i--) {\n        if (priority >= handlers[i - 1].priority) {\n          break;\n        }\n      }\n      if (i === handlers.length) {\n        // If append, operate via direct assignment.\n        handlers[i] = handler;\n      } else {\n        // Otherwise, insert before index via splice.\n        handlers.splice(i, 0, handler);\n      }\n\n      // We may also be currently executing this hook.  If the callback\n      // we're adding would come after the current callback, there's no\n      // problem; otherwise we need to increase the execution index of\n      // any other runs by 1 to account for the added element.\n      hooksStore.__current.forEach(hookInfo => {\n        if (hookInfo.name === hookName && hookInfo.currentIndex >= i) {\n          hookInfo.currentIndex++;\n        }\n      });\n    } else {\n      // This is the first hook of its type.\n      hooksStore[hookName] = {\n        handlers: [handler],\n        runs: 0\n      };\n    }\n    if (hookName !== 'hookAdded') {\n      hooks.doAction('hookAdded', hookName, namespace, callback, priority);\n    }\n  };\n}\n/* harmony default export */ __webpack_exports__[\"default\"] = (createAddHook);\n//# sourceMappingURL=createAddHook.js.map\n\n//# sourceURL=webpack://formidable-abandonment/./node_modules/@wordpress/hooks/build-module/createAddHook.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createCurrentHook.js":
/*!*************************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createCurrentHook.js ***!
  \*************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/**\n * Returns a function which, when invoked, will return the name of the\n * currently running hook, or `null` if no hook of the given type is currently\n * running.\n *\n * @param {import('.').Hooks}    hooks    Hooks instance.\n * @param {import('.').StoreKey} storeKey\n *\n * @return {() => string | null} Function that returns the current hook name or null.\n */\nfunction createCurrentHook(hooks, storeKey) {\n  return function currentHook() {\n    var _hooksStore$__current;\n    const hooksStore = hooks[storeKey];\n    return (_hooksStore$__current = hooksStore.__current[hooksStore.__current.length - 1]?.name) !== null && _hooksStore$__current !== void 0 ? _hooksStore$__current : null;\n  };\n}\n/* harmony default export */ __webpack_exports__[\"default\"] = (createCurrentHook);\n//# sourceMappingURL=createCurrentHook.js.map\n\n//# sourceURL=webpack://formidable-abandonment/./node_modules/@wordpress/hooks/build-module/createCurrentHook.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createDidHook.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createDidHook.js ***!
  \*********************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _validateHookName_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./validateHookName.js */ \"./node_modules/@wordpress/hooks/build-module/validateHookName.js\");\n/**\n * Internal dependencies\n */\n\n\n/**\n * @callback DidHook\n *\n * Returns the number of times an action has been fired.\n *\n * @param {string} hookName The hook name to check.\n *\n * @return {number | undefined} The number of times the hook has run.\n */\n\n/**\n * Returns a function which, when invoked, will return the number of times a\n * hook has been called.\n *\n * @param {import('.').Hooks}    hooks    Hooks instance.\n * @param {import('.').StoreKey} storeKey\n *\n * @return {DidHook} Function that returns a hook's call count.\n */\nfunction createDidHook(hooks, storeKey) {\n  return function didHook(hookName) {\n    const hooksStore = hooks[storeKey];\n    if (!(0,_validateHookName_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(hookName)) {\n      return;\n    }\n    return hooksStore[hookName] && hooksStore[hookName].runs ? hooksStore[hookName].runs : 0;\n  };\n}\n/* harmony default export */ __webpack_exports__[\"default\"] = (createDidHook);\n//# sourceMappingURL=createDidHook.js.map\n\n//# sourceURL=webpack://formidable-abandonment/./node_modules/@wordpress/hooks/build-module/createDidHook.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createDoingHook.js":
/*!***********************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createDoingHook.js ***!
  \***********************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/**\n * @callback DoingHook\n * Returns whether a hook is currently being executed.\n *\n * @param {string} [hookName] The name of the hook to check for.  If\n *                            omitted, will check for any hook being executed.\n *\n * @return {boolean} Whether the hook is being executed.\n */\n\n/**\n * Returns a function which, when invoked, will return whether a hook is\n * currently being executed.\n *\n * @param {import('.').Hooks}    hooks    Hooks instance.\n * @param {import('.').StoreKey} storeKey\n *\n * @return {DoingHook} Function that returns whether a hook is currently\n *                     being executed.\n */\nfunction createDoingHook(hooks, storeKey) {\n  return function doingHook(hookName) {\n    const hooksStore = hooks[storeKey];\n\n    // If the hookName was not passed, check for any current hook.\n    if ('undefined' === typeof hookName) {\n      return 'undefined' !== typeof hooksStore.__current[0];\n    }\n\n    // Return the __current hook.\n    return hooksStore.__current[0] ? hookName === hooksStore.__current[0].name : false;\n  };\n}\n/* harmony default export */ __webpack_exports__[\"default\"] = (createDoingHook);\n//# sourceMappingURL=createDoingHook.js.map\n\n//# sourceURL=webpack://formidable-abandonment/./node_modules/@wordpress/hooks/build-module/createDoingHook.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createHasHook.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createHasHook.js ***!
  \*********************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/**\n * @callback HasHook\n *\n * Returns whether any handlers are attached for the given hookName and optional namespace.\n *\n * @param {string} hookName    The name of the hook to check for.\n * @param {string} [namespace] Optional. The unique namespace identifying the callback\n *                             in the form `vendor/plugin/function`.\n *\n * @return {boolean} Whether there are handlers that are attached to the given hook.\n */\n/**\n * Returns a function which, when invoked, will return whether any handlers are\n * attached to a particular hook.\n *\n * @param {import('.').Hooks}    hooks    Hooks instance.\n * @param {import('.').StoreKey} storeKey\n *\n * @return {HasHook} Function that returns whether any handlers are\n *                   attached to a particular hook and optional namespace.\n */\nfunction createHasHook(hooks, storeKey) {\n  return function hasHook(hookName, namespace) {\n    const hooksStore = hooks[storeKey];\n\n    // Use the namespace if provided.\n    if ('undefined' !== typeof namespace) {\n      return hookName in hooksStore && hooksStore[hookName].handlers.some(hook => hook.namespace === namespace);\n    }\n    return hookName in hooksStore;\n  };\n}\n/* harmony default export */ __webpack_exports__[\"default\"] = (createHasHook);\n//# sourceMappingURL=createHasHook.js.map\n\n//# sourceURL=webpack://formidable-abandonment/./node_modules/@wordpress/hooks/build-module/createHasHook.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createHooks.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createHooks.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   _Hooks: function() { return /* binding */ _Hooks; }\n/* harmony export */ });\n/* harmony import */ var _createAddHook__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./createAddHook */ \"./node_modules/@wordpress/hooks/build-module/createAddHook.js\");\n/* harmony import */ var _createRemoveHook__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./createRemoveHook */ \"./node_modules/@wordpress/hooks/build-module/createRemoveHook.js\");\n/* harmony import */ var _createHasHook__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./createHasHook */ \"./node_modules/@wordpress/hooks/build-module/createHasHook.js\");\n/* harmony import */ var _createRunHook__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./createRunHook */ \"./node_modules/@wordpress/hooks/build-module/createRunHook.js\");\n/* harmony import */ var _createCurrentHook__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./createCurrentHook */ \"./node_modules/@wordpress/hooks/build-module/createCurrentHook.js\");\n/* harmony import */ var _createDoingHook__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./createDoingHook */ \"./node_modules/@wordpress/hooks/build-module/createDoingHook.js\");\n/* harmony import */ var _createDidHook__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./createDidHook */ \"./node_modules/@wordpress/hooks/build-module/createDidHook.js\");\n/**\n * Internal dependencies\n */\n\n\n\n\n\n\n\n\n/**\n * Internal class for constructing hooks. Use `createHooks()` function\n *\n * Note, it is necessary to expose this class to make its type public.\n *\n * @private\n */\nclass _Hooks {\n  constructor() {\n    /** @type {import('.').Store} actions */\n    this.actions = Object.create(null);\n    this.actions.__current = [];\n\n    /** @type {import('.').Store} filters */\n    this.filters = Object.create(null);\n    this.filters.__current = [];\n    this.addAction = (0,_createAddHook__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, 'actions');\n    this.addFilter = (0,_createAddHook__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, 'filters');\n    this.removeAction = (0,_createRemoveHook__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(this, 'actions');\n    this.removeFilter = (0,_createRemoveHook__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(this, 'filters');\n    this.hasAction = (0,_createHasHook__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(this, 'actions');\n    this.hasFilter = (0,_createHasHook__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(this, 'filters');\n    this.removeAllActions = (0,_createRemoveHook__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(this, 'actions', true);\n    this.removeAllFilters = (0,_createRemoveHook__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(this, 'filters', true);\n    this.doAction = (0,_createRunHook__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(this, 'actions');\n    this.applyFilters = (0,_createRunHook__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(this, 'filters', true);\n    this.currentAction = (0,_createCurrentHook__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(this, 'actions');\n    this.currentFilter = (0,_createCurrentHook__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(this, 'filters');\n    this.doingAction = (0,_createDoingHook__WEBPACK_IMPORTED_MODULE_5__[\"default\"])(this, 'actions');\n    this.doingFilter = (0,_createDoingHook__WEBPACK_IMPORTED_MODULE_5__[\"default\"])(this, 'filters');\n    this.didAction = (0,_createDidHook__WEBPACK_IMPORTED_MODULE_6__[\"default\"])(this, 'actions');\n    this.didFilter = (0,_createDidHook__WEBPACK_IMPORTED_MODULE_6__[\"default\"])(this, 'filters');\n  }\n}\n\n/** @typedef {_Hooks} Hooks */\n\n/**\n * Returns an instance of the hooks object.\n *\n * @return {Hooks} A Hooks instance.\n */\nfunction createHooks() {\n  return new _Hooks();\n}\n/* harmony default export */ __webpack_exports__[\"default\"] = (createHooks);\n//# sourceMappingURL=createHooks.js.map\n\n//# sourceURL=webpack://formidable-abandonment/./node_modules/@wordpress/hooks/build-module/createHooks.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createRemoveHook.js":
/*!************************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createRemoveHook.js ***!
  \************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _validateNamespace_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./validateNamespace.js */ \"./node_modules/@wordpress/hooks/build-module/validateNamespace.js\");\n/* harmony import */ var _validateHookName_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./validateHookName.js */ \"./node_modules/@wordpress/hooks/build-module/validateHookName.js\");\n/**\n * Internal dependencies\n */\n\n\n\n/**\n * @callback RemoveHook\n * Removes the specified callback (or all callbacks) from the hook with a given hookName\n * and namespace.\n *\n * @param {string} hookName  The name of the hook to modify.\n * @param {string} namespace The unique namespace identifying the callback in the\n *                           form `vendor/plugin/function`.\n *\n * @return {number | undefined} The number of callbacks removed.\n */\n\n/**\n * Returns a function which, when invoked, will remove a specified hook or all\n * hooks by the given name.\n *\n * @param {import('.').Hooks}    hooks             Hooks instance.\n * @param {import('.').StoreKey} storeKey\n * @param {boolean}              [removeAll=false] Whether to remove all callbacks for a hookName,\n *                                                 without regard to namespace. Used to create\n *                                                 `removeAll*` functions.\n *\n * @return {RemoveHook} Function that removes hooks.\n */\nfunction createRemoveHook(hooks, storeKey, removeAll = false) {\n  return function removeHook(hookName, namespace) {\n    const hooksStore = hooks[storeKey];\n    if (!(0,_validateHookName_js__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(hookName)) {\n      return;\n    }\n    if (!removeAll && !(0,_validateNamespace_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(namespace)) {\n      return;\n    }\n\n    // Bail if no hooks exist by this name.\n    if (!hooksStore[hookName]) {\n      return 0;\n    }\n    let handlersRemoved = 0;\n    if (removeAll) {\n      handlersRemoved = hooksStore[hookName].handlers.length;\n      hooksStore[hookName] = {\n        runs: hooksStore[hookName].runs,\n        handlers: []\n      };\n    } else {\n      // Try to find the specified callback to remove.\n      const handlers = hooksStore[hookName].handlers;\n      for (let i = handlers.length - 1; i >= 0; i--) {\n        if (handlers[i].namespace === namespace) {\n          handlers.splice(i, 1);\n          handlersRemoved++;\n          // This callback may also be part of a hook that is\n          // currently executing.  If the callback we're removing\n          // comes after the current callback, there's no problem;\n          // otherwise we need to decrease the execution index of any\n          // other runs by 1 to account for the removed element.\n          hooksStore.__current.forEach(hookInfo => {\n            if (hookInfo.name === hookName && hookInfo.currentIndex >= i) {\n              hookInfo.currentIndex--;\n            }\n          });\n        }\n      }\n    }\n    if (hookName !== 'hookRemoved') {\n      hooks.doAction('hookRemoved', hookName, namespace);\n    }\n    return handlersRemoved;\n  };\n}\n/* harmony default export */ __webpack_exports__[\"default\"] = (createRemoveHook);\n//# sourceMappingURL=createRemoveHook.js.map\n\n//# sourceURL=webpack://formidable-abandonment/./node_modules/@wordpress/hooks/build-module/createRemoveHook.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createRunHook.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createRunHook.js ***!
  \*********************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/**\n * Returns a function which, when invoked, will execute all callbacks\n * registered to a hook of the specified type, optionally returning the final\n * value of the call chain.\n *\n * @param {import('.').Hooks}    hooks                  Hooks instance.\n * @param {import('.').StoreKey} storeKey\n * @param {boolean}              [returnFirstArg=false] Whether each hook callback is expected to\n *                                                      return its first argument.\n *\n * @return {(hookName:string, ...args: unknown[]) => undefined|unknown} Function that runs hook callbacks.\n */\nfunction createRunHook(hooks, storeKey, returnFirstArg = false) {\n  return function runHooks(hookName, ...args) {\n    const hooksStore = hooks[storeKey];\n    if (!hooksStore[hookName]) {\n      hooksStore[hookName] = {\n        handlers: [],\n        runs: 0\n      };\n    }\n    hooksStore[hookName].runs++;\n    const handlers = hooksStore[hookName].handlers;\n\n    // The following code is stripped from production builds.\n    if (true) {\n      // Handle any 'all' hooks registered.\n      if ('hookAdded' !== hookName && hooksStore.all) {\n        handlers.push(...hooksStore.all.handlers);\n      }\n    }\n    if (!handlers || !handlers.length) {\n      return returnFirstArg ? args[0] : undefined;\n    }\n    const hookInfo = {\n      name: hookName,\n      currentIndex: 0\n    };\n    hooksStore.__current.push(hookInfo);\n    while (hookInfo.currentIndex < handlers.length) {\n      const handler = handlers[hookInfo.currentIndex];\n      const result = handler.callback.apply(null, args);\n      if (returnFirstArg) {\n        args[0] = result;\n      }\n      hookInfo.currentIndex++;\n    }\n    hooksStore.__current.pop();\n    if (returnFirstArg) {\n      return args[0];\n    }\n    return undefined;\n  };\n}\n/* harmony default export */ __webpack_exports__[\"default\"] = (createRunHook);\n//# sourceMappingURL=createRunHook.js.map\n\n//# sourceURL=webpack://formidable-abandonment/./node_modules/@wordpress/hooks/build-module/createRunHook.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/index.js":
/*!*************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/index.js ***!
  \*************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   actions: function() { return /* binding */ actions; },\n/* harmony export */   addAction: function() { return /* binding */ addAction; },\n/* harmony export */   addFilter: function() { return /* binding */ addFilter; },\n/* harmony export */   applyFilters: function() { return /* binding */ applyFilters; },\n/* harmony export */   createHooks: function() { return /* reexport safe */ _createHooks__WEBPACK_IMPORTED_MODULE_0__[\"default\"]; },\n/* harmony export */   currentAction: function() { return /* binding */ currentAction; },\n/* harmony export */   currentFilter: function() { return /* binding */ currentFilter; },\n/* harmony export */   defaultHooks: function() { return /* binding */ defaultHooks; },\n/* harmony export */   didAction: function() { return /* binding */ didAction; },\n/* harmony export */   didFilter: function() { return /* binding */ didFilter; },\n/* harmony export */   doAction: function() { return /* binding */ doAction; },\n/* harmony export */   doingAction: function() { return /* binding */ doingAction; },\n/* harmony export */   doingFilter: function() { return /* binding */ doingFilter; },\n/* harmony export */   filters: function() { return /* binding */ filters; },\n/* harmony export */   hasAction: function() { return /* binding */ hasAction; },\n/* harmony export */   hasFilter: function() { return /* binding */ hasFilter; },\n/* harmony export */   removeAction: function() { return /* binding */ removeAction; },\n/* harmony export */   removeAllActions: function() { return /* binding */ removeAllActions; },\n/* harmony export */   removeAllFilters: function() { return /* binding */ removeAllFilters; },\n/* harmony export */   removeFilter: function() { return /* binding */ removeFilter; }\n/* harmony export */ });\n/* harmony import */ var _createHooks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./createHooks */ \"./node_modules/@wordpress/hooks/build-module/createHooks.js\");\n/**\n * Internal dependencies\n */\n\n\n/** @typedef {(...args: any[])=>any} Callback */\n\n/**\n * @typedef Handler\n * @property {Callback} callback  The callback\n * @property {string}   namespace The namespace\n * @property {number}   priority  The namespace\n */\n\n/**\n * @typedef Hook\n * @property {Handler[]} handlers Array of handlers\n * @property {number}    runs     Run counter\n */\n\n/**\n * @typedef Current\n * @property {string} name         Hook name\n * @property {number} currentIndex The index\n */\n\n/**\n * @typedef {Record<string, Hook> & {__current: Current[]}} Store\n */\n\n/**\n * @typedef {'actions' | 'filters'} StoreKey\n */\n\n/**\n * @typedef {import('./createHooks').Hooks} Hooks\n */\n\nconst defaultHooks = (0,_createHooks__WEBPACK_IMPORTED_MODULE_0__[\"default\"])();\nconst {\n  addAction,\n  addFilter,\n  removeAction,\n  removeFilter,\n  hasAction,\n  hasFilter,\n  removeAllActions,\n  removeAllFilters,\n  doAction,\n  applyFilters,\n  currentAction,\n  currentFilter,\n  doingAction,\n  doingFilter,\n  didAction,\n  didFilter,\n  actions,\n  filters\n} = defaultHooks;\n\n//# sourceMappingURL=index.js.map\n\n//# sourceURL=webpack://formidable-abandonment/./node_modules/@wordpress/hooks/build-module/index.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/validateHookName.js":
/*!************************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/validateHookName.js ***!
  \************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/**\n * Validate a hookName string.\n *\n * @param {string} hookName The hook name to validate. Should be a non empty string containing\n *                          only numbers, letters, dashes, periods and underscores. Also,\n *                          the hook name cannot begin with `__`.\n *\n * @return {boolean} Whether the hook name is valid.\n */\nfunction validateHookName(hookName) {\n  if ('string' !== typeof hookName || '' === hookName) {\n    // eslint-disable-next-line no-console\n    console.error('The hook name must be a non-empty string.');\n    return false;\n  }\n  if (/^__/.test(hookName)) {\n    // eslint-disable-next-line no-console\n    console.error('The hook name cannot begin with `__`.');\n    return false;\n  }\n  if (!/^[a-zA-Z][a-zA-Z0-9_.-]*$/.test(hookName)) {\n    // eslint-disable-next-line no-console\n    console.error('The hook name can only contain numbers, letters, dashes, periods and underscores.');\n    return false;\n  }\n  return true;\n}\n/* harmony default export */ __webpack_exports__[\"default\"] = (validateHookName);\n//# sourceMappingURL=validateHookName.js.map\n\n//# sourceURL=webpack://formidable-abandonment/./node_modules/@wordpress/hooks/build-module/validateHookName.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/validateNamespace.js":
/*!*************************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/validateNamespace.js ***!
  \*************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/**\n * Validate a namespace string.\n *\n * @param {string} namespace The namespace to validate - should take the form\n *                           `vendor/plugin/function`.\n *\n * @return {boolean} Whether the namespace is valid.\n */\nfunction validateNamespace(namespace) {\n  if ('string' !== typeof namespace || '' === namespace) {\n    // eslint-disable-next-line no-console\n    console.error('The namespace must be a non-empty string.');\n    return false;\n  }\n  if (!/^[a-zA-Z][a-zA-Z0-9_.\\-\\/]*$/.test(namespace)) {\n    // eslint-disable-next-line no-console\n    console.error('The namespace can only contain numbers, letters, dashes, periods, underscores and slashes.');\n    return false;\n  }\n  return true;\n}\n/* harmony default export */ __webpack_exports__[\"default\"] = (validateNamespace);\n//# sourceMappingURL=validateNamespace.js.map\n\n//# sourceURL=webpack://formidable-abandonment/./node_modules/@wordpress/hooks/build-module/validateNamespace.js?");

/***/ }),

/***/ "./assets/src/dom-closing.js":
/*!***********************************!*\
  !*** ./assets/src/dom-closing.js ***!
  \***********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/hooks */ \"./node_modules/@wordpress/hooks/build-module/index.js\");\n/* harmony import */ var _dom_helper__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./dom-helper */ \"./assets/src/dom-helper.js\");\n/* harmony import */ var _form_settings__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./form-settings */ \"./assets/src/form-settings.js\");\n\n\n\n\n/**\n * Class for handling closing of DOM.\n *\n * @since 1.0\n */\nfunction domClosing() {\n  /**\n   * Is sending data Eligible.\n   *\n   * @since 1.0\n   */\n  this.eligible = false;\n  /**\n   * Form ID to watch.\n   *\n   * @since 1.0\n   */\n  this.formID = null;\n  /**\n   * Attached callback on dom close.\n   *\n   * @since 1.0\n   */\n  this.callBack = null;\n  /**\n   * Bind the function if eligible.\n   *\n   * @since 1.0\n   */\n  this.bind = function () {\n    if (this.isEligible()) {\n      this.callBack();\n    }\n  };\n  /**\n   * Check if eligible.\n   *\n   * @since 1.0\n   *\n   * @return {boolean} Eligibility.\n   */\n  this.isEligible = function () {\n    return this.eligible;\n  };\n  /**\n   * Handle visibility change event.\n   *\n   * @since 1.0\n   */\n  this.visibilitychange = function () {\n    document.addEventListener('visibilitychange', () => {\n      if (document.visibilityState === 'hidden') {\n        this.bind();\n      }\n    });\n  };\n  /**\n   * Capture form on interval to backup the visibilitychange \"hidden\"\n   * when it's not triggered.\n   *\n   * @since x.x\n   */\n  this.saveOnInterval = function () {\n    /**\n     * Apply filter for auto save timer.\n     *\n     * @param timer Interval in ms.\n     *\n     * @since x.x\n     */\n    const timer = (0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__.applyFilters)('frm_auto_save_interval', 10000);\n    setInterval(() => {\n      if (document.visibilityState === 'visible') {\n        this.bind();\n      }\n    }, timer);\n  };\n  /**\n   * Check if the form field has changed by visitor.\n   *\n   * @since 1.0\n   */\n  this.isFormManipulated = function () {\n    (0,_dom_helper__WEBPACK_IMPORTED_MODULE_1__.selector)(this.formID).addEventListener('change', () => {\n      this.eligible = true;\n    });\n  };\n  /**\n   * Check if form has been submitted.\n   *\n   * @since 1.0\n   */\n  this.isFormSubmitted = function () {\n    (0,_dom_helper__WEBPACK_IMPORTED_MODULE_1__.selector)(this.formID).addEventListener('submit', e => {\n      if (e.target.classList.contains('frm_final_submit')) {\n        this.eligible = false;\n      }\n    });\n  };\n  /**\n   * Check if collection is enabled.\n   *\n   * @since 1.0\n   *\n   * @return {boolean} If collection under circumstances is enabled.\n   */\n  this.isEmailRequiredEnabled = function () {\n    return (0,_form_settings__WEBPACK_IMPORTED_MODULE_2__.getSetting)(this.formID, 'abandon_email_required');\n  };\n  /**\n   * Observe fields for changes.\n   *\n   * @since 1.0\n   */\n  this.observeFields = function () {\n    const selectors = (0,_dom_helper__WEBPACK_IMPORTED_MODULE_1__.observableSelector)((0,_form_settings__WEBPACK_IMPORTED_MODULE_2__.getSetting)(this.formID, 'observable_fields'));\n    Array.prototype.forEach.call(selectors, selector => {\n      // Make the form eligible immediately on keyup.\n      selector.addEventListener('keyup', () => {\n        if (selector.value) {\n          this.eligible = true;\n        }\n      });\n      // On page break we need to check whether value is set.\n      if (selector.value) {\n        this.eligible = true;\n      }\n    });\n  };\n  /**\n   * Initialization function.\n   *\n   * @since 1.0\n   *\n   * @param {string}   formID   - Form ID.\n   * @param {Function} callBack - Callback function.\n   */\n  this.init = function () {\n    let formID = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : this.formID;\n    let callBack = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this.callBack;\n    this.formID = formID;\n    this.callBack = callBack;\n    // Unbind the callback whenever the form is being submitted.\n    this.isFormSubmitted();\n\n    /**\n     * When an email or phone field is required for capturing entry so we ensure visitors have started filling the form\n     * otherwise there would be a simple change event on form fields to ensure we are not capturing the form when it's not even touched.\n     */\n    if (true === this.isEmailRequiredEnabled()) {\n      this.observeFields();\n    } else {\n      this.isFormManipulated();\n    }\n\n    // In addition to page visibility change we will save the form on intervals.\n    this.saveOnInterval();\n    this.visibilitychange();\n  };\n}\n/* harmony default export */ __webpack_exports__[\"default\"] = (domClosing);\n\n//# sourceURL=webpack://formidable-abandonment/./assets/src/dom-closing.js?");

/***/ }),

/***/ "./assets/src/dom-helper.js":
/*!**********************************!*\
  !*** ./assets/src/dom-helper.js ***!
  \**********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   formSelector: function() { return /* binding */ formSelector; },\n/* harmony export */   observableSelector: function() { return /* binding */ observableSelector; },\n/* harmony export */   prepareData: function() { return /* binding */ prepareData; },\n/* harmony export */   selector: function() { return /* binding */ selector; }\n/* harmony export */ });\n/* global formidableAbandonedGlobal */\n\n/**\n * Get all dom needed for app.\n *\n * @since 1.0\n *\n * @return {Object} Elements.\n */\nconst formSelector = () => {\n  const selectors = {\n    [Symbol.iterator]() {\n      const values = Object.values(this);\n      let index = Object.keys(this);\n      return {\n        next() {\n          if (index < values.length) {\n            const val = values[index];\n            index++;\n            return {\n              value: val,\n              done: false\n            };\n          }\n          return {\n            done: true\n          };\n        }\n      };\n    }\n  };\n  Array.prototype.forEach.call(JSON.parse(formidableAbandonedGlobal.formSettings), formSetting => {\n    selectors[formSetting.form_id] = document.querySelector('#frm_form_' + formSetting.form_id + '_container form');\n  });\n  return selectors;\n};\n\n/**\n * Get dom form selector based on passed ID.\n *\n * @since 1.0\n *\n * @param {string} formID form identifier.\n *\n * @return {Object} Elements.\n */\nconst selector = formID => document.querySelector(`#frm_form_${formID}_container form`);\n\n/**\n * Prepare all filled form Data.\n *\n * @since 1.0\n *\n * @param {integer} id Form selector.\n *\n * @return {Object} Elements.\n */\nconst prepareData = id => {\n  const element = selector(id);\n  const formData = new FormData(element);\n  const preparedData = {\n    item_meta: {},\n    empty: true // Used as a flag to don't send the beacon when form is empty.\n  };\n\n  let finilizedData = {};\n  let prev;\n  const count = 1;\n  for (const [key, value] of formData.entries()) {\n    if (key.includes('item_meta')) {\n      finilizedData = {};\n      if ('' !== value) {\n        preparedData.empty = false;\n      }\n      let iterator = key.replace('item_meta', '').replaceAll('[', '').split(']');\n      iterator.pop(iterator.length - 1);\n\n      // When fields are not repeater, name etc collect the value and continue.\n      if (iterator.length <= 1) {\n        preparedData.item_meta[key.replace(/\\D+/g, '')] = value;\n        continue;\n      }\n      if (prev === key) {\n        iterator = iterator.map(x => {\n          if ('' === x) {\n            return count;\n          }\n          return x;\n        });\n      }\n      setDeep(finilizedData, iterator, value);\n      mergeDeep(preparedData.item_meta, finilizedData);\n    } else {\n      preparedData[key] = value;\n    }\n    prev = key;\n  }\n  antiSpamToken(element, preparedData);\n  return preparedData;\n};\n\n/**\n * Prepare an array of observable fields selectors.\n *\n * @since 1.0\n *\n * @param {Array} fieldsName array of field names.\n *\n * @return {Array} array of email or phone number elements for a form.\n */\nconst observableSelector = fieldsName => fieldsName.map(fieldName => document.querySelector(`[name=\"item_meta[${fieldName}]\"]`));\n\n/**\n * Assign JS anti spam token to prepared Data object.\n *\n * @since 1.0\n *\n * @param {Element} selector     Selected form element.\n * @param {Object}  preparedData Data object to be modified.\n *\n * @return {Object} Modified data object.\n */\nconst antiSpamToken = (selector, preparedData) => Object.assign(preparedData, {\n  antispam_token: selector.dataset.token\n});\n\n/**\n * Dynamically sets a deeply nested value in an object.\n *\n * @since 1.0\n *\n * @param {!Object} obj   - The object which contains the value you want to change/set.\n * @param {!Array}  path  - The array representation of path to the value you want to change/set.\n * @param {*}       value - The value you want to set it to.\n *\n * @return {Object|undefined} Return multidimensional object.\n */\nconst setDeep = (obj, path, value) => {\n  path.reduce((a, b, level) => {\n    if (typeof a[b] === 'undefined' && level !== path.length - 1) {\n      a[b] = {};\n    }\n    if (level === path.length - 1) {\n      a[b] = value;\n      return value;\n    }\n    return a[b];\n  }, obj);\n};\n\n/**\n * Checks if the input is an object.\n *\n * @since 1.0\n *\n * @param {*} item - The input to check.\n * @return {boolean} - Returns true if the input is an object, false otherwise.\n */\nconst isObject = item => item && typeof item === 'object' && !Array.isArray(item);\n\n/**\n * Deep merge two objects for form values.\n *\n * @param {Object} target\n * @param {...any} sources\n */\nfunction mergeDeep(target) {\n  for (var _len = arguments.length, sources = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {\n    sources[_key - 1] = arguments[_key];\n  }\n  if (!sources.length) {\n    return target;\n  }\n  const source = sources.shift();\n  if (isObject(target) && isObject(source)) {\n    for (const key in source) {\n      if (isObject(source[key])) {\n        if (!target[key]) {\n          Object.assign(target, {\n            [key]: {}\n          });\n        }\n        mergeDeep(target[key], source[key]);\n      } else {\n        Object.assign(target, {\n          [key]: source[key]\n        });\n      }\n    }\n  }\n  return mergeDeep(target, ...sources);\n}\n\n//# sourceURL=webpack://formidable-abandonment/./assets/src/dom-helper.js?");

/***/ }),

/***/ "./assets/src/form-settings.js":
/*!*************************************!*\
  !*** ./assets/src/form-settings.js ***!
  \*************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   getSetting: function() { return /* binding */ getSetting; }\n/* harmony export */ });\n/* global formidableAbandonedGlobal */\n\n/**\n * Get a setting from the global object.\n *\n * @since 1.0\n *\n * @param {string} formID     The form ID to match against.\n * @param {string} optionName The option name to retrieve from the matched form's settings.\n *\n * @return {*} The value of the requested setting for the matched form.\n */\nconst getSetting = (formID, optionName) => {\n  const formSettings = JSON.parse(formidableAbandonedGlobal.formSettings);\n  const matchedSetting = formSettings.find(setting => setting.form_id === formID);\n  return matchedSetting[optionName];\n};\n\n//# sourceURL=webpack://formidable-abandonment/./assets/src/form-settings.js?");

/***/ }),

/***/ "./assets/src/helper.js":
/*!******************************!*\
  !*** ./assets/src/helper.js ***!
  \******************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   createUUID: function() { return /* binding */ createUUID; },\n/* harmony export */   getCookie: function() { return /* binding */ getCookie; }\n/* harmony export */ });\n/* harmony import */ var uuid__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! uuid */ \"./node_modules/uuid/dist/esm-browser/v4.js\");\n\n\n/**\n * Helper object for cookie operations.\n *\n * @since 1.0\n */\nconst helper = {\n  /**\n   * Creates a cookie.\n   *\n   * @since 1.0\n   *\n   * @param {string} name  The name of the cookie.\n   * @param {string} value The value of the cookie.\n   */\n  createCookie(name, value) {\n    const secure = document.location.protocol === 'https:' ? ';secure' : '';\n    document.cookie = `${name}=uuid-${value};expires=-1;path=/;samesite=strict${secure}`;\n  }\n};\n\n/**\n * Sets a cookie containing a user UUID.\n *\n * @since 1.0\n */\nconst createUUID = () => {\n  if (getCookie('_frmuuid')) {\n    return;\n  }\n  helper.createCookie('_frmuuid', (0,uuid__WEBPACK_IMPORTED_MODULE_0__[\"default\"])());\n};\n\n/**\n * Retrieve cookie.\n *\n * @since 1.0\n *\n * @param {string} name Cookie name.\n *\n * @return {string|null} Cookie value or null when it doesn't exist.\n */\nconst getCookie = name => {\n  var _document$cookie$spli;\n  return (_document$cookie$spli = document.cookie.split('; ').find(row => row.startsWith(name))) === null || _document$cookie$spli === void 0 ? void 0 : _document$cookie$spli.split('=')[1];\n};\n\n\n//# sourceURL=webpack://formidable-abandonment/./assets/src/helper.js?");

/***/ }),

/***/ "./assets/src/index.js":
/*!*****************************!*\
  !*** ./assets/src/index.js ***!
  \*****************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/dom-ready */ \"./node_modules/@wordpress/dom-ready/build-module/index.js\");\n/* harmony import */ var _dom_helper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./dom-helper */ \"./assets/src/dom-helper.js\");\n/* harmony import */ var _dom_closing__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./dom-closing */ \"./assets/src/dom-closing.js\");\n/* harmony import */ var _helper__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./helper */ \"./assets/src/helper.js\");\n/* harmony import */ var _xhr_beacon__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./xhr-beacon */ \"./assets/src/xhr-beacon.js\");\n/**\n * WordPress dependencies\n */\n\n\n/**\n *Internal dependencies\n */\n\n\n\n\n\n/**\n * Initializes the scripts when DOM is ready.\n *\n * @since 1.0\n */\n(0,_wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(() => {\n  // Create a UUID to cookie.\n  (0,_helper__WEBPACK_IMPORTED_MODULE_2__.createUUID)();\n  // Observer on forms which has enabled abandonment.\n  observer();\n});\n\n/**\n * Observes the forms and handles their data.\n *\n * @since 1.0\n */\nconst observer = () => {\n  const formSelectors = (0,_dom_helper__WEBPACK_IMPORTED_MODULE_0__.formSelector)();\n  for (const formID in formSelectors) {\n    // Bail if form exists.\n    if (null === formSelectors[formID]) {\n      continue;\n    }\n    const domClosingObject = new _dom_closing__WEBPACK_IMPORTED_MODULE_1__[\"default\"]();\n    domClosingObject.init(formID, () => {\n      (0,_xhr_beacon__WEBPACK_IMPORTED_MODULE_3__.handleSendBeacon)(formID, (0,_dom_helper__WEBPACK_IMPORTED_MODULE_0__.prepareData)(formID));\n    });\n  }\n};\n\n//# sourceURL=webpack://formidable-abandonment/./assets/src/index.js?");

/***/ }),

/***/ "./assets/src/xhr-beacon.js":
/*!**********************************!*\
  !*** ./assets/src/xhr-beacon.js ***!
  \**********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   handleSendBeacon: function() { return /* binding */ handleSendBeacon; }\n/* harmony export */ });\n/* harmony import */ var _helper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./helper */ \"./assets/src/helper.js\");\n/* global formidableAbandonedGlobal */\n\n\n\n/**\n * Sets up the beacon.\n *\n * @since 1.0\n *\n * @param {string}   formID Form id.\n * @param {FormData} data   Form data.\n */\nfunction handleSendBeacon(formID, data) {\n  // Check the empty flag on prepare data and avoid sending form whenever form is empty.\n  if (data.empty === true) {\n    return;\n  }\n\n  // Delete unnecessary data.\n  delete data.empty;\n  const formData = new FormData();\n  formData.append('_frmuuid', (0,_helper__WEBPACK_IMPORTED_MODULE_0__.getCookie)('_frmuuid'));\n  formData.append('form_id', formID);\n  formData.append('data', JSON.stringify(data));\n  formData.append('action', 'frm_abandoned');\n  formData.append('task', 'send_analytics');\n  window.navigator.sendBeacon(formidableAbandonedGlobal.ajaxUrl, formData);\n}\n\n\n//# sourceURL=webpack://formidable-abandonment/./assets/src/xhr-beacon.js?");

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/native.js":
/*!******************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/native.js ***!
  \******************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\nconst randomUUID = typeof crypto !== 'undefined' && crypto.randomUUID && crypto.randomUUID.bind(crypto);\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  randomUUID\n});\n\n//# sourceURL=webpack://formidable-abandonment/./node_modules/uuid/dist/esm-browser/native.js?");

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/regex.js":
/*!*****************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/regex.js ***!
  \*****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = (/^(?:[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}|00000000-0000-0000-0000-000000000000)$/i);\n\n//# sourceURL=webpack://formidable-abandonment/./node_modules/uuid/dist/esm-browser/regex.js?");

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/rng.js":
/*!***************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/rng.js ***!
  \***************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": function() { return /* binding */ rng; }\n/* harmony export */ });\n// Unique ID creation requires a high quality random # generator. In the browser we therefore\n// require the crypto API and do not support built-in fallback to lower quality random number\n// generators (like Math.random()).\nlet getRandomValues;\nconst rnds8 = new Uint8Array(16);\nfunction rng() {\n  // lazy load so that environments that need to polyfill have a chance to do so\n  if (!getRandomValues) {\n    // getRandomValues needs to be invoked in a context where \"this\" is a Crypto implementation.\n    getRandomValues = typeof crypto !== 'undefined' && crypto.getRandomValues && crypto.getRandomValues.bind(crypto);\n\n    if (!getRandomValues) {\n      throw new Error('crypto.getRandomValues() not supported. See https://github.com/uuidjs/uuid#getrandomvalues-not-supported');\n    }\n  }\n\n  return getRandomValues(rnds8);\n}\n\n//# sourceURL=webpack://formidable-abandonment/./node_modules/uuid/dist/esm-browser/rng.js?");

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/stringify.js":
/*!*********************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/stringify.js ***!
  \*********************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   unsafeStringify: function() { return /* binding */ unsafeStringify; }\n/* harmony export */ });\n/* harmony import */ var _validate_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./validate.js */ \"./node_modules/uuid/dist/esm-browser/validate.js\");\n\n/**\n * Convert array of 16 byte values to UUID string format of the form:\n * XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX\n */\n\nconst byteToHex = [];\n\nfor (let i = 0; i < 256; ++i) {\n  byteToHex.push((i + 0x100).toString(16).slice(1));\n}\n\nfunction unsafeStringify(arr, offset = 0) {\n  // Note: Be careful editing this code!  It's been tuned for performance\n  // and works in ways you may not expect. See https://github.com/uuidjs/uuid/pull/434\n  return byteToHex[arr[offset + 0]] + byteToHex[arr[offset + 1]] + byteToHex[arr[offset + 2]] + byteToHex[arr[offset + 3]] + '-' + byteToHex[arr[offset + 4]] + byteToHex[arr[offset + 5]] + '-' + byteToHex[arr[offset + 6]] + byteToHex[arr[offset + 7]] + '-' + byteToHex[arr[offset + 8]] + byteToHex[arr[offset + 9]] + '-' + byteToHex[arr[offset + 10]] + byteToHex[arr[offset + 11]] + byteToHex[arr[offset + 12]] + byteToHex[arr[offset + 13]] + byteToHex[arr[offset + 14]] + byteToHex[arr[offset + 15]];\n}\n\nfunction stringify(arr, offset = 0) {\n  const uuid = unsafeStringify(arr, offset); // Consistency check for valid UUID.  If this throws, it's likely due to one\n  // of the following:\n  // - One or more input array values don't map to a hex octet (leading to\n  // \"undefined\" in the uuid)\n  // - Invalid input values for the RFC `version` or `variant` fields\n\n  if (!(0,_validate_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(uuid)) {\n    throw TypeError('Stringified UUID is invalid');\n  }\n\n  return uuid;\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (stringify);\n\n//# sourceURL=webpack://formidable-abandonment/./node_modules/uuid/dist/esm-browser/stringify.js?");

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/v4.js":
/*!**************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/v4.js ***!
  \**************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _native_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./native.js */ \"./node_modules/uuid/dist/esm-browser/native.js\");\n/* harmony import */ var _rng_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./rng.js */ \"./node_modules/uuid/dist/esm-browser/rng.js\");\n/* harmony import */ var _stringify_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./stringify.js */ \"./node_modules/uuid/dist/esm-browser/stringify.js\");\n\n\n\n\nfunction v4(options, buf, offset) {\n  if (_native_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"].randomUUID && !buf && !options) {\n    return _native_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"].randomUUID();\n  }\n\n  options = options || {};\n  const rnds = options.random || (options.rng || _rng_js__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(); // Per 4.4, set bits for version and `clock_seq_hi_and_reserved`\n\n  rnds[6] = rnds[6] & 0x0f | 0x40;\n  rnds[8] = rnds[8] & 0x3f | 0x80; // Copy bytes to buffer, if provided\n\n  if (buf) {\n    offset = offset || 0;\n\n    for (let i = 0; i < 16; ++i) {\n      buf[offset + i] = rnds[i];\n    }\n\n    return buf;\n  }\n\n  return (0,_stringify_js__WEBPACK_IMPORTED_MODULE_2__.unsafeStringify)(rnds);\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (v4);\n\n//# sourceURL=webpack://formidable-abandonment/./node_modules/uuid/dist/esm-browser/v4.js?");

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/validate.js":
/*!********************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/validate.js ***!
  \********************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _regex_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./regex.js */ \"./node_modules/uuid/dist/esm-browser/regex.js\");\n\n\nfunction validate(uuid) {\n  return typeof uuid === 'string' && _regex_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"].test(uuid);\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (validate);\n\n//# sourceURL=webpack://formidable-abandonment/./node_modules/uuid/dist/esm-browser/validate.js?");

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
/******/ 	var __webpack_exports__ = __webpack_require__("./assets/src/index.js");
/******/ 	
/******/ })()
;