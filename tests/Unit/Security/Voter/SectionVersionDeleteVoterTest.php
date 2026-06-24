<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Security\Voter;

use PHPUnit\Framework\TestCase;
use Softspring\CmsBundle\Config\CmsConfig;
use Softspring\CmsSectionsPlugin\Manager\SectionManagerInterface;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;
use Softspring\CmsSectionsPlugin\Security\Voter\SectionVersionDeleteVoter;
use stdClass;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class SectionVersionDeleteVoterTest extends TestCase
{
    public function testItGrantsDeleteOnlyWhenVersionCanBeCleanedUp(): void
    {
        $token = $this->createStub(TokenInterface::class);
        $version = $this->createStub(SectionVersionInterface::class);
        $version->method('deleteOnCleanup')->willReturn(true);
        $keptVersion = $this->createStub(SectionVersionInterface::class);
        $keptVersion->method('deleteOnCleanup')->willReturn(false);
        $voter = new SectionVersionDeleteVoter($this->createStub(CmsConfig::class), $this->createStub(SectionManagerInterface::class));

        self::assertSame(VoterInterface::ACCESS_GRANTED, $voter->vote($token, $version, ['PERMISSION_SFS_CMS_ADMIN_SECTION_VERSION_DELETE']));
        self::assertSame(VoterInterface::ACCESS_DENIED, $voter->vote($token, $keptVersion, ['PERMISSION_SFS_CMS_ADMIN_SECTION_VERSION_DELETE']));
        self::assertSame(VoterInterface::ACCESS_ABSTAIN, $voter->vote($token, $version, ['OTHER_PERMISSION']));
        self::assertSame(VoterInterface::ACCESS_ABSTAIN, $voter->vote($token, new stdClass(), ['PERMISSION_SFS_CMS_ADMIN_SECTION_VERSION_DELETE']));
    }
}
