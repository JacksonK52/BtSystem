<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%team_member}}`.
 */
class m221113_043053_create_team_member_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%team_member}}', [
            'id' => $this->primaryKey(),
            'team_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'status' => $this->tinyInteger(1)->defaultValue(1)->notNull(),
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // Team Id
        $this->createIndex('{{%idx-team_member-team_id}}', '{{%team_member}}', 'team_id');
        $this->addForeignKey('{{%fk-team_member-team_id-team-id}}', '{{%team_member}}', 'team_id', '{{%team}}', 'id', 'RESTRICT', 'CASCADE');

        // User Id
        $this->createIndex('{{%idx-team_member-user_id}}', '{{%team_member}}', 'user_id');
        $this->addForeignKey('{{%fk-team_member-user_id-user-id}}', '{{%team_member}}', 'user_id', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');

        // Updated By
        $this->createIndex('{{%idx-team_member-updated_by}}', '{{%team_member}}', 'updated_by');
        $this->addForeignKey('{{%fk-team_member-updated_by-user-id}}', '{{%team_member}}', 'updated_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');

        // Created By
        $this->createIndex('{{%idx-team_member-created_by}}', '{{%team_member}}', 'created_by');
        $this->addForeignKey('{{%fk-team_member-created_by-user-id}}', '{{%team_member}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Created By
        $this->dropForeignKey('{{%fk-team_member-created_by-user-id}}', '{{%team_member}}');
        $this->dropIndex('{{%idx-team_member-created_by}}', '{{%team_member}}');
        
        // Updated By
        $this->dropForeignKey('{{%fk-team_member-updated_by-user-id}}', '{{%team_member}}');
        $this->dropIndex('{{%idx-team_member-updated_by}}', '{{%team_member}}');
        
        // User Id
        $this->dropForeignKey('{{%fk-team_member-user_id-user-id}}', '{{%team_member}}');
        $this->dropIndex('{{%idx-team_member-user_id}}', '{{%team_member}}');
        
        // Team Id
        $this->dropForeignKey('{{%fk-team_member-team_id-team-id}}', '{{%team_member}}');
        $this->dropIndex('{{%idx-team_member-team_id}}', '{{%team_member}}');

        // Delete Table
        $this->dropTable('{{%team_member}}');
    }
}
