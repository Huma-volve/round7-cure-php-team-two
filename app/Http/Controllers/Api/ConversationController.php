<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Recipient;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ConversationController extends Controller
{
    public function index()
    {
        // dd('yasmina');
        $user = Auth::user();
        return $user->conversations()->with([
            'lastMessage',
            'participants' => function($builder) use ($user) {
                $builder->where('id', '<>', $user->id);
            },])
            ->withCount([
                'recipients as new_messages' => function($builder) use ($user) {
                    $builder->where('recipients.user_id', '=', $user->id)
                        ->whereNull('read_at');
                }
            ])
            ->paginate();
    }

    public function show($id)
    {
        $user = Auth::user();
        return $user->conversations()->with([
            'lastMessage',
            'participants' => function($builder) use ($user) {
                $builder->where('id', '<>', $user->id);
            },])
            ->withCount([
                'recipients as new_messages' => function($builder) use ($user) {
                    $builder->where('recipients.user_id', '=', $user->id)
                        ->whereNull('read_at');
                }
            ])
            ->findOrFail($id);
    }

    // public function addParticipant(Request $request, Conversation $conversation)
    // {
    //     $request->validate([
    //         'user_id' => ['required', 'int', 'exists:users,id'],
    //     ]);

    //     $conversation->participants()->attach($request->post('user_id'), [
    //         'joined_at' => Carbon::now(),
    //     ]);
    // }

    // public function removeParticipant(Request $request, Conversation $conversation)
    // {
    //     $request->validate([
    //         'user_id' => ['required', 'int', 'exists:users,id'],
    //     ]);

    //     $conversation->participants()->detach($request->post('user_id'));
    // }

    public function markAsRead($id)
    {
        Recipient::where('user_id', '=', Auth::id())
            ->whereNull('read_at')
            ->whereRaw('message_id IN (
                SELECT id FROM messages WHERE conversation_id = ?
            )', [$id])
            ->update([
                'read_at' => Carbon::now(),
            ]);

        return [
            'message' => 'Messages marked as read',
        ];
    }

    public function destroy($id)
    {
        Recipient::where('user_id', '=', Auth::id())
            ->whereRaw('message_id IN (
                SELECT id FROM messages WHERE conversation_id = ?
            )', [$id])
            ->delete();

        return [
            'message' => 'Conversation deleted',
        ];
    }
}
