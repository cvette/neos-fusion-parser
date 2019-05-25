<?php

declare(strict_types=1);

namespace Vette\FusionParser;

use Exception;

/**
 * Class ParserException
 *
 * @package Vette\FusionParser
 */
class ParserException extends Exception
{
    /** @var array<int> */
    protected $expectedTokenTypes;

    /** @var Token */
    protected $actualToken;

    /** @var Source|null */
    protected $source;

    private const MESSAGE_SINGLE = 'Parser error: Expected %s got %s on line %s in %s';
    private const MESSAGE_MULTIPLE = 'Parser error: Expected one of %s got %s on line %i in %s';


    /**
     * ParserException constructor.
     *
     * @param Source $source
     * @param array<int> $expectedTokenTypes
     * @param Token $actualToken
     */
    public function __construct(array $expectedTokenTypes, Token $actualToken, ?Source $source = null)
    {
        $this->source = $source;
        $this->expectedTokenTypes = $expectedTokenTypes;
        $this->actualToken = $actualToken;
        $this->file = $this->source ? $this->source->getName() : '';
        $this->line = $this->actualToken->getLine();

        parent::__construct($this->createMessage());
    }

    private function createMessage(): string
    {
        if (count($this->expectedTokenTypes) > 1) {
            $this->createSingleExpectedTokenMessage();
        } else if (count($this->expectedTokenTypes) === 1) {
            $this->createMultipleExpectedTokenMessage();
        }

        return '';
    }

    private function createSingleExpectedTokenMessage()
    {
        $expectedTokens = join(', ', $this->expectedTokenTypes);
        return sprintf(
            self::MESSAGE_MULTIPLE,
            $expectedTokens,
            $tokenType = Token::typeToString($this->actualToken->getType(), true),
            $this->getLine(),
            $this->getFile()
        );
    }

    private function createMultipleExpectedTokenMessage()
    {
        $expectedToken = reset($this->expectedTokenTypes);
        $expectedToken = Token::typeToString($expectedToken, true);

        return sprintf(
            self::MESSAGE_SINGLE,
            $expectedToken,
            $tokenType = Token::typeToString($this->actualToken->getType(), true),
            $this->getLine(),
            $this->getFile()
        );
    }
}
