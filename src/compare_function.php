<?php
/**
 * $Horde: framework/Text_Diff/Diff/Renderer.php,v 1.18 2007/09/25 22:10:36 chuck Exp $
 *
 * A class to render Diffs in different formats.
 *
 * This class renders the diff in classic diff format. It is intended that
 * this class be customized via inheritance, to obtain fancier outputs.
 *
 * Copyright 2004-2007 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @package Text_Diff
 */
class Text_Diff_Renderer {

    var $_leading_context_lines = 10000;
    var $_trailing_context_lines = 10000;
	var $_reverse = false;
    var $_ins_prefix = '<span class="green">';
    var $_ins_suffix = '</span>';
    var $_del_prefix = '<span class="hidden">';
    var $_del_suffix = '</span>';
    var $_block_header = '';
    var $_split_level = 'lines';
	
	function _redoSuf() {
		$this->_ins_prefix = '<span class="red">';
	}

    function Text_Diff_Renderer($params = array(), $rev = false) {
        foreach ($params as $param => $value) {
            $v = '_' . $param;
            if (isset($this->$v)) {
                $this->$v = $value;
            }
        }
		if ($rev) $this->_redoSuf();
		$this->_reverse = $rev;
    }
	
    function getParams() {
        $params = array();
        foreach (get_object_vars($this) as $k => $v) {
            if ($k[0] == '_') {
                $params[substr($k, 1)] = $v;
            }
        }

        return $params;
    }

    function render($diff) {
        $xi = $yi = 1;
        $block = false;
        $context = array();

        $nlead = $this->_leading_context_lines;
        $ntrail = $this->_trailing_context_lines;

        $output = $this->_startDiff();

        $diffs = $diff->getDiff();
        foreach ($diffs as $i => $edit) {
            if (is_a($edit, 'Text_Diff_Op_copy')) {
                if (is_array($block)) {
                    $keep = $i == count($diffs) - 1 ? $ntrail : $nlead + $ntrail;
                    if (count($edit->orig) <= $keep) {
                        $block[] = $edit;
                    } else {
                        if ($ntrail) {
                            $context = array_slice($edit->orig, 0, $ntrail);
                            $block[] = &new Text_Diff_Op_copy($context);
                        }
                        $output .= $this->_block($x0, $ntrail + $xi - $x0,
                                                 $y0, $ntrail + $yi - $y0,
                                                 $block);
                        $block = false;
                    }
                }
                $context = $edit->orig;
            } else {
                if (!is_array($block)) {
                    $context = array_slice($context, count($context) - $nlead);
                    $x0 = $xi - count($context);
                    $y0 = $yi - count($context);
                    $block = array();
                    if ($context) {
                        $block[] = &new Text_Diff_Op_copy($context);
                    }
                }
                $block[] = $edit;
            }
            if ($edit->orig) {
                $xi += count($edit->orig);
            }
            if ($edit->final) {
                $yi += count($edit->final);
            }
        }
        if (is_array($block)) {
            $output .= $this->_block($x0, $xi - $x0,
                                     $y0, $yi - $y0,
                                     $block);
        }
        return $output . $this->_endDiff();
    }

    function _block($xbeg, $xlen, $ybeg, $ylen, &$edits) {
        $output = $this->_startBlock($this->_blockHeader($xbeg, $xlen, $ybeg, $ylen));
        foreach ($edits as $edit) {
            switch (strtolower(get_class($edit))) {
            case 'text_diff_op_copy':
                $output .= $this->_context($edit->orig);
                break;
            case 'text_diff_op_add':
                $output .= $this->_added($edit->final);
                break;
            case 'text_diff_op_delete':
                $output .= $this->_deleted($edit->orig);
                break;
            case 'text_diff_op_change':
                $output .= $this->_changed($edit->orig, $edit->final);
                break;
            }
        }
        return $output . $this->_endBlock();
    }

    function _startDiff() {
        return '';
    }

    function _endDiff() {
        return '';
    }

    function _blockHeader($xbeg, $xlen, $ybeg, $ylen) {
        return $this->_block_header;
    }

    function _startBlock($header) {
        return $header;
    }

    function _endBlock() {
        return '';
    }

    function _lines($lines, $prefix = ' ', $encode = true) {
        if ($encode) {
            array_walk($lines, array(&$this, '_encode'));
        }

        if ($this->_split_level == 'words') {
            return implode('', $lines);
        } else {
            return implode("\n", $lines) . "\n";
        }
    }

    function _context($lines) {
        return $this->_lines($lines, '  ');
    }

    function _added($lines) {
        array_walk($lines, array(&$this, '_encode'));
        $lines[0] = $this->_ins_prefix . $lines[0];
        $lines[count($lines) - 1] .= $this->_ins_suffix;
        return $this->_lines($lines, ' ', false);
    }

    function _deleted($lines, $words = false) {
        array_walk($lines, array(&$this, '_encode'));
        $lines[0] = $this->_del_prefix . $lines[0];
        $lines[count($lines) - 1] .= $this->_del_suffix;
        return $this->_lines($lines, ' ', false);
    }

    function _changed($orig, $final) {
        if ($this->_split_level == 'words') {
            $prefix = '';
            while ($orig[0] !== false && $final[0] !== false &&
                   substr($orig[0], 0, 1) == ' ' &&
                   substr($final[0], 0, 1) == ' ') {
                $prefix .= substr($orig[0], 0, 1);
                $orig[0] = substr($orig[0], 1);
                $final[0] = substr($final[0], 1);
            }
            return $prefix . $this->_deleted($orig) . $this->_added($final);
        }
        $text1 = implode("\n", $orig);
        $text2 = implode("\n", $final);
        $nl = "\0";
        $diff = new Text_Diff($this->_splitOnWords($text1, $nl),
                              $this->_splitOnWords($text2, $nl));
        $renderer = new Text_Diff_Renderer(array_merge($this->getParams(),
                                                              array('split_level' => 'words')));
        return str_replace($nl, "\n", $renderer->render($diff)) . "\n";
    }
	
    function _splitOnWords($string, $newlineEscape = "\n") {
        $string = str_replace("\0", '', $string);
        $words = array();
        $length = strlen($string);
        $pos = 0;
        while ($pos < $length) {
            // Eat a word with any preceding whitespace.
            $spaces = strspn(substr($string, $pos), " \n");
            $nextpos = strcspn(substr($string, $pos + $spaces), " \n");
            $words[] = str_replace("\n", $newlineEscape, substr($string, $pos, $spaces + $nextpos));
            $pos += $spaces + $nextpos;
        }
        return $words;
    }
	
    function _encode(&$string) {
        //$string = htmlspecialchars($string);
        $string = $string;
    }
}
