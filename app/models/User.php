<?php
namespace app\models;

/**
 * This is the model class for table "users".
 *
 * @property int $userid
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 */
class User extends \Illuminate\Database\Eloquent\Model
{
    /**
     * disable timestamp for this model.
     */
    public $timestamps = false;

    /**
     * The table associated with the model, called users.
     */
    protected $table = 'users';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'userid';

    //To get all orders associated to this user
    public function orders()
    {
        return $this->hasMany('app\models\Order');
    }
}

?>