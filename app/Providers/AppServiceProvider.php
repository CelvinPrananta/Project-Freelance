<?php

namespace App\Providers;

use DB;
use App\Models\User;
use App\Logic\FileLogic;
use App\Logic\TeamLogic;
use App\Logic\UserLogic;
use App\Logic\BoardLogic;
use App\Models\ModeAplikasi;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $userLogic = new UserLogic();
        $fileLogic = new FileLogic();
        $teamLogic = new TeamLogic();
        $boardLogic = new BoardLogic();

        $this->app->instance(UserLogic::class, $userLogic);
        $this->app->instance(FileLogic::class, $fileLogic);
        $this->app->instance(TeamLogic::class, $teamLogic);
        $this->app->instance(BoardLogic::class, $boardLogic);
    }

    public function boot()
    {
        // Agar variable notif dibawa ke semua view,
        View::composer('*', function ($view) {
            if (Auth::check()) { // cek apakah sudah login atau belum
                $user = auth()->user();
                // $result_tema = ModeAplikasi::where('user_id', auth()->user()->user_id)->first();
                // $unreadNotifications = Notification::where('notifiable_id', $user->id)->whereNull('read_at')->get();
                // $readNotifications = Notification::where('notifiable_id', $user->id)->whereNotNull('read_at')->get();
                // $semua_notifikasi = Notification::latest()->with('user')->get();
                // $belum_dibaca = Notification::where('notifiable_id', $user->id)->whereNull('read_at')->get();
                // $dibaca = Notification::with('user')->whereNotNull('read_at')->get();
                // $view
                //     ->with('unreadNotifications', $unreadNotifications)
                //     ->with('readNotifications', $readNotifications)
                //     // ->with('belum_dibaca', $belum_dibaca)
                //     // ->with('semua_notifikasi', $semua_notifikasi)
                //     ->with('result_tema', $result_tema);
                // // ->with('dibaca', $dibaca);

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
