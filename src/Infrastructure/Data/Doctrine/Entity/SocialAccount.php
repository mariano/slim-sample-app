<?php
namespace Infrastructure\Data\Doctrine\Entity;

use Data\Entity\SocialAccountInterface;
use Data\Entity\SocialAccount as BaseSocialAccount;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

class SocialAccount extends BaseSocialAccount implements SocialAccountInterface
{
    protected $id;

    public static function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable('social_accounts');

        $builder->addField('id', 'integer', [
            'id' => true
        ]);

        $builder->addField('type', 'string', [
            'nullable' => false
        ]);

        $builder->addManyToOne('user', User::class);

        $builder->addField('identifier', 'string', [
            'nullable' => false
        ]);

        $builder->addField('data', 'json_array', [
            'nullable' => false
        ]);

        $builder->addField('created', 'datetime', [
            'nullable' => false
        ]);

        $builder->addUniqueConstraint([
            'type',
            'identifier'
        ], 'type__identifier');
    }
}