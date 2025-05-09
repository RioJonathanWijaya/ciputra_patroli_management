<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class NotificationController extends Controller
{
    protected $database;
    protected $kejadianRef;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->kejadianRef = $this->database->getReference('kejadian');
    }

    public function notifikasi()
    {
        return view('admin.notifikasi.notifikasi');
    }

    public function getNotifications()
    {
        try {
            $kejadianData = $this->kejadianRef->getValue();
            $notifications = [];

            if ($kejadianData) {
                foreach ($kejadianData as $key => $kejadian) {
                    if (isset($kejadian['is_notifikasi']) && $kejadian['is_notifikasi'] === true) {
                        $notifications[] = [
                            'id' => $key,
                            'judul' => $kejadian['nama_kejadian'] ?? 'Tidak ada judul',
                            'deskripsi' => $kejadian['deskripsi'] ?? 'Tidak ada deskripsi',
                            'tanggal' => $kejadian['tanggal'] ?? now()->toDateTimeString(),
                            'is_read' => $kejadian['is_read'] ?? false,
                            'lokasi' => $kejadian['lokasi'] ?? 'Tidak ada lokasi',
                            'tipe' => $kejadian['tipe'] ?? 'Tidak ada tipe',
                            'status' => $kejadian['status'] ?? 'Tidak ada status'
                        ];
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => $notifications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markAsRead(Request $request)
    {
        try {
            $kejadianId = $request->input('kejadian_id');
            
            if (!$kejadianId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kejadian ID is required'
                ], 400);
            }

            $this->kejadianRef->getChild($kejadianId)->update([
                'is_read' => true,
                'read_at' => now()->toDateTimeString()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking notification as read: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markAllAsRead()
    {
        try {
            $kejadianData = $this->kejadianRef->getValue();

            if ($kejadianData) {
                foreach ($kejadianData as $key => $kejadian) {
                    if (isset($kejadian['is_notifikasi']) && $kejadian['is_notifikasi'] === true) {
                        $this->kejadianRef->getChild($key)->update([
                            'is_read' => true,
                            'read_at' => now()->toDateTimeString()
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking all notifications as read: ' . $e->getMessage()
            ], 500);
        }
    }
}