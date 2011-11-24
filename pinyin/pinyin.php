<?php
/**
 *  获取拼音信息
 *
 * @access    public
 * @param     string  $str  字符串
 * @param     int  $ishead  是否为首字母
 * @return    string
 * @author	  chen1706@gmail.com
 */
function pinyin($str, $ishead = 0, $isdestroy = 1)
{
    $restr = '';
    $pinyins = array();
    $slen = strlen($str);
    if ($slen < 2) {
        return $str;
    }
    //加到程序中可以static pinyins 
    if (count($pinyins) == 0) {
        $fp = fopen(__DIR__ .'/pinyin-utf8.dat', 'r');
        while (! feof($fp)) {
            $line = trim(fgets($fp));
            if ($line) {
                list($chinese, $english) = explode('`', $line);
                $pinyins[$chinese] = $english;
            }
        }
        fclose($fp);
    }
    for ($i = 0; $i < $slen; $i ++) {
        $c = substr($str, $i, 1);	    
        $ord = ord($c);
		if ($ord >= 224) {
            $c = substr($str, $i, 3);
	        $i += 2; 
	    } elseif ($ord >= 192) {
	        $c = substr($str, $i, 2);
	        $i += 1;
        }
        if (isset($pinyins[$c])) {
            if ($ishead) $restr .= $pinyins[$c][0];
            else $restr .= $pinyins[$c];
        } elseif (preg_match("/[a-z0-9]/i", $c)) {
            $restr .= $c;
        }
    }
    if ($isdestroy) unset($pinyins);
    return $restr;
}
