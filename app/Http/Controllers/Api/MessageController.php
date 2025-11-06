<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetMessageRequest;
use App\Http\Requests\StoreMessageRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Message;


class MessageController extends Controller
{

   public function index(GetMessageRequest $request): JsonResponse
    {
        $data = $request->validated();
        $chatId = $data['chat_id'];
        $currentPage = $data['page'];
        $pageSize = $data['page_size'] ?? 15;

        $messages = Message::where('chat_id', $chatId)
            ->with('user')
            ->latest('created_at')
            ->simplePaginate(
                $pageSize,
                ['*'],
                'page',
                $currentPage
            );

        return $this->success($messages->getCollection());
    }

    /**
     * Create a chat message
     *
     * @param StoreMessageRequest $request
     * @return JsonResponse
     */
    public function store(StoreMessageRequest $request) : JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;

        $chatMessage = Message::create($data);
        $chatMessage->load('user');

        /// TODO send broadcast event to pusher and send notification to onesignal services
        // $this->sendNotificationToOther($chatMessage);

        return $this->success($chatMessage,'Message has been sent successfully.');
    }

}
