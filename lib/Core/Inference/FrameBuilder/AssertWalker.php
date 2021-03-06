<?php

namespace Phpactor\WorseReflection\Core\Inference\FrameBuilder;

use Phpactor\WorseReflection\Core\Inference\FrameWalker;
use Microsoft\PhpParser\Node;
use Phpactor\WorseReflection\Core\Inference\Frame;
use Phpactor\WorseReflection\Core\Inference\FrameBuilder;
use Microsoft\PhpParser\Node\Statement\ExpressionStatement;
use Microsoft\PhpParser\Node\Expression\CallExpression;
use Microsoft\PhpParser\Node\Expression\ArgumentExpression;
use Phpactor\WorseReflection\Core\Inference\ExpressionEvaluator;
use Phpactor\WorseReflection\Core\Inference\SymbolFactory;
use Microsoft\PhpParser\Node\Expression\BinaryExpression;
use Microsoft\PhpParser\Node\Expression\Variable;

class AssertWalker extends AbstractInstanceOfWalker implements FrameWalker
{
    public function canWalk(Node $node): bool
    {
        if (false === $node instanceof CallExpression) {
            return false;
        }

        $name = $node->callableExpression->getText();

        return strtolower($name) == 'assert';
    }

    public function walk(FrameBuilder $builder, Frame $frame, Node $node): Frame
    {
        assert($node instanceof CallExpression);
        $list = $node->argumentExpressionList->getElements();
        foreach ($list as $expression) {
            if (!$expression instanceof ArgumentExpression) {
                continue;
            }

            $expressionIsTrue = $this->evaluator->evaluate($expression->expression);

            if (false === $expressionIsTrue) {
                continue;
            }

            $variables = $this->collectVariables($expression);

            foreach ($variables as $variable) {
                $frame->locals()->add($variable);
            }
        }

        return $frame;
    }
}
