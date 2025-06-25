<?php namespace VaahCms\Modules\School\Providers;


use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use VaahCms\Modules\School\Models\Teacher;
use VaahCms\Modules\School\Observers\TeacherObserver;
use VaahCms\Modules\School\Providers\RouteServiceProvider;
use VaahCms\Modules\School\Providers\EventServiceProvider;

class SchoolServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(Router $router)
    {

        $this->registerMiddleware($router);
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerAssets();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->registerSeeders();
        $this->registerBladeDirectives();
        $this->registerBladeComponents();
        Teacher::observe(TeacherObserver::class);
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();

        $this->registerHelpers();
        $this->registerLibraries();

    }

    /**
     *
     */
    private function registerMiddleware($router) {

        //register middleware
        //$router->aliasMiddleware('sample.middleware', \School\Http\Middleware\SampleMiddleware::class);

    }

    /**
     *
     */
    private function registerHelpers() {

        //load all the helpers
        foreach (glob(__DIR__.'/../Helpers/*.php') as $filename){
            require_once($filename);
        }

    }

    /**
     *
     */
    private function registerLibraries()
    {
        //load all the helpers
        foreach (glob(__DIR__.'/Libraries/*.php') as $filename){
            require_once($filename);
        }
    }


    /**
     *
     */
    private function registerSeeders() {

        //load all the seeds
        foreach (glob(__DIR__.'/../Database/Seeds/*.php') as $filename){
            require_once($filename);
        }

    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('school.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'school'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('/views/vaahcms/modules/school');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/views/vaahcms/modules/school';
        }, \Config::get('view.paths')), [$sourcePath]), 'school');

    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerAssets()
    {

        $sourcePath = __DIR__.'/../Resources/assets';

        $desPath = public_path('vaahcms/modules/school/assets');

        $this->publishes([
            $sourcePath => $desPath
        ],'assets');


    }


    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('/lang/vaahcms/modules/school');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'school');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'school');
        }
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function registerBladeDirectives()
    {

        /*
        \Blade::directive('hello', function ($expression) {
            return "<?php echo 'Hello ' . {$expression}; ?>";
        });
        */

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function registerBladeComponents()
    {

        /*
        \Blade::component('example', Example::class);
        */

    }

}
