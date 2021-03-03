<?php

declare(strict_types=1);

namespace Vette\FusionParser\Ast\Nodes;

use Vette\FusionParser\Ast\AstNodeVisitor;

/**
 * Class AstPath
 *
 * @package Vette\FusionParser\Ast\Nodes
 */
class AstPath extends AstNode
{
    /** @var array<AstNode> */
    protected $parts = [];


    /**
     * @return array<AstNode>
     */
    public function getParts(): array
    {
        return $this->parts;
    }

    public function accept(AstNodeVisitor $visitor)
    {
        $visitor->visitPath($this);
    }

    public function addPart(AstNode $part): self
    {
        $this->parts[] = $part;
        return $this;
    }
}
