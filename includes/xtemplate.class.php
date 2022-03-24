<?php

/**
 * XTemplate PHP templating engine
 *
 * @package XTemplate
 * @author Barnabas Debreceni [cranx@users.sourceforge.net]
 * @copyright Barnabas Debreceni 2000-2001
 * @author Jeremy Coates [cocomp@users.sourceforge.net]
 * @copyright Jeremy Coates 2002-2007
 * @see license.txt LGPL / BSD license
 * @since PHP 5
 * @link $HeadURL$
 * @version $Id$
 *
 *
 * XTemplate class - http://www.phpxtemplate.org/ (x)html / xml generation with templates - fast & easy
 * Latest stable & Subversion versions available @ http://sourceforge.net/projects/xtpl/
 * License: LGPL / BSD - see license.txt
 * Changelog: see changelog.txt
 */
class XTemplate
{
    /**
     * Properties
     */

    /**
     * Raw contents of the template file
     *
     * @access public
     * @var string
     */
    public $filecontents = '';

    /**
     * Unparsed blocks
     *
     * @access public
     * @var array
     */
    public $blocks = [];

    /**
     * Parsed blocks
     *
     * @var array
     */
    public $parsed_blocks = [];

    /**
     * Preparsed blocks (for file includes)
     *
     * @access public
     * @var array
     */
    public $preparsed_blocks = [];

    /**
     * Block parsing order for recursive parsing
     * (Sometimes reverse :)
     *
     * @access public
     * @var array
     */
    public $block_parse_order = [];

    /**
     * Store sub-block names
     * (For fast resetting)
     *
     * @access public
     * @var array
     */
    public $sub_blocks = [];

    /**
     * Variables array
     *
     * @access public
     * @var array
     */
    public $vars = [];

    /**
     * File variables array
     *
     * @access public
     * @var array
     */
    public $filevars = [];

    /**
     * Filevars' parent block
     *
     * @access public
     * @var array
     */
    public $filevar_parent = [];

    /**
     * File caching during duration of script
     * e.g. files only cached to speed {FILE "filename"} repeats
     *
     * @access public
     * @var array
     */
    public $filecache = [];

    /**
     * Location of template files
     *
     * @access public
     * @var string
     */
    public $tpldir = '';

    /**
     * Filenames lookup table
     *
     * @access public
     * @var null
     */
    public $files = null;

    /**
     * Template filename
     *
     * @access public
     * @var string
     */
    public $filename = '';

    /**
     * Delimiter character used for preg_* function calls
     *
     * @access public
     * @var string
     */
    public $preg_delimiter = '`';

    // moved to setup method so uses the tag_start & end_delims
    /**
     * RegEx for file includes
     *
     * "/\{FILE\s*\"([^\"]+)\"\s*\}/m";
     *
     * @access public
     * @var string
     */
    public $file_delim = '';

    /**
     * RegEx for file include variable
     *
     * "/\{FILE\s*\{([A-Za-z0-9\._\x7f-\xff]+?)\}\s*\}/m";
     *
     * @access public
     * @var string
     */
    public $filevar_delim = '';

    /**
     * RegEx for file includes with newlines
     *
     * "/^\s*\{FILE\s*\{([A-Za-z0-9\._\x7f-\xff]+?)\}\s*\}\s*\n/m";
     *
     * @access public
     * @var string
     */
    public $filevar_delim_nl = '';

    /**
     * Template block start delimiter
     *
     * @access public
     * @var string
     */
    public $block_start_delim = '<!-- ';

    /**
     * Template block end delimiter
     *
     * @access public
     * @var string
     */
    public $block_end_delim = '-->';

    /**
     * Template block start word
     *
     * @access public
     * @var string
     */
    public $block_start_word = 'BEGIN:';

    /**
     * Template block end word
     *
     * The last 3 properties and this make the delimiters look like:
     * @example <!-- BEGIN: block_name -->
     * if you use the default syntax.
     *
     * @access public
     * @var string
     */
    public $block_end_word = 'END:';

    /**
     * Template tag start delimiter
     *
     * This makes the delimiters look like:
     * @example {tagname}
     * if you use the default syntax.
     *
     * @access public
     * @var string
     */
    public $tag_start_delim = '{';

    /**
     * Template tag end delimiter
     *
     * This makes the delimiters look like:
     * @example {tagname}
     * if you use the default syntax.
     *
     * @access public
     * @var string
     */
    public $tag_end_delim = '}';

    /**
     * Delimeter character for comments withing tags and blocks
     * Should also be in XTemplate::$comment_preg
     *
     * @var string
     */
    public $comment_delim = '#';

    /**
     * Regular expression element for comments within tags and blocks
     *
     * @example {tagname#My Comment}
     * @example {tagname #My Comment}
     * @example <!-- BEGIN: blockname#My Comment -->
     * @example <!-- BEGIN: blockname #My Comment -->
     *
     * @access public
     * @var string
     */
    public $comment_preg = '( ?#.*?)?';

    /**
     * Delimiter character for callbacks within tags
     * Should also be in XTemplate::$callback_preg
     *
     * @var string
     */
    public $callback_delim = '|';

