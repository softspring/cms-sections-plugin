<?php

declare(strict_types=1);

namespace Softspring\CmsSectionsPlugin\Tests\Unit\Security\Voter;

use PHPUnit\Framework\TestCase;
use Softspring\CmsSectionsPlugin\Model\SectionVersionInterface;
use Softspring\CmsSectionsPlugin\Security\Voter\SectionVersionVoter;
use stdClass;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class SectionVersionVoterTest extends TestCase
{
    public function testItDeniesRecompileWhenDisabledAndOtherwiseAbstains(): void
    {
        $token = $this->createStub(TokenInterface::class);
        $version = $this->createStub(SectionVersionInterface::class);

        self::assertSame(VoterInterface::ACCESS_DENIED, (new SectionVersionVoter(false))->vote($token, $version, ['PERMISSION_SFS_CMS_ADMIN_SECTION_RECOMPILE_VERSION']));
        self::assertSame(VoterInterface::ACCESS_ABSTAIN, (new SectionVersionVoter(true))->vote($token, $version, ['PERMISSION_SFS_CMS_ADMIN_SECTION_RECOMPILE_VERSION']));
        self::assertSame(VoterInterface::ACCESS_ABSTAIN, (new SectionVersionVoter(false))->vote($token, new stdClass(), ['PERMISSION_SFS_CMS_ADMIN_SECTION_RECOMPILE_VERSION']));
    }
}
