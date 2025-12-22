<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\User;
use App\Models\Keluhan;
use App\Notifications\KeluhanSubmitted;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('REDIRECT_HTTPS')) {
            URL::forceScheme('https');
        }

        // Check if settings table schema is present.
        if (Schema::hasTable('settings')) {
            $settings = Setting::pluck('option_value', 'option_name')->toArray();
            foreach ($settings as $key => $value) {
                config(['settings.' . $key => $value]);
            }
        }

        // Only allowed people can view the pulse.
        Gate::define('viewPulse', function (User $user) {
            return $user->can('pulse.view');
        });

        // Kirim notifikasi saat keluhan baru dibuat
        try {
            Keluhan::created(function (Keluhan $keluhan) {
                try {
                    // Pastikan tabel notifications tersedia sebelum mengirim notifikasi
                    if (!Schema::hasTable('notifications')) {
                        return;
                    }
                    $recipients = User::permission('keluhan.view')->get();
                    foreach ($recipients as $user) {
                        $user->notify(new KeluhanSubmitted($keluhan));
                    }
                } catch (\Throwable $e) {
                    // Abaikan error agar tidak mengganggu proses lain
                }
            });
            // Tandai notifikasi terkait keluhan sebagai terbaca ketika keluhan diupdate
            Keluhan::updated(function (Keluhan $keluhan) {
                try {
                    if (!Schema::hasTable('notifications')) {
                        return;
                    }
                    DatabaseNotification::query()
                        ->whereNull('read_at')
                        ->where('type', KeluhanSubmitted::class)
                        ->whereJsonContains('data->keluhan_id', $keluhan->id_keluhan)
                        ->update(['read_at' => now()]);
                } catch (\Throwable $e) {
                    // Abaikan error
                }
            });
        } catch (\Throwable $e) {
            // Abaikan jika model belum siap saat proses tertentu (misalnya saat migrate awal)
        }
    }
}
