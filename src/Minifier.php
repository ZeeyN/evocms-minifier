<?php

namespace EvolutionCMS\ZeeyN;

use Illuminate\Support\Facades\Cache;
use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;

class Minifier
{

    const MINIFIER_HASH_KEY = 'beware_the_dragon';

    /**
     * @param array $files
     * @param int $minify
     * @param string $output_folder
     * @return string
     */
    public function activate(array $files, int $minify = 1, string $output_folder = '')
    {
        $links = '';
        $type = pathinfo(MODX_BASE_PATH . $files[0])['extension'];

        if ($minify == 1) {
            $hash = Cache::rememberForever(self::MINIFIER_HASH_KEY, function () use ($files, $output_folder, $type) {

                $innerHash = self::getHardHash($files);
                if (file_exists(MODX_BASE_PATH . $output_folder . "include.$innerHash.min.$type")) {
                    return $innerHash;
                } else {
                    $existingFiles = glob(MODX_BASE_PATH . $output_folder . "include.*.min.$type");
                    if (!empty($existingFiles)) {
                        foreach ($existingFiles as $existingFile) {
                            self::deleteFile($existingFile);
                        }
                    }
                    self::generateMinFile($files, $innerHash, $type, $output_folder);
                    return $innerHash;
                }
            });
            switch ($type) {
                case 'css':
                    $links .= '<link rel="stylesheet" href="' . MODX_SITE_URL . $output_folder . 'include.' . $hash . '.min.' . $type . '" />';
                    break;
                case 'js':
                    $links .= '<script src="' . MODX_SITE_URL . $output_folder . 'include.' . $hash . '.min.' . $type . '"></script>';
                    break;
            }
        } else {
            foreach ($files as $key => $file) {
                switch ($type) {
                    case 'js':
                        $links .= '<script src="' . MODX_SITE_URL . trim($file) . '?v=' . self::getSimpleHash(filemtime(MODX_BASE_PATH . $file)) . '"></script>';
                        break;

                    case 'css':
                        $links .= '<link rel="stylesheet" href="' . MODX_SITE_URL . trim($file) . '?v=' . self::getSimpleHash(filemtime(MODX_BASE_PATH . $file)) . '" />';
                        break;
                }
            }
        }

        return $links;
    }

    /**
     * @param $file
     */
    protected static function deleteFile($file)
    {
        if (file_exists($file))
            unlink($file);
    }

    /**
     * @param $value
     * @return string
     */
    protected static function getSimpleHash($value)
    {
        return md5($value);

    }

    /**
     * @param $files
     * @return string
     */
    protected static function getHardHash($files)
    {
        foreach ($files as $file) {
            $out[] = md5_file(MODX_BASE_PATH . trim($file));
        }
        $out = implode($out);
        return md5($out);
    }

    /**
     * @param $files
     * @param $innerHash
     * @param $type
     * @param $output_folder
     */
    protected function generateMinFile($files, $innerHash, $type, $output_folder)
    {
        $lib = $type == 'js' ? new JS($files) : new CSS($files);
        $lib->minify($output_folder . "include.$innerHash.min.$type");
    }

}



