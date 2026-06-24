<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Admin\ActionListener;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Softspring\CmsSectionsPlugin\Admin\ActionListener\Section\CleanupVersionsListener;
use Softspring\CmsSectionsPlugin\Admin\ActionListener\Section\CreateListener as SectionCreateListener;
use Softspring\CmsSectionsPlugin\Admin\ActionListener\Section\DeleteListener as SectionDeleteListener;
use Softspring\CmsSectionsPlugin\Admin\ActionListener\Section\ListListener as SectionListListener;
use Softspring\CmsSectionsPlugin\Admin\ActionListener\Section\PreviewListener as SectionPreviewListener;
use Softspring\CmsSectionsPlugin\Admin\ActionListener\Section\ReadListener;
use Softspring\CmsSectionsPlugin\Admin\ActionListener\Section\UnpublishListener;
use Softspring\CmsSectionsPlugin\Admin\ActionListener\Section\UpdateListener;
use Softspring\CmsSectionsPlugin\Admin\ActionListener\SectionVersion\BlameListener;
use Softspring\CmsSectionsPlugin\Admin\ActionListener\SectionVersion\ClearCompiledListener;
use Softspring\CmsSectionsPlugin\Admin\ActionListener\SectionVersion\CreateListener as VersionCreateListener;
use Softspring\CmsSectionsPlugin\Admin\ActionListener\SectionVersion\DeleteListener as VersionDeleteListener;
use Softspring\CmsSectionsPlugin\Admin\ActionListener\SectionVersion\InfoListener;
use Softspring\CmsSectionsPlugin\Admin\ActionListener\SectionVersion\ListListener as VersionListListener;
use Softspring\CmsSectionsPlugin\Admin\ActionListener\SectionVersion\LockListener;
use Softspring\CmsSectionsPlugin\Admin\ActionListener\SectionVersion\PreviewListener as VersionPreviewListener;
use Softspring\CmsSectionsPlugin\Admin\ActionListener\SectionVersion\PublishListener;
use Softspring\CmsSectionsPlugin\Admin\ActionListener\SectionVersion\RecompileListener;
use Softspring\CmsSectionsPlugin\SfsCmsSectionsEvents;

class SubscribedEventsTest extends TestCase
{
    /**
     * @param class-string $listenerClass
     * @param string[]     $expectedEvents
     */
    #[DataProvider('listenerEventsProvider')]
    public function testListenersDeclareExpectedEvents(string $listenerClass, array $expectedEvents): void
    {
        self::assertSame($expectedEvents, array_keys($listenerClass::getSubscribedEvents()));
    }

