<?php

/**
 * Created by PhpStorm.
 * User: i.glazkov
 * Date: 26.11.15
 * Time: 14:03
 */
class PrettyJSON
{
	const COLOR_GRAY = 'gray';

	private $data = [];
	private $error = '';

	/**
	 * @param string $json
	 *
	 * @throws \Exception
	 */
	public function setJsonText($json)
	{
		if (!is_string($json)) {
			throw new \Exception('Param "json" not a string');
		}

		$this->data = json_decode($json, true, 1024);

		if (json_last_error() != JSON_ERROR_NONE) {
			$this->data  = [];
			$this->error = static::descriptionJsonParseErrorByCode(json_last_error());
		}
	}

	/**
	 * Description Error parsing JSON
	 * @see http://php.net/manual/en/function.json-last-error.php
	 *
	 * @param int $jsonErrorCode
	 *
	 * @return string
	 */
	private static function descriptionJsonParseErrorByCode($jsonErrorCode)
	{
		$description = [
			JSON_ERROR_DEPTH          => 'Maximum stack depth exceeded',
			JSON_ERROR_STATE_MISMATCH => 'Underflow or the modes mismatch',
			JSON_ERROR_CTRL_CHAR      => 'Unexpected control character found',
			JSON_ERROR_SYNTAX         => 'Syntax error, malformed JSON',
			JSON_ERROR_UTF8           => 'Unknown error'
		];

		return array_key_exists($jsonErrorCode, $description) ? $description[$jsonErrorCode] : 'Undefined error';
	}

	/**
	 * @return bool
	 */
	public function isError()
	{
		return strlen($this->error) > 0;
	}

	/**
	 * @return string
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 * @return string
	 */
	public function getHtml()
	{
		if (is_array($this->data)) {
			return static::printHtml($this->data);
		} else {
			return static::renderSimpleValue($this->data);
		}
	}

	/**
	 * @param array   $data
	 * @param string  $headerKey
	 * @param boolean $isEndComma
	 *
	 * @return string
	 */
	private static function printHtml($data, $headerKey = null, $isEndComma = false)
	{
		if (is_null($data)) {
			return static::renderSimpleValue($data);
		}

		$html    = '<div style="font-family: \'droid sans mono\', consolas, monospace, \'courier new\', courier, sans-serif, monospace;white-space: pre;">';
		$isAssoc = static::isAssoc($data);
		$keyHtml = '';

		if ($headerKey) {
			$keyHtml = '<span style="color:#404040;">"' . $headerKey . '"</span>';
			$keyHtml .= '<span style="color:#A1A1A1;">:&nbsp;</span>';
		}

		$html .= '<div style="color:#A1A1A1;">' . $keyHtml . ($isAssoc ? '{' : '[') . '</div>';
		$html .= '<div style="padding-left:20px;">';

		$count     = count($data);
		$iteration = 0;

		foreach ($data as $key => $value) {
			$isComma = $iteration++ < $count - 1;

			if (is_array($value)) {
				$html .= static::printHtml($value, $isAssoc ? $key : null, $isComma);
			} else {
				$html .= '<div>';

				if ($isAssoc) {
					$html .= '<span style="color: #404040;">"' . $key . '"</span>';
					$html .= '<span style="color:#A1A1A1;">:&nbsp;</span>';
				}

				$html .= static::renderSimpleValue($value);

				if ($isComma) {
					$html .= '<span style="color:#A1A1A1;">,</span>';
				}

				$html .= '</div>';
			}
		}

		$html .= '</div>';
		$html .= '<div style="color:#A1A1A1;">' . ($isAssoc ? '}' : ']') . ($isEndComma ? ',' : '') . '</div>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Render simple value
	 *
	 * @param $value
	 *
	 * @return string
	 */
	private static function renderSimpleValue($value):string
	{
		$type = gettype($value);

		switch ($type) {
			case 'string':
				return '<span style="color: #45A139;">"' . $value . '"</span>';
			case 'number':
			case 'integer':
			case 'double':
				return '<span style="color: #FF6F6F">' . $value . '</span>';
			case 'boolean':
				$str = [true => 'true', false => 'false'];

				return '<span style="color: #FFA32D;">' . $str[$value] . '</span>';
			case 'NULL':
				$color = static::COLOR_GRAY;

				return "<span style=\"color:{$color};\">null</span>";
			default:
				return '<span>' . $value . ' (' . $type . ')</span>';
		}
	}

	/**
	 * @param array $array
	 *
	 * @return bool
	 */
	private static function isAssoc(array $array)
	{
		return array_keys($array) !== range(0, count($array) - 1);
	}

}