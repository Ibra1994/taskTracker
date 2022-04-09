<?php

//must be App\Controller
namespace Api\Controller;

use App\Model;
use App\Storage\DataStorage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController 
{
    /**
     * @var DataStorage
     */
    private $storage;

    public function __construct(DataStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param Request $request
     * use plurar nouns as it's rest standart
     * @Route("/project/{id}", name="project", method="GET")
     */
    public function projectAction(Request $request)
    {
        try {
            //add project ID parameter to action method and use it from there
            $project = $this->storage->getProjectById($request->get('id'));

            return new Response($project->toJson());
        } catch (Model\NotFoundException $e) {
            return new Response('Not found', 404);
        } catch (\Throwable $e) {
            return new Response('Something went wrong', 500);
        }
    }

    /**
     * @param Request $request
     * use plurar nouns
     * @Route("/project/{id}/tasks", name="project-tasks", method="GET")
     */
    public function projectTaskPagerAction(Request $request)
    {
        //add project ID parameter to action method and use it from there
        //add limit & offset defaults, $request->get('limit', 10)
        $tasks = $this->storage->getTasksByProjectId(
            $request->get('id'),
            $request->get('limit'),
            $request->get('offset')
        );

        return new Response(json_encode($tasks));
    }

    /**
     * @param Request $request
     * use plurar nouns
     * must be POST Method
     * @Route("/project/{id}/tasks", name="project-create-task", method="PUT")
     */
    public function projectCreateTaskAction(Request $request)
    {
        //get ID from action parameter
		$project = $this->storage->getProjectById($request->get('id'));
        //this part will not work as method will throw exception instead of null, so you have to handle exception
		if (!$project) {
			return new JsonResponse(['error' => 'Not found']);
		}

        //validation needed to control fields and values that can be inserted before sending data to DataStorage
        //use $request->toArray() instead $_REQUEST
		return new JsonResponse(
			$this->storage->createTask($_REQUEST, $project->getId())
		);
    }
}
