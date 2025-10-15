<?php

namespace Softspring\CmsSectionsPlugin\Twig\Extension;

use Exception;
use Softspring\CmsSectionsPlugin\Manager\SectionManagerInterface;
use Softspring\CmsSectionsPlugin\Model\SectionInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RenderExtension extends AbstractExtension
{
    public function __construct(
        protected SectionManagerInterface $sectionManager,
        protected Environment $twig,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('sfs_cms_section_find', [$this, 'find'], ['is_safe' => ['html']]),
            new TwigFunction('sfs_cms_section_find_by_*', [$this, 'findBy'], ['is_safe' => ['html']]),
            new TwigFunction('sfs_cms_section', [$this, 'renderEmbed'], ['is_safe' => ['html']]),
            new TwigFunction('sfs_cms_section_embed', [$this, 'renderEmbed'], ['is_safe' => ['html']]),
            new TwigFunction('sfs_cms_section_embed_by_*', [$this, 'renderEmbedBy'], ['is_safe' => ['html']]),
            new TwigFunction('sfs_cms_section_esi', [$this, 'renderEsi'], ['is_safe' => ['html']]),
            new TwigFunction('sfs_cms_section_esi_by_*', [$this, 'renderEsiBy'], ['is_safe' => ['html']]),
            new TwigFunction('sfs_cms_section_ajax', [$this, 'renderAjax'], ['is_safe' => ['html']]),
            new TwigFunction('sfs_cms_section_ajax_by_*', [$this, 'renderAjaxBy'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @throws Exception
     */
    public function find($criteria, array $orderBy = []): ?SectionInterface
    {
        if (is_string($criteria)) {
            $criteria = ['id' => $criteria];
        }

        if (!is_array($criteria)) {
            throw new Exception('Invalid criteria');
        }

        return $this->sectionManager->getRepository()->findOneBy($criteria, $orderBy);
    }

    /**
     * @throws Exception
     */
    public function findBy($field, $value, array $criteria = [], array $orderBy = []): ?SectionInterface
    {
        $criteria[$field] = $value;

        return $this->find($criteria, $orderBy);
    }

    /**
     * @throws Exception
     */
    public function renderEmbed(SectionInterface|string|null $sectionOrSectionId): string
    {
        if (!($section = $this->getSection($sectionOrSectionId))) {
            return "<!-- section {$sectionOrSectionId} not found -->";
        }

        return $this->twig->render('@module/section/render.html.twig', [
            'section' => $section,
            'mode' => 'embed',
        ]);
    }

    /**
     * @throws Exception
     */
    public function renderEmbedBy($field, $value, array $criteria = [], array $orderBy = []): string
    {
        return $this->renderEmbed($this->findBy($field, $value, $criteria, $orderBy));
    }

    /**
     * @throws Exception
     */
    public function renderEsi(SectionInterface|string|null $sectionOrSectionId): string
    {
        if (!($section = $this->getSection($sectionOrSectionId))) {
            return "<!-- section {$sectionOrSectionId} not found -->";
        }

        return $this->twig->render('@module/section/render.html.twig', [
            'section' => $section,
            'mode' => 'esi',
        ]);
    }

    /**
     * @throws Exception
     */
    public function renderEsiBy($field, $value, array $criteria = [], array $orderBy = []): string
    {
        return $this->renderEsi($this->findBy($field, $value, $criteria, $orderBy));
    }

    /**
     * @throws Exception
     */
    public function renderAjax(SectionInterface|string|null $sectionOrSectionId): string
    {
        if (!($section = $this->getSection($sectionOrSectionId))) {
            return "<!-- section {$sectionOrSectionId} not found -->";
        }

        return $this->twig->render('@module/section/render.html.twig', [
            'section' => $section,
            'mode' => 'ajax',
        ]);
    }

    /**
     * @throws Exception
     */
    public function renderAjaxBy($field, $value, array $criteria = [], array $orderBy = []): string
    {
        return $this->renderAjax($this->findBy($field, $value, $criteria, $orderBy));
    }

    /**
     * @throws Exception
     */
    protected function getSection(SectionInterface|string|null $sectionOrSectionId): ?SectionInterface
    {
        if (is_string($sectionOrSectionId)) {
            return $this->find($sectionOrSectionId);
        }

        return $sectionOrSectionId;
    }
}
