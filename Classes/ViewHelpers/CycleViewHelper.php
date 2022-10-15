<?php

namespace Blueways\BwStaticTemplate\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Class CycleViewHelper
 *
 * @package Blueways\BwStaticTemplate\ViewHelpers
 */
class CycleViewHelper extends AbstractViewHelper
{

    use CompileWithRenderStatic;

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        if ($arguments['prev'] === null) {
            return $arguments['next'] == $arguments['length'] ? $arguments['offset'] : ($arguments['next'] + 1);
        }

        if ($arguments['next'] === null) {
            return $arguments['prev'] == $arguments['offset'] ? ($arguments['length'] - 1) : ($arguments['prev'] - 1);
        }

        return 0;
    }

    public function initializeArguments()
    {
        $this->registerArgument('length', 'integer', 'The length of the cycle', true);
        $this->registerArgument('next', 'integer', 'Current index for next slide', false);
        $this->registerArgument('prev', 'integer', 'Current index for previous slide', false);
        $this->registerArgument('offset', 'integer', 'Wether to start at 0 or 1', false, 0);
    }
}
