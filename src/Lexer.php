<?php

namespace Vette\FusionParser;

/**
 * Fusion Lexer
 *
 * @package Vette\FusionParser
 */
class Lexer
{
    /** lexical states */
    const STATE_INITIAL = 'initial';
    const STATE_PROTOTYPE_FOUND = 'prototype_start';
    const STATE_OBJECT_IDENTIFIER_FOUND = 'object_identifier_found';
    const STATE_VALUE_EXPECTED = 'value_expected';

    /** @var array */
    protected $states;

    /** @var int */
    protected $state;

    /** @var int */
    protected $cursor;

    /** @var int */
    protected $lineno;

    /** @var int */
    protected $end;

    /** @var Source */
    protected $source;

    /** @var array */
    protected $tokens;

    /** @var string */
    protected $code;

    /** @var array */
    protected $stateDefinitions;


    const LINE_BREAK = '/\n/A';
    const WHITESPACE = '/[ \t\f]+/A';

    const DOT = '/\./A';
    const COLON = '/:/A';
    const LPAREN = '/\(/A';
    const RPAREN = '/\)/A';
    const LBRACE = '/{/A';
    const RBRACE = '/}/A';
    const PROTOTYPE_KEYWORD = '/prototype/A';
    const OBJECT_IDENTIFIER = '/[.a-zA-Z0-9_]+/A';
    const PATH_PART = '/[a-zA-Z0-9_]+/A';
    const COPY_OPERATOR = '/</A';
    const UNSET_OPERATOR = '/>/A';
    const ASSIGNMENT_OPERATOR = '/=/A';

    const META_PROPERTY_KEYWORD = '/@/A';
    const META_PROPERTY_NAME = '[a-zA-Z0-9_\-]+/A';

    const VALUE_NULL = '/(NULL|null)/A';
    const VALUE_BOOLEAN = '/(true|TRUE|false|FALSE)/A';
    const VALUE_NUMBER = '/[\-]?[0-9] [0-9]* ("." [0-9] [0-9]*)?/A';


    /**
     * Lexer constructor.
     */
    public function __construct()
    {
        $this->stateDefinitions = [
            self::STATE_INITIAL => [
                self::PROTOTYPE_KEYWORD => function ($text) {
                    $this->pushToken(Token::LPAREN_TYPE, $text);
                    $this->pushState(self::STATE_PROTOTYPE_FOUND);
                },
                self::OBJECT_IDENTIFIER => function ($text) {
                    $this->pushToken(Token::IDENTIFIER_TYPE, $text);
                },
                self::LBRACE => function ($text) {
                    $this->pushToken(Token::LBRACE_TYPE, $text);
                },
                self::RBRACE => function ($text) {
                    $this->pushToken(Token::RBRACE_TYPE, $text);
                },
                self::COPY_OPERATOR => function ($text) {
                    $this->pushToken(Token::COPY_TYPE, $text);
                },
                self::UNSET_OPERATOR => function ($text) {
                    $this->pushToken(Token::UNSET_TYPE, $text);
                },
                self::ASSIGNMENT_OPERATOR => function ($text) {
                    $this->pushToken(Token::ASSIGNMENT_TYPE, $text);
                    $this->pushState(self::STATE_VALUE_EXPECTED);
                },
                self::DOT => function ($text) {
                    $this->pushToken(Token::DOT_TYPE, $text);
                }
            ],

            self::STATE_PROTOTYPE_FOUND => [
                self::LPAREN => function ($text) {
                    $this->pushToken(Token::LPAREN_TYPE, $text);
                },
                self::RPAREN => function ($text) {
                    $this->pushToken(Token::RPAREN_TYPE, $text);
                    $this->popState();
                },
                self::COLON => function ($text) {
                    $this->pushToken(Token::COLON_TYPE, $text);
                },
                self::OBJECT_IDENTIFIER => function ($text) {
                    $this->pushToken(Token::IDENTIFIER_TYPE, $text);
                }
            ],

            self::STATE_VALUE_EXPECTED => [
                self::VALUE_BOOLEAN => function ($text) {
                    $this->pushToken(Token::BOOLEAN_TYPE, $text);
                },
                self::VALUE_NULL => function ($text) {
                    $this->pushToken(Token::NULL_TYPE, $text);
                },
                self::VALUE_NUMBER => function ($text) {
                    $this->pushToken(Token::NUMBER_TYPE, $text);
                },
                self::OBJECT_IDENTIFIER => function ($text) {
                    $this->pushToken(Token::IDENTIFIER_TYPE, $text);
                },
                self::COLON=> function ($text) {
                    $this->pushToken(Token::COLON_TYPE, $text);
                },
                self::LINE_BREAK => function ($text) {
                    $this->pushToken(Token::LINE_BREAK, $text);
                    $this->popState();
                }
            ]
        ];
    }

    /**
     * Tokenize source
     *
     * @param Source $source
     * @return TokenStream
     * @throws \Exception
     */
    public function tokenize(Source $source): TokenStream
    {
        $this->cursor = 0;
        $this->lineno = 1;
        $this->states = [];
        $this->tokens = [];
        $this->source = $source;
        $this->state = self::STATE_INITIAL;
        $this->code = str_replace(["\r\n", "\r"], "\n", $source->getCode());
        $this->end = \strlen($this->code);

        while ($this->cursor < $this->end) {
            if ($this->lexState() || $this->lexWhitespace()) {
                continue;
            }

            throw new \Exception('Unexpected character: ' . $this->lineno . ':' . $this->cursor);
        }

        return new TokenStream($this->tokens, $this->source);
    }

    /**
     * @return bool
     */
    private function lexState()
    {
        foreach ($this->stateDefinitions[$this->state] as $pattern => $function) {
            if (preg_match($pattern, $this->code, $match, 0, $this->cursor)) {
                $function($match[0]);
                $this->moveCursor($match[0]);
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    private function lexWhitespace()
    {
        if (preg_match(self::WHITESPACE, $this->code, $match, 0, $this->cursor)) {
            $this->pushToken(Token::WHITESPACE_TYPE);
            $this->moveCursor($match[0]);
            return true;
        }

        if (preg_match(self::LINE_BREAK, $this->code, $match, 0, $this->cursor)) {
            $this->pushToken(Token::LINE_BREAK);
            $this->moveCursor($match[0]);
            return true;
        }

        return false;
    }

    /**
     * Pushes a token
     *
     * @param $type
     * @param string $value
     */
    private function pushToken($type, $value = '')
    {
        // do not push empty text tokens
        if (/* Token::TEXT_TYPE */ 0 === $type && '' === $value) {
            return;
        }

        echo $type . ' ';

        $this->tokens[] = new Token($type, $value, $this->lineno);
    }

    /**
     * Moves the cursor
     *
     * @param $text
     */
    private function moveCursor($text)
    {
        $this->cursor += \strlen($text);
        $this->lineno += substr_count($text, "\n");
    }

    /**
     * Pushes a state
     *
     * @param string $state
     */
    private function pushState(string $state)
    {
        $this->states[] = $this->state;
        $this->state = $state;
    }

    /**
     * Pops a state
     */
    private function popState()
    {
        if (0 === \count($this->states)) {
            throw new \LogicException('Cannot pop state without a previous state.');
        }
        $this->state = array_pop($this->states);
    }
}
