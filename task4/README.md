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

Можно сэкономить время на количестве запросов к базе данных от клиентского кода. Вместо 100 запросов (равных количеству цветов) уложиться в 4. + Получить ускорение за счёт внутреней оптимизации движка БД.

Для этого:

1. Создать временную таблицу 
2. Рассчитать коэффициент скидки для каждого цвета и вставить данные во временную таблицу групповым Insert.
3. Обновить цены в таблице Products, присоедив к записи данные из временной таблицы
4. Финальным шагом удалить временную таблицу.

Тест на 1 миллионе записей при 128 цветах показал время 26 секунд. Обычный перебор цветов с `UPDATE` на каждую запись занял 170 секунд. 

Если честно, затрудняюсь ответить, как ещё можно ускориться, например, без создания индекса на поле `color`. Насколько понимаю, `ON DUPLICATE KEY UPDATE` здесь не подходит.

Класс `Database` является псевдокодом.

```php

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

```

## Миграции для Yii 2


[Посмотреть >](https://github.com/aik27/boxberry/tree/master/task4/migrations)
