<?php

declare(strict_types=1);

namespace Vette\FusionParser\Ast\Nodes;

use Vette\FusionParser\Ast\AstNodeVisitor;
use Vette\FusionParser\Token;

/**
 * Class AstNamespace
 *
 * @package Vette\FusionParser\Ast
 */
class AstNamespace extends AstNode
{
    /** @var Token */
    protected $alias;

    /** @var Token */
    protected $namespace;


    /**
     * AstNamespace constructor.
     *
     * @param Token $alias
     * @param Token $namespace
     */
    public function __construct(Token $alias, Token $namespace)
    {
        $this->alias = $alias;
        $this->namespace = $namespace;
    }

    public function accept(AstNodeVisitor $visitor)
    {
        $visitor->visitNamespace($this);
    }

    /**
     * @return Token
     */
    public function getAlias(): Token
    {
        return $this->alias;
    }

    /**
     * @return Token
     */
    public function getNamespace(): Token
    {
        return $this->namespace;
    }
}
