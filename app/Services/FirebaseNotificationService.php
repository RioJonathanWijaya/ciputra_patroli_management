<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Database;

class FirebaseNotificationService
{
    private $messaging;
    private $database;
    private $lastNotificationId;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/firebase_credentials.json'));

        $this->messaging = $factory->createMessaging();
        $this->database = $factory->createDatabase();
    }

    public function sendToAllSatpam(string $title, string $body, array $data = []): int
    {
        // 1. Get all tokens
        $tokens = $this->getAllFcmTokens();

        if (empty($tokens)) {
            return 0;
        }

        // 2. Create notification record
        $notificationRef = $this->database
            ->getReference('notifications')
            ->push();

        $this->lastNotificationId = $notificationRef->getKey();

        $notificationRef->set([
            'title' => $title,
            'body' => $body,
            'data' => $data,
            'target_count' => count($tokens),
            'created_at' => ['.sv' => 'timestamp'],
            'status' => 'sending'
        ]);

        // 3. Send notifications
        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData(array_merge($data, [
                'notification_id' => $this->lastNotificationId
            ]));

        $report = $this->messaging->sendMulticast($message, $tokens);

        // 4. Update status
        $notificationRef->update([
            'status' => 'sent',
            'success_count' => $report->successes()->count(),
            'failure_count' => $report->failures()->count(),
            'sent_at' => ['.sv' => 'timestamp']
        ]);

        return $report->successes()->count();
    }

    private function getAllFcmTokens(): array
    {
        $snapshot = $this->database
            ->getReference('satpam_fcm_tokens')
            ->getSnapshot();

        return array_filter(array_map(
            fn ($item) => $item['token'] ?? null,
            $snapshot->getValue() ?? []
        ));
    }

    public function updateSatpamToken(string $satpamId, string $token): void
    {
        $this->database
            ->getReference("satpam_fcm_tokens/$satpamId")
            ->set([
                'token' => $token,
                'updated_at' => ['.sv' => 'timestamp']
            ]);
    }

    public function getLastNotificationId(): ?string
    {
        return $this->lastNotificationId;
    }

    public function getNotifications(int $limit): array
    {
        return $this->database
            ->getReference('notifications')
            ->orderByChild('created_at')
            ->limitToLast($limit)
            ->getSnapshot()
            ->getValue() ?? [];
    }
}