<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Twig;

use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Softspring\CmsBundle\Config\CmsConfig;
use Softspring\CmsBundle\EventListener\SiteResolverListener;
use Softspring\CmsBundle\Manager\SiteManagerInterface;
use Softspring\CmsBundle\Model\Site;
use Softspring\CmsBundle\Render\Module\ModuleRenderer;
use Softspring\CmsBundle\Routing\SiteResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\UriSigner;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Fragment\FragmentUriGenerator;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\TwigFunction;

final class SectionSiteContextTest extends TestCase
{
    #[DataProvider('esiContexts')]
    public function testEsiSectionsRenderInTheSignedSiteContext(string $siteId, string $locale, bool $siteObject, bool $siteAttribute, bool $preview = false): void
    {
        [$config, $sites] = $this->createSiteConfig();
        $parent = $this->createRequest($sites[$siteId], $locale, $siteObject, $siteAttribute, $preview);
        $stack = new RequestStack();
        $stack->push($parent);
        $signer = new UriSigner('section-context-test');
        $generator = new FragmentUriGenerator('/_fragment', $signer, $stack);
        $capturedUrl = null;
        $twig = $this->createTwig($parent, $generator, $capturedUrl);
        $html = $twig->render('section', ['section' => ['id' => 'section-id'], 'mode' => 'esi']);

        self::assertSame(1, preg_match('/src="([^"]+)"/', $html, $match));
        $uri = $parent->getSchemeAndHttpHost().html_entity_decode($match[1], ENT_QUOTES);
        self::assertTrue($signer->check($uri));
        parse_str(parse_url($uri, PHP_URL_QUERY), $query);
        parse_str($query['_path'], $attributes);
        self::assertSame($siteId, $attributes['_site'] ?? null);
        self::assertSame($locale, $attributes['_locale']);
        self::assertSame($preview, isset($attributes['adminPreview']));

        $fragment = Request::create($uri);
        $fragment->attributes->add($attributes);
        $stack->push($fragment);
        $listener = new SiteResolverListener(new SiteResolver($config, ['throw_not_found' => false]), $stack);
        $listener->onKernelRequest(new RequestEvent($this->createStub(HttpKernelInterface::class), $fragment, HttpKernelInterface::SUB_REQUEST));
        $renderer = new ModuleRenderer($config, $stack, $twig, null);
        $collector = [];
        $module = ['_module' => 'fixture', 'site_filter' => [$siteId => true], 'locale_filter' => [$locale => true]];
        self::assertSame('<h2>Section content</h2>', $renderer->render($module, $collector));

        $module['site_filter'][$siteId] = false;
        self::assertStringContainsString('SITE_HIDDEN_MODULE', $renderer->render($module, $collector));
        $module['site_filter'][$siteId] = true;
        $module['locale_filter'][$locale] = false;
        self::assertStringContainsString('LOCALE_HIDDEN_MODULE', $renderer->render($module, $collector));

        $attributes['_site'] = 'other-site';
        $query['_path'] = http_build_query($attributes);
        self::assertFalse($signer->check($parent->getSchemeAndHttpHost().'/_fragment?'.http_build_query($query)));
    }

    public static function esiContexts(): array
    {
        return [
            'Europe' => ['default', 'en', true, true],
            'Switzerland' => ['chf', 'de', true, true],
            'United Kingdom' => ['gbp', 'en', true, true],
            'United States' => ['usd', 'en', true, true],
            'Australia' => ['aud', 'en', true, true],
            'isolated compilation' => ['default', 'fr', true, false],
            'site attribute fallback' => ['chf', 'it', false, true],
            'admin preview' => ['chf', 'de', true, false, true],
        ];
    }

