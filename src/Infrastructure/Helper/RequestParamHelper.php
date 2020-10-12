<?php

namespace App\Infrastructure\Helper;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestParamHelper
 *
 * @author Oscar Jimenez <oscarjg19.developer@gmail.com>
 * @package App\Infrastructure\Helper
 */
class RequestParamHelper
{
    public static function param(Request $request, $param, $default = null)
    {
        $params = json_decode($request->getContent(), true);

        return $params[$param] ?? $default;
    }
}
