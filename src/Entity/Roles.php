<?php
/**
 * Created by PhpStorm.
 * User: samuelerb
 * Date: 18.12.17
 * Time: 17:51
 */

namespace App\Entity;

abstract class Roles {
    const ADMIN = "Admin";
    const USER = "Benutzer";

    public static function getStates() {
        return [
            self::ADMIN,
            self::USER
        ];
    }
}