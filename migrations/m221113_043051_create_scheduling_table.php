<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%scheduling}}`.
 */
class m221113_043051_create_scheduling_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%scheduling}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string(255)->notNull(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'starting_date' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'ending_date' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'status' => $this->tinyInteger(1)->defaultValue(1)->notNull(),
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // Updated By
        $this->createIndex('{{%idx-scheduling-updated_by}}', '{{%scheduling}}', 'updated_by');
        $this->addForeignKey('{{%fk-scheduling-updated_by-user-id}}', '{{%scheduling}}', 'updated_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');

        // Created By
        $this->createIndex('{{%idx-scheduling-created_by}}', '{{%scheduling}}', 'created_by');
        $this->addForeignKey('{{%fk-scheduling-created_by-user-id}}', '{{%scheduling}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Created By
        $this->dropForeignKey('{{%fk-scheduling-created_by-user-id}}', '{{%scheduling}}');
        $this->dropIndex('{{%idx-scheduling-created_by}}', '{{%scheduling}}');

        // Updated By
        $this->dropForeignKey('{{%fk-scheduling-updated_by-user-id}}', '{{%scheduling}}');
        $this->dropIndex('{{%idx-scheduling-updated_by}}', '{{%scheduling}}');

        $this->dropTable('{{%scheduling}}');
    }
}
