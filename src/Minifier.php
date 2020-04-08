<?php

namespace EvolutionCMS\ZeeyN;

use Illuminate\Support\Facades\Cache;
use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;

class Minifier
{

    const MINIFIER_HASH_KEY = 'beware_the_dragon';

    private static $links;

//---------------------------------General_methods----------------------------------------------------------------------

    /**
     * @param array  $files
     * @param string $no_laravel_cache
     * @param string $minify
     * @param string $output_folder
     * @return string
     */
    public function css(array $files, string $no_laravel_cache = '0', string $minify = '1', string $output_folder = '')
    {
        return $this->activate($files, $no_laravel_cache, $minify, $output_folder);
    }

    /**
     * @param array  $files
     * @param string $no_laravel_cache
     * @param string $minify
     * @param string $output_folder
     * @return string
     */
    public function js(array $files, string $no_laravel_cache = '0', string $minify = '1', string $output_folder = '')
    {
        return $this->activate($files, $no_laravel_cache, $minify, $output_folder);
    }

    /**
     * @param array  $files
     * @param string $no_laravel_cache
     * @param string $minify
     * @param string $output_folder
     * @return string
     */
    public function activate(array $files, string $no_laravel_cache = '0', string $minify = '1', string $output_folder = '')
    {
        self::$links = '';
        $type        = self::getExtension($files[0]);
        if ($minify == 1) {
            switch ($no_laravel_cache) {
                case 0:
                    $hash = self::getLaravelHash($files, $output_folder, $type);
                    break;

                case 1:
                    $hash = self::generateHashedFile($files, $output_folder, $type);
                    break;
            }
            self::setMinifiedOutput($output_folder, $hash, $type);
        } else {
            self::setNotMinifiedOutput($files, $type);
        }

        return self::$links;
    }
//======================================================================================================================
//-----------------------------------------------Support_methods--------------------------------------------------------
    /**
     * @param $file
     */
    protected static function deleteFile($file)
    {
        if (file_exists($file)) {
            unlink($file);
        }
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
    protected static function generateMinFile($files, $innerHash, $type, $output_folder)
    {
        self::prepareFiles($files);
        $lib = $type == 'js' ? new JS($files) : new CSS($files);
        $lib->minify($output_folder . "include.$innerHash.min.$type");
    }

    /**
     * @param $path
     * @return mixed
     */
    protected static function getExtension($path)
    {
        return pathinfo(MODX_BASE_PATH . $path)['extension'];
    }

    /**
     * @param $files
     * @param $output_folder
     * @param $type
     * @return mixed
     */
    protected function getLaravelHash($files, $output_folder, $type)
    {
        return Cache::rememberForever(
            self::MINIFIER_HASH_KEY . '_' . $type,
            function () use ($files, $output_folder, $type) {
                return self::generateHashedFile($files, $output_folder, $type);
            }
        );
    }

    /**
     * @param $output_folder
     * @param $hash
     * @param $type
     */
    protected static function setMinifiedOutput($output_folder, $hash, $type)
    {
        switch ($type) {
            case 'css':
                self::$links .= '<link rel="stylesheet" href="'
                    . MODX_SITE_URL . $output_folder . 'include.' . $hash . '.min.' . $type . '" />';
                break;
            case 'js':
                self::$links .= '<script src="'
                    . MODX_SITE_URL . $output_folder . 'include.' . $hash . '.min.' . $type . '"></script>';
                break;
        }
    }

    /**
     * @param $files
     * @param $type
     */
    protected static function setNotMinifiedOutput($files, $type)
    {
        foreach ($files as $file) {
            switch ($type) {
                case 'js':
                    self::$links .= '<script src="'
                        . MODX_SITE_URL . trim($file)
                        . '?v=' . self::getSimpleHash(filemtime(MODX_BASE_PATH . $file)) . '"></script>';
                    break;

                case 'css':
                    self::$links .= '<link rel="stylesheet" href="'
                        . MODX_SITE_URL . trim($file)
                        . '?v=' . self::getSimpleHash(filemtime(MODX_BASE_PATH . $file)) . '" />';
                    break;
            }
        }
    }

    /**
     * @param array $files
     */
    protected static function prepareFiles(array &$files)
    {
        foreach ($files as &$file) {
            $file = MODX_BASE_PATH . $file;
        }
    }

    protected static function generateHashedFile(array $files, string $output_folder, string $type)
    {
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
    }

    /**
     * @param string $data
     * @return array
     */
    public function parseDirectiveData(string $data): array
    {
        $cleanDataArr = explode(',', str_replace(['[', ']', PHP_EOL, '\'',' '], '', $data));
        $out          = [
            'files' => null,
            'args' => null
        ];
        $defaultArgs = ['0','1',''];
        foreach ($cleanDataArr as $item) {
            if (strpos($item, '.js') !== false || strpos($item,'.css') !== false) {
                $out['files'][] = $item;
            } else {
                $out['args'][] = $item;
            }
        }
        if(empty($out['args'][0])) {
            array_shift($out['args']);
        }
        if(count($out['args']) < 3) {
            $newArgs = array_slice($defaultArgs, count($out['args']));
            $out['args'] = array_merge($out['args'],$newArgs);
        }
        return $out;
    }
//======================================================================================================================
}



