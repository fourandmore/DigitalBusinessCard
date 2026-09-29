<?php
namespace DigitalBusinessCard\Controllers;

use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use DigitalBusinessCard\Contracts\BusinessCardRepositoryContract;
use DigitalBusinessCard\Security\AdminSecurityService;

class AdminController extends Controller
{
    public function login(Request $request, AdminSecurityService $security, Response $response): Response
    {
        $result = $security->login($request);
        $status = (int)$result['status'];
        $headers = $this->jsonHeaders();
        if (!empty($result['retryAfter'])) {
            $headers['Retry-After'] = (string)$result['retryAfter'];
        }
        return $response->make(json_encode($result), $status, $headers);
    }

    public function logout(Request $request, AdminSecurityService $security, Response $response): Response
    {
        $security->logout($request);
        return $this->json($response, ['success' => true], 200);
    }

    public function index(Request $request, AdminSecurityService $security, BusinessCardRepositoryContract $repo, Response $response): Response
    {
        $denied = $this->guard($request, $security, $response);
        if ($denied !== null) {
            return $denied;
        }
        return $this->json($response, $repo->all(), 200);
    }

    public function store(Request $request, AdminSecurityService $security, BusinessCardRepositoryContract $repo, Response $response): Response
    {
        $denied = $this->guard($request, $security, $response);
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

    public function update(int $id, Request $request, AdminSecurityService $security, BusinessCardRepositoryContract $repo, Response $response): Response
    {
        $denied = $this->guard($request, $security, $response);
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

    public function destroy(int $id, Request $request, AdminSecurityService $security, BusinessCardRepositoryContract $repo, Response $response): Response
    {
        $denied = $this->guard($request, $security, $response);
        if ($denied !== null) {
            return $denied;
        }
        return $this->json($response, ['success' => $repo->delete($id)], 200);
    }

    private function guard(Request $request, AdminSecurityService $security, Response $response)
    {
        if (!$security->validateSession($request)) {
            return $this->json($response, ['error' => 'Sitzung abgelaufen oder nicht autorisiert.'], 401);
        }
        return null;
    }

    private function json(Response $response, $data, int $status): Response
    {
        return $response->make(json_encode($data), $status, $this->jsonHeaders());
    }

    private function jsonHeaders(): array
    {
        return [
            'Content-Type' => 'application/json; charset=utf-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'Referrer-Policy' => 'no-referrer'
        ];
    }
}
