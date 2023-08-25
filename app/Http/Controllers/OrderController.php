<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CampaignController;

class OrderController extends Controller
{
    public function store(Request $request, CampaignController $campaignController)
    {
        DB::beginTransaction();

        try {

            $discountResponse = $campaignController->applyDiscounts($request);
            $discountData = json_decode($discountResponse->getContent(), true);

            $discountedTotalAmount = $discountData['discounted_total_amount']['discountedTotalAmount'];
            $appliedCampaign = $discountData['discounted_total_amount']['appliedCampaign'];

            $orderItems = json_decode($request->getContent(), true);
            $totalAmount = 0;

            foreach ($orderItems as $orderItem) {
                $product = Product::where('product_id', '=', $orderItem['product_id'])->first();
                $totalAmount += $product->list_price * $orderItem['quantity'];
            }
            $shippingFee = $totalAmount >= 200 ? 0 : 75;

            $order = new Order();
            $order->total_amount = $totalAmount + $shippingFee - $discountedTotalAmount;
            $order->shipping_fee = $shippingFee;
            $order->discount_amount = $discountedTotalAmount;
            $order->applied_campaign = $appliedCampaign;
            $order->save();

            foreach ($orderItems as $orderItem) {
                $product = Product::where('product_id', '=', $orderItem['product_id'])->first();
                $orderItemModel = new OrderItem();
                $orderItemModel->quantity = $orderItem['quantity'];
                $orderItemModel->order_id = $order->id;
                $orderItemModel->product_id = $product->product_id;
                $orderItemModel->price = $product->list_price;
                $orderItemModel->save();

                $product->stock_quantity -= $orderItem['quantity'];
                $product->save();
            }

            DB::commit();
            return response()->json([
                'message' => 'Order created successfully',
                'order_id' => $order->id,
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
