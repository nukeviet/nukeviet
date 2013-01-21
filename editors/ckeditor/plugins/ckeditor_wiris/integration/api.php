<?php

//
//  Copyright (c) 2011, Maths for More S.L. http://www.wiris.com
//  This file is part of WIRIS Plugin.
//
//  WIRIS Plugin is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, either version 3 of the License, or
//  any later version.
//
//  WIRIS Plugin is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with WIRIS Plugin. If not, see <http://www.gnu.org/licenses/>.
//

require_once dirname(__FILE__) . '/libwiris.php';

class com_wiris_plugin_PluginAPI {
	private $formulaDirectory;
	private $TAGS;
	
	public function com_wiris_plugin_PluginAPI() {
		$this->formulaDirectory = wrs_getFormulaDirectory(wrs_loadConfig(WRS_CONFIG_FILE));
		$this->TAGS = new stdClass();
	}

	public function mathml2img($mathml, $baseURL, $properties = array()) {
		$parsedProperties = array();
		global $wrs_xmlFileAttributes;

		foreach ($properties as $key => $value) {
			if (in_array($key, $wrs_xmlFileAttributes) || substr($key, 0, 4) == 'font') {
				$parsedProperties[$key] = $value;
			}
		}

		$parsedProperties['mml'] = $mathml;
		$toSave = wrs_createIni($parsedProperties);
		$fileName = md5($toSave);
		$url = $baseURL . '/showimage.php?formula=' . $fileName . '.png';
		$filePath = $this->formulaDirectory . '/' . $fileName . '.ini';

		if (!is_file($filePath) && file_put_contents($filePath, $toSave) === false) {
			throw new Exception('Unable to create formula file.');
		}

		return $url;
	}

	public function mathml2accessible($data){
		$config = wrs_loadConfig(WRS_CONFIG_FILE);
		$url = wrs_getImageServiceURL($config, 'mathml2accessible');
		$response = wrs_getContents($config, $url, $data);
		
		return $response;
	}	
	
	private function initfilter($type){
		global $CFG;
		require_once($CFG->libdir . '/textlib.class.php');

		if ($type == 'safeXML'){
			$convertor = textlib_get_instance();
			$this->TAGS->in_open = $convertor->convert('«', 'utf8');
			$this->TAGS->in_close = $convertor->convert('»', 'utf8');
			$this->TAGS->in_entity = $convertor->convert('§', 'utf8');
			$this->TAGS->in_appletopen = $convertor->convert('«applet', 'utf8');
			$this->TAGS->in_appletclose = $convertor->convert('«/applet»', 'utf8');			
			$this->TAGS->in_mathopen = $convertor->convert('«math', 'utf8');
			$this->TAGS->in_mathclose = $convertor->convert('«/math»', 'utf8');		
			$this->TAGS->in_double_quote = $convertor->convert('¨', 'utf8');
			$this->TAGS->out_open = '<';
			$this->TAGS->out_close = '>';
			$this->TAGS->out_entity = '&';
			$this->TAGS->out_double_quote = '"';
		}else if ($type == 'mathml'){
			$this->TAGS->in_open = '<';
			$this->TAGS->in_close = '>';
			$this->TAGS->in_entity = '&';
			$this->TAGS->in_appletopen = '<applet';
			$this->TAGS->in_appletclose = '</applet>';			
			$this->TAGS->in_mathopen = '<math';
			$this->TAGS->in_mathclose = '</math>';		
			$this->TAGS->in_double_quote = '"';
			$this->TAGS->out_open = '<';
			$this->TAGS->out_close = '>';
			$this->TAGS->out_entity = '&';
			$this->TAGS->out_double_quote = '"';
		}
	}        
        
