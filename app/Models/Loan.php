<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    public const STATUS_DUE = 'due';
    public const STATUS_REPAID = 'repaid';

    public const CURRENCY_SGD = 'SGD';
    public const CURRENCY_VND = 'VND';

    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'loans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'amount',
        'terms',
        'outstanding_amount',
        'currency_code',
        'processed_at',
        'status',
    ];

    /**
     * The default attributes value
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS_DUE,
    ];

    /**
     * The function run when CRUD event happen
     *
     * @var array
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function($model){
            if(!$model->outstanding_amount) {
                $model->outstanding_amount = $model->amount;
            }
        });
    }

    /**
     * A Loan belongs to a User
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A Loan has many Scheduled Repayments
     *
     * @return HasMany
     */
    public function scheduledRepayments()
    {
        return $this->hasMany(ScheduledRepayment::class, 'loan_id');
    }

    /**
     * A Loan has many Received Repayments
     *
     * @return HasMany
     */
    public function receivedRepayments()
    {
        return $this->hasMany(ReceivedRepayment::class, 'loan_id');
    }
}
