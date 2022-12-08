<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%favorite_list}}`.
 */
class m221207_073910_create_favorite_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%favorite_list}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string(255)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'favorite_id' => $this->integer()->notNull(),
            'status' => $this->tinyInteger(1)->defaultValue(1)->notNull(),
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // User Id
        $this->createIndex('{{%idx-favorite_list-user_id}}', '{{%favorite_list}}', 'user_id');
        $this->addForeignKey('{{%fk-favorite_list-user_id-user-id}}', '{{%favorite_list}}', 'user_id', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');

        // Favorite Id
        $this->createIndex('{{%idx-favorite_list-favorite-id}}', '{{%favorite_list}}', 'favorite_id');
        $this->addForeignKey('{{%fk-favorite_list-favorite-id}}', '{{%favorite_list}}', 'favorite_id', '{{%favorite}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Favorite Id
        $this->dropForeignKey('{{%fk-favorite_list-favorite-id}}', '{{%favorite_list}}');
        $this->dropIndex('{{%idx-favorite_list-favorite-id}}', '{{%favorite_list}}');

        // User Id
        $this->dropForeignKey('{{%fk-favorite_list-user_id-user-id}}', '{{%favorite_list}}');
        $this->dropIndex('{{%idx-favorite_list-user_id}}', '{{%favorite_list}}');

        $this->dropTable('{{%favorite_list}}');
    }
}
