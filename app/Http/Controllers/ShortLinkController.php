<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateShortLinkRequest;
use App\Models\ShortLink;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class ShortLinkController extends Controller
{
    public function createShortLink(CreateShortLinkRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        do {
            $short = Str::random(20);
        } while (ShortLink::where('short', $short)->count() > 0);

        $shortLink = new ShortLink($data);
        $shortLink->id = Str::uuid()->toString();
        $shortLink->user = $user->id;
        $shortLink->short = $short;

        $shortLink->save();
        return response()->json([
            'data' => [
                'id' => $shortLink->id,
                'url' => $shortLink->url,
                'short' => $shortLink->short
            ]
        ])->setStatusCode(201);
    }

    public function getShortLink(Request $request, string $idShort)
    {
        $shortLink = ShortLink::where([
            'id' => $idShort,
            'isDelete' => false
        ])->first();
        if (!$shortLink) {
            return response()->json([
                'errors' => [
                    'message' => 'short link not found'
                ]
            ])->setStatusCode(404);
        }

        return response()->json([
            'data' => [
                'id' => $shortLink->id,
                'url' => $shortLink->url,
                'short' => $shortLink->short,
                'updated_at' => $shortLink->updated_at
            ]
        ])->setStatusCode(200);
    }

    public function getAllShortLinks(Request $request)
    {
        $user = Auth::user();
        $shortLinks = ShortLink::where('user', $user->id)->where('isDelete', false)->get(['id', 'url', 'short', 'updated_at']);

        return response()->json([
            'data' => $shortLinks
        ])->setStatusCode(200);
    }

    public function deleteShortLink(Request $request, string $idShort)
    {
        $shortLink = ShortLink::where([
            'id' => $idShort,
            'isDelete' => false
        ])->first();
        if (!$shortLink) {
            return response()->json([
                'errors' => [
                    'message' => 'short link not found'
                ]
            ])->setStatusCode(404);
        }

        $shortLink->isDelete = true;

        $shortLink->save();

        return response()->json([
            'data' => true,
        ])->setStatusCode(200);
    }

    public function redirectShortLink(Request $request, string $short)
    {
        $shortLink = ShortLink::where([
            'short' => $short,
            'isDelete' => false
        ])->first();
        if (!$shortLink) {
            return response()->json([
                'errors' => [
                    'message' => 'short link not found'
                ]
            ])->setStatusCode(404);
        }

        return response()->json([
            'data' => [
                'url' => $shortLink->url
            ]
        ])->setStatusCode(200);
    }
}
