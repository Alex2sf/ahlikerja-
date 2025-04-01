<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RecommendationController extends Controller
{
    public function getRecommendations()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Log data awal
        Log::info('User ID', ['user_id' => $userId]);
        $users = User::whereHas('bookings')->get();
        Log::info('Users with bookings', ['count' => $users->count(), 'users' => $users->pluck('id')->toArray()]);

        $similarities = [];
        foreach ($users as $otherUser) {
            if ($otherUser->id === $userId) {
                continue;
            }

            $similarity = $this->calculateSimilarity($userId, $otherUser->id);
            Log::info('Similarity calculated', [
                'user1' => $userId,
                'user2' => $otherUser->id,
                'similarity' => $similarity
            ]);

            if ($similarity > 0) {
                $similarities[$otherUser->id] = $similarity;
            }
        }

        Log::info('Similarities', ['similarities' => $similarities]);
        $recommendedContractors = $this->getRecommendedContractors($userId, $similarities);
        Log::info('Recommended Contractors', ['count' => count($recommendedContractors), 'contractors' => array_map(fn($c) => $c->id, $recommendedContractors)]);

        return view('recommendations.index', compact('recommendedContractors'));
    }

    private function calculateSimilarity($userId1, $userId2)
    {
        $user1Bookings = Booking::where('user_id', $userId1)->pluck('contractor_id')->toArray();
        $user2Bookings = Booking::where('user_id', $userId2)->pluck('contractor_id')->toArray();

        $commonContractors = array_intersect($user1Bookings, $user2Bookings);
        $totalUnique = count(array_unique(array_merge($user1Bookings, $user2Bookings)));

        Log::info('Similarity calculation', [
            'user1_bookings' => $user1Bookings,
            'user2_bookings' => $user2Bookings,
            'common_contractors' => $commonContractors,
            'total_unique' => $totalUnique
        ]);

        if ($totalUnique === 0) {
            return 0;
        }

        return count($commonContractors) / $totalUnique;
    }

    private function getRecommendedContractors($userId, $similarities)
    {
        $userBookings = Booking::where('user_id', $userId)->pluck('contractor_id')->toArray();
        $recommendations = [];

        foreach ($similarities as $similarUserId => $similarity) {
            $similarUserBookings = Booking::where('user_id', $similarUserId)
                ->whereNotIn('contractor_id', $userBookings)
                ->with('contractor')
                ->get();

            foreach ($similarUserBookings as $booking) {
                $contractorId = $booking->contractor_id;
                $bookingCount = Booking::where('contractor_id', $contractorId)->count();

                Log::info('Contractor check', [
                    'contractor_id' => $contractorId,
                    'booking_count' => $bookingCount
                ]);

                // Filter minimal 2 pemesanan (opsional)
                if ($bookingCount >= 2) {
                    if (!isset($recommendations[$contractorId])) {
                        $recommendations[$contractorId] = [
                            'contractor' => $booking->contractor,
                            'score' => 0,
                        ];
                    }
                    $recommendations[$contractorId]['score'] += $similarity;
                }
            }
        }

        uasort($recommendations, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return array_map(function ($item) {
            return $item['contractor'];
        }, $recommendations);
    }
}
