<?php

namespace App\Providers;

use App\Models\CompanyProfile;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Mendaftarkan layanan aplikasi saat container disiapkan.
     */
    public function register(): void
    {
    }

    /**
     * Membagikan profil perusahaan ke seluruh view saat aplikasi dijalankan.
     */
    public function boot(): void
    {
        try {
            View::share('companyProfile', CompanyProfile::first());
        } catch (QueryException) {
            View::share('companyProfile', null);
        }
    }
}
