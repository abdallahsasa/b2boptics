<?php

namespace App\Http\Controllers;

use App\Models\Factory;
use Illuminate\Http\Request;

class FactoryController extends Controller
{
    public function index($locale)
    {
        $factories = Factory::where('status', 'approved')
            ->with(['country', 'category'])
            ->latest()
            ->paginate(12);

        return view('factories.index', compact('factories'));
    }

    public function show($locale, Factory $factory)
    {
        if ($factory->status !== 'approved') {
            abort(404);
        }

        $factory->load(['country', 'category', 'products' => function ($q) {
            $q->where('status', 'approved')->latest();
        }]);

        return view('factories.show', compact('factory'));
    }
}
