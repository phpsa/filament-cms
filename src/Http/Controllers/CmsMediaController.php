<?php

namespace Phpsa\FilamentCms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Phpsa\FilamentCms\Models\CmsMedia;

class CmsMediaController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json(
            CmsMedia::when(
                $request->has('q'),
                fn($query) => $query->filtered($request->get('q'))
            )->latest()
            ->paginate(30)
            ->withQueryString()
        );
    }
}