    #[DataProvider('routeModes')]
    public function testOtherRenderModesKeepSiteLocaleAndPreview(string $mode, bool $preview, bool $siteObject): void
    {
        [, $sites] = $this->createSiteConfig();
        $parent = $this->createRequest($sites['chf'], 'fr', $siteObject, !$siteObject, $preview);
        $stack = new RequestStack();
        $stack->push($parent);
        $capturedUrl = null;
        $twig = $this->createTwig($parent, new FragmentUriGenerator('/_fragment', new UriSigner('section-context-test'), $stack), $capturedUrl);
        $html = $twig->render('section', ['section' => ['id' => 'section-id'], 'mode' => $mode]);
        if ('ajax' === $mode) {
            self::assertSame(1, preg_match('/data-href="([^"]+)"/', $html, $match));
            $capturedUrl = html_entity_decode($match[1], ENT_QUOTES);
        }
        self::assertNotNull($capturedUrl);
        parse_str(parse_url($capturedUrl, PHP_URL_QUERY), $parameters);
        self::assertSame('chf', $parameters['_site'] ?? null);
        self::assertSame('fr', $parameters['_locale']);
        self::assertSame($preview, isset($parameters['admin_preview']));
    }

    public static function routeModes(): array
    {
        $cases = [];
        foreach (['ajax', 'embedded'] as $mode) {
            foreach ([false, true] as $preview) {
                foreach ([false, true] as $siteObject) {
                    $cases[] = [$mode, $preview, $siteObject];
                }
            }
        }

        return $cases;
    }

    private function createSiteConfig(): array
    {
        $sites = $siteConfigs = [];
        foreach (['default', 'chf', 'gbp', 'usd', 'aud'] as $id) {
            $site = new Site();
            $site->setId($id);
            $siteConfigs[$id] = ['enabled' => true, 'hosts' => [['domain' => $id.'.example.test', 'canonical' => true, 'locale' => false]], 'paths' => [['path' => '/en', 'locale' => 'en']]];
            $site->setConfig($siteConfigs[$id]);
            $sites[$id] = $site;
        }
        $repository = $this->createStub(EntityRepository::class);
        $repository->method('findAll')->willReturn(array_values($sites));
        $manager = $this->createStub(SiteManagerInterface::class);
        $manager->method('getRepository')->willReturn($repository);
        $modules = ['fixture' => ['enabled' => true, 'revision_migration_scripts' => [], 'revision' => 1, 'module_type' => 'fixture', 'render_template' => 'content']];

        return [new CmsConfig([], $modules, [], [], [], $siteConfigs, $manager), $sites];
    }

    private function createRequest(Site $site, string $locale, bool $siteObject, bool $siteAttribute, bool $preview): Request
    {
        $request = Request::create('https://'.$site->getId().'.example.test/'.$locale.'/page');
        $request->setLocale($locale);
        if ($siteObject) {
            $request->attributes->set('_sfs_cms_site', $site);
        }
        if ($siteAttribute) {
            $request->attributes->set('_site', $site->getId());
        }
        $request->attributes->set('_cms_preview', $preview);

        return $request;
    }

    private function createTwig(Request $request, FragmentUriGenerator $generator, ?string &$capturedUrl): Environment
    {
        $twig = new Environment(new ArrayLoader([
            'section' => file_get_contents(dirname(__DIR__, 3).'/cms/modules/section/render.html.twig'),
            '@SfsCms/macros/modules_render.html.twig' => '{% macro id(value) %}{% endmacro %}{% macro class(value) %}{% endmacro %}',
            'content' => '<h2>Section content</h2>',
        ]), ['strict_variables' => true]);
        $twig->addGlobal('app', ['request' => $request]);
        $twig->addFunction(new TwigFunction('controller', static fn (string $controller, array $attributes): ControllerReference => new ControllerReference($controller, $attributes)));
        $twig->addFunction(new TwigFunction('fragment_uri', static fn (ControllerReference $controller): string => $generator->generate($controller)));
        $twig->addFunction(new TwigFunction('url', static fn (string $route, array $parameters): string => '/__/s/section-id?'.http_build_query($parameters)));
        $twig->addFunction(new TwigFunction('render', static function (string $url) use (&$capturedUrl): string {
            $capturedUrl = $url;

            return 'rendered';
        }));

        return $twig;
    }
}
