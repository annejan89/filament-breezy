<?php

namespace JeffGreco13\FilamentBreezy;

use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Filament\PluginServiceProvider;
use JeffGreco13\FilamentBreezy\Commands\FilamentBreezyCommand;
use JeffGreco13\FilamentBreezy\Http\Livewire\Auth;
use JeffGreco13\FilamentBreezy\Http\Livewire\BreezySanctumTokens;
use JeffGreco13\FilamentBreezy\Pages\MyProfile;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;

class FilamentBreezyServiceProvider extends PluginServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name("filament-breezy")
            ->hasConfigFile()
            ->hasViews()
            //->hasMigration("create_filament-breezy_table")
            ->hasRoute("web")
            ->hasTranslations();
        //->hasCommand(FilamentBreezyCommand::class);
    }

    public function packageBooted(): void
    {
        parent::packageBooted();

        Livewire::component(Auth\Login::getName(), Auth\Login::class);

        Livewire::component(
            Auth\ResetPassword::getName(),
            Auth\ResetPassword::class
        );
        Livewire::component(Auth\Verify::getName(), Auth\Verify::class);

        if (config("filament-breezy.enable_registration")) {
            Livewire::component(Auth\Register::getName(), Auth\Register::class);
        }

        if (config("filament-breezy.enable_sanctum")) {
            Livewire::component(
                BreezySanctumTokens::getName(),
                BreezySanctumTokens::class
            );
        }

        if (config("filament-breezy.enable_profile_page") && config('filament-breezy.show_profile_page_in_user_menu')) {
            Filament::serving(function () {
                Filament::registerUserMenuItems([
                    'account' => UserMenuItem::make()->url(MyProfile::getUrl()),
                ]);
            });
        }
    }

    protected function getPages(): array
    {
        return config("filament-breezy.enable_profile_page")
            ? [Pages\MyProfile::class]
            : [];
    }
}
