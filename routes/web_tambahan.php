<?php
// ─── TAMBAHKAN ini ke dalam blok Route::middleware('auth')->group() di routes/web.php ───

// Notifikasi
Route::get('/account/notification',        [NotificationController::class, 'index'])->name('notification');
Route::put('/account/notification',        [NotificationController::class, 'update'])->name('notification.update');
Route::post('/account/notification/test',  [NotificationController::class, 'testSend'])->name('notification.test');

// Keamanan
Route::get('/account/security',            [SecurityController::class, 'index'])->name('security');
Route::put('/account/security/password',   [SecurityController::class, 'updatePassword'])->name('security.password');
Route::delete('/account/security/sessions',[SecurityController::class, 'logoutOtherDevices'])->name('security.logout-others');
