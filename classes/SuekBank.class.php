<?php
class SuekBank extends \Jejik\MT940\Parser\AbstractParser
{
	/**
	 * Test if the document is an ABN-AMRO document
	 *
	 * @param string $text
	 * @return bool
	 */
	public function accept($text)
	{
	    //return substr($text, 0, 6) === 'ABNANL';
	    return true;
	}
}