<?php
require '../vendor/autoload.php';
require '../config.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

$settings = array(
    'driver' => 'mysql',
    'host' => HOST,
    'database' => DBNAME,
    'username' => DBUSER,
    'password' => DBPASS,
    'collation' => 'utf8_general_ci',
    'prefix' => ''
);

//To conenct Illuminate with Slim Framework
$container = new Illuminate\Container\Container;
$connFactory = new \Illuminate\Database\Connectors\ConnectionFactory($container);
$conn = $connFactory->make($settings);
$resolver = new \Illuminate\Database\ConnectionResolver();
$resolver->addConnection('default', $conn);
$resolver->setDefaultConnection('default');
\Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);


/**
 * Get order by orderid, and return order and user data as json
 *
 * @param int $orderid   The requested order id
 *
 * @return json response
 */
$app->get('/getorder/orderid/{orderid}', function (Request $request, Response $response) {
    //get order if from URL request
    $orderid = $request->getAttribute('orderid');

    //Get Order model by order id
    $order = \app\models\Order::find($orderid);

    header("Content-Type: application/json");

    if ($order === null) {
        //If the order does not exist in the database, show error message
        echo json_encode(['message' => 'No orders found']);
    } else {
        //If the order exist, show order data as json
        $data = [
            'id' => $order->orderid,
            'total' => $order->total,
            'date' => $order->date,
            'status' => $order->status,
            'user' => [
                'userid' => $order->user->userid,
                'first_name' => $order->user->first_name,
                'last_name' => $order->user->last_name,
                'email' => $order->user->email,
                'phone' => $order->user->phone
            ]
        ];
        //print order data
        echo json_encode($data);
    }
});

/**
 * Cancel the order request, to change order status to be cancel
 *
 * @param int $orderid   The requested order id
 *
 * @return json response
 */
$app->get('/cancelorder/orderid/{orderid}', function (Request $request, Response $response) {
    //get order if from URL request
    $orderid = $request->getAttribute('orderid');
    //Get Order model by order id
    $order = \app\models\Order::find($orderid);

    if ($order === null) {
        //If the order does not exist in the database, show error message
        echo json_encode(['message' => 'No orders found']);
    } else {
        if ($order->status != 2) {
            //If the order exist, and the order is not canceled
            $order->status = 2;
            $order->save();

            echo json_encode(['message' => 'Order status was successfully changed to cancel']);
        } else if ($order->status == 2) {
            //If the order exist, and the order is canceled
            echo json_encode(['message' => 'the Order is already cancelled']);
        }
    }
});

$app->run();