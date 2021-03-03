<?php

declare(strict_types=1);

namespace Vette\FusionParser;

use Vette\FusionParser\Ast\Nodes\AstAssignment;
use Vette\FusionParser\Ast\Nodes\AstBlock;
use Vette\FusionParser\Ast\Nodes\AstCopy;
use Vette\FusionParser\Ast\Nodes\AstDsl;
use Vette\FusionParser\Ast\Nodes\AstFile;
use Vette\FusionParser\Ast\Nodes\AstInclude;
use Vette\FusionParser\Ast\Nodes\AstMetaPathPart;
use Vette\FusionParser\Ast\Nodes\AstNamespace;
use Vette\FusionParser\Ast\Nodes\AstNode;
use Vette\FusionParser\Ast\Nodes\AstObject;
use Vette\FusionParser\Ast\Nodes\AstObjectIdentifier;
use Vette\FusionParser\Ast\Nodes\AstPath;
use Vette\FusionParser\Ast\Nodes\AstPathAction;
use Vette\FusionParser\Ast\Nodes\AstPathPart;
use Vette\FusionParser\Ast\Nodes\AstPrototype;
use Vette\FusionParser\Ast\Nodes\AstUnset;

/**
 * Fusion Parser
 *
 * @package Vette\FusionParser
 */
class Parser extends AbstractParser
{
    /** @var Source */
    protected $source;


    /**
     * Parse source
     *
     * @param Source $source
     *
     * @return AstNode
     *
     * @throws ParserException
     */
    public function parse(Source $source): AstNode
    {
        $lexer = new Lexer(true);

        $this->source = $source;
        $this->tokenStream = $lexer->tokenize($source);
        $this->lookahead = $this->tokenStream->current();

        return $this->parseFile();
    }

    /**
     * Parse file
     *
     * @return AstFile
     *
     * @throws ParserException
     */
    protected function parseFile(): AstFile
    {
        $file = new AstFile();
        while (! $this->accept(Token::EOF_TYPE)) {
            if ($this->accept(Token::INCLUDE_KEYWORD_TYPE)) {
                $file->addInclude($this->parseInclude());
                continue;
            }

            if ($this->accept(Token::NAMESPACE_KEYWORD_TYPE)) {
                $file->addNamespace($this->parseNamespace());
                continue;
            }

            $file->addPathAction($this->parsePathAction());
        }

        return $file;
    }

    /**
     * Parse include
     *
     * @throws ParserException
     */
    protected function parseInclude(): AstInclude
    {
        $this->expect(Token::INCLUDE_KEYWORD_TYPE);

        $includeValue = $this->expect(Token::INCLUDE_VALUE_TYPE);
        return new AstInclude($includeValue);
    }

    /**
     * Parse namespace
     *
     * @throws ParserException
     */
    protected function parseNamespace(): AstNamespace
    {
        $this->expect(Token::NAMESPACE_KEYWORD_TYPE);
        $alias = $this->expect(Token::OBJECT_IDENTIFIER_TYPE);
        $this->expect(Token::ASSIGNMENT_TYPE);
        $namespace = $this->expect(Token::OBJECT_IDENTIFIER_TYPE);

        return new AstNamespace($alias, $namespace);
    }

    /**
     * Parse path action
     *
     * @return AstPathAction
     *
     * @throws ParserException
     */
    protected function parsePathAction(): AstPathAction
    {
        $path = $this->parsePath();

        if ($this->accept(Token::ASSIGNMENT_TYPE)) {
            return new AstPathAction($path, $this->parseAssignment());
        }

        if ($this->accept(Token::LBRACE_TYPE)) {
            return new AstPathAction($path, $this->parseBlock());
        }

        if ($this->accept(Token::COPY_TYPE)) {
            return new AstPathAction($path, $this->parseCopy());
        }

        if ($this->accept(Token::UNSET_TYPE)) {
            $this->consume();
            return new AstPathAction($path, new AstUnset());
        }

        throw new ParserException($this->acceptedTokenTypes, $this->lookahead, $this->source);
    }

    /**
     * Parse path
     *
     * @return AstPath
     *
     * @throws ParserException
     */
    protected function parsePath(): AstPath
    {
        $path = new AstPath();
        $path->addPart($this->parsePathPart());

        while ($this->accept(Token::DOT_TYPE)) {
            $this->consume();
            $path->addPart($this->parsePathPart());
        }

        return $path;
    }

    /**
     * Parse path part
     *
     * @throws ParserException
     */
    protected function parsePathPart(): AstNode
    {
        if ($this->accept(Token::META_PROPERTY_KEYWORD_TYPE)) {
            return $this->parseMetaPath();
        }

        if ($this->accept(Token::OBJECT_PATH_PART_TYPE)) {
            return new AstPathPart($this->consume());
        }

        if ($this->accept(Token::PROTOTYPE_KEYWORD_TYPE)) {
            return $this->parsePrototype();
        }

        if ($this->accept(Token::STRING_VALUE_TYPE)) {
            return new AstPathPart($this->consume());
        }

        throw new ParserException($this->acceptedTokenTypes, $this->lookahead, $this->source);
    }

