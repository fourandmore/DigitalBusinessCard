<?php
namespace DigitalBusinessCard\Security;

use DigitalBusinessCard\Config\DigitalBusinessCardConfig;
use DigitalBusinessCard\Models\AdminAttempt;
use DigitalBusinessCard\Models\AdminSession;
use Plenty\Modules\Plugin\DataBase\Contracts\DataBase;
use Plenty\Plugin\Http\Request;

class AdminSecurityService
{
    const MAX_FAILURES = 8;
    const LOCK_SECONDS = 900;
    const SESSION_SECONDS = 1800;

    private $db;
    private $config;

    public function __construct(DataBase $db, DigitalBusinessCardConfig $config)
    {
        $this->db = $db;
        $this->config = $config;
    }

    public function login(Request $request): array
    {
        $this->cleanup();
        $clientHash = $this->clientHash($request);
        $remaining = $this->lockRemaining($clientHash);
        if ($remaining > 0) {
            return ['ok' => false, 'status' => 429, 'retryAfter' => $remaining, 'error' => 'Zu viele fehlgeschlagene Anmeldeversuche. Bitte später erneut versuchen.'];
        }

        $secret = trim((string)$this->config->adminSecret);
        if (strlen($secret) < 24) {
            return ['ok' => false, 'status' => 503, 'error' => 'Der Verwaltungsschlüssel muss in der Plugin-Konfiguration mindestens 24 Zeichen lang sein.'];
        }

        $provided = trim((string)$request->header('X-DBC-Admin-Key'));
        if ($provided === '' || !$this->secureEquals($secret, $provided)) {
            $attempt = pluginApp(AdminAttempt::class);
            $attempt->clientHash = $clientHash;
            $attempt->failedAt = time();
            $this->db->save($attempt);
            return ['ok' => false, 'status' => 401, 'error' => 'Nicht autorisiert.'];
        }

        $this->db->query(AdminAttempt::class)->where('clientHash', '=', $clientHash)->delete();

        $token = $this->generateToken();
        $now = time();
        $session = pluginApp(AdminSession::class);
        $session->tokenHash = hash('sha256', $token);
        $session->clientHash = $clientHash;
        $session->createdAt = $now;
        $session->lastSeenAt = $now;
        $session->expiresAt = $now + self::SESSION_SECONDS;
        $this->db->save($session);

        return ['ok' => true, 'status' => 200, 'token' => $token, 'expiresIn' => self::SESSION_SECONDS];
    }

    public function validateSession(Request $request): bool
    {
        $token = trim((string)$request->header('X-DBC-Admin-Token'));
        if ($token === '') {
            return false;
        }

        $tokenHash = hash('sha256', $token);
        $items = $this->db->query(AdminSession::class)->where('tokenHash', '=', $tokenHash)->limit(1)->get();
        $session = $items[0] ?? null;
        if (!$session) {
            return false;
        }

        $now = time();
        if ((int)$session->expiresAt < $now || !$this->secureEquals((string)$session->clientHash, $this->clientHash($request))) {
            $this->db->delete($session);
            return false;
        }

        $session->lastSeenAt = $now;
        $session->expiresAt = $now + self::SESSION_SECONDS;
        $this->db->save($session);
        return true;
    }

    public function logout(Request $request): void
    {
        $token = trim((string)$request->header('X-DBC-Admin-Token'));
        if ($token === '') {
            return;
        }
        $tokenHash = hash('sha256', $token);
        $items = $this->db->query(AdminSession::class)->where('tokenHash', '=', $tokenHash)->get();
        foreach ($items as $session) {
            $this->db->delete($session);
        }
    }

    private function lockRemaining(string $clientHash): int
    {
        $cutoff = time() - self::LOCK_SECONDS;
        $attempts = $this->db->query(AdminAttempt::class)
            ->where('clientHash', '=', $clientHash)
            ->where('failedAt', '>=', $cutoff)
            ->orderBy('failedAt', 'asc')
            ->get();

        if (count($attempts) < self::MAX_FAILURES) {
            return 0;
        }

        $first = $attempts[0];
        $remaining = self::LOCK_SECONDS - (time() - (int)$first->failedAt);
        return $remaining > 0 ? $remaining : 0;
    }

    private function cleanup(): void
    {
        $this->db->query(AdminAttempt::class)->where('failedAt', '<', time() - self::LOCK_SECONDS)->delete();
        $this->db->query(AdminSession::class)->where('expiresAt', '<', time())->delete();
    }

    private function clientHash(Request $request): string
    {
        $ip = trim((string)$request->header('CF-Connecting-IP'));
        if ($ip === '') {
            $forwarded = trim((string)$request->header('X-Forwarded-For'));
            if ($forwarded !== '') {
                $parts = explode(',', $forwarded);
                $ip = trim((string)$parts[0]);
            }
        }
        if ($ip === '') {
            $ip = trim((string)$request->header('X-Real-IP'));
        }
        if ($ip === '') {
            $ip = 'unknown';
        }
        $agent = trim((string)$request->header('User-Agent'));
        return hash('sha256', $ip . '|' . $agent);
    }

    private function secureEquals(string $expected, string $provided): bool
    {
        $a = hash('sha256', $expected);
        $b = hash('sha256', $provided);
        if (strlen($a) !== strlen($b)) {
            return false;
        }
        $diff = 0;
        for ($i = 0; $i < strlen($a); $i++) {
            $diff = $diff | (ord($a[$i]) ^ ord($b[$i]));
        }
        return $diff === 0;
    }

    private function generateToken(): string
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $token = '';
        $max = strlen($alphabet) - 1;
        for ($i = 0; $i < 64; $i++) {
            $token .= $alphabet[random_int(0, $max)];
        }
        return $token;
    }
}
