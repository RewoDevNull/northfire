<?php

namespace Northfire\Domain\Model\Member\Exception;

/**
 * Class MemberNotFoundException
 *
 * @package Northfire\Domain\Model\Member\Exception
 * @author  Hauke Weber
 */
class MemberNotFoundException extends \DomainException
{
    protected $message = 'Member not found.';
}