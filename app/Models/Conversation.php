<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'user_one_id',
        'user_two_id',
        'exchange_request_id',
    ];

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function exchangeRequest()
    {
        return $this->belongsTo(ExchangeRequest::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function otherUser(int $userId): User
    {
        return $this->user_one_id === $userId ? $this->userTwo : $this->userOne;
    }

    public function involves(int $userId): bool
    {
        return in_array($userId, [$this->user_one_id, $this->user_two_id], true);
    }

    public static function findOrCreateBetween(int $a, int $b, ?int $exchangeRequestId = null): self
    {
        [$one, $two] = $a < $b ? [$a, $b] : [$b, $a];

        $conversation = static::firstOrCreate(
            ['user_one_id' => $one, 'user_two_id' => $two],
            ['exchange_request_id' => $exchangeRequestId]
        );

        if ($exchangeRequestId && ! $conversation->exchange_request_id) {
            $conversation->update(['exchange_request_id' => $exchangeRequestId]);
        }

        return $conversation;
    }
}
