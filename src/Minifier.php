<?php

namespace EvolutionCMS\ZeeyN;

use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;

class Minifier
{
    protected $filesForMin = [];

    public function js(array $files, int $minify = 1, string $output_folder = '')
    {
        foreach ($files as $key => $value) {
            $file = MODX_BASE_PATH . trim($value);
            $v[$key] = filemtime($file);
            $this->filesForMin[$key] = $file;
        }
        if ($minify == 1) {
            $lib = new JS($this->filesForMin);
            $lib->minify($output_folder . 'scripts.min.js');
            return '<script src="' . MODX_SITE_URL . $output_folder . 'scripts.min.js?v=' . substr(md5(max($v)), 0, 3) . '"></script>';

        } else {
            $links = '';
            foreach ($this->filesForMin as $key => $value) {
                $links .= '<script src="' . MODX_SITE_URL . trim($value) . '?v=' . substr(md5(max($v)), 0, 3) . '"></script>';
            }
            return $links;
        }
    }

    public function css(array $files, int $minify = 1, string $output_folder = '')
    {
        foreach ($files as $key => $value) {
            $file = MODX_BASE_PATH . trim($value);
            $fileInfo = pathinfo($file);
            $v[$key] = filemtime($file);
            switch ($fileInfo['extention']) {
                case 'css':
                    $this->filesForMin[$key] = $file;
                    break;
            }
        }
        if ($minify == 1) {
            $lib = new CSS($this->filesForMin);
            $lib->minify($output_folder . 'styles.min.css');
            return '<link rel="stylesheet" href="' . MODX_SITE_URL . $output_folder . 'styles.min.css?v=' . substr(md5(max($v)), 0, 3) . '" />';
        } else {
            $links = '';
            foreach ($this->filesForMin as $key => $value) {
                $links .= '<link rel="stylesheet" href="' . MODX_SITE_URL . trim($value) . '?v=' . substr(md5(max($v)), 0, 3) . '" />';
            }
            return $links;
        }
    }
}



