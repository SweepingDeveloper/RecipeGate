<?php

	function encryptString($text)
	{
		$cypher = array('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRSTUVWXYZ1234567890$@!^&().*',
					    '!wErTyUIOp12345j0dBCDYbVfW$stucMLPh@RGF9iHK8egxz7v6klmnoZXSNqAJa)(&^*.');


		$rev_text = strrev($text);
		//Cypher the text.
		$newString = "";
		for ($a = 0; $a < strlen($rev_text); $a++)
		{
			$char = substr($rev_text,$a,1);
			$pos = strpos($cypher[0], $char);
			if ($pos)
			{
				$newString .= substr($cypher[1], $pos, 1);
			}
			else
			{
				$newString .= substr($rev_text, $a, 1);
			}
			
		}
		return $newString;
		
	}
	
	function estring($text)
	{
		$cypher = array('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRSTUVWXYZ1234567890$@!^&().*',
					    '!wErTyUIOp12345j0dBCDYbVfW$stucMLPh@RGF9iHK8egxz7v6klmnoZXSNqAJa)(&^*.');


		$rev_text = strrev($text);
		//Cypher the text.
		$newString = "";
		for ($a = 0; $a < strlen($rev_text); $a++)
		{
			$char = substr($rev_text,$a,1);
			$pos = strpos($cypher[0], $char);
			if ($pos)
			{
				$newString .= substr($cypher[1], $pos, 1);
			}
			else
			{
				$newString .= substr($rev_text, $a, 1);
			}
			
		}
		return $newString;
		
	}



?>