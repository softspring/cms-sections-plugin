<?php

namespace Softspring\CmsSectionsPlugin\Admin\Menu;

use Softspring\CmsSectionsPlugin\Model\SectionInterface;

class SectionMenuProvider extends AbstractSectionMenuProvider
{
    public static function getPriority(): int
    {
        return 255; // should be the first one to be executed
    }

    public function getMenu(array $menu, ?string $currentSelection = null, ?object $entity = null): array
    {
        /** @var ?SectionInterface $section */
        $section = $entity;

        $menu[] = $this->getMenuItem('details', $currentSelection, $section, 'PERMISSION_SFS_CMS_ADMIN_SECTION_READ');

        // current version management
        $menu[] = $this->getMenuItem('content', $currentSelection, $section, 'PERMISSION_SFS_CMS_ADMIN_SECTION_VERSION_CREATE');
        $menu[] = $this->getMenuItem('preview', $currentSelection, $section, 'PERMISSION_SFS_CMS_ADMIN_SECTION_PREVIEW');

        // versions
        $menu[] = $this->getMenuItem('versions', $currentSelection, $section, 'PERMISSION_SFS_CMS_ADMIN_SECTION_VERSIONS');

        // section configuration
        $menu[] = $this->getMenuItem('update', $currentSelection, $section, 'PERMISSION_SFS_CMS_ADMIN_SECTION_UPDATE');
        // $menu[] = new MenuItem('permissions', $this->translator->trans("admin);
        $menu[] = $this->getMenuItem('delete', $currentSelection, $section, 'PERMISSION_SFS_CMS_ADMIN_SECTION_DELETE');

        return $menu;
    }
}
