<?php

namespace App\Services;

use App\Models\Product;


class  CampaignService
{
    public function applyDiscounts($orderItems, $totalAmount)
    {
        $totalAmountWithSabahattinAli = $this->applySabahattinAliDiscount($orderItems);
        $totalAmountWithTotalAmountDiscount = $this->applyTotalAmountDiscount($totalAmount);

        if ($totalAmountWithSabahattinAli < $totalAmountWithTotalAmountDiscount) {
            $discountedTotalAmount = $totalAmountWithTotalAmountDiscount;
            $appliedCampaign = 'TT5';
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
        $product = null;

        foreach ($orderItems as $orderItem) {
            $product = Product::where('author', '=', 'Sabahattin Ali')
                ->where('category_id', '=', 1)
                ->first();
            if ($product) {
                $sabahattinAliItemCount += $orderItem['quantity'];
            }
        }
        $freeSabahattinAliItemCount = min($sabahattinAliItemCount, 1);

        $discountAmount = $freeSabahattinAliItemCount * $product->list_price;
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
