<?php

namespace App\Services;

use App\Models\Product;


class  CampaignService
{
    public function applyDiscounts($orderItems, $totalAmount)
    {
        $totalAmountWithSabahattinAli = $this->applySabahattinAliDiscount($orderItems);
        $totalAmountWithTotalAmountDiscount = $this->applyTotalAmountDiscount($totalAmount);
        // TODO If no campaign has been applied, the 'none' campaign will be applied!
        if ($totalAmountWithSabahattinAli < $totalAmountWithTotalAmountDiscount) {
            $discountedTotalAmount = $totalAmountWithTotalAmountDiscount;
            $appliedCampaign = 'TT5';
        } else if ($totalAmountWithSabahattinAli == $totalAmountWithTotalAmountDiscount) {
            $discountedTotalAmount = 0;
            $appliedCampaign = 'None';
        } else {
            $discountedTotalAmount = $totalAmountWithSabahattinAli;
            $appliedCampaign = 'SBHTTN2';
        }
        $discountedTotalAmount = round($discountedTotalAmount, 2);

        return ['discountedTotalAmount' => $discountedTotalAmount, 'appliedCampaign' => $appliedCampaign];
    }

    private function applySabahattinAliDiscount($orderItems)
    {
        $sabahattinAliItemCount = 0;
        $discountAmount = 0;
        $maxProductPrice = 0;
        foreach ($orderItems as $orderItem) {
            $product = Product::where('product_id', '=', $orderItem['product_id'])
                ->where('author', '=', 'Sabahattin Ali')
                ->where('category_id', '=', 1)
                ->first();
            if ($product) {
                $sabahattinAliItemCount += $orderItem['quantity'];
                $maxProductPrice = max($maxProductPrice, $product->list_price);
                if ($sabahattinAliItemCount >= 2) {
                    $freeSabahattinAliItemCount = min($sabahattinAliItemCount, 1);
                    $discountAmount = $freeSabahattinAliItemCount * $maxProductPrice;
                }
            }
        }
        return $discountAmount;
    }

    private function applyTotalAmountDiscount($totalAmount)
    {
        $discountAmount = 0;

        if ($totalAmount >= 100) {
            $discountAmount = $totalAmount * 0.05;
        }

        return $discountAmount;
    }

}
