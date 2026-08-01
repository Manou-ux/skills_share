<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $conversations = Conversation::query()
            ->where(function ($q) use ($userId) {
                $q->where('user_one_id', $userId)->orWhere('user_two_id', $userId);
            })
            ->with(['latestMessage', 'userOne', 'userTwo', 'exchangeRequest.skill'])
            ->withCount([
                'messages as unread_count' => function ($q) use ($userId) {
                    $q->where('user_id', '!=', $userId)->whereNull('read_at');
                },
            ])
            ->latest('updated_at')
            ->get();

        return view('chat.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        abort_unless($conversation->involves(auth()->id()), 403);

        $conversation->load(['exchangeRequest.skill', 'userOne', 'userTwo']);

        Message::where('conversation_id', $conversation->id)
            ->where('user_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $other = $conversation->otherUser(auth()->id());
        $initialMessages = $this->formatMessages($conversation);

        return view('chat.show', compact('conversation', 'other', 'initialMessages'));
    }

    public function store(Request $request, Conversation $conversation)
    {
        abort_unless($conversation->involves(auth()->id()), 403);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $message = $conversation->messages()->create([
            'user_id' => auth()->id(),
            'body' => trim($data['body']),
        ]);

        $conversation->touch();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'message' => [
                    'id' => $message->id,
                    'body' => $message->body,
                    'mine' => true,
                    'user' => auth()->user()->name,
                    'time' => $message->created_at->format('H:i'),
                ],
            ]);
        }

        return back();
    }

    public function start(User $user)
    {
        abort_if($user->id === auth()->id(), 403);

        $conversation = Conversation::findOrCreateBetween(auth()->id(), $user->id);

        return redirect()->route('chat.show', $conversation);
    }

    public function messages(Conversation $conversation)
    {
        abort_unless($conversation->involves(auth()->id()), 403);

        Message::where('conversation_id', $conversation->id)
            ->where('user_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'messages' => $this->formatMessages($conversation),
        ]);
    }

    private function formatMessages(Conversation $conversation): array
    {
        return $conversation->messages()
            ->with('user')
            ->orderBy('created_at')
            ->get()
            ->map(fn (Message $message) => [
                'id' => $message->id,
                'body' => $message->body,
                'mine' => $message->user_id === auth()->id(),
                'user' => $message->user->name,
                'time' => $message->created_at->format('H:i'),
            ])
            ->values()
            ->all();
    }
}
