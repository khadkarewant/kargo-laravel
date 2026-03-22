<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $base = ServiceRequest::active()
            ->where('user_id', auth()->id())
            ->whereIn('status', [
                ServiceRequest::STATUS_REQUEST,
                ServiceRequest::STATUS_PENDING,
                ServiceRequest::STATUS_COMPLETED,
                ServiceRequest::STATUS_APPROVED,
                ServiceRequest::STATUS_REVISION_REQUIRED,
                ]);

        $totalRequests = (clone $base)->count();

        $base1 = ServiceRequest::active()
            ->where('user_id', auth()->id())
            ->whereIn('status', [
                ServiceRequest::STATUS_PENDING,
                ServiceRequest::STATUS_COMPLETED,
                ServiceRequest::STATUS_REVISION_REQUIRED,
            ]);

        $inProgress = (clone $base1)->count();

    
        $recentRequests = (clone $base)
            ->latest('id')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalRequests',
            'inProgress',
            'recentRequests'
        ));
    }
}