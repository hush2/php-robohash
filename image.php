<?php
// (C) 2012 hush2 <hushywushy@gmail.com>

require 'lib/Robohash.php';

define('CACHE_IMAGE',  true);
define('CACHE_DIR', 'cache/');

$text = isset($_GET['text']) ? $_GET['text'] : $_SERVER['REMOTE_ADDR'];

$gravatar = isset($_GET['gravatar']) ? $_GET['gravatar'] : false;

if ($gravatar) {
    $gravatar_hash = null;
    if ($gravatar === 'yes') {
        $gravatar_hash = md5(strtolower(trim($text)));
    } elseif ($gravatar === 'hashed') {
        $gravatar_hash = $text;
    }
    if ($gravatar_hash) {
        $gravatar_url = "http://www.gravatar.com/avatar/$gravatar_hash?s=80&default=404";

        stream_context_set_default(array('http' => array('method' => 'HEAD')));
        $headers = get_headers($gravatar_url);
        if (strpos($headers[0], '200 OK') !== false) {
            header("Location: $gravatar_url");
        }
    }
}

$color = isset($_GET['color']) ? $_GET['color'] : false;
$set   = isset($_GET['set'])   ? $_GET['set']   : false;
$bgset = isset($_GET['bgset']) ? $_GET['bgset'] : false;
$size  = isset($_GET['size'])  ? $_GET['size']  : false;

// File extension is ignored by default when computing the hash.
$ignoreext = isset($_GET['ignoreext']) && $_GET['ignoreext'] === 'false' ? false : true;
$ext = 'png';

$match = preg_match('/(.*)\.(jpe?g|gif|png|bmp)$/', $text, $matches);
if ($match > 0) {
    $ext  = $matches[2];
    $text = $ignoreext ? $matches[1] : $matches[0];
}

header("Content-Type: image/$ext");
header("Content-Disposition: inline; filename=$text");
header("Cache-Control: maxage=" . 3600 * 24 * 7);   // 7 Days
$dt = new DateTime();
$dt = $dt->add(new DateInterval('P7D'))->format('D, d M Y H:i:s');
header("Expires: $dt GMT");

$filename = CACHE_DIR . md5("{$text}_{$set}_{$bgset}_{$color}_{$size}") . ".$ext";

if (CACHE_IMAGE && file_exists($filename)) {
    echo file_get_contents($filename);
} else {
    $robohash = new Robohash(array(
                    'text'      => $text,
                    'bgset'     => $bgset,
                    'set'       => $set,
                    'size'      => $size,
                    'color'     => $color,
                    'ext'       => $ext,
                    'filename'  => $filename,
                    'cache'     => CACHE_IMAGE,
    ));
    echo $robohash->generate_image();
}
