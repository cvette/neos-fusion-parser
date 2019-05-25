<?php

declare(strict_types=1);

namespace Vette\FusionParser\Ast\Nodes;

use Vette\FusionParser\Ast\AstNodeVisitor;
use Vette\FusionParser\Token;

/**
 * Class AstInclude
 *
 * @package Vette\FusionParser\Ast
 */
class AstInclude extends AstNode
{
    /** @var Token */
    protected $value;


    /**
     * AstInclude constructor.
     *
     * @param $value Token
     */
    public function __construct(Token $value)
    {
        $this->value = $value;
    }

    public function accept(AstNodeVisitor $visitor)
    {
        $visitor->visitInclude($this);
    }

    /**
     * @return Token
     */
    public function getValue(): Token
    {
        return $this->value;
    }
}
