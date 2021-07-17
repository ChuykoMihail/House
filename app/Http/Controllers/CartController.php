<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderProducts;
//use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Response;

class CartController extends Controller
{
    public function add(Request $request){
      //получим цену товара
      $id = $request->input('id');
      $cost = str_replace(' ','',Product::where('id',$id)->value('cost'));
      //$name = Product::where('id',$id)->value('name');
      $strid = 'id'.(string)$id;


      //получим сумму тележки
      if(!($request->session()->has('sum'))){
        $request->session()->put('sum',0);
      }
      $sum =(int)$request->session()->get('sum',0);

      if (!($request->session()->has($strid))) {
        $request->session()->put($strid,1);
        $sum=$sum+$cost;
        $request->session()->put('sum',$sum);
        return response()-> json(['Message' => 'Product '.$id.' was added'], 200);
        //'product '.$id.' was added';

      }else{
        $request->session()->increment($strid);
        $sum=$sum+$cost;
        $request->session()->put('sum',$sum);
        return response()-> json(['Message' => 'Product '.$id.' was increased'], 200);
        //'product '.$id.' was increased';

      }
      return response()-> json(['Message' => 'something gone wrong'], 200);
      //'something gone wrong '.$id;
    }
    public function all(Request $request){
        $val=$request->session()->all();
        $returnable = array();
        $sum = $request->session()->get('sum',0);
        foreach($val as $key => $prod){
          if(preg_match('/id./',$key)){
            $nkey = str_replace('id','',$key);
            $returnable[$nkey]=$prod;
          }
        }
        $returnable['sum']=$sum;
        return response()-> json($returnable, 201);
    }
    public function delete(Request $request){
        $id = $request->input('id');
        $cost = str_replace(' ','',Product::where('id',$id)->value('cost'));
        $sum = (int)$request->session()->get('sum',0);

        if($request->session()->has('id'.$id)){
          $amount = (int)$request->session()->get('id'.$id,0);
          $request->session()->forget('id'.$id);
          $sum = $sum - $cost*$amount;
          $request->session()->put('sum',$sum);
          return response()-> json(['Message' => 'Product with id '.$id.' was deleted'], 200);
          //'product with id '.$id.' was deleted';
        }else return 'there is no such a product';

    }
    public function crement(Request $request){
        $id = $request->input('id');
        $crement = $request->input('crement');

        $cost = str_replace(' ','',Product::where('id',$id)->value('cost'));
        $sum = (int)$request->session()->get('sum',0);

        if($crement){
          $request->session()->increment('id'.$id);
          $sum = $sum+$cost;
          $request->session()->put('sum',$sum);
          return response()-> json(['Message' => 'Amount of product '.$id.' was increased'], 200);
          //'amount of product '.$id.' was increased';
        }
        else{
          $request->session()->decrement('id'.$id);
          $sum = $sum-$cost;
          $request->session()->put('sum',$sum);

          if($request->session()->put('sum',0)==0){
            $request->session()->forgot('id'.$id);
            return response()-> json(['Message' => 'Product'.$id.' was deleted'], 200);
            //'product '.$id.' was deleted';

          }else{
            return 'amount of product '.$id.'was decreased';
          }
        }
    }
    public function clear(Request $request){
      $request->session()->flush();
      return response()-> json(['Message' => 'Cart was cleared'], 200);
    }
    public function submit(Request $request){
      $name = $request->input('name');
      $email = $request->input('email');
      $adress = $request->input('adress');
      $sum = (int)$request->session()->get('sum',0);

      if($sum>0){
        $order = new Order;
        $order->client_name = $name;
        $order->client_mail=$email;
        $order->client_adress=$adress;
        $order->save();
      } else {return response()-> json(['Message' => 'Cart is empty'], 200);}

      $orderId = $order->id;
      $val=$request->session()->all();
      $returnable = array();
      $sum = $request->session()->get('sum',0);
      foreach($val as $key => $prod){
        if(preg_match('/id./',$key)){
          $nkey = str_replace('id','',$key);
          $orderProduct = new OrderProducts;
          $orderProduct->order_id=$orderId;
          $orderProduct->product_id=$nkey;
          $orderProduct->product_quantity=$prod;
          if($orderProduct->save()) {
            $request->session()->flush();
            return response()-> json(['Message' => 'Order was registrated. Thank you'], 200);}
        }
      }
    }
}
