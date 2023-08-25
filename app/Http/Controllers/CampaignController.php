<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CampaignService;
use App\Models\Product;


class CampaignController extends Controller
{
    private $campaignService;

    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    public function applyDiscounts(Request $request)
    {
        $orderItems = json_decode($request->getContent(), true);
        $totalAmount = 0;

        foreach ($orderItems as $orderItem) {
            $product = Product::where('product_id', '=', $orderItem['product_id'])->first();
            $totalAmount += $product->list_price * $orderItem['quantity'];
        }
        $discountedTotalAmount = $this->campaignService->applyDiscounts($orderItems, $totalAmount);

        return response()->json([
            'message' => 'Discounts applied successfully',
            'discounted_total_amount' => $discountedTotalAmount,
        ], 200);
    }
}