    /**
     * Regular expression elements for callback functions within tags
     *
     * @example {tagname|my_callback_func(true, %s)} - tagname contents passed at %s point
     * @example {tagname|my_callback_func} - tagname contents passed as single argument
     * @example {tagname|first_callback|second_callback('#value', true, %s)|third_callback #Comment}
     * @example If you want quotes within your quoted strings, you'll need to escape them with \
     * @example {tagname|callback('I hope this won\'t break')
     *
     * @access public
     * @var string
     */
    public $callback_preg = '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(\(.*?\))?';

    /**
     * Whether to enable callback feature or not
     *
     * @access public
     * @var boolean
     */
    public $allow_callbacks = true;

    /**
     * Allowed callback functions
     *
     * Small security limiter - stops everything being available
     * For reference, all methods in sub-classes are available, this only applies to function calls
     *
     * @access public
     * @var array
     */
    public $allowed_callbacks = [
        // Simple string modifiers
        'strtoupper', 'strtolower', 'ucwords', 'ucfirst', 'strrev', 'str_word_count', 'strlen',
        // String replacement modifiers
        'str_replace', 'str_ireplace', 'preg_replace', 'strip_tags', 'stripcslashes', 'stripslashes', 'substr',
        'str_pad', 'str_repeat', 'strtr', 'trim', 'ltrim', 'rtrim', 'nl2br', 'wordwrap', 'printf', 'sprintf',
        'addslashes', 'addcslashes',
        // Encoding / decoding modifiers
        'htmlentities', 'html_entity_decode', 'htmlspecialchars', 'htmlspecialchars_decode',
        'urlencode', 'urldecode',
        // Date / time modifiers
        'date', 'idate', 'strtotime', 'strftime', 'getdate', 'gettimeofday',
        // Number modifiers
        'number_format', 'money_format',
        // Miscellaneous modifiers
        'var_dump', 'print_r'
    ];

    /**
     * Default main template block name
     *
     * @access public
     * @var string
     */
    public $mainblock = 'main';

    /**
     * Script output type
     *
     * @access public
     * @var string
     */
    public $output_type = 'HTML';

    /**
     * Null string for unassigned vars
     *
     * @access protected
     * @var array
     */
    protected $_null_string = ['' => ''];

    /**
     * Null string for unassigned blocks
     *
     * @access protected
     * @var array
     */
    protected $_null_block = ['' => ''];

    /**
     * Errors
     *
     * @access protected
     * @var string
     */
    protected $_error = '';

    /**
     * Auto-reset sub blocks
     *
     * @access protected
     * @var boolean
     */
    protected $_autoreset = true;

    /**
     * Set to FALSE to generate errors if a non-existant blocks is referenced
     *
     * @author NW
     * @since 2002/10/17
     * @access protected
     * @var boolean
     */
    protected $_ignore_missing_blocks = true;

    /**
     * PHP 5 Constructor - Instantiate the object
     *
     * @param array $options Options array (was $file)
     * @param string/array $tpldir Location of template files (useful for keeping files outside web server root)
     * @param array $files Filenames lookup
     * @param string $mainblock Name of main block in the template
     * @param boolean $autosetup If true, run setup() as part of constuctor
     * @return XTemplate
     */
    public function __construct($options, $tpldir = '', $files = null, $mainblock = 'main', $autosetup = true)
    {
        /**
         * Support deprecated multi-param constructor behaviour
         */
        if (!is_array($options)) {
            $options = ['file' => $options, 'path' => $tpldir, 'files' => $files, 'mainblock' => $mainblock, 'autosetup' => $autosetup];
        }

        if (!isset($options['tag_start'])) {
            $options['tag_start'] = $this->tag_start_delim;
        }
        if (!isset($options['tag_end'])) {
            $options['tag_end'] = $this->tag_end_delim;
        }

        $this->restart($options);
    }

    /***************************************************************************/
    /***[ public stuff ]********************************************************/
    /***************************************************************************/

