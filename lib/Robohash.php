<?php

// (C) 2012 hush2 <hushywushy@gmail.com>

class Robohash
{
    private $image_dir      =  'images/';

    private static $colors  = array(
        'blue', 'brown', 'green', 'grey', 'orange', 'pink', 'purple', 'red', 'white', 'yellow',
    );
    private static $sets    = array('set1','set2', 'set3');
    private static $bgsets  = array('bg1', 'bg2');

    private $set            = '',
            $bgset          = '',
            $hash_index     = 4,
            $hash_list      = array();

    const IMAGE_WIDTH       = 300,
          IMAGE_HEIGHT      = 300;

    function  __construct($options) {

        $this->create_hashes($options['text']);

        $this->set_color($options['color']) ;
        $this->set_set($options['set']);

        if ($options['bgset'])
        {
            $this->set_bgset($options['bgset']) ;
        }

        $this->set_size($options['size']) ;

        $this->filename = $options['filename'];
        $this->ext      = $options['ext'];
        $this->cache    = $options['cache'];
    }

    private function create_hashes($text, $length=11)
    {
        $hashes = str_split(hash('sha512', $text), $length);
        foreach ($hashes as $hash)
        {
            $this->hash_list[] = base_convert($hash, 16, 10);
        }
    }

    function set_color($color)
    {
        $this->set = 'set1/';

        if ($color && in_array($color, self::$colors))
        {
            $this->set .= $color;
        }
        else {
            $this->set .= self::$colors[bcmod($this->hash_list[0], count(self::$colors))] ;
        }
    }

    function set_set($set)
    {
        if ($set == 'any') 
        {
            $set = self::$sets[bcmod($this->hash_list[1], count(self::$sets))] ;
        }
        if ($set == 'set1' || !in_array($set, self::$sets))
        {
            return;  // Use set from set_color()
        }
        $this->set = $set;
    }

    function set_bgset($bgset)
    {
        if (!in_array($bgset, self::$bgsets))
        {
            $bgset = self::$bgsets[bcmod($this->hash_list[2], count(self::$bgsets))];
        }
        $bgfiles = glob($this->image_dir . "$bgset/*");
        $this->bgset = $bgfiles[bcmod($this->hash_list[3], count($bgfiles))];
    }

    function get_image_list()
    {
        $image_list = array();
        $dirs = glob($this->image_dir . "{$this->set}/*");

        foreach ($dirs as $dir)
        {
            $files = glob("$dir/*");
            $img_index = bcmod($this->hash_list[$this->hash_index], count($files));
            $this->hash_index++;
            $s = explode('#', $files[$img_index], 2);
            krsort($s);
            $temp[] = implode("|", $s);
        }
        sort($temp);

        foreach ($temp as $file)
        {
            $s = explode('|',$file, 2);
            krsort($s);
            $image_list[] = implode("#", $s);
        }
        if ($this->bgset)
        {
            array_unshift($image_list, $this->bgset);
        }
        return $image_list;
    }

    function set_size($size)
    {
        $this->size = $size;
    }

    function get_width_height()
    {
        $width  = self::IMAGE_WIDTH;
        $height = self::IMAGE_WIDTH;

        if ($this->size)
        {
            $width_height = explode('x', $this->size);

            $width  = isset($width_height[0]) ? (int) $width_height[0] : self::IMAGE_WIDTH;
            $height = isset($width_height[1]) ? (int) $width_height[1] : self::IMAGE_HEIGHT;

            if ($width  > 1024 || $width  < 10)
            {
                $width  = self::IMAGE_WIDTH;
            }
            if ($height > 1024 || $height < 10)
            {
                $height = self::IMAGE_HEIGHT;
            }
        }
        return array($width, $height);
    }

    // Use ImageMagick for processing images.
    function generate_image_imagick($image_list)
    {
        $body = array_shift($image_list);
        $body = new Imagick($body);

        $body->resizeImage(self::IMAGE_WIDTH, self::IMAGE_HEIGHT, Imagick::FILTER_LANCZOS, 1);

        foreach ($image_list as $image_file)
        {
            $image = new Imagick($image_file);
            // Since some of the images varies in width/height (Set3 in particular),
            // they need to be resized first so that they are centered properly.
            $image->resizeImage(self::IMAGE_WIDTH, self::IMAGE_HEIGHT, Imagick::FILTER_LANCZOS, 1);
            $body->compositeImage($image, $image->getImageCompose(), 0, 0);
            $image->clear();
        }

        list($width, $height) = $this->get_width_height();

        if ($width != self::IMAGE_WIDTH && $height != self::IMAGE_HEIGHT)
        {
            $body->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1);
        }

        $body->setImageFormat($this->ext);

        // cache it
        if ($this->cache)
        {
            file_put_contents($this->filename, $body);
        }
        return $body;

    }

    // Use GD as a fallback if host does not support ImageMagick.
    function generate_image_gd($image_list)
    {
        // functions with alpha channel support
        require 'imagecopymerge_alpha.php';
        require 'image_resize.php';

        $body = array_shift($image_list);

        $body = imagecreatefrompng($body);
        $body = image_resize($body, self::IMAGE_WIDTH, self::IMAGE_HEIGHT);

        foreach ($image_list as $image_file) {
            $image = imagecreatefrompng($image_file);
            $image = image_resize($image, self::IMAGE_WIDTH, self::IMAGE_HEIGHT);
            imagecopymerge_alpha($body, $image, 0, 0, 0, 0, imagesx($image), imagesy($image), 100);
            imagedestroy($image);
        }

        list($width, $height) = $this->get_width_height();

        $body = image_resize($body, $width, $height);

        imagesavealpha($body, true);

        return $body;
    }

    public function generate_image()
    {
        $image_list = $this->get_image_list();

        if (extension_loaded('imagick'))
        {
            return $this->generate_image_imagick($image_list);
        }
        if (extension_loaded('gd'))
        {
            $image = $this->generate_image_gd($image_list);

            // Buffer image so we can cache it.
            ob_start();

            switch ($this->ext) {
                case 'jpg':
                    imagejpeg($image);
                    break;

                case 'gif':
                    imagegif($image);
                    break;

                case 'bmp':
                    imagewbmp($image);
                    break;

                default:
                    imagepng($image);
                    break;
            }

            $body = ob_get_clean();
            if ($this->cache) {
                file_put_contents($this->filename, $body);
            }
            return $body;
        }
    }

    static function rand_text($length = 8)
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($chars), 0, $length);
    }

    static function rand_set()
    {
        return self::$sets[array_rand(self::$sets)];
    }

    static function rand_color()
    {
        return self::$colors[array_rand(self::$colors)];
    }

    static function rand_bgset()
    {
        return self::$bgsets[array_rand(self::$bgsets)];
    }

}
