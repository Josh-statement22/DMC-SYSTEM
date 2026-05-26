<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use App\Support\AccountingMonthlyBalance;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->shareAccountingBudgetBalance();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }

    protected function shareAccountingBudgetBalance(): void
    {
        View::composer(['layouts.accounting', 'accounting.*'], function ($view): void {
            static $accountingBudgetBalance = null;

            if ($accountingBudgetBalance !== null) {
                $view->with('accountingBudgetBalance', $accountingBudgetBalance);
                return;
            }

            $accountingBudgetBalance = AccountingMonthlyBalance::forMonth();

            $view->with('accountingBudgetBalance', $accountingBudgetBalance);
        });
    }
}
