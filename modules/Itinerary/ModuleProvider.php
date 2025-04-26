<?php
namespace Modules\Itinerary;
use Modules\Core\Helpers\SitemapHelper;
use Modules\ModuleServiceProvider;
use Modules\User\Helpers\PermissionHelper;

class ModuleProvider extends ModuleServiceProvider
{

    public function boot(SitemapHelper $sitemapHelper){

        $this->loadMigrationsFrom(__DIR__ . '/Migrations');


        PermissionHelper::add([
            //itineary
            'itineary_view',
            'itineary_create',
            'itineary_update',
            'itineary_delete',
        ]);

    }
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouterServiceProvider::class);
    }

    public static function getAdminMenu()
    {
        return [
            'itineary'=>[
                "position"=>52,
                'url'        => route('itineary.admin.index'),
                'title'      => __('itineary'),
                'icon'       => 'fa fa-ticket',
                'permission' => 'itineary_view',
            ],
        ];
    }
   
}
