<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\Request;

class ComicController extends Controller
{

    public function index(Request $request)
    {
        $query = Comic::with(['category', 'publisher'])
            ->active();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('publisher')) {
            $query->where('publisher_id', $request->publisher);
        }

        if ($request->filled('edition_type')) {
            $query->where('edition_type', $request->edition_type);
        }

        if ($request->filled('price')) {
            $range = $request->input('price');
            $query->where(function ($q) use ($range) {
                switch ($range) {
                    case '0-150000':
                        $q->whereBetween('price', [0, 150000]);
                        break;
                    case '150000-300000':
                        $q->whereBetween('price', [150000, 300000]);
                        break;
                    case '300000-500000':
                        $q->whereBetween('price', [300000, 500000]);
                        break;
                    case '500000-700000':
                        $q->whereBetween('price', [500000, 700000]);
                        break;
                    case '700000+':
                        $q->where('price', '>=', 700000);
                        break;
                }
            });
        }

        if ($request->filled('in_stock')) {
            $query->inStock();
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('title', 'asc');
                break;
            case 'best_seller':
                $query->withCount(['orderItems as total_sold' => function($q) {
                    $q->whereHas('order', function($orderQuery) {
                        $orderQuery->where('order_status', 'completed');
                    });
                }])->orderBy('total_sold', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $comics = $query->paginate(24);
        $categories = Category::all();
        $publishers = Publisher::all();

        if ($request->ajax()) {
            return view('comics._list', compact('comics', 'categories', 'publishers'))->render();
        }

        return view('comics.index', compact('comics', 'categories', 'publishers'));
    }


    public function show($slug)
    {
        $comic = Comic::with(['category', 'publisher'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        $relatedComics = Comic::where('id', '!=', $comic->id)
            ->active()
            ->where(function ($query) use ($comic) {
                $query->where('category_id', $comic->category_id)
                      ->orWhere('series', $comic->series);
            })
            ->limit(4)
            ->get();

        return view('comics.show', compact('comic', 'relatedComics'));
    }


    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $comics = Comic::active()
            ->where('title', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'title', 'slug', 'price', 'cover']);

        return response()->json($comics);
    }

  
    public function getCategories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    /**
     * API Lấy danh sách publishers
     */
    public function getPublishers()
    {
        $publishers = Publisher::all();
        return response()->json($publishers);
    }
}
