<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chat extends Model
{
    use HasFactory;
    protected $table = "chats";
    protected $guarded = ['id'];


    public function partisipants(): HasMany
    {
        return $this->hasMany(ChatPartisipant::class, 'chat_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'chat_id');
    }

    public function lastMessage(): HasOne
    {
        return $this->hasOne(ChatMessage::class, 'chat_id')->latest('updated_at');
    }

    public function scopeHasPartisipant($query, int $userId)
    {
        return $query->whereHas('partisipants', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }
}
