<?php

namespace EvolutionCMS\ZeeyN;

use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;

class Minifier
{
    public function js(array $files, int $minify = 1, string $output_folder = '')
    {
        foreach ($files as $key => $value) {
            $file = MODX_SITE_URL . trim($value);
            $v[$key] = filemtime($file);
            $filesForMin[$key] = $file;
        }
        if ($minify == 1) {
            $lib = new JS($filesForMin);
            $this->deleteFile($output_folder . 'scripts.min.js');
            $lib->minify($output_folder . 'scripts.min.js');
            return '<script src="' . MODX_SITE_URL . $output_folder . 'scripts.min.js?v=' . $this->getHash($v) . '"></script>';

        } else {
            $links = '';
            foreach ($filesForMin as $key => $value) {
                $links .= '<script src="' . trim($value) . '?v=' . $this->getHash($v) . '"></script>';
            }
            return $links;
        }
    }

    public function css(array $files, int $minify = 1, string $output_folder = '')
    {
        foreach ($files as $key => $value) {
            $file = MODX_SITE_URL . trim($value);
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
            $this->deleteFile($output_folder . 'styles.min.css');
            $lib->minify($output_folder . 'styles.min.css');
            return '<link rel="stylesheet" href="' . MODX_SITE_URL . $output_folder . 'styles.min.css?v=' . $this->getHash($v) . '" />';
        } else {
            $links = '';
            foreach ($filesForMin as $key => $value) {
                $links .= '<link rel="stylesheet" href="' . trim($value) . '?v=' . $this->getHash($v) . '" />';
            }
            return $links;
        }
    }

    protected function getHash($val)
    {
        return substr(md5(max($val)), 0, 3);
    }

    protected function deleteFile($file)
    {
        if(file_exists($file))
            unlink($file);
    }
}



