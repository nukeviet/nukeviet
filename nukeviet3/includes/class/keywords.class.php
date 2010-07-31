<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/28/2009 14:30
 */

if (!defined('NV_MAINFILE')) die('Stop!!!');
if (defined('_CLASS_KEYWORDS_PHP')) return;
define('_CLASS_KEYWORDS_PHP', true);

class keywords
{
	var $min_word_length = 5;
	var $min_word_occur = 2;
	var $min_2words_length = 3;
	var $min_2words_phrase_length = 5;
	var $min_2words_phrase_occur = 2;
	var $min_3words_length = 3;
	var $min_3words_phrase_length = 7;
	var $min_3words_phrase_occur = 2;
	var $commonly_used_words = "";
	var $max_strlen = 1000;
	var $result_strlen = 0;
	var $text = array();

	/**
	 * keywords::keywords()
	 * 
	 * @param mixed $min_word_length
	 * @param mixed $min_word_occur
	 * @param mixed $min_2words_length
	 * @param mixed $min_2words_phrase_length
	 * @param mixed $min_2words_phrase_occur
	 * @param mixed $min_3words_length
	 * @param mixed $min_3words_phrase_length
	 * @param mixed $min_3words_phrase_occur
	 * @param mixed $commonly_used_words
	 * @param mixed $max_strlen
	 * @return
	 */
	function keywords($min_word_length, $min_word_occur, $min_2words_length, $min_2words_phrase_length, $min_2words_phrase_occur, $min_3words_length, $min_3words_phrase_length, $min_3words_phrase_occur, $commonly_used_words, $max_strlen)
	{
		$this->min_word_length = $min_word_length;
		$this->min_word_occur = $min_word_occur;
		$this->min_2words_length = $min_2words_length;
		$this->min_2words_phrase_length = $min_2words_phrase_length;
		$this->min_2words_phrase_occur = $min_2words_phrase_occur;
		$this->min_3words_length = $min_3words_length;
		$this->min_3words_phrase_length = $min_3words_phrase_length;
		$this->min_3words_phrase_occur = $min_3words_phrase_occur;
		if ($commonly_used_words != "")
		{
			$commonly_used_words = array_map("nv_preg_quote", explode("|", $commonly_used_words));
			$this->commonly_used_words = implode("|", $commonly_used_words);
		}
		$this->max_strlen = $max_strlen;
	}

	/**
	 * keywords::occurefilter()
	 * 
	 * @param mixed $array_count_values
	 * @param mixed $min_occur
	 * @return
	 */
	function occurefilter($array_count_values, $min_occur)
	{
		$occur_filtered = array();
		foreach ($array_count_values as $word => $occured)
		{
			if ($occured >= $min_occur)
			{
				$occur_filtered[$word] = $occured;
			}
		}
		return $occur_filtered;
	}

	/**
	 * keywords::impl()
	 * 
	 * @param mixed $array
	 * @return
	 */
	function impl($array)
	{
		$c = "";
		if (is_array($array) and $array != array())
		{
			foreach ($array as $key => $val)
			{
				if ($this->result_strlen > $this->max_strlen) break;
				$c .= $key.", ";
				$this->result_strlen += nv_strlen($key);
			}
		}
		return $c;
	}

	/**
	 * keywords::parsewords()
	 * 
	 * @return
	 */
	function parsewords()
	{
		$k = array();
		foreach ($this->text as $key => $val)
		{
			if (nv_strlen(trim($val)) >= $this->min_word_length && !is_numeric(trim($val)))
			{
				$k[] = trim($val);
			}
		}
		$k = array_count_values($k);
		$occur_filtered = $this->occurefilter($k, $this->min_word_occur);
		arsort($occur_filtered);
		$imploded = $this->impl($occur_filtered);
		return $imploded;
	}

	/**
	 * keywords::parse2words()
	 * 
	 * @return
	 */
	function parse2words()
	{
		$y = array();
		for ($i = 0; $i < count($this->text) - 1; $i++)
		{
			if (nv_strlen(trim($this->text[$i])) >= $this->min_2words_length && nv_strlen(trim($this->text[$i + 1])) >= $this->min_2words_length && nv_strlen(trim($this->text[$i]).trim($this->text[$i + 1])) >= $this->min_2words_phrase_length)
			{
				$y[] = trim($this->text[$i])." ".trim($this->text[$i + 1]);
			}
		}
		$y = array_count_values($y);
		$occur_filtered = $this->occurefilter($y, $this->min_2words_phrase_occur);
		arsort($occur_filtered);
		$imploded = $this->impl($occur_filtered);
		return $imploded;
	}

	/**
	 * keywords::at_parse3words()
	 * 
	 * @return
	 */
	function parse3words()
	{
		$b = array();
		for ($i = 0; $i < count($this->text) - 2; $i++)
		{
			if (nv_strlen(trim($this->text[$i])) >= $this->min_3words_length && nv_strlen(trim($this->text[$i + 1])) > $this->min_3words_length && nv_strlen(trim($this->text[$i + 2])) >= $this->min_3words_length && nv_strlen(trim($this->text[$i]).trim($this->text[$i + 1]).trim($this->text[$i + 2])) >= $this->min_3words_phrase_length)
			{
				$b[] = trim($this->text[$i])." ".trim($this->text[$i + 1])." ".trim($this->text[$i + 2]);
			}
		}
		$b = array_count_values($b);
		$occur_filtered = $this->occurefilter($b, $this->min_3words_phrase_occur);
		arsort($occur_filtered);
		$imploded = $this->impl($occur_filtered);
		return $imploded;
	}

	/**
	 * keywords::getkeywords()
	 * 
	 * @param mixed $content
	 * @return
	 */
	function getkeywords($content)
	{
		global $global_config;
		if (empty($content)) return ("");
		if ($this->commonly_used_words != "") $content = preg_replace("/".$this->commonly_used_words."/i", " ", $content);
		nv_internal_encoding($global_config['site_charset']);
		$content = nv_strtolower($content);
		$content = strip_tags($content);
		$content = str_replace(array('&quot;', '&copy;', '&gt;', '&lt;', '&nbsp;'), " ", $content);
		$content = str_replace(array(',', ')', '(', '.', "'", '"', '<', '>', ';', '!', '?', '/', '-', '_', '[', ']', ':', '+', '=', '#', '$', chr(10), chr(13), chr(9)), " ", $content);
		$content = preg_replace('/ {2,}/si', " ", $content);
		$content = explode(" ", $content);
		if ($content == array()) return ("");
		$this->text = $content;
		$keywords = $this->parse3words();
		if ($this->result_strlen < $this->max_strlen) $keywords .= $this->parse2words();
		if ($this->result_strlen < $this->max_strlen) $keywords .= $this->parsewords();

		if ($keywords == "") return ("");
		return substr($keywords, 0, -2);
	}
}

?>