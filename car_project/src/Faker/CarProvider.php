<?php
/**
 * Created by PhpStorm.
 * User: garyluypaert
 * Date: 2019-03-27
 * Time: 02:12
 */

namespace App\Faker;


use Faker\Provider\Base;

class CarProvider extends Base
{
    const CARBURANT = [
        'essence',
        'diesel',
        'electrique',
    ];

    const COLOR = [
        'rouge',
        'bleu',
        'vert',
        'argent',
    ];

    public function carCarburant() {
        return self::randomElement(self::CARBURANT);
    }

    public function carColor() {
        return self::randomElement(self::COLOR);
    }

    public function carPrice() {
        return rand(500, 5000);
    }
}