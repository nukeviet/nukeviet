<?php

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'xtemplate.class.php');

/**
 * CachingXTemplate
 * Extension to XTemplate to provide block level and whole template caching facilities
 * Needs Web server writable directory
 *
 * @package XTemplate
 * @subpackage CachingXTemplate
 * @uses XTemplate
 * @author Jeremy Coates [cocomp@users.sourceforge.net]
 * @copyright Jeremy Coates / Co-Comp Ltd 2006-2007
 * @see license.txt BSD license
 * @since PHP 5
 * @link $HeadURL$
 * @version $Id$
 *
 * @example Whole template level caching (e.g. the total parsed output for the file)
 * @example $xtpl = new CachingXTemplate('template.xtpl', '', null, 'main', true, 600, session_id(), './xcache', '.xcache');
 *
 * @example Alternatively (and perhaps more useful in real world):
 * @example Block level caching
 * @example $xtpl = new CachingXTemplate('template.xtpl', '', null, 'main', true, 0, session_id(), './xcache', '.xcache');
 * @example $xtpl->parse('main', 600);
 * @example Bear in mind that because XTemplate uses a reversed parsing tree the innermost blocks need to be parsed
 * @example first, therefore if you cache an outer block, don't be surprised when it's inner content blocks don't update!
 */
class CachingXTemplate extends XTemplate {

	/**
	 * Cache expiry time (seconds)
	 *
	 * @access public
	 * @var int
	 */
	public $cache_expiry	= 0;

	/**
	 * Cache file unique identifier
	 *
	 * @example session_id()
	 * @access public
	 * @var string
	 */
	public $cache_unique	= 'unique';

	/**
	 * Filename extension
	 *
	 * @example .xcache
	 * @access public
	 * @var string
	 */
	public $cache_ext		= '.xcache';

	/**
	 * Path to cache dir
	 * Needs to be writable by webserver
	 *
	 * @example ./xcache
	 * @access public
	 * @var string
	 */
	public $cache_dir		= './xcache';

	/**
	 * Flag showing whether template is cached
	 *
	 * @access private
	 * @var boolean
	 */
	private $_template_is_cached	= false;

	/**
	 * Cache expiry time
	 *
	 * @access private
	 * @var int
	 */
	private $_cache_expiry			= 0;

	/**
	 * File modified time
	 *
	 * @access private
	 * @var int
	 */
	private $_cache_filemtime		= 0;

	/**
	 * Override of parent constructor
	 *
	 * @access public
     * @param string $file Template file to work on
     * @param string $tpldir Location of template files (useful for keeping files outside web server root)
     * @param array $files Filenames lookup
     * @param string $mainblock Name of main block in the template
     * @param boolean $autosetup If true, run setup() as part of constuctor
	 * @param int $cache_expiry Seconds to cache for
	 * @param string $cache_unique Unique file id (e.g. session_id())
	 * @param string $cache_dir Cache folder
	 * @param string $cache_ext Cache file extension
	 */
	public function __construct($file, $tpldir = '', $files = null, $mainblock = 'main', $autosetup = true, $cache_expiry = 0, $cache_unique = '', $cache_dir = './xcache', $cache_ext = '.xcache') {

		$this->restart($file, $tpldir, $files, $mainblock, $autosetup, $this->tag_start_delim, $this->tag_end_delim, $cache_expiry, $cache_unique, $cache_dir, $cache_ext);

	}

	/**
	 * Override of parent restart method
	 *
	 * @access public
	 * @param string $file Template file to work on
	 * @param string $tpldir Location of template files
	 * @param array $files Filenames lookup
	 * @param string $mainblock Name of main block in the template
	 * @param boolean $autosetup If true, run setup() as part of restarting
	 * @param string $tag_start {
	 * @param string $tag_end }
	 * @param int $cache_expiry Seconds to cache for
	 * @param string $cache_unique Unique file id (e.g. session_id())
	 * @param string $cache_dir Cache folder
	 * @param string $cache_ext Cache file extension
	 */
	public function restart ($file, $tpldir = '', $files = null, $mainblock = 'main', $autosetup = true, $tag_start = '{', $tag_end = '}', $cache_expiry = 0, $cache_unique = '', $cache_dir = './xcache', $cache_ext = '.xcache') {

		if ($cache_expiry > 0) {
			$this->cache_expiry = $cache_expiry;
		}

		if (!empty($cache_unique)) {
			if (!preg_match('/^\./', $cache_unique)) {
				$cache_unique = '.' . $cache_unique;
			}
			$this->cache_unique = $cache_unique;
		}

		if (!empty($cache_dir)) {
			$this->cache_dir = $cache_dir;
		}

		if (!empty($cache_ext)) {
			if (!preg_match('/^\./', $cache_ext)) {
				$cache_ext = '.' . $cache_ext;
			}
			$this->cache_ext = $cache_ext;
		}

		// Call parent restart method but don't run setup yet!
		parent::restart($file, $tpldir, $files, $mainblock, false, $tag_start, $tag_end);

		if ($this->cache_expiry > 0) {
			$this->read_template_cache();
		}

		if (!$this->_template_is_cached && $autosetup) {
			$this->setup();
		}
	}

	/**
	 * Override of parent assign method
	 *
	 * @access public
     * @param string $name Variable to assign $val to
     * @param string / array $val Value to assign to $name
	 * @param boolean $magic_quotes
	 */
	public function assign ($name, $val = '', $magic_quotes = false) {

		if (!$this->_template_is_cached) {
			parent::assign($name, $val, $magic_quotes);
		}
	}

