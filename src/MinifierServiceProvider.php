<?php

namespace EvolutionCMS\ZeeyN;

use EvolutionCMS\ServiceProvider;
use EvolutionCMS;
use EvolutionCMS\ZeeyN\Minifier;

class MinifierServiceProvider extends ServiceProvider
{
    public $evo;

    public function register()
    {
        $this->evo = EvolutionCMS();
        $this->evo->addDataToView([
            'minifier' => new Minifier()
        ]);



    }

}
