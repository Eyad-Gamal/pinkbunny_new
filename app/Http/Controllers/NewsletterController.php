<?php

namespace App\Http\Controllers;

use App\Services\NewsletterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function __construct(private readonly NewsletterService $newsletterService)
    {
    }

    public function subscribe(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'contact' => ['required', 'string', 'max:255'],
        ]);

        $result = $this->newsletterService->subscribe($data['contact']);

        if ($request->expectsJson()) {
            return response()->json($result, $result['success'] ? 200 : 422);
        }

        return back()->with($result['success'] ? 'toast' : 'error', $result['message']);
    }
}
