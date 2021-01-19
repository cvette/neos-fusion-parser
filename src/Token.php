<?php

declare(strict_types=1);

namespace Vette\FusionParser;

use LogicException;

/**
 * Represents a Token.
 */
final class Token
{
    /** @var string  */
    private $value;

    /** @var int  */
    private $type;

    /** @var int  */
    private $lineno;

    private $column;

    public const EOF_TYPE = -1;
    public const WHITESPACE_TYPE = 0;
    public const LINE_BREAK = 1;

    public const DOT_TYPE = 2;
    public const COLON_TYPE = 3;
    public const LPAREN_TYPE = 4;
    public const RPAREN_TYPE = 5;
    public const LBRACE_TYPE = 6;
    public const RBRACE_TYPE = 7;

    public const INCLUDE_KEYWORD_TYPE = 8;
    public const INCLUDE_VALUE_TYPE = 9;
    public const NAMESPACE_KEYWORD_TYPE = 10;
    public const PROTOTYPE_KEYWORD_TYPE = 11;
    public const OBJECT_IDENTIFIER_TYPE = 12;
    public const OBJECT_PATH_PART_TYPE = 13;
    public const META_PROPERTY_KEYWORD_TYPE = 14;

    public const COPY_TYPE = 15;
    public const UNSET_TYPE = 16;
    public const ASSIGNMENT_TYPE = 17;

    public const DSL_START_TYPE = 18;
    public const DSL_CODE_TYPE = 19;
    public const DSL_END_TYPE = 20;

    public const EEL_EXPRESSION_TYPE = 21;
    public const NULL_VALUE_TYPE = 22;
    public const BOOLEAN_VALUE_TYPE = 23;
    public const NUMBER_VALUE_TYPE = 24;
    public const FLOAT_NUMBER_VALUE_TYPE = 25;
    public const STRING_VALUE_TYPE = 26;


    /**
     * @param int $type The type of the token
     * @param string $value The token value
     * @param int $lineno The line position in the source
     * @param int $column The column on the line
     */
    public function __construct(int $type, string $value, int $lineno, int $column)
    {
        $this->type = $type;
        $this->value = $value;
        $this->lineno = $lineno;
        $this->column = $column;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s(%s)',
            self::typeToString($this->type, true),
            $this->value
        );
    }

    public function getLine(): int
    {
        return $this->lineno;
    }

    public function getColumn(): int
    {
        return $this->column;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Returns the constant representation (internal) of a given type.
     *
     * @param int $type The type as an integer
     * @param bool $short Whether to return a short representation or not
     *
     * @return string The string representation
     */
    public static function typeToString(int $type, bool $short = false): string
    {
        switch ($type) {
            case self::EOF_TYPE:
                $name = 'EOF_TYPE';
                break;
            case self::OBJECT_IDENTIFIER_TYPE:
                $name = 'OBJECT_IDENTIFIER_TYPE';
                break;
            case self::WHITESPACE_TYPE:
                $name = 'WHITESPACE_TYPE';
                break;
            case self::NUMBER_VALUE_TYPE:
                $name = 'NUMBER_VALUE_TYPE';
                break;
            case self::FLOAT_NUMBER_VALUE_TYPE:
                $name = 'FLOAT_NUMBER_VALUE_TYPE';
                break;
            case self::UNSET_TYPE:
                $name = 'UNSET_TYPE';
                break;
            case self::LINE_BREAK:
                $name = 'LINE_BREAK_TYPE';
                break;
            case self::COPY_TYPE:
                $name = 'COPY_TYPE';
                break;
            case self::DOT_TYPE:
                $name = 'DOT_TYPE';
                break;
            case self::COLON_TYPE:
                $name = 'COLON_TYPE';
                break;
            case self::ASSIGNMENT_TYPE:
                $name = 'ASSIGNMENT_TYPE';
                break;
            case self::NULL_VALUE_TYPE:
                $name = 'NULL_VALUE_TYPE';
                break;
            case self::BOOLEAN_VALUE_TYPE:
                $name = 'BOOLEAN_VALUE_TYPE';
                break;
            case self::RBRACE_TYPE:
                $name = 'RBRACE_TYPE';
                break;
            case self::LBRACE_TYPE:
                $name = 'LBRACE_TYPE';
                break;
            case self::RPAREN_TYPE:
                $name = 'RPAREN_TYPE';
                break;
            case self::LPAREN_TYPE:
                $name = 'LPAREN_TYPE';
                break;
            case self::INCLUDE_KEYWORD_TYPE:
                $name = 'INCLUDE_KEYWORD_TYPE';
                break;
            case self::NAMESPACE_KEYWORD_TYPE:
                $name = 'NAMESPACE_KEYWORD_TYPE';
                break;
            case self::PROTOTYPE_KEYWORD_TYPE:
                $name = 'PROTOTYPE_KEYWORD_TYPE';
                break;
            case self::STRING_VALUE_TYPE:
                $name = 'STRING_VALUE_TYPE';
                break;
            case self::OBJECT_PATH_PART_TYPE:
                $name = 'OBJECT_PATH_PART_TYPE';
                break;
            case self::META_PROPERTY_KEYWORD_TYPE:
                $name = 'META_PROPERTY_KEYWORD_TYPE';
                break;
            case self::EEL_EXPRESSION_TYPE:
                $name = 'EEL_EXPRESSION_TYPE';
                break;
            case self::DSL_START_TYPE:
                $name = 'DSL_START_TYPE';
                break;
            case self::DSL_CODE_TYPE:
                $name = 'DSL_CODE_TYPE';
                break;
            case self::DSL_END_TYPE:
                $name = 'DSL_END_TYPE';
                break;
            default:
                throw new LogicException(sprintf('Token of type "%s" does not exist.', $type));
        }

        return $short ? $name : 'Fusion\Token::' . $name;
    }
}
