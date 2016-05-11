<?php
namespace AppBundle\Entity\ValueObject;


class GenreValue
{

    const HEAVY_METALL = 101;
    const RUSSIAN_ROCK = 73;
    const BLUES_ROCK = 79;
    const FUNK = 85;


    public static function translate($code)
    {
        switch ($code) {
            case self::HEAVY_METALL:
                $value = "Heavy Metall";
                break;
            case self::RUSSIAN_ROCK:
                $value = "Russian Rock";
                break;
            case self::BLUES_ROCK:
                $value = "Blues Rock";
                break;
            case self::FUNK:
                $value = "Funk";
                break;
        }

        return $value;

    }
}