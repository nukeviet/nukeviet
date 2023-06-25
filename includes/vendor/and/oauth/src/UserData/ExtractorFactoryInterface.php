<?php

/*
 * This file is part of the php-oauth package <https://github.com/logical-and/php-oauth>.
 *
 * (c) Oryzone, developed by Luciano Mammino <lmammino@oryzone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OAuth\UserData;

use OAuth\Common\Service\ServiceInterface;

/**
 * Interface ExtractorFactoryInterface
 *
 * @package OAuth\UserData
 */
interface ExtractorFactoryInterface
{

    /**
     * Get the extractor for a given service
     *
     * @param  \OAuth\Common\Service\ServiceInterface $service
     *
     * @throws Exception\InvalidExtractorException    if the retrieved instance is not a valid
     *                                                        Extractor (not implement ExtractorInterface)
     * @throws Exception\UndefinedExtractorException  if can't find an extractor associated to the given service
     * @return Extractor\ExtractorInterface
     */
    public function get(ServiceInterface $service);
}
