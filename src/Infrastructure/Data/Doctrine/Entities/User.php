<?php
namespace Infrastructure\Data\Doctrine\Entities;

use Data\Entities\Contract;
use Data\Entities\User as BaseUser;
use Doctrine\ORM\Mapping\ClassMetadata;

class User extends BaseUser implements Contract\User
{
    public static function loadMetadata(ClassMetadata $metadata)
    {
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
            'fieldName' => 'password',
            'type' => 'text',
            'nullable' => false
        ]);
    }
}