<x-layout>
    <x-slot name="title">Daftar Barang</x-slot>
    <x-slot name="header">Daftar Barang Inventaris</x-slot>
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Barang Inventaris</h2>
        <button onclick="openCreateModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-plus mr-2"></i>Tambah Barang
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Scan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($items as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->category ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->location ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($item->condition === 'baik') bg-green-100 text-green-800
                                @elseif($item->condition === 'rusak ringan') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($item->condition) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->total_scans }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.items.show', $item) }}" class="text-blue-600 hover:text-blue-900" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button onclick="openEditModal({{ $item->id }}, '{{ $item->name }}', '{{ $item->code }}', '{{ $item->category }}', '{{ $item->description }}', '{{ $item->location }}', '{{ $item->condition }}', {{ $item->quantity }}, {{ $item->price ?? 'null' }}, '{{ $item->purchase_date?->format('Y-m-d') ?? '' }}')" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="{{ route('admin.items.print', $item) }}" target="_blank" class="text-purple-600 hover:text-purple-900" title="Cetak QR Code">
                                <i class="fas fa-print"></i>
                            </a>
                            <a href="{{ route('admin.items.barcode', $item) }}" class="text-green-600 hover:text-green-900" title="Download QR Code">
                                <i class="fas fa-download"></i>
                            </a>
                            <button onclick="confirmDeleteItem({{ $item->id }}, '{{ $item->name }}')" class="text-red-600 hover:text-red-900" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data barang</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>

<!-- Modal Create Item -->
<x-modal id="modalCreateItem" title="Tambah Barang Baru" size="max-w-4xl">
    <form action="{{ route('admin.items.store') }}" method="POST">
        @csrf
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                <div>
                    <p class="text-sm font-semibold text-blue-800">Kode Barang Otomatis</p>
                    <p class="text-sm text-blue-600">Kode barang akan di-generate otomatis dengan format ITM001, ITM002, dan seterusnya. QR Code juga akan dibuat secara otomatis.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Nama Barang <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Kategori</label>
                <input type="text" name="category" value="{{ old('category') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Contoh: Elektronik, Furniture, Alat Tulis</p>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Lokasi</label>
                <input type="text" name="location" value="{{ old('location') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Kondisi <span class="text-red-500">*</span></label>
                <select name="condition" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="baik" {{ old('condition') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak ringan" {{ old('condition') == 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak berat" {{ old('condition') == 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Jumlah <span class="text-red-500">*</span></label>
                <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price') }}" min="0" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Tanggal Pembelian</label>
                <input type="date" name="purchase_date" value="{{ old('purchase_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
            <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <button type="button" onclick="closeModal('modalCreateItem')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold">
                Batal
            </button>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                <i class="fas fa-save mr-2"></i>Simpan
            </button>
        </div>
    </form>
</x-modal>

<!-- Modal Edit Item -->
<x-modal id="modalEditItem" title="Edit Barang" size="max-w-4xl">
    <form id="formEditItem" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Nama Barang <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="edit_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Kode Barang</label>
                <input type="text" id="edit_code_display" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed" readonly>
                <p class="text-xs text-gray-500 mt-1">Kode barang tidak dapat diubah</p>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Kategori</label>
                <input type="text" name="category" id="edit_category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Lokasi</label>
                <input type="text" name="location" id="edit_location" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Kondisi <span class="text-red-500">*</span></label>
                <select name="condition" id="edit_condition" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="baik">Baik</option>
                    <option value="rusak ringan">Rusak Ringan</option>
                    <option value="rusak berat">Rusak Berat</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Jumlah <span class="text-red-500">*</span></label>
                <input type="number" name="quantity" id="edit_quantity" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Harga (Rp)</label>
                <input type="number" name="price" id="edit_price" min="0" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Tanggal Pembelian</label>
                <input type="date" name="purchase_date" id="edit_purchase_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
            <textarea name="description" id="edit_description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <button type="button" onclick="closeModal('modalEditItem')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold">
                Batal
            </button>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                <i class="fas fa-save mr-2"></i>Update
            </button>
        </div>
    </form>
</x-modal>

<script>
// Cache buster: {{ now() }}
const itemData = @json($items->items());

// Show success message if exists
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
    });
@endif

function openCreateModal() {
    openModal('modalCreateItem');
}

function confirmDeleteItem(id, name) {
    Swal.fire({
        title: 'Hapus Barang?',
        html: 'Apakah Anda yakin ingin menghapus barang:<br><strong>' + name + '</strong>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then(function(result) {
        if (result.isConfirmed) {
            // Create and submit form using a different approach
            var form = document.createElement('form');
            form.setAttribute('method', 'POST');
            form.setAttribute('action', '/admin/items/' + id);
            form.style.display = 'none';
            
            var token = document.createElement('input');
            token.setAttribute('type', 'hidden');
            token.setAttribute('name', '_token');
            token.setAttribute('value', '{{ csrf_token() }}');
            form.appendChild(token);
            
            var method = document.createElement('input');
            method.setAttribute('type', 'hidden');
            method.setAttribute('name', '_method');
            method.setAttribute('value', 'DELETE');
            form.appendChild(method);
            
            document.body.appendChild(form);
            
            // Use requestSubmit if available, otherwise use submit
            if (form.requestSubmit) {
                form.requestSubmit();
            } else if (HTMLFormElement.prototype.submit) {
                HTMLFormElement.prototype.submit.call(form);
            } else {
                form.submit();
            }
        }
    });
}

function openEditModal(id, name, code, category, description, location, condition, quantity, price, purchase_date) {
    const item = itemData.find(i => i.id === id);
    if (!item) return;
    
    document.getElementById('formEditItem').action = `/admin/items/${id}`;
    document.getElementById('edit_name').value = item.name;
    document.getElementById('edit_code_display').value = item.code;
    document.getElementById('edit_category').value = item.category || '';
    document.getElementById('edit_location').value = item.location || '';
    document.getElementById('edit_condition').value = item.condition;
    document.getElementById('edit_quantity').value = item.quantity;
    document.getElementById('edit_price').value = item.price || '';
    document.getElementById('edit_purchase_date').value = item.purchase_date || '';
    document.getElementById('edit_description').value = item.description || '';
    
    openModal('modalEditItem');
}
</script>
</x-layout>
