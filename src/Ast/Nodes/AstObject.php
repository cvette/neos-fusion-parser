<?php

declare(strict_types=1);

namespace Vette\FusionParser\Ast\Nodes;

use Vette\FusionParser\Ast\AstNodeVisitor;

/**
 * Class AstObject
 *
 * @package Vette\FusionParser\Ast
 */
class AstObject extends AstNode
{
    /** @var AstObjectIdentifier */
    protected $identifier;

    /** @var AstBlock|null */
    protected $block;


    public function __construct(AstObjectIdentifier $identifier, ?AstBlock $block = null)
    {
        $this->identifier = $identifier;
        $this->block = $block;
    }

    public function accept(AstNodeVisitor $visitor)
    {
        $visitor->visitObject($this);
    }

    /**
     * @return AstObjectIdentifier
     */
    public function getIdentifier(): AstObjectIdentifier
    {
        return $this->identifier;
    }

    /**
     * @return AstBlock|null
     */
    public function getBlock(): ?AstBlock
    {
        return $this->block;
    }
}
