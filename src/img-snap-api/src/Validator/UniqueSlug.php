<?php
/*
 * This file just contains the Attribute that is added to fields
 * there is no logic here except the message format
 */

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class UniqueSlug extends Constraint {
    public string $message = 'The slug "{{ value }}" is already in use.';
}
