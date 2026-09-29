<?php
namespace DigitalBusinessCard\Controllers;

use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use DigitalBusinessCard\Contracts\BusinessCardRepositoryContract;

class AdminController extends Controller
{
    public function index(BusinessCardRepositoryContract $repo, Response $response): Response
    {
        return $response->make(json_encode($repo->all()), 200, ['Content-Type'=>'application/json; charset=utf-8']);
    }

    public function store(Request $request, BusinessCardRepositoryContract $repo, Response $response): Response
    {
        $data = $request->all();
        if (empty($data['slug'])) return $response->make(json_encode(['error'=>'Slug fehlt']), 422, ['Content-Type'=>'application/json']);
        $existing = $repo->findBySlug($data['slug']);
        if ($existing) return $response->make(json_encode(['error'=>'Slug bereits vergeben']), 409, ['Content-Type'=>'application/json']);
        $card = $repo->save($data);
        return $response->make(json_encode($card), 201, ['Content-Type'=>'application/json; charset=utf-8']);
    }

    public function update(int $id, Request $request, BusinessCardRepositoryContract $repo, Response $response): Response
    {
        if (!$repo->findById($id)) return $response->make(json_encode(['error'=>'Nicht gefunden']), 404, ['Content-Type'=>'application/json']);
        $data = $request->all();
        if (!empty($data['slug'])) {
            $same = $repo->findBySlug($data['slug']);
            if ($same && (int)$same->id !== $id) return $response->make(json_encode(['error'=>'Slug bereits vergeben']), 409, ['Content-Type'=>'application/json']);
        }
        return $response->make(json_encode($repo->save($data, $id)), 200, ['Content-Type'=>'application/json; charset=utf-8']);
    }

    public function destroy(int $id, BusinessCardRepositoryContract $repo, Response $response): Response
    {
        return $response->make(json_encode(['success'=>$repo->delete($id)]), 200, ['Content-Type'=>'application/json']);
    }
}