	function filter_math($text, $type) {
		$this->initfilter($type);
		$output = ''; 
		$n0 = 0;
		// Search for '«math'. If it is not found, the
		// content is returned without any modification
		$n1 = stripos($text, $this->TAGS->in_mathopen);

		if($n1 === false) {
				return $text; // directly return the content
		}

		// filtering
		while($n1 !== false) {
			$output .= substr($text, $n0, $n1 - $n0);
			$n0 = $n1;
			$n1 = stripos($text, $this->TAGS->in_mathclose, $n0);
			if(!$n1) {
				break;
			}
			$n1 = $n1 + strlen($this->TAGS->in_mathclose);
			// Getting the substring «math ... «/math>
			$sub = substr($text, $n0, $n1 - $n0);

			/*
				* This filter does the following replacement inside the <math> tags.
				*   <a href="<url>">blabla</a>  -->  <url>
				* 
				* The reason is that Moodle replaces URL's with HTML links ('A' tags) and ignores the <span class="nolink"> tag.
				*/
			$pattern = '/<a href="[^"]*" target="_blank">([^<]*)<\/a>/';
			$replacement = '\1';
			$sub = preg_replace($pattern, $replacement, $sub);

			if ($type == 'safeXML'){
				$sub = html_entity_decode($sub, ENT_COMPAT);
				// replacing '¨' by '"'
				$sub = str_replace($this->TAGS->in_double_quote, $this->TAGS->out_double_quote, $sub);			
				// replacing '«' by '<'
				$sub = str_replace($this->TAGS->in_open, $this->TAGS->out_open, $sub);
				// replacing '»' by '>'
				$sub = str_replace($this->TAGS->in_close, $this->TAGS->out_close, $sub);
				// replacing '§' by '&'
				$sub = str_replace($this->TAGS->in_entity, $this->TAGS->out_entity, $sub);
			}

			// generate the image code
			$sub = $this->math_image($sub);                
			
			// appending the modified substring
			$output .= $sub;
			$n0 = $n1;
			// searching next '«math'
			$n1 = stripos($text, $this->TAGS->in_mathopen, $n0);
		}
		$output .= substr($text, $n0);
		return $output;
	}        

	function filter_applet($text){
		$this->initfilter('safeXML');
		$output = ''; 
		$n0 = 0;
		// Search for '«applet'. If it is not found, the
		// content is returned without any modification

		$n1 = stripos($text, $this->TAGS->in_appletopen);

		if($n1 === false) {
				return $text; // directly return the content
		}

		// filtering
		while($n1 !== false) {

			$output .= substr($text, $n0, $n1 - $n0);

			$n0 = $n1;
			$n1 = stripos($text, $this->TAGS->in_appletclose, $n0);
			if(!$n1) {
					break;
			}
			$n1 = $n1 + strlen($this->TAGS->in_appletclose);
			// Getting the substring «applet ... «/applet»
			$sub = substr($text, $n0, $n1 - $n0);

			/*
				* This filter does the following replacement inside the <math> tags.
				*   <a href="<url>">blabla</a>  -->  <url>
				* 
				* The reason is that Moodle replaces URL's with HTML links ('A' tags) and ignores the <span class="nolink"> tag.
				*/
			$pattern = '/<a href="[^"]*" target="_blank">([^<]*)<\/a>/';
			$replacement = '\1';
			$sub = preg_replace($pattern, $replacement, $sub);

			// replacing '¨' by '"'
			$sub = str_replace($this->TAGS->in_double_quote, $this->TAGS->out_double_quote, $sub);
			// replacing '§' by '&'
			$sub = str_replace($this->TAGS->in_entity, $this->TAGS->out_entity, $sub);
			// replacing '«' by '<'
			$sub = str_replace($this->TAGS->in_open, $this->TAGS->out_open, $sub);
			// replacing '»' by '>'
			$sub = str_replace($this->TAGS->in_close, $this->TAGS->out_close, $sub);

			$output .= $sub;

			$n0 = $n1;
			// searching next '«applet'
			$n1 = stripos($text, $this->TAGS->in_appletopen, $n0);
		}
		$output .= substr($text, $n0);
		return $output;
	}	
        
	/*
	 * Generate the html IMG code corresponding to the specified MathML expression
	 */
	private function math_image($mathml) {
		global $CFG;

		include $CFG->dirroot . '/lib/editor/tinymce/version.php';

		$src = $this->mathml2img($mathml, $CFG->wwwroot . "/lib/editor/tinymce/tiny_mce/" . $plugin->release . "/plugins/tiny_mce_wiris/integration");
		
		$lang = substr(current_language(), 0, 2);
		$data = array('mml' => $mathml, 'lang' => $lang);
		$accessible = $this->mathml2accessible($data);
				
		$output = '<img align="middle" ';
		$output .= 'src="' . $src . '" ';
		$output .= 'alt="' . $accessible . '" ';
		$output .= ' />'; 

		return $output;
	}        
}
?>