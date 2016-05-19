<?php

namespace PSA\AuthenticationBundle\Encoder;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\scalar;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

class PsaEncoder implements PasswordEncoderInterface
{
    public function encodePassword($raw, $salt)
    {
        return $raw;
    }

    public function isPasswordValid($encoded, $raw, $salt)
    {
        // @TODO A modifier peut être inutile voir si il faut faire un authenticator custom
        return true; //$encoded === $this->encodePassword($raw, $salt);
    }

}