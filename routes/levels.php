<?php
use App\Http\Controllers\LanguageLevelController;
use Illuminate\Support\Facades\Route;
use App\Models\LanguageLevel;
use Illuminate\Http\Request;
use Binafy\LaravelCart\Models\Cart;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Flow\TeacherSelectionController;
use App\Http\Controllers\Flow\TimeslotSelectionController;
use App\Http\Controllers\Flow\CheckoutController;
use App\Http\Controllers\Flow\ConfirmationController;

Route::get('/levels', [LanguageLevelController::class, 'index'])->name('levels.index');
Route::get('/levels/{level:slug}', [LanguageLevelController::class, 'show'])->name('levels.show');
Route::post('/cart/add/{level:slug}', function (Request $request, LanguageLevel $course) {
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

Route::post('/flow/select-teacher', [TeacherSelectionController::class, 'store'])
    ->name('flow.selectTeacher.store');
Route::get('/flow/select-teacher', [TeacherSelectionController::class, 'index'])
    ->name('flow.selectTeacher.index');
Route::post('/flow/select-timeslot', [TimeslotSelectionController::class, 'store'])
    ->name('flow.selectTimeslot.store');
Route::get('/flow/select-timeslot', [TimeslotSelectionController::class, 'index'])
    ->name('flow.selectTimeslot.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('flow.checkout.store');
Route::get('/checkout', [CheckoutController::class, 'show'])->name('flow.checkout.show');
Route::post('/checkout/process', [ConfirmationController::class, 'store'])->name('checkout.process');
Route::get('/checkout/confirmed', [ConfirmationController::class, 'confirmed'])->name('checkout.confirmed');
