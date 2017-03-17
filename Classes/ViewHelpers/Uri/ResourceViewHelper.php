<?php
namespace Mindscreen\Flow\CacheBreak\ViewHelpers\Uri;

/*                                                                        *
 * This script belongs to the Flow framework.                             *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the MIT license.                                          *
 *                                                                        */

use Mindscreen\Flow\CacheBreak\Service\CacheBreakService;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\ResourceManagement\PersistentResource;

/**
 * A view helper for creating URIs to resources (including a cache breaking GET parameter).
 *
 * = Examples =
 *
 * <code title="Defaults">
 * <link href="{cb:uri.resource(path: 'CSS/Stylesheet.css')}" rel="stylesheet" />
 * </code>
 * <output>
 * <link href="http://yourdomain.tld/_Resources/Static/YourPackage/CSS/Stylesheet.css?cachebreakingstring" rel="stylesheet" />
 * (depending on current package)
 * </output>
 *
 * <code title="Other package resource">
 * {cb:uri.resource(path: 'gfx/SomeImage.png', package: 'DifferentPackage')}
 * </code>
 * <output>
 * http://yourdomain.tld/_Resources/Static/DifferentPackage/gfx/SomeImage.png?cachebreakingstring
 * (depending on domain)
 * </output>
 *
 * <code title="Resource URI">
 * {cb:uri.resource(path: 'resource://DifferentPackage/Public/gfx/SomeImage.png')}
 * </code>
 * <output>
 * http://yourdomain.tld/_Resources/Static/DifferentPackage/gfx/SomeImage.png?cachebreakingstring
 * (depending on domain)
 * </output>
 *
 * <code title="Resource object">
 * <img src="{cb:uri.resource(resource: myImage.resource)}" />
 * </code>
 * <output>
 * <img src="http://yourdomain.tld/_Resources/Persistent/69e73da3ce0ad08c717b7b9f1c759182d6650944.jpg?cachebreakingstring" />
 * (depending on your resource object)
 * </output>
 *
 * @api
 */
class ResourceViewHelper extends \Neos\FluidAdaptor\ViewHelpers\Uri\ResourceViewHelper
{
    /**
     * @Flow\Inject
     * @var CacheBreakService
     */
    protected $cacheBreakingService;

    /**
     * Render the URI to the resource. The filename is used from child content.
     *
     * @param string $path The location of the resource, can be either a path relative to the Public resource directory of the package or a resource://... URI
     * @param string $package Target package key. If not set, the current package key will be used
     * @param PersistentResource $resource If specified, this resource object is used instead of the path and package information
     * @param boolean $localize Whether resource localization should be attempted or not
     * @return string The absolute URI to the resource
     * @throws InvalidVariableException
     * @api
     */
    public function render($path = null, $package = null, PersistentResource $resource = null, $localize = true)
    {
        $uri = parent::render($path, $package, $resource, $localize);
        $uri .= '?' . $this->cacheBreakingService->get();
        return $uri;
    }
}