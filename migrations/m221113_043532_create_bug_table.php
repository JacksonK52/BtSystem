<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bug}}`.
 */
class m221113_043532_create_bug_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bug}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string(255)->notNull(),
            'project_id' => $this->integer()->notNull(),
            'team_id' => $this->integer()->notNull(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'priority' => $this->integer(2)->defaultValue(5)->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'status' => $this->tinyInteger(1)->defaultValue(1)->notNull(),
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // Project Id
        $this->createIndex('{{%idx-bug-project_id}}', '{{%bug}}', 'project_id');
        $this->addForeignKey('{{%fk-bug-project_id-project-id}}', '{{%bug}}', 'project_id', '{{%project}}', 'id', 'RESTRICT', 'CASCADE');

        // Team Id
        $this->createIndex('{{%idx-bug-team_id}}', '{{%bug}}', 'team_id');
        $this->addForeignKey('{{%fk-bug-team_id-team-id}}', '{{%bug}}', 'team_id', '{{%team}}', 'id', 'RESTRICT', 'CASCADE');

        // Updated By
        $this->createIndex('{{%idx-bug-updated_by}}', '{{%bug}}', 'updated_by');
        $this->addForeignKey('{%fk-bug-updated_by-user-id}', '{{%bug}}', 'updated_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');

        // Created By
        $this->createIndex('{{%idx-bug-created_by}}', '{{%bug}}', 'created_by');
        $this->addForeignKey('{{%fk-bug-created_by-user-id}}', '{{%bug}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Created By
        $this->dropForeignKey('{{%fk-bug-created_by-user-id}}', '{{%bug}}');
        $this->dropIndex('{{%idx-bug-created_by}}', '{{%bug}}');

        // Updated By
        $this->dropForeignKey('{%fk-bug-updated_by-user-id}', '{{%bug}}');
        $this->dropIndex('{{%idx-bug-updated_by}}', '{{%bug}}');

        // Team Id
        $this->dropForeignKey('{{%fk-bug-team_id-team-id}}', '{{%bug}}');
        $this->dropIndex('{{%idx-bug-team_id}}', '{{%bug}}');

        // Project Id
        $this->dropForeignKey('{{%fk-bug-project_id-project-id}}', '{{%bug}}');
        $this->dropIndex('{{%idx-bug-project_id}}', '{{%bug}}');

        $this->dropTable('{{%bug}}');
    }
}
