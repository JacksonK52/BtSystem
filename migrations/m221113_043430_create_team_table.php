<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%team}}`.
 */
class m221113_043430_create_team_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%team}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string(255)->notNull(),
            'project_id' => $this->integer()->notNull(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'updated_by' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'status' => $this->tinyInteger(1)->defaultValue(1)->notNull(),
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // Project Id
        $this->createIndex('{{%idx-team-project_id}}', '{{%team}}', 'project_id');
        $this->addForeignKey('{{%fk-team-project_id-project-id}}', '{{%team}}', 'project_id', '{{%project}}', 'id', 'RESTRICT', 'CASCADE');

        // Updated By
        $this->createIndex('{{%idx-team-updated_by}}', '{{%team}}', 'updated_by');
        $this->addForeignKey('{{%fk-team-updated_by-user-id}}', '{{%team}}', 'updated_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');

        // Created By
        $this->createIndex('{{%idx-team-created_by}}', '{{%team}}', 'created_by');
        $this->addForeignKey('{{%fk-team-created_by-user-id}}', '{{%team}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Created By
        $this->dropForeignKey('{{%fk-team-created_by-user-id}}', '{{%team}}');
        $this->dropIndex('{{%idx-team-created_by}}', '{{%team}}');
        
        // Updated By
        $this->dropForeignKey('{{%fk-team-updated_by-user-id}}', '{{%team}}');
        $this->dropIndex('{{%idx-team-updated_by}}', '{{%team}}');
        
        // Project Id
        $this->dropForeignKey('{{%fk-team-project_id-project-id}}', '{{%team}}');
        $this->dropIndex('{{%idx-team-project_id}}', '{{%team}}');

        // Delete Table
        $this->dropTable('{{%team}}');
    }
}
