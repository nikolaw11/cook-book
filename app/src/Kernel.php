<?php

/**
 * This file is part of the YourProject package.
 *
 * (c) Your Name <your.email@example.com>
 *
 * For license information, please view the LICENSE file that was distributed with this source code.
 */

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * Application Kernel class.
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
