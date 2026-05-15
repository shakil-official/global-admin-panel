<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SimpleBlogTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function testAdd(): View
    {
        $user = auth()->user();
        $hasPermission = $user ? $user->can('blog_create') : false;
        
        return view('test.blog-add', compact('user', 'hasPermission'));
    }

    public function testMiddleware(): View
    {
        $user = auth()->user();
        
        // Test if middleware is working
        try {
            $hasPermission = $user ? $user->can('blog_create') : false;
            $message = $hasPermission ? 'Permission check passed' : 'Permission check failed';
        } catch (\Exception $e) {
            $message = 'Error: ' . $e->getMessage();
        }

        return view('test.middleware', compact('user', 'message'));
    }
}
