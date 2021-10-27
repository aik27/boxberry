<?php

use yii\db\Migration;

class m211027_080130_create_table_patients extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(
            '{{%patients}}',
            [
                'id' => $this->primaryKey(11)->unsigned(),
                'first_name' => $this->string(128)->notNull()->comment('Имя пациента'),
                'last_name' => $this->string(128)->notNull()->comment('Фамилия пациента'),
            ],
            $tableOptions
        );
        $this->addCommentOnTable('{{%patients}}', 'Пациенты');
    }

    public function safeDown()
    {
        $this->dropTable('{{%patients}}');
    }
}
