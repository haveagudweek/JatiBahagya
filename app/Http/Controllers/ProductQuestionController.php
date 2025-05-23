<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductQuestionController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'question' => 'required|string|max:1000'
        ]);

        $question = $product->questions()->create([
            'user_id' => Auth::id(),
            'question' => $request->question
        ]);

        return back()->with('status', 'Pertanyaan Anda telah berhasil dikirim!');
    }

    // Untuk admin
    public function answer(Request $request, ProductQuestion $question)
    {
        $request->validate([
            'answer' => 'required|string|max:1000'
        ]);

        $question->update([
            'answer' => $request->answer,
            'answered_by' => Auth::id(),
            'answered_at' => now()
        ]);

        return back()->with('status', 'Jawaban berhasil disimpan!');
    }
}