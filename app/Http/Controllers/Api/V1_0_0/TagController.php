<?php

namespace App\Http\Controllers\Api\V1_0_0;

use App\Actions\V1_0_0\Tag\DeleteTag;
use App\Actions\V1_0_0\Tag\UpdateTag;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1_0_0\TagResource;
use App\Models\Tag;
use App\Models\Task;
use App\Repositories\Tag\Contracts\TagInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TagController extends Controller
{

    /**
     * Create new Controller instance
     */
    public function __construct(
        private TagInterface $tagInterface
    )
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = request()->user();
        $tags = $this->tagInterface->getUserTags($user->id);

        return TagResource::collection($tags);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => [
                'required',
                Rule::unique('tags', 'name')
                    ->where(function ($query) use($user){
                        return $query->where('user_id', $user->id);
                    })
            ]
        ]);

        $tag = $user->tags()->create($data);

        return TagResource::make($tag);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        abort_if(request()->user()->cannot('view', $tag), 401, 'You are not authorized to view this tag');

        return TagResource::make($tag);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        abort_if($request->user()->cannot('update', $tag), 401, 'You are not authorized to update this tag');

        $user = $request->user();
        $data = $request->validate([
            'name' => [ 'required',
                Rule::unique('tags', 'name')
                    ->where(function ($query) use($user,$tag){
                        return $query->where('user_id', $user->id)->where('id', '<>', $tag->id);
                    })
            ]
        ]);

        $tag = (new UpdateTag)($tag, $data);

        return TagResource::make($tag);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        abort_if(request()->user()->cannot('delete', $tag), 401, 'You are not authorized to delete this tag');

        (new DeleteTag)($tag);

        return response('',204);

    }
}
