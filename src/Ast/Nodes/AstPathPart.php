<?php

declare(strict_types=1);

namespace Vette\FusionParser\Ast\Nodes;

use Vette\FusionParser\Ast\AstNodeVisitor;
use Vette\FusionParser\Token;

class AstPathPart extends AstNode
{
    /** @var Token */
    protected $pathPart;


    /**
     * AstPathPart constructor.
     *
     * @param Token $pathPart
     */
    public function __construct(Token $pathPart)
    {
        $this->pathPart = $pathPart;
    }

    public function accept(AstNodeVisitor $visitor)
    {
        $visitor->visitPathPart($this);
    }

    public function getPathPart(): Token
    {
        return $this->pathPart;
    }
}
