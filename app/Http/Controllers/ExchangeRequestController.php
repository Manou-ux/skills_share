<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ExchangeRequest;
use App\Http\Requests\StoreExchangeRequestRequest;
use Illuminate\Http\Request;

class ExchangeRequestController extends Controller
{
    public function index()
    {
        $received = auth()->user()->receivedRequests()->with(['sender', 'skill'])->latest()->get();
        $sent = auth()->user()->sentRequests()->with(['receiver', 'skill'])->latest()->get();

        return view('exchange-requests.index', compact('received', 'sent'));
    }

    public function store(StoreExchangeRequestRequest $request)
    {
        $pending = ExchangeRequest::where('sender_id', auth()->id())
            ->where('receiver_id', $request->receiver_id)
            ->where('skill_id', $request->skill_id)
            ->where('status', 'en_attente')
            ->exists();

        if ($pending) {
            return back()->withErrors(['skill_id' => 'Une demande en attente existe déjà pour cette compétence.'])->withInput();
        }

        ExchangeRequest::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'skill_id' => $request->skill_id,
            'message' => $request->message,
            'status' => 'en_attente',
        ]);

        return redirect()->route('exchange-requests.index')->with('success', 'Demande envoyée.');
    }

    public function update(ExchangeRequest $exchangeRequest, Request $request)
    {
        abort_unless($exchangeRequest->receiver_id === auth()->id(), 403);

        $request->validate([
            'status' => 'required|in:acceptee,refusee',
        ]);

        $exchangeRequest->update(['status' => $request->status]);

        if ($request->status === 'acceptee') {
            $conversation = Conversation::findOrCreateBetween(
                $exchangeRequest->sender_id,
                $exchangeRequest->receiver_id,
                $exchangeRequest->id
            );

            return redirect()
                ->route('chat.show', $conversation)
                ->with('success', 'Demande acceptée. Vous pouvez discuter ici.');
        }

        return back()->with('success', 'Statut mis à jour.');
    }

    public function destroy(ExchangeRequest $exchangeRequest)
    {
        abort_unless($exchangeRequest->sender_id === auth()->id(), 403);
        abort_unless($exchangeRequest->status === 'en_attente', 403);

        $exchangeRequest->delete();

        return back()->with('success', 'Demande annulée.');
    }
}