    /**
     * Restart the class - allows one instantiation with several files processed by restarting
     * e.g. $xtpl = new XTemplate('file1.xtpl');
     * $xtpl->parse('main');
     * $xtpl->out('main');
     * $xtpl->restart('file2.xtpl');
     * $xtpl->parse('main');
     * $xtpl->out('main');
     * (Added in response to sf:641407 feature request)
     *
     * @param string $file Template file to work on
     * @param string/array $tpldir Location of template files
     * @param array $files Filenames lookup
     * @param string $mainblock Name of main block in the template
     * @param boolean $autosetup If true, run setup() as part of restarting
     * @param string $tag_start = {
     * @param string $tag_end = }
     */
    public function restart($options, $tpldir = '', $files = null, $mainblock = 'main', $autosetup = true, $tag_start = '{', $tag_end = '}')
    {
        /**
         * Encourage an options array to be passed as the first parameter
         *
         * Deprecate the massive list of parameters
         */
        if (is_array($options)) {
            foreach ($options as $option => $value) {
                switch ($option) {
                    case 'path':
                    case 'tpldir':
                        $tpldir = $value;
                        break;

                    case 'callbacks':
                        $this->allow_callbacks = true;
                        $this->allowed_callbacks = array_merge($this->allowed_callbacks, (array) $value);
                        break;

                    case 'file':
                    case 'files':
                    case 'mainblock':
                    case 'autosetup':
                    case 'tag_start':
                    case 'tag_end':
                        $$option = $value;
                        break;
                }
            }

            $this->filename = $file;
        } else {
            $this->filename = $options;
        }

        // From SF Feature request 1202027
        // Kenneth Kalmer
        if (isset($tpldir)) {
            $this->tpldir = $tpldir;
        }
        if (defined('XTPL_DIR') and empty($this->tpldir)) {
            $this->tpldir = XTPL_DIR;
        }

        if (isset($files) and is_array($files)) {
            $this->files = $files;
        }

        if (isset($mainblock)) {
            $this->mainblock = $mainblock;
        }

        if (isset($tag_start)) {
            $this->tag_start_delim = $tag_start;
        }

        if (isset($tag_end)) {
            $this->tag_end_delim = $tag_end;
        }

        // Start with fresh file contents
        $this->filecontents = '';

        // Reset the template arrays
        $this->blocks = [];
        $this->parsed_blocks = [];
        $this->preparsed_blocks = [];
        $this->block_parse_order = [];
        $this->sub_blocks = [];
        $this->vars = [];
        $this->filevars = [];
        $this->filevar_parent = [];
        $this->filecache = [];

        if ($this->allow_callbacks) {
            $delim = preg_quote($this->callback_delim);
            if (strlen($this->callback_delim) < strlen($delim)) {
                // Quote our quotes
                $delim = preg_quote($delim);
            }

            $this->callback_preg = preg_replace($this->preg_delimiter . '^\(' . $delim . '(.*)\)\*$' . $this->preg_delimiter, '\\1', $this->callback_preg);
        }

        if (!isset($autosetup) or $autosetup) {
            $this->setup();
        }
    }

    /**
     * setup - the elements that were previously in the constructor
     *
     * @access public
     * @param boolean $add_outer If true is passed when called, it adds an outer main block to the file
     */
    public function setup($add_outer = false)
    {
        $this->tag_start_delim = preg_quote($this->tag_start_delim);
        $this->tag_end_delim = preg_quote($this->tag_end_delim);

        // Setup the file delimiters

        // regexp for file includes
        $this->file_delim = $this->preg_delimiter . $this->tag_start_delim . "FILE\s*\"([^\"]+)\"" . $this->comment_preg . $this->tag_end_delim . $this->preg_delimiter . 'm';

        // regexp for file includes
        $this->filevar_delim = $this->preg_delimiter . $this->tag_start_delim . "FILE\s*" . $this->tag_start_delim . "([A-Za-z0-9\._\x7f-\xff]+?)" . $this->comment_preg . $this->tag_end_delim . $this->comment_preg . $this->tag_end_delim . $this->preg_delimiter . 'm';

        // regexp for file includes w/ newlines
        $this->filevar_delim_nl = $this->preg_delimiter . "^\s*" . $this->tag_start_delim . "FILE\s*" . $this->tag_start_delim . "([A-Za-z0-9\._\x7f-\xff]+?)" . $this->comment_preg . $this->tag_end_delim . $this->comment_preg . $this->tag_end_delim . "\s*\n" . $this->preg_delimiter . 'm';

        // regexp for tag callback matching
        $this->callback_preg = '(' . preg_quote($this->callback_delim) . $this->callback_preg . ')*';

        if (empty($this->filecontents)) {
            // read in template file
            $this->filecontents = $this->_r_getfile($this->filename);
        }

        if ($add_outer) {
            $this->_add_outer_block();
        }

        // preprocess some stuff
        $this->blocks = $this->_maketree($this->filecontents, '');
        $this->filevar_parent = $this->_store_filevar_parents($this->blocks);
        //$this->scan_globals();
    }

