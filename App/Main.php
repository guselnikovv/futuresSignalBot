<?php
include __DIR__ . '/../extensions/margin-api.php';
include __DIR__ . '/../extensions/futures-api.php';
include __DIR__ . '/../php-binance-api.php';
include __DIR__ . '/Db.php';

class Main extends Db
{
    public $user_id;
    public $budget;
    public $api;

    public function __construct($user_id)
    {
        parent::__construct();
        $stmt = $this->db->query("SELECT * FROM users WHERE id = $user_id");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->user_id = $user_id;
        $this->budget = $row['budget'];

        $this->api = new Binance\Api($row['api_key'], $row['api_secret']);
    }

    public function startSignal($signal_id, $limit = false)
    {
      $stmt = $this->db->query("SELECT * FROM signals WHERE id = $signal_id");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
          if($row){
            if($row['type'] == 'LONG'){
              $side = 'BUY';
              $tp = 'SELL';
            } else if($row['type'] == 'SHORT'){
              $side = 'SELL';
              $tp = 'BUY';
            }

            $price = $this->api->price($row['symbol']);

            //Расчитываем количество, которое мы можем купить учитывая 3 равных тейка, минимальный шаг цены и бюджета пользователя
            $quantity = $this->getQuantity($row['symbol'], $price);
            if($quantity === false) {
              echo 'Маленький Бюджет';
              die;
            }
            $part_take = $quantity / 3;

            echo "<br /> Проверки пройдены, размещаем первый ордер.";
            //Если не передали параметр true пропускаем firstOrder
            if($limit !== true){
              if($row['in_price'] !== null){
                echo "Размещаем первый LIMIT order";
                $firstOrder = $this->newOrderFutures('FIRST', $signal_id, $row['symbol'], $side, 'LIMIT', $quantity, $row['in_price'], 'null', 'GTC');
                if($firstOrder !== false){
                  $this->db->query("INSERT INTO signals_users (user_id, signal_id, status) VALUES ($this->user_id, $signal_id, 'NEW_LIMIT')");
                  die;
                }
                var_dump($firstOrder);
              } else {
                $firstOrder = $this->newOrderFutures('FIRST', $signal_id, $row['symbol'], $side, 'MARKET', $quantity, $price, 'null', 'GTC');
              }
            }
            
            
              if($firstOrder !== false) {
                echo "<br /> Позиция открыта.";
                $take1 = $this->newOrderFutures('TAKE', $signal_id, $row['symbol'], $tp, 'TAKE_PROFIT', $part_take, $row['tp1'], $row['tp1'], 'GTC');
              } else {
                echo "<br /> Ошибка добавления в базу";
                die;
              }

              if($take1 !== false) {
                echo "<br /> Первый тейк размещен.";
                $take2 = $this->newOrderFutures('TAKE',$signal_id, $row['symbol'], $tp, 'TAKE_PROFIT', $part_take, $row['tp2'], $row['tp2'], 'GTC');
              } else {
                echo "<br /> Ошибка добавления в базу";
                die;
              }

              if($take2 !== false) {
                echo "<br /> Второй тейк размещен.";
                $take3 = $this->newOrderFutures('TAKE', $signal_id, $row['symbol'], $tp, 'TAKE_PROFIT', $part_take, $row['tp3'], $row['tp3'], 'GTC');
              } else {
                echo "<br /> Ошибка добавления в базу";
                die;
              }

              if($take3 !== false) {
                echo "<br /> Третий тейк размещен.";
                $stop = $this->newOrderFutures('STOP', $signal_id, $row['symbol'], $tp, 'STOP_MARKET', null, null, $row['stop'], 'GTC', 'true');
              } else {
                echo "<br /> Ошибка добавления в базу";
                die;
              }

              //Игнорируем при размещении лимитного ордера
              if($limit !== true){
                if($stop !== false) {
                  echo "<br /> Стоп размещен.";
                  $this->db->query("INSERT INTO signals_users (user_id, signal_id, status) VALUES ($this->user_id, $signal_id, 'NEW')");
                }
              } else {
                if($stop !== false) {
                  echo "<br /> Стоп размещен.";
                  $this->db->query("UPDATE signals_users SET status = 'NEW' WHERE signal_id = '$signal_id')");
                }
              }

          }
    }


