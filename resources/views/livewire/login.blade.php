<div style="display: flex; justify-content: center; align-items: center; min-height: 70vh;">
    <div class="card" style="width: 400px;">
        <h2 style="text-align: center; margin-bottom: 20px;">تسجيل الدخول للنظام</h2>
        
        <form wire:submit.prevent="login">
            <div>
                <label>البريد الإلكتروني</label>
                <input type="email" wire:model="email" placeholder="admin@hayat.com">
                @error('email') <span style="color: #e74c3c; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-top: 15px;">
                <label>كلمة المرور</label>
                <input type="password" wire:model="password">
                @error('password') <span style="color: #e74c3c; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 25px; padding: 12px;">دخول</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px; color: #666; font-size: 0.9rem;">
            استخدم البيانات المسجلة في السيرفر للدخول
        </p>
    </div>
</div>
