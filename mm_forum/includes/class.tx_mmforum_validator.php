<?php
/**
 *  Copyright notice
 *
 *  (c) 2008 Mittwald CM Service
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   54: class tx_mmforum_validator
 *   66:     function specialChars($text)
 *  115:     function specialChars_URL($url)
 *  132:     function init($conf = null)
 *  145:     function getValidatorObject()
 *
 * TOTAL FUNCTIONS: 4
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * The validator class is designed to handle all output validating in
 * the mm_forum extension in order to ensure a high protection level
 * against e.g. cross-site scripting (XSS).
 * This class is instantiated inside the respective plugins of the mm_forum
 * extension and is initialized with the settings stored in
 * plugin.tx_mmforum_pi1.validatorSettings
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @copyright  2008 Martin Helmich, Mittwald CM Service
 * @version    2008-12-08
 * @package    mm_forum
 * @subpackage Includes
 */
class tx_mmforum_validator {

	/**
	 * Parses a string for special characters. This is the main protection
	 * function against XSS attacks. The behaviour of this function can be
	 * configured using TypoScript.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-12-08
	 * @param   string $text The text to be validated
	 * @return  string       The validated text
	 */
    function specialChars($text) {

    		// Quote handling
    	switch($this->conf['quotes']) {
    		case 'double':
    		case 'ent_compat':
    			$quotes		= ENT_COMPAT; break;
    		case 'all':
    		case 'ent_quotes':
    			$quotes		= ENT_QUOTES; break;
    		case 'none':
    		case 'ent_noquotes':
    		default:
    			$quotes		= ENT_NOQUOTES; break;
    	}

    		// Get charset
    	if ($this->conf['charset'] AND $this->conf['charset'] != 'auto')
    		$charset = $this->conf['charset'];
    	elseif ($GLOBALS['TSFE']->renderCharset)
    		$charset = $GLOBALS['TSFE']->renderCharset;
    	else $charset = 'UTF-8';

			// Remove tags
		if ($this->conf['stripTags'])
			$text = strip_tags($text);

			// Replace all specialchars with HTML entities if configured
		if ($this->conf['replace'] == 'all')
			return htmlentities($text, $quotes, $charset);
	    else return htmlspecialchars($text, $quotes, $charset);
    }

	/**
	 * Parses an URL.
	 * This function parses an URL for output in the frontend. This function is
	 * not primarily meant as a protection against XSS but rather to make URLs
	 * generated by the mm_forum XHTML-valid.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-06-22
	 * @param   string $url The URL to be parsed
	 * @return  string      The parsed URL
	 */
    function specialChars_URL($url) {
    	return htmlspecialchars($url);
    }

	/**
	 * Initializes the validator object.
	 * This function initializes the validator object by loading the validator
	 * configuration variables from the global TypoScript array. The validator
	 * configuration is located in plugin.tx_mmforum_pi1.validatorSettings.
	 *
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-06-22
	 * @param   array $conf The validator configuration array. If this parameter is
	 *                      left empty, the default configuration is used.
	 * @return  void
	 */
	function init($conf = null) {
		if($conf === null AND isset($GLOBALS['TSFE'])) {
			$this->conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_mmforum_pi1.']['validatorSettings.'];
		} else $this->conf = $conf;
	}

	/**
	 * Instantiates the validator object and return the instance.
	 * @author  Martin Helmich <m.helmich@mittwald.de>
	 * @version 2008-06-22
	 * @return  tx_mmforum_validator A validator object
	 */
	function getValidatorObject() {
		$validatorObj		= t3lib_div::makeInstance('tx_mmforum_validator');
		$validatorObj->init();
		return $validatorObj;
	}

}

	// XClass inclusion
if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/includes/class.tx_mmforum_validator.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/includes/class.tx_mmforum_validator.php"]);
}
?>