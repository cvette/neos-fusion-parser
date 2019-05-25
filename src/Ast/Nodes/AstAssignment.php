<?php

declare(strict_types=1);

namespace Vette\FusionParser\Ast\Nodes;

use Vette\FusionParser\Ast\AstNodeVisitor;
use Vette\FusionParser\Token;

/**
 * Class AstAssignment
 *
 * @package Vette\FusionParser\Ast
 */
class AstAssignment extends AstNode
{
    /** @var Token|null */
    protected $simpleValue;

    /** @var AstNode|null */
    protected $value;


    public function __construct(?Token $simpleValue = null, ?AstNode $value = null)
    {
        $this->simpleValue = $simpleValue;
        $this->value = $value;
    }

    public function accept(AstNodeVisitor $visitor)
    {
        $visitor->visitAssignment($this);
    }

    public function getSimpleValue(): ?Token
    {
        return $this->simpleValue;
    }

    public function getValue(): ?AstNode
    {
        return $this->value;
    }
}
