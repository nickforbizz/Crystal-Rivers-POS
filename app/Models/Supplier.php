<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Events\SupplierCreated;
use App\Traits\Cacheable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Supplier
 * 
 * @property int $id
 * @property int $fk_user
 * @property string $supplier_ID
 * @property string $first_name
 * @property string $last_name
 * @property string $names
 * @property string $email
 * @property string|null $phone
 * @property string|null $address
 * @property string $active
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Supplier extends Model
{
	use SoftDeletes, HasFactory;

	use Cacheable;
	protected $table = 'suppliers';

	protected $casts = [
		'fk_user' => 'int'
	];

	protected $fillable = [
		'fk_user',
		'supplier_ID',
		'first_name',
		'last_name',
		'names',
		'email',
		'phone',
		'address',
		'active'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'fk_user');
	}

	protected static function booted()
	{
		static::created(function ($supplier) {
			event(new SupplierCreated($supplier));
		});
	}
}
