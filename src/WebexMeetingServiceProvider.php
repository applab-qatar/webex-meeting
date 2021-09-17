<?php
namespace Applab\WebexMeeting;
use Illuminate\Support\ServiceProvider;
class WebexMeetingServiceProvider extends ServiceProvider
{

    const CONFIG_PATH = __DIR__ . '/../config/webex-config.php';
    const ROUTE_PATH = __DIR__ . '/../routes';
    const MIGRATION_PATH = __DIR__ . '/../migrations';

    public function boot()
    {
        $this->publish();
        $this->loadRoutesFrom(self::ROUTE_PATH . '/web.php');
    }

    private function publish()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('applab-webex.php')
        ], 'config');

        $this->publishes([
            self::MIGRATION_PATH => database_path('migrations')
        ], 'migrations');
    }

    public function register()
    {
        $this->app->bind('webex-meeting', function($app) {
            return new WebexMeeting();
        });
//        $this->app->singleton(WebexMeeting::class,function (){
//            return new WebexMeeting();
//        });
    }
}
