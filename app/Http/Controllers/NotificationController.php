<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function notifications() { 
        $notifications = auth()->user() ->unreadNotifications() ->latest() ->get() 
            ->map(function ($notification) { 
                return [ 'id' => $notification->id, 
                    'title' => $notification->data['title'] ?? 'Notification', 
                    'message' => $notification->data['message'] ?? '', 
                    'export_id' => $notification->data['export_id'] ?? null, 
                    'created_at' => $notification->created_at->diffForHumans(), ]; 
        }); 
    
        return response()->json([ 
            'count' => $notifications->count(), 
            'notifications' => $notifications, 
            ]); 
    }
}
