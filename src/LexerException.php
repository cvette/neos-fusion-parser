<?php

declare(strict_types=1);

namespace Vette\FusionParser;

use LogicException;

/**
 * Class LexerException
 *
 * @package Vette\FusionParser
 */
class LexerException extends LogicException
{

    /** @var int */
    protected $lineNumber;

    /** @var string */
    protected $character;


    /**
     * LexerException constructor.
     *
     * @param int $lineNumber
     * @param string $character
     * @param string $message
     */
    public function __construct(int $lineNumber, string $character, string $message = "")
    {
        $this->lineNumber = $lineNumber;
        $this->character = $character;

        parent::__construct($message);
    }
}