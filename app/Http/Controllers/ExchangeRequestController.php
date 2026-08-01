<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRequest;
use App\Http\Requests\StoreExchangeRequestRequest;

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
        ExchangeRequest::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'skill_id' => $request->skill_id,
            'message' => $request->message,
            'status' => 'en_attente',
        ]);

        return back()->with('success', 'Demande envoyée.');
    }

    public function update(ExchangeRequest $exchangeRequest, \Illuminate\Http\Request $request)
    {
        abort_unless($exchangeRequest->receiver_id === auth()->id(), 403);

        $request->validate([
            'status' => 'required|in:acceptee,refusee',
        ]);

        $exchangeRequest->update(['status' => $request->status]);

        return back()->with('success', 'Statut mis à jour.');
    }

    public function destroy(ExchangeRequest $exchangeRequest)
    {
        abort_unless($exchangeRequest->sender_id === auth()->id(), 403);

        $exchangeRequest->delete();

        return back()->with('success', 'Demande annulée.');
    }
}