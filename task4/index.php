<?php

/**
 * Set price by color discount
 *
 * @param array $colors
 * @return bool
 */

function setPriceByColor(array $colors) : bool
{
    if (empty($colors)) {
        return false;
    }

    $names = [];
    $insert = "";

    foreach ($colors as $color) {
        if (!in_array($color['name'], $names)) {
            $names[] = '"' . $color['name'] . '"';
        }
        $discountRatio = 1 - $color['discount'] / 100;
        $insert .= 'WHEN color = "' . $color['name'] . '" THEN price * ' . $discountRatio . ' ';
    }

    $names = implode(",", $names);

    Yii::$app->getDb()->createCommand('
        UPDATE 
            products 
        SET `price` = 
            CASE ' . $insert . ' END 
        WHERE 
            color IN (' . $names . ')
    ')->execute();

    return true;
}

$colors = [
    1=> [
        'name' => 'Red',
        'discount' => 5
    ],
    2=> [
        'name' => 'Green',
        'discount' => 10
    ],
];

setPriceByColor($colors);