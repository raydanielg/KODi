<?php

namespace App\Http\Controllers\Api\Message;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            $query = Message::with(['sender', 'receiver', 'property'])
                ->forUser($user->id);

            if ($request->has('conversation_with')) {
                $query->between($user->id, $request->conversation_with);
            }

            if ($request->has('property_id')) {
                $query->where('property_id', $request->property_id);
            }

            if ($request->has('unread')) {
                $query->where('receiver_id', $user->id)->where('is_read', false);
            }

            $messages = $query->latest()->paginate($request->per_page ?? 20);

            $unreadCount = Message::where('receiver_id', $user->id)
                ->where('is_read', false)
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'messages' => $messages,
                    'unread_count' => $unreadCount,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'receiver_id' => 'required|exists:users,id',
                'property_id' => 'nullable|exists:properties,id',
                'subject' => 'required|string|max:255',
                'body' => 'required|string',
                'parent_id' => 'nullable|exists:messages,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $message = Message::create([
                'sender_id' => $request->user()->id,
                'receiver_id' => $request->receiver_id,
                'property_id' => $request->property_id,
                'subject' => $request->subject,
                'body' => $request->body,
                'parent_id' => $request->parent_id,
                'is_read' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ujumbe umetumwa.',
                'data' => $message->load(['sender', 'receiver'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function markAsRead(Request $request, $id)
    {
        try {
            $message = Message::findOrFail($id);

            if ($message->receiver_id !== $request->user()->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $message->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ujumbe umesomwa.',
                'data' => $message
            ]);
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json(['success' => false, 'message' => 'Message not found'], 404);
            }
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
