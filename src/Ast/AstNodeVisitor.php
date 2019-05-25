<?php

declare(strict_types=1);

namespace Vette\FusionParser\Ast;

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

abstract class AstNodeVisitor
{
    abstract public function visitFile(AstFile $file);
    abstract public function visitNamespace(AstNamespace $namespace);
    abstract public function visitInclude(AstInclude $include);
    abstract public function visitPathAction(AstPathAction $pathAction);
    abstract public function visitPath(AstPath $path);
    abstract public function visitPathPart(AstPathPart $partPart);
    abstract public function visitMetaPathPart(AstMetaPathPart $metaPathPart);
    abstract public function visitPrototype(AstPrototype $prototype);
    abstract public function visitCopy(AstCopy $copy);
    abstract public function visitAssignment(AstAssignment $assignment);
    abstract public function visitUnset(AstUnset $unset);
    abstract public function visitObject(AstObject $object);
    abstract public function visitObjectIdentifier(AstObjectIdentifier $objectIdentifier);
    abstract public function visitBlock(AstBlock $block);
    abstract public function visitDsl(AstDsl $dsl);
    abstract public function visitEel(AstEel $eel);
}
