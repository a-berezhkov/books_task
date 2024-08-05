<?php

use yii\db\Migration;

/**
 * Class m240804_155618_Subscription
 */
class m240804_155618_Subscription extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable("subscription", [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'author_id' => $this->integer()->notNull(),
            'phone' => $this->string()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey(
            'fk_subscription_user_id',
            'subscription',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_subscription_author_id',
            'subscription',
            'author_id',
            'author',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_subscription_user_id', 'subscription');
        $this->dropForeignKey('fk_subscription_author_id', 'subscription');
        $this->dropTable("subscription");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240804_155618_Subscription cannot be reverted.\n";

        return false;
    }
    */
}
