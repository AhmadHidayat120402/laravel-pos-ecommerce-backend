<?php

namespace App\Http\Controllers\Api;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetChartRequest;
use App\Http\Requests\StoreChatRequest;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetChartRequest $request): JsonResponse
    {
        $data = $request->validated();

        $isPrivate = 1;
        if ($request->has('is_private')) {
            $isPrivate = (int)$data['is_private'];
        }

        $chats = Chat::where('is_private', $isPrivate)
            ->hasPartisipant(auth()->user()->id)
            ->whereHas('messages')
            ->with('lastMessage.user', 'partisipants.user')
            ->latest('updated_at')
            ->get();
        return $this->success($chats);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChatRequest $request): JsonResponse
    {
        $data = $this->prepareStoreData($request);
        if ($data['userId'] === $data['otherUserId']) {
            return $this->error('You can not create a chat with your own');
        }

        $previousChat = $this->getPreviousChat($data['otherUserId']);

        if ($previousChat === null) {

            $chat = Chat::create($data['data']);
            $chat->partisipants()->createMany([
                [
                    'user_id' => $data['userId']
                ],
                [
                    'user_id' => $data['otherUserId']
                ]
            ]);

            $chat->refresh()->load('lastMessage.user', 'partisipants.user');
            return $this->success($chat);
        }

        return $this->success($previousChat->load('lastMessage.user', 'partisipants.user'));
    }

    private function getPreviousChat(int $otherUserId): mixed
    {

        $userId = auth()->user()->id;

        return Chat::where('is_private', 1)
            ->whereHas('partisipants', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->whereHas('partisipants', function ($query) use ($otherUserId) {
                $query->where('user_id', $otherUserId);
            })
            ->first();
    }


    private function prepareStoreData(StoreChatRequest $request): array
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
        $chat->load('lastMessage.user', 'partisipants.user');
        return $this->success($chat);
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
