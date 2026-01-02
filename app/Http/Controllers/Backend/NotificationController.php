<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Mark notification as read and redirect to the target URL.
     */
    public function read($id)
    {
        $notification = DatabaseNotification::find($id);

        if ($notification && $notification->notifiable_id == auth()->id()) {
            $notification->markAsRead();
            
            // Redirect ke URL yang ada di data notifikasi, atau fallback ke dashboard
            $url = $notification->data['url'] ?? route('admin.dashboard');
            
            // Tambahkan parameter query untuk highlighting, jika ada
            if (isset($notification->data['keluhan_id'])) {
               // Kita bisa redirect ke show page dengan flash message
               return redirect($url)->with('highlight_keluhan', $notification->data['keluhan_id']);
            }

            return redirect($url);
        }

        return back();
    }
}
