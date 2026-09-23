<?php

namespace Softspring\CmsSectionsPlugin\Render;

use Softspring\CmsBundle\Render\Error\RenderErrorList;
use Softspring\CmsBundle\Render\Exception\RenderException;
use Softspring\CmsBundle\Render\Isolated\IsolatedRunner;
use Softspring\CmsBundle\Render\Module\ModuleRenderer;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class SectionVersionRenderer
{
    protected array $profilerDebugCollectorData = [];

    public function __construct(
        protected IsolatedRunner $isolatedRunner,
    ) {
    }

    /**
     * @throws RenderException
     */
    public function render(SectionVersionInterface $version, Request $request, ?RenderErrorList $renderErrorList = null): string
    {
        return $this->isolatedRunner->isolateRequestRender($request, function (Request $request, Environment $twig, ModuleRenderer $moduleRenderer) use ($version, $renderErrorList): string {
            // preload all medias
            $version->getMedias();
            // preload all routes
            $version->getRoutes();
            // preload all sections
            //            $version->getSections();

            $versionData = $version->getData() ?? [];

            if ($renderErrorList instanceof RenderErrorList) {
                $renderErrorList->resetLocation();
            }
            if ($renderErrorList instanceof RenderErrorList) {
                $renderErrorList->pushLocation('data');
            }

            $section = '';
            foreach ($versionData as $m => $moduleData) {
                if ($renderErrorList instanceof RenderErrorList) {
                    $renderErrorList->pushLocation($m);
                }
                $section .= $moduleRenderer->render($moduleData, $this->profilerDebugCollectorData, [], $renderErrorList);
                if ($renderErrorList instanceof RenderErrorList) {
                    $renderErrorList->popLocation();
                }
            }

            return $section;
        });
    }

    public function getDebugCollectorData(): array
    {
        return $this->profilerDebugCollectorData;
    }
}
