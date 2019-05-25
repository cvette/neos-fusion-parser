<?php

declare(strict_types=1);

namespace Vette\FusionParser\Ast\Nodes;

use Vette\FusionParser\Ast\AstNodeVisitor;

/**
 * Class AstAssignment
 *
 * @package Vette\FusionParser\Ast
 */
class AstCopy extends AstNode
{
    /** @var AstPath */
    protected $path;

    /** @var AstBlock|null */
    protected $block;


    public function __construct(AstPath $path, ?AstBlock $block = null)
    {
        $this->path = $path;
        $this->block = $block;
    }

    public function accept(AstNodeVisitor $visitor)
    {
        $visitor->visitCopy($this);
    }

    public function getPath(): AstPath
    {
        return $this->path;
    }

    public function getBlock(): ?AstBlock
    {
        return $this->block;
    }
}
