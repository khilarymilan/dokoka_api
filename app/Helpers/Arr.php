<?php
namespace App\Helpers;

use Illuminate\Support\Collection;

class Arr
{
    public static function encasedString($strStart, $arrSource, $strEnd)
    {
        if ($arrSource instanceof Collection) {
            $arrSource = $arrSource->toArray();
        }

        if (empty($arrSource)) {
            return '';
        }

        return $strStart . (is_array($arrSource) ? implode($strEnd . $strStart, $arrSource) : $arrSource) . $strEnd;
    }
}
