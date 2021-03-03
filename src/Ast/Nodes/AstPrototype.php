<?php

declare(strict_types=1);

namespace Vette\FusionParser\Ast\Nodes;

use Vette\FusionParser\Ast\AstNodeVisitor;

/**
 * Class AstPrototype
 *
 * @package Vette\FusionParser\Ast\Nodes
 */
class AstPrototype extends AstNode
{
    /** @var AstObjectIdentifier */
    protected $objectIdentifier;


    /**
     * AstPrototype constructor.
     *
     * @param AstObjectIdentifier $objectIdentifier
     */
    public function __construct(AstObjectIdentifier $objectIdentifier)
    {
        $this->objectIdentifier = $objectIdentifier;
    }

    public function accept(AstNodeVisitor $visitor)
    {
        $visitor->visitPrototype($this);
    }

    public function getObjectIdentifier(): AstObjectIdentifier
    {
        return $this->objectIdentifier;
    }
}
