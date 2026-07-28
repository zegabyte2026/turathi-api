<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::with('sites')->paginate(25);

        return response()->json($users);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:super_admin,local_admin',
            'site_ids' => 'sometimes|array',
            'site_ids.*' => 'exists:sites,id',
            'lang' => 'sometimes|in:ar,fr,en',
        ]);

        $temporaryPassword = Str::random(12);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($temporaryPassword),
            'role' => $request->role,
            'lang' => $request->lang ?? 'fr',
        ]);

        if ($request->has('site_ids')) {
            $user->sites()->attach($request->site_ids);
        }

        return response()->json([
            'user' => $user->load('sites'),
            'temporary_password' => $temporaryPassword,
            'message' => 'Compte créé. Un e-mail d\'invitation sera envoyé.',
        ], 201);
    }

    public function updateSites(Request $request, int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $request->validate([
            'site_ids' => 'required|array',
            'site_ids.*' => 'exists:sites,id',
        ]);

        $user->sites()->sync($request->site_ids);

        return response()->json($user->load('sites'));
    }

    public function toggleActive(Request $request, int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'Vous ne pouvez pas désactiver votre propre compte.'], 403);
        }

        if ($user->isSuperAdmin()) {
            return response()->json(['message' => 'Impossible de désactiver un Super Admin.'], 403);
        }

        $user->update(['is_active' => !$user->is_active]);

        return response()->json($user);
    }
}
