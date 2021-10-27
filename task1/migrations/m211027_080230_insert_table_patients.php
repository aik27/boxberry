<?php

use yii\db\Migration;

class m211027_080230_insert_table_patients extends Migration
{
    public function safeUp()
    {
        $data = [];
        for ($i = 1; $i <= 1000; $i++) {
            $data[] = [
                "Имя {$i}",
                "Фамилия {$i}",
            ];
        }
        $this->batchInsert('{{%patients}}', ["first_name", "last_name"], $data);
    }

    public function safeDown()
    {
        Yii::$app->db->createCommand()->truncateTable('{{%patients}}')->execute();
    }
}
