<?php

/**
 * Set price by color discount
 *
 * @param Database $db
 * @param array $colors
 * @return bool
 */

function setPriceByColor(Database $db, array $colors) : bool
{
    if (empty($colors)) {
        return false;
    }

    $db->query('
        CREATE TEMPORARY TABLE `tmp_color` (
            `color` tinytext NOT NULL,
            `discount_ratio` FLOAT NOT NULL DEFAULT 0
        ) ENGINE=MyISAM;
    ')->execute();

    $insert = "INSERT INTO `tmp_color` (`color`, `discount_ratio`) VALUES";
    $values = [];

    foreach ($colors as $color) {
        $discountRatio = 1 - $color['discount'] / 100;
        $values[] = '("' . $color['name'] . '", ' . $discountRatio  . ')';
    }

    $insert .= implode(',', $values);
    $db->query($insert)->execute();

    $db->query('
        UPDATE `products`
        LEFT JOIN `tmp_color` ON (tmp_color.color = products.color)
        SET products.price = products.price * tmp_color.discount_ratio;
    ')->execute();

    $db->query('DROP TEMPORARY TABLE `tmp_color`;')->execute();

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

setPriceByColor($database, $colors);