<?php

//namespace App\Providers;
namespace Ndp\CustomCollect;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Ndp\CustomCollect\Collections\CollectionSelect;


class CustomCollectionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Collection::macro('select', function (... $args) {
            $args = array_flatten($args);
            $callable = end($args);
            $setAdditionalDummyProps = null;
            if(is_callable($callable)){
                $setAdditionalDummyProps = $callable;
                array_pop($args);
            }
            $fields = $args;
            return (new CollectionSelect($this))->select($fields)->setAfterSelectCallable($setAdditionalDummyProps)->get();
        });
    }
}
