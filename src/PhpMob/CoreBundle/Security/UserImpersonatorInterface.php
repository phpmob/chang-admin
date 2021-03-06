<?php

/*
 * This file is part of the PhpMob package.
 *
 * (c) Ishmael Doss <nukboon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PhpMob\CoreBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Ishmael Doss <nukboon@gmail.com>
 */
interface UserImpersonatorInterface
{
    /**
     * @param UserInterface $user
     */
    public function impersonate(UserInterface $user): void;
}
