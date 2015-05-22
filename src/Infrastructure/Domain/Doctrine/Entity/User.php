<?php
namespace Infrastructure\Domain\Doctrine\Entity;

use Domain\Entity\UserInterface;
use Domain\Entity\User as BaseUser;
use Infrastructure\Domain\Doctrine\ArrayCollection;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class User extends BaseUser implements UserInterface
{
    public function __construct()
    {
        $this->socialAccounts = new ArrayCollection();
        parent::__construct();
    }

    public static function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable('users');

        $builder->addField('id', 'integer', [
            'id' => true
        ]);

        $builder->addField('email', 'string', [
            'nullable' => false,
            'options' => ['unique' => true]
        ]);

        $builder->addField('firstName', 'string', [
            'columnName' => 'first_name',
            'nullable' => false
        ]);

        $builder->addField('lastName', 'string', [
            'columnName' => 'last_name',
            'nullable' => false
        ]);

        $builder->addField('password', 'text', [
            'nullable' => true
        ]);

        $builder->addField('country', 'string', [
            'nullable' => false,
            'options' => ['length' => 2]
        ]);

        $builder->addField('locale', 'string', [
            'nullable' => false,
            'options' => ['length' => 5]
        ]);

        $builder->addField('verified', 'datetime', [
            'nullable' => true
        ]);

        $builder->addField('created', 'datetime', [
            'nullable' => false
        ]);
    }
}