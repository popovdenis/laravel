<?php
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;
use App\Models\Course;
use Illuminate\Http\Request;
use Binafy\LaravelCart\Models\Cart;
use App\Http\Controllers\CartController;

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('courses.show');
Route::post('/cart/add/{course:slug}', function (Request $request, Course $course) {
    if (!auth()->check()) {
        session()->put('redirect_course_id', $course->slug);
        return redirect()->route('login');
    }
    $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

    $existing = $cart->items()
        ->where('itemable_id', $course->id)
        ->where('itemable_type', get_class($course))
        ->first();

    if ($existing) {
        $existing->increment('quantity');
    } else {
        $cart->storeItem($course);
    }

    return back()->with('success', 'Course added to cart');
})->name('cart.add');


Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::delete('/cart/remove/{item}', [CartController::class, 'destroy'])->name('cart.remove');