    public function newOrderFutures($checked = '', $signal_id, $symbol, $side, $type, $quantity, $price, $stopPrice, $timeInForce, $closePosition = null)
    {

        $positionSide = null;
        $reduceOnly = null;
        $newClientOrderId = null;
        $activationPrice = null;
        $callbackRate = null;
        $workingType = null;
        $priceProtect = null;
        $newOrderRespType = "RESULT";
        $test = false;

        $user_id = $this->user_id;
        $newOrder = $this->api->futuresOrder($symbol, $side, $type, $positionSide, $reduceOnly, $quantity,
        $price, $newClientOrderId, $stopPrice, $closePosition, $activationPrice, $callbackRate, $timeInForce, $workingType, $priceProtect, $newOrderRespType);

        foreach ($newOrder as $k => $v) {
          $$k = $v;
        }
        if($newOrder['reduceOnly'] === false) $reduceOnly = 0;
        if($newOrder['closePosition'] === false) $closePosition = 0;

        $stmt = $this->db->query("INSERT INTO orders (user_id, signal_id, checked, orderId, symbol, status, clientOrderId, price, avgPrice, origQty, executedQty, cumQty, cumQuote, timeInForce, type, reduceOnly, closePosition, side, positionSide, stopPrice, workingType, origType, updateTime) VALUES        ($user_id, $signal_id, '$checked', '$orderId', '$symbol', '$status', '$clientOrderId', '$price', '$avgPrice', '$origQty', '$executedQty', '$cumQty', '$cumQuote', '$timeInForce', '$type', $reduceOnly, $closePosition, '$side', '$positionSide', '$stopPrice', '$workingType', '$origType', '$updateTime')");

        if($stmt) return $newOrder;
        else return false;


    }

    public function getQuantity($symbol, $price)
    {
      $stmt = $this->db->query("SELECT minQty FROM lot_size WHERE symbol = '$symbol'");
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $minQty = $row['minQty'];

      $minStep = $minQty * $price;
      $part = $this->budget / 3;
      if($part > $minStep){
        $factor = intVal($this->budget / $minStep);
        $factor = intVal($factor / 3);
        return $minQty * 3 * $factor;
      } else return false;
    }

