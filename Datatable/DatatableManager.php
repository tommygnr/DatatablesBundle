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

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use TommyGNR\DatatablesBundle\Datatable\View\AbstractDatatableView;

/**
 * Class DatatableManager
 */
class DatatableManager
{
    /**
     * The doctrine service.
     *
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * The request service.
     *
     * @var Request
     */
    private $request;

    /**
     * The serializer service.
     *
     * @var Serializer
     */
    private $serializer;

    /**
     * @param RegistryInterface $doctrine   The doctrine service
     * @param Request           $request    The request service
     * @param Serializer        $serializer The serializer service
     */
    public function __construct(RegistryInterface $doctrine, Request $request, Serializer $serializer)
    {
        $this->doctrine = $doctrine;
        $this->request = $request;
        $this->serializer = $serializer;
    }

     /**
     * Get datatable.
     *
     * @param string $entity
     *
     * @return DatatableData A DatatableData instance
     */
    public function getData(AbstractDatatableView $datatable)
    {
        /**
         * Get all GET params.
         *
         * @var \Symfony\Component\HttpFoundation\ParameterBag $parameterBag
         */
        $parameterBag = $this->request->query;
        $params = $parameterBag->all();

        /**
         * @var \Doctrine\ORM\EntityManager $em
         */
        $em = $this->doctrine->getManager();

        $datatable->buildDatatableView();

        return new DatatableData(
            $params,
            $datatable,
            $this->doctrine,
            $this->serializer
        );
    }
}
