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
trait TokenableMethods
{

    /**
     * Returns the token base
     *
     * @return int
     */
    public function getTokenBase()
    {
        return 36;
    }

    /**
     * Returns whether or not the token gets regenerated on update.
     *
     * @return bool
     */
    public function getRegenerateTokenOnUpdate()
    {
        return false;
    }

    /**
     * Sets the entity's token.
     *
     * @param $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Returns the entity's token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Generates and sets the entity's slug. Called prePersist and preUpdate
     */
    public function generateTokenValue()
    {
        return base_convert(uniqid(mt_rand()), 16, $this->getTokenBase());
    }

    /**
     * Generates and sets the entity's slug. Called prePersist and preUpdate
     */
    public function generateToken()
    {
        if ( $this->getRegenerateTokenOnUpdate() || empty( $this->token ) ) {

            $this->token = $this->generateTokenValue();
        }
    }
}
