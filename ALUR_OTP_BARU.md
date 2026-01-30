# 📱 ALUR OTP BARU - IMPROVED UX

## 🎯 Perubahan Alur

### **Alur Lama** ❌
```
Step 1 Substep 6 → Submit 
    ↓ 
Step 2 → Auto-kirim OTP (langsung)
    ↓
Tampilkan form input OTP
```

**Masalah:**
- User tidak bisa confirm nomor WA dulu
- OTP langsung terkirim tanpa user tahu
- Tidak ada control dari user

---

### **Alur Baru** ✅
```
Step 1 Substep 6 → Submit
    ↓
Step 2 - Initial State:
    - Tampilkan konfirmasi: "OTP akan dikirim ke: 08123456789"
    - Button: "Kirim OTP"
    - (BELUM kirim OTP, hanya konfirmasi)
    ↓
User klik "Kirim OTP"
    ↓
System generate & send OTP via WhatsApp
    ↓
Tampilan berubah:
    - Form input OTP 6 digit
    - Button "Kirim Ulang" (dengan cooldown 60 detik)
    - Informasi: "Kode OTP telah dikirim ke 08123456789"
    ↓
User input OTP → Verify → Step 3 (PIN)
```

**Keuntungan:**
- ✅ User bisa confirm nomor WA sebelum OTP dikirim
- ✅ User yang trigger send OTP (lebih controlled)
- ✅ Lebih jelas flow-nya
- ✅ Jika nomor salah, user bisa kembali ke Step 1

---

## 🔧 Backend Implementation (DONE)

### Variables yang Dikirim ke View:

```php
return view('auth.register', [
    'step' => 2,
    'phone' => '08123456789',           // Nomor WA dari user_temp
    'otpSent' => false/true,            // Flag: apakah OTP sudah dikirim?
    'remainingCooldown' => 0/60,        // Detik remaining untuk resend
]);
```

### Form Actions:

#### 1. **Button "Kirim OTP"** (Initial State)
```html
<form method="POST" action="{{ route('register.submit') }}">
    @csrf
    <input type="hidden" name="step" value="2">
    <input type="hidden" name="send_otp" value="1">
    
    <button type="submit">Kirim OTP</button>
</form>
```

#### 2. **Form Input OTP** (After OTP Sent)
```html
<form method="POST" action="{{ route('register.submit') }}">
    @csrf
    <input type="hidden" name="step" value="2">
    <input type="text" name="otp_code" maxlength="6" required>
    
    <button type="submit">Verifikasi OTP</button>
</form>
```

#### 3. **Button "Kirim Ulang"** (Resend)
```html
<form method="POST" action="{{ route('register.submit') }}">
    @csrf
    <input type="hidden" name="step" value="2">
    <input type="hidden" name="send_otp" value="1">
    
    <button type="submit" @if($remainingCooldown > 0) disabled @endif>
        Kirim Ulang @if($remainingCooldown > 0) ({{ $remainingCooldown }}s) @endif
    </button>
</form>
```

---

## 📄 Frontend View Implementation

### File: `resources/views/auth/register.blade.php`

Tambahkan logic untuk Step 2 di blade view:

```blade
@if($step == 2)
    <div class="otp-verification-section">
        <h2>Verifikasi Nomor WhatsApp</h2>
        
        {{-- State 1: Belum kirim OTP - Tampilkan konfirmasi --}}
        @if(!$otpSent)
            <div class="otp-confirmation">
                <p class="info-text">
                    Kode OTP akan dikirim ke nomor WhatsApp:
                </p>
                <p class="phone-number">
                    <strong>{{ $phone }}</strong>
                </p>
                
                <p class="small-text">
                    Pastikan nomor WhatsApp Anda aktif dan dapat menerima pesan.
                </p>
                
                {{-- Button Kirim OTP --}}
                <form method="POST" action="{{ route('register.submit') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="step" value="2">
                    <input type="hidden" name="send_otp" value="1">
                    
                    <button type="submit" class="btn btn-primary btn-lg">
                        📱 Kirim Kode OTP
                    </button>
                </form>
                
                {{-- Link kembali jika nomor salah --}}
                <div class="mt-3">
                    <a href="{{ route('register', ['step' => 1, 'substep' => 1]) }}" class="text-muted">
                        ← Nomor salah? Kembali ke Step 1
                    </a>
                </div>
            </div>
        
        {{-- State 2: OTP sudah dikirim - Tampilkan form input --}}
        @else
            <div class="otp-input-form">
                <p class="success-text">
                    ✅ Kode OTP telah dikirim ke WhatsApp nomor <strong>{{ $phone }}</strong>
                </p>
                
                <p class="info-text">
                    Silakan cek pesan WhatsApp Anda dan masukkan kode OTP 6 digit.
                </p>
                
                {{-- Form Input OTP --}}
                <form method="POST" action="{{ route('register.submit') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="step" value="2">
                    
                    <div class="form-group">
                        <label for="otp_code">Kode OTP</label>
                        <input 
                            type="text" 
                            name="otp_code" 
                            id="otp_code"
                            class="form-control form-control-lg text-center @error('otp_code') is-invalid @enderror"
                            maxlength="6"
                            pattern="[0-9]{6}"
                            placeholder="000000"
                            required
                            autofocus
                        >
                        @error('otp_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg btn-block mt-3">
                        Verifikasi OTP
                    </button>
                </form>
                
                {{-- Button Kirim Ulang --}}
                <form method="POST" action="{{ route('register.submit') }}" class="mt-3">
                    @csrf
                    <input type="hidden" name="step" value="2">
                    <input type="hidden" name="send_otp" value="1">
                    
                    <button 
                        type="submit" 
                        class="btn btn-link"
                        @if($remainingCooldown > 0) disabled @endif
                    >
                        @if($remainingCooldown > 0)
                            🔄 Kirim Ulang ({{ $remainingCooldown }}s)
                        @else
                            🔄 Kirim Ulang Kode OTP
                        @endif
                    </button>
                </form>
                
                {{-- Info --}}
                <div class="alert alert-info mt-3">
                    <small>
                        💡 Kode OTP berlaku selama 5 menit.<br>
                        Jika belum menerima, tunggu {{ $remainingCooldown > 0 ? $remainingCooldown : 0 }} detik untuk kirim ulang.
                    </small>
                </div>
            </div>
        @endif
        
        {{-- Error Messages --}}
        @if(session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif
        
        {{-- Success Messages --}}
        @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif
    </div>
@endif
```

