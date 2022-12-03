<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%team}}`.
 */
class m221113_043052_create_team_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%team}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string(255)->notNull(),
            'team_leader_id' => $this->integer()->notNull(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'updated_by' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'status' => $this->tinyInteger(1)->defaultValue(1)->notNull(),  // 0 - Inactive | 1 - Active | 2 - Deleted
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // Team Leader
        $this->createIndex('{{%idx-team-team_leader_id}}', '{{%team}}', 'team_leader_id');
        $this->addForeignKey('{{%fk-team-team_leader_id-user-id}}', '{{%team}}', 'team_leader_id', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');

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

        // Team Leader Id
        $this->dropForeignKey('{{%fk-team-team_leader_id-user-id}}', '{{%team}}');
        $this->dropIndex('{{%idx-team-team_leader_id}}', '{{%team}}');
        
        // Delete Table
        $this->dropTable('{{%team}}');
    }
}
