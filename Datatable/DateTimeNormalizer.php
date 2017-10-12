<?php

/**
 * This file is part of the TommyGNRDatatablesBundle package.
 *
 * (c) Tom Corrigan <https://github.com/tommygnr/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TommyGNR\DatatablesBundle\Datatable;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use \DateTime;

/**
 * Class DateTimeNormalizer
 */
class DateTimeNormalizer implements NormalizerInterface
{
    public function normalize($object, $format = null, array $context = [])
    {
        return $object->getTimestamp();
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof DateTime) return true;
        return false;
    }
}
