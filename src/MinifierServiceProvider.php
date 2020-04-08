<?php

namespace EvolutionCMS\ZeeyN;

use EvolutionCMS\ServiceProvider;
use EvolutionCMS;
use EvolutionCMS\ZeeyN\Minifier;
use Illuminate\Support\Facades\Blade;

class MinifierServiceProvider extends ServiceProvider
{
    public $evo;

    public function register()
    {
        $this->evo = EvolutionCMS();
        //js directive
        Blade::directive('minjs', function($args){
            $minifier = new Minifier();
            $data = $minifier->parseDirectiveData($args);
            return $minifier->js($data['files'], $data['args'][0], $data['args'][1], $data['args'][2]);

        });

        //css directive
        Blade::directive('mincss', function($args){
            $minifier = new Minifier();
            $data = $minifier->parseDirectiveData($args);
            return $minifier->css($data['files'], $data['args'][0], $data['args'][1], $data['args'][2]);

        });

        //adaptive directive
        Blade::directive('minifier', function($args){
            $minifier = new Minifier();
            $data = $minifier->parseDirectiveData($args);
            return $minifier->activate($data['files'], $data['args'][0], $data['args'][1], $data['args'][2]);
        });

    }

}
