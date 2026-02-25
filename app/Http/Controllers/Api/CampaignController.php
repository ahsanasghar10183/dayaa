<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Get active campaign for the authenticated device
     */
    public function getActive(Request $request): JsonResponse
    {
        $device = $request->user();

        // Get active campaign assigned to this device
        $campaign = $device->campaigns()
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->first();

        if (!$campaign) {
            return response()->json([
                'success' => false,
                'message' => 'No active campaign found for this device'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'description' => $campaign->description,
                'type' => $campaign->type,
                'status' => $campaign->status,
                'reference_code' => $campaign->reference_code,
                'start_date' => $campaign->start_date?->toIso8601String(),
                'end_date' => $campaign->end_date?->toIso8601String(),
                'design_settings' => $campaign->design_settings,
                'amount_settings' => $campaign->amount_settings,
            ]
        ]);
    }
}
