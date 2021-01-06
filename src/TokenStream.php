<?php

declare(strict_types=1);

namespace Vette\FusionParser;

use Iterator;

/**
 * Class TokenStream
 *
 * @package Vette\FusionParser
 */
class TokenStream implements Iterator
{
    /** @var array<Token> */
    private $tokens;

    /** @var Source|null */
    private $source;

    /** @var int */
    private $pointer;


    /**
     * TokenStream constructor.
     *
     * @param array<Token> $tokens
     * @param Source|null $source
     */
    public function __construct(array $tokens, ?Source $source = null)
    {
        $this->pointer = 0;
        $this->tokens = $tokens;
        $this->source = $source;

        if ($source === null) {
            $this->source = new Source('', '');
        }
    }

    public function getTokenAt(int $index): ?Token
    {
        if (!isset($this->tokens[$index])) {
            return null;
        }

        return $this->tokens[$index];
    }

    public function getPointer(): int
    {
        return $this->pointer;
    }

    public function current(): Token
    {
        return $this->tokens[$this->pointer];
    }

    public function next(): void
    {
        $this->pointer++;
    }

    public function key(): int
    {
        return $this->pointer;
    }

    public function valid(): bool
    {
        return isset($this->tokens[$this->pointer]);
    }

    public function rewind(): void
    {
        $this->pointer = 0;
    }

    public function getSource(): ?Source
    {
        return $this->source;
    }

    public function count(): int
    {
        return count($this->tokens);
    }
}
