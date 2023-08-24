<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Product;

class CheckOrderValidation
{
    public function handle(Request $request, Closure $next): Response
    {
        $order = json_decode($request->getContent(), true);
        foreach ($order as $orderItem) {
            $productId = $orderItem['product_id'];
            $quantity = $orderItem['quantity'];
            if ($quantity <= 0) {
                return response()->json(['error' => 'Quantity of the product is not valid'], 400);
            }
            $product = Product::where('product_id', '=', $productId)->first();
            if (!$product) {
                return response()->json(['error' => "We're sorry, the product is unavailable"], 404);
            }
            if ($quantity > $product->stock_quantity) {
                return response()->json(['error' => "Unfortunately, we can't fulfill large quantities due to limited stock"], 400);
            }
        }
        return $next($request);
    }
}
