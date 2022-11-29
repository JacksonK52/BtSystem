<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m221111_020302_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string(255)->notNull(),
            'salt' => $this->integer(100)->notNull(),
            'name' => $this->string(255)->notNull(),
            'password' => $this->string(255)->notNull(),
            'email' => $this->string(255)->notNull(),
            'auth_key' => $this->string(100)->notNull(),
            'token_id' => $this->string(100)->notNull(),
            'img_location' => $this->string(255)->defaultValue('/default/user.png')->notNull(),
            'verify' => $this->tinyInteger(1)->defaultValue(0)->notNull(),  // 0 - Not Verify | 1 - Verify
            'role' => $this->tinyInteger(1)->defaultValue(1)->notNull(),    // 0 - Super-admin | 1 - Admin | 2 - Team-leader | 3 - Tester | 4 - Developer
            'status' => $this->tinyInteger(1)->defaultValue(1)->notNull(),  // 0 - Inactive | 1 - Active | 2 - Deleted
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // Super Admin
        $this->insert('{{%user}}', [
            'slug' => 'super_admin-' . Yii::$app->security->generateRandomString(8),
            'salt' => '361249',
            'name' => 'Super Admin',
            'password' => password_hash('lenovo@123361249', PASSWORD_DEFAULT), // bCrypt Encryption with salt
            'email' => 'admin@btsystem.io',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'token_id' => Yii::$app->security->generateRandomString(32),
            'verify' => 1,
            'role' => 0,
            'status' => 1
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%user}}');
        $this->dropTable('{{%user}}');
    }
}
