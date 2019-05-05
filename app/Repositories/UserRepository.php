<?php


namespace App\Repositories;


use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class UserRepository
{
  private $user;
  private $http;
  private $otp;
  private $expiry_time_in_minutes = 1;

  public function __construct(User $user, $otp)
  {
    $this->user = $user;
    $this->otp = $otp;
    $this->http = new Client([
      'base_uri' => env('NOTIFICATION_SERVICE_URL'),
    ]);
  }

  public function sendOTP() {
    $time = new \DateTime();
    $time->add(new \DateInterval('PT' . $this->expiry_time_in_minutes . 'M'));

    try {
      $this->user->otps()->create([
        'code' => $this->otp,
        'expires_on' => $time->format('Y-m-d H:i:s'),
      ]);
    } catch(QueryException $qe) {
      Log::error($qe->getMessage());
    }

    try {
      $this->http->post('/otp/send', [
        'form_params' => [
          "phoneNos" => ["+" . $this->user->mobile_phone],
          "message" => "Hi, " . $this->user->first_name . ". Your Verification Code is: $this->otp."
        ],
      ]);
    } catch (ServerException $se) {
      Log::error($se->getMessage());
    }
  }
}