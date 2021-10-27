<?php

use yii\db\Migration;


class m211027_192906_create_table_products extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(
            '{{%products}}',
            [
                'id' => $this->integer(11)->unique()->notNull(),
                'name' => $this->string(255)->comment('Название товара'),
                'price' => $this->float(2)->defaultValue('0.00')->comment('Стоимость'),
                'color' => $this->string(255)->comment('Цвет'),
            ],
            $tableOptions
        );
        $this->addCommentOnTable('{{%products}}', 'Продукты');
    }

    public function safeDown()
    {
        $this->dropTable('{{%products}}');
    }
}
