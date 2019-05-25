<?php

declare(strict_types=1);

namespace Vette\FusionParser\Ast\Nodes;

use Vette\FusionParser\Ast\AstNodeVisitor;
use Vette\FusionParser\Token;

/**
 * Class AstDsl
 *
 * @package Vette\FusionParser\Ast
 */
class AstDsl extends AstNode
{
    /** @var Token */
    protected $code;


    /**
     * AstDsl constructor.
     *
     * @param Token $code
     */
    public function __construct(Token $code)
    {
        $this->code = $code;
    }

    public function accept(AstNodeVisitor $visitor)
    {
        $visitor->visitDsl($this);
    }

    /**
     * @return Token
     */
    public function getCode(): Token
    {
        return $this->code;
    }
}
