<?php

namespace App\Http\Controllers\Debug;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SimpleDebugController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function test(): View
    {
        $user = auth()->user();
        
        $data = [
            'user_id' => $user ? $user->id : null,
            'user_email' => $user ? $user->email : null,
            'user_name' => $user ? $user->name : null,
            'is_authenticated' => auth()->check(),
            'has_roles_trait' => method_exists($user, 'assignRole') ?? false,
        ];

        if ($user && method_exists($user, 'getRoleNames')) {
            try {
                $data['user_roles'] = $user->getRoleNames()->toArray();
            } catch (\Exception $e) {
                $data['roles_error'] = $e->getMessage();
            }
        }

        if ($user && method_exists($user, 'can')) {
            try {
                $data['can_blog_create'] = $user->can('blog.create');
                $data['can_blog_view'] = $user->can('blog.view');
            } catch (\Exception $e) {
                $data['permission_error'] = $e->getMessage();
            }
        }

        return view('debug.simple', compact('data'));
    }

    public function testRoute(): string
    {
        return "Route is working! User: " . (auth()->user()->email ?? 'Not authenticated');
    }
}
