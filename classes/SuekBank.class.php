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
	public function accountCurrency($text){
		if ($number = $this->getLine('34F', $text)) {
		    return substr($number,0,3);
		}

		return null;
		return "$text";
	}

	// protected function statementNumber($text)
	// {
	// 	//echo util::var_dump($text, TRUE,1,"statementNumber");
	//     if ($number = $this->getLine('28C', $text)) {
	//         return $number;
	//     }

	//     return null;
	// }
	// protected function closingBalance($text)
	// {
	//     if ($line = $this->getLine('90D|90C', $text)) {
	//         return $this->balance($this->reader->createClosingBalance(), $line);
	//     }
	// }
	protected function contraAccountNumber(array $lines)
	    {
	        if (!isset($lines[1])) {
	            return null;
	        }

	        if (preg_match('/^([0-9.]{11,14}) /', $lines[1], $match)) {
	            return str_replace('.', '', $match[1]);
	        }

	        if (preg_match('/^GIRO([0-9 ]{9}) /', $lines[1], $match)) {
	            return trim($match[1]);
	        }

	        return null;
	    }

	    /**
	     * Get the contra account holder name from a transaction
	     *
	     * There is only a countra account name if there is a contra account number
	     * The name immediately follows the number in the first 32 characters of the first line
	     * If the charaters up to the 32nd after the number are blank, the name is found in
	     * the rest of the line.
	     *
	     * @param array $lines The transaction text at offset 0 and the description at offset 1
	     * @return string|null
	     */
	    protected function contraAccountName(array $lines)
	    {
	    	//echo util::var_dump($lines, TRUE,1,"lines");
	        if (!isset($lines[1])) {
	            return null;
	        }
	        $line = strstr($lines[1], "\r\n", true) ?: $lines[1];
	        $parts=explode("?",$line);
	        if($parts[1])return $parts[1];
	        return null;
	    }
}