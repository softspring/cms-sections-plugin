<?php

namespace Softspring\CmsSectionsPlugin\Controller;

use Exception;
use Psr\Log\LoggerInterface;
use Softspring\CmsBundle\Config\CmsConfig;
use Softspring\CmsSectionsPlugin\Manager\SectionManagerInterface;
use Softspring\CmsSectionsPlugin\Manager\SectionVersionManagerInterface;
use Softspring\CmsSectionsPlugin\Model\SectionInterface;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class SectionController extends AbstractController
{
    public function __construct(
        protected SectionManagerInterface $sectionManager,
        protected SectionVersionManagerInterface $sectionVersionManager,
        protected Environment $twig,
        protected CmsConfig $cmsConfig,
        protected ?LoggerInterface $cmsLogger,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(Request $request, string $section): Response
    {
        return $this->renderById($section, $request);
    }

    /**
     * @throws Exception
     */
    public function renderById(string $section, Request $request, bool $adminPreview = false): Response
    {
        // allow CORS for this endpoint
        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, OPTIONS');

        try {
            /** @var ?SectionInterface $section */
            $section = $this->sectionManager->getRepository()->findOneById($section);

            if (!$section) {
                $this->cmsLogger && $this->cmsLogger->error(sprintf('CMS missing section %s', $section));

                throw $this->createNotFoundException(sprintf('Section with id "%s" not found.', $section));
            }

            /** @var ?SectionVersionInterface $publishedVersion */
            $publishedVersion = $section->getPublishedVersion();

            if ($adminPreview) {
                if (!$request->attributes->has('_sfs_cms_site') && $request->query->has('_sfs_cms_site')) {
                    $request->attributes->set('_sfs_cms_site', $this->cmsConfig->getSite($request->query->get('_sfs_cms_site')));
                }
                if (!$request->attributes->has('_locale') && $request->query->has('_locale')) {
                    $request->attributes->set('_locale', $request->query->get('_locale'));
                    $request->setLocale($request->attributes->get('_locale'));
                }

                if (!$publishedVersion) {
                    $publishedVersion = $section->getLastVersion();
                }
            }

            if (!$publishedVersion) {
                throw $this->createNotFoundException();
            }

            // if ('last_modified' === $this->contentCacheType) {
            //     $response->setEtag(md5($content->getId().$content->getLastModified()?->getTimestamp().$this->contentVersionCompiler->getCompileKeyFromRequest($publishedVersion, $request)));
            //     $response->setLastModified($content->getLastModified());
            //     // Set response as public. Otherwise it will be private by default.
            //     $response->setPublic();
            //     if ($response->isNotModified($request)) {
            //         return $response;
            //     }
            // }

            $sectionContent = $this->sectionVersionManager->getCompiledContent($publishedVersion, $request);

            // create response
            $response->setContent($sectionContent->getDataPart('content'));

            if ($sectionContent->hasErrors()) {
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            } elseif (/* 'ttl' === $this->contentCacheType && */ $section->getExtra('ttl') && !$adminPreview) {
                $response->setPublic();
                $response->setMaxAge((int) $section->getExtra('ttl'));
            }

            return $response;
        } catch (Exception $e) {
            $this->cmsLogger && $this->cmsLogger->error(sprintf('An error occurred while rendering section with id "%s": %s', $section->getId(), $e->getMessage()), ['exception' => $e]);
            throw $e;
        }
    }
}
