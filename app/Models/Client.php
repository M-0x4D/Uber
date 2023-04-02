<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Illuminate\Support\Str;


class Client extends Authenticatable
{
    use HasApiTokens;
    use HasFactory  , Notifiable ;
    // public $guard_name = 'api';
    protected $table = 'clients';
    public $timestamps = true;
    protected $fillable = ['name' , 'phone' , 'email' , 'password' , 'api_token' , 'pin_code'];

    protected $hidden = [
        'password',
        'api_token',
    ];

    // to increase token size in sanctum
//     public function createToken(string $name, array $abilities = ['*'])
//     {
//         $token = $this->tokens()->create([
//             'name' => $name,
//             'token' => hash('sha256', $plainTextToken = Str::random(240)),
//             'abilities' => $abilities,
//         ]);

//         return new NewAccessToken($token, $token->getKey().'|'.$plainTextToken);
//     }
}
