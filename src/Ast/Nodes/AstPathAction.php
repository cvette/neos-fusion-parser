<?php

declare(strict_types=1);

namespace Vette\FusionParser\Ast\Nodes;

use Vette\FusionParser\Ast\AstNodeVisitor;

/**
 * Class AstPathAction
 *
 * @package Vette\FusionParser\Ast
 */
class AstPathAction extends AstNode
{
    /** @var AstPath */
    protected $path;

    /** @var AstNode|null */
    protected $action;


    /**
     * AstPathAction constructor.
     *
     * @param AstPath $path
     * @param AstNode $action
     */
    public function __construct(AstPath $path, ?AstNode $action = null)
    {
        $this->path = $path;
        $this->action = $action;
    }

    public function accept(AstNodeVisitor $visitor)
    {
        $visitor->visitPathAction($this);
    }

    /**
     * @return AstPath
     */
    public function getPath(): AstPath
    {
        return $this->path;
    }

    /**
     * @param AstPath $path
     */
    public function setPath(AstPath $path): void
    {
        $this->path = $path;
    }

    /**
     * @return AstNode|null
     */
    public function getAction(): ?AstNode
    {
        return $this->action;
    }

    /**
     * @param AstNode|null $action
     */
    public function setAction(?AstNode $action): void
    {
        $this->action = $action;
    }
}
