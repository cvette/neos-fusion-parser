<?php

declare(strict_types=1);

namespace Vette\FusionParser\Ast\Nodes;

use Vette\FusionParser\Ast\AstNodeVisitor;

/**
 * Class AstNode
 */
abstract class AstNode
{
    /**
     * @param AstNodeVisitor $visitor
     */
    abstract public function accept(AstNodeVisitor $visitor);
}
