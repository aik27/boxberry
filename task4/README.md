# Задание №4

**Условие:** 

Есть таблица, которая хранит сведения о товарах вида:

```sql
CREATE TABLE `products ` (
`id` int(11) NOT NULL,
`name` tinytext,
`price` float(9,2) DEFAULT '0.00',
`color` tinytext,
UNIQUE KEY `id` (`id`)
) ENGINE=innoDB;
```

Товаров более 1 млн. Различных цветов более 100.

Перед вами стоит задача, обновить цену в зависимости от цвета товара. Например, товарам с color=red цену уменьшить на 5%, товарам с color=green, увеличить цену на 10% и т.д.
Напишите PHP + SQL скрипт как это сделать максимально эффективно с точки зрения производительности.

**Решение:**

```php

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

```

Тест на 1 миллионе записей при 147 цветах показал время **4.449 сек.**

Сравнивал с другими способами:

+ Обычный перебор цветов с `UPDATE` на каждую запись - 170 секунд
+ Обновление через временную таблицу - 26 секунд

## Миграции для Yii 2


[Посмотреть >](https://github.com/aik27/boxberry/tree/master/task4/migrations)
