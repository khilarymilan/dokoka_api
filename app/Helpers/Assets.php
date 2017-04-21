<?php
namespace App\Helpers;

class Assets
{
    public static function renderJs($srcs)
    {
        return (empty($srcs) ? '' : Arr::encasedString('<script src="', $srcs, '"></script>'));
    }
}
