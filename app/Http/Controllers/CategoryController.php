<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function addForm()
    {
        return view('category.add');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'image' => ['required', 'mimes:jpeg,jpg,png,gif'],
                'title_english' => 'required',
                'description_english' => 'required',
                'background_color' => 'required',
            ],
            [
                'image.required' => 'Image is required',
                'image.mimes' => 'This types of file is not allowed',
                'title_english.required' => 'English title is required',
                'description_english.required' => 'English description is required',
                'background_color.required' => 'Background color is required',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->store('public/category');
            $fileName = str_replace('public/','storage/',$fileName);
        }
        $data = [
            'image' => $fileName,
            'title_english' => $request->title_english,
            'title_japanese' => $request->title_japanese,
            'title_french' => $request->title_french,
            'title_spanish' => $request->title_spanish,
            'title_arabic' => $request->title_arabic,
            'description_english' => $request->description_english,
            'description_japanese' => $request->description_japanese,
            'description_french' => $request->description_french,
            'description_spanish' => $request->description_spanish,
            'description_arabic' => $request->description_arabic,
            'background_color' => $request->background_color,
            'multi_langual' => $request->multi_langual,
            'has_video' => $request->has_video,
            'play_audio_before' => $request->play_audio_before,
        ];
        $saved = DB::table('categories')->insert($data);
        if ($saved) {
            return redirect()->back()->with('success', 'Successfully saved!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function all(Request $request)
    {
        $keyword = $request->keyword;
        if (!empty($keyword)) {
            $rows = DB::table('categories')
                ->where('title_english', 'like', '%' . $keyword . '%')
                ->orderBy('id', 'desc')
                ->paginate(50);
        } else {
            $rows = DB::table('categories')
                ->orderBy('id', 'desc')
                ->paginate(50);
        }
        return view('category.index', compact('rows'));
    }

    public function edit($id)
    {
        $row = DB::table('categories')
            ->where('id', $id)
            ->first();
        return view('category.edit', compact('row'));
    }

    public function delete($id)
    {
        DB::table('categories')
            ->where('id', $id)
            ->delete();
        return redirect()->back()->with('success', 'Successfully Deleted');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'title_english' => 'required',
                'description_english' => 'required',
                'background_color' => 'required',
            ],
            [
                'title_english.required' => 'English title is required',
                'description_english.required' => 'English description is required',
                'background_color.required' => 'Background color is required',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->store('public/category');
            $fileName = str_replace('public/','storage/',$fileName);
            $data = [
                'image' => $fileName,
                'title_english' => $request->title_english,
                'title_japanese' => $request->title_japanese,
                'title_french' => $request->title_french,
                'title_spanish' => $request->title_spanish,
                'title_arabic' => $request->title_arabic,
                'description_english' => $request->description_english,
                'description_japanese' => $request->description_japanese,
                'description_french' => $request->description_french,
                'description_spanish' => $request->description_spanish,
                'description_arabic' => $request->description_arabic,
                'background_color' => $request->background_color,
                'multi_langual' => $request->multi_langual,
                'has_video' => $request->has_video,
                'play_audio_before' => $request->play_audio_before,
            ];
        }else{
            $data = [
                'title_english' => $request->title_english,
                'title_japanese' => $request->title_japanese,
                'title_french' => $request->title_french,
                'title_spanish' => $request->title_spanish,
                'title_arabic' => $request->title_arabic,
                'description_english' => $request->description_english,
                'description_japanese' => $request->description_japanese,
                'description_french' => $request->description_french,
                'description_spanish' => $request->description_spanish,
                'description_arabic' => $request->description_arabic,
                'background_color' => $request->background_color,
                'multi_langual' => $request->multi_langual,
                'has_video' => $request->has_video,
                'play_audio_before' => $request->play_audio_before,
            ];
        }
        $saved = DB::table('categories')->where('id', $request->id)->update($data);
        if ($saved) {
            return redirect()->back()->with('success', 'Successfully updated!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }
}
