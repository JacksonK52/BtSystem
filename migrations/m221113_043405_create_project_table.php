<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%project}}`.
 */
class m221113_043405_create_project_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%project}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string(255)->notNull(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'updated_by' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'status' => $this->tinyInteger(1)->defaultValue(1)->notNull(),
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // Updated By
        $this->createIndex('{{%idx-project-updated_by}}', '{{%project}}', 'updated_by');
        $this->addForeignKey('{{%fk-project-updated_by-user-id}}', '{{%project}}', 'updated_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');

        // Created By
        $this->createIndex('{{%idx-project-created_by}}', '{{%project}}', 'created_by');
        $this->addForeignKey('{{%fk-project-created_by-user-id}}', '{{%project}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Created By
        $this->dropForeignKey('{{%fk-project-created_by-user-id}}', '{{%project}}');
        $this->dropIndex('{{%idx-project-created_by}}', '{{%project}}');
        
        // Updated By
        $this->dropForeignKey('{{%fk-project-updated_by-user-id}}', '{{%project}}');
        $this->dropIndex('{{%idx-project-updated_by}}', '{{%project}}');

        // Delete Table
        $this->dropTable('{{%project}}');
    }
}
