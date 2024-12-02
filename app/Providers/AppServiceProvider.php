<?php

namespace App\Providers;

use DB;
use App\Models\User;
use App\Models\ModeAplikasi;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
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
        // Agar variable notif dibawa ke semua view,
        View::composer('*', function ($view) {
            // cek apakah sudah login atau belum
            if (Auth::check()) {
                $user = auth()->user();
                $result_tema = Cache::remember('result_tema_' . $user->user_id, 2, function () use ($user) {
                    return ModeAplikasi::where('user_id', $user->user_id)->first();
                });

                $unreadNotifications = Cache::remember('unreadNotifications_' . $user->id, 2, function () use ($user) {
                    return Notification::where('notifiable_id', $user->id)->whereNull('read_at')->get();
                });

                $readNotifications = Cache::remember('readNotifications_' . $user->id, 2, function () use ($user) {
                    return Notification::where('notifiable_id', $user->id)->whereNotNull('read_at')->get();
                });

                $view->with(compact('unreadNotifications', 'readNotifications', 'result_tema'));
            }
        });

        date_default_timezone_set('Asia/Jakarta');
        config(['app.locale' => 'id']);
        \Carbon\Carbon::setLocale('id');
        if (env('APP_ENV') != 'local') {
            $this->app['request']->server->set('HTTPS', true);
        } else {
            $this->app['request']->server->set('HTTPS', false);
        }

        Gate::define('admin', function (User $user) {
            return $user->role_name == 'Admin';
        });
    }
}