<?php

use yii\db\Migration;

class m211027_194741_insert_table_products extends Migration
{
    public function safeUp()
    {
        $data = [];
        for ($c = 0, $i = 1; $i <= 1000000; $c++, $i++) {
            $data[] = [
                $i,
                "Продукт {$i}",
                rand(1, 999999),
                $i % 2 ? "green" : "red"
            ];
            if ($c > 9999) {
                $this->batchInsert('{{%products}}', ["id", "name", "price", "color"], $data);
                $data = [];
                $c = 0;
            }
        }
        if ($c > 0) {
            $this->batchInsert('{{%products}}', ["id", "name", "price", "color"], $data);
        }
    }

    public function safeDown()
    {
        Yii::$app->db->createCommand()->truncateTable('{{%products}}')->execute();
    }
}
