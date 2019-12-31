<?php

/**
 * @see       https://github.com/laminas/laminas-view for the canonical source repository
 * @copyright https://github.com/laminas/laminas-view/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-view/blob/master/LICENSE.md New BSD License
 */

namespace Zend\View\Resolver;

use Zend\View\Renderer\RendererInterface as Renderer;

final class PrefixPathStackResolver implements ResolverInterface
{
    /**
     * Array containing prefix as key and "template path stack array" as value
     *
     * @var string[]|string[][]|ResolverInterface[]
     */
    private $prefixes = array();

    /**
     * Constructor
     *
     * @param string[]|string[][]|ResolverInterface[] $prefixes Set of path prefixes to be matched (array keys), with
     *                                                          either a path or an array of paths to use for matching
     *                                                          as in the {@see \Zend\View\Resolver\TemplatePathStack},
     *                                                          or a {@see \Zend\View\Resolver\ResolverInterface}
     *                                                          to use for view path starting with that prefix
     */
    public function __construct(array $prefixes = array())
    {
        $this->prefixes = $prefixes;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve($name, Renderer $renderer = null)
    {
        foreach ($this->prefixes as $prefix => & $resolver) {
            if (strpos($name, $prefix) !== 0) {
                continue;
            }

            if (! $resolver instanceof ResolverInterface) {
                $resolver = new TemplatePathStack(array('script_paths' => (array) $resolver));
            }

            if ($result = $resolver->resolve(substr($name, strlen($prefix)), $renderer)) {
                return $result;
            }
        }

        return;
    }
}
