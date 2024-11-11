<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnectionsController extends Controller
{
    public function create(Request $request)
    {

        $request->validate([
            'connection_id' => 'required|string|min:3|max:50|exists:users,id',
        ]);

        // Check if the user is trying to send a request to themselves
        if ($request->connection_id == Auth::id()) {
            return response()->json(['error' => 'You cannot send a connection request to yourself.'], 400);
        }

        $user = Auth::user();

        // Check if a request already exists with this connection_id and status 'P' (Pending)
        if ($user->connections()->where('connection_id', $request->connection_id)->wherePivot('status', 'P')->exists()) {
            return response()->json(['error' => 'Connection request already sent.'], 400);
        }

        // Check if a connection already exists in either direction
        $existingConnection = $user->connections()
            ->where(function ($query) use ($request) {
                $query->where('connection_id', $request->connection_id);
            })
            ->orWhere(function ($query) use ($user, $request) {
                $query->where('connection_id', $user->id)
                    ->where('user_id', $request->connection_id);
            })
            ->exists();

        if ($existingConnection) {
            return response()->json(['error' => 'A connection request already exists between these users.'], 400);
        }

        // Send the connection request
        $user->connections()->attach($request->connection_id, [
            'status' => 'P', // Pending
            'request_send_date' => now(),
        ]);

        return ok(__('strings.connection.create'));
    }

    //  Accept or reject a connection request
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:A,R', // Accept (A) or Reject (R)
        ]);

        $user = Auth::user();

        $connection = $user->connectionRequestsReceived()->where('user_id', $id)->first();

        // Check if the sender is trying to accept their own request
        if ($connection && $connection->pivot->user_id == $user->id) {
            // return response()->json(['message' => 'You cannot accept a request you sent yourself.'], 403);
            return error(__('strings.connection.self_connection_error'));
        }

        if (!$connection) {
            // return response()->json(['message' => 'No connection request found.'], 404);
            return error(__('strings.connection.not_found'));
        }

        //dd($connection);
        $connection->pivot->status = $request->status;
        if ($request->status == 'A') {
            $connection->pivot->request_accepted_date = now();
        }
        $connection->pivot->save();

        // return response()->json(['message' => 'Connection status updated.']);
        return error(__('strings.connection.update'));
    }

    // Delete a connection
    public function delete($id)
    {
        $user = Auth::user();

        // Check if the connection exists in the pivot table for the authenticated user
        $connection = $user->connections()->wherePivot('connection_id', $id)->first();

        if (!$connection) {
            return error(__('strings.connection.not_found'));
        }

        // If the connection exists, proceed to detach it
        $user->connections()->detach($id);

        return ok(__('strings.connection.delete'));
    }

    // View pending connection requests for the authenticated user
    public function pendingRequests()
    {
        $user = Auth::user();
        $pendingRequests = $user->connectionRequestsReceived()->get();

        // Check if there are no pending requests
        if ($pendingRequests->isEmpty()) {
            return ok(__('strings.connection.no_pending_requests'));
        }

        return ok(__('strings.connection.pending_requests'), [
            'requests' => $pendingRequests,
            'count'  => $pendingRequests->count()
        ]);
    }
}
