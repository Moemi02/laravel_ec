<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemsController extends Controller
{
    public function index(){
        $title = '商品管理';

        // itemモデルを利用して一覧を取得
        $items = \App\Item::all();

        // views/items/index.blade.phpを指定
        return view('items.index',[
            'title' => $title,
            'items' => $items,
        ]);
    }

    public function store(Request $request){

        // requestオブジェクトのvalidateメソッドを利用。
        $request->validate([
            'name' => 'required|max:20', // nameは必須、20文字以内
            'price' => 'required|integer|min:1|max:1000000', 
            'stock' => 'required|integer|min:1|max:10000', 
            'image' => [
                'file',
                'image',
                'mimes:jpeg,png',
                'max:100'
            ], 
        ]);

        $filename = '';
        $image = $request->file('image');
        if( isset($image) === true ){
            // 拡張子を取得
            $ext = $image->guessExtension();
            // アップロードファイル名は [ランダム文字列20文字].[拡張子]
            $filename = str_random(20) . ".{$ext}";
            // publicディスク(storage/app/public/)のphotosディレクトリに保存
            $path = $image->storeAs('photos', $filename, 'public');
        }

        // itemモデルを利用して空のitemオブジェクトを作成
        $item = new \App\Item;

        // フォームから送られた値でname,price,stock,statusを設定
        $item->name = $request->name;
        $item->price = $request->price;
        $item->stock = $request->stock;
        $item->status = $request->status;
        $item->image = $filename;

        // messagesテーブルにINSERT
        $item->save();

        // メッセージ一覧ページにリダイレクト
        return redirect('/items');
    }

    // ルーティングパラメータは引数として取得可能。
    public function destroy($id){
        $item = \App\Item::find($id);
        $item->delete();
        return redirect('/items');
    }
}
