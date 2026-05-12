<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index()
    {
        $ratings = Rating::with(['customer', 'booking'])
            ->latest()
            ->paginate(15);
        return view('admin.ratings.index', compact('ratings'));
    }

    public function show(Rating $rating)
    {
        $rating->load(['customer', 'booking']);
        return view('admin.ratings.show', compact('rating'));
    }

    public function approve(Rating $rating)
    {
        $rating->update(['is_approved' => true]);

        ActivityLog::log(auth()->id(), 'rating_approved', "Approved rating #{$rating->id}");

        return redirect()->route('admin.ratings.index')->with('success', 'Rating approved successfully.');
    }

    public function destroy(Rating $rating)
    {
        $rating->delete();

        ActivityLog::log(auth()->id(), 'rating_deleted', "Deleted rating #{$rating->id}");

        return redirect()->route('admin.ratings.index')->with('success', 'Rating deleted successfully.');
    }
}
