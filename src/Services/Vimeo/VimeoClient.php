<?php

namespace Eduka\Nova\Services\Vimeo;

use Eduka\Cube\Models\Course;
use Vimeo\Vimeo;

class VimeoClient
{
    private const VIMEO_URL_CREATE_NEW_PROJECT_URL = '/me/projects';

    private const VIMEO_URL_GET_PROJECT_URL = '/me/projects/%s';

    private const HTTP_POST = 'POST';

    private const HTTP_GET = 'GET';

    private const HTTP_HEADER = [
        'Content-Type' => 'application/json',
    ];

    private const HTTP_JSON = true;

    private Vimeo $client;

    public function __construct()
    {
        $this->client = new Vimeo(env('VIMEO_CLIENT_ID'), env('VIMEO_CLIENT_SECRET'), env('VIMEO_PERSONAL_ACCESS_TOKEN'));
    }

    public function upload(string $storagePath, array $metadata = [])
    {
        return $this->client->upload($storagePath, $metadata);
    }

    public function ensureProjectExists(Course $course)
    {
        if ($course->vimeo_project_id && $this->checkIfProjectExists($course->vimeo_project_id)) {
            return;
        }

        $newProjectResponse = $this->createProject($course->name);

        if ($newProjectResponse['status'] !== 201) {
            throw new \Exception('Recevied api response from vimeo '.$newProjectResponse['status'].' . Expecting 201');
        }

        $vimeoProjectId = $this->getProjectIdFromVimeoPath($newProjectResponse['body']['uri']);

        return $this->addProjectIdToVideo($course, $vimeoProjectId);
    }

    public function getProjectIdFromVimeoPath(string $vimeoPath): string
    {
        return str($vimeoPath)->afterLast('/')->toString();
    }

    public function addProjectIdToVideo(Course $course, string $projectId)
    {
        return $course->update([
            'vimeo_project_id' => $projectId,
        ]);
    }

    public function checkIfProjectExists(string $projectId): bool
    {
        $url = sprintf(self::VIMEO_URL_GET_PROJECT_URL, $projectId);

        try {
            $response = $this->client->request($url, [], self::HTTP_GET, self::HTTP_JSON, self::HTTP_HEADER);

            return $response['status'] === 200;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function createProject(string $name): array
    {
        $params = ['name' => $name];

        try {
            $response = $this->client->request(self::VIMEO_URL_CREATE_NEW_PROJECT_URL, $params, self::HTTP_POST, self::HTTP_JSON, self::HTTP_HEADER);

            if ($response['status'] >= 400) {
                throw new \Exception($response['body']['error']);
            }

            return $response;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
