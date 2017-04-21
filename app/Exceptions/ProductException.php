<?php

namespace App\Exceptions;

class ProductException extends AppException
{
    const ERR_EMPTY_NAME = 'ERR_EMPTY_NAME';
    const ERR_DUPLICATE_NAME = 'ERR_DUPLICATE_NAME';
    const ERR_EMPTY_BRANCH = 'ERR_EMPTY_BRANCH';
    const ERR_EMPTY_TEAM = 'ERR_EMPTY_TEAM';
    const ERR_EMPTY_ACCOUNT = 'ERR_EMPTY_ACCOUNT';

    public static function messages()
    {
        return
            [
                self::ERR_EMPTY_NAME => 'Staff nickname must not be empty',
                self::ERR_DUPLICATE_NAME => 'Staff nickname must be unique',
                self::ERR_EMPTY_BRANCH => 'Staff branch must not be empty',
                self::ERR_EMPTY_TEAM => 'Staff team must not be empty',
                self::ERR_EMPTY_ACCOUNT => 'Staff DMM admin account user ID must not be empty',
            ];
    }

    public static function throwEmptyName()
    {
        static::throws(self::ERR_EMPTY_NAME);
    }

    public static function throwDuplicateName()
    {
        static::throws(self::ERR_DUPLICATE_NAME);
    }

    public static function throwEmptyBranch()
    {
        static::throws(self::ERR_EMPTY_BRANCH);
    }

    public static function throwEmptyTeam()
    {
        static::throws(self::ERR_EMPTY_TEAM);
    }

    public static function throwEmptyDmmAdminAccount()
    {
        static::throws(self::ERR_EMPTY_ACCOUNT);
    }
}
