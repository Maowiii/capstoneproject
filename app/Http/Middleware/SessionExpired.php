<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class SessionExpired
{
  public function handle($request, Closure $next)
  {
    Log::debug('SessionExpired middleware is executing.');

    if ($this->userIsAuthenticated()) {
      $lastActivity = session('last_activity', 0);
      Log::debug('Last Activity: ' . $lastActivity);

      $currentTime = time();

      if ($currentTime - $lastActivity > 1800) { // seconds
        Log::debug('Session expired; performing logout.');
        $request->session()->destroy();

        $this->performLogout();

        session()->flash('message', 'Your session has expired. Please log in again.');

        return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
      }
    } else {
      return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
    }

    session(['last_activity' => time()]);

    return $next($request);
  }

  private function userIsAuthenticated()
  {
    return session()->has('account_id');
  }

  private function performLogout()
  {
    session()->invalidate();

    return redirect()->route('viewLogin')->with('message', 'Your session has expired. Please log in again.');
  }
}