    /**
     * assign a variable
     *
     * @example Simplest case:
     * @example $xtpl->assign('name', 'value');
     * @example {name} in template
     *
     * @example Array assign:
     * @example $xtpl->assign(array('name' => 'value', 'name2' => 'value2'));
     * @example {name} {name2} in template
     *
     * @example Value as array assign:
     * @example $xtpl->assign('name', array('key' => 'value', 'key2' => 'value2'));
     * @example {name.key} {name.key2} in template
     *
     * @example Reset array:
     * @example $xtpl->assign('name', array('key' => 'value', 'key2' => 'value2'));
     * @example // Other code then:
     * @example $xtpl->assign('name', array('key3' => 'value3'), false);
     * @example {name.key} {name.key2} {name.key3} in template
     *
     * @access public
     * @param string / array / object $name Variable to assign $val to
     * @param string / array / object $val Value to assign to $name
     * @param boolean $reset_array Reset the variable array if $val is an array
     */
    public function assign($name, $val = '', $reset_array = true)
    {
        /**
         * Allow assigning with objects as well as arrays
         *
         * @author JRCoates
         * @since 04/09/2008
         */
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->vars[$k] = $v;
            }
        } elseif (is_array($val)) {
            // Clear the existing values
            if ($reset_array) {
                $this->vars[$name] = [];
            }

            foreach ($val as $k => $v) {
                $this->vars[$name][$k] = $v;
            }
        } else {
            $this->vars[$name] = $val;
        }
    }

    /**
     * assign a file variable
     *
     * @access public
     * @param string $name Variable to assign $val to
     * @param string / array $val Values to assign to $name
     */
    public function assign_file($name, $val = '')
    {
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->_assign_file_sub($k, $v);
            }
        } else {
            $this->_assign_file_sub($name, $val);
        }
    }

    /**
     * parse a block
     *
     * @access public
     * @param string $bname Block name to parse
     */
    public function parse($bname)
    {
        if (isset($this->preparsed_blocks[$bname])) {
            $copy = $this->preparsed_blocks[$bname];
        } elseif (isset($this->blocks[$bname])) {
            $copy = $this->blocks[$bname];
        } elseif ($this->_ignore_missing_blocks) {
            // ------------------------------------------------------
            // NW : 17 Oct 2002. Added default of ignore_missing_blocks
            //      to allow for generalised processing where some
            //      blocks may be removed from the HTML without the
            //      processing code needing to be altered.
            // ------------------------------------------------------
            // JRC: 3/1/2003 added set error to ignore missing functionality
            $this->_set_error("parse: blockname [$bname] does not exist");
            return;
        } else {
            $this->_set_error("parse: blockname [$bname] does not exist");
        }

        /* from there we should have no more {FILE } directives */
        if (!isset($copy)) {
            exit('Block: ' . $bname);
        }

        $copy = preg_replace($this->filevar_delim_nl, '', $copy);

        $var_array = [];

        /* find & replace variables+blocks */
        preg_match_all($this->preg_delimiter . $this->tag_start_delim . '([A-Za-z0-9\._\x7f-\xff]+?' . $this->callback_preg . $this->comment_preg . ')' . $this->tag_end_delim . $this->preg_delimiter, $copy, $var_array);

        $var_array = $var_array[1];

        foreach ($var_array as $k => $v) {
            // Use in regexes later
            $orig_v = $v;

            // Are there any comments in the tags {tag#a comment for documenting the template}
            $comment = '';
            $any_comments = explode($this->comment_delim, $v);
            if (count($any_comments) > 1) {
                $comment = array_pop($any_comments);
            }
            $v = rtrim(implode($this->comment_delim, $any_comments));

            if ($this->allow_callbacks) {
                // Callback function modifiers {tag|callback}
                $callback_funcs = explode($this->callback_delim, $v);
                $v = rtrim($callback_funcs[0]);
                unset($callback_funcs[0]);
            }

            $sub = explode('.', $v);

            if ($sub[0] == '_BLOCK_') {
                // BLOCKS

                unset($sub[0]);

                $bname2 = implode('.', $sub);

                // trinary operator eliminates assign error in E_ALL reporting
                $var = isset($this->parsed_blocks[$bname2]) ? $this->parsed_blocks[$bname2] : '';
                $nul = (!isset($this->_null_block[$bname2])) ? $this->_null_block[''] : $this->_null_block[$bname2];

                if ($var === '') {
                    if ($nul == '') {
                        // -----------------------------------------------------------
                        // Removed requirement for blocks to be at the start of string
                        // -----------------------------------------------------------
                        //                      $copy=preg_replace("/^\s*\{".$v."\}\s*\n*/m","",$copy);
                        // Now blocks don't need to be at the beginning of a line,
                        //$copy=preg_replace("/\s*" . $this->tag_start_delim . $v . $this->tag_end_delim . "\s*\n*/m","",$copy);
                        $copy = preg_replace($this->preg_delimiter . $this->tag_start_delim . $v . $this->tag_end_delim . $this->preg_delimiter . 'm', '', $copy);
                    } else {
                        $copy = preg_replace($this->preg_delimiter . $this->tag_start_delim . $v . $this->tag_end_delim . $this->preg_delimiter . 'm', "$nul", $copy);
                    }
                } else {
                    //$var = trim($var);
                    switch (true) {
                        case preg_match($this->preg_delimiter . "^\n" . $this->preg_delimiter, $var) and preg_match($this->preg_delimiter . "\n$" . $this->preg_delimiter, $var):
                            $var = substr($var, 1, -1);
                            break;

                        case preg_match($this->preg_delimiter . "^\n" . $this->preg_delimiter, $var):
                            $var = substr($var, 1);
                            break;

                        case preg_match($this->preg_delimiter . "\n$" . $this->preg_delimiter, $var):
                            $var = substr($var, 0, -1);
                            break;
                    }

                    // SF Bug no. 810773 - thanks anonymous
                    $var = str_replace('\\', '\\\\', $var);
                    // Ensure dollars in strings are not evaluated reported by SadGeezer 31/3/04
                    $var = str_replace('$', '\\$', $var);
                    // Replaced str_replaces with preg_quote
                    //$var = preg_quote($var);
                    $var = str_replace('\\|', '|', $var);
                    $copy = preg_replace($this->preg_delimiter . $this->tag_start_delim . $v . $this->tag_end_delim . $this->preg_delimiter . 'm', "$var", $copy);

                    if (preg_match($this->preg_delimiter . "^\n" . $this->preg_delimiter, $copy) and preg_match($this->preg_delimiter . "\n$" . $this->preg_delimiter, $copy)) {
                        $copy = substr($copy, 1, -1);
                    }
                }
            } else {
                // TAGS

                $var = $this->vars;

                foreach ($sub as $v1) {
                    // NW 4 Oct 2002 - Added isset and is_array check to avoid NOTICE messages
                    // JC 17 Oct 2002 - Changed EMPTY to strlen=0
                    //                if (empty($var[$v1])) { // this line would think that zeros(0) were empty - which is not true
                    /**
                     * Allow assigning with objects as well as arrays
                     *
                     * @author JRCoates
                     * @since 04/09/2008
                     */
                    switch (true) {
                        case is_array($var):
                            if (!isset($var[$v1]) or (is_string($var[$v1]) and strlen($var[$v1]) == 0)) {
                                // Check for constant, when variable not assigned
                                if (defined($v1)) {
                                    $var[$v1] = constant($v1);
                                } else {
                                    $var[$v1] = null;
                                }
                            }
                            $var = $var[$v1];
                            break;

                        case is_object($var):
                             if (!isset($var->$v1) or (is_string($var->$v1) and strlen($var->$v1) == 0)) {
                                 // Check for constant, when variable not assigned
                                 if (defined($v1)) {
                                     $var->$v1 = constant($v1);
                                 } else {
                                     $var->$v1 = null;
                                 }
                             }
                            $var = $var->$v1;
                            break;
                    }
                }

                /**
                 * Callback function handling
                 * Inspired by sf feature request #1756946 christophe_lu
                 *
                 * @author JRCoates (cocomp)
                 * @since 03/08/2007
                 */
                if ($this->allow_callbacks) {
                    if (is_array($callback_funcs) and !empty($callback_funcs)) {
                        foreach ($callback_funcs as $callback) {
                            // See if we've got parameters being used e.g. |str_replace('A', 'B', %s)
                            if (preg_match($this->preg_delimiter . '\((.*?)\)' . $this->preg_delimiter, $callback, $matches)) {
                                $parameters = [];
                                /**
                                 * Zero width assertion positive look behind (?<=a)x
                                 * Zero width assertion negative look behind (?<!a)x
                                 * Zero width assertion positive look ahead x(?=a)
                                 * Zero width assertion negative look ahead x(?!a)
                                 */
                                if (preg_match_all($this->preg_delimiter . '(?#
                                    match optional comma, optional other stuff, then
                                    apostrophes / quotes then stuff followed by comma or
                                    closing bracket negative look behind for an apostrophe
                                    or quote not preceeded by an escaping back slash
                                    )[,?\s*?]?[\'|"](.*?)(?<!\\\\)(?<=[\'|"])[,|\)$](?#
                                    OR match optional comma, optional other stuff, then
                                    multiple word \w with look behind % for our %s followed
                                    by comma or closing bracket
                                    )|,?\s*?([\w(?<!\%)]+)[,|\)$]' . $this->preg_delimiter, $matches[1] . ')', $param_matches)) {
                                    $parameters = $param_matches[0];
                                }

                                if (count($parameters)) {
                                    array_walk($parameters, [$this, 'trim_callback']);
                                    if (($key = array_search('%s', $parameters)) !== false) {
                                        $parameters[$key] = $var;
                                    } else {
                                        array_unshift($parameters, $var);
                                    }
                                } else {
                                    unset($parameters);
                                }
                            }

                            // Remove the parameters
                            $callback = preg_replace($this->preg_delimiter . '\(.*?\)' . $this->preg_delimiter, '', $callback);

                            // Allow callback of methods in a sub-class of XTemplate
                            // e.g. you must my_class extends XTemplate {} if you want to use this feature
                            if (is_subclass_of($this, 'XTemplate') and method_exists($this, $callback) and is_callable([$this, $callback])) {
                                if (isset($parameters)) {
                                    $var = call_user_func_array([$this, $callback], $parameters);
                                    unset($parameters);
                                } else {
                                    // Standard form e.g. {tag|callback}
                                    $var = call_user_func([$this, $callback], $var);
                                }
                            } elseif (in_array($callback, $this->allowed_callbacks) and function_exists($callback) and is_callable($callback)) {
                                if (isset($parameters)) {
                                    $var = call_user_func_array($callback, $parameters);
                                    unset($parameters);
                                } else {
                                    // Standard form e.g. {tag|callback}
                                    $var = call_user_func($callback, $var);
                                }
                            }
                        }
                    }
                }

                $nul = (!isset($this->_null_string[$v])) ? ($this->_null_string['']) : ($this->_null_string[$v]);
                $var = (!isset($var)) ? $nul : $var;

                // Prevent cast to strings when arrays passed in
                if (is_string($var)) {
                    if ($var === '') {
                        $copy = preg_replace($this->preg_delimiter . $this->tag_start_delim . preg_quote($orig_v) . $this->tag_end_delim . $this->preg_delimiter . 'm', '', $copy);
                    } else {
                        //$var = trim($var);
                        // SF Bug no. 810773 - thanks anonymous
                        $var = str_replace('\\', '\\\\', $var);
                        // Ensure dollars in strings are not evaluated reported by SadGeezer 31/3/04
                        $var = str_replace('$', '\\$', $var);
                        // Replace str_replaces with preg_quote
                        //$var = preg_quote($var);
                        $var = str_replace('\\|', '|', $var);
                    }
                }

                $copy = preg_replace($this->preg_delimiter . $this->tag_start_delim . preg_quote($orig_v) . $this->tag_end_delim . $this->preg_delimiter . 'm', "$var", $copy);

                if (preg_match($this->preg_delimiter . "^\n" . $this->preg_delimiter, $copy) and preg_match($this->preg_delimiter . "\n$" . $this->preg_delimiter, $copy)) {
                    $copy = substr($copy, 1);
                }
            }
        }

        if (isset($this->parsed_blocks[$bname])) {
            $this->parsed_blocks[$bname] .= $copy;
        } else {
            $this->parsed_blocks[$bname] = $copy;
        }

        /* reset sub-blocks */
        if ($this->_autoreset and (!empty($this->sub_blocks[$bname]))) {
            reset($this->sub_blocks[$bname]);

            foreach ($this->sub_blocks[$bname] as $k => $v) {
                $this->reset($v);
            }
        }
    }

    /**
     * returns the parsed text for a block, including all sub-blocks.
     *
     * @access public
     * @param string $bname Block name to parse
     */
    public function rparse($bname)
    {
        if (!empty($this->sub_blocks[$bname])) {
            reset($this->sub_blocks[$bname]);

            foreach ($this->sub_blocks[$bname] as $k => $v) {
                if (!empty($v)) {
                    $this->rparse($v);
                }
            }
        }

        $this->parse($bname);
    }

    /**
     * inserts a loop ( call assign & parse )
     *
     * @access public
     * @param string $bname Block name to assign
     * @param string $var Variable to assign values to
     * @param string / array $value Value to assign to $var
    */
    public function insert_loop($bname, $var, $value = '')
    {
        $this->assign($var, $value);
        $this->parse($bname);
    }

    /**
     * parses a block for every set of data in the values array
     *
     * @access public
     * @param string $bname Block name to loop
     * @param string $var Variable to assign values to
     * @param array $values Values to assign to $var
    */
    public function array_loop($bname, $var, &$values)
    {
        if (is_array($values)) {
            foreach ($values as $v) {
                $this->insert_loop($bname, $var, $v);
            }
        }
    }

    /**
     * returns the parsed text for a block
     *
     * @access public
     * @param string $bname Block name to return
     * @return string
     */
    public function text($bname = '')
    {
        $text = '';

        $bname = !empty($bname) ? $bname : $this->mainblock;

        $text .= isset($this->parsed_blocks[$bname]) ? $this->parsed_blocks[$bname] : $this->get_error();

        return $text;
    }

    /**
     * prints the parsed text
     *
     * @access public
     * @param string $bname Block name to echo out
     */
    public function out($bname)
    {
        $out = $this->text($bname);
        //        $length=strlen($out);
        //header("Content-Length: ".$length); // TODO: Comment this back in later

        echo $out;
    }

    /**
     * prints the parsed text to a specified file
     *
     * @access public
     * @param string $bname Block name to write out
     * @param string $fname File name to write to
     */
    public function out_file($bname, $fname)
    {
        if (!empty($bname) and !empty($fname) and is_writeable($fname)) {
            $fp = fopen($fname, 'w');
            fwrite($fp, $this->text($bname));
            fclose($fp);
        }
    }

    /**
     * resets the parsed text
     *
     * @access public
     * @param string $bname Block to reset
     */
    public function reset($bname)
    {
        $this->parsed_blocks[$bname] = '';
    }

    /**
     * returns true if block was parsed, false if not
     *
     * @access public
     * @param string $bname Block name to test
     * @return boolean
     */
    public function parsed($bname)
    {
        return (!empty($this->parsed_blocks[$bname]));
    }

    /**
     * sets the string to replace in case the var was not assigned
     *
     * @access public
     * @param string $str Display string for null block
     * @param string $varname Variable name to apply $str to
     */
    public function set_null_string($str, $varname = '')
    {
        $this->_null_string[$varname] = $str;
    }

    /**
     * Backwards compatibility only
     *
     * @param string $str
     * @param string $varname
     * @deprecated Change to set_null_string to keep in with rest of naming convention
     */
    public function SetNullString($str, $varname = '')
    {
        $this->set_null_string($str, $varname);
    }

    /**
     * sets the string to replace in case the block was not parsed
     *
     * @access public
     * @param string $str Display string for null block
     * @param string $bname Block name to apply $str to
     */
    public function set_null_block($str, $bname = '')
    {
        $this->_null_block[$bname] = $str;
    }

    /**
     * Backwards compatibility only
     *
     * @param string $str
     * @param string $bname
     * @deprecated Change to set_null_block to keep in with rest of naming convention
     */
    public function SetNullBlock($str, $bname = '')
    {
        $this->set_null_block($str, $bname);
    }

    /**
     * sets AUTORESET to 1. (default is 1)
     * if set to 1, parse() automatically resets the parsed blocks' sub blocks
     * (for multiple level blocks)
     *
     * @access public
     */
    public function set_autoreset()
    {
        $this->_autoreset = true;
    }

    /**
     * sets AUTORESET to 0. (default is 1)
     * if set to 1, parse() automatically resets the parsed blocks' sub blocks
     * (for multiple level blocks)
     *
     * @access public
     */
    public function clear_autoreset()
    {
        $this->_autoreset = false;
    }

    /**
     * gets error condition / string
     *
     * @access public
     * @return boolean / string
     */
    public function get_error()
    {
        // JRC: 3/1/2003 Added ouptut wrapper and detection of output type for error message output
        $retval = false;

        if ($this->_error != '') {
            switch ($this->output_type) {
                case 'HTML':
                case 'html':
                    $retval = '<b>[XTemplate]</b><ul>' . nl2br(str_replace('* ', '<li>', str_replace(" *\n", "</li>\n", $this->_error))) . '</ul>';
                    break;

                default:
                    $retval = '[XTemplate] ' . str_replace(' *\n', "\n", $this->_error);
                    break;
            }
        }

        return $retval;
    }

    /***************************************************************************/
    /***[ private stuff ]*******************************************************/
    /***************************************************************************/

    /**
     * generates the array containing to-be-parsed stuff:
     * $blocks["main"],$blocks["main.table"],$blocks["main.table.row"], etc.
     * also builds the reverse parse order.
     *
     * @access public - aiming for private
     * @param string $con content to be processed
     * @param string $parentblock name of the parent block in the block hierarchy
     */
    public function _maketree($con, $parentblock = '')
    {
        $blocks = [];

        $con2 = explode($this->block_start_delim, $con);

        if (!empty($parentblock)) {
            $block_names = explode('.', $parentblock);
            $level = sizeof($block_names);
        } else {
            $block_names = [];
            $level = 0;
        }

        // JRC 06/04/2005 Added block comments (on BEGIN or END) <!-- BEGIN: block_name#Comments placed here -->
        //$patt = "($this->block_start_word|$this->block_end_word)\s*(\w+)\s*$this->block_end_delim(.*)";
        $patt = '(' . $this->block_start_word . '|' . $this->block_end_word . ")\s*(\w+)" . $this->comment_preg . "\s*" . $this->block_end_delim . '(.*)';

        foreach ($con2 as $k => $v) {
            $res = [];

            if (preg_match_all($this->preg_delimiter . "$patt" . $this->preg_delimiter . 'ims', $v, $res, PREG_SET_ORDER)) {
                // $res[0][1] = BEGIN or END
                // $res[0][2] = block name
                // $res[0][3] = comment
                // $res[0][4] = kinda content
                $block_word = $res[0][1];
                $block_name = $res[0][2];
                $comment = $res[0][3];
                $content = $res[0][4];

                if (strtoupper($block_word) == $this->block_start_word) {
                    $parent_name = implode('.', $block_names);

                    // add one level - array("main","table","row")
                    $block_names[++$level] = $block_name;

                    // make block name (main.table.row)
                    $cur_block_name = implode('.', $block_names);

                    // build block parsing order (reverse)
                    $this->block_parse_order[] = $cur_block_name;

                    //add contents. trinary operator eliminates assign error in E_ALL reporting
                    $blocks[$cur_block_name] = isset($blocks[$cur_block_name]) ? $blocks[$cur_block_name] . $content : $content;

                    // add {_BLOCK_.blockname} string to parent block
                    $blocks[$parent_name] .= str_replace('\\', '', $this->tag_start_delim) . '_BLOCK_.' . $cur_block_name . str_replace('\\', '', $this->tag_end_delim);

                    // store sub block names for autoresetting and recursive parsing
                    $this->sub_blocks[$parent_name][] = $cur_block_name;

                    // store sub block names for autoresetting
                    $this->sub_blocks[$cur_block_name][] = '';
                } elseif (strtoupper($block_word) == $this->block_end_word) {
                    unset($block_names[$level--]);

                    $parent_name = implode('.', $block_names);

                    // add rest of block to parent block
                    $blocks[$parent_name] .= $content;
                }
            } else {
                // no block delimiters found
                // Saves doing multiple implodes - less overhead
                $tmp = implode('.', $block_names);

                if ($k) {
                    $blocks[$tmp] .= $this->block_start_delim;
                }

                // trinary operator eliminates assign error in E_ALL reporting
                $blocks[$tmp] = isset($blocks[$tmp]) ? $blocks[$tmp] . $v : $v;
            }
        }

        return $blocks;
    }

    /**
     * Sub processing for assign_file method
     *
     * @access private
     * @param string $name
     * @param string $val
     */
    private function _assign_file_sub($name, $val)
    {
        if (isset($this->filevar_parent[$name])) {
            if ($val != '') {
                $val = $this->_r_getfile($val);

                foreach ($this->filevar_parent[$name] as $parent) {
                    if (isset($this->preparsed_blocks[$parent]) and !isset($this->filevars[$name])) {
                        $copy = $this->preparsed_blocks[$parent];
                    } elseif (isset($this->blocks[$parent])) {
                        $copy = $this->blocks[$parent];
                    }

                    $res = [];

                    preg_match_all($this->filevar_delim, $copy, $res, PREG_SET_ORDER);

                    if (is_array($res) and isset($res[0])) {
                        // Changed as per solution in SF bug ID #1261828
                        foreach ($res as $v) {
                            // Changed as per solution in SF bug ID #1261828
                            if ($v[1] == $name) {
                                // Changed as per solution in SF bug ID #1261828
                                $copy = preg_replace($this->preg_delimiter . preg_quote($v[0]) . $this->preg_delimiter, "$val", $copy);
                                $this->preparsed_blocks = array_merge($this->preparsed_blocks, $this->_maketree($copy, $parent));
                                $this->filevar_parent = array_merge($this->filevar_parent, $this->_store_filevar_parents($this->preparsed_blocks));
                            }
                        }
                    }
                }
            }
        }

        $this->filevars[$name] = $val;
    }

    /**
     * store container block's name for file variables
     *
     * @access public - aiming for private
     * @param array $blocks
     * @return array
     */
    public function _store_filevar_parents($blocks)
    {
        $parents = [];

        foreach ($blocks as $bname => $con) {
            $res = [];

            preg_match_all($this->filevar_delim, $con, $res);

            foreach ($res[1] as $k => $v) {
                $parents[$v][] = $bname;
            }
        }
        return $parents;
    }

    /**
     * Set the error string
     *
     * @access private
     * @param string $str
     */
    private function _set_error($str)
    {
        // JRC: 3/1/2003 Made to append the error messages
        $this->_error .= '* ' . $str . " *\n";
        // JRC: 3/1/2003 Removed trigger error, use this externally if you want it eg. trigger_error($xtpl->get_error())
        //trigger_error($this->get_error());
    }

    /**
     * returns the contents of a file
     *
     * @access protected
     * @param string $file
     * @return string
     */
    protected function _getfile($file)
    {
        if (!isset($file)) {
            // JC 19/12/02 added $file to error message
            $this->_set_error('!isset file name!' . $file);

            return '';
        }

        // check if filename is mapped to other filename
        if (isset($this->files)) {
            if (isset($this->files[$file])) {
                $file = $this->files[$file];
            }
        }

        // prepend template dir
        if (!empty($this->tpldir)) {
            /**
             * Support hierarchy of file locations to search
             *
             * @example Supply array of filepaths when instantiating
             * 			First path supplied that has the named file is prioritised
             * 			$xtpl = new XTemplate('myfile.xtpl', array('.','/mypath', '/mypath2'));
             * @since 29/05/2007
             */
            if (is_array($this->tpldir)) {
                foreach ($this->tpldir as $dir) {
                    if (is_readable($dir . DIRECTORY_SEPARATOR . $file)) {
                        $file = $dir . DIRECTORY_SEPARATOR . $file;
                        break;
                    }
                }
            } else {
                $file = $this->tpldir . DIRECTORY_SEPARATOR . $file;
            }
        }

        $file_text = '';

        if (isset($this->filecache[$file])) {
            $file_text .= $this->filecache[$file];
        } else {
            if ($file_text = file_get_contents($file, true)) {
                // Enable use of include path by using file_get_contents
                // Implemented at suggestion of SF Feature Request ID #1529478 michaelgroh
                if ($file_text === false) {
                    $this->_set_error('[' . realpath($file) . "] ($file) does not exist");
                    if ($this->output_type == 'HTML') {
                        $file_text = "<b>__XTemplate fatal error: file [$file] does not exist in the include path__</b>";
                    }
                }
            } else {
                // NW 17 Oct 2002 : Added realpath around the file name to identify where the code is searching.
                $this->_set_error('[' . realpath($file) . "] ($file) does not exist");
                if ($this->output_type == 'HTML') {
                    $file_text .= "<b>__XTemplate fatal error: file [$file] does not exist__</b>";
                }
            }

            $this->filecache[$file] = $file_text;
        }

        return $file_text;
    }

    /**
     * recursively gets the content of a file with {FILE "filename.tpl"} directives
     *
     * @access public - aiming for private
     * @param string $file
     * @return string
     */
    public function _r_getfile($file)
    {
        $text = $this->_getfile($file);

        $res = [];

        while (preg_match($this->file_delim, $text, $res)) {
            $text2 = $this->_getfile($res[1]);
            $text = preg_replace($this->preg_delimiter . preg_quote($res[0]) . $this->preg_delimiter, $text2, $text);
        }

        return $text;
    }

    /**
     * Function for preparing tag callback function parameters
     *
     * @access protected
     * @param string $value
     */
    protected function trim_callback(&$value)
    {
        $value = preg_replace($this->preg_delimiter . '^.*(%s).*$' . $this->preg_delimiter, '\\1', trim($value));
        $value = preg_replace($this->preg_delimiter . '^,?\s*?(.*?)[,|\)]?$' . $this->preg_delimiter, '\\1', trim($value));
        $value = preg_replace($this->preg_delimiter . '^[\'|"]?(.*?)[\'|"]?$' . $this->preg_delimiter, '\\1', trim($value));
        $value = preg_replace($this->preg_delimiter . '\\\\(?=\'|")' . $this->preg_delimiter, '', $value);
        // Deal with escaped commas (beta)
        $value = preg_replace($this->preg_delimiter . '\\\,' . $this->preg_delimiter, ',', $value);
    }

    /**
     * add an outer block delimiter set useful for rtfs etc - keeps them editable in word
     *
     * @access private
     */
    private function _add_outer_block()
    {
        $before = $this->block_start_delim . $this->block_start_word . ' ' . $this->mainblock . ' ' . $this->block_end_delim;
        $after = $this->block_start_delim . $this->block_end_word . ' ' . $this->mainblock . ' ' . $this->block_end_delim;

        $this->filecontents = $before . "\n" . $this->filecontents . "\n" . $after;
    }
}
