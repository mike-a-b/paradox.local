<?php

namespace App\Services;

class BladeService {
    public function printPriceBig($price, $pointLength = 2) {
        //$priceText = round($price, $pointLength);
        $priceText = number_format($price, $pointLength);
        $priceLR = explode('.', $priceText);
        $ret = $priceLR[0] . (!empty($priceLR[1]) ? "<span>.$priceLR[1]</span>" : '');

        return $ret;
    }

    public function printAssetPrice($price, $pointLength = 2) {
        if ($price >= 1 / $pointLength) {
            $ret = round($price, $pointLength);
        } else {
            $str = number_format($price, 16);
            $str = substr($str, strpos($str, '.') + 1);
            $len = strlen($str);
            $expPoint = 0;
            for ($i = 0; $i < $len; $i++) {
                if ($str[$i] !== '0') {
                    break;
                }
                $expPoint++;
            }
            if ($expPoint > 0) {
                $expPoint += 2;
                $localExpPoint = $expPoint > 2 ? 0 : 2;
                $ret = round($price * (10 ** $expPoint), $localExpPoint);
            } else {
                $ret = round($price, $pointLength);
            }
            //$ret = number_format($price, 16) .' '.$ret;
        }

        return $ret;
    }
}