    public static function listenerEventsProvider(): iterable
    {
        yield 'section cleanup versions' => [CleanupVersionsListener::class, [
            SfsCmsSectionsEvents::ADMIN_SECTION_CLEANUP_VERSIONS_NOT_FOUND,
            SfsCmsSectionsEvents::ADMIN_SECTION_CLEANUP_VERSIONS_APPLY,
            SfsCmsSectionsEvents::ADMIN_SECTION_CLEANUP_VERSIONS_SUCCESS,
        ]];
        yield 'section create' => [SectionCreateListener::class, [
            SfsCmsSectionsEvents::ADMIN_SECTIONS_CREATE_SUCCESS,
            SfsCmsSectionsEvents::ADMIN_SECTIONS_CREATE_FAILURE,
        ]];
        yield 'section delete' => [SectionDeleteListener::class, [
            SfsCmsSectionsEvents::ADMIN_SECTIONS_DELETE_NOT_FOUND,
            SfsCmsSectionsEvents::ADMIN_SECTIONS_DELETE_SUCCESS,
            SfsCmsSectionsEvents::ADMIN_SECTIONS_DELETE_FAILURE,
        ]];
        yield 'section list' => [SectionListListener::class, []];
        yield 'section preview' => [SectionPreviewListener::class, [
            SfsCmsSectionsEvents::ADMIN_SECTIONS_PREVIEW_NOT_FOUND,
            SfsCmsSectionsEvents::ADMIN_SECTIONS_PREVIEW_VIEW,
        ]];
        yield 'section read' => [ReadListener::class, [
            SfsCmsSectionsEvents::ADMIN_SECTIONS_READ_NOT_FOUND,
            SfsCmsSectionsEvents::ADMIN_SECTIONS_READ_VIEW,
        ]];
        yield 'section unpublish' => [UnpublishListener::class, [
            SfsCmsSectionsEvents::ADMIN_SECTIONS_UNPUBLISH_NOT_FOUND,
            SfsCmsSectionsEvents::ADMIN_SECTIONS_UNPUBLISH_APPLY,
            SfsCmsSectionsEvents::ADMIN_SECTIONS_UNPUBLISH_SUCCESS,
            SfsCmsSectionsEvents::ADMIN_SECTIONS_UNPUBLISH_FAILURE,
        ]];
        yield 'section update' => [UpdateListener::class, [
            SfsCmsSectionsEvents::ADMIN_SECTIONS_UPDATE_NOT_FOUND,
            SfsCmsSectionsEvents::ADMIN_SECTIONS_UPDATE_SUCCESS,
            SfsCmsSectionsEvents::ADMIN_SECTIONS_UPDATE_FAILURE,
        ]];
        yield 'version blame' => [BlameListener::class, [
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CREATE_APPLY,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_PUBLISH_APPLY,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_LOCK_APPLY,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_RECOMPILE_APPLY,
            SfsCmsSectionsEvents::ADMIN_SECTIONS_DUPLICATE_APPLY,
        ]];
        yield 'version clear compiled' => [ClearCompiledListener::class, [
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_INITIALIZE,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_NOT_FOUND,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_APPLY,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_SUCCESS,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_FAILURE,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CLEAR_COMPILED_EXCEPTION,
        ]];
        yield 'version create' => [VersionCreateListener::class, [
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CREATE_INITIALIZE,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CREATE_ENTITY,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CREATE_FORM_PREPARE,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CREATE_SUCCESS,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CREATE_FAILURE,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CREATE_FORM_INVALID,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_CREATE_VIEW,
        ]];
        yield 'version delete' => [VersionDeleteListener::class, [
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_DELETE_INITIALIZE,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_DELETE_NOT_FOUND,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_DELETE_SUCCESS,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_DELETE_FAILURE,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_DELETE_VIEW,
        ]];
        yield 'version info' => [InfoListener::class, [
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_INFO_INITIALIZE,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_INFO_NOT_FOUND,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_INFO_FORM_PREPARE,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_INFO_SUCCESS,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_INFO_VIEW,
        ]];
        yield 'version list' => [VersionListListener::class, [
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_LIST_INITIALIZE,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_LIST_FILTER_FORM_PREPARE,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_LIST_VIEW,
        ]];
        yield 'version lock' => [LockListener::class, [
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_LOCK_INITIALIZE,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_LOCK_NOT_FOUND,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_LOCK_APPLY,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_LOCK_SUCCESS,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_LOCK_FAILURE,
        ]];
        yield 'version preview' => [VersionPreviewListener::class, [
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_PREVIEW_INITIALIZE,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_PREVIEW_NOT_FOUND,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_PREVIEW_FOUND,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_PREVIEW_VIEW,
        ]];
        yield 'version publish' => [PublishListener::class, [
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_PUBLISH_INITIALIZE,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_PUBLISH_NOT_FOUND,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_PUBLISH_APPLY,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_PUBLISH_SUCCESS,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_PUBLISH_FAILURE,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_PUBLISH_EXCEPTION,
        ]];
        yield 'version recompile' => [RecompileListener::class, [
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_RECOMPILE_INITIALIZE,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_RECOMPILE_NOT_FOUND,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_RECOMPILE_APPLY,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_RECOMPILE_SUCCESS,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_RECOMPILE_FAILURE,
            SfsCmsSectionsEvents::ADMIN_SECTION_VERSIONS_RECOMPILE_EXCEPTION,
        ]];
    }
}
