<?php

namespace CDNServer\CoreBundle\Controller\Api;

use CDNServer\CoreBundle\Entity\Resource;
use CDNServer\CoreBundle\Helper\ApiHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ResourceController
 * @package CDNServer\CoreBundle\Controller\Api
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
class ResourceController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function fetchAction(Request $request)
    {
        $params = ApiHelper::checkRequiredParameters($request->request->all(), array('key', 'url'));
        if (isset($params['_error']))
            return new Response(json_encode(array('error' => $params['_error'])), 500);
        $d = $this->getDoctrine();

        if (!($project = $d->getRepository('CDNServerCoreBundle:Project')->findOneBy(array('ukey' => $params['key']))))
            return new Response(json_encode(array('error' => "Supplied key is invalid.")), 500);

        $name = isset($params['name']) ? $params['name'] : $this->generateNameFromURL($params['url']);
        $ext = pathinfo(parse_url($params['url'], PHP_URL_PATH), PATHINFO_EXTENSION);
        $resource = $d->getRepository('CDNServerCoreBundle:Resource')->findOneByProjectAndName($project->getId(), $name);
        if ($resource && (!isset($params['update']) || !$params['update']))
            return new Response(json_encode(array('error' => "The resource ".$name." already exists, either use another name or set the 'update' option to true.")), 500);
        else if (!$resource)
            $resource = new Resource($name, $name.'.'.$ext, $project);

        $path = $project->getFullPath().'/'.$resource->getFilename();

        if (($data = file_get_contents($params['url'])) === false)
            return new Response(json_encode(array('error' => "The file ".$params['url']." could not be retrieved.")), 500);

        if (file_put_contents($this->container->getParameter('resource_root_dir').$path, $data) === false)
            return new Response(json_encode(array('error' => "An unexpected error occurred and the file could not be written.")), 500);

        $d->getEntityManager()->persist($resource);
        $d->getEntityManager()->flush();

        return new Response(json_encode(array(
            'name'  => $resource->getFilename(),
            'url'   => $this->container->getParameter('resource_ext_path').$path,
        )), 200);
    }

    public function fetchAsyncAction(Request $request)
    {
        $params = ApiHelper::checkRequiredParameters($request->request->all(), array('key', 'url'));
        if (isset($params['_error']))
            return new Response(json_encode(array('error' => $params['_error'])), 500);
        $d = $this->getDoctrine();

        if (!($project = $d->getRepository('CDNServerCoreBundle:Project')->findOneBy(array('ukey' => $params['key']))))
            return new Response(json_encode(array('error' => "Supplied key is invalid.")), 500);

        $name = isset($params['name']) ? $params['name'] : $this->generateNameFromURL($params['url']);
        $ext = pathinfo(parse_url($params['url'], PHP_URL_PATH), PATHINFO_EXTENSION);
        $resource = $d->getRepository('CDNServerCoreBundle:Resource')->findOneByProjectAndName($project->getId(), $name);
        if ($resource && (!isset($params['update']) || !$params['update']))
            return new Response(json_encode(array('error' => "The resource ".$name." already exists, either use another name or set the 'update' option to true.")), 500);
        else if (!$resource)
            $resource = new Resource($name, $name.'.'.$ext, $project);

        $path = $project->getFullPath().'/'.$resource->getFilename();

        $d->getEntityManager()->persist($resource);
        $d->getEntityManager()->flush();

        $request->attributes->set('fetch_request', array(
            'resource'  => $resource,
            'url'       => $params['url'],
            'path'      => $path,
        ));

        return new Response(json_encode(array(
            'name'  => $resource->getFilename(),
            'url'   => $this->container->getParameter('resource_ext_path').$path,
        )), 200);
    }

    protected function  generateNameFromURL($url)
    {
        return sha1($url);
    }
}