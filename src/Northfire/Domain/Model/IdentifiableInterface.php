<?php

namespace Northfire\Domain\Model;

/**
 * Interface IdentifiableInterface
 *
 * @package Northfire\Domain\Model
 * @author  Hauke Weber
 */
interface IdentifiableInterface
{
    /**
     * @return string
     */
    public function __toString() : string;

    /**
     * @param string $id
     *
     * @return self
     */
    public static function fromString(string $id);

    /**
     * @return self
     */
    public static function generate();

    /**
     * @return string
     */
    public function toString() : string;
}