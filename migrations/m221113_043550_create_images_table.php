<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%images}}`.
 */
class m221113_043550_create_images_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%images}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string(255)->notNull(),
            'bug_id' => $this->integer()->notNull(),
            'img_location' => $this->string(255)->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'status' => $this->tinyInteger(1)->defaultValue(1)->notNull(),
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // Bug Id
        $this->createIndex('{{%idx-images-bug_id}}', '{{%images}}', 'bug_id');
        $this->addForeignKey('{{%fk-images-bug_id-bug-id}}', '{{%images}}', 'bug_id', '{{%bug}}', 'id', 'RESTRICT', 'CASCADE');

        // Updated By
        $this->createIndex('{{%idx-images-updated_by}}', '{{%images}}', 'updated_by');
        $this->addForeignKey('{{%fk-images-updated_by-user-id}}', '{{%images}}', 'updated_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');

        // Created By
        $this->createIndex('{{%idx-images-created_by}}', '{{%images}}', 'created_by');
        $this->addForeignKey('{{%fk-images-created_by-user-id}}', '{{%images}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Created By
        $this->dropForeignKey('{{%fk-images-created_by-user-id}}', '{{%images}}');
        $this->dropIndex('{{%idx-images-created_by}}', '{{%images}}');
        
        // Updated By
        $this->dropForeignKey('{{%fk-images-updated_by-user-id}}', '{{%images}}');
        $this->dropIndex('{{%idx-images-updated_by}}', '{{%images}}');
        
        // Bug Id
        $this->dropForeignKey('{{%fk-images-bug_id-bug-id}}', '{{%images}}');
        $this->dropIndex('{{%idx-images-bug_id}}', '{{%images}}');

        // Deleted Table
        $this->dropTable('{{%images}}');
    }
}
