<?php

namespace CDNServer\Core\Controller\Api;

use CDNServer\Core\Factory\Exception\ResourceFactoryException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CDNServer\Core\Helper\ApiHelper;

/**
 * Class ProjectController
 * @package CDNServer\Core\Controller\Api
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
class ProjectController extends Controller
{
    public function directoryAction(Request $request)
    {
        try {
            $params = ApiHelper::checkRequiredParameters($request->query->all(), array('key'));
            $project = $this->container->get('resource.factory.simple')->getProject($params['key']);

            return new Response(json_encode(array(
                'name'  => $project->getName(),
                'url'   => $this->container->get('resource.writer')->generateRemotePathFromString($project->getFullPath()),
            )), 200);
        }
        catch (\Exception $e) {
            return new Response(json_encode(array('error' => $e->getMessage())), 500);
        }
    }
}
