# 🎨 STRUKTUR STYLING & TEKNIS SISTEM TABUNGAN KOPERASI MAJAKARA

## 📋 DAFTAR ISI
1. [Design System](#design-system)
2. [Struktur File & Folder](#struktur-file--folder)
3. [Layout System](#layout-system)
4. [Component Styling](#component-styling)
5. [Form Styling](#form-styling)
6. [Table Styling](#table-styling)
7. [Modal & Dialog](#modal--dialog)
8. [Badge & Status](#badge--status)
9. [Button Styling](#button-styling)
10. [Card Components](#card-components)
11. [JavaScript Patterns](#javascript-patterns)
12. [Responsive Design](#responsive-design)

---

## 🎨 DESIGN SYSTEM

### Color Palette
```css
:root {
    --primary: #674c1d;        /* Brown - Primary brand color */
    --primary-light: #8b6f2f;  /* Light Brown */
    --primary-dark: #4a3514;   /* Dark Brown */
    --accent: #d4af37;         /* Gold - Accent color */
    --bg-light: #faf9f6;       /* Light Beige - Background */
}
```

### Color Usage Guide
- **Primary (#674c1d)**: Headings, primary buttons, active states
- **Primary Light (#8b6f2f)**: Gradients, hover states
- **Primary Dark (#4a3514)**: Text, dark backgrounds
- **Accent (#d4af37)**: Highlights, special elements, gold accents
- **BG Light (#faf9f6)**: Page background, light sections

### Typography
```css
/* Font Families */
body {
    font-family: 'Inter', sans-serif;  /* Body text */
}

.font-display {
    font-family: 'Playfair Display', serif;  /* Headings, titles */
}

/* Font Sizes */
- Display Heading: text-4xl md:text-5xl (36-48px)
- Page Title: text-3xl (30px)
- Section Title: text-2xl (24px)
- Card Title: text-lg (18px)
- Body Text: text-base (16px)
- Small Text: text-sm (14px)
- Extra Small: text-xs (12px)
```

### Spacing System
```css
/* Consistent spacing using Tailwind scale */
- xs: p-2, m-2 (8px)
- sm: p-3, m-3 (12px)
- md: p-4, m-4 (16px)
- lg: p-6, m-6 (24px)
- xl: p-8, m-8 (32px)

/* Component spacing */
- Card padding: p-6 (24px)
- Section margin: mb-6 (24px)
- Element gap: gap-3, gap-4 (12px, 16px)
```

### Border Radius
```css
- Small: rounded-lg (8px) - Buttons, inputs, badges
- Medium: rounded-xl (12px) - Cards, form fields
- Large: rounded-2xl (16px) - Main cards, sections
- Extra Large: rounded-3xl (24px) - Hero sections
```

### Shadows
```css
- Small: shadow-md - Hover states
- Medium: shadow-lg - Cards
- Large: shadow-xl - Elevated cards
- XL: shadow-2xl - Hero sections, modals
```

---

## 📁 STRUKTUR FILE & FOLDER

### Directory Structure
```
resources/views/
├── layouts/
│   ├── nasabah.blade.php          # Layout untuk nasabah
│   └── admin.blade.php            # Layout untuk admin
│
├── components/
│   ├── nasabah/
│   │   ├── header.blade.php       # Header nasabah
│   │   ├── bottom-navbar.blade.php # Bottom navigation
│   │   └── tabungan/
│   │       └── filter-tabungan.blade.php  # Filter component
│   └── admin/
│       ├── sidebar.blade.php
│       └── header.blade.php
│
├── nasabah/
│   └── tabungan/
│       ├── index.blade.php                 # Dashboard tabungan
│       ├── nabung-sekarang.blade.php       # Menu utama setoran
│       ├── pengajuan-transfer.blade.php    # Form pengajuan transfer
│       ├── janji-temu.blade.php           # Form janji temu
│       ├── penarikan-tabungan.blade.php   # Form penarikan
│       ├── status-pengajuan-setor.blade.php
│       ├── status-pengajuan-tarik.blade.php
│       ├── detail-pengajuan-setor.blade.php
│       ├── detail-pengajuan-tarik.blade.php
│       ├── detail-transaksi.blade.php
│       └── detail-janji-temu.blade.php
│
└── admin/
    └── tabungan/
        ├── index.blade.php                 # Dashboard admin
        ├── pengajuan-setor.blade.php      # List pengajuan setor
        ├── pengajuan-tarik.blade.php      # List pengajuan tarik
        ├── detail-pengajuan-setor.blade.php
        ├── detail-pengajuan-tarik.blade.php
        ├── transaksi.blade.php            # List transaksi
        ├── detail-transaksi.blade.php
        ├── janji-temu.blade.php           # List janji temu
        ├── detail-janji-temu.blade.php
        └── saldo-nasabah.blade.php        # List saldo
```

### Controllers
```
app/Http/Controllers/
├── Nasabah/
│   └── TabunganController.php
└── Admin/
    └── TabunganController.php
```

### Models
```
app/Models/
├── PengajuanTabungan.php
├── PengajuanPenarikanTabungan.php
├── TransTabungan.php
├── BuktiFotoTabungan.php
└── JanjiTemuTabungan.php
```

---

## 🏗️ LAYOUT SYSTEM

### Nasabah Layout (`layouts/nasabah.blade.php`)

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Title -->
    <title>@yield('title', 'Dashboard') - Koperasi Majakara</title>
    
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&family=playfair-display:700" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary: #674c1d;
            --primary-light: #8b6f2f;
            --primary-dark: #4a3514;
            --accent: #d4af37;
            --bg-light: #faf9f6;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            padding-bottom: 80px; /* Space for bottom navbar */
        }
        
        .font-display {
            font-family: 'Playfair Display', serif;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-[#faf9f6]">
    <!-- Header Component -->
    <x-nasabah.header />
    
    <!-- Main Content -->
    <main class="w-full overflow-x-hidden">
        @yield('content')
    </main>
    
    <!-- Bottom Navbar Component -->
    <x-nasabah.bottom-navbar />
    
    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
```

### Layout Features:
- ✅ Mobile-first responsive design
- ✅ Custom CSS variables for theming
- ✅ Modular component system (header, navbar)
- ✅ Stack sections for page-specific styles/scripts
- ✅ Fixed bottom navigation with padding compensation

---

## 🎨 COMPONENT STYLING

### Hero Section Pattern
```html
<!-- Gradient Card dengan Pattern Background -->
<div class="mx-4 mt-4 mb-6">
    <div class="bg-gradient-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] 
                rounded-3xl shadow-2xl p-8 border-2 border-[#d4af37]/30 
                relative overflow-hidden">
        
        <!-- Background Pattern (Optional) -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full -ml-24 -mb-24"></div>
        </div>
        
        <!-- Content (dengan z-10 untuk di atas pattern) -->
        <div class="relative z-10">
            <!-- Hero content here -->
        </div>
    </div>
</div>
```

**Usage:**
- Dashboard saldo cards
- Page headers
- Important information sections

---

### White Card Pattern
```html
<!-- Standard White Card -->
<div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
    <!-- Card Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] 
                        rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-white">...</svg>
            </div>
            <h2 class="text-lg font-bold text-[#674c1d] font-display">Card Title</h2>
        </div>
    </div>
    
    <!-- Card Content -->
    <div class="space-y-4">
        <!-- Content here -->
    </div>
</div>
```

**Features:**
- White background dengan border subtle
- Icon container dengan gradient
- Consistent spacing
- Title dengan font display

---

### Icon Container Pattern
```html
<!-- Small Icon (w-10 h-10) -->
<div class="w-10 h-10 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] 
            rounded-xl flex items-center justify-center">
    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <!-- Icon path -->
    </svg>
</div>

<!-- Medium Icon (w-12 h-12) -->
<div class="w-12 h-12 bg-gradient-to-br from-[#674c1d]/20 to-[#8b6f2f]/20 
            rounded-lg flex items-center justify-center">
    <svg class="w-6 h-6 text-[#674c1d]">...</svg>
</div>

<!-- Large Icon (w-14 h-14) -->
<div class="w-14 h-14 bg-gradient-to-br from-[#674c1d]/20 to-[#674c1d]/10 
            rounded-xl flex items-center justify-center">
    <svg class="w-7 h-7 text-[#674c1d]">...</svg>
</div>
```

**Size Guide:**
- Small (w-10): List items, table icons
- Medium (w-12): Card headers, feature items
- Large (w-14): Statistics cards, hero sections

---

## 📝 FORM STYLING

### Input Field Pattern
```html
<!-- Text Input -->
<div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">
        Label Text *
    </label>
    <input type="text" 
           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl 
                  focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 
                  outline-none"
           placeholder="Enter value">
    <p class="text-xs text-gray-500 mt-2">Helper text</p>
</div>

<!-- Currency Input (dengan prefix) -->
<div class="relative">
    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
    <input type="text" 
           class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl 
                  focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 
                  outline-none text-lg font-semibold"
           placeholder="0"
           oninput="formatCurrency(this)">
</div>

<!-- Textarea -->
<textarea rows="3" 
          class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl 
                 focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 
                 outline-none resize-none"
          placeholder="Enter description..."></textarea>

<!-- Select Dropdown -->
<select class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl 
               focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 
               outline-none">
    <option value="">Pilih opsi</option>
    <option value="1">Option 1</option>
</select>

<!-- File Upload -->
<div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center 
            hover:border-[#674c1d] transition-colors cursor-pointer">
    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3">...</svg>
    <p class="text-sm text-gray-600">Klik untuk upload file</p>
    <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG (Max 5MB)</p>
    <input type="file" class="hidden" accept="image/*">
</div>
```

**Key Features:**
- Border 2px untuk visibility yang lebih baik
- Focus state dengan border color + ring
- Rounded-xl untuk konsistensi
- Helper text dengan text-xs
- Placeholder dengan warna gray yang jelas

---

### Form Validation States
```html
<!-- Success State -->
<input class="... border-green-500 focus:ring-green-500/20">
<p class="text-xs text-green-600 mt-2">✓ Valid input</p>

<!-- Error State -->
<input class="... border-red-500 focus:ring-red-500/20">
<p class="text-xs text-red-600 mt-2">✗ Error message</p>

<!-- Warning State -->
<input class="... border-yellow-500 focus:ring-yellow-500/20">
<p class="text-xs text-yellow-600 mt-2">⚠ Warning message</p>
```

---

## 📊 TABLE STYLING

### Standard Table Pattern
```html
<div class="overflow-x-auto">
    <table class="w-full">
        <!-- Table Header -->
        <thead>
            <tr class="border-b-2 border-[#674c1d]/20">
                <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">
                    Column 1
                </th>
                <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">
                    Column 2
                </th>
            </tr>
        </thead>
        
        <!-- Table Body -->
        <tbody>
            <tr class="border-b border-gray-100 hover:bg-gray-50 
                       transition-colors cursor-pointer">
                <td class="px-4 py-3 text-sm">
                    <p class="font-medium text-gray-900">Content</p>
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">
                    Content
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Empty State -->
<tr>
    <td colspan="5" class="px-4 py-12 text-center">
        <div class="flex flex-col items-center gap-3">
            <svg class="w-16 h-16 text-gray-300">...</svg>
            <p class="text-gray-500">Tidak ada data</p>
        </div>
    </td>
</tr>
```

**Features:**
- Overflow-x-auto untuk responsive
- Header dengan uppercase text + border bottom
- Hover effect pada row
- Cursor pointer untuk clickable rows
- Empty state yang informatif

---

### Admin Table Pattern (dengan gradient)
```html
<table class="w-full">
    <thead>
        <tr class="border-b-2 border-[#674c1d]/20 
                   bg-gradient-to-r from-[#674c1d]/5 to-[#8b6f2f]/5">
            <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">
                Header
            </th>
        </tr>
    </thead>
    <tbody>
        <tr class="border-b border-gray-100 
                   hover:bg-gradient-to-r hover:from-[#674c1d]/5 hover:to-[#8b6f2f]/5 
                   transition-all">
            <td class="px-4 py-3">...</td>
        </tr>
    </tbody>
</table>
```

---

## 🔔 MODAL & DIALOG

### Modal Pattern
```html
<!-- Modal Overlay (hidden by default) -->
<div id="modal-id" 
     class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 
            hidden items-center justify-center p-4">
    
    <!-- Modal Container -->
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 
                transform transition-all">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] 
                            rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white">...</svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Modal Title</h3>
                    <p class="text-sm text-gray-600">Subtitle</p>
                </div>
            </div>
            <button onclick="closeModal()" 
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6">...</svg>
            </button>
        </div>
        
        <!-- Modal Content -->
        <div class="mb-6">
            <!-- Content here -->
        </div>
        
        <!-- Modal Actions -->
        <div class="flex gap-3">
            <button onclick="closeModal()" 
                    class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 
                           rounded-xl font-semibold hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button class="flex-1 px-4 py-3 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] 
                           text-white rounded-xl font-semibold 
                           hover:from-[#4a3514] hover:to-[#674c1d] transition-all">
                Konfirmasi
            </button>
        </div>
    </div>
</div>
```

**JavaScript for Modal:**
```javascript
function showModal() {
    document.getElementById('modal-id').classList.remove('hidden');
    document.getElementById('modal-id').classList.add('flex');
}

function closeModal() {
    document.getElementById('modal-id').classList.add('hidden');
    document.getElementById('modal-id').classList.remove('flex');
}

// Close on outside click
document.getElementById('modal-id').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
```

---

### PIN Modal Pattern
```html
<div id="pin-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 
                           hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] 
                            rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white">
                        <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Verifikasi PIN</h3>
                    <p class="text-sm text-gray-600">Masukkan PIN Anda</p>
                </div>
            </div>
        </div>
        
        <!-- Error Alert -->
        <div id="pin-error" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-700 text-sm"></p>
        </div>
        
        <!-- PIN Input -->
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">PIN (6 digit)</label>
            <input type="text" id="pin-input" 
                   maxlength="6" 
                   pattern="[0-9]*" 
                   inputmode="numeric"
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl 
                          focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 
                          outline-none text-center text-2xl font-mono tracking-widest"
                   placeholder="••••••"
                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
        </div>
        
        <!-- Actions -->
        <div class="flex gap-3">
            <button onclick="closePinModal()" 
                    class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 
                           rounded-xl font-semibold hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button onclick="verifyAndSubmit()" 
                    class="flex-1 px-4 py-3 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] 
                           text-white rounded-xl font-semibold transition-all">
                Verifikasi
            </button>
        </div>
    </div>
</div>
```

---

## 🏷️ BADGE & STATUS

### Badge Patterns
```html
<!-- Status Badge - Pending (Yellow) -->
<span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
    Pending
</span>

<!-- Status Badge - Approved (Green) -->
<span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
    Approved
</span>

<!-- Status Badge - Rejected (Red) -->
<span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
    Rejected
</span>

<!-- Jenis Badge - Setoran (Green) -->
<span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
    Setoran
</span>

<!-- Jenis Badge - Penarikan (Red) -->
<span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
    Penarikan
</span>

<!-- Type Badge - Transfer (Info Blue) -->
<span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
    Transfer
</span>

<!-- Type Badge - Cash (Success Green) -->
<span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
    Cash
</span>

<!-- Admin Badge dengan Gradient Background -->
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 
             bg-[#674c1d]/10 text-[#674c1d] rounded-lg text-xs font-medium">
    Setoran
</span>

<!-- Count Badge -->
<span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
    5
</span>
```

**Color Guide:**
- Yellow: Pending, Waiting
- Green: Approved, Success, Setoran, Cash
- Red: Rejected, Failed, Penarikan
- Blue: Info, Transfer
- Gray: Inactive, Disabled

---

## 🔘 BUTTON STYLING

### Primary Button
```html
<!-- Full Gradient Primary Button -->
<button class="w-full py-4 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] 
               text-white rounded-xl font-bold text-lg shadow-lg 
               hover:shadow-xl transition-all transform hover:scale-[1.02]">
    Primary Action
</button>

<!-- Primary Button (normal size) -->
<button class="px-6 py-3 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] 
               text-white rounded-xl font-semibold 
               hover:from-[#4a3514] hover:to-[#674c1d] transition-all">
    Submit
</button>
```

### Secondary Button
```html
<!-- White Button with Border -->
<button class="px-4 py-2 bg-white border border-gray-300 rounded-lg 
               text-gray-700 hover:bg-gray-50 transition-colors 
               text-sm font-medium">
    Secondary Action
</button>

<!-- Gray Button -->
<button class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 
               rounded-xl font-semibold hover:bg-gray-50 transition-colors">
    Cancel
</button>
```

### Icon Button
```html
<!-- Icon Only -->
<button class="p-2 text-[#674c1d] hover:bg-[#674c1d]/10 rounded-lg transition-colors">
    <svg class="w-5 h-5">...</svg>
</button>

<!-- Icon with Text -->
<button class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 
               rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
    <svg class="w-5 h-5">...</svg>
    <span>Button Text</span>
</button>
```

### Link Button
```html
<!-- Text Link -->
<a href="#" class="text-sm text-[#674c1d] hover:underline font-medium">
    Link Text →
</a>

<!-- Block Link (full width) -->
<a href="#" class="block w-full mt-3 py-3 text-center text-gray-600 
                   hover:text-gray-800 transition-colors">
    Kembali
</a>
```

### Button States
```html
<!-- Disabled -->
<button disabled class="... opacity-50 cursor-not-allowed">
    Disabled
</button>

<!-- Loading -->
<button class="... relative">
    <span class="opacity-0">Button Text</span>
    <span class="absolute inset-0 flex items-center justify-center">
        <svg class="animate-spin h-5 w-5">...</svg>
    </span>
</button>
```

---

## 📦 CARD COMPONENTS

### Statistics Card
```html
<div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
    <!-- Icon & Badge -->
    <div class="flex items-center justify-between mb-4">
        <div class="w-14 h-14 bg-gradient-to-br from-[#674c1d]/20 to-[#674c1d]/10 
                    rounded-xl flex items-center justify-center">
            <svg class="w-7 h-7 text-[#674c1d]">...</svg>
        </div>
        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
            5
        </span>
    </div>
    
    <!-- Content -->
    <h3 class="text-sm font-medium text-gray-600 mb-1">Title</h3>
    <p class="text-3xl font-bold text-[#674c1d] mb-1">
        {{ number_format($value, 0, ',', '.') }}
    </p>
    <p class="text-xs text-gray-500">Subtitle</p>
</div>
```

### List Item Card
```html
<div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl 
            hover:bg-gray-100 transition-colors border border-gray-200">
    <!-- Left Side -->
    <div class="flex items-center space-x-4 flex-1">
        <div class="w-12 h-12 bg-gradient-to-br from-[#674c1d]/20 to-[#8b6f2f]/20 
                    rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-[#674c1d]">...</svg>
        </div>
        <div class="flex-1">
            <h3 class="font-semibold text-gray-900">Title</h3>
            <p class="text-sm text-gray-600">Subtitle</p>
            <p class="text-xs text-gray-500 mt-1">Additional info</p>
        </div>
    </div>
    
    <!-- Right Side -->
    <div class="flex items-center space-x-2">
        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
            Status
        </span>
        <a href="#" class="p-2 text-[#674c1d] hover:bg-[#674c1d]/10 rounded-lg transition-colors">
            <svg class="w-5 h-5">...</svg>
        </a>
    </div>
</div>
```

### Quick Action Card
```html
<a href="#" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 
                   rounded-xl p-4 transition-all border border-white/30">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-[#674c1d]">...</svg>
        </div>
        <div>
            <p class="text-white text-sm font-medium">Title</p>
            <p class="text-white/80 text-xs">Subtitle</p>
        </div>
    </div>
</a>
```

---

## 💻 JAVASCRIPT PATTERNS

### Currency Formatting
```javascript
// Format input to Indonesian currency (tanpa Rp)
function formatCurrency(input) {
    let value = input.value.replace(/[^\d]/g, '');
    if (value) {
        input.value = new Intl.NumberFormat('id-ID').format(value);
    }
}

// Usage in HTML
<input type="text" oninput="formatCurrency(this)">

// Parse formatted currency back to number
function parseCurrency(formattedValue) {
    return parseInt(formattedValue.replace(/[^\d]/g, '')) || 0;
}
```

### PIN Input Restriction
```javascript
// Restrict input to numbers only
<input type="text" 
       maxlength="6" 
       pattern="[0-9]*" 
       inputmode="numeric"
       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
```

### Form Validation
```javascript
function validateForm() {
    const nominal = document.getElementById('nominal').value.replace(/[^\d]/g, '');
    
    if (!nominal || parseInt(nominal) < 10000) {
        alert('Nominal minimal Rp 10.000');
        return false;
    }
    
    return true;
}
```

### Modal Control
```javascript
// Show modal
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

// Hide modal
function hideModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close on outside click
document.getElementById('modal-id').addEventListener('click', function(e) {
    if (e.target === this) {
        hideModal('modal-id');
    }
});
```

### File Preview
```javascript
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.getElementById('preview');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Usage
<input type="file" onchange="previewImage(this)">
<img id="preview" class="hidden max-w-full max-h-48 rounded-lg">
```

### Dynamic Form Fields
```javascript
let fieldCount = 0;

function addField() {
    fieldCount++;
    const container = document.getElementById('container');
    
    const div = document.createElement('div');
    div.className = 'border-2 border-gray-200 rounded-xl p-4 space-y-3';
    div.innerHTML = `
        <div class="flex items-center justify-between">
            <label class="text-sm font-semibold text-gray-700">Field ${fieldCount}</label>
            <button type="button" onclick="this.parentElement.parentElement.remove(); fieldCount--;" 
                    class="text-red-600 hover:text-red-700">
                <svg class="w-5 h-5">...</svg>
            </button>
        </div>
        <input type="text" name="field[]" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
    `;
    
    container.appendChild(div);
}
```

---

## 📱 RESPONSIVE DESIGN

### Breakpoint System (Tailwind)
```css
/* Mobile First Approach */
sm: 640px   /* Small devices (landscape phones) */
md: 768px   /* Medium devices (tablets) */
lg: 1024px  /* Large devices (desktops) */
xl: 1280px  /* Extra large devices (large desktops) */
2xl: 1536px /* 2X Extra large devices */
```

### Responsive Grid
```html
<!-- Statistics Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Cards -->
</div>

<!-- Two Column Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Content -->
</div>

<!-- Three Column Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <!-- Items -->
</div>
```

### Responsive Typography
```html
<h1 class="text-2xl md:text-3xl lg:text-4xl font-bold">
    Responsive Heading
</h1>

<p class="text-sm md:text-base lg:text-lg">
    Responsive paragraph
</p>
```

### Responsive Spacing
```html
<div class="p-4 md:p-6 lg:p-8">
    <!-- Content with responsive padding -->
</div>

<div class="mx-4 md:mx-6 lg:mx-auto lg:max-w-7xl">
    <!-- Container with responsive margin -->
</div>
```

### Mobile Navigation
```html
<!-- Bottom Navigation (mobile only) -->
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 
            md:hidden z-40">
    <div class="grid grid-cols-4 gap-1 p-2">
        <!-- Nav items -->
    </div>
</nav>

<!-- Sidebar Navigation (desktop only) -->
<aside class="hidden md:block w-64 fixed left-0 top-0 h-screen">
    <!-- Sidebar content -->
</aside>
```

### Responsive Table
```html
<div class="overflow-x-auto -mx-4 md:mx-0">
    <table class="w-full min-w-full">
        <!-- Table content -->
    </table>
</div>
```

---

## 📋 BEST PRACTICES

### 1. Consistency
- ✅ Selalu gunakan color palette yang sama
- ✅ Gunakan spacing scale yang konsisten (4, 6, 8, dll)
- ✅ Icon size harus proporsional dengan container

### 2. Accessibility
- ✅ Tambahkan label pada semua form input
- ✅ Gunakan semantic HTML (button, not div with click)
- ✅ Ensure sufficient color contrast (WCAG AA minimum)
- ✅ Tambahkan aria-label pada icon-only buttons

### 3. Performance
- ✅ Lazy load images yang besar
- ✅ Minify CSS/JS untuk production
- ✅ Optimize images sebelum upload
- ✅ Gunakan CDN untuk assets statis

### 4. Mobile First
- ✅ Design untuk mobile terlebih dahulu
- ✅ Test pada berbagai device sizes
- ✅ Pastikan touch targets minimal 44x44px
- ✅ Hindari hover-only interactions

### 5. Code Organization
- ✅ Gunakan components untuk reusable UI
- ✅ Pisahkan styles ke @stack('styles')
- ✅ Pisahkan scripts ke @stack('scripts')
- ✅ Comment code yang complex

---

## 🎯 IMPLEMENTATION CHECKLIST

### Untuk Setiap Halaman Baru:
- [ ] Extend dari layout yang sesuai (`layouts.nasabah` atau `layouts.admin`)
- [ ] Set `@section('title')`
- [ ] Gunakan color palette yang konsisten
- [ ] Implement responsive design (mobile first)
- [ ] Tambahkan empty states untuk lists/tables
- [ ] Tambahkan loading states untuk async operations
- [ ] Tambahkan error handling & validation
- [ ] Test pada berbagai screen sizes
- [ ] Validate accessibility (contrast, labels, keyboard navigation)
- [ ] Add comments untuk complex logic

### Untuk Setiap Form:
- [ ] Labels jelas untuk semua inputs
- [ ] Helper text untuk panduan user
- [ ] Validation messages yang informatif
- [ ] Focus states yang jelas
- [ ] Disabled states yang visible
- [ ] Submit button dengan loading state
- [ ] Prevent double submission
- [ ] Clear error messages

### Untuk Setiap Modal:
- [ ] Close button yang visible
- [ ] Close on outside click
- [ ] Close on ESC key
- [ ] Focus trap dalam modal
- [ ] Auto focus pada input pertama
- [ ] Loading state untuk async actions

---

**END OF DOCUMENT**

*Last Updated: {{ now()->format('d F Y') }}*
*Version: 1.0*
