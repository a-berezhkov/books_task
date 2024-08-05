<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
 /**
     * {@inheritdoc}
     */
    public function rules(): array{
        return [
            [['username', 'email', 'password_hash', 'auth_key', 'access_token'], 'required'],
            [['username', 'email', 'password_hash', 'auth_key', 'access_token'], 'string'],
            [['username', 'email'], 'unique'],
            [['email'], 'email'],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'user';
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id): ?IdentityInterface
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId(): int|string
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Summary of findByUsername
     * @param string $username
     * @return User|null
     */
    public static function findByUsername($username): ?self
    {
        return static::findOne(['username' => $username]);
    }
    /**
     * Summary of validatePassword
     * @param string $password
     * @return bool
     */
    public function validatePassword(string $password):bool
    {
        return \Yii::$app->security->validatePassword($password, $this->password_hash);
    }
}