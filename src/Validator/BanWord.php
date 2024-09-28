<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class BanWord extends Constraint
{
     public function __construct(
        public string $message ='This contrains a banned word {{ banword }} .',
        public array $BanWords = ['spam', 'viagra'],
        ?array $groups = null,
        mixed $payload = null
        )
    {
        parent::__construct(null, $groups, $payload );
    }


}
