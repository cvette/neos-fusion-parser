<?php

declare(strict_types=1);

namespace Vette\FusionParser\Ast\Nodes;

use Vette\FusionParser\Ast\AstNodeVisitor;

/**
 * Class AstUnset
 *
 * @package Vette\FusionParser\Ast
 */
class AstUnset extends AstNode
{
    public function accept(AstNodeVisitor $visitor)
    {
        $visitor->visitUnset($this);
    }
}
