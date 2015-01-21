<?php

namespace CDNServer\Core\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ResourceController
 * @package CDNServer\Core\Controller\Admin
 * @author Pierre LECERF <plecerf@keley-live.com>
 */
class ResourceController extends Controller
{
    public function listAction($project_id)
    {
        if (!($project = $this->getDoctrine()->getRepository('CDNServerCore:Project')->findOneById($project_id)))
            throw $this->createNotFoundException('Project not found.');
        $resources = $this->getDoctrine()->getRepository('CDNServerCore:Resource')->findByProjectId($project_id);

        return array(
            'project'   => $project,
            'entities'  => $resources,
        );
    }

    public function showAction($id)
    {
        if (!($resource = $this->getDoctrine()->getRepository('CDNServerCore:Resource')->findOneById($id)))
            throw $this->createNotFoundException('Resource not found.');

        $remote = $this->container->get('resource.writer')->generateRemotePath($resource);

        return array(
            'entity'        => $resource,
            'remote_url'    => $remote,
        );
    }
}
