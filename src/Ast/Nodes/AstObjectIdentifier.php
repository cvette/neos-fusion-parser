<?php

declare(strict_types=1);

namespace Vette\FusionParser\Ast\Nodes;

use Vette\FusionParser\Ast\AstNodeVisitor;
use Vette\FusionParser\Token;

class AstObjectIdentifier extends AstNode
{
    /** @var Token|null */
    protected $namespace;

    /** @var Token */
    protected $identifier;


    /**
     * AstObjectIdentifier constructor.
     *
     * @param Token $identifier
     * @param Token|null $namespace
     */
    public function __construct(Token $identifier, ?Token $namespace = null)
    {
        $this->namespace = $namespace;
        $this->identifier = $identifier;
    }

    public function accept(AstNodeVisitor $visitor)
    {
        $visitor->visitObjectIdentifier($this);
    }

    public function getNamespace(): ?Token
    {
        return $this->namespace;
    }

    public function getIdentifier(): Token
    {
        return $this->identifier;
    }
}
