<?php

namespace App\Listeners;

use App\Events\GroupAdminRegistered;
use App\Repositories\UserRepository;
use Illuminate\Database\QueryException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOTPText
{

  /**
   * Create the event listener.
   *
   * @return void
   */
  public function __construct()
  {

  }

  /**
   * Handle the event.
   *
   * @param GroupAdminRegistered $event
   * @return void
   * @throws \Exception
   */
    public function handle(GroupAdminRegistered $event)
    {
//      if (App::environment() === 'local')
//        $otp = '1234';

      (new UserRepository($event->user, $event->otp))->sendOTP();
    }
}
