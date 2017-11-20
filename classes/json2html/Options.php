<?php
/**
 * Created by PhpStorm.
 * User: glazkov
 * Date: 20.11.17
 * Time: 12:37
 */

namespace json2html;

use Pimple\Container;

/**
 * Class Options
 * @package json2html
 */
class Options extends Container
{
	/**
	 * Options constructor.
	 *
	 * @param array $options
	 */
	public function __construct(array $options = [])
	{
		$defaultOptions = [
			'isOnlyColorize' => false
		];

		parent::__construct(array_merge($defaultOptions, $options));
	}

	public function setIsOnlyColorize(bool $is = false)
	{
		$this->offsetSet('isOnlyColorize', $is);
	}

	public function getIsOnlyColorize(): bool
	{
		return (bool)$this->offsetGet('isOnlyColorize');
	}
}