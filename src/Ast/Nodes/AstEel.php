<?php

declare(strict_types=1);

namespace Vette\FusionParser\Ast\Nodes;

use Vette\FusionParser\Ast\AstNodeVisitor;

class AstEel extends AstNode
{
    public function accept(AstNodeVisitor $visitor)
    {
        $visitor->visitEel($this);
    }
}
