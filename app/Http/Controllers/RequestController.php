<?php

namespace App\Http\Controllers;

use App\Mail\RequestNotification;
use App\Models\Request;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Mail;


/**
 * @OA\Schema(
 *     schema="Request",
 *     type="object",
 *     required={"id", "name", "email", "status", "created_at", "updated_at"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="johndoe@example.com"),
 *     @OA\Property(property="status", type="string", example="Active"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-12T15:56:40Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-12T15:56:40Z")
 * )
 */

class RequestController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/requests",
     *     summary="Create a new request",
     *     operationId="createRequest",
     *     tags={"Requests"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "message"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *             @OA\Property(property="message", type="string", example="This is a test request.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Request created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *             @OA\Property(property="status", type="string", example="Active")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */
    public function store(HttpRequest $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string'
        ]);

        $newRequest = Request::create($data);

        Mail::to($data['email'])->send(new RequestNotification($newRequest, 'created'));

        return response()->json($newRequest, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/requests",
     *     summary="Get all requests",
     *     operationId="getRequests",
     *     tags={"Requests"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter requests by status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"Active", "Resolved"}, example="Active")
     *     ),
     *     @OA\Parameter(
     *         name="date_from",
     *         in="query",
     *         description="Filter requests created from this date",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2025-03-01")
     *     ),
     *     @OA\Parameter(
     *         name="date_to",
     *         in="query",
     *         description="Filter requests created up to this date",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2025-03-31")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Request")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */
    public function index(HttpRequest $request)
    {
        $query = Request::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->has('date_to')) {
            $dateFrom = Carbon::parse($request->date_from)->startOfDay();
            $dateTo = Carbon::parse($request->date_to)->endOfDay();
            $query->whereBetween('created_at', [$dateFrom, $dateTo]);
        }

        $requests = $query->get();

        return response()->json($requests);
    }

    /**
     * @OA\Put(
     *     path="/api/requests/{id}",
     *     summary="Update a request",
     *     operationId="updateRequest",
     *     tags={"Requests"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status", "comment"},
     *             @OA\Property(property="status", type="string", example="Resolved"),
     *             @OA\Property(property="comment", type="string", example="This request is resolved.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Request updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Resolved"),
     *             @OA\Property(property="comment", type="string", example="This request is resolved.")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */
    public function update(HttpRequest $request, $id)
    {
        $data = $request->validate([
            'comment' => 'required|string',
            'status' => 'required|in:Active,Resolved'
        ]);

        $requestData = Request::findOrFail($id);

        $requestData->update($data);

        Mail::to($requestData->email)->send(new RequestNotification($requestData, 'answered'));

        return response()->json($requestData);
    }
}
