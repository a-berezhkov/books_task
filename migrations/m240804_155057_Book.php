<?php

use yii\db\Migration;

/**
 * Class m240804_155057_Book
 */
class m240804_155057_Book extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable("book", [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'year' => $this->integer()->notNull(),
            'description' => $this->text(),
            'isbn' => $this->text(),
            "photo_url" => $this->text(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            "created_by" => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable("book");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240804_155057_Book cannot be reverted.\n";

        return false;
    }
    */
}
