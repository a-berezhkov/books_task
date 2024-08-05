<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $title
 * @property int $year
 * @property string|null $description
 * @property string|null $isbn
 * @property string|null $photo_url
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 *
 * @property BookHasAuthor[] $bookHasAuthors
 */
class Book extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $photoFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book';
    }
    /**
     * Summary of behaviors
     * @return array
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => false,
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'year'], 'required'],
            [['created_by'], 'integer'],
            [["year"], "integer", "min" => 1900, "max" => date("Y")],
            [['description', 'isbn', 'photo_url'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['photoFile'], 'file', 'extensions' => 'png,  jpg, jpeg', 'skipOnEmpty' => false],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название книги',
            'year' => 'Год',
            'description' => 'Описание',
            'isbn' => 'ISBN',
            'photo_url' => 'Фото',
            'created_at' => 'Добавлено',
            'updated_at' => 'Updated At',
            'created_by' => 'Автор',
            'photoFile' => 'Фото',
        ];
    }

    /**
     * Gets query for [[BookHasAuthors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookHasAuthors()
    {
        return $this->hasMany(BookHasAuthor::class, ['book_id' => 'id']);
    }
    /**
     * Gets query for [[Authors]].
     * @return Yii\db\ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('book_has_author', ['book_id' => 'id']);
    }
    /**
     * Uploads the photo file and saves its path.
     *
     * @return bool whether the photo was uploaded successfully.
     */
    public function upload()
    {
        if ($this->validate()) {
            $filePath = 'uploads/' . md5(uniqid(rand(), true)) . "_" . $this->photoFile->name;

            if ($this->photoFile->saveAs($filePath)) {
                $this->photo_url = $filePath;
                $this->save(false);
                return true;
            }
        }
        return false;
    }

    /**
     * Summary of deletePhoto
     * @return void
     */
    public function deletePhoto()
    {
        if ($this->photo_url && file_exists($this->photo_url)) {
            unlink($this->photo_url);
        }
    }
     
}
