<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use App\Http\Requests\StoreChatRequest;
use App\Http\Requests\GetChatRequest;
use Illuminate\Http\JsonResponse;



class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   // عرض كل الشات للمستخدم
  public function index(GetChatRequest $request): JsonResponse
{
    // بنجيب الشاتات اللي المستخدم الحالي طرف فيها فقط
    $chats = Chat::hasParticipant(auth()->id())
        ->whereHas('messages')
        ->with('lastMessage.user', 'participants.user')
        ->latest('updated_at')
        ->get();

    return $this->success($chats);
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

     public function store(StoreChatRequest $request) : JsonResponse
    {
        $data = $this->prepareStoreData($request);
        if($data['userId'] === $data['otherUserId']){
            return $this->error('You can not create a chat with your own');
        }

        $previousChat = $this->getPreviousChat($data['otherUserId']);

        if($previousChat === null){

            $chat = Chat::create($data['data']);
            $chat->participants()->createMany([
                [
                    'user_id'=>$data['userId']
                ],
                [
                    'user_id'=>$data['otherUserId']
                ]
            ]);

            $chat->refresh()->load('lastMessage.user','participants.user');
            return $this->success($chat);
        }

        return $this->success($previousChat->load('lastMessage.user','participants.user'));
    }

    
     private function getPreviousChat(int $otherUserId): ?Chat
{
    $userId = auth()->id();

    return Chat::whereHas('participants', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->whereHas('participants', function ($query) use ($otherUserId) {
            $query->where('user_id', $otherUserId);
        })
        ->first();
}



    /**
     * Prepares data for store a chat
     *
     * @param StoreChatRequest $request
     * @return array
     */
    private function prepareStoreData(StoreChatRequest $request) : array
    {
        $data = $request->validated();
        $otherUserId = (int)$data['user_id'];
        unset($data['user_id']);
        $data['created_by'] = auth()->user()->id;

        return [
            'otherUserId' => $otherUserId,
            'userId' => auth()->user()->id,
            'data' => $data,
        ];
    }





    /**
     * Display the specified resource.
     */
      public function show(Chat $chat): JsonResponse
    {
        $chat->load('lastMessage.user', 'participants.user');
        return $this->success($chat);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
