<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%favorite}}`.
 */
class m221207_064630_create_favorite_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%favorite}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string(255)->notNull(),
            'title' => $this->string(255)->notNull(),
            'icon' => $this->string(20)->notNull(),
            'access_level' => $this->tinyInteger(1)->defaultValue(0)->notNull(),    // 0 - Super-admin | 1 - Admin | 2 - Team-leader | 3 - Everyone
            'status' => $this->tinyInteger(1)->defaultValue(1)->notNull(),
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->batchInsert('{{%favorite}}', 
        ['slug', 'title', 'icon', 'access_level'],
        [
            ['slug' => "user-".time(), 'title' => 'User', 'icon' => 'fas fa-users', 'access_level' => 1],
            ['slug' => "project-".time(), 'title' => 'Project', 'icon' => 'fas fa-disc-drive', 'access_level' => 3]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%favorite}}');
    }
}
