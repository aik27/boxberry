<?php

use yii\db\Migration;

class m211027_084825_insert_table_sensor_data extends Migration
{
    public function safeUp()
    {
        $data = [];
        $insertDaysNum = 30;
        $start = strtotime(date('Y-m-d 00:00:00')) - 86400 * $insertDaysNum;
        for ($day = 1; $day < $insertDaysNum; $day++) {
            for ($hour = 0; $hour <= 24; $hour++) {
                for ($interval = 0; $interval < 6; $interval++) {
                    for ($patient = 1; $patient <= 1000; $patient++) {
                        $created_at = $start + ($day * 86400) + ($hour * 3600) + ($interval * 600);
                        $created_at = date('Y-m-d H:i:s', $created_at);
                        $data[] = [
                            $patient,
                            rand(50, 200),
                            rand(50, 70),
                            rand(90, 200),
                            $created_at
                        ];
                    }
                }
            }
        }
        $this->batchInsert('{{%sensor_data}}', ["patient_id", "heart_rate", "pressure_min", "pressure_max", "created_at"], $data);
    }

    public function safeDown()
    {
        Yii::$app->db->createCommand()->truncateTable('{{%sensor_data}}')->execute();
    }
}
