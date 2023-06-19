<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\NewsPost;
use App\Models\SubCategory;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class IndexController extends Controller
{
    public function index()
    {
        $newnewspost = NewsPost::orderBy('id', 'DESC')->limit(8)->get();
        $newspopular = NewsPost::orderBy('view_count', 'DESC')->limit(8)->get();

        return view('frontend.index', compact('newnewspost', 'newspopular'));
    }

    public function newsDetails($id, $slug)
    {
        $news = NewsPost::findOrFail($id);

        $tags = $news->tags;
        $tags_all = explode(',', $tags);

        $cat_id = $news->category_id;
        $relatedNews = NewsPost::where('category_id', $cat_id)->where('id', '!=', $id)->orderBy('id', 'DESC')->limit(6)->get();

        $newsKey = 'blog' . $news->id;

        if (!Session::has($newsKey)) {
            $news->increment('view_count');
            Session::put($newsKey, 1);
        }

        return view('frontend.news.news_details', compact('news', 'tags_all', 'relatedNews'));
    }


    public function catWiseNews($id, $slug)
    {

        $news = NewsPost::where('status', 1)->where('category_id', $id)->orderBy('id', 'DESC')->get();
        $breadcat = Category::where('id', $id)->first();


        $newstwo = NewsPost::where('status', 1)->where('category_id', $id)->orderBy('id', 'DESC')->limit(2)->get();

        $newnewspost = NewsPost::orderBy('id', 'DESC')->limit(8)->get();
        $newspopular = NewsPost::orderBy('view_count', 'DESC')->limit(8)->get();

        return view('frontend.news.category_news', compact('news', 'breadcat', 'newstwo', 'newnewspost', 'newspopular'));

    }


    public function subCatWiseNews($id, $slug)
    {

        $news = NewsPost::where('status', 1)->where('subcategory_id', $id)->orderBy('id', 'DESC')->get();

        $breadsubcat = SubCategory::where('id', $id)->first();

        $newstwo = NewsPost::where('status', 1)->where('subcategory_id', $id)->orderBy('id', 'DESC')->limit(2)->get();

        $newnewspost = NewsPost::orderBy('id', 'DESC')->limit(8)->get();
        $newspopular = NewsPost::orderBy('view_count', 'DESC')->limit(8)->get();

        return view('frontend.news.subcategory_news', compact('news', 'breadsubcat', 'newstwo', 'newnewspost', 'newspopular'));

    }

    public function change(Request $request)
    {
        App::setLocale($request->lang);
        session()->put('locale', $request->lang);

        return redirect()->back();

    }

    public function searchByDate(Request $request)
    {
        $date = new DateTime($request->date);
        $formatDate = $date->format('d-m-Y');

        $newnewspost = NewsPost::orderBy('id', 'DESC')->limit(8)->get();
        $newspopular = NewsPost::orderBy('view_count', 'DESC')->limit(8)->get();

        $news = NewsPost::where('post_date', $formatDate)->latest()->get();
        return view('frontend.news.search_by_date', compact('news', 'formatDate', 'newnewspost', 'newspopular'));
    }


}