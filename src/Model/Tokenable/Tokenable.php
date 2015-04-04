<?php

/*
 * This file is part of the KnpDoctrineBehaviors package.
 *
 * (c) KnpLabs <http://knplabs.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Knp\DoctrineBehaviors\Model\Tokenable;

/**
 * Tokenable trait.
 *
 * Should be used inside entity, that needs to be tokenable.
 */
trait Tokenable
{
    use TokenableProperties,
        TokenableMethods
    ;
}
