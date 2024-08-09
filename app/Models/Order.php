<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Order
 * 
 * @property int $id
 * @property int $fk_user
 * @property int $fk_customer
 * @property string $order_ID
 * @property Carbon $order_date
 * @property float $amount
 * @property string $status
 * @property string $active
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Customer $customer
 * @property User $user
 * @property Collection|OrderItem[] $order_items
 * @property Collection|Transaction[] $transactions
 *
 * @package App\Models
 */
class Order extends Model
{
	use SoftDeletes;
	protected $table = 'orders';

	protected $casts = [
		'fk_user' => 'int',
		'fk_customer' => 'int',
		'order_date' => 'datetime',
		'amount' => 'float'
	];

	protected $fillable = [
		'fk_user',
		'fk_customer',
		'order_ID',
		'order_date',
		'amount',
		'status',
		'active'
	];

	public function customer()
	{
		return $this->belongsTo(Customer::class, 'fk_customer');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'fk_user');
	}

	public function order_items()
	{
		return $this->hasMany(OrderItem::class, 'fk_order');
	}

	public function transactions()
	{
		return $this->hasMany(Transaction::class, 'fk_order');
	}
}
