<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\Condition;
use App\Models\Listing;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Favorite;
use App\Models\Profile;
use App\Models\Category;
use App\Models\Comment;

use App\Http\Requests\SellRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\CommentRequest;

class ContentController extends Controller
{
    private function getMyListingContentIds()
    {
        if (!auth()->check()) {
            return collect();
        }
        return Listing::where('user_id', auth()->id())->pluck('content_id');
    }

    private function getSoldContentIds()
    {
        return Purchase::whereNotNull('content_id')->pluck('content_id')->toArray();
    }

    private function addSoldFlag($contents, $soldContentIds)
    {
        foreach ($contents as $content) {
            $content->is_sold = in_array($content->id, $soldContentIds);
        }
    }

    public function index()
    {
        $myListingContentIds = $this->getMyListingContentIds();

        $contentsQuery = Content::query();
        if ($myListingContentIds->isNotEmpty()) {
            $contentsQuery->whereNotIn('id', $myListingContentIds);
        }
        $contents = $contentsQuery->paginate(40, ['*'], 'contents_page');

        $soldContentIds = $this->getSoldContentIds();
        $this->addSoldFlag($contents, $soldContentIds);

        $myContents = collect();
        if (auth()->check()) {
            $favoriteContentIds = Favorite::where('user_id', auth()->id())->pluck('content_id');

            if ($favoriteContentIds->isNotEmpty()) {
                $myContentsQuery = Content::whereIn('id', $favoriteContentIds);
                if ($myListingContentIds->isNotEmpty()) {
                    $myContentsQuery->whereNotIn('id', $myListingContentIds);
                }
                $myContents = $myContentsQuery->paginate(40, ['*'], 'mycontents_page');
                $this->addSoldFlag($myContents, $soldContentIds);
            }
        }

        return view('index', compact('contents', 'myContents'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query)) {
            return redirect('/');
        }

        $myListingContentIds = $this->getMyListingContentIds();

        $contentsQuery = Content::where(function($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%')
              ->orWhere('brand', 'like', '%' . $query . '%')
              ->orWhere('info', 'like', '%' . $query . '%');
        });
        if ($myListingContentIds->isNotEmpty()) {
            $contentsQuery->whereNotIn('id', $myListingContentIds);
        }
        $contents = $contentsQuery->paginate(40, ['*'], 'contents_page')
            ->appends(['q' => $query]);

        $soldContentIds = $this->getSoldContentIds();
        $this->addSoldFlag($contents, $soldContentIds);

        $myContents = collect();
        if (auth()->check()) {
            $favoriteContentIds = Favorite::where('user_id', auth()->id())->pluck('content_id');

            if ($favoriteContentIds->isNotEmpty()) {
                $myContentsQuery = Content::whereIn('id', $favoriteContentIds)
                    ->where(function($q) use ($query) {
                        $q->where('name', 'like', '%' . $query . '%')
                          ->orWhere('brand', 'like', '%' . $query . '%')
                          ->orWhere('info', 'like', '%' . $query . '%');
                    });
                if ($myListingContentIds->isNotEmpty()) {
                    $myContentsQuery->whereNotIn('id', $myListingContentIds);
                }
                $myContents = $myContentsQuery->paginate(40, ['*'], 'mycontents_page')
                    ->appends(['q' => $query]);
                $this->addSoldFlag($myContents, $soldContentIds);
            }
        }

        return view('search', compact('contents', 'myContents', 'query'));
    }

    public function mypage()
    {
        if (!auth()->check()) {
            return redirect()->back()->with('error', 'ログインしてください');
        }

        $users = User::where('id', auth()->user()->id)->with('profile')->get();
        $listings = Listing::where('user_id', auth()->user()->id)
            ->with('content')
            ->paginate(20, ['*'], 'listings_page');
        
        $soldContentIds = $this->getSoldContentIds();
        foreach ($listings as $listing) {
            if ($listing->content) {
                $listing->content->is_sold = in_array($listing->content->id, $soldContentIds);
            }
        }
        
        $purchases = Purchase::where('user_id', auth()->user()->id)
            ->paginate(20, ['*'], 'purchases_page');
        return view('mypage', compact('users', 'listings', 'purchases'));
    }

    public function sell()
    {
        if (!auth()->check()) {
            return redirect()->back()->with('error', 'ログインしてください');
        }

        // 商品状態マスタを取得
        $conditions = Condition::all();

        return view('sell', compact('conditions'));
    }

    public function sellStore(SellRequest $request)
    {
        if (!auth()->check()) {
            return redirect()->back()->with('error', 'ログインしてください');
        }

        $categories = Category::whereIn('name', $request->categories)->get();

        if ($categories->isEmpty()) {
            return redirect()->back()->with('error', '選択されたカテゴリーが見つかりませんでした');
        }

        $storedPath = $request->file('image')->store('image', 'public');
        $imagePathForDb = 'storage/' . $storedPath;

        $content = Content::create([
            'category_id' => $categories->first()->id,
            'image'       => $imagePathForDb,
            'name'        => $request->name,
            'price'       => $request->price,
            'info'        => $request->info,
            'condition_id' => $request->condition_id,
        ]);

        $content->categories()->attach($categories->pluck('id'));

        Listing::create([
            'user_id'     => auth()->id(),
            'content_id'  => $content->id,
        ]);

        return redirect('/')->with('success', '商品を出品しました');
    }

