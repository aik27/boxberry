# Задание №3

**Условие:** 

Напишите PHP скрипт в который через GET передаются две даты, а скрипт должен рассчитать сколько вторников было между ними.

**Решение:**

```php
<?php

/**
 * Count tuesdays between two date
 *
 * @param string $dateStart
 * @param string $dateStop
 * @return int
 */

function tuesdayCounter(string $dateStart, string $dateStop) : int
{
    $dateStart = strtotime($dateStart);
    $dateStop = strtotime($dateStop);

    if ($dateStart === false) {
        throw new Exception('Start date is incorrect');
    }

    if ($dateStop === false) {
        throw new Exception('Stop date is incorrect');
    }

    if ($dateStart > $dateStop) {
        throw new Exception('Start date bigger then stop date');
    }

    $count = 0;

    for ($i = $dateStart; $i <= $dateStop; $i += 86400) {
        if (date("w", $i) == 2) {
            $count++;
        }
    }

    return $count;
}

try {
    if (!isset($_GET['dateStart'])) {
        throw new Exception('"dateStart" GET parameter is required');
    }
    if (!isset($_GET['dateStop'])) {
        throw new Exception('"dateStop" GET parameter is required');
    }
    echo tuesdayCounter($_GET['dateStart'], $_GET['dateStop']);
} catch (Exception $e) {
    echo $e->getMessage();
}
```