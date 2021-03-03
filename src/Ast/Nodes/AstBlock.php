<?php

declare(strict_types=1);

namespace Vette\FusionParser\Ast\Nodes;

use Vette\FusionParser\Ast\AstNodeVisitor;

/**
 * Class AstBlock
 *
 * @package Vette\FusionParser\Ast\Nodes
 */
class AstBlock extends AstFile
{
    public function accept(AstNodeVisitor $visitor)
    {
        $visitor->visitBlock($this);
    }
}
