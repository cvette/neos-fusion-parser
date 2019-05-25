<?php

declare(strict_types=1);

namespace Vette\FusionParser;

use Vette\FusionParser\Ast\Nodes\AstNode;

/**
 * Abstract Parser
 *
 * @package Vette\FusionParser
 */
abstract class AbstractParser
{
    /** @var TokenStream */
    protected $tokenStream;

    /** @var Token */
    protected $lookahead;

    /** @var array<int> */
    protected $acceptedTokenTypes = [];


    /**
     * Parse source
     *
     * @param Source $source
     *
     * @return AstNode
     *
     * @throws ParserException
     */
    abstract public function parse(Source $source): AstNode;

    /**
     * Consume next token
     *
     * @return Token the current token
     */
    protected function consume(): Token
    {
        $this->acceptedTokenTypes = [];
        $current = $this->lookahead;
        $this->tokenStream->next();
        $this->lookahead = $this->tokenStream->current();

        return $current;
    }

    protected function expect(int $tokeType): Token
    {
        $this->acceptedTokenTypes = [];
        if ($this->lookahead->getType() === $tokeType) {
            return $this->consume();
        }

        throw new ParserException([$tokeType], $this->lookahead, $this->tokenStream->getSource());
    }

    protected function accept(int $tokenType): bool
    {
        $this->acceptedTokenTypes[] = $tokenType;
        if ($this->lookahead->getType() === $tokenType) {
            return true;
        }

        return false;
    }
}
