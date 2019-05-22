<?php

namespace Vette\FusionParser;

/**
 * Class TokenStream
 *
 * @package Vette\FusionParser
 */
class TokenStream
{
    private $tokens;
    private $source;


    /**
     * TokenStream constructor.
     *
     * @param array $tokens
     * @param Source|null $source
     */
    public function __construct(array $tokens, Source $source = null)
    {
        $this->tokens = $tokens;
        $this->source = $source ?: new Source('', '');

        foreach ($this->tokens as $token) {
            if ($token == Token::WHITESPACE_TYPE) {
                continue;
            }

            echo $token . ' ';
        }
    }

}