    public function profile()
    {
        $users = User::where('id', auth()->user()->id)->with('profile')->get();
        return view('profile', compact('users'));
    }

    public function profileUpdate(ProfileRequest $request)
    {
        $profile = Profile::where('user_id', auth()->user()->id)->first();

        if (!$profile) {
            $profileData = [
                'user_id'  => auth()->id(),
                'name'     => $request->name,
                'postcode' => $request->postcode,
                'address'  => $request->address,
                'building' => $request->building,
            ];

            if ($request->hasFile('image')) {
                $storedPath = $request->file('image')->store('image', 'public');
                $profileData['image'] = 'storage/' . $storedPath;
            }

            Profile::create($profileData);
            return redirect('/mypage')->with('success', 'プロフィールを登録しました');
        }

        $profile->name = $request->name;
        $profile->postcode = $request->postcode;
        $profile->address = $request->address;
        $profile->building = $request->building;

        if ($request->hasFile('image')) {
            $storedPath = $request->file('image')->store('image', 'public');
            $profile->image = 'storage/' . $storedPath;
        }

        $profile->save();
        return redirect('/mypage')->with('success', 'プロフィールを更新しました');
    }

    public function purchase()
    {
        return view('purchase');
    }

    public function purchase_buy(Request $request)
    {
        $content = Content::where('id', $request->content_id)->firstOrFail();
        $profile = Profile::where('user_id', auth()->user()->id)->first();
        
        if (!$profile || !$profile->address) {
            return redirect('/address?content_id=' . $content->id)
                ->with('error', '購入には配送先の登録が必要です');
        }
        
        return view('purchase', compact('content', 'profile'));
    }

    public function purchase_store(PurchaseRequest $request)
    {
        $content = Content::where('id', $request->content_id)->firstOrFail();

        Purchase::create([
            'user_id'    => auth()->id(),
            'content_id' => $content->id,
            'image'      => $content->image,
            'name'       => $content->name,
            'brand'      => $content->brand ?? '',
            'price'      => $content->price,
            'detail'     => $content->info ?? '',
            'info'       => $content->info ?? '',
        ]);

        return redirect('/thanks')->with('success', 'ご購入ありがとうございました');
    }

    public function thanks()
    {
        return view('thanks');
    }

    public function item($id)
    {
        $content = Content::where('id', $id)
            ->with(['category', 'categories', 'condition', 'comments.user.profile'])
            ->get();
        
        $soldContentIds = $this->getSoldContentIds();
        
        foreach ($content as $item) {
            $item->favorite_count = Favorite::where('content_id', $item->id)->count();
            $item->comment_count = $item->comments()->count();
            $item->is_sold = in_array($item->id, $soldContentIds);
            $item->is_favorited = auth()->check() 
                ? Favorite::where('content_id', $item->id)->where('user_id', auth()->id())->exists()
                : false;
        }
        
        return view('item', compact('content'));
    }

    public function toggleFavorite(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->back()->with('error', 'ログインが必要です');
        }

        $favorite = Favorite::where('content_id', $request->input('content_id'))
            ->where('user_id', auth()->id())
            ->first();

        if ($favorite) {
            $favorite->delete();
        } else {
            Favorite::create([
                'content_id' => $request->input('content_id'),
                'user_id' => auth()->id(),
            ]);
        }

        return redirect()->back();
    }

    public function address()
    {
        $profile = Profile::where('user_id', auth()->user()->id)->get();
        return view('address', compact('profile'));
    }

    public function addressUpdate(AddressRequest $request)
    {
        $profile = Profile::where('user_id', auth()->user()->id)->firstOrFail();

        $profile->postcode = $request->postcode;
        $profile->address = $request->address;
        $profile->building = $request->building;
        $profile->save();

        if ($request->filled('content_id')) {
            return redirect()->route('purchase.from.address', ['id' => $request->content_id])
                ->with('success', '住所を更新しました');
        }

        return redirect('/purchase')->with('success', '住所を更新しました');
    } 

    public function purchaseFromAddress($id)
    {
        $content = Content::where('id', $id)->firstOrFail();
        $profile = Profile::where('user_id', auth()->user()->id)->first();

        return view('purchase', compact('content', 'profile'));
    }

    public function setup()
    {
        return view('setup');
    }

    public function setup_create(ProfileRequest $request)
    {
        $profileData = [
            'user_id'  => auth()->id(),
            'name'     => $request->name,
            'postcode' => $request->postcode,
            'address'  => $request->address,
            'building' => $request->building,
        ];

        if ($request->hasFile('image')) {
            $storedPath = $request->file('image')->store('image', 'public');
            $profileData['image'] = 'storage/' . $storedPath;
        }

        Profile::create($profileData);
        return redirect('/');
    }

    public function commentStore(CommentRequest $request)
    {
        if (!auth()->check()) {
            return redirect()->back()->with('error', 'ログインが必要です');
        }

        Comment::create([
            'content_id' => $request->content_id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'コメントを投稿しました');
    }

    public function verifyEmail(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect('/login')->with('error', 'ログインが必要です');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect('/')->with('info', 'すでにメール認証が完了しています');
        }

        $user->markEmailAsVerified();
        return redirect('/setup')->with('success', 'メール認証が完了しました');
    }
}
