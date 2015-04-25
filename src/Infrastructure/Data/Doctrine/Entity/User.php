<?php
namespace Infrastructure\Data\Doctrine\Entity;

use Data\Entity\UserInterface;
use Data\Entity\User as BaseUser;
use Doctrine\ORM\Mapping\ClassMetadata;

class User extends BaseUser implements UserInterface
{
    public static function loadMetadata(ClassMetadata $metadata)
    {
        $metadata->setTableName('users');

        $metadata->mapField([
            'id' => true,
            'fieldName' => 'id',
            'type' => 'integer'
        ]);

        $metadata->mapField([
            'fieldName' => 'email',
            'type' => 'string',
            'nullable' => false,
            'options' => [
                'unique' => true
            ]
        ]);

        $metadata->mapField([
            'fieldName' => 'firstName',
            'columnName' => 'first_name',
            'type' => 'string',
            'nullable' => false
        ]);

        $metadata->mapField([
            'fieldName' => 'lastName',
            'columnName' => 'last_name',
            'type' => 'string',
            'nullable' => false
        ]);

        $metadata->mapField([
            'fieldName' => 'password',
            'type' => 'text',
            'nullable' => true
        ]);

        $metadata->mapField([
            'fieldName' => 'created',
            'type' => 'datetime',
            'nullable' => false
        ]);
    }
}