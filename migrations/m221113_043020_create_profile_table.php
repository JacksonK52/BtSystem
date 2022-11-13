<?php

use yii\db\Migration;

/**
 * Class m221113_043020_create_profile_table
 */
class m221113_043020_create_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%profile}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string(255)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'emp_id' => $this->string(100),
            'mobile' => $this->string(15),
            'address_line_one' => $this->string(255),
            'address_line_two' => $this->string(255),
            'landmark' => $this->string(200),
            'district' => $this->string(100),
            'pincode' => $this->integer(7),
            'state' => $this->string(100),
            'updated_by' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'status' => $this->tinyInteger(1)->defaultValue(1)->notNull(),  // 0 - Inactive | 1 - Active | 2 - Deleted
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // User ID
        $this->createIndex('{{%idx-profile-user_id}}', '{{%profile}}', 'user_id');
        $this->addForeignKey('{{%fk-profile-user_id-user-id}}', '{{%profile}}', 'user_id', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');

        // Updated By
        $this->createIndex('{{%idx-profile-updated_by}}', '{{%profile}}', 'updated_by');
        $this->addForeignKey('{{%fk-profile-updated_by-user-id}}', '{{%profile}}', 'updated_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');

        // Created By
        $this->createIndex('{{%idx-profile-created_by}}', '{{%profile}}', 'created_by');
        $this->addForeignKey('{{%fk-profile-created_by-user-id}}', '{{%profile}}', 'created_by', '{{%user}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Created By
        $this->dropForeignKey('{{%fk-profile-created_by-user-id}}', '{{%profile}}');
        $this->dropIndex('{{%idx-profile-created_by}}', '{{%profile}}');

        // Updated By
        $this->dropForeignKey('{{%fk-profile-updated_by-user-id}}', '{{%profile}}');
        $this->dropIndex('{{%idx-profile-updated_by}}', '{{%profile}}');
        
        // User ID
        $this->dropForeignKey('{{%fk-profile-user_id-user-id}}', '{{%profile}}');
        $this->dropIndex('{{%idx-profile-user_id}}', '{{%profile}}');

        $this->dropTable('{{%profile}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221113_043020_create_profile_table cannot be reverted.\n";

        return false;
    }
    */
}
