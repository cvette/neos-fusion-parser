<?php

declare(strict_types=1);

namespace Vette\FusionParser;

use Vette\FusionParser\Ast\AstNodeVisitor;
use Vette\FusionParser\Ast\Nodes\AstAssignment;
use Vette\FusionParser\Ast\Nodes\AstBlock;
use Vette\FusionParser\Ast\Nodes\AstCopy;
use Vette\FusionParser\Ast\Nodes\AstDsl;
use Vette\FusionParser\Ast\Nodes\AstEel;
use Vette\FusionParser\Ast\Nodes\AstFile;
use Vette\FusionParser\Ast\Nodes\AstInclude;
use Vette\FusionParser\Ast\Nodes\AstMetaPathPart;
use Vette\FusionParser\Ast\Nodes\AstNamespace;
use Vette\FusionParser\Ast\Nodes\AstObject;
use Vette\FusionParser\Ast\Nodes\AstObjectIdentifier;
use Vette\FusionParser\Ast\Nodes\AstPath;
use Vette\FusionParser\Ast\Nodes\AstPathAction;
use Vette\FusionParser\Ast\Nodes\AstPathPart;
use Vette\FusionParser\Ast\Nodes\AstPrototype;
use Vette\FusionParser\Ast\Nodes\AstUnset;

/**
 * Class JsonAstNodeVisitor
 *
 * @package Vette\FusionParser
 */
class JsonAstNodeVisitor extends AstNodeVisitor
{
    protected $jsonData = [];
    protected $jsonPartialData = [];

    public function visitFile(AstFile $file)
    {
        $this->jsonData = [
            'type' => 'File',
            'pathActions' => [],
            'includes' => [],
            'namespaces' => []
        ];

        /** @var AstInclude $pathAction */
        foreach ($file->getIncludes() as $include) {
            $include->accept($this);
            $this->jsonData['includes'][] = $this->jsonPartialData;
        }

        /** @var AstNamespace $pathAction */
        foreach ($file->getNamespaces() as $namespace) {
            $namespace->accept($this);
            $this->jsonData['namespaces'][] = $this->jsonPartialData;
        }

        /** @var AstPathAction $pathAction */
        foreach ($file->getPathActions() as $pathAction) {
            $pathAction->accept($this);
            $this->jsonData['pathActions'][] = $this->jsonPartialData;
        }

        return json_encode($this->jsonData);
    }

    public function visitNamespace(AstNamespace $namespace)
    {
        $this->jsonPartialData = [
            'type' => 'Namespace',
            'alias' => $namespace->getAlias()->getValue(),
            'namespace' => $namespace->getNamespace()->getValue()
        ];
    }

    public function visitInclude(AstInclude $include)
    {
        $this->jsonPartialData = [
            'type' => 'Include',
            'value' => $include->getValue()->getValue()
        ];
    }

    public function visitPathAction(AstPathAction $pathAction)
    {
        $pathAction->getPath()->accept($this);
        $path = $this->jsonPartialData;

        $pathAction->getAction()->accept($this);
        $action = $this->jsonPartialData;

        $this->jsonPartialData = [
            'type' => 'PathAction',
            'path' => $path,
            'action' => $action
        ];
    }

    public function visitPath(AstPath $path)
    {
        $parts = [];

        /** @var AstPathPart $part */
        foreach ($path->getParts() as $part) {
            $part->accept($this);
            $parts[] = $this->jsonPartialData;
        }

        $this->jsonPartialData = [
            'type' => 'Path',
            'parts' => $parts
        ];
    }

    public function visitPathPart(AstPathPart $partPart)
    {
        $this->jsonPartialData = [
            'type' => 'PathPart',
            'value' => $partPart->getPathPart()->getValue()
        ];
    }

    public function visitMetaPathPart(AstMetaPathPart $metaPathPart)
    {
        $this->jsonPartialData = [
            'type' => 'MetaPathPart',
            'value' => $metaPathPart->getPathPart()->getValue()
        ];
    }

    public function visitPrototype(AstPrototype $prototype)
    {
        $prototype->getObjectIdentifier()->accept($this);
        $objectIdentifier = $this->jsonPartialData;

        $this->jsonPartialData = [
            'type' => 'Prototype',
            'objectIdentifier' => $objectIdentifier
        ];
    }

    public function visitCopy(AstCopy $copy)
    {
        $copy->getPath()->accept($this);
        $path = $this->jsonPartialData;

        $block = null;
        if ($copy->getBlock()) {
            $copy->getBlock()->accept($this);
            $block = $this->jsonPartialData;
        }

        $this->jsonPartialData = [
            'type' => 'Copy',
            'path' => $path,
            'block' => $block
        ];
    }

    public function visitAssignment(AstAssignment $assignment)
    {
        $value = [];
        if ($assignment->getValue() !== null) {
            $assignment->getValue()->accept($this);
            $value = $this->jsonPartialData;
        }

        $this->jsonPartialData = [
            'type' => 'Assignment',
            'simpleValue' => $assignment->getSimpleValue() !== null ? $assignment->getSimpleValue()->getValue() : '',
            'value' => $value
        ];
    }

    public function visitUnset(AstUnset $unset)
    {
        $this->jsonPartialData = [
            'type' => 'Unset'
        ];
    }

    public function visitObject(AstObject $object)
    {
        $block = [];
        if ($object->getBlock()) {
            $object->getBlock()->accept($this);
            $block = $this->jsonPartialData;
        }

        $object->getIdentifier()->accept($this);
        $identifier = $this->jsonPartialData;

        $this->jsonPartialData = [
            'type' => 'Object',
            'identifier' => $identifier,
            'block' => $block
        ];
    }

    public function visitObjectIdentifier(AstObjectIdentifier $objectIdentifier)
    {
        $this->jsonPartialData = [
            'type' => 'ObjectIdentifier',
            'namespace' => $objectIdentifier->getNamespace() ? $objectIdentifier->getNamespace()->getValue() : null,
            'identifier' => $objectIdentifier->getIdentifier()->getValue()
        ];
    }

    public function visitBlock(AstBlock $block)
    {
        $pathActions = [];

        /** @var AstPathAction $pathAction */
        foreach ($block->getPathActions() as $pathAction) {
            $pathAction->accept($this);
            $pathActions[] = $this->jsonPartialData;
        }

        $this->jsonPartialData = [
            'type' => 'Block',
            'pathActions' => $pathActions
        ];
    }

    public function visitDsl(AstDsl $dsl)
    {
        $this->jsonPartialData = [
            'type' => 'Dsl',
            'code' => $dsl->getCode()->getValue()
        ];
    }

    public function visitEel(AstEel $eel)
    {
        $this->jsonPartialData = [
            'type' => 'Eel'
        ];
    }
}