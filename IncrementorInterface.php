<?php
/**
 * @package    SugiPHP
 * @subpackage Cache
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Cache;

/**
 * Cache Incrementor Interface
 */
interface IncrementorInterface
{
	/**
	 * Increment stored numeric value by $step.
	 * If a stored value is not numeric, or there is no such key (not set or expired) the function MUST return FALSE.
	 * 
	 * @param  string  $key
	 * @param  integer $step
	 * @return integer|FALSE - incremented value or FALSE on failure
	 */
	function inc($key, $step = 1);

	/**
	 * Decrement stored numeric value by $step
	 * If a stored value is not numeric, or there is no such key (not set or expired) the function MUST return FALSE.
	 *  
	 * @param  string  $key
	 * @param  integer $step
	 * @return integer|FALSE - incremented value or FALSE on failure
	 */
	function dec($key, $step = 1);
}
