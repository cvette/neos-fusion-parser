<?php

declare(strict_types=1);

namespace Vette\FusionParser\Ast\Nodes;

use Vette\FusionParser\Ast\AstNodeVisitor;

/**
 * Class AstFile
 *
 * @package Vette\FusionParser\Ast
 */
class AstFile extends AstNode
{
    /**
     * @var array<AstPathAction>
     */
    protected $pathActions = [];

    /**
     * @var array<AstInclude>
     */
    protected $includes = [];

    /**
     * @var array<AstNamespace>
     */
    protected $namespaces = [];


    public function accept(AstNodeVisitor $visitor)
    {
        $visitor->visitFile($this);
    }

    public function addPathAction(AstPathAction $pathAction): self
    {
        $this->pathActions[] = $pathAction;
        return $this;
    }

    public function addInclude(AstInclude $include): self
    {
        $this->includes[] = $include;
        return $this;
    }

    public function addNamespace(AstNamespace $namespace): self
    {
        $this->namespaces[] = $namespace;
        return $this;
    }

    /**
     * @return array<AstPathAction>
     */
    public function getPathActions(): array
    {
        return $this->pathActions;
    }

    /**
     * @return array
     */
    public function getIncludes(): array
    {
        return $this->includes;
    }

    /**
     * @return array
     */
    public function getNamespaces(): array
    {
        return $this->namespaces;
    }
}
