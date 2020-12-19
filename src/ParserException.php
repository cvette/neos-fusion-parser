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
    /** @var int[] */
    protected $expectedTokenTypes;

    /** @var Token */
    protected $actualToken;

    /** @var Source|null */
    protected $source;

    private const MESSAGE_SINGLE = 'Parser error: Expected %s got %s on line %s in %s';
    private const MESSAGE_MULTIPLE = 'Parser error: Expected one of %s got %s on line %s in %s';


    /**
     * ParserException constructor.
     *
     * @param Source|null $source
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

    /**
     * @return string
     */
    private function createMessage(): string
    {
        if (count($this->expectedTokenTypes) > 1) {
            return $this->createMultipleExpectedTokenMessage();
        } else if (count($this->expectedTokenTypes) === 1) {
            return $this->createSingleExpectedTokenMessage();
        }

        return '';
    }

    /**
     * @return string
     */
    private function createMultipleExpectedTokenMessage(): string
    {
        $tokenTypes = [];
        foreach ($this->expectedTokenTypes as $tokenType) {
            $tokenTypes[] = Token::typeToString($tokenType, true);
        }

        $expectedTokens = join(', ', $tokenTypes);
        return sprintf(
            self::MESSAGE_MULTIPLE,
            $expectedTokens,
            Token::typeToString($this->actualToken->getType(), true),
            $this->getLine(),
            $this->getFile()
        );
    }

    /**
     * @return string
     */
    private function createSingleExpectedTokenMessage(): string
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
