<?php

namespace CDNServer\Core\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CDNServer\Core\Helper\ApiHelper;
use CDNServer\Core\Factory\Exception\ResourceFactoryException;
use CDNServer\Core\Fetch\Exception\ResourceFetcherException;
use CDNServer\Core\Write\Exception\ResourceWriterException;

/**
 * Class ResourceController
 * @package CDNServer\Core\Controller\Api
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
class ResourceController extends Controller
{
    public function checkAction(Request $request)
    {
        try {
            $params = ApiHelper::checkRequiredParameters($request->query->all(), array('key', 'name'));
            $resource = $this->container->get('resource.factory.simple')->check($params['key'], $params);

            return new Response(json_encode(array(
                'name'  => $resource->getFilename(),
                'url'   => $this->container->get('resource.writer')->generateRemotePath($resource),
            )), 200);
        }
        catch (\Exception $e) {
            $code = ($e instanceof ResourceFactoryException && $e->getCode() == 2) ? 404 : 500;
            return new Response(json_encode(array('error' => $e->getMessage())), $code);
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function fetchAction(Request $request)
    {
        try {
            $params = ApiHelper::checkRequiredParameters($request->request->all(), array('key', 'url'));
            $resource = $this->container->get('resource.factory.url')->create($params['key'], $params);
            $remote = $this->container->get('resource.fetcher')->fetch($resource, $params['url']);

            return new Response(json_encode(array(
                'name'  => $resource->getFilename(),
                'url'   => $remote,
            )), 200);
        }
        catch (\Exception $e) {
            return new Response(json_encode(array('error' => $this->getDisplayedErrorMessage($e))), 500);
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function fetchAsyncAction(Request $request)
    {
        try {
            $params = ApiHelper::checkRequiredParameters($request->request->all(), array('key', 'url'));
            /** @var Resource $resource */
            $resource = $this->container->get('resource.factory.url')->create($params['key'], $params);
            $request->attributes->set('fetch_request', array(
                'resource'  => $resource,
                'url'       => $params['url'],
            ));

            return new Response(json_encode(array(
                'name'  => $resource->getFilename(),
                'url'   => $this->container->get('resource.writer')->generateRemotePath($resource),
            )), 200);
        }
        catch (\Exception $e) {
            return new Response(json_encode(array('error' => $e->getMessage())), 500);
        }
    }

    public function pushAction(Request $request)
    {
        try {
            $params = ApiHelper::checkRequiredParameters($request->request->all(), array('key', 'data'));
            $resource = $this->container->get('resource.factory.stream')->create($params['key'], $params);
            $remote = $this->container->get('resource.writer')->write($resource, $params['data']);

            return new Response(json_encode(array(
                'name'  => $resource->getFilename(),
                'url'   => $remote,
            )), 200);
        }
        catch (\Exception $e) {
            return new Response(json_encode(array('error' => $this->getDisplayedErrorMessage($e))), 500);
        }
    }

    public function pushAsyncAction(Request $request)
    {
        return array();
    }

    protected function  getDisplayedErrorMessage(\Exception $e)
    {
        if ($e instanceof ResourceFetcherException && $e->getCode() == 1)
            return "The requested file could not be retrieved.";
        else if ($e instanceof ResourceWriterException && $e->getCode() == 1)
            return "An unexpected error occurred and the file could not be written.";
        return $e->getMessage();
    }
}
