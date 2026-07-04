<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    /**
     * Liste des notifications
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Notification::where('user_id', $user->id);

        // Filtre par type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filtre par lu/non lu
        if ($request->has('read') && $request->read !== '') {
            $query->where('is_read', $request->read);
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => Notification::where('user_id', $user->id)->count(),
            'non_lues' => Notification::where('user_id', $user->id)->where('is_read', false)->count(),
            'lues' => Notification::where('user_id', $user->id)->where('is_read', true)->count(),
        ];

        $types = Notification::where('user_id', $user->id)
            ->distinct()
            ->pluck('type')
            ->toArray();

        return view('client.notifications.index', compact('notifications', 'stats', 'types'));
    }

    /**
     * Afficher une notification
     */
    public function show($id)
    {
        $user = auth()->user();

        $notification = Notification::where('user_id', $user->id)
            ->findOrFail($id);

        // Marquer comme lue
        if (!$notification->is_read) {
            $notification->markAsRead();
        }

        return view('client.notifications.show', compact('notification'));
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($id)
    {
        $user = auth()->user();

        $notification = Notification::where('user_id', $user->id)
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        $user = auth()->user();

        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return redirect()->route('client.notifications.index')
            ->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /**
     * Supprimer une notification
     */
    public function destroy($id)
    {
        $user = auth()->user();

        $notification = Notification::where('user_id', $user->id)
            ->findOrFail($id);

        $notification->delete();

        return redirect()->route('client.notifications.index')
            ->with('success', 'Notification supprimée.');
    }

    /**
     * Supprimer toutes les notifications lues
     */
    public function clearRead()
    {
        $user = auth()->user();

        Notification::where('user_id', $user->id)
            ->where('is_read', true)
            ->delete();

        return redirect()->route('client.notifications.index')
            ->with('success', 'Notifications lues supprimées.');
    }

    /**
     * Compter les notifications non lues (AJAX)
     */
    public function countUnread()
    {
        $user = auth()->user();

        $count = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Dernières notifications (AJAX)
     */
    public function latest()
    {
        $user = auth()->user();

        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json($notifications);
    }
}