<?php


namespace App\Models\Transactions;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Wallet extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id', 'user_id', 'balance'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deposit($value)
    {
        $this->update([
            'balance' => $this->attributes['balance'] + $value
        ]);
    }

    public function withdraw($value)
    {
        $this->update([
            'balance' => $this->attributes['balance'] - $value
        ]);
    }
}
