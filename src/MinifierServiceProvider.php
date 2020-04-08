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
        $minifier = new Minifier();
        //js directive
        Blade::directive('minjs', function($expression) use ($minifier){
            $data = explode(',', $expression);
            return "<?php echo {$minifier->js($data[0], $data[1], $data[2], $data[3])}?>";
        });

        //css directive
        Blade::directive('mincss', function($expression) use ($minifier){
            $data = explode(',', $expression);
            return "<?php echo {$minifier->css($data[0], $data[1], $data[2], $data[3])}?>";
        });

        //adaptive directive
        Blade::directive('minifier', function($expression) use ($minifier){
            $data = explode(',', $expression);
            return "<?php echo {$minifier->activate($data[0], $data[1], $data[2], $data[3])}?>";
        });

    }

}