---

## 🎨 Styling Suggestion

```css
/* OTP Verification Section */
.otp-verification-section {
    max-width: 500px;
    margin: 0 auto;
    padding: 2rem;
}

.otp-confirmation {
    text-align: center;
}

.phone-number {
    font-size: 1.5rem;
    color: #25D366; /* WhatsApp green */
    font-weight: bold;
    margin: 1rem 0;
}

.info-text {
    color: #6c757d;
    margin: 1rem 0;
}

.small-text {
    font-size: 0.875rem;
    color: #999;
}

/* OTP Input */
#otp_code {
    font-size: 2rem;
    letter-spacing: 0.5rem;
    font-weight: bold;
}

/* Countdown Timer for Resend Button */
button[disabled] {
    opacity: 0.5;
    cursor: not-allowed;
}
```

---

## 🧪 Testing Checklist

### ✅ Test Case 1: Initial Landing
1. Complete Step 1 (all 6 substeps)
2. Submit Step 1 Substep 6
3. **Expected**: Redirect to Step 2
4. **Expected**: See confirmation page with phone number
5. **Expected**: See "Kirim OTP" button
6. **Expected**: NO OTP sent yet

### ✅ Test Case 2: Send OTP
1. Click "Kirim OTP" button
2. **Expected**: Success message "Kode OTP telah dikirim..."
3. **Expected**: View changes to show OTP input form
4. **Expected**: Receive WhatsApp message with OTP code

### ✅ Test Case 3: Input Correct OTP
1. Input the OTP code received
2. Click "Verifikasi OTP"
3. **Expected**: Success message "Nomor HP berhasil diverifikasi"
4. **Expected**: Redirect to Step 3 (PIN creation)

### ✅ Test Case 4: Input Wrong OTP
1. Input wrong OTP (e.g., 000000)
2. Click "Verifikasi OTP"
3. **Expected**: Error message "Kode OTP tidak valid"
4. **Expected**: Stay on Step 2

### ✅ Test Case 5: Resend OTP
1. Wait for cooldown (60 seconds)
2. Click "Kirim Ulang"
3. **Expected**: New OTP sent
4. **Expected**: Old OTP no longer valid

### ✅ Test Case 6: Cooldown
1. Send OTP
2. Immediately try to resend
3. **Expected**: Button disabled with countdown timer
4. **Expected**: Can resend after 60 seconds

### ✅ Test Case 7: Wrong Phone Number
1. See confirmation page with wrong number
2. Click "← Nomor salah? Kembali ke Step 1"
3. **Expected**: Go back to Step 1 Substep 1
4. **Expected**: Can edit phone number

---

## 📊 Backend Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                  STEP 2 OTP - BACKEND FLOW                   │
└─────────────────────────────────────────────────────────────┘

REQUEST TYPE                    ACTION                  RESPONSE
─────────────────────────────────────────────────────────────────

GET /register?step=2       →   Check session         →  View with:
                                - otpSent = false         - phone number
                                - remainingCooldown = 0   - "Kirim OTP" btn
                                                          - confirmation text

POST /register             →   Generate OTP          →  Redirect with:
  step=2                        Save to tbl_otp          - success message
  send_otp=1                    Send via WhatsApp        - otpSent = true
                                Set session('otp_sent_at')

GET /register?step=2       →   Check session         →  View with:
(after OTP sent)                - otpSent = true          - OTP input form
                                - calc cooldown           - "Kirim Ulang" btn
                                                          - cooldown timer

POST /register             →   Verify OTP:           →  Success:
  step=2                        - Check code              - Redirect Step 3
  otp_code=123456               - Check expired          Error:
                                - Check used              - Stay Step 2
                                - Update is_verified      - Show error msg

POST /register             →   Resend OTP:           →  Success:
  step=2                        - Invalidate old          - New OTP sent
  send_otp=1                    - Generate new            - Stay Step 2
(resend)                        - Send WhatsApp          Error:
                                                          - Show error msg
```

---

## ✅ Completed

- [x] Backend controller updated
- [x] Removed auto-send OTP on first landing
- [x] Added confirmation page before OTP send
- [x] User must click "Kirim OTP" to trigger
- [x] Added `otpSent` flag to view
- [x] Added `remainingCooldown` to view
- [x] Improved logging for debugging
- [x] Better error handling

---

## 📝 Next Steps for You

1. **Update Frontend View** (`resources/views/auth/register.blade.php`):
   - Add conditional rendering for Step 2
   - Show confirmation page when `$otpSent = false`
   - Show OTP input form when `$otpSent = true`
   - Add button "Kirim OTP" and "Kirim Ulang"
   - Add countdown timer for cooldown

2. **Optional Enhancements**:
   - Add JavaScript countdown timer (real-time update)
   - Add auto-submit on 6 digits entered
   - Add "Change Number" link to go back to Step 1
   - Add OTP input field with 6 separate boxes (better UX)

---

**Dibuat**: 30 Januari 2026  
**Status**: ✅ Backend Ready, Frontend Needed