    /**
     * Parse prototype
     *
     * @return AstPrototype
     *
     * @throws ParserException
     */
    protected function parsePrototype(): AstPrototype
    {
        $this->expect(Token::PROTOTYPE_KEYWORD_TYPE);
        $this->expect(Token::LPAREN_TYPE);
        $objectIdentifier = $this->parseObjectIdentifier();
        $this->expect(Token::RPAREN_TYPE);

        return new AstPrototype($objectIdentifier);
    }

    /**
     * Parse meta path
     *
     * @throws ParserException
     */
    protected function parseMetaPath(): AstMetaPathPart
    {
        $this->expect(Token::META_PROPERTY_KEYWORD_TYPE);
        $pathPart = $this->expect(Token::OBJECT_PATH_PART_TYPE);

        return new AstMetaPathPart($pathPart);
    }

    /**
     * Parse object identifier
     *
     * @return AstObjectIdentifier
     *
     * @throws ParserException
     */
    protected function parseObjectIdentifier(): AstObjectIdentifier
    {
        $identifier1 = $this->expect(Token::OBJECT_IDENTIFIER_TYPE);

        if ($this->accept(Token::COLON_TYPE)) {
            $this->consume();
            $identifier2 = $this->expect(Token::OBJECT_IDENTIFIER_TYPE);

            return new AstObjectIdentifier($identifier2, $identifier1);
        }

        return new AstObjectIdentifier($identifier1);
    }

    /**
     * Parse copy
     *
     * @return AstCopy
     *
     * @throws ParserException
     */
    protected function parseCopy(): AstCopy
    {
        $this->expect(Token::COPY_TYPE);

        $path = $this->parsePath();

        $block = null;
        if ($this->accept(Token::LBRACE_TYPE)) {
            $block = $this->parseBlock();
        }

        return new AstCopy($path, $block);
    }

    /**
     * Parse assignment
     *
     * @return AstAssignment
     *
     * @throws ParserException
     */
    protected function parseAssignment(): AstAssignment
    {
        $this->expect(Token::ASSIGNMENT_TYPE);

        if ($this->accept(Token::OBJECT_IDENTIFIER_TYPE)) {
            return new AstAssignment(null, $this->parseObject());
        }

        if ($this->accept(Token::STRING_VALUE_TYPE)) {
            return new AstAssignment($this->consume());
        }

        if ($this->accept(Token::BOOLEAN_VALUE_TYPE)) {
            return new AstAssignment($this->consume());
        }

        if ($this->accept(Token::NUMBER_VALUE_TYPE)) {
            return new AstAssignment($this->consume());
        }

        if ($this->accept(Token::FLOAT_NUMBER_VALUE_TYPE)) {
            return new AstAssignment($this->consume());
        }

        if ($this->accept(Token::NULL_VALUE_TYPE)) {
            return new AstAssignment($this->consume());
        }

        if ($this->accept(Token::EEL_EXPRESSION_TYPE)) {
            return new AstAssignment($this->consume());
        }

        if ($this->accept(Token::DSL_START_TYPE)) {
            return new AstAssignment(null, $this->parseDsl());
        }

        throw new ParserException($this->acceptedTokenTypes, $this->lookahead, $this->source);
    }

    /**
     * Parse DSL
     *
     * @throws ParserException
     */
    protected function parseDsl(): AstDsl
    {
        $this->expect(Token::DSL_START_TYPE);
        $code = $this->expect(Token::DSL_CODE_TYPE);
        $this->expect(Token::DSL_END_TYPE);

        return new AstDsl($code);
    }

    /**
     * Parse object
     *
     * @return AstObject
     *
     * @throws ParserException
     */
    protected function parseObject(): AstObject
    {
        $block = null;
        $identifier = $this->parseObjectIdentifier();

        if ($this->accept(Token::LBRACE_TYPE)) {
            $block = $this->parseBlock();
        }

        return new AstObject($identifier, $block);
    }

    /**
     * Parse block
     *
     * @return AstBlock
     *
     * @throws ParserException
     */
    protected function parseBlock(): AstBlock
    {
        $this->expect(Token::LBRACE_TYPE);
        $block = new AstBlock();

        while (! $this->accept(Token::RBRACE_TYPE) && ! $this->accept(Token::EOF_TYPE)) {
            $block->addPathAction($this->parsePathAction());
        }

        $this->expect(Token::RBRACE_TYPE);
        return $block;
    }
}
