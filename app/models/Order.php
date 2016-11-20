<?php
namespace app\models;

/**
 * This is the model class for table "orders".
 *
 * @property int $orderid
 * @property int $user_id
 * @property float $total
 * @property date $date
 * @property int $status
 */
class Order extends \Illuminate\Database\Eloquent\Model
{
    /**
     * disable timestamp for this model.
     */
    public $timestamps = false;

    /**
     * The table associated with the model, called orders.
     */
    protected $table = 'orders';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'orderid';

    //To get user information for this order
    public function user()
    {
        return $this->belongsTo('app\models\User');
    }
}

?>