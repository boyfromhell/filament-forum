<?php

namespace IchBin\FilamentForum;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use IchBin\FilamentForum\Commands\FilamentForumCommand;
use IchBin\FilamentForum\Http\Middleware\DiscussionMiddleware;
use IchBin\FilamentForum\Testing\TestsFilamentForum;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentForumServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-forum';

    public static string $viewNamespace = 'filament-forum';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasRoute('web')
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('ichbin/filament-forum');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('discussion', DiscussionMiddleware::class);
        $router->pushMiddlewareToGroup('web', DiscussionMiddleware::class);

    }

    public function packageBooted(): void
    {
        $this->registerComponents();
        $this->registerPolicies();

        // Asset Registration
        //        FilamentAsset::register(
        //            $this->getAssets(),
        //            $this->getAssetPackageName()
        //        );
        //
        //        FilamentAsset::registerScriptData(
        //            $this->getScriptData(),
        //            $this->getAssetPackageName()
        //        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-forum/{$file->getFilename()}"),
                ], 'filament-forum-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentForum());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'ichbin/filament-forum';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filament-forum', __DIR__ . '/../resources/dist/components/filament-forum.js'),
            Css::make('filament-forum-styles', __DIR__ . '/../resources/dist/filament-forum.css'),
            Js::make('filament-forum-scripts', __DIR__ . '/../resources/dist/filament-forum.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentForumCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_forum_table',
        ];
    }

    protected function registerComponents(): self
    {
        Blade::componentNamespace('IchBin\\FilamentForum\\View\\Components', 'filament-forum');

        Livewire::component('discussions', \IchBin\FilamentForum\Livewire\Discussions::class);
        Livewire::component('add-discussion', \IchBin\FilamentForum\Livewire\AddDiscussion::class);
        Livewire::component('header', \IchBin\FilamentForum\Livewire\Header::class);
        Livewire::component('discussion-details', \IchBin\FilamentForum\Livewire\DiscussionDetails::class);
        Livewire::component('mark-as-resolved', \IchBin\FilamentForum\Livewire\MarkAsResolved::class);
        Livewire::component('discussion', \IchBin\FilamentForum\Livewire\Discussion::class);
        Livewire::component('likes', \IchBin\FilamentForum\Livewire\Likes::class);
        Livewire::component('replies', \IchBin\FilamentForum\Livewire\Replies::class);
        Livewire::component('reply-details', \IchBin\FilamentForum\Livewire\ReplyDetails::class);
        Livewire::component('reply', \IchBin\FilamentForum\Livewire\Reply::class);
        Livewire::component('reply-btn', \IchBin\FilamentForum\Livewire\ReplyBtn::class);
        Livewire::component('follow', \IchBin\FilamentForum\Livewire\Follow::class);
        Livewire::component('lock', \IchBin\FilamentForum\Livewire\Lock::class);
        Livewire::component('tags', \IchBin\FilamentForum\Livewire\Tags::class);
        Livewire::component('forum-notifications', \IchBin\FilamentForum\Livewire\Notifications::class);

        return $this;
    }

    public function registerPolicies(): self
    {
        //Gate::define('createComment', [Config::getCommentPolicyName(), 'create']);

        Gate::policy(\IchBin\FilamentForum\Models\Discussion::class, \IchBin\FilamentForum\Policies\DiscussionPolicy::class);
        //Gate::policy(Config::getReactionModelName(), Config::getReactionPolicyName());

        return $this;
    }
}