    public function checkOrders()
    {
        $user_id = $this->user_id;

        echo "Проверяем наличие лимитных ордеров на вход";
        $stmt = $this->db->query("SELECT * FROM signals_users WHERE user_id = $user_id AND status = 'NEW_LIMIT' ");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row){
          echo "<br> Есть лимитный ордер";
          $signal_id = $row['signal_id'];
          $stmt = $this->db->query("SELECT * FROM signals WHERE id = $signal_id");
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          $symbol = $row['symbol'];
          
          $stmt = $this->db->query("SELECT * FROM orders WHERE user_id = $user_id AND signal_id = $signal_id AND checked = 'FIRST' AND symbol = '$symbol' AND type = 'LIMIT' ");
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row) {
              echo "Найдет first ордер";
              $limit_order = $this->api->futuresGetOrder($row['symbol'], $row['orderId']);
              if($limit_order['status'] == 'NEW') {
                echo "<br> Лимитный ордер не отработал";
              } else if($limit_order['status'] == 'CANCELED'){
                echo "<br Ордер отменет. закрываем ордера в базе и завершаем сигнал";
                $this->db->query("UPDATE signals_users SET status = 'CLOSED' WHERE signal_id = $signal_id");
                $result = $this->updateOrdersBase($limit_order, $user_id, $limit_order['signal_id'], $row['id']);
                
            } else if ($limit_order['status'] == 'FILLED'){
              echo "Лимитный ордер отработал, выставляем тейки и стоп";
              $this->startSignal($signal_id, true);
              $result = $this->updateOrdersBase($limit_order, $user_id, $limit_order['signal_id'], $row['id']);
              $this->db->query("UPDATE signals_users SET status = 'NEW' WHERE signal_id = $signal_id");
            }
          }
          
        }

        $stmt = $this->db->query("SELECT * FROM signals_users WHERE user_id = $user_id AND status = 'NEW'");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row){
          $signal_id = $row['signal_id'];
          $id = $row['id'];
          echo "<br> Есть незаконченные сигналы $signal_id";
          echo "<br> Проверяем стоп лос у пользователя $user_id";

          $stmt = $this->db->query("SELECT * FROM orders WHERE user_id = $user_id AND signal_id = $signal_id AND status != 'FILLED' AND status != 'CANCELED' AND type = 'STOP_MARKET'");
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row){
              //Запрашиваем статус стоп ордера
              $checkStop = $this->getStatusOrder($row['symbol'], $row['orderId']);

              if($checkStop['status'] == 'FILLED'){
                  echo "<br> Стоп отработал отменяем оставшиеся ордера.";
                  $this->api->futuresDeleteAllOpenOrders($checkStop['symbol']);

                  $stmt = $this->db->query("SELECT * FROM orders WHERE user_id = $user_id AND signal_id = $signal_id");
                  $row_or = $stmt->fetchAll(PDO::FETCH_ASSOC);

                  foreach ($row_or as $order) {
                    $order_arr = $this->api->futuresGetOrder($order['symbol'], $order['orderId']);
                    $result = $this->updateOrdersBase($order_arr, $order['user_id'], $order['signal_id'], $order['id']);
                  }

                  if($result !== false) {
                    $this->db->query("UPDATE signals_users SET status = 'CLOSED' WHERE id = $id");
                    echo "Сигнал $signal_id для пользователя $user_id закрыт.";
                  }
                  die;
              } else if($checkStop['status'] == 'CANCELED'){
                $this->api->futuresDeleteAllOpenOrders($checkStop['symbol']);

                $stmt = $this->db->query("SELECT * FROM orders WHERE signal_id = $signal_id AND user_id = $user_id AND status = 'FILLED' AND checked = 'FIRST'");
                $firstOrder = $stmt->fetch(PDO::FETCH_ASSOC);

                echo "<br /> Ордер отменен пользователем. Отменяем все открытые ордера по символу" . $firstOrder['symbol'];
                if($firstOrder['side'] == 'BUY') $type = 'SELL';
                else $type = 'BUY';

                if(!isset($type)){
                    echo "<br /> Type не определен, завершаем цикл";
                    die;
                }

                $closePos = $this->api->futuresOrder($firstOrder['symbol'], $type, 'MARKET', null, null, $firstOrder['origQty']);
                echo "<br /> Закрываем позицию";

                $stmt = $this->db->query("SELECT * FROM orders WHERE user_id = $user_id AND signal_id = $signal_id");
                $row_or = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($row_or as $order) {
                  $order_arr = $this->api->futuresGetOrder($order['symbol'], $order['orderId']);
                  $result = $this->updateOrdersBase($order_arr, $order['user_id'], $order['signal_id'], $order['id']);
                }

                if($result !== false) {
                  $this->db->query("UPDATE signals_users SET status = 'CLOSED' WHERE id = $id");
                  echo "<br />Сигнал $signal_id для пользователя $user_id закрыт.";
                }
                die;
            }
        }

        $stmt = $this->db->query("SELECT * FROM signals WHERE id = $signal_id");
        $signals = $stmt->fetch(PDO::FETCH_ASSOC);

        echo "<br /> Проверяем открытые ордера";
        $stmt = $this->db->query("SELECT * FROM orders WHERE user_id = $user_id AND signal_id = $signal_id AND status != 'FILLED' AND status != 'CANCELED' AND checked = 'TAKE'");
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        
        if($row){
          
          foreach ($row as $order) {
            $order_arr = $this->api->futuresGetOrder($order['symbol'], $order['orderId']);
            echo "<br /> Order status" . $order_arr['status'];
            if($order_arr['status'] == 'FILLED'){
              echo "<br />Ордер  ".$order_arr['orderId']." для пользователя $user_id Исполнился.";
              $result = $this->updateOrdersBase($order_arr, $order['user_id'], $order['signal_id'], $order['id']);
              die;
            }
            if($order_arr['status'] == 'CANCELED'){
              $res = $this->api->futuresPositionRisk($order_arr['symbol']);
              $amt = abs($res[0]['positionAmt']);

              $stmt = $this->db->query("SELECT * FROM signals WHERE id = $signal_id");
              $row = $stmt->fetch(PDO::FETCH_ASSOC);

              if($row['type'] == 'LONG') $type = 'SELL';
              else $type = 'BUY';

              if(!isset($type)){
                  echo "<br /> Type не определен, завершаем цикл";
                  die;
              }
              echo '<br />Ордер отменет пользователемя, закрываем позицию. Остаток ' . $amt;
              echo "<br />Размещаем ордер на закрытие  $amt с типом $type";
              $this->api->futuresDeleteAllOpenOrders($signals['symbol']);
              $closePos = $this->api->futuresOrder($order_arr['symbol'], $type, 'MARKET', null, null, $amt);

              $stmt = $this->db->query("SELECT * FROM orders WHERE user_id = $user_id AND signal_id = $signal_id");
              $row_or = $stmt->fetchAll(PDO::FETCH_ASSOC);

              foreach ($row_or as $order) {
                $order_arr = $this->api->futuresGetOrder($order['symbol'], $order['orderId']);
                $result = $this->updateOrdersBase($order_arr, $order['user_id'], $order['signal_id'], $order['id']);
              }

              if($result !== false) {
                $this->db->query("UPDATE signals_users SET status = 'CLOSED' WHERE id = $id");
                echo "<br />Сигнал $signal_id для пользователя $user_id закрыт.";
              }

              die;
            }
          }
        } else {
          echo "<br /> Последний ордер закрылся Отменяем стоп";
          $this->api->futuresDeleteAllOpenOrders($signals['symbol']);
          $stmt = $this->db->query("SELECT * FROM orders WHERE user_id = $user_id AND signal_id = $signal_id AND status != 'NEW' AND status != 'CANCELED' AND type = 'STOP_MARKET'");
          $stmt = $this->db->query("UPDATE orders SET status = 'CANCELED' WHERE user_id = $user_id AND signal_id = $signal_id AND status = 'NEW'");
          $stmt = $this->db->query("UPDATE signals_users SET status = 'CLOSED' WHERE user_id = $user_id AND signal_id = $signal_id");
          die;
        }
    }
  }

    //Обновление ордера
    public function updateOrdersBase(array $order, $user_id, $signal_id, $table_id)
    {
      foreach ($order as $k => $v) {
        $$k = $v;
      }

       $stmt = $this->db->query("UPDATE orders SET status = '$status', price = '$price', avgPrice = '$avgPrice', origQty = '$origQty', executedQty = '$executedQty', cumQty = '$cumQty', cumQuote = '$cumQuote' WHERE id = $table_id");

      return $stmt;
    }

    public function getStatusOrder($symbol, $order_id)
    {
        return $this->api->futuresGetOrder($symbol, $order_id);
    }



    //Загрузка информации по минимально допустимому количеству покупки каждой монеты.
    public function addExchangeInfo(){

    	$info = file_get_contents('https://www.binance.com/fapi/v1/exchangeInfo');

    	$info_arr = json_decode($info, true);

    	foreach ($info_arr as $key => $value) {
    		foreach ($value as $k => $v) {
    			$symbol = $v['symbol'];
    			$minQty = $v['filters'][2]['minQty'];

    			$stmt = $this->db->query("SELECT * FROM lot_size WHERE symbol = '$symbol'");
    			$row = $stmt->fetch(PDO::FETCH_ASSOC);
    			if($row !== false){
    				$id = $row['id'];
    				$stmt = $this->db->query("UPDATE lot_size SET minQty = $minQty WHERE id = $id");
    			} else {
    				$stmt = $this->db->query("INSERT INTO lot_size (symbol, minQty) VALUES ('$symbol', '$minQty')");
    			}
    		}

    	}
    }

}
