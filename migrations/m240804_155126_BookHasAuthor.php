<?php

use yii\db\Migration;

/**
 * Class m240804_155126_BookHasAuthor
 */
class m240804_155126_BookHasAuthor extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable("book_has_author", [
            'id' => $this->primaryKey(),
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey(
            'fk_book_has_author_book_id',
            'book_has_author',
            'book_id',
            'book',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_book_has_author_author_id',
            'book_has_author',
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
        $this->dropForeignKey('fk_book_has_author_book_id', 'book_has_author');
        $this->dropForeignKey('fk_book_has_author_author_id', 'book_has_author');
        $this->dropTable("book_has_author");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240804_155126_BookHasAuthor cannot be reverted.\n";

        return false;
    }
    */
}
