<?php

namespace Softspring\CmsSectionsPlugin\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Softspring\CmsBundle\Config\CmsConfig;
use Softspring\CmsBundle\Helper\LocaleHelper;
use Softspring\CmsSectionsPlugin\Model\SectionInterface;
use Softspring\CmsSectionsPlugin\Render\SectionVersionRenderer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class SectionType extends AbstractType
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected CmsConfig $cmsConfig,
        protected SectionVersionRenderer $sectionVersionRenderer,
        protected RequestStack $requestStack,
        protected readonly LocaleHelper $localeHelper,
        protected RouterInterface $router,
    ) {
    }

    public function getBlockPrefix(): string
    {
        return 'section';
    }

    public function getParent(): string
    {
        return EntityType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => SectionInterface::class,
            'em' => $this->em,
            'required' => false,
            'query_builder' => fn (EntityRepository $entityRepository): QueryBuilder => $entityRepository->createQueryBuilder('b'),
            'choice_label' => function (SectionInterface $section): ?string {
                $label = $section->getName();

                if (!$section->getPublishedVersion()) {
                    $label .= ' (draft)';
                }

                return $label;
            },
            'choice_filter' => function (?SectionInterface $section = null): bool {
                $currentSection = $this->requestStack->getCurrentRequest()?->attributes->get('section');

                return !$currentSection || $currentSection->getId() !== $section?->getId();
            },
            'choice_attr' => function (?SectionInterface $section): array {
                $attr = [
                    'data-section-preview' => '',
                ];

                if ($section instanceof SectionInterface) {
                    $attr['data-section-url'] = $this->router->generate('sfs_cms_admin_sections_details', ['section' => $section->getId()]);

                    if ('draft' === $section->getStatus()) {
                        $attr['data-section-draft'] = '';
                    }

                    if ($section->getExtra('ttl', false)) {
                        $attr['data-section-ttl'] = $section->getExtra('ttl');
                    }

                    $attr['data-section-preview'] = '';
                    $attr['data-section-notes'] = nl2br($section->getNotes() ?? '');

                    foreach ($this->cmsConfig->getSites() as $site) {
                        foreach ($this->localeHelper->getEnabledLocales() as $locale) {
                            $attr['data-section-preview'] .= '<div data-lang="'.$locale.'" data-site="'.$site->getId().'" class="section-preview"'
                                .' data-preview-url="'.$this->router->generate('sfs_cms_admin_sections_render_preview', ['section' => $section->getId(), '_locale' => $locale, '_sfs_cms_site' => $site->getId()]).'"'
                                .'></div>';
                        }
                    }
                }

                return $attr;
            },
        ]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['section_preview'] = '';
        $view->vars['section_notes'] = '';

        /** @var ChoiceView $choice */
        foreach ($view->vars['choices'] as $choice) {
            if ($view->vars['value'] == $choice->value) {
                $view->vars['section_preview'] = $choice->attr['data-section-preview'];
                $view->vars['section_notes'] = $choice->attr['data-section-notes'];
            }
        }
    }
}
