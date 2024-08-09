<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Transaction
 * 
 * @property int $id
 * @property int $fk_user
 * @property int $fk_order
 * @property string|null $mpesa_transaction_id
 * @property string|null $cash_transaction_id
 * @property string $payment_method
 * @property float $amount
 * @property string $active
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Order $order
 * @property User $user
 *
 * @package App\Models
 */
class Transaction extends Model
{
	use SoftDeletes;
	protected $table = 'transactions';

	protected $casts = [
		'fk_user' => 'int',
		'fk_order' => 'int',
		'amount' => 'float'
	];

	protected $fillable = [
		'fk_user',
		'fk_order',
		'mpesa_transaction_id',
		'cash_transaction_id',
		'payment_method',
		'amount',
		'active'
	];

	public function order()
	{
		return $this->belongsTo(Order::class, 'fk_order');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'fk_user');
	}
}
