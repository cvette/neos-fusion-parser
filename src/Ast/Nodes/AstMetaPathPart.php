<?php

declare(strict_types=1);

namespace Vette\FusionParser\Ast\Nodes;

use Vette\FusionParser\Ast\AstNodeVisitor;
use Vette\FusionParser\Token;

/**
 * Class AstMetaPathPart
 *
 * @package Vette\FusionParser\Ast\Nodes
 */
class AstMetaPathPart extends AstNode
{
    /** @var Token */
    protected $pathPart;


    /**
     * AstMetaPathPart constructor.
     *
     * @param Token $pathPart
     */
    public function __construct(Token $pathPart)
    {
        $this->pathPart = $pathPart;
    }

    public function accept(AstNodeVisitor $visitor)
    {
        $visitor->visitMetaPathPart($this);
    }

    /**
     * @return Token
     */
    public function getPathPart(): Token
    {
        return $this->pathPart;
    }
}
