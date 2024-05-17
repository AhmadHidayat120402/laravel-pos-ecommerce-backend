<?php

namespace App\Http\Controllers\Api;

use App\Models\Chat;
use App\Models\User;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use App\Events\NewMessageSent;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetMessageRequest;
use App\Http\Requests\StoreMessageRequest;

class ChatMessageController extends Controller
{
    public function index(GetMessageRequest $request): JsonResponse
    {
        $data = $request->validated();
        $chatId = $data['chat_id'];
        $currentPage = $data['page'];
        $pageSize = $data['page_size'] ?? 15;

        $messages = ChatMessage::where('chat_id', $chatId)
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

    public function store(StoreMessageRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;

        $chatMessage = ChatMessage::create($data);
        $chatMessage->load('user');

        /// TODO send broadcast event to pusher and send notification to onesignal services
        $this->sendNotificationToOther($chatMessage);

        return $this->success($chatMessage, 'Message has been sent successfully.');
    }

    private function sendNotificationToOther(ChatMessage $chatMessage): void
    {

        // TODO move this event broadcast to observer
        broadcast(new NewMessageSent($chatMessage))->toOthers();

        $user = auth()->user();
        $userId = $user->id;

        $chat = Chat::where('id', $chatMessage->chat_id)
            ->with(['partisipants' => function ($query) use ($userId) {
                $query->where('user_id', '!=', $userId);
            }])
            ->first();
        if (count($chat->partisipants) > 0) {
            $otherUserId = $chat->partisipants[0]->user_id;

            $otherUser = User::where('id', $otherUserId)->first();
            $otherUser->sendNewMessageNotification([
                'messageData' => [
                    'senderName' => $user->username,
                    'message' => $chatMessage->message,
                    'chatId' => $chatMessage->chat_id
                ]
            ]);
        }
    }
}
