<?php

namespace App\Providers;

use App\Enums\Roles\RolesEnums;
use App\Models\User\User;
use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Laravel\Head\Enums\OgType;
use Laravel\Head\Enums\TwitterCard;
use Laravel\Head\Facades\Head;
use Laravel\Head\HeadBuilder;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        $this->addActiveFields();
        $this->configureCommands();
        $this->configureModels();
        $this->configurePasswordValidation();
        $this->configureDates();
        $this->configureHead();
        $this->configureRateLimiting();
        $this->configureAuthorization();

        // $this->configureLoadMigrationsFrom();
    }

    private function addActiveFields(): void
    {
        Blueprint::macro('activeFields', function () {
            $this->boolean('is_active')->default(true);
            $this->boolean('is_valid')->default(true);
        });
    }

    /**
     * Configure the application's commands.
     */
    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(
            $this->app->isProduction()
        );
    }

    /**
     * Configure the dates.
     */
    private function configureDates(): void
    {
        Date::use(CarbonImmutable::class);
        //Carbon::setLocale('fr_FR');
    }

    private function configureHead(): void
    {
        Head::defaults(function (HeadBuilder $head): void {
            $socialImage = asset('assets/img/logo-d.png');

            $head
                ->title("Moroccan Meraki Discover Morocco's beauty and culture", exact: true)
                ->description('Art retreats in Morocco, fully organized so you can focus on teaching, inspiring, and connecting with your students.')
                ->canonical()
                ->meta('google', 'notranslate')
                ->meta('keywords', 'Morocco, retreats, sorties, activités, retreats')
                ->meta('author', 'Moroccan Meraki LLC')
                ->og(
                    type: OgType::Website,
                    image: $socialImage,
                    siteName: 'Moroccan Meraki',
                    locale: 'En',
                )
                ->twitter(card: TwitterCard::SummaryWithLargeImage, image: $socialImage);

                $head->searchableByRobots();
        });
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('login', function (Request $request): Limit {
            $email = strtolower($request->string('email')->toString());

            return Limit::perMinute(5)->by($email.'|'.$request->ip());
        });

        RateLimiter::for('customer-2fa', function (Request $request): Limit {
            return Limit::perMinute(10)->by($request->session()->get('customer.2fa.uuid', 'unknown').'|'.$request->ip());
        });

    }

 

    private function configureAuthorization(): void
    {
        Gate::before(function (?User $user, string $ability): ?bool {
            if ($user?->hasRole(RolesEnums::SUPERADMIN->value)) {
                return true;
            }
            return null;
        });
    }

    /**
     * Configure the models.
     */
    private function configureModels(): void
    {
        Model::shouldBeStrict(! $this->app?->isProduction());
        Model::unguard();
    }

    /**
     * Configure the password validation rules.
     */
    private function configurePasswordValidation(): void
    {
        Password::defaults(fn () => $this->app?->isProduction() ? Password::min(8)->uncompromised() : null);
    }

    private function configureLoadMigrationsFrom(): void
    {
        $this->loadMigrationsFrom([
            database_path().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR.'food',
            database_path().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR.'school',
        ]);

        /* $migrationsPath = database_path('migrations');
        $directories = glob($migrationsPath . '/*', GLOB_ONLYDIR);
        $paths = array_merge([$migrationsPath], $directories);

        $this->loadMigrationsFrom($paths);*/
    }
}
