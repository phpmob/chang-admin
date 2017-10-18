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

namespace PhpMob\ChangMinBundle\Doctrine\ORM;

use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Ishmael Doss <nukboon@gmail.com>
 */
interface TaxonRepositoryInterface
{
    /**
     * @param null|string $rootCode
     *
     * @return TaxonInterface[]
     */
    public function findNodesTreeSorted($rootCode = null);
}
