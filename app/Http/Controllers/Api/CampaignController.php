<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Get all campaigns assigned to the authenticated device
     */
    public function index(Request $request): JsonResponse
    {
        $device = $request->user();

        // Get all campaigns assigned to this device
        $campaigns = $device->campaigns()
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->with(['donations' => function($query) {
                $query->where('payment_status', 'completed');
            }])
            ->get();

        $campaignsData = $campaigns->map(function ($campaign) {
            $designSettings = $campaign->design_settings ?? [];
            $amountSettings = $campaign->amount_settings ?? [];

            // Transform image URLs
            if (isset($designSettings['background_image']) && !empty($designSettings['background_image'])) {
                if (!str_starts_with($designSettings['background_image'], 'http')) {
                    $designSettings['background_image'] = url('storage/' . $designSettings['background_image']);
                }
            }

            if (isset($designSettings['thankyou_image']) && !empty($designSettings['thankyou_image'])) {
                if (!str_starts_with($designSettings['thankyou_image'], 'http')) {
                    $designSettings['thankyou_image'] = url('storage/' . $designSettings['thankyou_image']);
                }
            }

            return [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'description' => $campaign->description,
                'type' => $campaign->type,
                'status' => $campaign->status,
                'reference_code' => $campaign->reference_code,
                'start_date' => $campaign->start_date?->toIso8601String(),
                'end_date' => $campaign->end_date?->toIso8601String(),
                'language' => $campaign->language ?? 'en',
                'currency' => $campaign->currency ?? 'EUR',
                'design_settings' => $designSettings,
                'amount_settings' => $amountSettings,
                'updated_at' => $campaign->updated_at?->toIso8601String(),
                'total_raised' => $campaign->donations->sum('amount'),
                'donations_count' => $campaign->donations->count(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $campaignsData
        ]);
    }

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

        // Get the casted arrays directly from the model
        $designSettings = $campaign->design_settings ?? [];
        $amountSettings = $campaign->amount_settings ?? [];

        \Log::info('Original design_settings from model:', ['settings' => $designSettings]);

        // Convert background_image to full URL if it exists and is a relative path
        if (isset($designSettings['background_image']) && !empty($designSettings['background_image'])) {
            $originalPath = $designSettings['background_image'];
            if (!str_starts_with($designSettings['background_image'], 'http')) {
                $designSettings['background_image'] = url('storage/' . $designSettings['background_image']);
                \Log::info('Transformed background_image:', [
                    'original' => $originalPath,
                    'transformed' => $designSettings['background_image']
                ]);
            }
        }

        // Convert thankyou_image to full URL if it exists and is a relative path
        if (isset($designSettings['thankyou_image']) && !empty($designSettings['thankyou_image'])) {
            $originalPath = $designSettings['thankyou_image'];
            if (!str_starts_with($designSettings['thankyou_image'], 'http')) {
                $designSettings['thankyou_image'] = url('storage/' . $designSettings['thankyou_image']);
                \Log::info('Transformed thankyou_image:', [
                    'original' => $originalPath,
                    'transformed' => $designSettings['thankyou_image']
                ]);
            }
        }

        \Log::info('Final design_settings being returned:', ['settings' => $designSettings]);

        // CRITICAL: Return design_settings as ARRAY (not JSON string) so mobile app receives it properly
        // Laravel will automatically JSON encode the response

        // Return transformed settings as arrays - Laravel's response()->json() will handle encoding
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'description' => $campaign->description,
                'type' => $campaign->type,
                'campaign_type' => $campaign->type, // Alias for mobile app compatibility
                'status' => $campaign->status,
                'reference_code' => $campaign->reference_code,
                'start_date' => $campaign->start_date?->toIso8601String(),
                'end_date' => $campaign->end_date?->toIso8601String(),
                'language' => $campaign->language ?? 'en',
                'currency' => $campaign->currency ?? 'EUR',
                'design_settings' => $designSettings, // Return as array with transformed URLs
                'amount_settings' => $amountSettings,
                'updated_at' => $campaign->updated_at?->toIso8601String(), // For change detection
            ]
        ]);
    }
}
