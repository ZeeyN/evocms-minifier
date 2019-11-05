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
            $filesForMin[$key] = $file;
        }
        if ($minify == 1) {
            $lib = new JS($filesForMin);
            if(file_exists($output_folder . 'scripts.min.js'))
                unlink($output_folder . 'scripts.min.js');
            $lib->minify($output_folder . 'scripts.min.js');
            return '<script src="' . MODX_SITE_URL . $output_folder . 'scripts.min.js?v=' . substr(md5(max($v)), 0, 25) . '"></script>';

        } else {
            $links = '';
            foreach ($filesForMin as $key => $value) {
                $links .= '<script src="' . MODX_SITE_URL . trim($value) . '?v=' . substr(md5(max($v)), 0, 25) . '"></script>';
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
            switch ($fileInfo['extension']) {
                case 'css':
                    $filesForMin[$key] = $file;
                    break;
            }
        }

        if ($minify == 1) {
            $lib = new CSS($filesForMin);
            if(file_exists($output_folder . 'styles.min.css'))
                unlink($output_folder . 'styles.min.css');
            $lib->minify($output_folder . 'styles.min.css');
            return '<link rel="stylesheet" href="' . MODX_SITE_URL . $output_folder . 'styles.min.css?v=' . substr(md5(max($v)), 0, 25) . '" />';
        } else {
            $links = '';
            foreach ($filesForMin as $key => $value) {
                $links .= '<link rel="stylesheet" href="' . MODX_SITE_URL . trim($value) . '?v=' . substr(md5(max($v)), 0, 25) . '" />';
            }
            return $links;
        }
    }
}



