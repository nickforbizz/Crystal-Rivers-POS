<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OrderItem
 * 
 * @property int $id
 * @property int $fk_user
 * @property int $fk_product
 * @property int $fk_order
 * @property int $quantity
 * @property float $unit_price
 * @property float $amount
 * @property string $active
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Order $order
 * @property Product $product
 * @property User $user
 *
 * @package App\Models
 */
class OrderItem extends Model
{
	use SoftDeletes;
	protected $table = 'order_items';

	protected $casts = [
		'fk_user' => 'int',
		'fk_product' => 'int',
		'fk_order' => 'int',
		'quantity' => 'int',
		'unit_price' => 'float',
		'amount' => 'float'
	];

	protected $fillable = [
		'fk_user',
		'fk_product',
		'fk_order',
		'quantity',
		'unit_price',
		'amount',
		'active'
	];

	public function order()
	{
		return $this->belongsTo(Order::class, 'fk_order');
	}

	public function product()
	{
		return $this->belongsTo(Product::class, 'fk_product');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'fk_user');
	}
}
