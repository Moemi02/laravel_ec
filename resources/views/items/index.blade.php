@extends('layouts.default')

@section('title', $title)

@section('content')
    <h1>{{ $title }}</h1>

    {{-- エラーメッセージを出力 --}}
    @foreach($errors->all() as $error)
    <p class="error">{{ $error }}</p>
    @endforeach

    {{-- 以下にフォームを追記します。 --}}
    <form method="post" action="{{ url("/items") }}" enctype="multipart/form-data">
      {{-- LaravelではCSRF対策のため以下の一行が必須です。 --}}
      {{ csrf_field() }}
      <div>
        <label>
          名前:
          <input type="text" name="name" class="name_field" placeholder="商品名を入力">
        </label>
      </div>

      <div>
        <label>
          価格：
          <input type="text" name="price" class="price_field" placeholder="価格を入力">
        </label>
      </div>

      <div>
        <label>
          在庫数：
          <input type="text" name="stock" class="stock_field" placeholder="在庫数を入力">
        </label>
      </div>

      <div>
        <label>
          ステータス：
          <select name="status" class="status_field">
            <option value="0">非公開</option>
            <option value="1">公開</option>
          </select>  
        </label>
      </div>

      <div>
        <label>
          画像：
          <input type="file" name="image">
        </label>
        </div>

      <div>
        <input type="submit" value="投稿">
      </div>
    </form>
    
    <table>
      <tr>
        <th>商品画像</th>
        <th>商品名</th>
        <th>価格</th>
        <th>在庫数</th>
        <th>操作</th>
      </tr>
  @forelse($items as $item)
    @if($item->status === 1)
      <tr>
    @else
      <tr class="status_false">
    @endif    
        <td class="img-width">
          @if($item->image !== '')
            <img src="{{ asset('storage/photos/' . $item->image) }}">
          @else
            <img src="/images/no_image.png">
          @endif
        </td>       
        <td class="item_name_width">
          商品名:{{ $item->name }}
        </td>
        <td class="text_align_right">
          {{ $item->price }}円 
        </td> 
        <td class="input_text_width text_align_right"> 
          {{ $item->stock }}個 
        </td>
        {{-- RESTに基づく書き換え --}}
        <td>
          <form method="post" action="{{ url('/items/' . $item->id) }}">
            {{ csrf_field() }}
            {{ method_field('delete')}}
              <input type="submit" value="削除"> 
          </form>
        </td>   
      </tr>  
  @empty
    <li>メッセージはありません。</li>
  @endforelse
  </table>
@endsection