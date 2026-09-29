<?php
namespace DigitalBusinessCard\Controllers;

use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use DigitalBusinessCard\Contracts\BusinessCardRepositoryContract;
use DigitalBusinessCard\Config\DigitalBusinessCardConfig;

class AdminController extends Controller
{
    public function index(Request $request, DigitalBusinessCardConfig $config, BusinessCardRepositoryContract $repo, Response $response): Response
    {
        $denied = $this->guard($request, $config, $response);
        if ($denied !== null) {
            return $denied;
        }
        return $this->json($response, $repo->all(), 200);
    }

    public function store(Request $request, DigitalBusinessCardConfig $config, BusinessCardRepositoryContract $repo, Response $response): Response
    {
        $denied = $this->guard($request, $config, $response);
        if ($denied !== null) {
            return $denied;
        }

        $data = $request->all();
        if (empty($data['slug'])) {
            return $this->json($response, ['error' => 'Slug fehlt'], 422);
        }
        $existing = $repo->findBySlug($data['slug']);
        if ($existing) {
            return $this->json($response, ['error' => 'Slug bereits vergeben'], 409);
        }
        $card = $repo->save($data);
        return $this->json($response, $card, 201);
    }

    public function update(int $id, Request $request, DigitalBusinessCardConfig $config, BusinessCardRepositoryContract $repo, Response $response): Response
    {
        $denied = $this->guard($request, $config, $response);
        if ($denied !== null) {
            return $denied;
        }

        if (!$repo->findById($id)) {
            return $this->json($response, ['error' => 'Nicht gefunden'], 404);
        }
        $data = $request->all();
        if (!empty($data['slug'])) {
            $same = $repo->findBySlug($data['slug']);
            if ($same && (int)$same->id !== $id) {
                return $this->json($response, ['error' => 'Slug bereits vergeben'], 409);
            }
        }
        return $this->json($response, $repo->save($data, $id), 200);
    }

    public function destroy(int $id, Request $request, DigitalBusinessCardConfig $config, BusinessCardRepositoryContract $repo, Response $response): Response
    {
        $denied = $this->guard($request, $config, $response);
        if ($denied !== null) {
            return $denied;
        }
        return $this->json($response, ['success' => $repo->delete($id)], 200);
    }

    private function guard(Request $request, DigitalBusinessCardConfig $config, Response $response)
    {
        $secret = trim((string)$config->adminSecret);
        if (strlen($secret) < 12) {
            return $this->json($response, ['error' => 'Verwaltungsschlüssel ist im Plugin noch nicht eingerichtet.'], 503);
        }

        $provided = trim((string)$request->header('X-DBC-Admin-Key'));
        if ($provided === '' || $provided !== $secret) {
            return $this->json($response, ['error' => 'Nicht autorisiert.'], 401);
        }
        return null;
    }

    private function json(Response $response, $data, int $status): Response
    {
        return $response->make(json_encode($data), $status, ['Content-Type' => 'application/json; charset=utf-8']);
    }
}
