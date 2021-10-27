<?php

use yii\db\Migration;

/**
 * Class m211027_081243_create_table_sensor_data
 */
class m211027_081243_create_table_sensor_data extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(
            '{{%sensor_data}}',
            [
                'id' => $this->bigPrimaryKey()->unsigned(),
                'patient_id' => $this->integer(11)->unsigned()->notNull()->comment('ID пациента'),
                'heart_rate' => $this->tinyInteger()->notNull()->comment('Пульс'),
                'pressure_min' => $this->tinyInteger()->notNull()->comment('Нижняя граница давления'),
                'pressure_max' => $this->tinyInteger()->notNull()->comment('Верхняя граница давления'),
                'created_at' => $this->dateTime()->notNull()->defaultExpression('NOW()')->comment('Дата и время измерения'),
            ],
            $tableOptions
        );
        $this->addCommentOnTable('{{%sensor_data}}', 'Данные сенсора');

        $this->createIndex('idx-sensor-data_patient_id', '{{%sensor_data}}', ['patient_id']);
        //$this->createIndex('idx-sensor-data_heart_rate', '{{%sensor_data}}', ['heart_rate']);
        //$this->createIndex('idx-sensor-data_pressure_max', '{{%sensor_data}}', ['pressure_max']);
        //$this->createIndex('idx-sensor-data_pressure_min', '{{%sensor_data}}', ['pressure_min']);
        $this->createIndex('idx-sensor-data_created_at', '{{%sensor_data}}', ['created_at']);

        $this->addForeignKey(
            'fk_patient',
            '{{%sensor_data}}',
            'patient_id',
            'patients',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%sensor_data}}');
        $this->dropForeignKey(
            'fk_patient',
            '{{%sensor_data}}'
        );
    }
}