	/**
	 * Override of parent assign_file method
	 *
     * @access public
     * @param string $name Variable to assign $val to
     * @param string / array $val Values to assign to $name
	 */
	public function assign_file ($name, $val = '') {

		if (!$this->_template_is_cached) {
			parent::assign_file($name, $val);
		}
	}

	/**
	 * Override of parent parse method
	 *
     * @access public
     * @param string $bname Block name to parse
	 * @param int $cache_expiry Seconds to cache block for
	 */
	public function parse ($bname, $cache_expiry = 0) {

		if (!$this->_template_is_cached) {

			if (!$this->read_block_cache($bname, $cache_expiry)) {

				parent::parse($bname);

				$this->write_block_cache($bname, $cache_expiry);
			}
		}
	}

	/**
	 * Override of parent text method
	 *
     * @access public
     * @param string $bname Block name to return
     * @return string
	 */
	public function text ($bname = '') {

		$text = parent::text($bname);

		if (!$this->_template_is_cached && $this->cache_expiry > 0) {

			$this->write_template_cache();

		} elseif ($this->debug && $this->output_type == 'HTML') {

			$text_header = "<!-- CachingXTemplate debug:\n";

			if ($this->cache_expiry > 0) {

				$filename = $this->_get_filename();

				$file = $this->cache_dir . DIRECTORY_SEPARATOR . $filename . $this->cache_unique . $this->cache_ext;

				$text_header .= 'File: ' . $file . "\nExpires in: " . ($this->_cache_filemtime - $this->_cache_expiry) . " seconds -->\n";
			} else {
				$text_header .= "Template Cache (whole template) disabled -->\n";
			}

			$text = $text_header . $text;
		}

		return $text;
	}

	/**
	 * Read whole template cache file
	 *
	 * @access protected
	 */
	protected function read_template_cache () {

		$filename = $this->_get_filename();

		$file = $this->cache_dir . DIRECTORY_SEPARATOR . $filename . DIRECTORY_SEPARATOR . $this->cache_unique . $this->cache_ext;

		if ($this->cache_expiry > 0 && file_exists($file)) {

			$this->_cache_filemtime = filemtime($file);
			$this->_cache_expiry = time() - $this->cache_expiry;

			if ($this->_cache_filemtime >= $this->_cache_expiry) {
				if ($parsed_blocks = file_get_contents($file)) {
					$this->parsed_blocks = unserialize($parsed_blocks);
					$this->_template_is_cached = true;
				}
			} else {
				// Stale file
				if (is_writable($this->cache_dir) && is_writable($file)) {
					unlink($file);
				}
			}
		}
	}

	/**
	 * Write out whole template cache file
	 *
	 * @access protected
	 */
	protected function write_template_cache () {

		if ($this->cache_expiry > 0 && is_writable($this->cache_dir)) {

			$filename = $this->_get_filename();

			if (!file_exists($this->cache_dir . DIRECTORY_SEPARATOR . $filename)) {
				mkdir($this->cache_dir . DIRECTORY_SEPARATOR . $filename);
			}

			file_put_contents($this->cache_dir . DIRECTORY_SEPARATOR . $filename . DIRECTORY_SEPARATOR . $this->cache_unique . $this->cache_ext, serialize($this->parsed_blocks));
		}
	}

	/**
	 * Read block level cache file
	 *
	 * @access protected
	 * @param string $bname Block name to read from cache
	 * @param ing $cache_expiry Seconds to cache block for
	 * @return boolean
	 */
	protected function read_block_cache ($bname, $cache_expiry = 0) {

		$retval = false;

		$filename = $this->_get_filename();

		$file = $this->cache_dir . DIRECTORY_SEPARATOR . $filename . DIRECTORY_SEPARATOR . $bname . $this->cache_unique . $this->cache_ext;

		if ($cache_expiry > 0 && file_exists($file)) {

			$filemtime = filemtime($file);
			$cache_expiry = time() - $cache_expiry;

			if ($filemtime >= $cache_expiry) {
				if ($block = file_get_contents($file)) {
					$block = unserialize($block);
					if ($this->debug) {
						$block = "<!-- CachingXTemplate debug:\nFile: " . $file . "\nBlock: " . $bname . "\nExpires in: " . ($filemtime - $cache_expiry) . ' seconds -->' . "\n" . $block;
					}
					$this->parsed_blocks[$bname] = $block;
					$retval = true;
				}
			} else {
				// Stale file
				if (is_writable($this->cache_dir) && is_writable($file)) {
					unlink($file);
				}
			}
		}

		return $retval;
	}

	/**
	 * Write out block level cache file
	 *
	 * @access protected
	 * @param string $bname Block name to cache
	 * @param int $cache_expiry Seconds to cache block for
	 */
	protected function write_block_cache ($bname, $cache_expiry = 0) {

		if ($cache_expiry > 0 && is_writable($this->cache_dir)) {

			$filename = $this->_get_filename();

			if (!file_exists($this->cache_dir . DIRECTORY_SEPARATOR . $filename)) {
				mkdir($this->cache_dir . DIRECTORY_SEPARATOR . $filename);
			}

			file_put_contents($this->cache_dir . DIRECTORY_SEPARATOR . $filename . DIRECTORY_SEPARATOR . $bname . $this->cache_unique . $this->cache_ext, serialize($this->parsed_blocks[$bname]));
		}
	}

	/**
	 * Create the main part of the cache filename
	 *
	 * @access private
	 * @return string
	 */
	private function _get_filename () {

		$filename = $this->filename;
		if (!empty($this->tpldir)) {

			$filename = str_replace(DIRECTORY_SEPARATOR, '_', $this->tpldir . DIRECTORY_SEPARATOR) . $this->filename;
		}

		return $filename;
	}
}

?>